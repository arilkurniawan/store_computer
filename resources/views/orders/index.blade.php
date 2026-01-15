@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            
            <div class="flex items-start justify-between gap-4 flex-wrap mt-2">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-orange-600">
                        My Orders
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Track your purchases, payments, and delivery status.
                    </p>
                </div>

            </div>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 shadow-sm">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Orders List --}}
        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
                    {{-- Order Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 font-mono">
                                    {{ $order->invoice }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Placed on {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>

                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold ring-1
                                @if($order->status == 'pending') bg-yellow-50 text-yellow-800 ring-yellow-200
                                @elseif($order->status == 'waiting_confirmation') bg-blue-50 text-blue-800 ring-blue-200
                                @elseif($order->status == 'processing') bg-purple-50 text-purple-800 ring-purple-200
                                @elseif($order->status == 'shipped') bg-indigo-50 text-indigo-800 ring-indigo-200
                                @elseif($order->status == 'completed') bg-green-50 text-green-800 ring-green-200
                                @elseif($order->status == 'cancelled') bg-red-50 text-red-800 ring-red-200
                                @else bg-gray-50 text-gray-800 ring-gray-200
                                @endif">
                                {{ \App\Models\Order::getStatuses()[$order->status] ?? \Illuminate\Support\Str::headline($order->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $item->product_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Qty: {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </p>
                                </div>

                                @if(!$loop->last)
                                    <hr class="border-gray-100">
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/60">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div>
                                @if($order->status == 'pending' && !$order->payment_proof)
                                    <a href="{{ route('payment.confirm', $order) }}"
                                       class="inline-flex items-center gap-2 rounded-xl bg-white px-3 py-2 text-sm font-semibold text-amber-700
                                              ring-1 ring-amber-200 hover:bg-amber-50 hover:ring-amber-300 transition">
                                        Upload payment proof
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <p class="text-xs text-gray-500">
                                        @if($order->status == 'waiting_confirmation')
                                            Payment proof submitted. Waiting for confirmation.
                                        @elseif($order->status == 'processing')
                                            Your order is being prepared.
                                        @elseif($order->status == 'shipped')
                                            Your order has been shipped.
                                        @elseif($order->status == 'completed')
                                            Order completed.
                                        @elseif($order->status == 'cancelled')
                                            This order was cancelled.
                                        @else
                                            —
                                        @endif
                                    </p>
                                @endif
                            </div>

                            <div class="text-right">
                                @if($order->discount > 0)
                                    <p class="text-xs text-green-700">
                                        Discount: -Rp {{ number_format($order->discount, 0, ',', '.') }}
                                    </p>
                                @endif
                                <p class="text-base font-semibold text-gray-900">
                                    Total: <span class="text-amber-700">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-10 text-center">
                    <div class="mx-auto w-14 h-14 rounded-2xl bg-gray-100 ring-1 ring-gray-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17" />
                        </svg>
                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No orders yet</h3>
                    <p class="mt-1 text-sm text-gray-600">Once you place an order, it will show up here.</p>

                    <a href="{{ route('products.index') }}"
                       class="mt-6 inline-flex items-center justify-center rounded-2xl bg-orange-500 px-5 py-3 text-sm font-semibold text-white
                              shadow-sm hover:bg-orange-600 hover:shadow-md active:scale-[0.99] transition">
                        Start shopping
                    </a>

                    <div class="mt-5">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-amber-600 transition">
                            Back to Home
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif

        {{-- Back Button --}}
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-amber-600 transition">
                ← Back to Home
            </a>
        </div>

    </div>
</div>
@endsection
