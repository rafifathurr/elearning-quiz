<?php

namespace Database\Seeders;

use App\Models\AspectQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AspectQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aspectQuestion = [
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

        foreach ($aspectQuestion as $aspect) {
            AspectQuestion::create([
                'name' => $aspect['name'],
                'description' => $aspect['description'],
            ]);
        }
    }
}
