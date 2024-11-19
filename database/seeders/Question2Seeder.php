<?php

namespace Database\Seeders;

use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Question2Seeder extends Seeder
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
                    'question' => 'Berapa hasil dari 5 + 3?',
                    'answers' => [
                        ['answer' => '8', 'is_answer' => 1],
                        ['answer' => '7', 'is_answer' => 0],
                        ['answer' => '9', 'is_answer' => 0],
                        ['answer' => '6', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 12 - 4?',
                    'answers' => [
                        ['answer' => '8', 'is_answer' => 1],
                        ['answer' => '6', 'is_answer' => 0],
                        ['answer' => '7', 'is_answer' => 0],
                        ['answer' => '10', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 9 x 3?',
                    'answers' => [
                        ['answer' => '27', 'is_answer' => 1],
                        ['answer' => '24', 'is_answer' => 0],
                        ['answer' => '30', 'is_answer' => 0],
                        ['answer' => '21', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 36 ÷ 6?',
                    'answers' => [
                        ['answer' => '6', 'is_answer' => 1],
                        ['answer' => '5', 'is_answer' => 0],
                        ['answer' => '7', 'is_answer' => 0],
                        ['answer' => '8', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 15 + 25?',
                    'answers' => [
                        ['answer' => '40', 'is_answer' => 1],
                        ['answer' => '35', 'is_answer' => 0],
                        ['answer' => '45', 'is_answer' => 0],
                        ['answer' => '50', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 18 - 9?',
                    'answers' => [
                        ['answer' => '9', 'is_answer' => 1],
                        ['answer' => '8', 'is_answer' => 0],
                        ['answer' => '10', 'is_answer' => 0],
                        ['answer' => '7', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 7 x 8?',
                    'answers' => [
                        ['answer' => '56', 'is_answer' => 1],
                        ['answer' => '49', 'is_answer' => 0],
                        ['answer' => '63', 'is_answer' => 0],
                        ['answer' => '54', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 50 ÷ 10?',
                    'answers' => [
                        ['answer' => '5', 'is_answer' => 1],
                        ['answer' => '6', 'is_answer' => 0],
                        ['answer' => '4', 'is_answer' => 0],
                        ['answer' => '7', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 100 - 25?',
                    'answers' => [
                        ['answer' => '75', 'is_answer' => 1],
                        ['answer' => '80', 'is_answer' => 0],
                        ['answer' => '70', 'is_answer' => 0],
                        ['answer' => '85', 'is_answer' => 0],
                    ],
                ],
                [
                    'question' => 'Berapa hasil dari 20 + 15?',
                    'answers' => [
                        ['answer' => '35', 'is_answer' => 1],
                        ['answer' => '30', 'is_answer' => 0],
                        ['answer' => '40', 'is_answer' => 0],
                        ['answer' => '25', 'is_answer' => 0],
                    ],
                ],
            ];

            foreach ($questions as $index => $item) {
                $quiz_question_math = QuizQuestion::create([
                    'is_random_answer' => 0,
                    'is_generate_random_answer' => 0,
                    'order' => $index + 1, // Penomoran urutan pertanyaan
                    'level' => '|2|3|',
                    'aspect' => '|3|',
                    'direction_question' => 'Pilih jawaban yang benar untuk soal berikut',
                    'question' => $item['question'],
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
