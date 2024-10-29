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
        TypeQuiz::create([
            'name' => 'Matematika & Logika',
            'description' => 'Matematika & Logika',
        ]);
    }
}
