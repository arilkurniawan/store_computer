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
            // KERIPIK SANJAI ORIGINAL
            // ========================================
            [
                'category' => 'keripik-sanjai-original',
                'name' => 'Keripik Sanjai Original 100g',
                'slug' => 'keripik-sanjai-original-100g',
                'description' => 'Keripik sanjai original dengan bumbu gurih khas Bukittinggi. Terbuat dari singkong pilihan yang diiris tipis dan digoreng hingga renyah. Kemasan praktis 100 gram.',
                'price' => 15000,
                'stock' => 100,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'keripik-sanjai-original',
                'name' => 'Keripik Sanjai Original 250g',
                'slug' => 'keripik-sanjai-original-250g',
                'description' => 'Keripik sanjai original ukuran keluarga. Pas untuk dinikmati bersama keluarga di rumah. Kemasan 250 gram.',
                'price' => 35000,
                'stock' => 75,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],
            [
                'category' => 'keripik-sanjai-original',
                'name' => 'Keripik Sanjai Original 500g',
                'slug' => 'keripik-sanjai-original-500g',
                'description' => 'Keripik sanjai original kemasan besar. Hemat dan cocok untuk stok camilan di rumah. Kemasan 500 gram.',
                'price' => 65000,
                'stock' => 50,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],

            // ========================================
            // KERIPIK SANJAI BALADO
            // ========================================
            [
                'category' => 'keripik-sanjai-balado',
                'name' => 'Keripik Sanjai Balado Merah 100g',
                'slug' => 'keripik-sanjai-balado-merah-100g',
                'description' => 'Keripik sanjai dengan bumbu balado merah pedas yang menggugah selera. Level pedas sedang, cocok untuk pecinta pedas.',
                'price' => 18000,
                'stock' => 80,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'keripik-sanjai-balado',
                'name' => 'Keripik Sanjai Balado Hijau 100g',
                'slug' => 'keripik-sanjai-balado-hijau-100g',
                'description' => 'Keripik sanjai dengan bumbu balado hijau yang segar dan pedas. Aroma cabai hijau yang khas.',
                'price' => 18000,
                'stock' => 70,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'keripik-sanjai-balado',
                'name' => 'Keripik Sanjai Super Pedas 100g',
                'slug' => 'keripik-sanjai-super-pedas-100g',
                'description' => 'Khusus pecinta pedas sejati! Keripik sanjai dengan level pedas maksimal. Hati-hati, sangat pedas!',
                'price' => 20000,
                'stock' => 60,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],
            [
                'category' => 'keripik-sanjai-balado',
                'name' => 'Keripik Sanjai Balado 250g',
                'slug' => 'keripik-sanjai-balado-250g',
                'description' => 'Keripik sanjai balado ukuran keluarga. Perpaduan rasa pedas dan gurih yang pas.',
                'price' => 40000,
                'stock' => 55,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],

            // ========================================
            // KERIPIK SANJAI MANIS
            // ========================================
            [
                'category' => 'keripik-sanjai-manis',
                'name' => 'Keripik Sanjai Manis 100g',
                'slug' => 'keripik-sanjai-manis-100g',
                'description' => 'Keripik sanjai dengan lapisan gula aren yang manis legit. Perpaduan renyah dan manis yang sempurna.',
                'price' => 17000,
                'stock' => 65,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'keripik-sanjai-manis',
                'name' => 'Keripik Sanjai Cokelat 100g',
                'slug' => 'keripik-sanjai-cokelat-100g',
                'description' => 'Keripik sanjai dengan topping cokelat premium. Inovasi baru yang disukai anak-anak.',
                'price' => 22000,
                'stock' => 45,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],
            [
                'category' => 'keripik-sanjai-manis',
                'name' => 'Keripik Sanjai Karamel 100g',
                'slug' => 'keripik-sanjai-karamel-100g',
                'description' => 'Keripik sanjai dengan karamel creamy yang lezat. Tekstur renyah dengan rasa karamel yang meleleh.',
                'price' => 20000,
                'stock' => 40,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],

            // ========================================
            // KERIPIK SANJAI PREMIUM
            // ========================================
            [
                'category' => 'keripik-sanjai-premium',
                'name' => 'Keripik Sanjai Premium Gift Box',
                'slug' => 'keripik-sanjai-premium-gift-box',
                'description' => 'Kemasan premium dalam gift box eksklusif. Berisi 3 varian rasa (Original, Balado, Manis) masing-masing 100g. Cocok untuk oleh-oleh atau hadiah.',
                'price' => 75000,
                'stock' => 30,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'keripik-sanjai-premium',
                'name' => 'Keripik Sanjai Premium Jar',
                'slug' => 'keripik-sanjai-premium-jar',
                'description' => 'Keripik sanjai original dalam kemasan jar kaca premium. Elegan dan tahan lama. Berat bersih 200g.',
                'price' => 55000,
                'stock' => 25,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],
            [
                'category' => 'keripik-sanjai-premium',
                'name' => 'Keripik Sanjai Exclusive Hampers',
                'slug' => 'keripik-sanjai-exclusive-hampers',
                'description' => 'Hampers eksklusif berisi 5 varian keripik sanjai terbaik. Kemasan mewah dengan pita dan kartu ucapan. Perfect gift!',
                'price' => 150000,
                'stock' => 15,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],

            // ========================================
            // PAKET HEMAT
            // ========================================
            [
                'category' => 'paket-hemat',
                'name' => 'Paket Hemat 3 in 1',
                'slug' => 'paket-hemat-3-in-1',
                'description' => 'Paket hemat berisi 3 bungkus keripik sanjai (Original, Balado Merah, Manis) masing-masing 100g. Hemat Rp 10.000!',
                'price' => 45000,
                'stock' => 40,
                'image' => null,
                'is_active' => true,
                'is_recommended' => true,
            ],
            [
                'category' => 'paket-hemat',
                'name' => 'Paket Hemat Balado Lovers',
                'slug' => 'paket-hemat-balado-lovers',
                'description' => 'Khusus pecinta balado! Berisi 3 bungkus keripik sanjai balado (Merah, Hijau, Super Pedas) masing-masing 100g.',
                'price' => 50000,
                'stock' => 35,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],
            [
                'category' => 'paket-hemat',
                'name' => 'Paket Keluarga 1kg',
                'slug' => 'paket-keluarga-1kg',
                'description' => 'Paket super hemat 1 kilogram keripik sanjai original. Cocok untuk stok bulanan keluarga.',
                'price' => 120000,
                'stock' => 20,
                'image' => null,
                'is_active' => true,
                'is_recommended' => false,
            ],
            [
                'category' => 'paket-hemat',
                'name' => 'Paket Reseller',
                'slug' => 'paket-reseller',
                'description' => 'Paket khusus reseller berisi 10 bungkus keripik sanjai original 100g. Harga spesial untuk reseller.',
                'price' => 130000,
                'stock' => 25,
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
