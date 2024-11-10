<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizTypeUserAccess;
use App\Models\Quiz\TypeQuiz;
use App\Models\QuizUserAnswer;
use App\Models\QuizUserResult;
use App\Models\TypeUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listQuiz()
    {
        $userTypeIds = Auth::user()->userTypeAccess->pluck('type_user_id');


        $quizes = Quiz::whereHas('quizTypeUserAccess', function ($query) use ($userTypeIds) {
            $query->whereIn('type_user_id', $userTypeIds);
        })->get();

        return view('quiz.list.index', compact('quizes'));
    }

    public function historyQuiz()
    {
        $user_id = Auth::user()->id;
        // Ambil semua attempt untuk user ini
        $histories = QuizUserResult::where('user_id', $user_id)
            ->orderBy('quiz_id')
            ->orderBy('id')
            ->get();

        // Menambahkan nomor attempt secara manual per quiz_id
        $grouped_histories = [];
        foreach ($histories->groupBy('quiz_id') as $quiz_id => $attempts) {
            foreach ($attempts as $index => $history) {
                $history->attempt_number = $index + 1; // Menambahkan nomor attempt ke-berapa
                $grouped_histories[] = $history;
            }
        }

        return view('quiz.list.history', ['histories' => $grouped_histories]);
    }



    public function index(Request $request)
    {
        if ($request->ajax()) {
            $quiz = Quiz::whereNull('deleted_at')->get();

            return DataTables::of($quiz)
                ->addIndexColumn()
                ->addColumn('type_quiz', function ($data) {
                    return $data->typeQuiz->name;
                })
                ->addColumn('access', function ($data) {
                    $list_view = '<ul>';
                    foreach ($data->quizTypeUserAccess as $quiz_access) {
                        $list_view .= '<li>' . $quiz_access->typeUser->name . '</li>';
                    }
                    $list_view .= '</ul>';
                    return $list_view;
                })
                ->addColumn('action', function ($data) {
                    $btn_action = '<a href="' . route('admin.quiz.show', ['quiz' => $data->id]) . '" class="btn btn-sm btn-info my-1"><i class="fas fa-eye"></i></a>';
                    $btn_action .= '<a href="' . route('admin.quiz.edit', ['quiz' => $data->id]) . '" class="btn btn-sm btn-warning my-1 ml-1"><i class="fas fa-pencil-alt"></i></a>';
                    $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => $data->id]) . '" class="btn btn-sm btn-success my-1 ml-1"><i class="fas fa-play"></i></a>';
                    $btn_action .= '<button onclick="destroyRecord(' . $data->id . ')" class="btn btn-sm btn-danger my-1 ml-1"><i class="fas fa-trash"></i></button>';
                    return $btn_action;
                })
                ->only(['name', 'type_quiz', 'access', 'action'])
                ->rawColumns(['access', 'action'])
                ->make(true);
        }
        return view('quiz.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['type_quiz'] = TypeQuiz::whereNull('deleted_at')->get();
        $data['type_user'] = TypeUser::whereNull('deleted_at')->get();
        return view('quiz.create', $data);
    }

    /**
     * Append form resource
     */
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $quiz = Quiz::create([
                'name' => $request->name,
                'type_quiz_id' => $request->type_quiz,
                'is_random_question' => isset($request->is_random_question),
                'description' => $request->description,
                'open_quiz' => isset($request->open_quiz) ? $request->open_quiz : null,
                'close_quiz' => isset($request->close_quiz) ? $request->close_quiz : null,
                'time_duration' => $request->time_duration,
            ]);

            $quiz_type_user_access_request = [];
            foreach ($request->quiz_type_user as $type_user_id) {
                $quiz_type_user_access_request[] = [
                    'quiz_id' => $quiz->id,
                    'type_user_id' => $type_user_id,
                ];
            }

            $quiz_type_user_access = QuizTypeUserAccess::insert($quiz_type_user_access_request);
            $time_duration_quiz = intval($request->time_duration);

            if ($quiz && $quiz_type_user_access) {
                foreach ($request->quiz_question as $index => $quiz_question_request) {

                    $quiz_question_duration = null;

                    if (!is_null($time_duration_quiz)) {
                        if (!is_null($quiz_question_request['time_duration'])) {
                            if ($time_duration_quiz - intval($quiz_question_request['time_duration']) >= 0) {
                                $quiz_question_duration = $quiz_question_request['time_duration'];
                                $time_duration_quiz -= intval($quiz_question_request['time_duration']);
                            } else {
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Waktu Durasi Tidak Sesuai'])
                                    ->withInput();
                            }
                        }
                    } else {
                        $quiz_question_duration = $quiz_question_request['time_duration'];
                    }

                    $quiz_question = QuizQuestion::create([
                        'quiz_id' => $quiz->id,
                        'is_random_answer' => isset($quiz_question_request['is_random_answer']),
                        'is_generate_random_answer' => isset($quiz_question_request['is_generate_random_answer']),
                        'order' => $index,
                        'direction_question' => $quiz_question_request['direction_question'],
                        'question' => $quiz_question_request['question'],
                        'description' => $quiz_question_request['description'],
                        'time_duration' => $quiz_question_duration,
                    ]);

                    if (!$quiz_question) {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Simpan Pertanyaan'])
                            ->withInput();
                    }

                    foreach ($quiz_question_request['quiz_answer'] as $quiz_answer_request) {
                        $quiz_answer = QuizAnswer::create([
                            'quiz_question_id' => $quiz_question->id,
                            'answer' => $quiz_answer_request['answer'],
                            'point' => $quiz_answer_request['point'],
                            'is_answer' => isset($quiz_answer_request['is_answer']),
                        ]);

                        if (!$quiz_answer) {
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Simpan Pertanyaan'])
                                ->withInput();
                        }
                    }
                }

                DB::commit();
                return redirect()
                    ->route('admin.quiz.index')
                    ->with(['success' => 'Berhasil Simpan Quiz']);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Simpan Quiz'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $data['disabled'] = 'disabled';
        $data['quiz'] = $quiz;
        $data['type_quiz'] = TypeQuiz::whereNull('deleted_at')->get();
        $data['type_user'] = TypeUser::whereNull('deleted_at')->get();

        $data['quiz_question'] = '';
        foreach ($quiz->quizQuestion as $index => $question) {
            if (is_null($question->deleted_at)) {
                $data['quiz_question'] .= $this->appendQuestion($question, $index + 1, 'disabled');
            }
        }

        return view('quiz.edit', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $data['disabled'] = '';
        $data['quiz'] = $quiz;
        $data['type_quiz'] = TypeQuiz::whereNull('deleted_at')->get();
        $data['type_user'] = TypeUser::whereNull('deleted_at')->get();

        $data['quiz_question'] = '';
        foreach ($quiz->quizQuestion as $index => $question) {
            if (is_null($question->deleted_at)) {
                $data['quiz_question'] .= $this->appendQuestion($question, $index + 1);
            }
        }

        return view('quiz.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        try {
            DB::beginTransaction();

            $quiz_update = $quiz->update([
                'name' => $request->name,
                'type_quiz_id' => $request->type_quiz,
                'is_random_question' => isset($request->is_random_question),
                'description' => $request->description,
                'open_quiz' => isset($request->open_quiz) ? $request->open_quiz : null,
                'close_quiz' => isset($request->close_quiz) ? $request->close_quiz : null,
                'time_duration' => $request->time_duration,
            ]);

            /**
             * Clear Last Record
             */
            $deleted_quiz_type_user_access = QuizTypeUserAccess::where('quiz_id', $quiz->id)->delete();
            $last_quiz_question = $quiz->quizQuestion->pluck('id')->toArray();
            // $deleted_quiz_answer = QuizAnswer::whereIn('quiz_question_id', QuizQuestion::where('quiz_id', $quiz->id)->pluck('id')->toArray())->delete();
            // $deleted_quiz_question = QuizQuestion::where('quiz_id',  $quiz->id)->delete();

            if ($quiz_update && $deleted_quiz_type_user_access) {

                $quiz_type_user_access_request = [];
                foreach ($request->quiz_type_user as $type_user_id) {
                    $quiz_type_user_access_request[] = [
                        'quiz_id' => $quiz->id,
                        'type_user_id' => $type_user_id,
                    ];
                }
                $quiz_type_user_access = QuizTypeUserAccess::insert($quiz_type_user_access_request);
                $time_duration_quiz = intval($request->time_duration);

                if ($quiz_type_user_access) {

                    foreach ($request->quiz_question as $index => $quiz_question_request) {

                        if (!is_null($time_duration_quiz)) {
                            if (!is_null($quiz_question_request['time_duration'])) {
                                if ($time_duration_quiz - intval($quiz_question_request['time_duration']) >= 0) {
                                    $quiz_question_duration = $quiz_question_request['time_duration'];
                                    $time_duration_quiz -= $quiz_question_duration;
                                } else {
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Waktu Durasi Tidak Sesuai'])
                                        ->withInput();
                                }
                            } else {
                                $quiz_question_duration = 0;
                                $time_duration_quiz -= $quiz_question_duration;
                            }
                        } else {
                            $quiz_question_duration = $quiz_question_request['time_duration'];
                        }

                        if (isset($quiz_question_request['id'])) {

                            $last_quiz_answer = $quiz->quizQuestion->where('id', $quiz_question_request['id'])->first()->quizAnswer->pluck('id')->toArray();

                            $quiz_question = QuizQuestion::where('id', $quiz_question_request['id'])->update([
                                'quiz_id' => $quiz->id,
                                'is_random_answer' => isset($quiz_question_request['is_random_answer']),
                                'is_generate_random_answer' => isset($quiz_question_request['is_generate_random_answer']),
                                'order' => $index,
                                'direction_question' => $quiz_question_request['direction_question'],
                                'question' => $quiz_question_request['question'],
                                'description' => $quiz_question_request['description'],
                                'time_duration' => $quiz_question_duration,
                            ]);

                            if (!$quiz_question) {
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Gagal Simpan Pertanyaan'])
                                    ->withInput();
                            }

                            foreach ($quiz_question_request['quiz_answer'] as $quiz_answer_request) {

                                if (isset($quiz_answer_request['id'])) {
                                    $quiz_answer = QuizAnswer::where('id', $quiz_answer_request['id'])->update([
                                        'quiz_question_id' => $quiz_question_request['id'],
                                        'answer' => $quiz_answer_request['answer'],
                                        'point' => $quiz_answer_request['point'],
                                        'is_answer' => isset($quiz_answer_request['is_answer']),
                                    ]);

                                    if (($key_answer_array = array_search($quiz_answer_request['id'], $last_quiz_answer)) !== false) {
                                        unset($last_quiz_answer[$key_answer_array]);
                                    }
                                } else {
                                    $quiz_answer = QuizAnswer::create([
                                        'quiz_question_id' => $quiz_question_request['id'],
                                        'answer' => $quiz_answer_request['answer'],
                                        'point' => $quiz_answer_request['point'],
                                        'is_answer' => isset($quiz_answer_request['is_answer']),
                                    ]);
                                }

                                if (!$quiz_answer) {
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Simpan Pertanyaan'])
                                        ->withInput();
                                }
                            }

                            if (!empty($last_quiz_answer)) {
                                $quiz_answer_destroy = QuizAnswer::whereIn('id', $last_quiz_answer)
                                    ->update(['deleted_at' => date('Y-m-d H:i:s')]);

                                if (!$quiz_answer_destroy) {
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Hapus Jawaban'])
                                        ->withInput();
                                }
                            }

                            if (($key_question_array = array_search($quiz_question_request['id'], $last_quiz_question)) !== false) {
                                unset($last_quiz_question[$key_question_array]);
                            }
                        } else {

                            $quiz_question = QuizQuestion::create([
                                'quiz_id' => $quiz->id,
                                'is_random_answer' => isset($quiz_question_request['is_random_answer']),
                                'is_generate_random_answer' => isset($quiz_question_request['is_generate_random_answer']),
                                'order' => $index,
                                'direction_question' => $quiz_question_request['direction_question'],
                                'question' => $quiz_question_request['question'],
                                'description' => $quiz_question_request['description'],
                                'time_duration' => $quiz_question_duration,
                            ]);

                            if (!$quiz_question) {
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Gagal Simpan Pertanyaan'])
                                    ->withInput();
                            }

                            foreach ($quiz_question_request['quiz_answer'] as $quiz_answer_request) {
                                $quiz_answer = QuizAnswer::create([
                                    'quiz_question_id' => $quiz_question->id,
                                    'answer' => $quiz_answer_request['answer'],
                                    'point' => $quiz_answer_request['point'],
                                    'is_answer' => isset($quiz_answer_request['is_answer']),
                                ]);

                                if (!$quiz_answer) {
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Simpan Pertanyaan'])
                                        ->withInput();
                                }
                            }
                        }
                        $quiz_question_duration = null;
                    }

                    if (!empty($last_quiz_question)) {
                        $quiz_question_destroy = QuizQuestion::whereIn('id', $last_quiz_question)
                            ->update(['deleted_at' => date('Y-m-d H:i:s')]);

                        if (!$quiz_question_destroy) {
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Hapus Pertanyaan'])
                                ->withInput();
                        }
                    }

                    DB::commit();
                    return redirect()
                        ->route('admin.quiz.index')
                        ->with(['success' => 'Berhasil Simpan Quiz']);
                } else {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Perbarui Akses Quiz'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Quiz'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        try {
            DB::beginTransaction();

            // Destroy with Softdelete
            $quiz_destroy = $quiz->update(['deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Quiz
            if ($quiz_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Quiz');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Quiz');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }

    /**
     * Start quiz resource
     */
    public function start(Quiz $quiz, Request $request)
    {
        Session::forget('quiz');
        if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
            return response()->json(['result' => $quiz], 200);
        } else {
            if (!empty($request->all())) {
                return redirect()->route('quiz.start', ['quiz' => $quiz->id]);
            }

            // Tentukan percakapan pertama
            $attempt_number = 1;

            // Simpan percakapan pertama pada session
            session(['quiz_attempt' => $attempt_number]);

            $data['quiz'] = $quiz;
            return view('quiz.play.start', $data);
        }
    }

    /**
     * Play quiz resource
     */
    public function play(Quiz $quiz, Request $request)
    {
        try {


            if (Session::has('quiz')) {
                $quiz = Session::get('quiz');

                foreach ($quiz['quiz_question'] as $index_quiz_question => $quiz_question) {
                    $quiz['quiz_question'][$index_quiz_question]['is_active'] = false;
                }

                Session::forget('quiz');
            } else {
                $quiz = $quiz->with(['quizTypeUserAccess.typeUser', 'quizQuestion.quizAnswer'])->find($quiz->id)->toArray();
                $quiz['total_question'] = count($quiz['quiz_question']);

                // Generate It Has Random Question
                if ($quiz['is_random_question']) {
                    shuffle($quiz['quiz_question']);
                }

                // Quiz Question
                $question_number = 1;
                foreach ($quiz['quiz_question'] as $index_quiz_question => $quiz_question) {
                    // Adding Question Numbering
                    $quiz['quiz_question'][$index_quiz_question]['question_number'] = $question_number;
                    $quiz['quiz_question'][$index_quiz_question]['is_active'] = false;
                    $quiz['quiz_question'][$index_quiz_question]['answered'] = false;
                    $question_number++;

                    // Array of Answer
                    $quiz_answer_arr = $quiz_question['quiz_answer'];

                    // Generate It Has Random Another Correct Answer
                    if ($quiz_question['is_generate_random_answer']) {
                        $quiz_answer_arr = [];
                        $quiz_answer = collect($quiz_question['quiz_answer'])->where('is_answer', 1)->first();

                        if (!is_null($quiz_answer['attachment'])) {
                        } else {
                            // Generate Random Another Correct Answer Number Method
                            $range_num_min = '1';
                            $range_num_max = '9';

                            for ($index = 1; $index < strlen($quiz_answer['answer']); $index++) {
                                $range_num_min .= '0';
                                $range_num_max .= '9';
                            }

                            $quiz_answer_first = $quiz_answer;
                            $quiz_answer_first['answered'] = false;
                            $quiz_answer_first['is_answer'] = intval($quiz_answer_first['is_answer']);
                            array_push($quiz_answer_arr, collect($quiz_answer_first));

                            $answer_list = [intval($quiz_answer_first['answer'])];
                            for ($random_index = 1; $random_index <= 4; $random_index++) {

                                $answer =  $this->generateAnswerRandom(intval($range_num_min), intval($range_num_max), $answer_list);

                                array_push($quiz_answer_arr, collect([
                                    'quiz_question_id' => $quiz_answer['quiz_question_id'],
                                    'answer' => $answer,
                                    'attachment' => null,
                                    'is_answer' => 0,
                                    'answered' => false,
                                    'point' => 0,
                                    'created_at' => $quiz_answer['created_at'],
                                    'updated_at' => $quiz_answer['updated_at'],
                                ]));

                                array_push($answer_list, $answer);
                            }

                            shuffle($quiz_answer_arr);
                        }
                    } else {
                        foreach ($quiz_answer_arr  as $index => $quiz_answer) {
                            $quiz_answer_arr[$index]['answered'] = false;
                            $quiz_answer_arr[$index]['is_answer'] = intval($quiz_answer['is_answer']);
                            $quiz_answer_arr[$index] = collect($quiz_answer_arr[$index]);
                        }
                    }

                    // Generate It Has Random Answer
                    if ($quiz_question['is_random_answer']) {
                        shuffle($quiz_answer_arr);
                    }

                    // Picking New List Answer
                    $quiz['quiz_question'][$index_quiz_question]['quiz_answer'] = collect($quiz_answer_arr);
                }
            }

            $current_quiz = collect($quiz['quiz_question'])->where('question_number', isset($request->q) ? $request->q : 1)->first();
            $current_quiz['is_active'] = true;

            foreach ($quiz['quiz_question'] as $index => $question) {
                if ($question['question_number'] == $current_quiz['question_number'] && $question['id'] == $current_quiz['id']) {
                    $quiz['quiz_question'][$index] = $current_quiz;
                }
            }

            Session::forget('quiz');
            Session::put('quiz', $quiz);

            $data['quiz'] = $quiz;
            $data['quiz_question'] = $current_quiz;

            if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
                return response()->json(['result' => $data], 200);
            } else {
                if (isset($request->q)) {
                    return view('quiz.play.question', $data);
                }
                return view('quiz.play.index', $data);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed', $e->getMessage()]);
        }
    }

    /**
     * Preview quiz resource
     */
    public function preview(Quiz $quiz, Request $request)
    {
        $quiz = Session::get('quiz');
        Session::forget('quiz');

        if (!isset($request->q) || is_null(collect($quiz['quiz_question'])->where('question_number', $request->q)->first())) {
            if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
                return response()->json(['failed' => 'Permintaan Tidak Sesuai'], 404);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Permintaan Tidak Sesuai']);
            }
        } else {

            $current_quiz = collect($quiz['quiz_question'])->where('question_number', $request->q)->first();
            $current_quiz['is_active'] = true;

            foreach ($quiz['quiz_question'] as $index => $question) {
                if ($question['question_number'] == $current_quiz['question_number'] && $question['id'] == $current_quiz['id']) {
                    $quiz['quiz_question'][$index] = $current_quiz;
                }
            }

            Session::forget('quiz');
            Session::put('quiz', $quiz);

            $data['quiz'] = $quiz;
            $data['quiz_question'] = collect($quiz['quiz_question'])->where('question_number', $request->q)->first();

            if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
                return response()->json(['result' => $data], 200);
            } else {
                return view('quiz.play.index', $data);
            }
        }
    }

    public function answer(Request $request)
    {
        if (Session::has('quiz')) {
            $quiz = Session::get('quiz');

            $current_quiz = collect($quiz['quiz_question'])->where('question_number', $request->q)->first();
            $current_quiz['answered'] = true;

            $quiz_answer = collect(collect($quiz['quiz_question'])->where('question_number', $request->q)->first()['quiz_answer'])->where('answer', $request->value)->first();
            $quiz_answer['answered'] = true;

            // Insert ke DB
            $is_correct = $quiz_answer['is_answer'];
            $point =  $quiz_answer['point'];
            $attempt_number = session('quiz_attempt', 1);

            QuizUserAnswer::create([
                'quiz_id' => $quiz['id'],
                'user_id' => Auth::user()->id,
                'quiz_question_id' => $current_quiz['id'],
                'quiz_answer_id' => $quiz_answer['id'],
                'is_correct' => $is_correct,
                'point' => $point,
                'attempt_number' => $attempt_number,
            ]);


            foreach ($quiz['quiz_question'] as $index => $question) {
                if ($question['question_number'] == $current_quiz['question_number'] && $question['id'] == $current_quiz['id']) {
                    $quiz['quiz_question'][$index] = $current_quiz;
                    foreach ($quiz['quiz_question'][$index]['quiz_answer'] as $num => $answer) {
                        if ($quiz_answer['answer'] == $answer['answer']) {
                            $quiz['quiz_question'][$index]['quiz_answer'][$num] = collect($quiz_answer);
                        }
                    }
                }
            }

            Session::forget('quiz');
            Session::put('quiz', $quiz);
            return response()->json(['message' => 'Jawaban Berhasil Disimpan'],  200);
        } else {
            return response()->json(['message' => 'Session Telah Habis'], 401);
        }
    }

    public function finish(Request $request, Quiz $quiz)
    {
        if (Session::has('quiz')) {
            $quiz_session = Session::get('quiz');


            // Insert ke DB
            $user_id = Auth::user()->id;
            $attempt_number = session('quiz_attempt', 1);
            $total_score = QuizUserAnswer::where('quiz_id', $quiz->id)
                ->where('user_id', $user_id)
                ->where('attempt_number', $attempt_number)
                ->sum('point');

            QuizUserResult::insert([
                'quiz_id' => $quiz->id,
                'user_id' => $user_id,
                'total_score' => $total_score
            ]);

            // Menambahkan 1 pada attempt_number untuk percakapan berikutnya
            session(['quiz_attempt' => $attempt_number + 1]);


            $data['quiz'] = $quiz;
            $total_point = 0;

            foreach ($quiz_session['quiz_question'] as $question) {
                foreach ($question['quiz_answer'] as $answer) {
                    if ($answer['answered']) {
                        $total_point += $answer['point'];
                    }
                }
            }


            $data['total_point'] = $total_point;

            Session::forget('quiz');
            return view('quiz.result', $data);
        } else {
            return redirect()->route('admin.quiz.start', ['quiz' => $quiz->id])->with(['failed' => 'Sesi Anda Telah Habis']);
        }
    }

    private function appendQuestion(QuizQuestion $quiz_question = null, $increment, $disabled = '')
    {
        $data['disabled'] = $disabled;
        $data['quiz_question'] = $quiz_question;
        $data['increment'] = $increment;

        if (!is_null($quiz_question)) {
            foreach ($quiz_question->quizAnswer as $index => $quiz_answer) {
                if (is_null($quiz_answer->deleted_at)) {
                    $data['quiz_answer'][$index + 1] = $this->appendAnswer($quiz_answer, $index + 1, $increment, $disabled);
                }
            }
        }

        return view('quiz.form.question', $data);
    }

    private function appendAnswer(QuizAnswer $quiz_answer = null, $increment, $parent, $disabled = '')
    {
        $data['disabled'] = $disabled;
        $data['quiz_answer'] = $quiz_answer;
        $data['increment'] = $increment;
        $data['parent'] = $parent;
        return view('quiz.form.answer', $data);
    }

    private function generateAnswerRandom(int $min, int $max, array $exception)
    {
        do {
            $answer = rand($min, $max);
        } while (in_array($answer, $exception));

        return $answer;
    }
}
