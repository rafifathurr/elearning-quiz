<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\AspectQuestion;
use App\Models\PackageTest;
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
                ->addColumn('action', function ($data) {
                    // $btn_action = '<a href="' . route('admin.quiz.show', ['quiz' => $data->id]) . '" class="btn btn-sm btn-info my-1"><i class="fas fa-eye"></i></a>';
                    $btn_action = '<a href="' . route('admin.quiz.edit', ['quiz' => $data->id]) . '" class="btn btn-sm btn-warning my-1 ml-1"><i class="fas fa-pencil-alt"></i></a>';
                    $btn_action .= '<a href="' . route('admin.quiz.showQuestion', ['quiz' => $data->id]) . '" class="btn btn-sm btn-info my-1 ml-1"><i class="fas fa-search"></i></a>';
                    $btn_action .= '<a href="' . route('admin.quiz.start', ['quiz' => encrypt($data->id)]) . '" class="btn btn-sm btn-success my-1 ml-1"><i class="fas fa-play"></i></a>';
                    $btn_action .= '<button onclick="destroyRecord(' . $data->id . ')" class="btn btn-sm btn-danger my-1 ml-1"><i class="fas fa-trash"></i></button>';
                    return $btn_action;
                })
                ->only(['name', 'type_aspect', 'action'])
                ->rawColumns(['action'])
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
        // Debugging untuk memastikan data diterima
        Log::info("Data request: ", $request->all());

        // Cek apakah type_aspect dan increment ada
        if ($request->has('type_aspect') && $request->has('increment')) {
            $type_aspect = $request->type_aspect; // Ambil tipe aspek
            $increment = $request->increment; // Ambil increment

            // Lakukan pemrosesan untuk menambahkan aspek
            return $this->appendAspect(null, $increment, '', $type_aspect);
        }

        // Jika tidak ada type_aspect atau increment
        return response()->json(['message' => 'Bad Request: Missing parameters'], 400);
    }


    private function appendAspect($quiz_aspect = null, $increment, $disabled = '', $type_aspect = null)
    {
        // Pastikan type_aspect ada dan valid
        if ($type_aspect) {
            // Ambil aspek berdasarkan type_aspect
            $query = AspectQuestion::whereNull('deleted_at')->where('type_aspect', $type_aspect);
            $aspect_question = $query->get();
        } else {
            // Jika tidak ada type_aspect, ambil semua
            $aspect_question = AspectQuestion::whereNull('deleted_at')->get();
        }

        // Kirim data ke view
        $data['disabled'] = $disabled;
        $data['quiz_aspect'] = $quiz_aspect;
        $data['increment'] = $increment;
        $data['aspect_question'] = $aspect_question;

        // Return view
        return view('quiz.form.aspect', $data);
    }




    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $quiz = Quiz::create([
                'name' => $request->name,
                'description' => $request->description,
                'type_aspect' => $request->type_aspect,
                'time_duration' => $request->time_duration,
            ]);


            if ($quiz) {
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
                            ->with(['failed' => 'Gagal Simpan Aspek Test'])
                            ->withInput();
                    }
                }

                DB::commit();
                return redirect()
                    ->route('admin.quiz.index')
                    ->with(['success' => 'Berhasil Simpan Test']);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Simpan Test'])
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
        $data['type_aspect'] = $quiz->type_aspect;

        $data['quiz_aspect'] = '';
        foreach ($quiz->quizAspect as $index => $aspect) {
            if (is_null($aspect->deleted_at)) {
                $data['quiz_aspect'] .= $this->appendAspect($aspect, $index + 1, '', $data['type_aspect']);
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
                'type_aspect' => $request->type_aspect,
                'time_duration' => $request->time_duration,
            ]);

            /**
             * Clear Last Record
             */
            $last_quiz_aspect = $quiz->quizAspect->pluck('id')->toArray();

            if ($quiz_update) {
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
                                ->with(['failed' => 'Gagal Simpan Aspek Test'])
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
                                ->with(['failed' => 'Gagal Simpan Aspek Test'])
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
                            ->with(['failed' => 'Gagal Hapus Aspek Test'])
                            ->withInput();
                    }
                }

                DB::commit();
                return redirect()
                    ->route('admin.quiz.index')
                    ->with(['success' => 'Berhasil Simpan Test']);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Test'])
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
            $deleted_package_test = PackageTest::where('quiz_id', $quiz->id)->delete();

            // Validation Destroy Quiz
            if ($quiz_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Test');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Test');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }

    public function showQuestion(Quiz $quiz)
    {
        $questions = collect();

        // Ambil pertanyaan berdasarkan aspek dan level
        foreach ($quiz->quizAspect as $aspect) {
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



            // Gabungkan semua pertanyaan ke dalam koleksi
            $questions = $questions->merge($questionSet);
        }

        // Tambahkan jawaban untuk setiap pertanyaan
        $questions->each(function ($question) {
            $question->quizAnswer = $question->quizAnswer()->whereNull('deleted_at')->get();
        });

        // Acak ulang seluruh pertanyaan (aspek, level, dan pertanyaan)
        $shuffledQuestions = $questions->shuffle();

        // Hitung total pertanyaan
        $totalQuestions = $shuffledQuestions->count();

        // Kirim ke view
        return view('quiz.preview', compact('shuffledQuestions', 'totalQuestions'));
    }


    public function start($encryptedQuizId, Request $request)
    {
        try {
            // Dekripsi ID quiz yang diterima melalui URL
            $quizId = decrypt($encryptedQuizId);
            $quiz = Quiz::findOrFail($quizId);  // Temukan quiz berdasarkan ID yang didekripsi

            // Inisialisasi orderDetailId sebagai null
            $orderDetailId = null;

            // Cek jika order_detail_id ada di request dan dekripsi jika ada
            if ($request->has('order_detail_id')) {
                $encryptedOrderDetailId = $request->get('order_detail_id');
                $orderDetailId = decrypt($encryptedOrderDetailId);
            }
        } catch (\Exception $e) {
            abort(403, 'Invalid ID');
        }

        $questions = collect();

        // Ambil pertanyaan berdasarkan aspek dan level
        foreach ($quiz->quizAspect as $aspect) {
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

            $questions = $questions->merge($questionSet);
        }

        // Tambahkan jawaban untuk setiap pertanyaan
        $questions->each(function ($question) {
            $question->quizAnswer = $question->quizAnswer()->whereNull('deleted_at')->get();
        });

        // Acak ulang seluruh pertanyaan
        $shuffledQuestions = $questions->shuffle();
        $totalQuestions = $shuffledQuestions->count();

        // Jika permintaan berbasis API (JSON)
        if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
            return response()->json([
                'result' => $quiz,
                'total_questions' => $totalQuestions,
            ], 200);
        } else {
            // Kirim data ke tampilan
            return view('quiz.play.start', [
                'quiz' => $quiz,
                'orderDetailId' => $orderDetailId,
                'totalQuestions' => $totalQuestions,
            ]);
        }
    }


    public function play(Quiz $quiz, Request $request)
    {
        DB::beginTransaction();
        try {

            $encryptedId = $request->get('order_detail_id');
            $orderDetailId = decrypt($encryptedId);
            // Buat entri Result baru
            $result = Result::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::user()->id,
                'start_time' => now(),
                'time_duration' => $quiz->time_duration,
                'order_detail_id' => $orderDetailId,
            ]);

            Log::info('New result created with ID: ' . $result->id);

            $order = 0; // Pertanyaan pertama dimulai dari order 0
            $questionAspectPairs = []; // Menyimpan pasangan pertanyaan dan aspek

            // Proses pengambilan soal dan pengacakan aspek
            foreach ($quiz->quizAspect as $aspect) {
                // Ambil pertanyaan berdasarkan level dan aspek
                $questionSet = QuizQuestion::where(function ($query) use ($aspect) {
                    $query->where('level', 'like', '%' . '|' . $aspect->level . '|' . '%')
                        ->orWhere('level', '0');
                })
                    ->where(function ($query) use ($aspect) {
                        $query->where('aspect', 'like', '%' . '|' . $aspect->aspect_id . '|' . '%')
                            ->orWhere('aspect', '0');
                    })
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->when($aspect->total_question > 0, function ($query) use ($aspect) {
                        return $query->limit($aspect->total_question);
                    })
                    ->get();


                foreach ($questionSet as $question) {
                    $questionAspectPairs[] = [
                        'question_id' => $question->id,
                        'aspect_id' => $aspect->aspect_id,
                        'level' => $aspect->level,
                        'question_detail' => json_encode([
                            'direction_question' => $question->direction_question,
                            'description' => $question->description,
                            'question' => $question->question,
                            'attachment' => $question->attachment,
                            'is_random_answer' => $question->is_random_answer,
                            'is_generate_random_answer' => $question->is_generate_random_answer,
                        ]),
                    ];
                }
            }

            // Acak pasangan soal dan aspek
            shuffle($questionAspectPairs);

            // Simpan pasangan soal dan aspek ke result_details
            foreach ($questionAspectPairs as $pair) {
                try {

                    $order++;
                    ResultDetail::create([
                        'result_id' => $result->id,
                        'question_id' => $pair['question_id'],
                        'question_detail' => $pair['question_detail'],
                        'aspect_id' => $pair['aspect_id'],
                        'level' =>  $pair['level'],
                        'order' => $order,
                    ]);
                } catch (Exception $e) {
                    // Tangani duplikat tanpa menghentikan proses
                    Log::warning('Duplicate question skipped for result_id: ' . $result->id . ' and question_id: ' . $pair['question_id']);
                    $order--; // Kurangi order jika gagal menyimpan
                }
            }

            // Tampilkan waktu untuk pertanyaan pertama
            ResultDetail::where('result_id', $result->id)->where('order', 1)->update([
                'display_time' => now()
            ]);

            DB::commit();
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
            DB::rollBack();
            // Tangani error
            Log::error('Error in starting quiz: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    public function getQuestion(Result $result, Request $request)
    {
        try {
            Log::info('Request Headers:', $request->headers->all());
            Log::info('Request Data:', $request->all());
            Log::info('Request Accepts JSON: ' . ($request->wantsJson() ? 'Yes' : 'No'));
            Log::info('Result ID from route: ' . $result->id);

            // Ambil seluruh ResultDetail terkait quiz dan user
            $resultDetails = $result->details()->orderBy('display_time', 'desc')->get();

            if ($result->user_id != Auth::user()->id) {
                session()->flash('failed', 'Tidak Dibenarkan Mengakses Test Orang Lain');
                return redirect()->back();
            }

            if ($resultDetails->isEmpty()) {
                return response()->json(['message' => 'Tidak ada pertanyaan untuk kuis ini'], 404);
            }

            // Persiapkan data untuk setiap soal
            $questions = $resultDetails->map(function ($resultDetail) {
                $question = QuizQuestion::find($resultDetail->question_id);

                $questionDetail = json_decode($resultDetail->question_detail, true);


                if ($questionDetail['is_generate_random_answer']) {
                    $quizAnswerArr = []; // Menyimpan jawaban untuk pertanyaan ini

                    // Ambil jawaban asli yang memiliki is_answer = 1
                    $correctAnswer = $question->quizAnswer->where('is_answer', 1)->first();

                    if (!is_null($correctAnswer)) {
                        // Tambahkan jawaban asli ke array jawaban
                        $correctAnswer['answered'] = false; // Menandai jawaban belum dijawab
                        $correctAnswer['is_answer'] = intval($correctAnswer['is_answer']); // Pastikan is_answer berupa integer
                        $quizAnswerArr[] = $correctAnswer;

                        // Generate jawaban random lainnya
                        $answerList = [intval($correctAnswer['answer'])]; // Daftar jawaban untuk menghindari duplikasi
                        $rangeNumMin = 10 ** (strlen($correctAnswer['answer']) - 1); // Nilai minimum (contoh: 100 untuk angka 3 digit)
                        $rangeNumMax = (10 ** strlen($correctAnswer['answer'])) - 1; // Nilai maksimum (contoh: 999 untuk angka 3 digit)

                        for ($i = 1; $i <= 4; $i++) { // Tambahkan 4 jawaban random
                            $randomAnswer = $this->generateAnswerRandom($rangeNumMin, $rangeNumMax, $answerList);

                            $quizAnswerArr[] = [
                                'quiz_question_id' => $correctAnswer['quiz_question_id'],
                                'answer' => $randomAnswer,
                                'attachment' => null,
                                'is_answer' => 0, // Jawaban ini bukan jawaban yang benar
                                'answered' => false,
                                'point' => 0,
                                'created_at' => $correctAnswer['created_at'],
                                'updated_at' => $correctAnswer['updated_at'],
                                'answer_image' => $correctAnswer['answer_image'],
                            ];

                            $answerList[] = $randomAnswer; // Tambahkan jawaban ke daftar untuk menghindari duplikasi
                        }
                    }

                    // Acak urutan jawaban sebelum mengembalikan
                    shuffle($quizAnswerArr);
                } else {
                    $quizAnswerArr = $question->quizAnswer->map(function ($quiz_answer) {
                        return [
                            'id' => $quiz_answer->id,
                            'answer' => $quiz_answer->answer,
                            'answer_image' => $quiz_answer->answer_image,
                            'is_answer' => intval($quiz_answer->is_answer),
                            'answered' => false,
                        ];
                    })->toArray();
                }


                if ($questionDetail['is_random_answer']) {
                    shuffle($quizAnswerArr);
                }
                return [
                    'id' => $resultDetail->question_id,
                    'question_number' => $resultDetail->order,
                    'direction_question' => $questionDetail['direction_question'],
                    'question' => $questionDetail['question'],
                    'description' => $questionDetail['description'],
                    'attachment' => $questionDetail['attachment'],
                    'is_random_answer' => $questionDetail['is_random_answer'],
                    'is_generate_random_answer' => $questionDetail['is_generate_random_answer'],
                    'aspect_id' => $resultDetail->aspect_id,
                    'aspect_name' => $resultDetail->aspect->name,
                    'level' => $resultDetail->level,
                    'order' => $resultDetail->order,
                    'display_time' => $resultDetail->display_time,
                    'quiz_answer' => $quizAnswerArr,
                    'is_active' => false,
                    'answered' => !empty($resultDetail->answer),
                    'user_answer' => $resultDetail->answer,
                ];
            });

            // Tentukan pertanyaan aktif
            $activeQuestionNumber = $request->has('q')
                ? (int) $request->input('q') // Jika `q` ada di request, gunakan nilai tersebut
                : $resultDetails->first()->order; // Jika tidak ada, gunakan pertanyaan dengan display_time terbaru

            $questions = $questions->transform(function ($item) use ($activeQuestionNumber) {
                $item['is_active'] = $item['question_number'] == $activeQuestionNumber;
                return $item;
            });

            $activeQuestion = $questions->firstWhere('is_active', true);
            $questionList = $questions->sortBy('question_number')->values();

            // Persiapkan data untuk API atau tampilan
            $quizData = Quiz::find($result->quiz_id)->toArray();
            $data = [
                'quiz' => $quizData,
                'result' => $result,
                'questions' => $questions,
                'questionList' => $questionList,
                'active_question' => $activeQuestion,
                'total_question' => $questions->count(),
            ];

            if ($request->wantsJson() || str_starts_with($request->path(), 'api')) {
                Log::info('Sending JSON Response');
                return response()->json(['result' => $data], 200);
            } else {
                if ($request->has('q')) {
                    Log::info('Render Question');
                    return view('quiz.play.question', $data);
                }
                Log::info('Rendering HTML View');
                return view('quiz.play.index', $data);
            }
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function answer(Request $request)
    {
        try {
            $validated = $request->validate([
                'value' => 'required',
                'resultId' => 'required|integer',
                'questionId' => 'required|integer',
            ]);

            Log::info('Question ID: ' . $request->questionId);
            Log::info('Result ID: ' . $request->resultId);

            // Simpan jawaban pengguna
            $resultDetail = ResultDetail::where('question_id', $request->questionId)
                ->where('result_id', $request->resultId)
                ->whereHas('result', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->firstOrFail();

            if (!$resultDetail) {
                Log::error('Data Result Tidak ditemukan: ');
                throw new Exception("Data result detail tidak ditemukan");
            }

            $question = QuizQuestion::find($request->questionId);

            $score = 0;
            foreach ($question->quizAnswer as $answer) {
                if (!is_null($answer->answer) && $request->value == $answer->answer && $answer->is_answer == 1) {
                    $score = 1;
                }

                if (!is_null($answer->answer_image) && $request->value == $answer->answer_image && $answer->is_answer == 1) {
                    $score = 1;
                }
            }

            $resultDetail->update([
                'answer' => $validated['value'],
                'score' => $score,
            ]);
            Log::info('Jawaban yang disimpan: ' . $resultDetail->answer);
            Log::info('Skor yang disimpan: ' . $resultDetail->score);

            return response()->json(['message' => 'Jawaban berhasil disimpan'], 200);
        } catch (Exception $e) {
            Log::error('Error pada pengolahan jawaban: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function lastQuestion(Request $request)
    {
        try {
            $request->validate([
                'q' => 'required|integer',
                'resultId' => 'required|integer',
            ]);

            Log::info('Order: ' . $request->q);

            // Simpan jawaban pengguna
            // Tentukan pertanyaan berikutnya berdasarkan order
            $nextResultDetail = ResultDetail::where('order', $request->q)
                ->where('result_id', $request->resultId)
                ->whereHas('result', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->first();

            if ($nextResultDetail) {
                $nextResultDetail->update([
                    'display_time' => now(),
                ]);
            } else {
                Log::info('Tidak ada pertanyaan berikutnya untuk order: ' . ($request->q));
            }


            return response()->json(['message' => 'Berhasil Pindah Halaman'], 200);
        } catch (Exception $e) {
            Log::error('Error pada pindah halaman: ' . $e->getMessage());
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
                if (!is_null($answer->answer) && $request->value == $answer->answer && $answer->is_answer == 1) {
                    $score = 1;
                }

                if (!is_null($answer->answer_image) && $request->value == $answer->answer_image && $answer->is_answer == 1) {
                    $score = 1;
                }
            }

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
            $result = Result::where('id', $resultId)
                ->where('user_id', Auth::id())
                ->with(['quiz', 'details.aspect']) // Pastikan memuat aspek terkait
                ->firstOrFail();

            // Hitung data per aspek
            $questionsPerAspect = $result->details
                ->groupBy('aspect_id')
                ->map(function ($details, $aspectId) {
                    $totalQuestions = $details->count();
                    $correctQuestions = $details->where('score', 1)->count();
                    $percentage = $totalQuestions > 0
                        ? ($correctQuestions / $totalQuestions) * 100
                        : 0;

                    return [
                        'aspect_name' => $details->first()->aspect->name ?? 'Unknown Aspect',
                        'total_questions' => $totalQuestions,
                        'correct_questions' => $correctQuestions,
                        'percentage' => round($percentage, 2), // Dibulatkan 2 desimal
                    ];
                });

            // Urutkan berdasarkan persentase tertinggi
            $questionsPerAspect = $questionsPerAspect->sortByDesc('percentage');

            return view('quiz.result', compact('result', 'questionsPerAspect'));
        } catch (\Exception $e) {
            Log::error("Error saat menampilkan hasil quiz: " . $e->getMessage());
            return redirect('/')->with('error', 'Gagal menampilkan hasil quiz.');
        }
    }

    private function generateAnswerRandom(int $min, int $max, array $exception)
    {
        do {
            $answer = rand($min, $max);
        } while (in_array($answer, $exception)); // Cek apakah angka sudah ada di daftar pengecualian

        return $answer;
    }



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
        $histories = Result::where('user_id', $user_id)
            ->orderBy('quiz_id')
            ->orderBy('id')
            ->paginate(6);

        // Menambahkan nomor attempt per quiz_id
        $histories->getCollection()->transform(function ($history) {
            $quiz_id = $history->quiz_id;
            // Hitung nomor attempt untuk quiz_id yang sama
            $attempt_number = Result::where('user_id', $history->user_id)
                ->where('quiz_id', $quiz_id)
                ->where('id', '<=', $history->id)
                ->count();

            $history->attempt_number = $attempt_number; // Menambahkan nomor attempt
            return $history;
        });

        return view('quiz.list.history', ['histories' => $histories]);
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
    // public function preview(Quiz $quiz, Request $request)
    // {
    //     $quiz = Session::get('quiz');
    //     Session::forget('quiz');

    //     if (!isset($request->q) || is_null(collect($quiz['quiz_question'])->where('question_number', $request->q)->first())) {
    //         if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
    //             return response()->json(['failed' => 'Permintaan Tidak Sesuai'], 404);
    //         } else {
    //             return redirect()
    //                 ->back()
    //                 ->with(['failed' => 'Permintaan Tidak Sesuai']);
    //         }
    //     } else {

    //         $current_quiz = collect($quiz['quiz_question'])->where('question_number', $request->q)->first();
    //         $current_quiz['is_active'] = true;

    //         foreach ($quiz['quiz_question'] as $index => $question) {
    //             if ($question['question_number'] == $current_quiz['question_number'] && $question['id'] == $current_quiz['id']) {
    //                 $quiz['quiz_question'][$index] = $current_quiz;
    //             }
    //         }

    //         Session::forget('quiz');
    //         Session::put('quiz', $quiz);

    //         $data['quiz'] = $quiz;
    //         $data['quiz_question'] = collect($quiz['quiz_question'])->where('question_number', $request->q)->first();

    //         if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
    //             return response()->json(['result' => $data], 200);
    //         } else {
    //             return view('quiz.play.index', $data);
    //         }
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
