<?php

namespace App\Http\Controllers;

use App\Models\Quiz\QuizQuestion;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuestionController extends Controller
{

    public function index()
    {
        $datatable_route = route('master.question.dataTable');
        return view('master.question.index', compact('datatable_route'));
    }

    public function dataTable()
    {
        $payments = QuizQuestion::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($payments)
            ->addIndexColumn()

            ->addColumn('access', function ($data) {
                $list_view = '<ul>';
                foreach ($data->questionTypeQuiz as $question_type) {
                    $list_view .= '<li>' . $question_type->typeUser->name . '</li>';
                }
                $list_view .= '</ul>';
                return $list_view;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('master.question.show', ['quiz' => $data->id]) . '" class="btn btn-sm btn-info my-1"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('master.question.edit', ['quiz' => $data->id]) . '" class="btn btn-sm btn-warning my-1 ml-1"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button onclick="destroyRecord(' . $data->id . ')" class="btn btn-sm btn-danger my-1 ml-1"><i class="fas fa-trash"></i></button>';
                return $btn_action;
            })
            ->addColumn('price', function ($data) {
                $price = '<div>' . 'Rp. ' . number_format($data->price, 0, ',', '.');

                $price .= '<div>';
                return $price;
            })
            ->only(['name', 'description', 'price', 'quota_access', 'action'])
            ->rawColumns(['action', 'description', 'price'])
            ->make(true);

        return $dataTable;
    }
}
