<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrderExport implements FromCollection, WithHeadings, WithEvents, WithStartRow
{
    protected $startDate;
    protected $endDate;
    protected $totalPrice = 0;


    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $orders = Order::with(['user', 'orderPackages.package'])
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->whereBetween('updated_at', [$this->startDate, $this->endDate])
            ->orderByDesc('updated_at')
            ->get();

        // Hitung total nominal
        $this->totalPrice = $orders->sum('total_price');

        return $orders->map(function ($order) {
            $packageList = $order->orderPackages->map(function ($orderPackage) {
                return '• ' . ($orderPackage->package->name ?? '-');
            })->implode("\n");

            return [
                'tanggal_order' => \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y'),
                'order_id' => $order->id,
                'nama' => $order->user->name ?? '-',
                'email' => $order->user->email ?? '-',
                'paket' => $packageList,
                'pembayaran' => $order->payment_method,
                'nominal' => $order->total_price

            ];
        });
    }



    public function headings(): array
    {
        return [
            ['Daftar order dari periode ' . \Carbon\Carbon::parse($this->startDate)->translatedFormat('d F Y') . ' sampai ' . \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y')],
            ['tanggal_order', 'order_id', 'nama', 'email', 'paket', 'pembayaran', 'nominal'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Hitung total row data + 2 baris header
                $rowCount = $sheet->getHighestRow() + 1;

                // Tambahkan total label dan total price
                $sheet->setCellValue('F' . $rowCount, 'Total:');
                $sheet->setCellValue('G' . $rowCount, $this->totalPrice);

                // Styling total
                $sheet->getStyle('F' . $rowCount . ':G' . $rowCount)->getFont()->setBold(true);

                // Header styling
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2:G2')->getFont()->setBold(true);

                // Wrap text untuk paket
                $sheet->getStyle('E')->getAlignment()->setWrapText(true);

                //Number Format
                $sheet->getStyle('F' . $rowCount . ':G' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                $sheet->getStyle('G3:G' . ($rowCount - 1))
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');
            },
        ];
    }

    public function startRow(): int
    {
        return 3; // Mulai dari baris ke-3 setelah judul dan header
    }
}
