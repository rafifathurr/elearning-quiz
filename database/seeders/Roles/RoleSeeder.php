<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Role::create(['name' => 'admin']);
        // Role::create(['name' => 'user']);
        // Role::create(['name' => 'counselor']);
        Role::create(['name' => 'package manager']);
        Role::create(['name' => 'finance']);
        Role::create(['name' => 'question operator']);
    }
}
