<?php

namespace Database\Seeders;

use App\Models\PaymentPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentPackage::create([
            'name' => 'Promo Khusus Polri November 2024',
            'price' => 300000,
            'quota_access' => 12,
        ]);
        PaymentPackage::create([
            'name' => 'Promo Khusus ASN November 2024',
            'price' => 240000,
            'quota_access' => 8,
        ]);
    }
}
