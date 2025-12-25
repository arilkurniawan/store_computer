<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Keripik Balado',
                'slug' => 'keripik-balado',
                'icon' => 'fire',
                'description' => 'Keripik dengan bumbu balado pedas khas Padang',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Keripik Original',
                'slug' => 'keripik-original',
                'icon' => 'star',
                'description' => 'Keripik tanpa bumbu, gurih alami',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Keripik Manis',
                'slug' => 'keripik-manis',
                'icon' => 'cake',
                'description' => 'Keripik dengan rasa manis legit',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Paket Hemat',
                'slug' => 'paket-hemat',
                'icon' => 'gift',
                'description' => 'Paket bundling dengan harga spesial',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
