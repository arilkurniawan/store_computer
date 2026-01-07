@extends('layouts.app')

@section('title', isset($activeCategory) ? $activeCategory->name . ' - Keripik Sanjai' : 'Produk - Keripik Sanjai')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-12xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-orange-500">
                @if(isset($activeCategory))
                    {{ $activeCategory->name }}
                @else
                    All Product
                @endif
            </h1>
            <p class="mt-2 text-gray-600">
                @if(isset($activeCategory))
                    Menampilkan produk dalam kategori {{ $activeCategory->name }}
                @else
                    Temukan keripik sanjai favorit Anda
                @endif
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ============================= --}}
            {{-- SIDEBAR - Filter & Kategori --}}
            {{-- ============================= --}}
            <aside class="w-full lg:w-64 flex-shrink-0">
                
                {{-- Kategori --}}
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="font-bold text-gray-900 mb-4">📂 Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('products.index') }}" 
                               class="block px-3 py-2 rounded-lg transition {{ !isset($activeCategory) ? 'bg-orange-100 text-orange-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                Semua Produk
                                <span class="float-right text-sm text-gray-400">{{ $products->total() ?? 0 }}</span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                   class="block px-3 py-2 rounded-lg transition {{ (isset($activeCategory) && $activeCategory->id == $category->id) ? 'bg-orange-100 text-orange-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    {{ $category->name }}
                                    <span class="float-right text-sm text-gray-400">{{ $category->products_count ?? 0 }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Filter Harga (Opsional) --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">💰 Urutkan</h3>
                    <form action="{{ route('products.index') }}" method="GET">
                        @if(isset($activeCategory))
                            <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <select name="sort" onchange="this.form.submit()" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Terbaru</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama: A-Z</option>
                        </select>
                    </form>
                </div>
            </aside>

            {{-- ============================= --}}
            {{-- MAIN CONTENT - Product Grid --}}
            {{-- ============================= --}}
            <main class="flex-1">

                {{-- Search Bar --}}
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                    <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                        @if(isset($activeCategory))
                            <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        
                        <div class="flex-1 relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari produk..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('products.index', isset($activeCategory) ? ['category' => $activeCategory->slug] : []) }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Active Filters Info --}}
                @if(request('search') || request('sort'))
                    <div class="mb-4 flex flex-wrap items-center gap-2 text-sm">
                        <span class="text-gray-600">Filter aktif:</span>
                        @if(request('search'))
                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                                Pencarian: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('sort'))
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                @switch(request('sort'))
                                    @case('price_low') Harga Terendah @break
                                    @case('price_high') Harga Tertinggi @break
                                    @case('name') Nama A-Z @break
                                @endswitch
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Results Count --}}
                <div class="mb-4 text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $products->count() }}</span> dari 
                    <span class="font-semibold">{{ $products->total() }}</span> produk
                </div>

                {{-- Product Grid --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition group">
                                {{-- Product Image --}}
                                <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center group-hover:from-orange-200 group-hover:to-orange-300 transition">
                                            <span class="text-6xl">🥔</span>
                                        </div>
                                    @endif
                                    
                                    {{-- Badges --}}
                                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                                        @if($product->is_recommended)
                                            <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">
                                                ⭐ Rekomendasi
                                            </span>
                                        @endif
                                        @if($product->stock <= 5 && $product->stock > 0)
                                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                Stok Terbatas
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Out of Stock Overlay --}}
                                    @if($product->stock <= 0)
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                            <span class="bg-red-500 text-white font-bold px-4 py-2 rounded-lg">
                                                HABIS
                                            </span>
                                        </div>
                                    @endif
                                </a>

                                {{-- Product Info --}}
                                <div class="p-4">
                                    {{-- Category --}}
                                    <p class="text-xs text-orange-600 font-medium mb-1">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </p>
                                    
                                    {{-- Name --}}
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 hover:text-orange-600 transition">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    
                                    {{-- Price --}}
                                    <p class="text-xl font-bold text-orange-600 mb-2">
                                        {{ $product->formatted_price }}
                                    </p>
                                    
                                    {{-- Stock Info --}}
                                    <div class="flex items-center justify-between mb-4">
                                        @if($product->stock > 0)
                                            <span class="text-sm text-green-600">
                                                ✓ Stok: {{ $product->stock }}
                                            </span>
                                        @else
                                            <span class="text-sm text-red-500">
                                                ✗ Stok Habis
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Add to Cart Button --}}
                                    @auth
                                        @if($product->stock > 0)
                                            <form action="{{ route('cart.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" 
                                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    Tambah ke Keranjang
                                                </button>
                                            </form>
                                        @else
                                            <button disabled 
                                                    class="w-full bg-gray-300 text-gray-500 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                                Stok Habis
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="block w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                                            Login untuk Membeli
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif

                @else
                    {{-- Empty State --}}
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                        <p class="text-gray-600 mb-6">
                            @if(request('search'))
                                Tidak ada produk yang cocok dengan pencarian "{{ request('search') }}"
                            @else
                                Belum ada produk dalam kategori ini
                            @endif
                        </p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                            Lihat Semua Produk
                        </a>
                    </div>
                @endif

            </main>
        </div>

    </div>
</div>
@endsection