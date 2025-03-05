<?php

namespace App\Exports;

use App\Models\ClassPackage;
use App\Models\ClassCounselor;
use App\Models\ClassAttendance;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClassReportExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $packageId;

    public function __construct($packageId)
    {
        $this->packageId = $packageId;
    }

    public function view(): View
    {
        $classes = ClassPackage::where('package_id', $this->packageId)
            ->with(['classCounselor', 'classAttendances.orderPackage.order.user'])
            ->get();

        return view('myclass.class_report', compact('classes'));
    }

    public function title(): string
    {
        return "Laporan Kelas";
    }
}
