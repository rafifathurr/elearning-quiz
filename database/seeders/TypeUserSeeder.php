<?php

namespace Database\Seeders;

use App\Models\TypeUser;
use Illuminate\Database\Seeder;

class TypeUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        TypeUser::create([
            'name' => 'ASN',
            'description' => 'Untuk ASN',
        ]);

        TypeUser::create([
            'name' => 'Polri - Tamtama',
            'description' => 'Untuk Polri Tamtama',
        ]);

        TypeUser::create([
            'name' => 'TNI - Tamtama',
            'description' => 'Untuk TNI Tamtama',
        ]);

        TypeUser::create([
            'name' => 'Polri - Bintara',
            'description' => 'Untuk Polri Bintara',
        ]);

        TypeUser::create([
            'name' => 'TNI - Bintara',
            'description' => 'Untuk TNI Bintara',
        ]);

        TypeUser::create([
            'name' => 'Polri - Perwira',
            'description' => 'Untuk Polri Perwira',
        ]);

        TypeUser::create([
            'name' => 'TNI - Perwira',
            'description' => 'Untuk TNI Perwira',
        ]);
    }
}
