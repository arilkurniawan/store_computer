@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">🛒 Keranjang Belanja</h1>
            <p class="text-gray-600">Kelola item belanjaan Anda</p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700">✓ {{ session('success') }}</p>
            </div>
        @endif

        @if ($carts->isEmpty())
            {{-- Empty Cart --}}
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-xl font-semibold mb-2">Keranjang Kosong</h2>
                <p class="text-gray-500 mb-6">Belum ada produk di keranjang</p>
                <a href="{{ route('products.index') }}" 
                   class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-6 rounded-lg">
                    Mulai Belanja
                </a>
            </div>
        @else
            {{-- Cart Items --}}
            <div class="bg-white rounded-lg shadow mb-6">
                @foreach ($carts as $cart)
                    <div class="p-6 border-b flex items-center gap-4">
                        {{-- Image --}}
                        <div class="w-20 h-20 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($cart->product->image)
                                <img src="{{ Storage::url($cart->product->image) }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <span class="text-2xl">🍠</span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-grow">
                            <h3 class="font-semibold">{{ $cart->product->name }}</h3>
                            <p class="text-amber-600">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                        </div>

                        {{-- Quantity --}}
                        <form action="{{ route('cart.update', $cart) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <span class="text-sm text-gray-500">Qty:</span>
                            <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" 
                                   class="w-16 px-2 py-1 border rounded text-center">
                            <button type="submit" class="bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded text-sm">
                                Update
                            </button>
                        </form>

                        {{-- Subtotal --}}
                        <div class="text-right min-w-[100px]">
                            <p class="text-sm text-gray-500">Subtotal</p>
                            <p class="font-bold">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</p>
                        </div>

                        {{-- Delete --}}
                        <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus item?')" 
                                    class="text-red-500 hover:bg-red-50 p-2 rounded">
                                🗑️
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- Summary & Checkout Button --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-lg mb-4">Ringkasan Belanja</h2>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Total Item</span>
                        <span>{{ $carts->sum('quantity') }} produk</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-amber-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- ⭐ TOMBOL CHECKOUT --}}
                <div class="flex flex-col gap-3 mt-6">
                    <a href="{{ route('checkout.index') }}" 
                       class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 px-6 rounded-lg text-center text-lg transition">
                        🛍️ Checkout Sekarang
                    </a>
                    
                    <a href="{{ route('products.index') }}" 
                       class="w-full border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-3 px-6 rounded-lg text-center transition">
                        ← Lanjut Belanja
                    </a>
                </div>

                {{-- Clear Cart --}}
                <form action="{{ route('cart.clear') }}" method="POST" class="mt-4 text-center">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Kosongkan keranjang?')"
                            class="text-sm text-red-500 hover:underline">
                        🗑️ Kosongkan Keranjang
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
@endsection
