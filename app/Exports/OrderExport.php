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
            ->whereBetween('updated_at', [$this->startDate, $this->endDate])
            ->orderBy('updated_at', 'asc')
            ->get();

        // Hitung total nominal
        $this->totalPrice = $orders->sum('total_price');

        return $orders->map(function ($order, $index) {
            $packageList = $order->orderPackages->map(function ($orderPackage) {
                return '• ' . ($orderPackage->package->name ?? '-');
            })->implode("\n");

            return [
                'no' => $index + 1,
                'tanggal_order' => \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->created_at),
                'order_id' => $order->id,
                'nama' => $order->user->name ?? '-',
                'email' => $order->user->email ?? '-',
                'paket' => $packageList,
                'pembayaran' => $order->payment_method,
                'tanggal_aproval' => $order->approval_date ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->approval_date) : null,
                'tanggal_settle' => $order->payment_date ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->payment_date) : null,
                'nominal' => $order->total_price,

            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Daftar order dari periode ' . \Carbon\Carbon::parse($this->startDate)->translatedFormat('d F Y') . ' sampai ' . \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y')],
            [''], // <--- baris kosong
            ['No', 'Tanggal Order', 'Order No', 'Nama Pengguna', 'Email Pengguna', 'Paket Yang Diambil', 'Jenis Pembayaran', 'Tanggal Approval', 'Tanggal Mutasi Rekening', 'Nominal'],
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
                $sheet->setCellValue('I' . $rowCount, 'Total:');
                $sheet->setCellValue('J' . $rowCount, '=SUM(J4:J' . ($rowCount - 1) . ')');

                // Styling total
                $sheet->getStyle('I' . $rowCount . ':J' . $rowCount)->getFont()->setBold(true);

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
                $sheet->getStyle('F')->getAlignment()->setWrapText(true);

                //Number Format
                $sheet->getStyle('I' . $rowCount . ':J' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                //Nominal
                $sheet->getStyle('J4:J' . ($rowCount - 1))
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                // Format tanggal_order (kolom A)
                $sheet->getStyle('B4:B' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_DMYSLASH); // hasilnya jadi: 18/04/2025

                // Format tanggal aproval (kolom G)
                $sheet->getStyle('H4:H' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_DMYSLASH);

                // Format tanggal_settle (kolom H)
                $sheet->getStyle('I4:I' . $rowCount)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_DMYSLASH);


                // Atur lebar kolom manual supaya lebih lebar
                $sheet->getColumnDimension('A')->setWidth(5);    // No
                $sheet->getColumnDimension('B')->setWidth(15);   // Tanggal Order
                $sheet->getColumnDimension('C')->setWidth(5);   // Order No
                $sheet->getColumnDimension('D')->setWidth(20);   // Nama Pengguna
                $sheet->getColumnDimension('E')->setWidth(25);   // Email Pengguna
                $sheet->getColumnDimension('F')->setWidth(30);   // Paket Yang Diambil
                $sheet->getColumnDimension('G')->setWidth(18);   // Jenis Pembayaran
                $sheet->getColumnDimension('H')->setWidth(18);   // Tanggal Approval
                $sheet->getColumnDimension('I')->setWidth(18);   // Tanggal Mutasi Rekening
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
