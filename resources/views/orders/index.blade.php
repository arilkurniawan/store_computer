@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold">📦 Pesanan Saya</h1>
            <p class="text-gray-600">Riwayat semua pesanan Anda</p>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Daftar Pesanan --}}
        @forelse($orders as $order)
            <div class="bg-white rounded-lg shadow mb-4 overflow-hidden">
                {{-- Header Order --}}
                <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                    <div>
                        <span class="font-bold">{{ $order->invoice }}</span>
                        <span class="text-gray-500 text-sm ml-2">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status == 'processing') bg-purple-100 text-purple-800
                        @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                        @elseif($order->status == 'completed') bg-green-100 text-green-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                {{-- Items --}}
                <div class="p-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between py-2 {{ !$loop->last ? 'border-b' : '' }}">
                            <div>
                                <span class="font-medium">{{ $item->product_name }}</span>
                                <span class="text-gray-500 text-sm">x{{ $item->quantity }}</span>
                            </div>
                            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="bg-gray-50 px-4 py-3 border-t flex justify-between items-center">
                    <div>
                        @if($order->status == 'pending' && !$order->payment_proof)
                            <a href="{{ route('payment.confirm', $order) }}" 
                               class="text-amber-600 hover:underline font-medium">
                                Upload Bukti Bayar →
                            </a>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($order->discount > 0)
                            <span class="text-sm text-green-600">Diskon: -Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                        @endif
                        <p class="font-bold text-lg">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-bold mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-600 mb-4">Anda belum pernah melakukan pemesanan</p>
                <a href="{{ route('home') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-6 py-2 rounded-lg">
                    Belanja Sekarang
                </a>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif

        {{-- Back Button --}}
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-gray-600 hover:underline">
                ← Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
@endsection
