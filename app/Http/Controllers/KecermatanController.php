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
            'durasi_kombinasi' => 'required|array',
            'durasi_kombinasi.*' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            $questions = [];
            foreach ($request->type_random_question as $index => $type) {
                $qty = $request->qty[$index];
                $durasi_kombinasi = $request->durasi_kombinasi[$index];
                $nama_kombinasi = 'kombinasi' . ($index + 1);

                // Generate 5 unique answers
                if ($type === 'angka') {
                    // Generate an array of unique numbers, ensuring exactly 5 unique numbers
                    $unique_answers = range(1, 99);
                    shuffle($unique_answers);
                    $unique_answers = array_slice($unique_answers, 0, 5); // Ambil 5 angka unik
                } else {
                    // Generate 5 unique letters
                    $unique_answers = [];
                    while (count($unique_answers) < 5) {
                        $letter = chr(rand(65, 90)); // Generate a random letter A-Z
                        if (!in_array($letter, $unique_answers)) {
                            $unique_answers[] = $letter; // Add unique letter
                        }
                    }
                }


                // Repeat unique answers and shuffle to fill the number of questions (qty)
                $correct_answers = [];
                for ($i = 0; $i < $qty; $i++) {
                    $correct_answers[] = $unique_answers[$i % count($unique_answers)];
                }
                shuffle($correct_answers); // Acak ulang jawaban agar tidak mengikuti pola

                // Create questions
                foreach ($correct_answers as $correct_answer) {
                    $questions[] = [
                        'correct_answer' => $correct_answer,
                        'durasi_kombinasi' => $durasi_kombinasi,
                        'nama_kombinasi' => $nama_kombinasi,
                    ];
                }
            }

            // Tambahkan nomor urut ke setiap pertanyaan
            foreach ($questions as $index => &$question) {
                $question['order'] = $index + 1;
            }

            // Simpan data ke database
            $kecermatan = Quiz::create([
                'name' => $request->name,
                'type_aspect' => 'kecermatan',
                'time_duration' => $request->time_duration,
                'question_kecermatan' => json_encode($questions), // Simpan dalam format JSON
            ]);

            DB::commit();

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







    public function edit($id)
    {
        // Ambil data quiz berdasarkan ID
        $quiz = Quiz::findOrFail($id);

        // Decode data pertanyaan (yang disimpan dalam format JSON)
        $questions = json_decode($quiz->question_kecermatan, true);

        // Group questions by 'nama_kombinasi'
        $groupedQuestions = [];
        foreach ($questions as $question) {
            $groupedQuestions[$question['nama_kombinasi']][] = $question;
        }

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
            'durasi_kombinasi' => 'required|array',
            'durasi_kombinasi.*' => 'required|integer|min:1',
        ]);



        try {
            DB::beginTransaction();
            $quiz = Quiz::findOrFail($id);

            $questions = [];
            foreach ($request->type_random_question as $index => $type) {
                $qty = $request->qty[$index];
                $durasi_kombinasi = $request->durasi_kombinasi[$index];
                $nama_kombinasi = 'kombinasi' . ($index + 1);

                // Generate 5 unique answers
                if ($type === 'angka') {
                    // Generate an array of unique numbers, ensuring exactly 5 unique numbers
                    $unique_answers = range(1, 99);
                    shuffle($unique_answers);
                    $unique_answers = array_slice($unique_answers, 0, 5); // Ambil 5 angka unik
                } else {
                    // Generate 5 unique letters
                    $unique_answers = [];
                    while (count($unique_answers) < 5) {
                        $letter = chr(rand(65, 90)); // Generate a random letter A-Z
                        if (!in_array($letter, $unique_answers)) {
                            $unique_answers[] = $letter; // Add unique letter
                        }
                    }
                }


                // Repeat unique answers and shuffle to fill the number of questions (qty)
                $correct_answers = [];
                for ($i = 0; $i < $qty; $i++) {
                    $correct_answers[] = $unique_answers[$i % count($unique_answers)];
                }
                shuffle($correct_answers); // Acak ulang jawaban agar tidak mengikuti pola

                // Create questions
                foreach ($correct_answers as $correct_answer) {
                    $questions[] = [
                        'correct_answer' => $correct_answer,
                        'durasi_kombinasi' => $durasi_kombinasi,
                        'nama_kombinasi' => $nama_kombinasi,
                    ];
                }
            }

            // Tambahkan nomor urut ke setiap pertanyaan
            foreach ($questions as $index => &$question) {
                $question['order'] = $index + 1;
            }

            // Update quiz data
            $quiz->update([
                'name' => $request->name,
                'type_aspect' => 'kecermatan',
                'time_duration' => $request->time_duration,
                'question_kecermatan' => json_encode($questions),
            ]);
            DB::commit();
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
        $questionKecermatan = json_decode($result->quiz->question_kecermatan, true);

        Log::info('Decoded question data:', ['question_kecermatan' => $questionKecermatan]);

        if (!is_array($questionKecermatan) || empty($questionKecermatan)) {
            throw new Exception('Invalid question data');
        }

        $durasiKombinasi = collect($questionKecermatan)
            ->groupBy(fn($question) => $question['nama_kombinasi'] ?? null)
            ->map(fn($questions) => $questions->first()['durasi_kombinasi'] ?? 0)
            ->toArray();

        Log::info('Durasi Kombinasi:', ['durasi_kombinasi' => $durasiKombinasi]);

        $activeQuestionNumber = 1; // Default value
        if ($request->has('q')) {

            $activeQuestionNumber = (int) $request->input('q');
        } elseif (isset($result->details) && $result->details->isNotEmpty()) {

            $resultDetails = $result->details()->orderBy('display_time', 'desc')->get();
            $activeQuestionNumber = $resultDetails->first()->order + 1;
        }

        Log::info('Active Question Number:', ['active_question_number' => $activeQuestionNumber]);

        $activeQuestion = collect($questionKecermatan)->firstWhere('order', $activeQuestionNumber);

        if (!$activeQuestion || !isset($activeQuestion['nama_kombinasi'])) {
            throw new Exception('Combination not found for active question');
        }

        $currentCombination = $activeQuestion['nama_kombinasi'];


        $questionsInCombination = array_filter($questionKecermatan, function ($question) use ($currentCombination) {
            return $question['nama_kombinasi'] === $currentCombination;
        });

        $currentCombinationIndex = array_search($currentCombination, array_keys($durasiKombinasi));
        $isLastCombination = $currentCombinationIndex === (count($durasiKombinasi) - 1);

        if ($isLastCombination && $activeQuestionNumber > max(array_column($questionsInCombination, 'order'))) {
            // Arahkan ke fungsi finish jika sudah di kombinasi terakhir
            // Create a request to pass the resultId
            $request = new Request();
            $request->merge([
                'resultId' => $result->id,  // Pass the resultId here
            ]);

            return $this->finish($request);  // Pass the request object to finish
        }

        Log::info('Questions in Current Combination:', ['questions_in_combination' => $questionsInCombination]);

        // Hapus unique_answers lama di session saat berpindah kombinasi
        $previousCombination = session()->get('previous_combination');
        if ($previousCombination !== $currentCombination) {
            session()->forget('unique_answers');
            session()->put('previous_combination', $currentCombination);
        }

        // Ambil unique_answers dari session atau generate baru jika tidak ada
        $uniqueAnswers = session()->get('unique_answers', []);
        if (empty($uniqueAnswers)) {
            $uniqueAnswers = $this->generateUniqueAnswersSet($questionsInCombination, $currentCombination);

            if (empty($uniqueAnswers)) {
                Log::warning('Unique Answers is empty. Generating fallback data.');
                $uniqueAnswers = array_unique(array_column($questionKecermatan, 'correct_answer'));
            }
            shuffle($uniqueAnswers);
            session()->put('unique_answers', $uniqueAnswers);
        }

        Log::info('Unique Answers:', ['unique_answers' => $uniqueAnswers]);

        $correctAnswer = $activeQuestion['correct_answer'] ?? null;

        if (!$correctAnswer) {
            throw new Exception('Correct answer not found in question data');
        }

        Log::info('Correct Answer:', ['correct_answer' => $correctAnswer]);

        $wrongAnswers = array_values(array_filter($uniqueAnswers, function ($answer) use ($correctAnswer) {
            return $answer !== $correctAnswer;
        }));

        $wrongAnswers = array_slice($wrongAnswers, 0, 4);

        Log::info('Wrong Answers:', ['wrong_answers' => $wrongAnswers]);

        $quizAnswerArr = [];
        foreach ($wrongAnswers as $answer) {
            $quizAnswerArr[] = [
                'answer' => $answer,
                'attachment' => null,
                'answered' => false,
                'point' => 0,
                'is_answer' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        shuffle($quizAnswerArr);

        $display_time = ResultDetail::where('result_id', $result->id)->count() + 1;
        $activeQuestion = [
            'question_number' => $activeQuestion['order'],
            'direction_question' => $activeQuestion['direction_question'] ?? '',
            'quiz_answer' => $quizAnswerArr,
            'correct_answer' => $correctAnswer,
            'is_active' => true,
            'nama_kombinasi' => $currentCombination,
            'display_time' => $display_time,
            'durasi_kombinasi' => $durasiKombinasi[$currentCombination] ?? 0,
        ];

        $data = [
            'quiz' => $result->quiz->toArray(),
            'result' => $result,
            'questions' => $questionKecermatan,
            'active_question' => $activeQuestion,
            'total_question' => count($questionKecermatan),
            'unique_answers' => $uniqueAnswers,
            'durasi_kombinasi' => $durasiKombinasi,
            'soal_data' => $questionKecermatan,
            'currentCombination' => $currentCombination,
        ];

        if ($request->wantsJson() || str_starts_with($request->path(), 'api')) {
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

    private function generateUniqueAnswersSet($questions, $currentCombination)
    {
        $uniqueAnswers = [];

        $filteredQuestions = array_filter($questions, function ($question) use ($currentCombination) {
            return isset($question['nama_kombinasi']) && $question['nama_kombinasi'] === $currentCombination;
        });

        Log::info('Filtered Questions for Combination:', ['filtered_questions' => $filteredQuestions]);

        foreach ($filteredQuestions as $question) {
            if (isset($question['correct_answer']) && !in_array($question['correct_answer'], $uniqueAnswers)) {
                $uniqueAnswers[] = $question['correct_answer'];
            }
        }

        Log::info('Generated Unique Answers:', ['unique_answers' => $uniqueAnswers]);

        return $uniqueAnswers;
    }






    public function answer(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'value' => 'required',
                'resultId' => 'required|integer',
                'questionNumber' => 'required|integer',
                'currentCombination' => 'required',
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
                'combination_name' => $validated['currentCombination'],
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


            session()->forget('unique_answers');
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

            $questionsPerCombination = $result->details
                ->groupBy('combination_name')
                ->map(function ($details) {
                    $totalQuestions = $details->count();
                    $correctQuestions = $details->where('score', 1)->count();

                    return [
                        'combination_name' => $details->first()->combination_name ?? 'Unknown Combination',
                        'total_questions' => $totalQuestions,
                        'correct_questions' => $correctQuestions,
                    ];
                });

            return view('quiz.result', compact('result', 'questionsPerCombination'));
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
        $alphabet = range('A', 'Z'); // Semua huruf kapital A-Z
        $validLetters = array_diff($alphabet, $exception); // Eksklusikan huruf yang ada dalam pengecualian

        if (empty($validLetters)) {
            throw new Exception('No valid letters available to generate random answer.');
        }

        // Ambil huruf acak dari daftar valid
        $letters = '';
        for ($i = 0; $i < $length; $i++) {
            $letters .= $validLetters[array_rand($validLetters)];
        }

        // Log hasil untuk debug
        Log::info("Generated random letter: $letters");
        return $letters;
    }
}
