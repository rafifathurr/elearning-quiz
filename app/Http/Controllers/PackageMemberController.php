<?php

namespace App\Http\Controllers;

use App\Exports\MemberExport;
use App\Models\ClassUser;
use App\Models\DateClass;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\PackageDate;
use App\Models\Result;
use Barryvdh\DomPDF\Facade\Pdf;
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
                return $data->package
                    ? $data->package->name . ' <span class="badge badge-info">' . $data->package->typePackage->name . '</span>'
                    : '-';
            })
            ->addColumn('created_at', function ($data) {
                return $data->order
                    ? \Carbon\Carbon::parse($data->order->created_at)->translatedFormat('d F Y H:i')
                    : '-';
            })
            ->addColumn('user', function ($data) {
                return $data->order ? $data->order->user->name : '-';
            })
            ->addColumn('email', function ($data) {
                return $data->order ? $data->order->user->email : '-';
            })
            ->addColumn('phone', function ($data) {
                return $data->order ? $data->order->user->phone : '-';
            })
            ->addColumn('date', function ($data) {
                return $data->dateClass ? $data->dateClass->name : '-';
            })
            ->addColumn('action', function ($data) {

                $btn_action = '<div align="center">';
                // $btn_action .= '<a href="' . route('master.aspect.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary" title="Detail">Detail</a>';
                $btn_action .= '<a href="' . route('master.member.pdf', ['id' => $data->id]) . '" class="btn btn-sm btn-primary" title="download"><i class="fas fa-download mr-1"></i>Download</a>';


                $btn_action .= '<div>';
                return $btn_action;
            })
            ->rawColumns(['package', 'action']) // Pastikan kolom package dirender sebagai HTML
            ->only(['package', 'action', 'created_at', 'user', 'date', 'email', 'phone'])
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

    public function getDateClass(Request $request)
    {
        $package_id = $request->input('package_id');

        $dateClasses = PackageDate::where('package_id', $package_id)
            ->with('classPackage') // Eager Loading ke DateClass
            ->get()
            ->pluck('classPackage');

        return response()->json($dateClasses);
    }

    public function exportPdf($id)
    {
        $orderPackage = OrderPackage::findOrFail($id);
        $classUsers = ClassUser::where('order_package_id', $id)->get();
        $orderDetails = OrderDetail::where('order_id', $orderPackage->order_id)
            ->where('package_id', $orderPackage->package_id)
            ->get();

        // Mengambil semua test berdasarkan order_detail_id
        $tests = Result::whereIn('order_detail_id', $orderDetails->pluck('id'))
            ->with('orderDetail') // Pastikan orderDetail dimuat
            ->get();

        $pdf = Pdf::loadView('master.member.member_report', compact('orderPackage', 'classUsers', 'tests'));
        return $pdf->download('Report Peserta ' . $orderPackage->order->user->name . ' .pdf');
    }
}
