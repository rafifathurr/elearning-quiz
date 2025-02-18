<?php

namespace App\Http\Controllers;

use App\Services\BrivaServices;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class BrivaController extends Controller
{

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
