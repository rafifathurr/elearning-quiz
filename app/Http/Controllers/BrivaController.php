<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BRIVAService;
use Illuminate\Support\Facades\Log;

class BrivaController extends Controller
{
    protected $brivaService;

    public function __construct(BRIVAService $brivaService)
    {
        $this->brivaService = $brivaService;
    }

    public function inquiry(Request $request)
    {
        $vaNumber = $request->input('va_number');
        $response = $this->brivaService->inquiry($vaNumber);

        // Debugging untuk memastikan request benar
        Log::info('BRIVA Inquiry Request:', ['va_number' => $vaNumber]);
        Log::info('BRIVA Inquiry Response:', (array) $response);

        return response()->json($response);
    }



    public function payment(Request $request)
    {
        $vaNumber = $request->input('va_number');
        $amount = $request->input('amount');
        $response = $this->brivaService->makePayment($vaNumber, $amount);
        return response()->json($response);
    }
}
