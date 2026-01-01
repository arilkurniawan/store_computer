@extends('layouts.app')

@section('title', 'Keripik Sanjai - Oleh-oleh Khas Bukittinggi')

@section('content')

    {{-- HERO BANNER --}}
    <section class="bg-gradient-to-r from-orange-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Keripik Sanjai Asli Bukittinggi
            </h1>
            <p class="text-xl mb-8">
                Renyah, Gurih, dan Lezat! Oleh-oleh khas Sumatera Barat
            </p>
            <a href="{{ route('products.index') }}" class="bg-white text-orange-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition">
                Belanja Sekarang
            </a>
        </div>
    </section>

    {{-- BANNERS SLIDER --}}
    @if(isset($banners) && $banners->count() > 0)
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($banners as $banner)
                <a href="{{ $banner->link ?? '#' }}" class="block">
                    <img src="{{ asset('storage/' . $banner->image) }}" 
                         alt="{{ $banner->title }}"
                         class="w-full h-48 object-cover rounded-lg shadow-md hover:shadow-lg transition">
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- KATEGORI --}}
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">Kategori Produk</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @forelse($categories ?? [] as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                   class="bg-orange-50 p-4 rounded-lg text-center hover:bg-orange-100 transition">
                    <div class="text-3xl mb-2">🍟</div>
                    <h3 class="font-semibold text-gray-800">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->products_count ?? 0 }} produk</p>
                </a>
                @empty
                <p class="col-span-5 text-center text-gray-500">Belum ada kategori</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- PRODUK REKOMENDASI --}}
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">⭐ Produk Rekomendasi</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($recommendedProducts ?? [] as $product)
                <a href="{{ route('products.show', $product) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/300x200/orange/white?text=Keripik' }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-orange-600 mb-1">{{ $product->category->name ?? '-' }}</p>
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-orange-600 font-bold">{{ $product->formatted_price }}</p>
                        <p class="text-xs text-gray-500 mt-1">Stok: {{ $product->stock }}</p>
                    </div>
                </a>
                @empty
                <p class="col-span-4 text-center text-gray-500">Belum ada produk rekomendasi</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- PRODUK TERBARU --}}
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">🆕 Produk Terbaru</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($latestProducts ?? [] as $product)
                <a href="{{ route('products.show', $product) }}" class="bg-gray-50 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/300x200/orange/white?text=Keripik' }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-orange-600 mb-1">{{ $product->category->name ?? '-' }}</p>
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-orange-600 font-bold">{{ $product->formatted_price }}</p>
                    </div>
                </a>
                @empty
                <p class="col-span-4 text-center text-gray-500">Belum ada produk</p>
                @endforelse
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-orange-700 transition">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
    </section>

@endsection
