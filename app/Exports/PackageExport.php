<?php

namespace App\Exports;

use App\Models\ClassPackage;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\TypePackage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PackageExport implements FromView
{
    protected $month;
    protected $year;
    protected $bulanTeks;

    public function __construct($month, $year, $bulanTeks)
    {
        $this->month = $month;
        $this->year = $year;
        $this->bulanTeks = $bulanTeks;
    }

    public function view(): View
    {
        $bulan = ($this->month == 'all') ? 'Semua Bulan' : $this->bulanTeks;
        $tahun = ($this->year == 'all') ? 'Semua Tahun' : $this->year;

        $userId = Auth::user()->id;
        if (User::find($userId)->hasRole('admin')) {
            $packageData = Package::whereNull('deleted_at')->get();
        } else {
            // Ambil semua parent yang punya akses
            $parents = TypePackage::where('id_parent', 0)
                ->whereNull('deleted_at')
                ->where(function ($query) use ($userId) {
                    // Cek akses di parent
                    $query->whereHas('packageAccess', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    })
                        // Atau akses di salah satu children
                        ->orWhereHas('children.packageAccess', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        });
                })
                ->with(['children' => function ($query) {
                    $query->whereNull('deleted_at');
                }])
                ->get();

            // Filter hasil berdasarkan aturan:
            $filteredParents = $parents->map(function ($parent) use ($userId) {
                // Cek apakah user punya akses di parent
                $hasParentAccess = $parent->packageAccess()->where('user_id', $userId)->exists();

                // Jika parent dipilih, ambil semua children-nya
                if ($hasParentAccess) {
                    return $parent;
                }

                // Jika tidak, cek children yang punya akses
                $childrenWithAccess = $parent->children->filter(function ($child) use ($userId) {
                    return $child->packageAccess()->where('user_id', $userId)->exists();
                });

                // Jika ada children dengan akses, tampilkan parent-nya tetapi hanya children yang punya akses
                if ($childrenWithAccess->isNotEmpty()) {
                    $parent->children = $childrenWithAccess;
                    return $parent;
                }

                // Jika tidak ada akses, jangan tampilkan
                return null;
            })->filter(); // Hapus null value

            // Ambil semua package yang terkait dengan hasil filter
            $packageData = Package::whereNull('deleted_at')
                ->whereIn('id_type_package', $filteredParents->pluck('id')->merge(
                    $filteredParents->pluck('children.*.id')->flatten()
                ))
                ->get();
        }

        $data = $packageData->map(function ($package) {
            $dataOrderPackage = OrderPackage::whereHas('order', function ($query) {
                $query->whereNull('deleted_at');
            })
                ->whereNull('deleted_at')
                ->where('package_id', $package->id);
            if ($this->year !== 'all') {
                $dataOrderPackage->whereYear('created_at', $this->year);
            }

            if ($this->month !== 'all') {
                $dataOrderPackage->whereMonth('created_at', $this->month);
            }

            $orderPackage = $dataOrderPackage->count();


            $dataPackageSold = OrderPackage::whereHas('order', function ($query) {
                $query->where('status', 100)
                    ->whereNull('deleted_at');
            })
                ->whereNull('deleted_at')
                ->where('package_id', $package->id);
            if ($this->year !== 'all') {
                $dataPackageSold->whereYear('created_at', $this->year);
            }

            if ($this->month !== 'all') {
                $dataPackageSold->whereMonth('created_at', $this->month);
            }

            $packageSold = $dataPackageSold->count();



            $dataClassOpen = ClassPackage::where('package_id', $package->id)
                ->whereNull('deleted_at');
            if ($this->year !== 'all') {
                $dataClassOpen->whereYear('created_at', $this->year);
            }

            if ($this->month !== 'all') {
                $dataClassOpen->whereMonth('created_at', $this->month);
            }

            $classOpen = $dataClassOpen->count();


            $dataOnGoingClass = ClassPackage::where('package_id', $package->id)
                ->whereNull('deleted_at')
                ->where('current_meeting', '>', 0)
                ->whereColumn('current_meeting', '<', 'total_meeting');
            if ($this->year !== 'all') {
                $dataOnGoingClass->whereYear('created_at', $this->year);
            }

            if ($this->month !== 'all') {
                $dataOnGoingClass->whereMonth('created_at', $this->month);
            }

            $onGoingClass = $dataOnGoingClass->count();

            return [
                'paket' => $package->name,
                'jumlah_order' => $orderPackage,
                'jumlah_paket_dibayar' => $packageSold,
                'jumlah_kelas_dibuka' => $classOpen,
                'jumlah_kelas_berjalan' => $onGoingClass
            ];
        });
        return view('master.package_payment.package_export', compact('data', 'bulan', 'tahun'));
    }

    // public function collection()
    // {
    //     return Package::whereNull('deleted_at')->get()->map(function ($package) {
    //         $dataOrderPackage = OrderPackage::whereHas('order', function ($query) {
    //             $query->whereNull('deleted_at');
    //         })
    //             ->whereNull('deleted_at')
    //             ->where('package_id', $package->id);
    //         if ($this->year !== 'all') {
    //             $dataOrderPackage->whereYear('created_at', $this->year);
    //         }

    //         if ($this->month !== 'all') {
    //             $dataOrderPackage->whereMonth('created_at', $this->month);
    //         }

    //         $orderPackage = $dataOrderPackage->count();


    //         $dataPackageSold = OrderPackage::whereHas('order', function ($query) {
    //             $query->where('status', 100)
    //                 ->whereNull('deleted_at');
    //         })
    //             ->whereNull('deleted_at')
    //             ->where('package_id', $package->id);
    //         if ($this->year !== 'all') {
    //             $dataPackageSold->whereYear('created_at', $this->year);
    //         }

    //         if ($this->month !== 'all') {
    //             $dataPackageSold->whereMonth('created_at', $this->month);
    //         }

    //         $packageSold = $dataPackageSold->count();



    //         $dataClassOpen = ClassPackage::where('package_id', $package->id)
    //             ->whereNull('deleted_at');
    //         if ($this->year !== 'all') {
    //             $dataClassOpen->whereYear('created_at', $this->year);
    //         }

    //         if ($this->month !== 'all') {
    //             $dataClassOpen->whereMonth('created_at', $this->month);
    //         }

    //         $classOpen = $dataClassOpen->count();


    //         $dataOnGoingClass = ClassPackage::where('package_id', $package->id)
    //             ->whereNull('deleted_at')
    //             ->where('current_meeting', '>', 0)
    //             ->whereColumn('current_meeting', '<', 'total_meeting');
    //         if ($this->year !== 'all') {
    //             $dataOnGoingClass->whereYear('created_at', $this->year);
    //         }

    //         if ($this->month !== 'all') {
    //             $dataOnGoingClass->whereMonth('created_at', $this->month);
    //         }

    //         $onGoingClass = $dataOnGoingClass->count();

    //         return [
    //             'paket' => $package->name,
    //             'jumlah_order' => $orderPackage,
    //             'jumlah_paket_dibayar' => $packageSold,
    //             'jumlah_kelas_dibuka' => $classOpen,
    //             'jumlah_kelas_berjalan' => $onGoingClass
    //         ];
    //     });
    // }

    // public function headings(): array
    // {
    //     return [
    //         'Paket',
    //         'Jumlah Order',
    //         'Jumlah Paket Dibayar',
    //         'Jumlah Kelas Dibuka',
    //         'Jumlah Kelas Berjalan'
    //     ];
    // }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $sheet = $event->sheet->getDelegate();

    //             // Menambahkan judul laporan di baris pertama
    //             $sheet->mergeCells('A1:E1');
    //             $sheet->setCellValue('A1', 'Laporan Order Paket');
    //             $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    //             $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

    //             // Menambahkan Bulan dan Tahun
    //             $bulan = ($this->month == 'all') ? 'Semua Bulan' : $this->bulanTeks;
    //             $tahun = ($this->year == 'all') ? 'Semua Tahun' : $this->year;

    //             $sheet->mergeCells('A2:E2');
    //             $sheet->setCellValue('A2', "Bulan: $bulan");
    //             $sheet->getStyle('A2')->getFont()->setBold(true);

    //             $sheet->mergeCells('A3:E3');
    //             $sheet->setCellValue('A3', "Tahun: $tahun");
    //             $sheet->getStyle('A3')->getFont()->setBold(true);

    //             // Menggeser header ke baris ke-4
    //             $sheet->setCellValue('A4', 'Paket');
    //             $sheet->setCellValue('B4', 'Jumlah Order');
    //             $sheet->setCellValue('C4', 'Jumlah Paket Dibayar');
    //             $sheet->setCellValue('D4', 'Jumlah Kelas Dibuka');
    //             $sheet->setCellValue('E4', 'Jumlah Kelas Berjalan');
    //             $sheet->getStyle('A4:E4')->getFont()->setBold(true);
    //         },
    //     ];
    // }
}
