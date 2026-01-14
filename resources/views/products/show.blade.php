@extends('layouts.app')

@section('title', $product->name . ' - Rill Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6">
        <ol class="flex items-center gap-2 flex-wrap">
            <li><a href="{{ route('home') }}" class="hover:text-orange-600">Home</a></li>
            <li class="text-gray-300">/</li>
            <li><a href="{{ route('products.index') }}" class="hover:text-orange-600">Products</a></li>
            <li class="text-gray-300">/</li>
            <li class="text-gray-800 font-medium line-clamp-1">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid lg:grid-cols-2 gap-10">

        {{-- Product Image --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if($product->image)
                <div class="bg-white">
                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-[420px] sm:h-[520px] object-contain p-6"
                        loading="eager"
                        decoding="async"
                    >
                </div>
            @else
                <div class="w-full h-[420px] sm:h-[520px] bg-gray-100 flex items-center justify-center">
                    <span class="text-7xl">🛒</span>
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div class="space-y-6">

            {{-- Category + Stock --}}
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <span class="inline-flex items-center bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">
                    {{ $product->category->name ?? 'Uncategorized' }}
                </span>

                @if($product->stock > 0)
                    <span class="inline-flex items-center gap-2 text-sm text-green-700">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        In stock ({{ $product->stock }})
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 text-sm text-red-600">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        Out of stock
                    </span>
                @endif
            </div>

            {{-- Name --}}
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">
                {{ $product->name }}
            </h1>

            {{-- Price --}}
            <div class="text-3xl font-bold text-orange-600">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 mb-2">Description</h3>

                @if($product->description)
                    <div class="prose max-w-none prose-sm text-gray-700">
                        {!! $product->description !!}
                    </div>
                @else
                    <p class="text-gray-600 text-sm">No description available.</p>
                @endif
            </div>

            {{-- Add to Cart --}}
            @auth
                @if($product->stock > 0)
                    <form action="{{ route('cart.store') }}" method="POST" class="bg-white rounded-xl border border-gray-100 shadow-sm p-5" data-ajax-cart>
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Quantity</p>
                                <p class="text-xs text-gray-500">Max: {{ $product->stock }}</p>
                            </div>

                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button type="button" onclick="decreaseQty()"
                                        class="px-4 py-2 text-gray-700 hover:bg-gray-50 active:bg-gray-100">
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
                                    class="w-16 text-center border-0 focus:ring-0 text-gray-900 font-semibold"
                                >

                                <button type="button" onclick="increaseQty()"
                                        class="px-4 py-2 text-gray-700 hover:bg-gray-50 active:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="mt-4 w-full bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Add to Cart
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-200 text-gray-500 font-semibold py-3 px-6 rounded-lg cursor-not-allowed">
                        Out of Stock
                    </button>
                @endif
            @else
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <a
                        href="{{ route('login') }}"
                        class="block w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition text-center"
                    >
                        Login to Purchase
                    </a>
                    <p class="text-xs text-gray-500 mt-3 text-center">You need to be logged in to add items to your cart.</p>
                </div>
            @endauth

        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-14">
            <div class="flex items-end justify-between gap-4 flex-wrap mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Related Products</h2>
                <a href="{{ route('products.index') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700">
                    View all →
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('products.show', $related->slug ?? $related->id) }}"
                       class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">
                        @if($related->image)
                            <img
                                src="{{ asset('storage/' . $related->image) }}"
                                alt="{{ $related->name }}"
                                class="w-full h-40 object-cover hover:scale-105 transition duration-300"
                                loading="lazy"
                                decoding="async"
                            >
                        @else
                            <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                                <span class="text-4xl">🛒</span>
                            </div>
                        @endif

                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 truncate">{{ $related->name }}</h3>
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
    const maxStock = {{ (int) $product->stock }};

    function decreaseQty() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value || '1', 10);
        if (current > 1) input.value = current - 1;
    }

    function increaseQty() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value || '1', 10);
        if (current < maxStock) input.value = current + 1;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('quantity');
        if (!input) return;

        input.addEventListener('input', () => {
            let val = parseInt(input.value || '1', 10);
            if (isNaN(val) || val < 1) val = 1;
            if (val > maxStock) val = maxStock;
            input.value = val;
        });
    });
</script>
@endsection
