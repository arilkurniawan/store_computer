<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Summer Sale',
                'subtitle' => 'Diskon hingga 50%',
                'image' => 'banners/banner1.jpg',
                'link' => '/products?sale=true',
                'button_text' => 'Belanja Sekarang',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'New Arrivals',
                'subtitle' => 'Koleksi terbaru telah hadir',
                'image' => 'banners/banner2.jpg',
                'link' => '/products?new=true',
                'button_text' => 'Lihat Koleksi',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
