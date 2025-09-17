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
    protected $startDate;
    protected $endDate;

    public function __construct($packageFilter, $dateFilter, $startDate = null, $endDate = null)
    {
        $this->packageFilter = $packageFilter;
        $this->dateFilter = $dateFilter;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $orderId = Order::whereNull('deleted_at')
            ->where('status', 100)
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('created_at', [
                    \Carbon\Carbon::parse($this->startDate)->startOfDay(),
                    \Carbon\Carbon::parse($this->endDate)->endOfDay()
                ]);
            })
            ->pluck('id');

        $packageId = Package::whereNull('deleted_at')->pluck('id');

        return OrderPackage::whereNull('deleted_at')
            ->where('class', '>', 0)
            ->whereIn('order_id', $orderId)
            ->whereIn('package_id', $packageId)
            ->with(['package', 'order.user', 'dateClass'])
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
                    'Jadwal Kelas' => $data->dateClass->name ?? '-',
                    'Nama Peserta' => $data->order->user->name ?? '-',
                    'Email' => $data->order->user->email ?? '-',
                    'Nomor HP' => $data->order->user->phone ?? '-',
                ];
            });
    }


    public function headings(): array
    {
        return ["Nama Paket", "Waktu Mendaftar", "Jadwal Kelas", "Nama Peserta", "Email", "Nomor HP"];
    }
}
