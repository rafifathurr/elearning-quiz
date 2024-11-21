<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\AspectQuestion;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizAuthenticationAccess;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizTypeUserAccess;
use App\Models\Quiz\TypeQuiz;
use App\Models\QuizAspect;
use App\Models\QuizUserAnswer;
use App\Models\QuizUserAnswerResult;
use App\Models\QuizUserResult;
use App\Models\Result;
use App\Models\ResultDetail;
use App\Models\TypeUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class QuizController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $quiz = Quiz::whereNull('deleted_at')->get();

            return DataTables::of($quiz)
                ->addIndexColumn()
                ->addColumn('access', function ($data) {
                    $list_view = '<ul>';
                    foreach ($data->quizTypeUserAccess as $quiz_access) {
                        $list_view .= '<li>' . $quiz_access->typeUser->name . '</li>';
                    }
                    $list_view .= '</ul>';
                    return $list_view;
                })
                ->addColumn('action', function ($data) {
                    // $btn_action = '<a href="' . route('admin.quiz.show', ['quiz' => $data->id]) . '" class="btn btn-sm btn-info my-1"><i class="fas fa-eye"></i></a>';
                    $btn_action = '<a href="' . route('admin.quiz.edit', ['quiz' => $data->id]) . '" class="btn btn-sm btn-warning my-1 ml-1"><i class="fas fa-pencil-alt"></i></a>';
                    $btn_action .= '<a href="' . route('admin.quiz.showQuestion', ['quiz' => $data->id]) . '" class="btn btn-sm btn-info my-1 ml-1"><i class="fas fa-search"></i></a>';
                    $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => $data->id]) . '" class="btn btn-sm btn-success my-1 ml-1"><i class="fas fa-play"></i></a>';
                    $btn_action .= '<button onclick="destroyRecord(' . $data->id . ')" class="btn btn-sm btn-danger my-1 ml-1"><i class="fas fa-trash"></i></button>';
                    return $btn_action;
                })
                ->only(['name', 'access', 'action'])
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
        $data['type_user'] = TypeUser::whereNull('deleted_at')->get();
        return view('quiz.create', $data);
    }

    public function append(Request $request)
    {
        if (isset($request->aspect_quiz)) {
            if ($request->aspect_quiz) {
                return $this->appendAspect(null, $request->increment);
            }
        } else {
            return response()->json(['message' => 'Gagal'], 400);
        }
    }
    private function appendAspect(QuizAspect $quiz_aspect = null, $increment, $disabled = '')
    {
        $data['disabled'] = $disabled;
        $data['quiz_aspect'] = $quiz_aspect;
        $data['increment'] = $increment;
        $data['aspect_question'] = AspectQuestion::whereNull('deleted_at')->get();


        return view('quiz.form.aspect', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $quiz = Quiz::create([
                'name' => $request->name,
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

            if ($quiz && $quiz_type_user_access) {
                foreach ($request->quiz_aspect as $quiz_aspect_request) {

                    $quiz_aspect = QuizAspect::create([
                        'quiz_id' => $quiz->id,
                        'aspect_id' => $quiz_aspect_request['aspect_id'],
                        'level' => $quiz_aspect_request['level'],
                        'total_question' => $quiz_aspect_request['total_question'],
                    ]);

                    if (!$quiz_aspect) {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Simpan Aspek Quiz'])
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

    public function show(Quiz $quiz)
    {
        $data['disabled'] = 'disabled';

        $data['quiz'] = $quiz;
        $data['type_user'] = TypeUser::whereNull('deleted_at')->get();

        $data['quiz_aspect'] = '';
        foreach ($quiz->quizAspect as $index => $aspect) {
            if (is_null($aspect->deleted_at)) {
                $data['quiz_aspect'] .= $this->appendAspect($aspect, $index + 1, 'disabled');
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
        $data['type_user'] = TypeUser::whereNull('deleted_at')->get();

        $data['quiz_aspect'] = '';
        foreach ($quiz->quizAspect as $index => $aspect) {
            if (is_null($aspect->deleted_at)) {
                $data['quiz_aspect'] .= $this->appendAspect($aspect, $index + 1);
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
                'description' => $request->description,
                'open_quiz' => isset($request->open_quiz) ? $request->open_quiz : null,
                'close_quiz' => isset($request->close_quiz) ? $request->close_quiz : null,
                'time_duration' => $request->time_duration,
            ]);

            /**
             * Clear Last Record
             */
            $deleted_quiz_type_user_access = QuizTypeUserAccess::where('quiz_id', $quiz->id)->delete();
            $last_quiz_aspect = $quiz->quizAspect->pluck('id')->toArray();

            if ($quiz_update && $deleted_quiz_type_user_access) {

                $quiz_type_user_access_request = [];
                foreach ($request->quiz_type_user as $type_user_id) {
                    $quiz_type_user_access_request[] = [
                        'quiz_id' => $quiz->id,
                        'type_user_id' => $type_user_id,
                    ];
                }
                $quiz_type_user_access = QuizTypeUserAccess::insert($quiz_type_user_access_request);


                if ($quiz_type_user_access) {
                    foreach ($request->quiz_aspect as  $quiz_aspect_request) {

                        if (isset($quiz_aspect_request['id'])) {

                            $quiz_aspect = QuizAspect::where('id', $quiz_aspect_request['id'])->update([
                                'quiz_id' => $quiz->id,
                                'level' => $quiz_aspect_request['level'],
                                'aspect_id' => $quiz_aspect_request['aspect_id'],
                                'total_question' => $quiz_aspect_request['total_question'],
                            ]);

                            if (!$quiz_aspect) {
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Gagal Simpan Aspek Quiz'])
                                    ->withInput();
                            }



                            if (($key_question_array = array_search($quiz_aspect_request['id'], $last_quiz_aspect)) !== false) {
                                unset($last_quiz_aspect[$key_question_array]);
                            }
                        } else {

                            $quiz_aspect = QuizAspect::create([
                                'quiz_id' => $quiz->id,
                                'level' => $quiz_aspect_request['level'],
                                'aspect_id' => $quiz_aspect_request['aspect_id'],
                                'total_question' => $quiz_aspect_request['total_question'],
                            ]);

                            if (!$quiz_aspect) {
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Gagal Simpan Aspek Quiz'])
                                    ->withInput();
                            }
                        }
                    }

                    if (!empty($last_quiz_aspect)) {
                        $quiz_aspect_destroy = QuizAspect::whereIn('id', $last_quiz_aspect)
                            ->update(['deleted_at' => date('Y-m-d H:i:s')]);

                        if (!$quiz_aspect_destroy) {
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Hapus Aspek Quiz'])
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
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            // return redirect()
            //     ->back()
            //     ->with(['failed' => $e->getMessage()])
            //     ->withInput();
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

    public function showQuestion(Quiz $quiz)
    {
        $questions = [];
        foreach ($quiz->quizAspect as $aspect) {

            $questions[] = QuizQuestion::where(function ($query) use ($aspect) {
                $query->where('level', 'like', '%' . '|' . $aspect->level . '|' . '%')
                    ->orWhere('level', '0');
            })
                ->where(function ($query) use ($aspect) {
                    $query->where('aspect', 'like', '%' . '|' . $aspect->aspect_id . '|' . '%')
                        ->orWhere('aspect', '0');
                })
                ->inRandomOrder()
                ->limit($aspect->total_question)
                ->get();
        }


        foreach ($questions as $key => $questionSet) {
            foreach ($questionSet as $question) {
                $question->quizAnswer = $question->quizAnswer()->whereNull('deleted_at')->get();
            }
        }

        $totalQuestions = collect($questions)->sum(function ($questionSet) {
            return $questionSet->count();
        });

        return view('quiz.preview', compact('questions', 'totalQuestions'));
    }

    public function start(Quiz $quiz, Request $request)
    {


        $questions = [];
        foreach ($quiz->quizAspect as $aspect) {
            $questions[] = QuizQuestion::where(function ($query) use ($aspect) {
                $query->where('level', 'like', '%' . $aspect->level . '%')
                    ->orWhere('level', 0);
            })
                ->where(function ($query) use ($aspect) {
                    $query->where('aspect', 'like', '%' . $aspect->aspect_id . '%')
                        ->orWhere('aspect', 0);
                })
                ->limit($aspect->total_question)
                ->get();
        }

        $totalQuestions = collect($questions)->sum(function ($questionSet) {
            return $questionSet->count();
        });

        // Jika permintaan berbasis API (JSON)
        if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
            return response()->json([
                'result' => $quiz,
                'total_questions' => $totalQuestions,
            ], 200);
        } else {
            // Jika permintaan berbasis browser
            if (!empty($request->all())) {
                return redirect()->route('quiz.start', ['quiz' => $quiz->id]);
            }

            // Kirim data ke tampilan
            $data['quiz'] = $quiz;
            $data['totalQuestions'] = $totalQuestions;

            return view('quiz.play.start', $data);
        }
    }


    public function play(Quiz $quiz, Request $request)
    {
        try {
            // Buat entri Result baru
            $result = Result::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::user()->id,
                'start_time' => now(),
                'time_duration' => $quiz->time_duration,
            ]);

            Log::info('New result created with ID: ' . $result->id);

            $order = 0; // Pertanyaan pertama dimulai dari order 0

            // Simpan data soal berdasarkan level dan aspek
            foreach ($quiz->quizAspect as $aspect) {
                // Ambil pertanyaan berdasarkan level dan aspect
                $questionSet = QuizQuestion::where(function ($query) use ($aspect) {
                    $query->where('level', 'like', '%' . '|' . $aspect->level . '|' . '%')
                        ->orWhere('level', '0');
                })
                    ->where(function ($query) use ($aspect) {
                        $query->where('aspect', 'like', '%' . '|' . $aspect->aspect_id . '|' . '%')
                            ->orWhere('aspect', '0');
                    })
                    ->inRandomOrder()
                    ->limit($aspect->total_question)
                    ->get();

                // Simpan hasil pertanyaan ke ResultDetail
                foreach ($questionSet as $question) {
                    $order++;
                    ResultDetail::create([
                        'result_id' => $result->id,
                        'question_id' => $question->id,
                        'question_detail' => json_encode([
                            'direction_question' => $question->direction_question,
                            'description' => $question->description,
                            'question' => $question->question,
                            'attachment' => $question->attachment,
                            'is_random_answer' => $question->is_random_answer,
                            'is_generate_random_answer' => $question->is_generate_random_answer,
                        ]),
                        'aspect_id' => $aspect->aspect_id,
                        'level' => $aspect->level,
                        'order' => $order,
                    ]);
                }
            }
            ResultDetail::where('result_id', $result->id)->where('order', 1)->update([
                'display_time' => now()
            ]);

            // Jika permintaan adalah JSON (API)
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Quiz has started successfully',
                    'quiz_id' => $quiz->id,
                    'result_id' => $result->id,
                ], 200);
            }

            // Mengarahkan ke halaman soal pertama
            return redirect()->route('admin.quiz.getQuestion', ['result' => $result->id]);
        } catch (Exception $e) {
            // Tangani error
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function getQuestion(Result $result, Request $request)
    {
        try {
            // Log data permintaan untuk debugging
            Log::info('Request Headers:', $request->headers->all()); // Melihat semua headers permintaan
            Log::info('Request Data:', $request->all()); // Melihat data yang dikirimkan (POST data, query params, dll)
            Log::info('Request Accepts JSON: ' . ($request->wantsJson() ? 'Yes' : 'No'));

            Log::info('Result ID from route: ' . $result->id);

            // Ambil seluruh ResultDetail terkait quiz dan user
            $resultDetails = $result->details()->get();

            if ($resultDetails->isEmpty()) {
                return response()->json(['message' => 'Tidak ada pertanyaan untuk kuis ini'], 404);
            }

            // Persiapkan data untuk setiap soal
            $questions = $resultDetails->map(function ($resultDetail) {
                // Ambil data soal dari ResultDetail
                $question = QuizQuestion::find($resultDetail->question_id);

                // Ambil jawaban soal yang terkait melalui relasi
                $quizAnswerArr = $question->quizAnswer->map(function ($quiz_answer) {
                    return [
                        'id' => $quiz_answer->id,
                        'answer' => $quiz_answer->answer,
                        'is_answer' => intval($quiz_answer->is_answer),
                        'answered' => false,
                    ];
                })->toArray();

                // Proses jawaban soal
                $questionDetail = json_decode($resultDetail->question_detail, true);
                $questionData = [
                    'id' => $resultDetail->question_id,
                    'question_number' => $resultDetail->order, // Menambahkan order sebagai question_number
                    'direction_question' => $questionDetail['direction_question'],
                    'question' => $questionDetail['question'],
                    'description' => $questionDetail['description'],
                    'attachment' => $questionDetail['attachment'],
                    'is_random_answer' => $questionDetail['is_random_answer'],
                    'is_generate_random_answer' => $questionDetail['is_generate_random_answer'],
                    'aspect_id' => $resultDetail->aspect_id,
                    'level' => $resultDetail->level,
                    'order' => $resultDetail->order,
                    'quiz_answer' => $quizAnswerArr,
                    'is_active' => false,
                    'answered' => false,
                ];

                return $questionData;
            });

            // Update soal aktif
            $activeQuestionNumber = max(1, min($questions->count(), (int) $request->input('q', 1)));

            $questions = $questions->transform(function ($item) use ($activeQuestionNumber) {
                $item['is_active'] = $item['question_number'] == $activeQuestionNumber;
                return $item;
            });

            $activeQuestion = $questions->firstWhere('is_active', true);


            // Persiapkan data untuk API

            $quizData = Quiz::find($result->quiz_id)->toArray();
            $data = [
                'quiz' => $quizData,
                'result' => $result,
                'questions' => $questions,
                'active_question' => $activeQuestion,
                'total_question' => $questions->count(),
            ];


            if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
                Log::info('Sending JSON Response');
                return response()->json(['result' => $data], 200);
            } else {
                if (isset($request->q)) {
                    Log::info('Render Question');
                    return view('quiz.play.question', $data);
                }
                Log::info('Rendering HTML View');
                return view('quiz.play.index', $data);
            }
        } catch (Exception $e) {
            // Tangani error
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    public function answer(Request $request)
    {
        try {
            $validated = $request->validate([
                'value' => 'required',
                'q' => 'required|integer',
                'resultId' => 'required|integer',
                'questionId' => 'required|integer',
            ]);


            Log::info('Question ID: ' . $request->questionId);
            Log::info('Result ID: ' . $request->resultId);

            // Simpan jawaban pengguna
            $resultDetail = ResultDetail::where('question_id', $request->questionId)->where('result_id', $request->resultId)
                ->whereHas('result', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->firstOrFail();


            if (!$resultDetail) {
                throw new Exception("Data result detail tidak ditemukan");
            }
            $question = QuizQuestion::find($request->questionId);

            $score = 0;
            foreach ($question->quizAnswer as $answer) {
                if ($request->value == $answer->answer && $answer->is_answer == 1) {
                    $score = 1;
                }
            };

            $resultDetail->update([
                'answer' => $validated['value'],
                'score' => $score,
                'display_time' => now(),
            ]);

            return response()->json(['message' => 'Jawaban berhasil disimpan'], 200);
        } catch (Exception $e) {
            Log::error('Error pada pengolahan jawaban: ' . $e->getMessage()); // Log error
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function finish(Request $request)
    {
        try {

            $validated = $request->validate([
                'q' => 'nullable|integer',
                'resultId' => 'nullable|integer',
                'questionId' => 'nullable|integer',
                'value' => 'nullable',
            ]);


            Log::info('Question ID: ' . $request->questionId);
            Log::info('Result ID: ' . $request->resultId);
            Log::info('Request Data:', $request->all());


            // Simpan jawaban pengguna
            $resultDetail = ResultDetail::where('question_id', $request->questionId)->where('result_id', $request->resultId)
                ->whereHas('result', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->firstOrFail();


            if (!$resultDetail) {
                throw new Exception("Data result detail tidak ditemukan");
            }
            $question = QuizQuestion::find($request->questionId);

            $score = 0;
            foreach ($question->quizAnswer as $answer) {
                if ($request->value == $answer->answer && $answer->is_answer == 1) {
                    $score = 1;
                }
            };

            $resultDetail->update([
                'answer' => $validated['value'],
                'score' => $score,
                'display_time' => now(),
            ]);

            $totalScore = ResultDetail::where('result_id', $request->resultId)->sum('score');

            $resultData = Result::find($request->resultId);
            $resultData->update([
                'total_score' => $totalScore,
                'finish_time' => now(),
            ]);

            return view('quiz.result', compact('result'));
        } catch (Exception $e) {
            Log::error('Error pada pengolahan jawaban: ' . $e->getMessage()); // Log error
        }
    }

    public function showResult($resultId)
    {
        try {
            // Ambil data hasil quiz berdasarkan ID dan user saat ini
            $result = Result::where('id', $resultId)
                ->where('user_id', Auth::id())
                ->with('quiz') // Pastikan relasi ke tabel quiz tersedia
                ->firstOrFail();

            // Tampilkan view hasil kuis
            return view('quiz.result', compact('result'));
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error("Error saat menampilkan hasil quiz: " . $e->getMessage());

            // Redirect ke halaman utama dengan pesan error
            return redirect('/')->with('error', 'Gagal menampilkan hasil quiz.');
        }
    }
























    // public function play(Quiz $quiz, Request $request)
    // {
    //     try {
    //         if (Session::has('quiz')) {
    //             $quiz = Session::get('quiz');

    //             foreach ($quiz['quiz_question'] as $index_quiz_question => $quiz_question) {
    //                 $quiz['quiz_question'][$index_quiz_question]['is_active'] = false;
    //             }

    //             Session::forget('quiz');
    //         } else {
    //             $quiz = $quiz->with(['quizTypeUserAccess.typeUser', 'quizQuestion.quizAnswer'])->find($quiz->id)->toArray();
    //             $quiz['total_question'] = count($quiz['quiz_question']);

    //             // Generate It Has Random Question
    //             if ($quiz['is_random_question']) {
    //                 shuffle($quiz['quiz_question']);
    //             }

    //             // Quiz Question
    //             $question_number = 1;
    //             foreach ($quiz['quiz_question'] as $index_quiz_question => $quiz_question) {
    //                 // Adding Question Numbering
    //                 $quiz['quiz_question'][$index_quiz_question]['question_number'] = $question_number;
    //                 $quiz['quiz_question'][$index_quiz_question]['is_active'] = false;
    //                 $quiz['quiz_question'][$index_quiz_question]['answered'] = false;
    //                 $question_number++;

    //                 // Array of Answer
    //                 $quiz_answer_arr = $quiz_question['quiz_answer'];

    //                 // Generate It Has Random Another Correct Answer
    //                 if ($quiz_question['is_generate_random_answer']) {
    //                     $quiz_answer_arr = [];
    //                     $quiz_answer = collect($quiz_question['quiz_answer'])->where('is_answer', 1)->first();

    //                     if (!is_null($quiz_answer['attachment'])) {
    //                     } else {
    //                         // Generate Random Another Correct Answer Number Method
    //                         $range_num_min = '1';
    //                         $range_num_max = '9';

    //                         for ($index = 1; $index < strlen($quiz_answer['answer']); $index++) {
    //                             $range_num_min .= '0';
    //                             $range_num_max .= '9';
    //                         }

    //                         $quiz_answer_first = $quiz_answer;
    //                         $quiz_answer_first['answered'] = false;
    //                         $quiz_answer_first['is_answer'] = intval($quiz_answer_first['is_answer']);
    //                         array_push($quiz_answer_arr, collect($quiz_answer_first));

    //                         $answer_list = [intval($quiz_answer_first['answer'])];
    //                         for ($random_index = 1; $random_index <= 4; $random_index++) {

    //                             $answer =  $this->generateAnswerRandom(intval($range_num_min), intval($range_num_max), $answer_list);

    //                             array_push($quiz_answer_arr, collect([
    //                                 'quiz_question_id' => $quiz_answer['quiz_question_id'],
    //                                 'answer' => $answer,
    //                                 'attachment' => null,
    //                                 'is_answer' => 0,
    //                                 'answered' => false,
    //                                 'point' => 0,
    //                                 'created_at' => $quiz_answer['created_at'],
    //                                 'updated_at' => $quiz_answer['updated_at'],
    //                             ]));

    //                             array_push($answer_list, $answer);
    //                         }

    //                         shuffle($quiz_answer_arr);
    //                     }
    //                 } else {
    //                     foreach ($quiz_answer_arr  as $index => $quiz_answer) {
    //                         $quiz_answer_arr[$index]['answered'] = false;
    //                         $quiz_answer_arr[$index]['is_answer'] = intval($quiz_answer['is_answer']);
    //                         $quiz_answer_arr[$index] = collect($quiz_answer_arr[$index]);
    //                     }
    //                 }

    //                 // Generate It Has Random Answer
    //                 if ($quiz_question['is_random_answer']) {
    //                     shuffle($quiz_answer_arr);
    //                 }

    //                 // Picking New List Answer
    //                 $quiz['quiz_question'][$index_quiz_question]['quiz_answer'] = collect($quiz_answer_arr);
    //             }
    //         }

    //         $current_quiz = collect($quiz['quiz_question'])->where('question_number', isset($request->q) ? $request->q : 1)->first();
    //         $current_quiz['is_active'] = true;

    //         foreach ($quiz['quiz_question'] as $index => $question) {
    //             if ($question['question_number'] == $current_quiz['question_number'] && $question['id'] == $current_quiz['id']) {
    //                 $quiz['quiz_question'][$index] = $current_quiz;
    //             }
    //         }

    //         Session::forget('quiz');
    //         Session::put('quiz', $quiz);

    //         $data['quiz'] = $quiz;
    //         $data['quiz_question'] = $current_quiz;

    //         if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
    //             return response()->json(['result' => $data], 200);
    //         } else {
    //             if (isset($request->q)) {
    //                 return view('quiz.play.question', $data);
    //             }
    //             return view('quiz.play.index', $data);
    //         }
    //     } catch (Exception $e) {
    //         dd($e->getMessage());
    //         // return redirect()
    //         //     ->back()
    //         //     ->with(['failed', $e->getMessage()]);
    //     }
    // }


    // public function answer(Request $request)
    // {
    //     if (Session::has('quiz')) {
    //         $quiz = Session::get('quiz');

    //         // Ambil soal dan jawaban saat ini berdasarkan nomor soal
    //         $current_quiz = collect($quiz['quiz_question'])->where('question_number', $request->q)->first();

    //         // Tandai jawaban sebelumnya sebagai tidak dijawab
    //         foreach ($current_quiz['quiz_answer'] as $index => $answer) {
    //             $current_quiz['quiz_answer'][$index]['answered'] = false;
    //         }

    //         // Update jawaban yang dipilih pengguna
    //         $selected_answer = collect($current_quiz['quiz_answer'])->where('answer', $request->value)->first();
    //         $selected_answer['answered'] = true;

    //         // Simpan kembali jawaban ke dalam sesi
    //         foreach ($quiz['quiz_question'] as $index => $question) {
    //             if ($question['question_number'] == $current_quiz['question_number'] && $question['id'] == $current_quiz['id']) {
    //                 $quiz['quiz_question'][$index] = $current_quiz;

    //                 foreach ($quiz['quiz_question'][$index]['quiz_answer'] as $num => $answer) {
    //                     if ($selected_answer['answer'] == $answer['answer']) {
    //                         $quiz['quiz_question'][$index]['quiz_answer'][$num] = collect($selected_answer);
    //                     }
    //                 }
    //             }
    //         }

    //         // Simpan kembali seluruh sesi quiz yang telah diperbarui
    //         Session::forget('quiz');
    //         Session::put('quiz', $quiz);

    //         return response()->json(['message' => 'Jawaban Berhasil Disimpan'], 200);
    //     } else {
    //         return response()->json(['message' => 'Session Telah Habis'], 401);
    //     }
    // }

    public function listQuiz()
    {

        QuizAuthenticationAccess::where('key', Session::get('key'))->update([
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        Session::forget('key');
        Session::forget('quiz');

        $userTypeIds = Auth::user()->userTypeAccess->pluck('type_user_id');

        $quizes = Quiz::whereHas('quizTypeUserAccess', function ($query) use ($userTypeIds) {
            $query->whereIn('type_user_id', $userTypeIds);
        })->paginate(6);


        return view('quiz.list.index', compact('quizes'));
    }

    public function historyQuiz()
    {
        $user_id = Auth::user()->id;

        // Ambil semua attempt untuk user ini, paginate 3 data per halaman
        $histories = QuizUserResult::where('user_id', $user_id)
            ->orderBy('quiz_id')
            ->orderBy('id')
            ->paginate(6);

        // Menambahkan nomor attempt per quiz_id
        $histories->getCollection()->transform(function ($history) {
            $quiz_id = $history->quiz_id;
            // Hitung nomor attempt untuk quiz_id yang sama
            $attempt_number = QuizUserResult::where('user_id', $history->user_id)
                ->where('quiz_id', $quiz_id)
                ->where('id', '<=', $history->id)
                ->count();

            $history->attempt_number = $attempt_number; // Menambahkan nomor attempt
            return $history;
        });

        return view('quiz.list.history', ['histories' => $histories]);
    }

    public function reviewQuiz(string $id)
    {
        try {
            $review = QuizUserResult::find($id);
            if (!is_null($review)) {
                return view('quiz.list.review', compact('review'));
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    /**
     * Start quiz resource
     */
    // public function start(Quiz $quiz, Request $request)
    // {
    //     Session::forget('quiz');
    //     if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
    //         return response()->json(['result' => $quiz], 200);
    //     } else {
    //         if (!empty($request->all())) {
    //             return redirect()->route('quiz.start', ['quiz' => $quiz->id]);
    //         }

    //         // Tentukan percakapan pertama
    //         $attempt_number = 1;

    //         // Simpan percakapan pertama pada session
    //         session(['quiz_attempt' => $attempt_number]);

    //         $data['quiz'] = $quiz;
    //         return view('quiz.play.start', $data);
    //     }
    // }


    // public function auth(Request $request)
    // {
    //     $request->validate([
    //         'quiz_id' => 'required',
    //         'code_access' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $checking = QuizAuthenticationAccess::whereNull('deleted_at')->where('quiz_id', $request->quiz_id)->where('code_access', $request->code_access)->where('password', $request->password)->first();

    //     if (!is_null($checking)) {
    //         Session::put('key', $checking->key);
    //         return redirect()->route('admin.quiz.play', ['quiz' => $checking->quiz_id]);
    //     } else {
    //         return redirect()->back()->with(['failed' => 'Kode Akses atau Password Tidak Sesuai!']);
    //     }
    // }

    /**
     * Play quiz resource
     */
    // public function play(Quiz $quiz, Request $request)
    // {
    //     try {

    //         if (User::find(auth()->user()->id)->hasRole('user')) {
    //             if (Session::has('key')) {
    //                 $check = QuizAuthenticationAccess::whereNull('deleted_at')->where('quiz_id', $quiz->id)->where('key', Session::get('key'))->first();

    //                 if (is_null($check)) {
    //                     return redirect()->route('quiz.listQuiz')->with(['failed' => 'Anda Tidak Memiliki Akses']);
    //                 }
    //             } else {
    //                 return redirect()->route('quiz.listQuiz')->with(['failed' => 'Anda Tidak Memiliki Akses']);
    //             }
    //         }

    //         if (Session::has('quiz')) {
    //             $quiz = Session::get('quiz');

    //             foreach ($quiz['quiz_question'] as $index_quiz_question => $quiz_question) {
    //                 $quiz['quiz_question'][$index_quiz_question]['is_active'] = false;
    //             }

    //             Session::forget('quiz');
    //         } else {
    //             $quiz = $quiz->with(['quizTypeUserAccess.typeUser', 'quizQuestion.quizAnswer'])->find($quiz->id)->toArray();
    //             $quiz['total_question'] = count($quiz['quiz_question']);

    //             // Generate It Has Random Question
    //             if ($quiz['is_random_question']) {
    //                 shuffle($quiz['quiz_question']);
    //             }

    //             // Quiz Question
    //             $question_number = 1;
    //             foreach ($quiz['quiz_question'] as $index_quiz_question => $quiz_question) {
    //                 // Adding Question Numbering
    //                 $quiz['quiz_question'][$index_quiz_question]['question_number'] = $question_number;
    //                 $quiz['quiz_question'][$index_quiz_question]['is_active'] = false;
    //                 $quiz['quiz_question'][$index_quiz_question]['answered'] = false;
    //                 $question_number++;

    //                 // Array of Answer
    //                 $quiz_answer_arr = $quiz_question['quiz_answer'];

    //                 // Generate It Has Random Another Correct Answer
    //                 if ($quiz_question['is_generate_random_answer']) {
    //                     $quiz_answer_arr = [];
    //                     $quiz_answer = collect($quiz_question['quiz_answer'])->where('is_answer', 1)->first();

    //                     if (!is_null($quiz_answer['attachment'])) {
    //                     } else {
    //                         // Generate Random Another Correct Answer Number Method
    //                         $range_num_min = '1';
    //                         $range_num_max = '9';

    //                         for ($index = 1; $index < strlen($quiz_answer['answer']); $index++) {
    //                             $range_num_min .= '0';
    //                             $range_num_max .= '9';
    //                         }

    //                         $quiz_answer_first = $quiz_answer;
    //                         $quiz_answer_first['answered'] = false;
    //                         $quiz_answer_first['is_answer'] = intval($quiz_answer_first['is_answer']);
    //                         array_push($quiz_answer_arr, collect($quiz_answer_first));

    //                         $answer_list = [intval($quiz_answer_first['answer'])];
    //                         for ($random_index = 1; $random_index <= 4; $random_index++) {

    //                             $answer =  $this->generateAnswerRandom(intval($range_num_min), intval($range_num_max), $answer_list);

    //                             array_push($quiz_answer_arr, collect([
    //                                 'quiz_question_id' => $quiz_answer['quiz_question_id'],
    //                                 'answer' => $answer,
    //                                 'attachment' => null,
    //                                 'is_answer' => 0,
    //                                 'answered' => false,
    //                                 'point' => 0,
    //                                 'created_at' => $quiz_answer['created_at'],
    //                                 'updated_at' => $quiz_answer['updated_at'],
    //                             ]));

    //                             array_push($answer_list, $answer);
    //                         }

    //                         shuffle($quiz_answer_arr);
    //                     }
    //                 } else {
    //                     foreach ($quiz_answer_arr  as $index => $quiz_answer) {
    //                         $quiz_answer_arr[$index]['answered'] = false;
    //                         $quiz_answer_arr[$index]['is_answer'] = intval($quiz_answer['is_answer']);
    //                         $quiz_answer_arr[$index] = collect($quiz_answer_arr[$index]);
    //                     }
    //                 }

    //                 // Generate It Has Random Answer
    //                 if ($quiz_question['is_random_answer']) {
    //                     shuffle($quiz_answer_arr);
    //                 }

    //                 // Picking New List Answer
    //                 $quiz['quiz_question'][$index_quiz_question]['quiz_answer'] = collect($quiz_answer_arr);
    //             }
    //         }

    //         $current_quiz = collect($quiz['quiz_question'])->where('question_number', isset($request->q) ? $request->q : 1)->first();
    //         $current_quiz['is_active'] = true;

    //         foreach ($quiz['quiz_question'] as $index => $question) {
    //             if ($question['question_number'] == $current_quiz['question_number'] && $question['id'] == $current_quiz['id']) {
    //                 $quiz['quiz_question'][$index] = $current_quiz;
    //             }
    //         }

    //         Session::forget('quiz');
    //         Session::put('quiz', $quiz);

    //         $data['quiz'] = $quiz;
    //         $data['quiz_question'] = $current_quiz;

    //         if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
    //             return response()->json(['result' => $data], 200);
    //         } else {
    //             if (isset($request->q)) {
    //                 return view('quiz.play.question', $data);
    //             }
    //             return view('quiz.play.index', $data);
    //         }
    //     } catch (Exception $e) {
    //         return redirect()
    //             ->back()
    //             ->with(['failed', $e->getMessage()]);
    //     }
    // }

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

    // public function answer(Request $request)
    // {
    //     if (Session::has('quiz')) {
    //         $quiz = Session::get('quiz');

    //         // Ambil soal dan jawaban saat ini berdasarkan nomor soal
    //         $current_quiz = collect($quiz['quiz_question'])->where('question_number', $request->q)->first();

    //         // Tandai jawaban sebelumnya sebagai tidak dijawab
    //         foreach ($current_quiz['quiz_answer'] as $index => $answer) {
    //             $current_quiz['quiz_answer'][$index]['answered'] = false;
    //         }

    //         // Update jawaban yang dipilih pengguna
    //         $selected_answer = collect($current_quiz['quiz_answer'])->where('answer', $request->value)->first();
    //         $selected_answer['answered'] = true;

    //         // Simpan kembali jawaban ke dalam sesi
    //         foreach ($quiz['quiz_question'] as $index => $question) {
    //             if ($question['question_number'] == $current_quiz['question_number'] && $question['id'] == $current_quiz['id']) {
    //                 $quiz['quiz_question'][$index] = $current_quiz;

    //                 foreach ($quiz['quiz_question'][$index]['quiz_answer'] as $num => $answer) {
    //                     if ($selected_answer['answer'] == $answer['answer']) {
    //                         $quiz['quiz_question'][$index]['quiz_answer'][$num] = collect($selected_answer);
    //                     }
    //                 }
    //             }
    //         }

    //         // Simpan kembali seluruh sesi quiz yang telah diperbarui
    //         Session::forget('quiz');
    //         Session::put('quiz', $quiz);

    //         return response()->json(['message' => 'Jawaban Berhasil Disimpan'], 200);
    //     } else {
    //         return response()->json(['message' => 'Session Telah Habis'], 401);
    //     }
    // }

    // public function finish(Request $request, Quiz $quiz)
    // {
    //     if (Session::has('quiz')) {
    //         $quiz_session = Session::get('quiz');

    //         $data['quiz'] = $quiz;
    //         $total_point = 0;

    //         // Hitung total poin hanya dari jawaban terakhir yang dipilih
    //         foreach ($quiz_session['quiz_question'] as $question) {
    //             $selected_answer = collect($question['quiz_answer'])->where('answered', true)->first();
    //             if ($selected_answer) {
    //                 $total_point += $selected_answer['point'];
    //             }
    //         }

    //         $data['total_point'] = $total_point;

    //         // Insert hasil ke database (misalnya pada QuizUserAnswer dan QuizUserResult)
    //         $user_id = Auth::user()->id;
    //         $attempt_number = session('quiz_attempt', 1);

    //         // Simpan hasil kuis
    //         $quizUserResult =  QuizUserResult::create([
    //             'quiz_id' => $quiz->id,
    //             'user_id' => $user_id,
    //             'total_score' => $total_point
    //         ]);

    //         // Simpan data jawaban pengguna
    //         foreach ($quiz_session['quiz_question'] as $question) {
    //             $selected_answer = collect($question['quiz_answer'])->where('answered', true)->first();
    //             if ($selected_answer) {
    //                 $quizUserAnswer = QuizUserAnswer::create([
    //                     'quiz_id' => $quiz->id,
    //                     'user_id' => $user_id,
    //                     'quiz_question_id' => $question['id'],
    //                     'quiz_answer_id' => $selected_answer['id'],
    //                     'is_correct' => $selected_answer['is_answer'],
    //                     'point' => $selected_answer['point'],
    //                     'attempt_number' => $attempt_number,
    //                 ]);

    //                 QuizUserAnswerResult::create([
    //                     'quiz_user_answer_id' => $quizUserAnswer->id,
    //                     'quiz_user_result_id' => $quizUserResult->id,
    //                 ]);
    //             }
    //         }



    //         // Tambahkan 1 pada attempt_number untuk percakapan berikutnya
    //         session(['quiz_attempt' => $attempt_number + 1]);

    //         QuizAuthenticationAccess::where('key', Session::get('key'))->update([
    //             'deleted_at' => date('Y-m-d H:i:s')
    //         ]);
    //         Session::forget('key');
    //         Session::forget('quiz');
    //         return view('quiz.result', $data);
    //     } else {
    //         return redirect()->route('admin.quiz.start', ['quiz' => $quiz->id])->with(['failed' => 'Sesi Anda Telah Habis']);
    //     }
    // }



    // public function append(Request $request)
    // {
    //     if (isset($request->question) || isset($request->answer)) {
    //         if ($request->question) {
    //             return $this->appendQuestion(null, $request->increment);
    //         } else {
    //             return $this->appendAnswer(null, $request->increment, $request->parent);
    //         }
    //     } else {
    //         return response()->json(['message' => 'Gagal'], 400);
    //     }
    // }

    // private function appendQuestion(QuizQuestion $quiz_question = null, $increment, $disabled = '')
    // {
    //     $data['disabled'] = $disabled;
    //     $data['quiz_question'] = $quiz_question;
    //     $data['increment'] = $increment;

    //     if (!is_null($quiz_question)) {
    //         foreach ($quiz_question->quizAnswer as $index => $quiz_answer) {
    //             if (is_null($quiz_answer->deleted_at)) {
    //                 $data['quiz_answer'][$index + 1] = $this->appendAnswer($quiz_answer, $index + 1, $increment, $disabled);
    //             }
    //         }
    //     }

    //     return view('quiz.form.question', $data);
    // }

    // public function store(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $quiz = Quiz::create([
    //             'name' => $request->name,
    //             'type_quiz_id' => $request->type_quiz,
    //             'is_random_question' => isset($request->is_random_question),
    //             'description' => $request->description,
    //             'open_quiz' => isset($request->open_quiz) ? $request->open_quiz : null,
    //             'close_quiz' => isset($request->close_quiz) ? $request->close_quiz : null,
    //             'time_duration' => $request->time_duration,
    //         ]);

    //         $quiz_type_user_access_request = [];
    //         foreach ($request->quiz_type_user as $type_user_id) {
    //             $quiz_type_user_access_request[] = [
    //                 'quiz_id' => $quiz->id,
    //                 'type_user_id' => $type_user_id,
    //             ];
    //         }

    //         $quiz_type_user_access = QuizTypeUserAccess::insert($quiz_type_user_access_request);
    //         $time_duration_quiz = intval($request->time_duration);

    //         if ($quiz && $quiz_type_user_access) {
    //             foreach ($request->quiz_question as $index => $quiz_question_request) {

    //                 $quiz_question_duration = null;

    //                 if (!is_null($time_duration_quiz)) {
    //                     if (!is_null($quiz_question_request['time_duration'])) {
    //                         if ($time_duration_quiz - intval($quiz_question_request['time_duration']) >= 0) {
    //                             $quiz_question_duration = $quiz_question_request['time_duration'];
    //                             $time_duration_quiz -= intval($quiz_question_request['time_duration']);
    //                         } else {
    //                             DB::rollBack();
    //                             return redirect()
    //                                 ->back()
    //                                 ->with(['failed' => 'Waktu Durasi Tidak Sesuai'])
    //                                 ->withInput();
    //                         }
    //                     }
    //                 } else {
    //                     $quiz_question_duration = $quiz_question_request['time_duration'];
    //                 }

    //                 $quiz_question = QuizQuestion::create([
    //                     'quiz_id' => $quiz->id,
    //                     'is_random_answer' => isset($quiz_question_request['is_random_answer']),
    //                     'is_generate_random_answer' => isset($quiz_question_request['is_generate_random_answer']),
    //                     'order' => $index,
    //                     'direction_question' => $quiz_question_request['direction_question'],
    //                     'question' => $quiz_question_request['question'],
    //                     'description' => $quiz_question_request['description'],
    //                     'time_duration' => $quiz_question_duration,
    //                 ]);

    //                 if (!$quiz_question) {
    //                     DB::rollBack();
    //                     return redirect()
    //                         ->back()
    //                         ->with(['failed' => 'Gagal Simpan Pertanyaan'])
    //                         ->withInput();
    //                 }

    //                 foreach ($quiz_question_request['quiz_answer'] as $quiz_answer_request) {
    //                     $quiz_answer = QuizAnswer::create([
    //                         'quiz_question_id' => $quiz_question->id,
    //                         'answer' => $quiz_answer_request['answer'],
    //                         'point' => $quiz_answer_request['point'],
    //                         'is_answer' => isset($quiz_answer_request['is_answer']),
    //                     ]);

    //                     if (!$quiz_answer) {
    //                         DB::rollBack();
    //                         return redirect()
    //                             ->back()
    //                             ->with(['failed' => 'Gagal Simpan Pertanyaan'])
    //                             ->withInput();
    //                     }
    //                 }
    //             }

    //             DB::commit();
    //             return redirect()
    //                 ->route('admin.quiz.index')
    //                 ->with(['success' => 'Berhasil Simpan Quiz']);
    //         } else {
    //             return redirect()
    //                 ->back()
    //                 ->with(['failed' => 'Gagal Simpan Quiz'])
    //                 ->withInput();
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()
    //             ->back()
    //             ->with(['failed' => $e->getMessage()])
    //             ->withInput();
    //     }
    // }

    // public function edit(Quiz $quiz)
    // {
    //     $data['disabled'] = '';
    //     $data['quiz'] = $quiz;
    //     $data['type_quiz'] = TypeQuiz::whereNull('deleted_at')->get();
    //     $data['type_user'] = TypeUser::whereNull('deleted_at')->get();

    //     $data['quiz_question'] = '';
    //     foreach ($quiz->quizQuestion as $index => $question) {
    //         if (is_null($question->deleted_at)) {
    //             $data['quiz_question'] .= $this->appendQuestion($question, $index + 1);
    //         }
    //     }

    //     return view('quiz.edit', $data);
    // }

    // public function update(Request $request, Quiz $quiz)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $quiz_update = $quiz->update([
    //             'name' => $request->name,
    //             'type_quiz_id' => $request->type_quiz,
    //             'is_random_question' => isset($request->is_random_question),
    //             'description' => $request->description,
    //             'open_quiz' => isset($request->open_quiz) ? $request->open_quiz : null,
    //             'close_quiz' => isset($request->close_quiz) ? $request->close_quiz : null,
    //             'time_duration' => $request->time_duration,
    //         ]);

    //         /**
    //          * Clear Last Record
    //          */
    //         $deleted_quiz_type_user_access = QuizTypeUserAccess::where('quiz_id', $quiz->id)->delete();
    //         $last_quiz_question = $quiz->quizQuestion->pluck('id')->toArray();
    //         // $deleted_quiz_answer = QuizAnswer::whereIn('quiz_question_id', QuizQuestion::where('quiz_id', $quiz->id)->pluck('id')->toArray())->delete();
    //         // $deleted_quiz_question = QuizQuestion::where('quiz_id',  $quiz->id)->delete();

    //         if ($quiz_update && $deleted_quiz_type_user_access) {

    //             $quiz_type_user_access_request = [];
    //             foreach ($request->quiz_type_user as $type_user_id) {
    //                 $quiz_type_user_access_request[] = [
    //                     'quiz_id' => $quiz->id,
    //                     'type_user_id' => $type_user_id,
    //                 ];
    //             }
    //             $quiz_type_user_access = QuizTypeUserAccess::insert($quiz_type_user_access_request);
    //             $time_duration_quiz = intval($request->time_duration);

    //             if ($quiz_type_user_access) {

    //                 foreach ($request->quiz_question as $index => $quiz_question_request) {

    //                     if (!is_null($time_duration_quiz)) {
    //                         if (!is_null($quiz_question_request['time_duration'])) {
    //                             if ($time_duration_quiz - intval($quiz_question_request['time_duration']) >= 0) {
    //                                 $quiz_question_duration = $quiz_question_request['time_duration'];
    //                                 $time_duration_quiz -= $quiz_question_duration;
    //                             } else {
    //                                 DB::rollBack();
    //                                 return redirect()
    //                                     ->back()
    //                                     ->with(['failed' => 'Waktu Durasi Tidak Sesuai'])
    //                                     ->withInput();
    //                             }
    //                         } else {
    //                             $quiz_question_duration = 0;
    //                             $time_duration_quiz -= $quiz_question_duration;
    //                         }
    //                     } else {
    //                         $quiz_question_duration = $quiz_question_request['time_duration'];
    //                     }

    //                     if (isset($quiz_question_request['id'])) {

    //                         $last_quiz_answer = $quiz->quizQuestion->where('id', $quiz_question_request['id'])->first()->quizAnswer->pluck('id')->toArray();

    //                         $quiz_question = QuizQuestion::where('id', $quiz_question_request['id'])->update([
    //                             'quiz_id' => $quiz->id,
    //                             'is_random_answer' => isset($quiz_question_request['is_random_answer']),
    //                             'is_generate_random_answer' => isset($quiz_question_request['is_generate_random_answer']),
    //                             'order' => $index,
    //                             'direction_question' => $quiz_question_request['direction_question'],
    //                             'question' => $quiz_question_request['question'],
    //                             'description' => $quiz_question_request['description'],
    //                             'time_duration' => $quiz_question_duration,
    //                         ]);

    //                         if (!$quiz_question) {
    //                             DB::rollBack();
    //                             return redirect()
    //                                 ->back()
    //                                 ->with(['failed' => 'Gagal Simpan Pertanyaan'])
    //                                 ->withInput();
    //                         }

    //                         foreach ($quiz_question_request['quiz_answer'] as $quiz_answer_request) {

    //                             if (isset($quiz_answer_request['id'])) {
    //                                 $quiz_answer = QuizAnswer::where('id', $quiz_answer_request['id'])->update([
    //                                     'quiz_question_id' => $quiz_question_request['id'],
    //                                     'answer' => $quiz_answer_request['answer'],
    //                                     'point' => $quiz_answer_request['point'],
    //                                     'is_answer' => isset($quiz_answer_request['is_answer']),
    //                                 ]);

    //                                 if (($key_answer_array = array_search($quiz_answer_request['id'], $last_quiz_answer)) !== false) {
    //                                     unset($last_quiz_answer[$key_answer_array]);
    //                                 }
    //                             } else {
    //                                 $quiz_answer = QuizAnswer::create([
    //                                     'quiz_question_id' => $quiz_question_request['id'],
    //                                     'answer' => $quiz_answer_request['answer'],
    //                                     'point' => $quiz_answer_request['point'],
    //                                     'is_answer' => isset($quiz_answer_request['is_answer']),
    //                                 ]);
    //                             }

    //                             if (!$quiz_answer) {
    //                                 DB::rollBack();
    //                                 return redirect()
    //                                     ->back()
    //                                     ->with(['failed' => 'Gagal Simpan Pertanyaan'])
    //                                     ->withInput();
    //                             }
    //                         }

    //                         if (!empty($last_quiz_answer)) {
    //                             $quiz_answer_destroy = QuizAnswer::whereIn('id', $last_quiz_answer)
    //                                 ->update(['deleted_at' => date('Y-m-d H:i:s')]);

    //                             if (!$quiz_answer_destroy) {
    //                                 DB::rollBack();
    //                                 return redirect()
    //                                     ->back()
    //                                     ->with(['failed' => 'Gagal Hapus Jawaban'])
    //                                     ->withInput();
    //                             }
    //                         }

    //                         if (($key_question_array = array_search($quiz_question_request['id'], $last_quiz_question)) !== false) {
    //                             unset($last_quiz_question[$key_question_array]);
    //                         }
    //                     } else {

    //                         $quiz_question = QuizQuestion::create([
    //                             'quiz_id' => $quiz->id,
    //                             'is_random_answer' => isset($quiz_question_request['is_random_answer']),
    //                             'is_generate_random_answer' => isset($quiz_question_request['is_generate_random_answer']),
    //                             'order' => $index,
    //                             'direction_question' => $quiz_question_request['direction_question'],
    //                             'question' => $quiz_question_request['question'],
    //                             'description' => $quiz_question_request['description'],
    //                             'time_duration' => $quiz_question_duration,
    //                         ]);

    //                         if (!$quiz_question) {
    //                             DB::rollBack();
    //                             return redirect()
    //                                 ->back()
    //                                 ->with(['failed' => 'Gagal Simpan Pertanyaan'])
    //                                 ->withInput();
    //                         }

    //                         foreach ($quiz_question_request['quiz_answer'] as $quiz_answer_request) {
    //                             $quiz_answer = QuizAnswer::create([
    //                                 'quiz_question_id' => $quiz_question->id,
    //                                 'answer' => $quiz_answer_request['answer'],
    //                                 'point' => $quiz_answer_request['point'],
    //                                 'is_answer' => isset($quiz_answer_request['is_answer']),
    //                             ]);

    //                             if (!$quiz_answer) {
    //                                 DB::rollBack();
    //                                 return redirect()
    //                                     ->back()
    //                                     ->with(['failed' => 'Gagal Simpan Pertanyaan'])
    //                                     ->withInput();
    //                             }
    //                         }
    //                     }
    //                     $quiz_question_duration = null;
    //                 }

    //                 if (!empty($last_quiz_question)) {
    //                     $quiz_question_destroy = QuizQuestion::whereIn('id', $last_quiz_question)
    //                         ->update(['deleted_at' => date('Y-m-d H:i:s')]);

    //                     if (!$quiz_question_destroy) {
    //                         DB::rollBack();
    //                         return redirect()
    //                             ->back()
    //                             ->with(['failed' => 'Gagal Hapus Pertanyaan'])
    //                             ->withInput();
    //                     }
    //                 }

    //                 DB::commit();
    //                 return redirect()
    //                     ->route('admin.quiz.index')
    //                     ->with(['success' => 'Berhasil Simpan Quiz']);
    //             } else {
    //                 return redirect()
    //                     ->back()
    //                     ->with(['failed' => 'Gagal Perbarui Akses Quiz'])
    //                     ->withInput();
    //             }
    //         } else {
    //             return redirect()
    //                 ->back()
    //                 ->with(['failed' => 'Gagal Perbarui Quiz'])
    //                 ->withInput();
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()
    //             ->back()
    //             ->with(['failed' => $e->getMessage()])
    //             ->withInput();
    //     }
    // }

    // private function appendAnswer(QuizAnswer $quiz_answer = null, $increment, $parent, $disabled = '')
    // {
    //     $data['disabled'] = $disabled;
    //     $data['quiz_answer'] = $quiz_answer;
    //     $data['increment'] = $increment;
    //     $data['parent'] = $parent;
    //     return view('quiz.form.answer', $data);
    // }

    // private function generateAnswerRandom(int $min, int $max, array $exception)
    // {
    //     do {
    //         $answer = rand($min, $max);
    //     } while (in_array($answer, $exception));

    //     return $answer;
    // }
}
