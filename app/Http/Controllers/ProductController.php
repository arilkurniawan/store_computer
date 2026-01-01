<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List semua produk dengan filter
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->available();

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Kategori untuk filter
        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->available()])
            ->get();

        // Kategori aktif (jika ada filter)
        $activeCategory = $request->category 
            ? Category::where('slug', $request->category)->first() 
            : null;

        return view('products.index', compact(
            'products',
            'categories',
            'activeCategory'
        ));
    }

    /**
     * API Search
     */

    public function apiSearch(Request $request)
    {
        $keyword = $request->input('q');

        if (empty($keyword) || strlen($keyword) <2 ) {
            return response()->json([
                'success'=> true,
                'products' => [],
                'message' => 'ketiik minimal 2 karakter'
            ]);
        }
        $products = Product::where('is_active', true)
        ->where(function ($q) use ($keyword) {
            $q->where('name','like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->orWhereHas('category', function ($cat) use ($keyword) {
                $cat->where('name', 'like', "%{$keyword}%");
            });
        })
        ->with('category')
        ->limit(10)
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'price_formatted' => 'RP' . number_format($product->price, 0,',','.'),
                'stock' => $product->stock,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'category' => $product->category->name ?? '-',
                'url' => route('products.show' ,$product->slug), 
            ];
        });
        return response()->json([
            'success' => true,
            'products' => $products,
            'total' => $products->count(),
        ]);
    }

    /**
     * Detail produk
     */
    public function show(Product $product)
    {
        // Pastikan produk aktif
        if (!$product->is_active) {
            abort(404);
        }

        // Load category
        $product->load('category');

        // Produk terkait (kategori sama, exclude current)
        $relatedProducts = Product::with('category')
            ->available()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    

    /**
     * Produk by kategori
     */
    public function byCategory(Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->available()
            ->latest()
            ->paginate(12);

        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->available()])
            ->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $category,
        ]);
    }
}
