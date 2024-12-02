<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
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
        $order_detail = OrderDetail::whereNull('deleted_at')->get();

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
