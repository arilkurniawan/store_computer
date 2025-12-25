<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $balado = Category::where('slug', 'keripik-balado')->first();
        $original = Category::where('slug', 'keripik-original')->first();
        $manis = Category::where('slug', 'keripik-manis')->first();
        $paket = Category::where('slug', 'paket-hemat')->first();

        $products = [
            [
                'name' => 'Keripik Sanjai Balado Merah 250gr',
                'slug' => 'keripik-sanjai-balado-merah-250gr',
                'description' => 'Keripik singkong dengan bumbu balado merah khas Padang. Pedas, gurih, dan renyah!',
                'price' => 25000,
                'stock' => 100,
                'weight' => 250,
                'image' => 'balado-merah.jpg',
                'sold_count' => 856,
                'is_recommended' => true,
                'is_active' => true,
                'category_id' => $balado->id,
            ],
            [
                'name' => 'Keripik Sanjai Balado Hijau 250gr',
                'slug' => 'keripik-sanjai-balado-hijau-250gr',
                'description' => 'Keripik singkong dengan bumbu balado hijau dari cabai hijau pilihan.',
                'price' => 25000,
                'stock' => 80,
                'weight' => 250,
                'image' => 'balado-hijau.jpg',
                'sold_count' => 542,
                'is_recommended' => true,
                'is_active' => true,
                'category_id' => $balado->id,
            ],
            [
                'name' => 'Keripik Sanjai Original 250gr',
                'slug' => 'keripik-sanjai-original-250gr',
                'description' => 'Keripik singkong original tanpa bumbu. Renyah dan gurih alami.',
                'price' => 20000,
                'stock' => 150,
                'weight' => 250,
                'image' => 'original.jpg',
                'sold_count' => 423,
                'is_recommended' => false,
                'is_active' => true,
                'category_id' => $original->id,
            ],
            [
                'name' => 'Keripik Sanjai Manis 250gr',
                'slug' => 'keripik-sanjai-manis-250gr',
                'description' => 'Keripik singkong dengan lapisan gula merah. Manis legit!',
                'price' => 23000,
                'stock' => 90,
                'weight' => 250,
                'image' => 'manis.jpg',
                'sold_count' => 198,
                'is_recommended' => false,
                'is_active' => true,
                'category_id' => $manis->id,
            ],
            [
                'name' => 'Paket Mix 3 Rasa',
                'slug' => 'paket-mix-3-rasa',
                'description' => 'Paket hemat berisi Balado Merah, Balado Hijau, dan Original.',
                'price' => 65000,
                'stock' => 50,
                'weight' => 750,
                'image' => 'paket-3-rasa.jpg',
                'sold_count' => 534,
                'is_recommended' => true,
                'is_active' => true,
                'category_id' => $paket->id,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
