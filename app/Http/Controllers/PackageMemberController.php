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

        $query = OrderPackage::query()
            ->whereNull('deleted_at')
            ->where('class', '>', 0)
            ->whereIn('order_id', $orderId);

        // Filter berdasarkan paket jika dipilih
        if ($packageFilter) {
            $query->where('package_id', $packageFilter);
        }

        // Filter berdasarkan nama tanggal kelas jika dipilih
        if ($dateClassFilter) {
            $query->where('date_class_id', $dateClassFilter);
        }

        $member = $query->get();

        return DataTables::of($member)
            ->addIndexColumn()
            ->addColumn('package', function ($data) {
                return $data->package ? $data->package->name  : '-';
            })
            ->addColumn('user', function ($data) {
                return $data->order ? $data->order->user->name : '-';
            })
            ->addColumn('date', function ($data) {
                return $data->dateClass ? $data->dateClass->name : '-';
            })
            ->only(['package', 'user', 'date'])
            ->make(true);
    }

    public function export(Request $request)
    {
        $packageFilter = $request->input('packageFilter');
        $dateFilter = $request->input('dateClassFilter');

        return Excel::download(new MemberExport($packageFilter, $dateFilter), 'member_data.xlsx');
    }
}
