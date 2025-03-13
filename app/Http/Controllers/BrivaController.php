<?php

namespace App\Http\Controllers;

use App\Helpers\SignatureHelper;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\SupportBriva;
use App\Services\BrivaServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BrivaController extends Controller
{

    public function simulateSignature()
    {
        // Simulasi response untuk menghindari timeout
        $clientId = env('BRI_CLIENT_ID');

        // Generate timestamp otomatis sesuai format BRI
        $timestamp = now()->format('Y-m-d\TH:i:s.v\Z');

        Http::fake([
            route('bri.access_token') => Http::response([
                "accessToken" => SignatureHelper::generateSignature($clientId, $timestamp, true),
                "tokenType"   => "Bearer",
                "expiresIn"   => "900"
            ], 200),
        ]);

        // Buat signature menggunakan private key
        $signature = SignatureHelper::generateSignature($clientId, $timestamp);

        // Request langsung ke endpoint yang kita buat di web.php
        $response = Http::withHeaders([
            'X-CLIENT-KEY' => $clientId,
            'X-TIMESTAMP'  => $timestamp,
            'X-SIGNATURE'  => $signature,
            'Content-Type' => 'application/json',
        ])->post(route('bri.access_token'), [
            "grantType" => "client_credentials"
        ]);

        $data = $response->json();

        // Tambahkan waktu expired token
        $data['expiresAt'] = now()->addSeconds($data['expiresIn'])->timestamp;

        //verifikasi signature
        if (!SignatureHelper::verifySignature($clientId, $timestamp, $signature)) {
            return response()->json([
                'responseCode' => '4010003',
                'responseMessage' => 'Invalid Signature'
            ], 401);
        }

        // Simpan ke file
        Storage::put('token.json', json_encode($data, JSON_PRETTY_PRINT));

        return response()->json($response->json(), 200);
    }

    public function getAccessToken(): JsonResponse
    {
        $clientId  = env('BRI_CLIENT_ID');
        // Generate timestamp otomatis sesuai format BRI
        $timestamp = now()->format('Y-m-d\TH:i:s.v\Z');
        // Buat signature menggunakan private key
        $signature = SignatureHelper::generateSignature($clientId, $timestamp);

        // Validasi Signature
        if (!$timestamp || !$signature || !SignatureHelper::verifySignature($clientId, $timestamp, $signature)) {
            return response()->json([
                'responseCode'    => '4010003',
                'responseMessage' => 'Invalid Signature'
            ], 401);
        }

        return response()->json([
            "accessToken" => SignatureHelper::generateSignature($clientId, $timestamp, true),
            "tokenType"   => "Bearer",
            "expiresIn"   => "900"
        ], 200);
    }


    public function generateSignatureV2(Request $request)
    {
        // Validasi request
        $validated = $request->validate([
            'method'      => 'required|string',
            'endpoint'    => 'required|string',
            'accessToken' => 'required|string',
            'body'        => 'nullable|string',
            'timestamp'   => 'required|string',
        ]);

        // Generate Signature v2
        $signature = SignatureHelper::generateSignatureV2(
            $validated['method'],
            $validated['endpoint'],
            $validated['accessToken'],
            $validated['body'] ?? '',
            $validated['timestamp']
        );

        return response()->json([
            'signature' => $signature
        ]);
    }


    public function inquiry(Request $request)
    {

        // Ambil token dari storage
        if (!Storage::exists('token.json')) {
            return response()->json([
                'responseCode'    => '4012401',
                'responseMessage' => 'Invalid Token'
            ], 401);
        }

        $tokenData = json_decode(Storage::get('token.json'), true);
        $storedToken = $tokenData['accessToken'] ?? null;
        $expiresAt = $tokenData['expiresAt'] ?? 0;

        // Ambil token dari header
        $authHeader = $request->header('Authorization');

        if (!$authHeader || $authHeader !== "Bearer $storedToken") {
            return response()->json([
                'responseCode'    => '4012401',
                'responseMessage' => 'Invalid Token'
            ], 401);
        }

        // Cek apakah token sudah expired
        if (now()->timestamp > $expiresAt) {
            return response()->json([
                'responseCode'    => '4012402',
                'responseMessage' => 'Token Expired'
            ], 401);
        }

        // Validasi Input
        $request->validate([
            'virtualAccountNo' => 'required'
        ]);

        // Cari VA yang sudah dibuat di function payment
        $briva = SupportBriva::where('va', $request->virtualAccountNo)->first();

        // Jika VA tidak ditemukan
        if (!$briva) {
            return response()->json([
                'responseCode' => '4042419',
                'responseMessage' => 'Invalid Bill/Virtual Account'
            ], 404);
        }

        // Cari Order Berdasarkan order_id dari VA
        $order = Order::find($briva->order_id);

        if (!$order) {
            return response()->json([
                'responseCode' => '4042414',
                'responseMessage' => 'Order Not Found'
            ], 404);
        }

        if ($order->status == 100) {
            return response()->json([
                'responseCode' => '4042414',
                'responseMessage' => 'Paid Bill'
            ], 404);
        }

        // Data Response Simulasi menggunakan VA yang sudah dibuat
        $response = [
            "responseCode" => "2002400",
            "responseMessage" => "Successful",
            "virtualAccountData" => [
                "partnerServiceId" => "77777",
                "customerNo" => str_pad($order->user_id, 13, '0', STR_PAD_LEFT),
                "virtualAccountNo" => $briva->va,
                "virtualAccountName" => $order->user->name,
                "inquiryRequestId" => (string) Str::uuid(),
                "totalAmount" => [
                    "value" => number_format($order->total_price, 2, '.', ''),
                    "currency" => "IDR"
                ],
                "inquiryStatus" => "00",
                "inquiryReason" => [
                    "english" => "Success",
                    "indonesia" => "Sukses"
                ],
                "additionalInfo" => [
                    "idApp" => "TEST",
                    "info1" => "info 1 harus diisi"
                ]
            ]
        ];

        return response()->json($response, 200);
    }

    public function payment(Request $request)
    {
        // Ambil token dari storage
        if (!Storage::exists('token.json')) {
            return response()->json([
                'responseCode'    => '4012501',
                'responseMessage' => 'Invalid Token'
            ], 401);
        }

        $tokenData = json_decode(Storage::get('token.json'), true);
        $storedToken = $tokenData['accessToken'] ?? null;
        $expiresAt = $tokenData['expiresAt'] ?? 0;

        // Ambil token dari header
        $authHeader = $request->header('Authorization');

        if (!$authHeader || $authHeader !== "Bearer $storedToken") {
            return response()->json([
                'responseCode'    => '4012501',
                'responseMessage' => 'Invalid Token'
            ], 401);
        }

        // Cek apakah token sudah expired
        if (now()->timestamp > $expiresAt) {
            return response()->json([
                'responseCode'    => '4012502',
                'responseMessage' => 'Token Expired'
            ], 401);
        }

        // Validasi Input
        $request->validate([
            'virtualAccountNo' => 'required',
            'paidAmount.value' => 'required|numeric',
            'paidAmount.currency' => 'required|string'
        ]);

        // Cari VA yang sudah dibuat di function payment
        $briva = SupportBriva::where('va', $request->virtualAccountNo)->first();

        // Jika VA tidak ditemukan
        if (!$briva) {
            return response()->json([
                'responseCode' => '4042519',
                'responseMessage' => 'Invalid Bill/Virtual Account'
            ], 404);
        }

        // Cari Order Berdasarkan order_id dari VA
        $order = Order::find($briva->order_id);

        if (!$order) {
            return response()->json([
                'responseCode' => '4042514',
                'responseMessage' => 'Order Not Found'
            ], 404);
        }

        if ($order->status == 100) {
            return response()->json([
                'responseCode' => '4042514',
                'responseMessage' => 'Paid Bill'
            ], 404);
        }

        // Cek apakah nominal yang dibayar sesuai dengan total harga order
        if ($request->paidAmount['value'] != $order->total_price) {
            return response()->json([
                'responseCode' => '4042513',
                'responseMessage' => 'Invalid Amount'
            ], 404);
        }

        // Mulai DB Transaction
        DB::beginTransaction();
        try {
            // Update Payment Status
            $briva_update = $briva->update([
                'payment_time' => now(),
                'latest_inquiry' => now()
            ]);

            if ($briva_update) {
                //update status order
                $order_update = $order->update([
                    'status' => 100,
                    'approval_date' => now(),
                ]);

                //insert order detail
                if ($order_update) {
                    $order_package = OrderPackage::where('order_id', $order->id)->whereNull('deleted_at')->get();
                    $order_detail = [];

                    foreach ($order_package as $item) {
                        if ($item->package) {
                            if ($item->package->packageTest->isNotEmpty()) {
                                foreach ($item->package->packageTest as $packageTest) {
                                    $order_detail[] = [
                                        'order_id' => $order->id,
                                        'package_id' => $item->package_id,
                                        'quiz_id' => $packageTest->quiz->id ?? null
                                    ];
                                }
                            } else {
                                $order_detail[] = [
                                    'order_id' => $order->id,
                                    'package_id' => $item->package_id,
                                    'quiz_id' => null
                                ];
                            }
                        }
                    }
                    OrderDetail::insert($order_detail);
                }
            }
            // Commit Transaction
            DB::commit();
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return response()->json([
                'responseCode' => '500',
                'responseMessage' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }

        // Data Response
        $response = [
            "responseCode" => "2002500",
            "responseMessage" => "Successful",
            "virtualAccountData" => [
                "partnerServiceId" => "77777",
                "customerNo" => str_pad($order->user_id, 13, '0', STR_PAD_LEFT),
                "virtualAccountNo" => $briva->va,
                "virtualAccountName" => $order->user->name,
                "paymentRequestId" => (string) Str::uuid(),
                "paidAmount" => [
                    "value" => number_format($order->total_price, 2, '.', ''),
                    "currency" => $request->paidAmount['currency']
                ],
                "paymentFlagStatus" => "00",
                "paymentFlagReason" => [
                    "english" => "Success",
                    "indonesia" => "Sukses"
                ]
            ],
            "additionalInfo" => [
                "idApp" => "TEST",
                "passApp" => "b7aee423dc7489dfa868426e5c950c677925f3b9",
                "info1" => "info 1 diisi"
            ]
        ];

        return response()->json($response, 200);
    }

    // Encrypt Decrypt signature
    // public function simulateSignature(Request $request)
    // {

    //     $clientId = env('BRI_CLIENT_ID');

    //     // Generate timestamp otomatis
    //     $timestamp = now()->format('Y-m-d\TH:i:s.v\Z');

    //     // Buat signature menggunakan private key
    //     $signature = SignatureHelper::generateSignature($clientId, $timestamp);

    //     // Verifikasi signature menggunakan public key
    //     $isValid = SignatureHelper::verifySignature($clientId, $timestamp, $signature);

    //     // Response JSON
    //     return response()->json([
    //         'client_id' => env('BRI_CLIENT_ID'),
    //         'timestamp' => $timestamp,
    //         'signature' => $signature,
    //         'is_valid' => $isValid
    //     ], 200, ['Content-Type' => 'application/json']);
    // }

    // public function getToken()
    // {
    //     try {
    //         $response = BrivaServices::getToken();
    //         return response()->json($response);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


    // public function getAccessToken()
    // {
    //     $clientId = "7505935e79b645f993f86f3f11eaecc0";
    //     $privateKey = env('PRIVATE_KEY');

    //     // Panggil helper untuk generate signature
    //     $signatureData = SignatureHelper::generateSignature($clientId, $privateKey);

    //     // Signature dan Timestamp
    //     $xSignature = $signatureData['signature'];
    //     $xTimestamp = $signatureData['timestamp'];

    //     // Data yang dikirim
    //     $postData = json_encode(["grantType" => "client_credentials"]);

    //     // Kirimkan HTTP Request ke API
    //     $url = "http://127.0.0.1:8000/snap/v1.0/access-token/b2b";
    //     $headers = [
    //         "X-SIGNATURE: $xSignature",
    //         "X-CLIENT-KEY: $clientId",
    //         "X-TIMESTAMP: $xTimestamp",
    //         "Content-Type: application/json"
    //     ];

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Body dengan grantType
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout 10 detik


    //     $response = curl_exec($ch);
    //     $error = curl_error($ch);
    //     curl_close($ch);

    //     if ($error) {
    //         return response()->json(["error" => $error]);
    //     }

    //     return response()->json(["response" => json_decode($response, true)]);
    // }



    // public function generateSignature()
    // {
    //     $clientId = "7505935e79b645f993f86f3f11eaecc0";

    //     // Ambil private key dari .env
    //     $privateKey = env('PRIVATE_KEY');

    //     if (!$privateKey) {
    //         return response()->json(['error' => 'Private Key not found'], 500);
    //     }

    //     date_default_timezone_set('UTC');
    //     $xTimestamp = gmdate("Y-m-d\TH:i:s.000\Z");

    //     $stringToSign = $clientId . "|" . $xTimestamp;

    //     openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
    //     $xSignature = base64_encode($signature);

    //     return response()->json([
    //         'Signature' => $xSignature,
    //         'X-Timestamp' => $xTimestamp
    //     ]);
    // }


    // inquiry simulasi bisa
    // public function inquiry(Request $request)
    // {
    //     // Ambil data dari request
    //     $data = $request->all();

    //     // Validasi parameter yang diperlukan
    //     if (!isset($data['partnerServiceId']) || !isset($data['customerNo']) || !isset($data['virtualAccountNo']) || !isset($data['inquiryRequestId'])) {
    //         return response()->json([
    //             'responseCode' => '4002401', // Invalid Field Format
    //             'responseMessage' => 'Bad Request',
    //             'errorDetails' => [
    //                 'english' => 'Invalid Field Format',
    //                 'indonesia' => 'Format Field Tidak Valid',
    //             ]
    //         ]);
    //     }

    //     // Logika jika request tidak valid
    //     if (empty($data['partnerServiceId']) || empty($data['customerNo']) || empty($data['virtualAccountNo']) || empty($data['inquiryRequestId'])) {
    //         return response()->json([
    //             'responseCode' => '4002402', // Invalid Mandatory Field
    //             'responseMessage' => 'Bad Request',
    //             'errorDetails' => [
    //                 'english' => 'Invalid Mandatory Field',
    //                 'indonesia' => 'Field Wajib Tidak Valid',
    //             ]
    //         ]);
    //     }

    //     // Simulasi pengecekan status inquiry atau kondisi lainnya
    //     // Misalnya jika data tidak ditemukan, atau akun tidak valid
    //     if ($data['virtualAccountNo'] === 'invalidAccount') {
    //         return response()->json([
    //             'responseCode' => '4042401', // Transaction Not Found
    //             'responseMessage' => 'Failed',
    //             'errorDetails' => [
    //                 'english' => 'Transaction Not Found',
    //                 'indonesia' => 'Transaksi Tidak Ditemukan',
    //             ]
    //         ]);
    //     }

    //     // Bangun struktur response jika tidak ada error
    //     $response = [
    //         'responseCode' => '2002400', // Response code sukses
    //         'responseMessage' => 'Successful',
    //         'virtualAccountData' => [
    //             'partnerServiceId' => trim($data['partnerServiceId']),
    //             'customerNo' => $data['customerNo'],
    //             'virtualAccountNo' => $data['virtualAccountNo'],
    //             'virtualAccountName' => 'John Doe', // Nama pengguna
    //             'inquiryRequestId' => $data['inquiryRequestId'],
    //             'totalAmount' => [
    //                 'value' => '200000.00', // Total amount yang ditentukan
    //                 'currency' => 'IDR',
    //             ],
    //             'inquiryStatus' => '00', // Status inquiry
    //             'inquiryReason' => [
    //                 'english' => 'Success',
    //                 'indonesia' => 'Sukses',
    //             ],
    //             'additionalInfo' => [
    //                 'idApp' => 'TEST',
    //                 'info1' => 'info 1 harus diisi',
    //             ]
    //         ]
    //     ];

    //     // Kembalikan response sukses
    //     return response()->json($response);
    // }


    // payment simulasi bisa
    // public function payment(Request $request)
    // {
    //     // Ambil data dari request
    //     $data = $request->all();

    //     // Validasi input
    //     if (empty($data['partnerServiceId'])) {
    //         return response()->json([
    //             'responseCode' => '4002501', // Invalid Field Format
    //             'responseMessage' => 'Invalid Field Format partnerServiceId',
    //         ]);
    //     }

    //     if (empty($data['customerNo'])) {
    //         return response()->json([
    //             'responseCode' => '4002502', // Invalid Mandatory Field customerNo
    //             'responseMessage' => 'Invalid Mandatory Field customerNo',
    //         ]);
    //     }



    //     // Bangun struktur response sesuai dengan format yang diberikan
    //     $response = [
    //         'responseCode' => '2002500', // Success
    //         'responseMessage' => 'Successful', // Response message
    //         'virtualAccountData' => [
    //             'partnerServiceId' => trim($data['partnerServiceId']),
    //             'customerNo' => $data['customerNo'],
    //             'virtualAccountNo' => $data['virtualAccountNo'],
    //             'virtualAccountName' => 'John Doe', // Nama yang statis, bisa diganti sesuai logika
    //             'paymentRequestId' => $data['paymentRequestId'],
    //             'paidAmount' => [
    //                 'value' => '10001.00', // Jumlah yang dibayar
    //                 'currency' => 'IDR',
    //             ],
    //             'paymentFlagStatus' => '00', // Status pembayaran (00 untuk sukses)
    //             'paymentFlagReason' => [
    //                 'english' => 'Success',
    //                 'indonesia' => 'Sukses',
    //             ]
    //         ],
    //         'additionalInfo' => [
    //             'idApp' => 'TEST',
    //             'passApp' => $data['additionalInfo']['passApp'], // Ambil passApp dari request
    //             'info1' => 'info 1 diisi', // Info tambahan
    //         ]
    //     ];

    //     // Kembalikan response dalam format JSON
    //     return response()->json($response);
    // }









    // protected $brivaService;

    // public function __construct(BrivaServices $brivaService)
    // {
    //     $this->brivaService = $brivaService;
    // }

    // public function inquiry(Request $request)
    // {
    //     $vaNumber = $request->input('va_number');
    //     $response = $this->brivaService->inquiry($vaNumber);

    //     // Debugging untuk memastikan request benar
    //     Log::info('BRIVA Inquiry Request:', ['va_number' => $vaNumber]);
    //     Log::info('BRIVA Inquiry Response:', (array) $response);

    //     return response()->json($response);
    // }

    // public function payment(Request $request)
    // {
    //     $vaNumber = $request->input('va_number');
    //     $amount = $request->input('amount');
    //     $response = $this->brivaService->makePayment($vaNumber, $amount);
    //     return response()->json($response);
    // }
}
