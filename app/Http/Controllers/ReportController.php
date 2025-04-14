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
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
        ]);
        $startDate = Carbon::parse(request('start_date'))->startOfDay();
        $endDate = Carbon::parse(request('end_date'))->endOfDay();
        $fileName = 'bc.' . \Carbon\Carbon::parse($startDate)->translatedFormat('ymd') . '-' . \Carbon\Carbon::parse($endDate)->translatedFormat('ymd') . '.xlsx';

        return Excel::download(new OrderExport($startDate, $endDate), $fileName);
    }
}
