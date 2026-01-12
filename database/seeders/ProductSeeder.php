<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        if ($categories->isEmpty()) {
            $this->command->error('❌ No categories found. Run CategorySeeder first.');
            return;
        }

        $products = [
            // ========================================
            // ASUS (Laptop)
            // ========================================
            [
                'category' => 'ASUS',
                'name' => 'ASUS VivoBook 14 (Ryzen 5, RAM 8GB, SSD 512GB)',
                'slug' => 'asus-vivobook-14-ryzen5-8gb-512gb',
                'description' => 'Laptop ringan untuk kerja/kuliah. Ryzen 5, RAM 8GB, SSD 512GB. Layar 14", cocok untuk produktivitas harian.',
                'price' => 7499000,
                'stock' => 15,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'ASUS',
                'name' => 'ASUS TUF Gaming F15 (i5, RAM 16GB, SSD 512GB)',
                'slug' => 'asus-tuf-gaming-f15-i5-16gb-512gb',
                'description' => 'Laptop gaming tangguh untuk game & desain. Intel Core i5, RAM 16GB, SSD 512GB. Cocok untuk gaming 1080p.',
                'price' => 15499000,
                'stock' => 6,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'ASUS',
                'name' => 'ASUS ZenBook 14 (i7, RAM 16GB, SSD 1TB)',
                'slug' => 'asus-zenbook-14-i7-16gb-1tb',
                'description' => 'Laptop premium tipis dan ringan. Intel Core i7, RAM 16GB, SSD 1TB. Cocok untuk profesional dan mobilitas tinggi.',
                'price' => 18999000,
                'stock' => 4,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],

            // ========================================
            // LENOVO (Laptop)
            // ========================================
            [
                'category' => 'LENOVO',
                'name' => 'Lenovo IdeaPad Slim 3 (i5, RAM 8GB, SSD 512GB)',
                'slug' => 'lenovo-ideapad-slim-3-i5-8gb-512gb',
                'description' => 'Laptop harian untuk tugas dan kerja. Intel Core i5, RAM 8GB, SSD 512GB. Build solid dan nyaman dipakai.',
                'price' => 7299000,
                'stock' => 12,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'LENOVO',
                'name' => 'Lenovo ThinkPad (i5, RAM 16GB, SSD 512GB)',
                'slug' => 'lenovo-thinkpad-i5-16gb-512gb',
                'description' => 'Laptop bisnis dengan keyboard nyaman dan durability tinggi. i5, RAM 16GB, SSD 512GB. Cocok untuk kantor.',
                'price' => 13499000,
                'stock' => 7,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],
            [
                'category' => 'LENOVO',
                'name' => 'Lenovo Legion 5 (Ryzen 7, RAM 16GB, SSD 1TB)',
                'slug' => 'lenovo-legion-5-ryzen7-16gb-1tb',
                'description' => 'Laptop gaming/performa tinggi. Ryzen 7, RAM 16GB, SSD 1TB. Cocok untuk editing dan gaming serius.',
                'price' => 20999000,
                'stock' => 3,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],

            // ========================================
            // DESKTOPS (PC)
            // ========================================
            [
                'category' => 'DESKTOPS',
                'name' => 'PC Office (i3, RAM 8GB, SSD 256GB)',
                'slug' => 'pc-office-i3-8gb-256gb',
                'description' => 'PC desktop untuk kebutuhan kantor dan sekolah. Intel Core i3, RAM 8GB, SSD 256GB. Kencang untuk aplikasi kerja.',
                'price' => 4699000,
                'stock' => 10,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'DESKTOPS',
                'name' => 'PC Gaming Entry (Ryzen 5, RAM 16GB, SSD 512GB)',
                'slug' => 'pc-gaming-entry-ryzen5-16gb-512gb',
                'description' => 'PC rakitan untuk gaming 1080p entry-level. Ryzen 5, RAM 16GB, SSD 512GB. (VGA bisa menyesuaikan paket).',
                'price' => 8499000,
                'stock' => 6,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'DESKTOPS',
                'name' => 'PC Creator (i7, RAM 32GB, SSD 1TB)',
                'slug' => 'pc-creator-i7-32gb-1tb',
                'description' => 'PC untuk editing dan rendering. Intel Core i7, RAM 32GB, SSD 1TB. Cocok untuk kerja kreatif.',
                'price' => 15999000,
                'stock' => 2,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],

            // ========================================
            // INTEL (Processor)
            // ========================================
            [
                'category' => 'INTEL',
                'name' => 'Intel Core i5-12400F',
                'slug' => 'intel-core-i5-12400f',
                'description' => 'Processor Intel Core i5-12400F. Cocok untuk gaming dan produktivitas. Catatan: seri F tidak memiliki iGPU.',
                'price' => 2399000,
                'stock' => 25,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'INTEL',
                'name' => 'Intel Core i7-12700K',
                'slug' => 'intel-core-i7-12700k',
                'description' => 'Processor Intel Core i7-12700K untuk performa tinggi. Cocok untuk creator dan gamer yang butuh tenaga lebih.',
                'price' => 5299000,
                'stock' => 10,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],

            // ========================================
            // AMD (Processor)
            // ========================================
            [
                'category' => 'AMD',
                'name' => 'AMD Ryzen 5 5600G',
                'slug' => 'amd-ryzen-5-5600g',
                'description' => 'Processor AMD Ryzen 5 5600G dengan iGPU. Cocok untuk rakitan hemat tanpa VGA dedicated.',
                'price' => 1899000,
                'stock' => 20,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'AMD',
                'name' => 'AMD Ryzen 7 5800X',
                'slug' => 'amd-ryzen-7-5800x',
                'description' => 'Processor AMD Ryzen 7 5800X untuk performa tinggi. Cocok untuk gaming dan pekerjaan berat.',
                'price' => 3499000,
                'stock' => 12,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],
        ];

        $count = 0;

        foreach ($products as $productData) {
            $categorySlug = $productData['category'];
            unset($productData['category']);

            if (isset($categories[$categorySlug])) {
                $productData['category_id'] = $categories[$categorySlug]->id;
                Product::create($productData);

                $count++;
            }
        }

        $this->command->info('✅ Products seeded: ' . $count . ' products');
    }
}
