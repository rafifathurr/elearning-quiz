<?php

namespace App\Http\Controllers;

use App\Exports\MemberExport;
use App\Models\DateClass;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Package;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PackageMemberController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.member.dataTable');
        $packages = Package::whereNull('deleted_at')->where('class', '>', 0)->get();
        $dateClasses = DateClass::whereNull('deleted_at')->get();
        return view('master.member.index', compact('datatable_route', 'packages', 'dateClasses'));
    }


    public function dataTable()
    {
        $packageFilter = request()->get('package');
        $dateClassFilter = request()->get('dateClass');
        $orderId = Order::whereNull('deleted_at')->where('status', 100)->pluck('id');
        $packageId = Package::whereNull('deleted_at')->pluck('id');

        $query = OrderPackage::query()
            ->whereNull('deleted_at')
            ->where('class', '>', 0)
            ->whereIn('order_id', $orderId)
            ->whereIn('package_id', $packageId);

        // Filter berdasarkan paket jika dipilih
        if ($packageFilter) {
            $query->where('package_id', $packageFilter);
        }

        // Filter berdasarkan nama tanggal kelas jika dipilih
        if ($dateClassFilter) {
            $query->where('date_class_id', $dateClassFilter);
        }

        if (!$packageFilter && !$dateClassFilter) {
            $query->orderBy('package_id', 'ASC');
        }

        $member = $query->get();

        return DataTables::of($member)
            ->addIndexColumn()
            ->addColumn('package', function ($data) {
                return $data->package ? $data->package->name  : '-';
            })
            ->addColumn('created_at', function ($data) {
                return $data->order ? \Carbon\Carbon::parse($data->order->created_at)->translatedFormat('d F Y H:i') : '-';
            })
            ->addColumn('user', function ($data) {
                return $data->order ? $data->order->user->name : '-';
            })
            ->addColumn('date', function ($data) {
                return $data->dateClass ? $data->dateClass->name : '-';
            })
            ->only(['package', 'created_at', 'user', 'date'])
            ->make(true);
    }

    public function export(Request $request)
    {
        $packageFilter = $request->input('packageFilter');
        $dateFilter = $request->input('dateClassFilter');

        $package = $packageFilter ? Package::find($packageFilter) : null;
        $date = $dateFilter ? DateClass::find($dateFilter) : null;

        // Tentukan nama file berdasarkan filter yang dipilih
        if ($package && $date) {
            $fileName = "Data Peserta - {$package->name} - {$date->name}.xlsx";
        } elseif ($package) {
            $fileName = "Data Peserta - {$package->name} - Semua Jadwal.xlsx";
        } elseif ($date) {
            $fileName = "Data Peserta - Semua Paket - {$date->name}.xlsx";
        } else {
            $fileName = "Data Peserta - Semua Paket - Semua Jadwal.xlsx";
        }

        return Excel::download(new MemberExport($packageFilter, $dateFilter), $fileName);
    }
}
