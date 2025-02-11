<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MemberExport implements FromCollection, WithHeadings
{
    protected $packageFilter;
    protected $dateFilter;

    public function __construct($packageFilter, $dateFilter)
    {
        $this->packageFilter = $packageFilter;
        $this->dateFilter = $dateFilter;
    }

    public function collection()
    {
        $orderId = Order::whereNull('deleted_at')->where('status', 100)->pluck('id');
        $packageId = Package::whereNull('deleted_at')->pluck('id');
        return OrderPackage::whereNull('deleted_at')
            ->where('class', '>', 0)
            ->whereIn('order_id', $orderId)
            ->whereIn('package_id', $packageId)
            ->with(['package', 'order.user', 'dateClass']) // Pastikan load relasi yang dibutuhkan
            ->when($this->packageFilter, function ($query) {
                $query->whereHas('package', function ($q) {
                    $q->where('id', $this->packageFilter);
                });
            })
            ->when($this->dateFilter, function ($query) {
                $query->whereHas('dateClass', function ($q) {
                    $q->where('id', $this->dateFilter);
                });
            })
            ->get()
            ->map(function ($data) {
                return [
                    'Nama Paket' => $data->package->name ?? '-',
                    'Waktu Mendaftar' => \Carbon\Carbon::parse($data->order->created_at)->translatedFormat('d F Y H:i') ?? '-',
                    'Nama Peserta' => $data->order->user->name ?? '-',
                    'Jadwal Kelas' => $data->dateClass->name ?? '-',
                ];
            });
    }


    public function headings(): array
    {
        return ["Nama Paket", "Waktu Mendaftar", "Nama Peserta", "Jadwal Kelas"];
    }
}
