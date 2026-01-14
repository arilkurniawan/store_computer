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

        {{-- Success Message (setelah upload bukti) --}}
        @if (session('success') && $order->payment_proof)
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700">✅ {{ session('success') }}</p>
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="text-center mb-6">
                <div class="text-4xl mb-2">💳</div>
                <h1 class="text-2xl font-bold">Payment Confirmation</h1>
                <p class="text-gray-600">Please complete your transfer and upload the payment proof below</p>
            </div>

            {{-- Status Badge --}}
            <div class="flex justify-center mb-6">
                <span class="px-4 py-2 rounded-full text-sm font-medium
                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status == 'waiting_confirmation') bg-blue-100 text-blue-800
                    @elseif($order->status == 'confirmed') bg-green-100 text-green-800
                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    @switch($order->status)
                        @case('pending')
                            ⏳ Awaiting Payment
                            @break
                        @case('waiting_confirmation')
                            🔍 Payment Under Review
                            @break
                        @case('confirmed')
                            ✅ Payment Confirmed
                            @break
                        @case('processing')
                            📦 Order Processing
                            @break
                        @case('shipped')
                            🚚 Shipped
                            @break
                        @case('completed')
                            ✅ Order Complete
                            @break
                        @case('cancelled')
                            ❌ Order Cancelled
                            @break
                        @default
                            {{ ucfirst($order->status) }}
                    @endswitch
                </span>
            </div>

            {{-- Info Order --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Order Number</span>
                    <span class="font-bold font-mono">{{ $order->invoice }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Order Date</span>
                    <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Payment</span>
                    <span class="font-bold text-2xl text-amber-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Info Rekening --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold mb-3">🏦 Transfer to Bank Account:</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bank</span>
                        <span class="font-medium">BCA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Account Number</span>
                        <div class="flex items-center gap-2">
                            <span class="font-medium font-mono text-lg">24 0154</span>
                            <button onclick="copyToClipboard('1234567890')" 
                                    class="text-amber-600 hover:text-amber-700 text-sm">
                                📋 Copy
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Account Holder</span>
                        <span class="font-medium">Ariel Kurniawan</span>
                    </div>
                </div>
                
                {{-- Copy Amount --}}
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Transfer Amount</span>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            <button onclick="copyToClipboard('{{ $order->total }}')" 
                                    class="text-amber-600 hover:text-amber-700 text-sm">
                                📋 Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Item --}}
            <div class="mb-6">
                <h3 class="font-semibold mb-3">📦 Order Details:</h3>
                <div class="space-y-2 bg-gray-50 rounded-lg p-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    
                    <hr class="my-2">
                    
                    <div class="flex justify-between text-sm">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->discount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Discount</span>
                            <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between font-bold pt-2 border-t">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Alamat Pengiriman --}}
            <div class="mb-6">
                <h3 class="font-semibold mb-3">📍 Shipping Address:</h3>
                <div class="bg-gray-50 rounded-lg p-4 text-sm">
                    <p class="font-medium">{{ $order->shipping_name }}</p>
                    <p class="text-gray-600">{{ $order->shipping_phone }}</p>
                    <p class="text-gray-600 mt-1">{{ $order->shipping_address }}</p>
                    <p class="text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                    @if($order->notes)
                        <p class="text-gray-500 mt-2 italic">Notes: {{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            {{-- ================================ --}}
            {{-- UPLOAD BUKTI PEMBAYARAN --}}
            {{-- ================================ --}}
            @if($order->status == 'pending')
                {{-- Form Upload --}}
                <form action="{{ route('payment.upload', $order) }}" method="POST" enctype="multipart/form-data" class="border-t pt-6">
                    @csrf
                    
                    <h3 class="font-semibold mb-3">📸 Upload Payment Proof</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Choose File *</label>
                        <input type="file" name="payment_proof" accept="image/*" required
                               class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-amber-500 transition"
                               onchange="previewImage(this)">
                        <p class="text-sm text-gray-500 mt-1">Accepted formats: JPG, PNG (Max 2MB)</p>
                        
                        {{-- Preview --}}
                        <div id="imagePreview" class="mt-3 hidden">
                            <p class="text-sm text-gray-600 mb-2">Preview:</p>
                            <img id="preview" src="" alt="Preview" class="max-w-xs rounded-lg shadow border">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">📝 Payment Notes (Opsional)</label>
                        <textarea name="notes" rows="2" 
                                  placeholder="e.g. Transfer from Rill bank account"
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-lg text-lg transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        CONFIRM PAYMENT
                    </button>
                </form>

            @elseif($order->status == 'waiting_confirmation')
                {{-- Bukti Sudah Diupload --}}
                <div class="border-t pt-6">
                    <h3 class="font-semibold mb-3">📸 Payment Proof</h3>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <div class="text-2xl">⏳</div>
                            <div>
                                <p class="font-medium text-blue-800">Payment proof is being reviewed</p>
                                <p class="text-sm text-blue-600 mt-1">Our team will confirm your payment within 24 hours</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($order->payment_proof)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Payment Proof:</p>
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                                 alt="Bukti Pembayaran" 
                                 class="max-w-xs rounded-lg shadow border cursor-pointer hover:opacity-90"
                                 onclick="window.open(this.src, '_blank')">
                            <p class="text-xs text-gray-500 mt-1">Tap or click the image to view details</p>
                        </div>
                    @endif
                </div>

            @elseif(in_array($order->status, ['confirmed', 'processing', 'shipped', 'completed']))
                {{-- Order Sedang Diproses --}}
                <div class="border-t pt-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="text-2xl">✅</div>
                            <div>
                                <p class="font-medium text-green-800">Pembayaran telah dikonfirmasi!</p>
                                <p class="text-sm text-green-600 mt-1">
                                    @if($order->status == 'confirmed')
                                        Pesanan Anda akan segera diproses.
                                    @elseif($order->status == 'processing')
                                        Pesanan Anda sedang dikemas.
                                    @elseif($order->status == 'shipped')
                                        Pesanan Anda sedang dalam pengiriman.
                                    @elseif($order->status == 'completed')
                                        Pesanan telah selesai. Terima kasih! 🎉
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($order->status == 'cancelled')
                {{-- Order Dibatalkan --}}
                <div class="border-t pt-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="text-2xl">❌</div>
                            <div>
                                <p class="font-medium text-red-800">Pesanan telah dibatalkan</p>
                                <p class="text-sm text-red-600 mt-1">Silakan buat pesanan baru jika ingin berbelanja kembali.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="mt-6 pt-6 border-t flex flex-col sm:flex-row gap-3">
                <a href="{{ route('orders.index') }}" 
                   class="flex-1 text-center py-3 px-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                    📋 View All Orders
                </a>
                <a href="{{ route('products.index') }}" 
                   class="flex-1 text-center py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition font-medium">
                    🛍️ Shop Again
                </a>
            </div>

        </div>

    </div>
</div>

{{-- ================================ --}}
{{-- POPUP PESANAN BERHASIL DIBUAT --}}
{{-- ================================ --}}
@if(session('success') && !$order->payment_proof)
<div id="orderCreatedPopup" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 text-center transform animate-bounce-in">
        {{-- Success Icon --}}
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-2">🎉 Order Created!</h2>
        <p class="text-gray-600 mb-2">Order Number: <span class="font-bold font-mono">{{ $order->invoice }}</span></p>
        <p class="text-gray-500 mb-6">Please complete the transfer and upload your payment proof below</p>
        
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-600">Total Amount to Pay:</p>
            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>

        {{-- ✅ PERUBAHAN: Tombol menutup popup saja, tidak redirect --}}
        <button onclick="closePopup()" 
                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-lg transition">
            OK, Upload Payment Proof
        </button>
        
        <p class="text-xs text-gray-400 mt-3">
            This window will close automatically in <span id="countdown">10</span> seconds
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
    // ✅ PERUBAHAN: Hanya menutup popup, tidak redirect
    function closePopup() {
        document.getElementById('orderCreatedPopup').style.display = 'none';
        // Scroll ke form upload
        document.querySelector('form')?.scrollIntoView({ behavior: 'smooth' });
    }
    
    // Countdown timer
    let timeLeft = 10;
    const countdownEl = document.getElementById('countdown');
    
    const countdownTimer = setInterval(function() {
        timeLeft--;
        if (countdownEl) countdownEl.textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(countdownTimer);
            closePopup(); // ✅ Hanya tutup popup, tidak redirect
        }
    }, 1000);
</script>
@endif

{{-- Script Preview Image & Copy --}}
<script>
    function previewImage(input) {
        var preview = document.getElementById('preview');
        var previewContainer = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            // Validasi ukuran file (max 2MB)
            if (input.files[0].size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                input.value = '';
                return;
            }
            
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show toast notification
            showToast('Berhasil disalin!');
        }).catch(function(err) {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast('Berhasil disalin!');
        });
    }
    
    function showToast(message) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Remove after 2 seconds
        setTimeout(() => {
            toast.remove();
        }, 2000);
    }
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
@endsection