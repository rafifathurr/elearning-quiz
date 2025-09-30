<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderVoucher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class myVoucherController extends Controller
{
    public function index()
    {
        $datatable_route = route('myvoucher.dataTable');
        return view('myvoucher.index', compact('datatable_route'));
    }
    public function dataTable()
    {
        $orderIds = Order::where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->pluck('id');


        $myVoucher = OrderVoucher::whereIn('order_id', $orderIds)
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($myVoucher)
            ->addIndexColumn()
            ->addColumn('voucher_code', function ($data) {
                return '
                <div class="d-flex justify-content-between align-items-center">
                    <span class="voucher-code-text" data-code="' . $data->voucher_code . '">' . $data->voucher_code . '</span>
                    <button class="btn btn-sm btn-light copy-btn ml-2" data-code="' . $data->voucher_code . '" title="Copy">
                        📋
                    </button>
                </div>';
            })

            ->addColumn('package', function ($data) {
                return $data->package->name;
            })
            ->addColumn('name', function ($data) {
                return $data->voucher->name;
            })
            ->addColumn('type_voucher', function ($data) {
                if ($data->type_voucher == 'discount') {
                    $value = 'Diskon ' . $data->voucher_value . '%';
                } else {
                    $value = 'Fixed Price Rp. ' . number_format($data->voucher_value, 0, ',', '.');
                }
                return $value;
            })
            // ->addColumn('price', function ($data) {
            //     return 'Rp. ' . number_format($data->price, 0, ',', '.');
            // })
            ->addColumn('status', function ($data) {
                if ($data->status == 1) {
                    $status = '<span class="text-primary">Belum Digunakan</span>';
                } else {
                    $status = '<span class="text-danger">Sudah Digunakan</span>';
                }
                return $status;
            })


            ->only(['voucher_code', 'package', 'name', 'type_voucher', 'status'])
            ->rawColumns(['status', 'voucher_code'])
            ->make(true);
    }
}
