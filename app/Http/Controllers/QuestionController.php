<?php

namespace App\Http\Controllers;

use App\Models\QuestionTypeQuiz;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\TypeQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $question = QuizQuestion::whereNull('deleted_at')->get();

        $dataTable = DataTables::of($question)
            ->addIndexColumn()

            ->addColumn('aspect', function ($data) {
                $list_view = '<ul>';
                foreach ($data->questionTypeQuiz as $question_type) {
                    $list_view .= '<li>' . $question_type->aspect->name . '</li>';
                }
                $list_view .= '</ul>';
                return $list_view;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('master.question.show', ['id' => $data->id]) . '" class="btn btn-sm btn-info my-1"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('master.question.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning my-1 ml-1"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button onclick="destroyRecord(' . $data->id . ')" class="btn btn-sm btn-danger my-1 ml-1"><i class="fas fa-trash"></i></button>';
                return $btn_action;
            })
            ->addColumn('time_duration', function ($data) {
                $time_duration = '<div>' .  $data->time_duration . ' Detik';

                $time_duration .= '<div>';
                return $time_duration;
            })
            ->only(['question', 'aspect', 'description', 'level', 'time_duration', 'action'])
            ->rawColumns(['action', 'description', 'aspect', 'time_duration'])
            ->make(true);

        return $dataTable;
    }

    public function create()
    {
        $data['type_quiz'] = TypeQuiz::whereNull('deleted_at')->get();
        return view('master.question.create', $data);
    }

    public function append(Request $request)
    {
        if (isset($request->question) || isset($request->answer)) {
            if ($request->question) {
                return $this->appendQuestion(null, $request->increment);
            } else {
                return $this->appendAnswer(null, $request->increment, $request->parent);
            }
        } else {
            return response()->json(['message' => 'Gagal'], 400);
        }
    }

    private function appendQuestion(QuizQuestion $quiz_question = null, $increment, $disabled = '')
    {
        $data['disabled'] = $disabled;
        $data['quiz_question'] = $quiz_question;
        $data['increment'] = $increment;
        $data['type_quiz'] = TypeQuiz::whereNull('deleted_at')->get();

        if (!is_null($quiz_question)) {
            foreach ($quiz_question->quizAnswer as $index => $quiz_answer) {
                if (is_null($quiz_answer->deleted_at)) {
                    $data['quiz_answer'][$index + 1] = $this->appendAnswer($quiz_answer, $index + 1, $increment, $disabled);
                }
            }
        }

        return view('master.question.form.question', $data);
    }

    private function appendAnswer(QuizAnswer $quiz_answer = null, $increment, $parent, $disabled = '')
    {
        $data['disabled'] = $disabled;
        $data['quiz_answer'] = $quiz_answer;
        $data['increment'] = $increment;
        $data['parent'] = $parent;
        return view('master.question.form.answer', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Create the question
            $quiz_question = QuizQuestion::create([
                'is_random_answer' => isset($request->is_random_answer),
                'is_generate_random_answer' => isset($request->is_generate_random_answer),
                'order' => 1,
                'direction_question' => $request->direction_question,
                'question' => $request->question,
                'description' => $request->description,
                'time_duration' => $request->time_duration,
                'level' => $request->level,
            ]);

            // Insert the question type
            $question_type_quiz_data = [];
            foreach ($request->type_quiz as $type_quiz_id) {
                $question_type_quiz_data[] = [
                    'question_id' => $quiz_question->id,
                    'type_quiz_id' => $type_quiz_id,
                ];
            }
            QuestionTypeQuiz::insert($question_type_quiz_data);

            // Insert answers related to the question
            foreach ($request->quiz_answer as $quiz_answer_request) {
                QuizAnswer::create([
                    'quiz_question_id' => $quiz_question->id,
                    'answer' => $quiz_answer_request['answer'],
                    'point' => $quiz_answer_request['point'],
                    'is_answer' => isset($quiz_answer_request['is_answer']),
                ]);
            }

            DB::commit();
            return redirect()
                ->route('master.question.index')
                ->with(['success' => 'Berhasil Simpan Jawaban']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }



    public function edit(string $id)
    {
        $data['type_quiz'] = TypeQuiz::whereNull('deleted_at')->get();
        $data['disabled'] = '';
        $data['quiz_question'] = QuizQuestion::find($id);

        // Inisialisasi array untuk menyimpan jawaban kuis
        $data['quiz_answer'] = [];

        if (!is_null($data['quiz_question'])) {
            foreach ($data['quiz_question']->quizAnswer as $index => $quiz_answer) {
                if (is_null($quiz_answer->deleted_at)) {
                    // Menambahkan jawaban ke dalam array quiz_answer
                    $data['quiz_answer'][] = $this->appendAnswer($quiz_answer, $index + 1, $id);
                }
            }
        }

        return view('master.question.edit', $data);
    }

    public function update(){
        
    }
}
