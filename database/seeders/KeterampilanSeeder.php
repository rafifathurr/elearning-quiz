<?php

namespace Database\Seeders;

use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeterampilanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $questions = [
                [
                    'answers' => [
                        ['answer' => '333', 'is_answer' => 1]

                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '27', 'is_answer' => 1],
                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '49', 'is_answer' => 1],

                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '298', 'is_answer' => 1]
                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '299', 'is_answer' => 1],

                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '371', 'is_answer' => 1]
                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '82', 'is_answer' => 1]

                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '70', 'is_answer' => 1]
                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '75', 'is_answer' => 1],
                    ],
                ],
                [
                    'answers' => [
                        ['answer' => '35', 'is_answer' => 1],
                    ],
                ],
            ];

            foreach ($questions as $index => $item) {
                $quiz_question_math = QuizQuestion::create([
                    'is_random_answer' => 0,
                    'is_generate_random_answer' => 1,
                    'order' => $index + 1, // Penomoran urutan pertanyaan
                    'level' => '|1|',
                    'aspect' => '|7|',
                    'direction_question' => 'Pilih jawaban yang benar untuk soal berikut',
                    'description' => null,
                    'time_duration' => 60,
                ]);

                foreach ($item['answers'] as $answer) {
                    QuizAnswer::create([
                        'quiz_question_id' => $quiz_question_math->id,
                        'is_answer' => $answer['is_answer'],
                        'point' => $answer['is_answer'] ? 100 : 0, // Poin 100 untuk benar, 0 untuk salah
                        'answer' => $answer['answer'],
                        'attachment' => null,
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
