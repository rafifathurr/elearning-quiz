<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class myClassController extends Controller
{
    public function index()
    {
        $datatable_route = route('myclass.dataTable');
        return view('myclass.index', compact('datatable_route'));
    }
    public function dataTable()
    {
        $orderIds = Order::where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->pluck('id');


        $myClass = OrderPackage::whereIn('order_id', $orderIds)
            ->whereNull('deleted_at')
            ->whereNotNull('class')
            ->get();

        return DataTables::of($myClass)
            ->addIndexColumn()
            ->addColumn('package', function ($data) {
                return $data->package->name;
            })
            ->addColumn('class', function ($data) {
                return (!is_null($data->class) ? $data->class . 'x Pertemuan' : '-');
            })

            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('myclass.detail', ['orderId' => $data->order_id, 'packageId' => $data->package_id]) . '" class="btn btn-sm btn-success">Test</a>';
                $btn_action .= '</div>';
                return $btn_action;
            })

            ->only(['package', 'class', 'action'])
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detail($orderId, $packageId)
    {
        $datatable_route = route('myclass.dataTableDetail', ['orderId' => $orderId, 'packageId' => $packageId]);
        $detailPackage = OrderDetail::where('order_id', $orderId)
            ->where('package_id', $packageId)
            ->whereNull('deleted_at')
            ->first();
        return view('myclass.detail', compact('datatable_route', 'detailPackage'));
    }

    public function dataTableDetail($orderId, $packageId)
    {
        $detailPackage = OrderDetail::where('order_id', $orderId)
            ->where('package_id', $packageId)
            ->whereNull('deleted_at')
            ->get();



        return DataTables::of($detailPackage)
            ->addIndexColumn()
            ->addColumn('quiz', function ($data) {
                return $data->quiz->name;
            })
            ->addColumn('type_quiz', function ($data) {
                return $data->quiz->type_aspect;
            })

            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';

                $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => $data->quiz->id]) . '" class="btn btn-sm btn-success">Mulai Test</a>';
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['package', 'quiz', 'type_quiz', 'action'])
            ->rawColumns(['action'])
            ->make(true);
    }
}
