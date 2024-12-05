<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class myTestController extends Controller
{
    public function index()
    {
        $datatable_route = route('mytest.dataTable');
        return view('mytest.index', compact('datatable_route'));
    }
    public function dataTable()
    {
        $orderIds = Order::where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            ->where('status', 100)
            ->pluck('id');


        $orderPackageIds = OrderPackage::whereIn('order_id', $orderIds)
            ->whereNull('deleted_at')
            ->whereNull('class')
            ->pluck('package_id');


        $orderDetails = OrderDetail::whereIn('order_id', $orderIds)
            ->whereIn('package_id', $orderPackageIds)
            ->whereNull('deleted_at')
            ->get();


        return DataTables::of($orderDetails)
            ->addIndexColumn()
            ->addColumn('package', function ($data) {
                return $data->package->name;
            })
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
