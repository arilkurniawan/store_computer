@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Shopping Cart</h1>
            <p class="text-sm text-gray-500 mt-1">Check your items before checkout.</p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($carts->isEmpty())
            {{-- Empty Cart --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center">
                <h2 class="text-lg font-semibold text-gray-900">Your cart is empty</h2>
                <p class="text-gray-500 text-sm mt-2">Browse products and add something you like.</p>

                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center mt-6 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                    Shop products
                </a>
            </div>
        @else

            <div class="grid lg:grid-cols-3 gap-6">

                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-3">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

                        <div class="px-6 py-4 border-b border-gray-100">
                            <p class="text-sm text-gray-600">
                                {{ $carts->sum('quantity') }} item{{ $carts->sum('quantity') > 1 ? 's' : '' }} in your cart
                            </p>
                        </div>

                        @foreach ($carts as $cart)
                            <div class="px-6 py-5 border-b last:border-b-0 border-gray-100">
                                <div class="flex gap-4">

                                    {{-- Image --}}
                                    <div class="w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        @if($cart->product->image)
                                            <img
                                                src="{{ Storage::url($cart->product->image) }}"
                                                class="w-full h-full object-cover"
                                                alt="{{ $cart->product->name }}"
                                                loading="lazy"
                                                decoding="async"
                                            >
                                        @else
                                            <span class="text-xl">📦</span>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <h3 class="font-semibold text-gray-900 truncate">
                                                    {{ $cart->product->name }}
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            {{-- Subtotal --}}
                                            <div class="text-right flex-shrink-0">
                                                <p class="text-xs text-gray-500">Subtotal</p>
                                                <p class="font-semibold text-gray-900">
                                                    Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Actions Row --}}
                                        <div class="mt-4 flex items-center justify-between flex-wrap gap-3">

                                            {{-- Quantity --}}
                                            <form action="{{ route('cart.update', $cart) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')

                                                <label class="text-sm text-gray-600">Qty</label>
                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    value="{{ $cart->quantity }}"
                                                    min="1"
                                                    class="w-20 px-3 py-2 border border-gray-200 rounded-lg text-sm text-center focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                >

                                                <button type="submit"
                                                        class="px-3 py-2 rounded-lg text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-800 transition">
                                                    Update
                                                </button>
                                            </form>

                                            {{-- Remove --}}
                                            <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Remove this item?')"
                                                    class="text-sm font-medium text-red-600 hover:text-red-700 transition"
                                                >
                                                    Remove
                                                </button>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Secondary actions --}}
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <a href="{{ route('products.index') }}"
                           class="text-sm font-medium text-gray-700 hover:text-orange-600 transition">
                            ← Continue shopping
                        </a>

                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Clear your cart?')"
                                    class="text-sm font-medium text-red-600 hover:text-red-700 transition">
                                Clear cart
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900">Summary</h2>

                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Items</span>
                                <span>{{ $carts->sum('quantity') }}</span>
                            </div>

                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <div class="border-t pt-3 flex justify-between font-semibold text-gray-900">
                                <span>Total</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}"
                           class="mt-6 w-full inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-lg transition">
                            Checkout
                        </a>

                        <p class="text-xs text-gray-500 mt-3">
                            Shipping and payment will be calculated at checkout.
                        </p>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>
@endsection
