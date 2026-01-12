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
                'name' => 'ASUS',
                'slug' => 'ASUS',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'LENOVO',
                'slug' => 'LENOVO',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'DESKTOPS',
                'slug' => 'DESKTOPS',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'INTEL',
                'slug' => 'INTEL',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'AMD',
                'slug' => 'AMD',
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
