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
                'name' => 'Keripik Sanjai Original',
                'slug' => 'keripik-sanjai-original',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Keripik Sanjai Balado',
                'slug' => 'keripik-sanjai-balado',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Keripik Sanjai Manis',
                'slug' => 'keripik-sanjai-manis',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Keripik Sanjai Premium',
                'slug' => 'keripik-sanjai-premium',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Hemat',
                'slug' => 'paket-hemat',
                'image' => null,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded: ' . count($categories) . ' categories');
    }
}
