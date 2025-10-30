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
        $orders = Order::with([
            'user',
            'orderPackages' => function ($query) {
                $query->whereNull('deleted_at');
            },
            'orderPackages.package'
        ])
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->whereBetween('approval_date', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung total nominal
        $this->totalPrice = $orders->sum('total_price');

        return $orders->map(function ($order, $index) {
            $packageList = $order->orderPackages->map(function ($orderPackage) {
                return '• ' . ($orderPackage->package->name ?? '-');
            })->implode("\n");

            // Cek metode pembayaran
            $isCashOrNonCash = in_array($order->payment_method, ['tunai', 'non_tunai']);


            return [
                'no' => $index + 1,
                'tanggal_order' => \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->created_at),
                'tanggal_settle' => $isCashOrNonCash ? null : ($order->payment_date ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->payment_date) : null),
                'tanggal_aproval' => $order->approval_date ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->approval_date) : null,
                'order_id' => $order->id,
                'nama' => $order->user->name ?? '-',
                'email' => $order->user->email ?? '-',
                'paket' => $packageList,
                'pembayaran' => $order->payment_method,
                'nominal' => $isCashOrNonCash ? null : $order->total_price,

            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Daftar order periode ' . \Carbon\Carbon::parse($this->startDate)->translatedFormat('d F Y') . ' sampai ' . \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y')],
            [''], // <--- baris kosong
            ['No', 'Tanggal Order', 'Tanggal Konfirmasi Pembayaran', 'Tanggal Approval', 'Order No', 'Nama Pengguna', 'Email Pengguna', 'Paket Yang Diambil', 'Jenis Pembayaran',  'Nominal'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Hitung total row data + 2 baris header
                $highestRow = $sheet->getHighestRow();
                $startDataRow = 4; // Data mulai dari baris 4 (setelah header)

                $rowCount = $highestRow + 1; // +1 untuk keperluan total akhir


                $sheet->mergeCells('A' . $rowCount . ':I' . $rowCount);
                $sheet->setCellValue('A' . $rowCount, 'Total:');
                $sheet->setCellValue('J' . $rowCount, '=SUM(J' . $startDataRow . ':J' . ($rowCount - 1) . ')');


                $sheet->getStyle('A' . $rowCount . ':J' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('A' . $rowCount . ':J' . $rowCount)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9EDF7'], // Kuning
                    ],
                ]);

                // End Total Sub Total


                // Header styling
                $sheet->mergeCells('A1:J1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A3:J3')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Wrap text untuk paket
                $sheet->getStyle('H')->getAlignment()->setWrapText(true);

                //Number Format
                $sheet->getStyle('J' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                //Nominal
                $sheet->getStyle('J4:J' . ($rowCount - 1))
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                // Format tanggal_order (kolom A)
                $sheet->getStyle('B4:B' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy'); // hasilnya jadi: 18/04/2025


                // Format konfirmasi (kolom C)
                $sheet->getStyle('C4:C' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy');


                // Format tanggal aproval (kolom D)
                $sheet->getStyle('D4:D' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy');

                // Atur lebar kolom manual supaya lebih lebar
                $sheet->getColumnDimension('A')->setWidth(5);    // No
                $sheet->getColumnDimension('B')->setWidth(15);   // Tanggal Order
                $sheet->getColumnDimension('C')->setWidth(18);   // Tanggal Konfirmasi
                $sheet->getColumnDimension('D')->setWidth(18);   // Tanggal Approval
                $sheet->getColumnDimension('E')->setWidth(12);   // Order No
                $sheet->getColumnDimension('F')->setWidth(20);   // Nama Pengguna
                $sheet->getColumnDimension('G')->setWidth(25);   // Email Pengguna
                $sheet->getColumnDimension('H')->setWidth(30);   // Paket Yang Diambil
                $sheet->getColumnDimension('I')->setWidth(18);   // Jenis Pembayaran
                $sheet->getColumnDimension('J')->setWidth(18);   // Nominal

                // BORDER semua data (A3 sampai J + rowCount)
                $sheet->getStyle('A3:J' . $rowCount)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }

    public function startRow(): int
    {
        return 4; // Mulai dari baris ke-4 setelah judul dan header
    }
}
