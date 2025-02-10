<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Package;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PackageMemberController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.member.dataTable');
        $packages = Package::whereNull('deleted_at')->where('class', '>', 0)->get();
        return view('master.member.index', compact('datatable_route', 'packages'));
    }


    public function dataTable()
    {
        $packageFilter = request()->get('package');
        $orderId = Order::whereNull('deleted_at')->where('status', 100)->pluck('id');

        $query = OrderPackage::query()
            ->whereNull('deleted_at')
            ->whereNotNull('class')
            ->whereIn('order_id', $orderId);

        // Terapkan filter jika ada
        if ($packageFilter) {
            $query->where('package_id', $packageFilter);
        }

        $member = $query->get();

        return DataTables::of($member)
            ->addIndexColumn()
            ->addColumn('package', function ($data) {
                return $data->package->name;
            })
            ->addColumn('user', function ($data) {
                return $data->order->user->name;
            })
            ->addColumn('date', function ($data) {
                return $data->package->dateClass ? $data->package->dateClass->name : '-';
            })
            ->only(['package', 'user', 'date'])
            ->make(true);
    }
}
