<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\OrderExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    public function export(Request $request)
    {
        $startDate = Carbon::parse(request('start_date'))->startOfDay();
        $endDate = Carbon::parse(request('end_date'))->endOfDay();
        $fileName = 'Order ' . \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') . ' sampai ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') . '.xlsx';

        return Excel::download(new OrderExport($startDate, $endDate), $fileName);
    }
}
