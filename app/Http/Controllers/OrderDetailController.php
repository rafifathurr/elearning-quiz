<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderDetailController extends Controller
{
    public function index()
    {
        $datatable_route = route('mytest.dataTable');
        return view('mytest.index', compact('datatable_route'));
    }
    public function dataTable()
    {
        $orderIds = Order::where('user_id', Auth::user()->id)->whereNull('deleted_at')->where('status', 100)->pluck('id');
        $order_detail = OrderDetail::whereNull('deleted_at')->whereIn('order_id', $orderIds)->get();

        return DataTables::of($order_detail)
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
