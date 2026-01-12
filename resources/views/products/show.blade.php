@extends('layouts.app')

@section('title', $product->name . ' - Rill Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    {{-- Breadcrumb --}}
    

    <div class="grid md:grid-cols-2 gap-8">
        
        {{-- Product Image --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($product->image)
                <img 
                    src="{{ asset('storage/' . $product->image) }}" 
                    alt="{{ $product->name }}" 
                    class="w-full h-auto object-contain max-h-[500px]"
                >
            @else
                <div class="w-full h-96 bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                    <span class="text-8xl">🥔</span>
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div class="space-y-6">
            {{-- Category Badge --}}
            <span class="inline-block bg-orange-100 text-orange-600 text-sm px-3 py-1 rounded-full">
                {{ $product->category->name ?? 'Uncategorized' }}
            </span>

            {{-- Product Name --}}
            <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>

            {{-- Price --}}
            <div class="text-3xl font-bold text-orange-600">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            {{-- Stock Status --}}
            <div class="flex items-center gap-2">
                @if($product->stock > 0)
                    <span class="inline-flex items-center gap-1 text-green-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Stok tersedia: {{ $product->stock }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-red-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Stok habis
                    </span>
                @endif
            </div>

            {{-- Description --}}
            <div class="border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-2">Deskripsi</h3>

                @if($product->description)
                    {!! $product->description !!}
                @else
                    <p class="text-gray-600">Tidak ada deskripsi.</p>
                @endif
            </div>

            {{-- Add to Cart Form --}}
            @auth
                @if($product->stock > 0)
                    <form action="{{ route('cart.store') }}" method="POST" class="border-t pt-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="flex items-center gap-4 mb-4">
                            <label class="text-gray-700 font-medium">Jumlah:</label>
                            <div class="flex items-center border rounded-lg">
                                <button type="button" onclick="decreaseQty()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input 
                                    type="number" 
                                    name="quantity" 
                                    id="quantity"
                                    value="1" 
                                    min="1" 
                                    max="{{ $product->stock }}"
                                    class="w-16 text-center border-0 focus:ring-0"
                                >
                                <button type="button" onclick="increaseQty()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-6 rounded-lg cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            @else
                <div class="border-t pt-6">
                    <a 
                        href="{{ route('login') }}" 
                        class="block w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition text-center"
                    >
                        Login untuk Membeli
                    </a>
                </div>
            @endauth
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Terkait</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('products.show', $related->slug ?? $related->id) }}" class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition group">
                        @if($related->image)
                            <img 
                                src="{{ asset('storage/' . $related->image) }}" 
                                alt="{{ $related->name }}" 
                                class="w-full h-40 object-cover group-hover:scale-105 transition duration-300"
                            >
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                                <span class="text-4xl">🥔</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-medium text-gray-800 truncate">{{ $related->name }}</h3>
                            <p class="text-orange-600 font-bold mt-1">
                                Rp {{ number_format($related->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>

{{-- Quantity Script --}}
<script>
    const maxStock = {{ $product->stock }};
    
    function decreaseQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
    
    function increaseQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) < maxStock) {
            input.value = parseInt(input.value) + 1;
        }
    }
</script>
@endsection
