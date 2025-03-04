<?php

namespace App\Exports;

use App\Models\ClassPackage;
use App\Models\OrderPackage;
use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PackageExport implements FromCollection, WithHeadings
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        return Package::whereNull('deleted_at')->get()->map(function ($package) {
            $orderPackage = OrderPackage::whereHas('order', function ($query) {
                $query->whereNull('deleted_at');
            })
                ->whereNull('deleted_at')
                ->whereMonth('created_at', $this->month) // Filter berdasarkan bulan
                ->where('package_id', $package->id)
                ->count();

            $packageSold = OrderPackage::whereHas('order', function ($query) {
                $query->where('status', 100)
                    ->whereNull('deleted_at');
            })
                ->whereNull('deleted_at')
                ->whereMonth('created_at', $this->month)
                ->where('package_id', $package->id)
                ->count();

            $classOpen = ClassPackage::where('package_id', $package->id)
                ->whereNull('deleted_at')
                ->whereMonth('created_at', $this->month)
                ->count();

            return [
                'paket' => $package->name,
                'jumlah_order' => $orderPackage,
                'jumlah_paket_dibayar' => $packageSold,
                'jumlah_kelas_dibuka' => $classOpen
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Paket',
            'Jumlah Order',
            'Jumlah Paket Dibayar',
            'Jumlah Kelas Dibuka'
        ];
    }
}
