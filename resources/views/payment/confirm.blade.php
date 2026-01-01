@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">

        {{-- Flash Messages --}}
        @if (session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                @foreach ($errors->all() as $error)
                    <p class="text-red-700">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="text-center mb-6">
                <div class="text-4xl mb-2">💳</div>
                <h1 class="text-2xl font-bold">Konfirmasi Pembayaran</h1>
                <p class="text-gray-600">Silakan transfer sesuai total dan upload bukti pembayaran</p>
            </div>

            {{-- Info Order --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">No. Pesanan</span>
                    <span class="font-bold">{{ $order->invoice }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pembayaran</span>
                    <span class="font-bold text-2xl text-amber-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Info Rekening --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold mb-3">🏦 Transfer ke Rekening:</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bank</span>
                        <span class="font-medium">BCA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Rekening</span>
                        <span class="font-medium font-mono">1234567890</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Atas Nama</span>
                        <span class="font-medium">Toko Keripik Sanjai</span>
                    </div>
                </div>
            </div>

            {{-- Detail Item --}}
            <div class="mb-6">
                <h3 class="font-semibold mb-3">📦 Detail Pesanan:</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    @if($order->discount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Diskon</span>
                            <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Form Upload Bukti --}}
            <form action="{{ route('payment.upload', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">📸 Upload Bukti Transfer *</label>
                    <input type="file" name="payment_proof" accept="image/*" required
                           class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-amber-500"
                           onchange="previewImage(this)">
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    
                    {{-- Preview --}}
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="preview" src="" alt="Preview" class="max-w-xs rounded-lg shadow">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">📝 Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" 
                              placeholder="Contoh: Transfer dari rekening a.n. Budi"
                              class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-lg text-lg">
                    ✓ KIRIM BUKTI PEMBAYARAN
                </button>
            </form>
        </div>

    </div>
</div>

{{-- ⭐⭐⭐ POPUP PESANAN DIBUAT ⭐⭐⭐ --}}
@if(session('success'))
<div id="orderCreatedPopup" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 text-center transform animate-bounce-in">
        {{-- Success Icon --}}
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-2">🎉 Pesanan Dibuat!</h2>
        <p class="text-gray-600 mb-2">No. Pesanan: <span class="font-bold">{{ $order->invoice }}</span></p>
        <p class="text-gray-500 mb-6">Silakan transfer dan upload bukti pembayaran</p>
        
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-600">Total yang harus dibayar:</p>
            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>

        <button onclick="closePopup()" 
                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-lg">
            OK, LANJUTKAN
        </button>
    </div>
</div>

<style>
    @keyframes bounce-in {
        0% { transform: scale(0.5); opacity: 0; }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-bounce-in {
        animation: bounce-in 0.5s ease-out;
    }
</style>

<script>
    function closePopup() {
        window.location.href = '{{ route("home") }}';
    }
    
    // Auto redirect after 10 seconds
    setTimeout(function() {
        window.location.href = '{{ route("home") }}';
    }, 10000);
</script>

@endif

{{-- Script Preview Image --}}
<script>
    function previewImage(input) {
        var preview = document.getElementById('preview');
        var previewContainer = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
