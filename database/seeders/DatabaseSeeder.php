<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Database\Seeders\Roles\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            // UserSeeder::class,
            // TypeUserSeeder::class,
            // TypeQuizSeeder::class,
            // AspectQuestionSeeder::class,
            // PaymentPackageSeeder::class,
            // QuestionSeeder::class,
            // Question2Seeder::class,
            //AllDataQuizSeeder::class
            // QuizSeeder::class,
            // Quiz2Seeder::class
        ]);
    }
}
