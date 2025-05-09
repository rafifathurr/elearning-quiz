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
            'name' => 'User_Admin',
            'username' => 'user_admin',
            'phone' => '081122334455',
            'email' => 'admin3@gmail.com',
            'password' => bcrypt('admin')
        ]);

        $admin_account->assignRole('admin');

        // $ASN_account = User::create([
        //     'name' => 'User ASN',
        //     'username' => 'user_ASN',
        //     'phone' => '081122334455',
        //     'email' => 'user_ASN@gmail.com',
        //     'password' => bcrypt('password123')
        // ]);
        // $ASN_account->assignRole('user');
    }
}
