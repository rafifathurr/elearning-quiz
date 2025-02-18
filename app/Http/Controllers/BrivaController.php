<?php

namespace App\Http\Controllers;

use App\Services\BrivaServices;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class BrivaController extends Controller
{

    public function inquiry(Request $request)
    {
        return response()->json($request->all());
    }

    public function payment(Request $request)
    {
        return response()->json($request->all());
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
