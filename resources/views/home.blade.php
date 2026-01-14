@extends('layouts.app')

@section('title', 'Rill Store')

@section('content')

    {{-- HERO BANNER --}}
<section
    class="relative h-screen bg-cover bg-center"
    style="background-image: url('{{ asset('img/hero2.jpg') }}')"
>
    <!-- overlay gradasi -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/5 to-black/15"></div>

    <!-- konten -->
    <div class="relative z-10 h-full flex items-center">
        <div class="max-w-3xl mx-auto px-6 text-center text-white">
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
                Build Your <span class="text-orange-400">Dream Setup</span>
            </h1>

            <p class="mt-5 text-base md:text-lg text-gray-200 leading-relaxed">
                Customize your PC setup with powerful component and aesthetics
            </p>

            <div class="mt-8 flex justify-center">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2
                          bg-orange-500 hover:bg-orange-600
                          text-white font-semibold
                          px-8 py-4 rounded-full
                          shadow-lg hover:shadow-xl
                          transition duration-300">
                    Build Now
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>



    {{-- BANNERS SLIDER --}}
    @if(isset($banners) && $banners->count() > 0)
<section class="pt-8 pb-6 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Grid banner -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($banners as $banner)
            <a href="{{ $banner->link ?? '#' }}"
               class="group relative overflow-hidden rounded-2xl shadow-md hover:shadow-xl transition duration-300">

                <!-- Image -->
                <img src="{{ asset('storage/' . $banner->image) }}"
                     alt="{{ $banner->title }}"
                     class="w-full h-56 md:h-64 object-cover
                            transform group-hover:scale-105
                            transition duration-500">

                <!-- Overlay halus -->
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition"></div>

                <!-- title  -->
                @if(!empty($banner->title))
                <div class="absolute bottom-4 left-4 right-4 text-white">
                    <h3 class="text-lg font-semibold drop-shadow">
                        {{ $banner->title }}
                    </h3>
                </div>
                @endif

            </a>
            @endforeach
        </div>

    </div>
</section>
@endif


    {{-- KATEGORI --}}
<section class="pt-4 pb-4 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-2 md:px-3">

        <!-- Judul section -->
        <div class="text-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                Product Category
            </h2>
            <p class="text-gray-500 mt-2">
                Shop By Category
            </p>
        </div>

        <!-- Grid kategori -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
            @forelse($categories ?? [] as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
               class="group bg-white rounded-2xl p-6
                      text-center shadow-sm
                      hover:shadow-lg hover:-translate-y-1
                      transition duration-300">

                <!-- Nama kategori -->
                <h3 class="font-semibold text-gray-800 text-base md:text-lg">
                    {{ $category->name }}
                </h3>

                <!-- Jumlah produk -->
                <p class="text-sm text-gray-500 mt-2">
                    {{ $category->products_count ?? 0 }} produk
                </p>

            </a>
            @empty
            <p class="col-span-full text-center text-gray-500">
                Belum ada kategori
            </p>
            @endforelse
        </div>

    </div>
</section>

    {{-- PRODUK REKOMENDASI --}}
<section class="pt-4 pb-4 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-2 md:px-3">

        <!-- Judul section -->
        <div class="text-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                Recommended Products
            </h2>
            <p class="text-gray-500 mt-2">
                Best picks for you
            </p>
        </div>

        <!-- Grid produk -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @forelse($recommendedProducts ?? [] as $product)
            <a href="{{ route('products.show', $product) }}"
               class="group bg-white rounded-2xl overflow-hidden
                      shadow-sm hover:shadow-xl
                      transition duration-300">

                <!-- Image -->
                <div class="relative overflow-hidden">
                    <img src="{{ $product->image 
                            ? asset('storage/' . $product->image) 
                            : 'https://placehold.co/400x300/orange/white?text=Store' }}"
                         alt="{{ $product->name }}"
                         class="w-full h-48 object-cover
                                transform group-hover:scale-105
                                transition duration-500">

                    <!-- Overlay hover -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <p class="text-xs text-orange-600 font-medium mb-1">
                        {{ $product->category->name ?? '-' }}
                    </p>

                    <h3 class="font-semibold text-gray-800 text-sm md:text-base
                               mb-2 line-clamp-2">
                        {{ $product->name }}
                    </h3>

                    <div class="flex items-center justify-between mt-3">
                        <span class="text-orange-600 font-bold">
                            {{ $product->formatted_price }}
                        </span>

                        <span class="text-xs text-gray-500">
                            Stok {{ $product->stock }}
                        </span>
                    </div>
                </div>

            </a>
            @empty
            <p class="col-span-full text-center text-gray-500">
                Belum ada produk rekomendasi
            </p>
            @endforelse
        </div>
        
        <!-- tombol -->
        <div class="mt-4 text-center">
            <br>
                <a href="{{ route('products.index') }}" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-orange-700 transition">
                    View All Products
                </a>
        </div>
    </div>
</section>


@endsection
