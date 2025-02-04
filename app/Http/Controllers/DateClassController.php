<?php

namespace App\Http\Controllers;

use App\Models\DateClass;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DateClassController extends Controller
{
    public function index()
    {
        $datatable_route = route('master.dateclass.dataTable');
        return view('master.dateclass.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $dates = DateClass::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($dates)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('master.dateclass.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning ml-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger ml-2" onclick="destroyRecord(' . $data->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';

                $btn_action .= '<div>';
                return $btn_action;
            })

            ->only(['name', 'date_code', 'action'])
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }
}
