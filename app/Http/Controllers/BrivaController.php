<?php

namespace App\Http\Controllers;

use App\Helpers\SignatureHelper;
use App\Services\BrivaServices;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class BrivaController extends Controller
{
    public function getAccessToken()
    {
        $clientId = "7505935e79b645f993f86f3f11eaecc0";
        $privateKey = <<<EOD
        -----BEGIN RSA PRIVATE KEY-----
        MIICWgIBAAKBgH6veQmFrdI9yXmAUoDTFJzSQPJspPpx71wbVM1NBUZx4w7C2V0g
        AadmMnhswn1PjSur7mmFqn687iMgLaiNKk6yTdGi3CWEH3eQov1ycWAOxCIZm+Yi
        XN/8fJiKP6LaeNQBQUwNI7fV7/NxzlJbJix79HYUV18ktTMnRCdMBEMtAgMBAAEC
        gYAJZ/nyrQxE6fWFofN+QS3snufXmB1/wunkytq3C5ryqg4T0H/XHENDLKFes6SV
        LUzsCy3+g8Au/NQpo4AAXcrgRioMOAaIQf3u1F9QeV8DF11WMRYmOyeiR0ISq6Y6
        /tw80e+jgqhN+7+AYuu/g8MxBQ7yaqtctCzq2tEi9cCCAQJBAMEeIY1nfjCuH/6P
        y21aeWLH8nKKjN5Ce321QROFAjIBNsatDVusQMoNLBD3oxEotNg65LLCuIkhMOcI
        Dd6qXW0CQQCn77Gog3mHGH5GhP+wTytcT6YboirLoifyZZicCOCTCvBX6UqsNyRX
        QBoPE1+pzutDWF9REqHKvJHI6nXeYqTBAkA4BQiQn1vwvSIU0xucvikGKaA/78cL
        VlfCUIjvI59OaCG+okaEuEQXGJkW1u8btCY5r2PWIzwqs1EfQ6vaUqtFAkAnsp2I
        fCvKJ5wSB3Z5sv1JAPr/JUKAiIBw6Fs+50pO+BMAdQFV3GMWzOxcC/RdK7CpZsaB
        X6onRpQfrmzWePMBAkArRh5A01Z7Dd81UT4iAuLVXeFx5/qgwTeVrDchgXU9aOTi
        Uodp8yHf/pv+qZd8uCmnfGj63s49Zk1ByaFIpEQn
        -----END RSA PRIVATE KEY-----
        EOD;

        // Panggil helper untuk generate signature
        $signatureData = SignatureHelper::generateSignature($clientId, $privateKey);

        // Signature dan Timestamp
        $xSignature = $signatureData['signature'];
        $xTimestamp = $signatureData['timestamp'];

        // Data yang dikirim
        $postData = json_encode(["grantType" => "client_credentials"]);

        // Kirimkan HTTP Request ke API
        $url = "http://127.0.0.1:8000/snap/v1.0/access-token/b2b";
        $headers = [
            "X-SIGNATURE: $xSignature",
            "X-CLIENT-KEY: $clientId",
            "X-TIMESTAMP: $xTimestamp",
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Body dengan grantType

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json(["error" => $error]);
        }

        return response()->json(["response" => json_decode($response, true)]);
    }



    public function generateSignature()
    {
        $clientId = "7505935e79b645f993f86f3f11eaecc0";

        $privateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIICWgIBAAKBgH6veQmFrdI9yXmAUoDTFJzSQPJspPpx71wbVM1NBUZx4w7C2V0g
AadmMnhswn1PjSur7mmFqn687iMgLaiNKk6yTdGi3CWEH3eQov1ycWAOxCIZm+Yi
XN/8fJiKP6LaeNQBQUwNI7fV7/NxzlJbJix79HYUV18ktTMnRCdMBEMtAgMBAAEC
gYAJZ/nyrQxE6fWFofN+QS3snufXmB1/wunkytq3C5ryqg4T0H/XHENDLKFes6SV
LUzsCy3+g8Au/NQpo4AAXcrgRioMOAaIQf3u1F9QeV8DF11WMRYmOyeiR0ISq6Y6
/tw80e+jgqhN+7+AYuu/g8MxBQ7yaqtctCzq2tEi9cCCAQJBAMEeIY1nfjCuH/6P
y21aeWLH8nKKjN5Ce321QROFAjIBNsatDVusQMoNLBD3oxEotNg65LLCuIkhMOcI
Dd6qXW0CQQCn77Gog3mHGH5GhP+wTytcT6YboirLoifyZZicCOCTCvBX6UqsNyRX
QBoPE1+pzutDWF9REqHKvJHI6nXeYqTBAkA4BQiQn1vwvSIU0xucvikGKaA/78cL
VlfCUIjvI59OaCG+okaEuEQXGJkW1u8btCY5r2PWIzwqs1EfQ6vaUqtFAkAnsp2I
fCvKJ5wSB3Z5sv1JAPr/JUKAiIBw6Fs+50pO+BMAdQFV3GMWzOxcC/RdK7CpZsaB
X6onRpQfrmzWePMBAkArRh5A01Z7Dd81UT4iAuLVXeFx5/qgwTeVrDchgXU9aOTi
Uodp8yHf/pv+qZd8uCmnfGj63s49Zk1ByaFIpEQn
-----END RSA PRIVATE KEY-----
EOD;

        date_default_timezone_set('UTC');
        $xTimestamp = gmdate("Y-m-d\TH:i:s.000\Z");

        $stringToSign = $clientId . "|" . $xTimestamp;
        openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $xSignature = base64_encode($signature);

        return response()->json([
            'Signature' => $xSignature,
            'X-Timestamp' => $xTimestamp
        ]);
    }


    public function inquiry(Request $request)
    {
        // Ambil data dari request
        $data = $request->all();

        // Validasi parameter yang diperlukan
        if (!isset($data['partnerServiceId']) || !isset($data['customerNo']) || !isset($data['virtualAccountNo']) || !isset($data['inquiryRequestId'])) {
            return response()->json([
                'responseCode' => '4002401', // Invalid Field Format
                'responseMessage' => 'Bad Request',
                'errorDetails' => [
                    'english' => 'Invalid Field Format',
                    'indonesia' => 'Format Field Tidak Valid',
                ]
            ]);
        }

        // Logika jika request tidak valid
        if (empty($data['partnerServiceId']) || empty($data['customerNo']) || empty($data['virtualAccountNo']) || empty($data['inquiryRequestId'])) {
            return response()->json([
                'responseCode' => '4002402', // Invalid Mandatory Field
                'responseMessage' => 'Bad Request',
                'errorDetails' => [
                    'english' => 'Invalid Mandatory Field',
                    'indonesia' => 'Field Wajib Tidak Valid',
                ]
            ]);
        }

        // Simulasi pengecekan status inquiry atau kondisi lainnya
        // Misalnya jika data tidak ditemukan, atau akun tidak valid
        if ($data['virtualAccountNo'] === 'invalidAccount') {
            return response()->json([
                'responseCode' => '4042401', // Transaction Not Found
                'responseMessage' => 'Failed',
                'errorDetails' => [
                    'english' => 'Transaction Not Found',
                    'indonesia' => 'Transaksi Tidak Ditemukan',
                ]
            ]);
        }

        // Bangun struktur response jika tidak ada error
        $response = [
            'responseCode' => '2002400', // Response code sukses
            'responseMessage' => 'Successful',
            'virtualAccountData' => [
                'partnerServiceId' => trim($data['partnerServiceId']),
                'customerNo' => $data['customerNo'],
                'virtualAccountNo' => $data['virtualAccountNo'],
                'virtualAccountName' => 'John Doe', // Nama pengguna
                'inquiryRequestId' => $data['inquiryRequestId'],
                'totalAmount' => [
                    'value' => '200000.00', // Total amount yang ditentukan
                    'currency' => 'IDR',
                ],
                'inquiryStatus' => '00', // Status inquiry
                'inquiryReason' => [
                    'english' => 'Success',
                    'indonesia' => 'Sukses',
                ],
                'additionalInfo' => [
                    'idApp' => 'TEST',
                    'info1' => 'info 1 harus diisi',
                ]
            ]
        ];

        // Kembalikan response sukses
        return response()->json($response);
    }


    public function payment(Request $request)
    {
        // Ambil data dari request
        $data = $request->all();

        // Validasi input
        if (empty($data['partnerServiceId'])) {
            return response()->json([
                'responseCode' => '4002501', // Invalid Field Format
                'responseMessage' => 'Invalid Field Format partnerServiceId',
            ]);
        }

        if (empty($data['customerNo'])) {
            return response()->json([
                'responseCode' => '4002502', // Invalid Mandatory Field customerNo
                'responseMessage' => 'Invalid Mandatory Field customerNo',
            ]);
        }



        // Bangun struktur response sesuai dengan format yang diberikan
        $response = [
            'responseCode' => '2002500', // Success
            'responseMessage' => 'Successful', // Response message
            'virtualAccountData' => [
                'partnerServiceId' => trim($data['partnerServiceId']),
                'customerNo' => $data['customerNo'],
                'virtualAccountNo' => $data['virtualAccountNo'],
                'virtualAccountName' => 'John Doe', // Nama yang statis, bisa diganti sesuai logika
                'paymentRequestId' => $data['paymentRequestId'],
                'paidAmount' => [
                    'value' => '10001.00', // Jumlah yang dibayar
                    'currency' => 'IDR',
                ],
                'paymentFlagStatus' => '00', // Status pembayaran (00 untuk sukses)
                'paymentFlagReason' => [
                    'english' => 'Success',
                    'indonesia' => 'Sukses',
                ]
            ],
            'additionalInfo' => [
                'idApp' => 'TEST',
                'passApp' => $data['additionalInfo']['passApp'], // Ambil passApp dari request
                'info1' => 'info 1 diisi', // Info tambahan
            ]
        ];

        // Kembalikan response dalam format JSON
        return response()->json($response);
    }









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
