<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $promos = [
            [
                'code' => 'WELCOME10',
                'discount' => 10000,
                'is_active' => true,
            ],
            [
                'code' => 'SANJAI20',
                'discount' => 20000,
                'is_active' => true,
            ],
            [
                'code' => 'HEMAT15',
                'discount' => 15000,
                'is_active' => true,
            ],
            [
                'code' => 'NEWUSER',
                'discount' => 25000,
                'is_active' => true,
            ],
            [
                'code' => 'BUKITTINGGI',
                'discount' => 30000,
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED2024',
                'discount' => 50000,
                'is_active' => false,
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }

        $this->command->info('✅ Promos seeded: ' . count($promos) . ' promo codes');
    }
}
