<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Halaman utama
     */
    public function index()
    {
        // Banner aktif (urut berdasarkan order)
        $banners = Banner::active()->get();

        // Produk rekomendasi (max 8)
        $recommendedProducts = Product::with('category')
            ->available()
            ->recommended()
            ->latest()
            ->take(8)
            ->get();

        // Kategori aktif dengan jumlah produk
        $categories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->available();
            }])
            ->get();

        // Produk terbaru (max 8)
        $latestProducts = Product::with('category')
            ->available()
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact(
            'banners',
            'recommendedProducts',
            'categories',
            'latestProducts'
        ));
    }
}
