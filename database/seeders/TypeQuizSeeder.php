<?php

namespace Database\Seeders;

use App\Models\Quiz\TypeQuiz;
use Illuminate\Database\Seeder;

class TypeQuizSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // TypeQuiz::create([
        //     'name' => 'Matematika & Logika',
        //     'description' => 'Matematika & Logika',
        // ]);

        $typeQuiz = [
            [
                'name' => 'Tes Kepribadian',
                'description' => 'Tes Kepribadian',
            ],
            [
                'name' => 'Tes Wawasan Kebangsaan',
                'description' => 'Tes Wawasan Kebangsaan',
            ],
            [
                'name' => 'Matematika & Logika',
                'description' => 'Matematika & Logika',
            ]
        ];

        foreach ($typeQuiz as $quiz) {
            TypeQuiz::create([
                'name' => $quiz['name'],
                'description' => $quiz['description'],
            ]);
        }
    }
}
