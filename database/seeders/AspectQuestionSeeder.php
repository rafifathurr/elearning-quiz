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
                'type_aspect' => 'kepribadian',
                'description' => 'Tes Kepribadian',
            ],
            [
                'name' => 'Tes Wawasan Kebangsaan',
                'type_aspect' => 'kepribadian',
                'description' => 'Tes Wawasan Kebangsaan',
            ],
            [
                'name' => 'Matematika & Logika',
                'type_aspect' => 'kecerdasan',
                'description' => 'Matematika & Logika',
            ],
            [
                'name' => 'Matematika Numerik',
                'type_aspect' => 'kecerdasan',
                'description' => 'Matematika Numerik',
            ]
        ];

        foreach ($aspectQuestion as $aspect) {
            AspectQuestion::create([
                'name' => $aspect['name'],
                'type_aspect' => $aspect['type_aspect'],
                'description' => $aspect['description'],
            ]);
        }
    }
}
