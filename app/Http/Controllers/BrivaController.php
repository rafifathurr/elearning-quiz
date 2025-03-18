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
use Illuminate\Support\Facades\Validator;

class BrivaController extends Controller
{


    public function generateSignature(Request $request): JsonResponse
    {
        // Ambil data dari request atau gunakan default untuk testing
        $clientId  = $request->input('clientId', env('BRI_CLIENT_ID'));
        $timestamp = now()->format('Y-m-d\TH:i:s.v\Z'); // Timestamp sesuai format BRI

        try {
            // Generate signature dengan fungsi yang sudah dibuat
            $signature = SignatureHelper::generateSignature($clientId, $timestamp);

            return response()->json([
                'clientId'  => $clientId,
                'timestamp' => $timestamp,
                'signature' => $signature
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'responseCode'    => '5001001',
                'responseMessage' => 'Signature Generation Failed',
                'error'           => $e->getMessage()
            ], 500);
        }
    }


    public function getAccessToken(Request $request): JsonResponse
    {
        // Ambil data dari request header
        $clientId  = $request->header('X-CLIENT-KEY');
        $timestamp = $request->header('X-TIMESTAMP');
        $signature = $request->header('X-SIGNATURE');

        // Log request header yang diterima
        Log::info("Received Access Token Request", [
            'clientId'  => $clientId,
            'timestamp' => $timestamp,
            'signature' => $signature
        ]);
        // Validasi jika header tidak ada
        if (!$clientId || !$timestamp || !$signature) {
            Log::warning("Missing required headers in Access Token request");
            return response()->json([
                'responseCode'    => '4001001',
                'responseMessage' => 'Missing required headers'
            ], 400);
        }

        // Validasi Signature dengan SHA256withRSA
        if (!SignatureHelper::verifySignature($clientId, $timestamp, $signature)) {
            Log::warning("Invalid Signature during Access Token request", [
                'clientId'  => $clientId,
                'timestamp' => $timestamp,
                'signature' => $signature
            ]);
            return response()->json([
                'responseCode'    => '4010003',
                'responseMessage' => 'Invalid Signature'
            ], 401);
        }

        // Generate Token
        $accessToken = hash('sha256', Str::random(40));
        $expiresIn   = 900; // 15 menit
        $expiresAt   = now()->addSeconds($expiresIn)->timestamp;

        // Data yang disimpan di file
        $dataToSave = [
            "accessToken" => $accessToken,
            "tokenType"   => "Bearer",
            "expiresIn"   => $expiresIn,
            "expiresAt"   => $expiresAt // Hanya disimpan di file
        ];

        // Simpan ke file storage/token.json
        Storage::put('token.json', json_encode($dataToSave, JSON_PRETTY_PRINT));

        Log::info("Access Token Generated Successfully", [
            'accessToken' => $accessToken,
            'expiresIn'   => $expiresIn
        ]);

        // Data yang dikembalikan ke response (tanpa expiresAt)
        $responseData = [
            "accessToken" => $accessToken,
            "tokenType"   => "Bearer",
            "expiresIn"   => $expiresIn
        ];

        return response()->json($responseData, 200);
    }


    public function generateSignatureV2(Request $request)
    {
        if (!Storage::exists('token.json')) {
            Log::error("Token not found");
            return response()->json([
                'responseCode'    => '4012401',
                'responseMessage' => 'Invalid Token'
            ], 401);
        }

        $tokenData = json_decode(Storage::get('token.json'), true);
        $storedToken = $tokenData['accessToken'] ?? null;

        $httpMethod = "POST";
        $endpointUrl = "/snap/v1.0/transfer-va/inquiry";
        $accessToken = $storedToken; // Sesuai header Authorization
        $timestamp = now()->format('Y-m-d\TH:i:sP');

        $requestBody = json_encode([
            "partnerServiceId" => "19114",
            "customerNo" => "0000000025144",
            "virtualAccountNo" => "191140000000025144",
            "trxDateInit" => "2025-03-17T14:15:07+07:00",
            "channelCode" => 1,
            "sourceBankCode" => "002",
            "passApp" => "b7aee423dc7489dfa868426e5c950c677925f3b9",
            "inquiryRequestId" => "4c7d1402-6217-48a9-b31c-c8a84e8f90b2",
            "additionalInfo" => [
                "idApp" => "TEST"
            ]
        ], JSON_UNESCAPED_SLASHES);

        try {
            // **Menghasilkan signature dan payload signature**
            $signatureData = SignatureHelper::generateSignatureV2($httpMethod, $endpointUrl, $accessToken, $requestBody, $timestamp);

            return response()->json([
                'xtimestamp' => $timestamp,
                'signature' => $signatureData['xSignature'], // Mengambil dari array hasil function
                'payload-signature' => $signatureData['xPayload'],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'responseCode'    => '5001001',
                'responseMessage' => 'Signature Generation Failed',
                'error'           => $e->getMessage()
            ], 500);
        }
    }



    public function inquiry(Request $request)
    {
        Log::info("Inquiry Request Received", ['request' => $request->all()]);
        Log::info("Inquiry Request Headers", ['headers' => $request->headers->all()]); // Logging header request

        // Ambil token dari storage
        if (!Storage::exists('token.json')) {
            Log::error("Token not found");
            return response()->json([
                'responseCode'    => '4012401',
                'responseMessage' => 'Invalid Token'
            ], 401);
        }

        $tokenData = json_decode(Storage::get('token.json'), true);
        $storedToken = $tokenData['accessToken'] ?? null;
        $expiresAt = $tokenData['expiresAt'] ?? 0;


        // Cek apakah token sudah expired
        if (now()->timestamp > $expiresAt) {
            Log::warning("Token Expired", ['tokenExpiresAt' => $expiresAt]);
            return response()->json([
                'responseCode'    => '4012402',
                'responseMessage' => 'Token Expired'
            ], 401);
        }

        // Validasi Input
        $validator = Validator::make($request->all(), [
            'partnerServiceId'   => 'required|string|regex:/^\d+$/', // Harus angka
            'customerNo'         => 'required|string|regex:/^\d+$/',
            'virtualAccountNo'   => 'required|string|regex:/^\d+$/', // Harus angka
            'trxDateInit'        => 'required|date_format:Y-m-d\TH:i:sP', // ISO 8601 format
            'channelCode'        => 'required|integer',
            'sourceBankCode'     => 'required|string|digits:3', // Harus 3 digit angka
            'passApp'            => 'required|string',
            'inquiryRequestId'   => 'required|string|uuid', // Harus UUID format
        ]);

        if ($validator->fails()) {
            $errors = $validator->failed();

            $missingFields = [];
            $invalidFields = [];

            foreach ($errors as $field => $rules) {
                if (isset($rules['Required'])) {
                    $missingFields[] = $field;
                } else {
                    $invalidFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                return response()->json([
                    'responseCode'    => '400xx02',
                    'responseMessage' => "Missing Mandatory Field {" . implode(', ', $missingFields) . "}"
                ], 400);
            }

            if (!empty($invalidFields)) {
                return response()->json([
                    'responseCode'    => '400xx01',
                    'responseMessage' => "Invalid Field Format {" . implode(', ', $invalidFields) . "}"
                ], 400);
            }
        }

        // Buat request header
        $headers = [
            'Authorization'  => "Bearer $storedToken",
            'Content-Type'   => 'application/json',
        ];


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
        if ($request->customerNo != $briva->customer_no) {
            return response()->json([
                'responseCode' => '4042401',
                'responseMessage' => 'Invalid Customer No'
            ], 404);
        }

        // Data Response Simulasi menggunakan VA yang sudah dibuat
        $response = [
            "responseCode" => "2002400",
            "responseMessage" => "Successful",
            "virtualAccountData" => [
                "partnerServiceId" => "19114",
                "customerNo" => $briva->customer_no,
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

        return response()->json($response, 200, $headers);
    }

    public function payment(Request $request)
    {
        Log::info("Payment Request Received", ['request' => $request->all()]);
        Log::info("Payment Request Headers", ['headers' => $request->headers->all()]); // Logging header request

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


        // Buat request header
        $headers = [
            'Authorization'  => "Bearer $storedToken",
            'Content-Type'   => 'application/json',
        ];

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
        if ($request->customerNo != $briva->customer_no) {
            return response()->json([
                'responseCode' => '4042401',
                'responseMessage' => 'Invalid Customer No'
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
                "partnerServiceId" => "19114",
                "customerNo" => $briva->customer_no,
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

        return response()->json($response, 200, $headers);
    }

    // Inquiry otomatis header juga make SHA yg payload
    // public function inquiry(Request $request)
    // {
    //     Log::info("Inquiry Request Received", ['request' => $request->all()]);
    //     Log::info("Inquiry Request Headers", ['headers' => $request->headers->all()]); // Logging header request

    //     // Ambil token dari storage
    //     if (!Storage::exists('token.json')) {
    //         Log::error("Token not found");
    //         return response()->json([
    //             'responseCode'    => '4012401',
    //             'responseMessage' => 'Invalid Token'
    //         ], 401);
    //     }

    //     $tokenData = json_decode(Storage::get('token.json'), true);
    //     $storedToken = $tokenData['accessToken'] ?? null;
    //     $expiresAt = $tokenData['expiresAt'] ?? 0;


    //     // Cek apakah token sudah expired
    //     if (now()->timestamp > $expiresAt) {
    //         Log::warning("Token Expired", ['tokenExpiresAt' => $expiresAt]);
    //         return response()->json([
    //             'responseCode'    => '4012402',
    //             'responseMessage' => 'Token Expired'
    //         ], 401);
    //     }

    //     // Validasi Input
    //     $request->validate([
    //         'virtualAccountNo' => 'required'
    //     ]);

    //     // Generate header otomatis
    //     $clientId = env('BRI_CLIENT_ID');
    //     $partnerId = '19114';
    //     $channelId = '00009'; // API channel
    //     $externalId = (string) Str::uuid(); // ID unik



    //     // Generate X-SIGNATURE menggunakan HMAC_SHA512
    //     // Endpoint Path (harus sesuai dengan URL setelah hostname tanpa query param)
    //     $httpMethod = "POST";
    //     $endpoint   = "/snap/v1.0/inquiry"; // Sesuaikan dengan path API BRI yang benar
    //     $timestamp  = now()->format('Y-m-d\TH:i:s.v\Z'); // ISO8601

    //     // Simulasi body request (harus sesuai dengan yang dikirim ke API BRI)
    //     $body = [
    //         'virtualAccountNo' => $request->virtualAccountNo,
    //         'customerNo'       => $request->customerNo ?? '',
    //     ];
    //     $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);

    //     // SHA-256 Hash dari body request (jika ada, jika GET kosong)
    //     $hashedBody = hash('sha256', $jsonBody);

    //     // String yang akan ditandatangani
    //     $stringToSign = "$httpMethod:$endpoint:$storedToken:" . strtolower($hashedBody) . ":$timestamp";

    //     // Buat X-SIGNATURE menggunakan HMAC_SHA512
    //     $signature = hash_hmac('sha512', $stringToSign, $clientId);

    //     Log::info("Signature Generated for Inquiry", [
    //         'stringToSign' => $stringToSign,
    //         'signature'    => $signature
    //     ]);

    //     // Buat request header
    //     $headers = [
    //         'Authorization'  => "Bearer $storedToken",
    //         'X-TIMESTAMP'    => $timestamp,
    //         'X-SIGNATURE'    => $signature,
    //         'Content-Type'   => 'application/json',
    //         'X-PARTNER-ID'   => $partnerId,
    //         'CHANNEL-ID'     => $channelId,
    //         'X-EXTERNAL-ID'  => $externalId,
    //     ];
    //     Log::info("Headers Sent to API", ['headers' => $headers]);

    //     // Cari VA yang sudah dibuat di function payment
    //     $briva = SupportBriva::where('va', $request->virtualAccountNo)->first();

    //     // Jika VA tidak ditemukan
    //     if (!$briva) {
    //         return response()->json([
    //             'responseCode' => '4042419',
    //             'responseMessage' => 'Invalid Bill/Virtual Account'
    //         ], 404);
    //     }

    //     // Cari Order Berdasarkan order_id dari VA
    //     $order = Order::find($briva->order_id);

    //     if (!$order) {
    //         return response()->json([
    //             'responseCode' => '4042414',
    //             'responseMessage' => 'Order Not Found'
    //         ], 404);
    //     }

    //     if ($order->status == 100) {
    //         return response()->json([
    //             'responseCode' => '4042414',
    //             'responseMessage' => 'Paid Bill'
    //         ], 404);
    //     }
    //     if ($request->customerNo != $briva->customer_no) {
    //         return response()->json([
    //             'responseCode' => '4042401',
    //             'responseMessage' => 'Invalid Customer No'
    //         ], 404);
    //     }

    //     // Data Response Simulasi menggunakan VA yang sudah dibuat
    //     $response = [
    //         "responseCode" => "2002400",
    //         "responseMessage" => "Successful",
    //         "virtualAccountData" => [
    //             "partnerServiceId" => "19114",
    //             "customerNo" => $briva->customer_no,
    //             "virtualAccountNo" => $briva->va,
    //             "virtualAccountName" => $order->user->name,
    //             "inquiryRequestId" => (string) Str::uuid(),
    //             "totalAmount" => [
    //                 "value" => number_format($order->total_price, 2, '.', ''),
    //                 "currency" => "IDR"
    //             ],
    //             "inquiryStatus" => "00",
    //             "inquiryReason" => [
    //                 "english" => "Success",
    //                 "indonesia" => "Sukses"
    //             ],
    //             "additionalInfo" => [
    //                 "idApp" => "TEST",
    //                 "info1" => "info 1 harus diisi"
    //             ]
    //         ]
    //     ];

    //     return response()->json($response, 200, $headers);
    // }

    // public function simulateSignature()
    // {
    //     $clientId = env('BRI_CLIENT_ID');
    //     $baseUrl = env('BRI_BASE_URL');

    //     // Perbaiki format timestamp sesuai BRI API
    //     $timestamp = now()->format('Y-m-d\TH:i:s.vP');

    //     // Generate signature
    //     $signature = SignatureHelper::generateSignature($clientId, $timestamp);

    //     // Request ke endpoint yang benar
    //     $response = Http::withHeaders([
    //         'X-CLIENT-KEY' => $clientId,
    //         'X-TIMESTAMP'  => $timestamp,
    //         'X-SIGNATURE'  => $signature,
    //         'Content-Type' => 'application/json',
    //     ])->post("{$baseUrl}/snap/v1.0/access-token/b2b", [
    //         'grantType' => 'client_credentials'
    //     ]);

    //     return $response->json();
    // }

    // public function getAccessToken(): JsonResponse
    // {
    //     $clientId  = env('BRI_CLIENT_ID');
    //     // Generate timestamp otomatis sesuai format BRI
    //     $timestamp = now()->format('Y-m-d\TH:i:s.v\Z');
    //     // Buat signature menggunakan private key
    //     $signature = SignatureHelper::generateSignature($clientId, $timestamp);

    //     Log::info("Generating Access Token", [
    //         'clientId'   => $clientId,
    //         'timestamp'  => $timestamp,
    //         'signature'  => $signature
    //     ]);

    //     // Validasi Signature
    //     if (!$timestamp || !$signature || !SignatureHelper::verifySignature($clientId, $timestamp, $signature)) {
    //         Log::warning("Invalid Signature during Access Token request");
    //         return response()->json([
    //             'responseCode'    => '4010003',
    //             'responseMessage' => 'Invalid Signature'
    //         ], 401);
    //     }

    //     // Generate Token
    //     $accessToken = SignatureHelper::generateSignature($clientId, $timestamp, true);
    //     $expiresIn   = 900; // 15 menit
    //     $expiresAt   = now()->addSeconds($expiresIn)->timestamp;

    //     // Data yang disimpan di file
    //     $dataToSave = [
    //         "accessToken" => $accessToken,
    //         "tokenType"   => "Bearer",
    //         "expiresIn"   => $expiresIn,
    //         "expiresAt"   => $expiresAt // Hanya disimpan di file
    //     ];

    //     // Simpan ke file storage/token.json
    //     Storage::put('token.json', json_encode($dataToSave, JSON_PRETTY_PRINT));

    //     Log::info("Access Token Generated Successfully", [
    //         'accessToken' => $accessToken,
    //         'expiresIn'   => $expiresIn
    //     ]);

    //     // Data yang dikembalikan ke response (tanpa expiresAt)
    //     $responseData = [
    //         "accessToken" => $accessToken,
    //         "tokenType"   => "Bearer",
    //         "expiresIn"   => $expiresIn
    //     ];

    //     return response()->json($responseData, 200);
    // }

    // public function simulateSignature()
    // {
    //     // Simulasi response untuk menghindari timeout
    //     $clientId = env('BRI_CLIENT_ID');

    //     // Generate timestamp otomatis sesuai format BRI
    //     $timestamp = now()->format('Y-m-d\TH:i:s.v\Z');

    //     Http::fake([
    //         route('bri.access_token') => Http::response([
    //             "accessToken" => SignatureHelper::generateSignature($clientId, $timestamp, true),
    //             "tokenType"   => "Bearer",
    //             "expiresIn"   => "900"
    //         ], 200),
    //     ]);

    //     // Buat signature menggunakan private key
    //     $signature = SignatureHelper::generateSignature($clientId, $timestamp);

    //     // Request langsung ke endpoint yang kita buat di web.php
    //     $response = Http::withHeaders([
    //         'X-CLIENT-KEY' => $clientId,
    //         'X-TIMESTAMP'  => $timestamp,
    //         'X-SIGNATURE'  => $signature,
    //         'Content-Type' => 'application/json',
    //     ])->post(route('bri.access_token'), [
    //         "grantType" => "client_credentials"
    //     ]);

    //     $data = $response->json();

    //     // Tambahkan waktu expired token
    //     $data['expiresAt'] = now()->addSeconds($data['expiresIn'])->timestamp;

    //     //verifikasi signature
    //     if (!SignatureHelper::verifySignature($clientId, $timestamp, $signature)) {
    //         return response()->json([
    //             'responseCode' => '4010003',
    //             'responseMessage' => 'Invalid Signature'
    //         ], 401);
    //     }

    //     // Simpan ke file
    //     Storage::put('token.json', json_encode($data, JSON_PRETTY_PRINT));

    //     return response()->json($response->json(), 200);
    // }

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
