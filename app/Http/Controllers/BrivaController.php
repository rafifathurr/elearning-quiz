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

        // Bangun struktur response sesuai dengan dokumentasi yang diberikan
        $response = [
            'responseCode' => '2002400', // Response code tetap
            'responseMessage' => 'Successful', // Response message
            'virtualAccountData' => [
                'partnerServiceId' => trim($data['partnerServiceId']),
                'customerNo' => $data['customerNo'],
                'virtualAccountNo' => $data['virtualAccountNo'],
                'virtualAccountName' => 'John Doe', // Contoh nama, bisa diganti sesuai logika
                'inquiryRequestId' => $data['inquiryRequestId'],
                'totalAmount' => [
                    'value' => '200000.00', // Total amount yang sudah ditentukan
                    'currency' => 'IDR',
                ],
                'inquiryStatus' => '00', // Status inquiry
                'inquiryReason' => [
                    'english' => 'Success',
                    'indonesia' => 'Sukses',
                ],
                'additionalInfo' => [
                    'idApp' => 'TEST',
                    'info1' => 'info 1 harus diisi', // Contoh info tambahan
                ]
            ]
        ];

        // Kembalikan response dalam format JSON
        return response()->json($response);
    }

    public function payment(Request $request)
    {
        // Ambil data dari request
        $data = $request->all();

        // Bangun struktur response sesuai dengan format yang diberikan
        $response = [
            'responseCode' => '2002500', // Response code tetap
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
                'paymentFlagStatus' => '00', // Status pembayaran
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
