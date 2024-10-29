<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $admin_account = User::create([
            'name' => 'Super Admin',
            'username' => 'super_admin',
            'phone' => '081122334455',
            'email' => 'super-admin@sisfosigasi.com',
            'password'=> bcrypt('superadmin')
        ]);

        $admin_account->assignRole('super-admin');
    }
}
