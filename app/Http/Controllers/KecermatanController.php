<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Quiz;
use App\Models\Result;
use App\Models\ResultDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KecermatanController extends Controller
{
    public function create()
    {
        return view('master.kecermatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time_duration' => 'required|integer|min:1',
            'type_random_question' => 'required|array',
            'type_random_question.*' => 'required|string|in:angka,huruf',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
        ]);

        try {
            // Proses data untuk question_kecermatan
            $questions = [];

            foreach ($request->type_random_question as $index => $type) {
                $qty = $request->qty[$index];
                if ($type === 'angka') {
                    for ($i = 0; $i < $qty; $i++) {
                        $questions[] = [
                            'correct_answer' => rand(1, 99), // Angka acak 1-99
                        ];
                    }
                } elseif ($type === 'huruf') {
                    for ($i = 0; $i < $qty; $i++) {
                        $questions[] = [
                            'correct_answer' => chr(rand(65, 90)), // Huruf acak A-Z
                        ];
                    }
                }
            }

            // Acak urutan pertanyaan
            shuffle($questions);

            // Tambahkan nomor urut ke setiap pertanyaan
            foreach ($questions as $index => &$question) {
                $question['order'] = $index + 1;
            }

            // Simpan data ke dalam database
            $kecermatan = Quiz::create([
                'name' => $request->name,
                'type_aspect' => 'kecermatan',
                'time_duration' => $request->time_duration,
                'question_kecermatan' => json_encode($questions), // Simpan dalam format JSON
            ]);

            if ($kecermatan) {
                return redirect()
                    ->route('admin.quiz.index')
                    ->with(['success' => 'Berhasil Simpan Test Kecermatan']);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('failed', $e->getMessage());
        }
    }

    public function edit(Quiz $quiz)
    {
        // Decode JSON data from the database
        $questions = collect(json_decode($quiz->question_kecermatan, true));

        // Group by type and count occurrences
        $groupedQuestions = $questions->map(function ($item) {
            return [
                'type_random_question' => is_numeric($item['correct_answer']) ? 'angka' : 'huruf',
            ];
        })->groupBy('type_random_question')->map(function ($group, $key) {
            return [
                'type_random_question' => $key,
                'qty' => $group->count()
            ];
        })->values();

        return view('master.kecermatan.edit', compact('quiz', 'groupedQuestions'));
    }



    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time_duration' => 'required|integer|min:1',
            'type_random_question' => 'required|array',
            'type_random_question.*' => 'required|string|in:angka,huruf',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
        ]);

        try {
            $quiz = Quiz::findOrFail($id);

            // Proses data baru
            $questions = [];

            foreach ($request->type_random_question as $index => $type) {
                $qty = $request->qty[$index];
                if ($type === 'angka') {
                    for ($i = 0; $i < $qty; $i++) {
                        $questions[] = [
                            'correct_answer' => rand(1, 99), // Angka acak 1-99
                        ];
                    }
                } elseif ($type === 'huruf') {
                    for ($i = 0; $i < $qty; $i++) {
                        $questions[] = [
                            'correct_answer' => chr(rand(65, 90)), // Huruf acak A-Z
                        ];
                    }
                }
            }

            // Acak urutan pertanyaan
            shuffle($questions);

            // Tambahkan nomor urut ke setiap pertanyaan
            foreach ($questions as $index => &$question) {
                $question['order'] = $index + 1;
            }

            // Update quiz data
            $quiz->update([
                'name' => $request->name,
                'time_duration' => $request->time_duration,
                'question_kecermatan' => json_encode($questions),
            ]);

            return redirect()
                ->route('admin.quiz.index')
                ->with(['success' => 'Berhasil Edit Test Kecermatan']);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('failed', $e->getMessage());
        }
    }



    public function play(Quiz $quiz, Request $request)
    {
        DB::beginTransaction();
        try {

            $encryptedId = $request->get('order_detail_id');
            $orderDetailId = decrypt($encryptedId);

            $result = Result::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::user()->id,
                'start_time' => now(),
                'time_duration' => $quiz->time_duration,
                'order_detail_id' => $orderDetailId
            ]);

            Log::info('New result created with ID: ' . $result->id);


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
            return redirect()->route('kecermatan.getQuestion', ['result' => $result->id]);
        } catch (Exception $e) {
            DB::rollBack();
            // Tangani error
            Log::error('Error in starting quiz: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getQuestion(Result $result, Request $request)
    {
        // Decode the question data from the quiz
        $questionKecermatan = json_decode($result->quiz->question_kecermatan, true);

        if (!is_array($questionKecermatan) || empty($questionKecermatan)) {
            throw new Exception('Invalid question data');
        }

        // Ambil nomor pertanyaan aktif
        $activeQuestionNumber = 1; // Default value
        if ($request->has('q')) {

            $activeQuestionNumber = (int) $request->input('q');
        } elseif (isset($result->details) && $result->details->isNotEmpty()) {

            $resultDetails = $result->details()->orderBy('display_time', 'desc')->get();
            $activeQuestionNumber = $resultDetails->first()->order + 1;
        }


        // Cari pertanyaan aktif berdasarkan "order"
        $activeQuestion = collect($questionKecermatan)->firstWhere('order', $activeQuestionNumber);

        if (!$activeQuestion || !isset($activeQuestion['correct_answer'])) {
            throw new Exception('Correct answer not found in question data');
        }

        // Ambil jawaban benar
        $correctAnswer = [
            'answer' => $activeQuestion['correct_answer'],
            'attachment' => null,
            'answered' => false,
            'is_answer' => 1,
            'point' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $quizAnswerArr = [$correctAnswer];
        $answerList = [intval($correctAnswer['answer'])]; // Untuk menghindari duplikasi

        if (is_numeric($correctAnswer['answer'])) {
            // Generate random numeric answers
            $rangeNumMin = 10 ** (strlen($correctAnswer['answer']) - 1);
            $rangeNumMax = (10 ** strlen($correctAnswer['answer'])) - 1;

            for ($i = 1; $i <= 4; $i++) {
                $randomAnswer = $this->generateAnswerRandom($rangeNumMin, $rangeNumMax, $answerList);

                $quizAnswerArr[] = [
                    'answer' => $randomAnswer,
                    'attachment' => null,
                    'answered' => false,
                    'point' => 0,
                    'is_answer' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $answerList[] = $randomAnswer;
            }
        } else {
            // Generate random alphabetic answers
            $length = strlen($correctAnswer['answer']);

            for ($i = 1; $i <= 4; $i++) {
                $randomAnswer = $this->generateAnswerRandomLetters($answerList, $length);

                // Pastikan tidak ada duplikasi
                while (in_array($randomAnswer, $answerList)) {
                    $randomAnswer = $this->generateAnswerRandomLetters($answerList, $length);
                }

                $quizAnswerArr[] = [
                    'answer' => $randomAnswer,
                    'attachment' => null,
                    'answered' => false,
                    'point' => 0,
                    'is_answer' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $answerList[] = $randomAnswer;
            }
        }

        // Acak urutan jawaban sebelum mengembalikan
        shuffle($quizAnswerArr);

        $activeQuestion = [
            'question_number' => $activeQuestion['order'], // Menggunakan 'order'
            'direction_question' => $activeQuestion['direction_question'] ?? '', // Tambahkan petunjuk soal
            'quiz_answer' => $quizAnswerArr,
            'is_active' => true
        ];

        // Siapkan data untuk respons
        $quizData = $result->quiz->toArray();
        $data = [
            'quiz' => $quizData,
            'result' => $result,
            'questions' => $questionKecermatan,
            'active_question' => $activeQuestion,
            'total_question' => count($questionKecermatan),
            'quiz_answer' => $quizAnswerArr,
        ];

        // Return the response based on request type
        if ($request->wantsJson() || str_starts_with($request->path(), 'api')) {
            Log::info('Sending JSON Response');
            return response()->json(['result' => $data], 200);
        } else {
            if ($request->has('q')) {
                Log::info('Render Question');
                return view('master.kecermatan.play.question', $data);
            }

            Log::info('Rendering HTML View');
            return view('master.kecermatan.play.index', $data);
        }
    }

    public function answer(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'value' => 'required',
                'resultId' => 'required|integer',
                'questionNumber' => 'required|integer',
            ]);

            Log::info('Result ID: ' . $request->resultId);

            // Ambil data `result`
            $result = Result::findOrFail($validated['resultId']);
            $questionKecermatan = json_decode($result->quiz->question_kecermatan, true);


            // Cari pertanyaan aktif berdasarkan "order"
            $activeQuestion = collect($questionKecermatan)->firstWhere('order', $validated['questionNumber']);

            if (!$activeQuestion || !isset($activeQuestion['correct_answer'])) {
                return response()->json(['message' => 'Invalid question data'], 400);
            }

            // Ambil jawaban benar
            $correctAnswer = $activeQuestion['correct_answer'];

            // Tentukan skor berdasarkan jawaban yang diberikan
            if (is_numeric($request->value) && is_numeric($correctAnswer)) {
                // Jika keduanya angka, bandingkan sebagai angka
                $score = (int)$request->value === (int)$correctAnswer ? 1 : 0;
            } else {
                // Jika salah satu adalah huruf atau keduanya, bandingkan sebagai string
                $score = (string)$request->value === (string)$correctAnswer ? 1 : 0;
            }



            // Simpan jawaban pengguna
            $resultDetail = ResultDetail::create([
                'result_id' => $validated['resultId'],
                'answer' => $validated['value'],
                'order' => $validated['questionNumber'],
                'score' => $score,
                'display_time' => now(),
            ]);

            Log::info('Jawaban yang diberikan: ' . $request->value . ' Tipe: ' . gettype($request->value));
            Log::info('Jawaban yang benar: ' . $correctAnswer . ' Tipe: ' . gettype($correctAnswer));
            Log::info('Skor yang disimpan: ' . $resultDetail->score);

            return response()->json(['message' => 'Jawaban berhasil disimpan'], 200);
        } catch (Exception $e) {
            Log::error('Error pada pengolahan jawaban: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function finish(Request $request)
    {
        try {

            $validated = $request->validate([
                'resultId' => 'nullable|integer',
            ]);

            Log::info('Result ID: ' . $request->resultId);
            Log::info('Request Data:', $request->all());



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

            return view('quiz.result', compact('result'));
        } catch (\Exception $e) {
            Log::error("Error saat menampilkan hasil Test: " . $e->getMessage());
            return redirect('/')->with('error', 'Gagal menampilkan hasil Test.');
        }
    }


    private function generateAnswerRandom(int $min, int $max, array $exception)
    {
        do {
            $answer = rand($min, $max);
        } while (in_array($answer, $exception)); // Cek apakah angka sudah ada di daftar pengecualian

        return $answer;
    }

    private function generateAnswerRandomLetters(array $exception, int $length = 1)
    {
        do {
            // Buat string huruf acak dengan panjang tertentu menggunakan random_int untuk keamanan lebih
            $letters = implode('', array_map(function () {
                return chr(random_int(65, 90)); // ASCII 65-90 adalah huruf kapital A-Z
            }, range(1, $length)));

            // Log untuk memeriksa jawaban yang dihasilkan
            Log::info("Generated random letter: $letters");
        } while (in_array($letters, $exception)); // Cek apakah huruf sudah ada di daftar pengecualian

        // Log untuk memeriksa daftar pengecualian
        Log::info("Exception list: " . implode(', ', $exception));

        return $letters;
    }
}
