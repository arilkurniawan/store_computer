<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        // Karena image adalah required (NOT NULL), 
        // kita perlu placeholder atau upload manual via admin
        
        $banners = [
            [
                'title' => 'Promo Spesial Akhir Tahun',
                'image' => 'banners/banner-1.jpg', // Placeholder - upload manual
                'link' => '/products?category=paket-hemat',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Keripik Sanjai Asli Bukittinggi',
                'image' => 'banners/banner-2.jpg',
                'link' => '/products',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Gratis Ongkir Seluruh Indonesia',
                'image' => 'banners/banner-3.jpg',
                'link' => '/products',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        $this->command->info('✅ Banners seeded: ' . count($banners) . ' banners');
        $this->command->warn('⚠️  Note: Upload banner images via admin panel!');
    }
}
