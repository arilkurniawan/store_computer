@extends('layouts.app')

@section('title', 'Payment Confirmation')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Flash Messages --}}
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 shadow-sm">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 shadow-sm">
                <p class="font-semibold text-red-700 mb-2">Please fix the following issues:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success') && $order->payment_proof)
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 shadow-sm">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Header Card --}}
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="px-6 py-6 border-b border-gray-100">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight text-gray-900">
                            Payment Confirmation
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">
                            Transfer the exact amount, then upload your payment proof below.
                        </p>
                    </div>

                    <a href="{{ route('orders.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 hover:text-amber-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 19l-7-7 7-7" />
                        </svg>
                        View Orders
                    </a>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6 grid lg:grid-cols-12 gap-6">
                {{-- Left: Main Info --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Status --}}
                    <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Status</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    @if($order->status == 'pending')
                                        Waiting for payment.
                                    @elseif($order->status == 'waiting_confirmation')
                                        Payment proof submitted. Our team is reviewing it.
                                    @elseif(in_array($order->status, ['confirmed', 'processing', 'shipped', 'completed']))
                                        Payment confirmed. Your order is moving forward.
                                    @elseif($order->status == 'cancelled')
                                        This order has been cancelled.
                                    @else
                                        Current status: {{ ucfirst($order->status) }}.
                                    @endif
                                </p>
                            </div>

                            <span class="px-4 py-2 rounded-full text-xs font-semibold ring-1
                                @if($order->status == 'pending') bg-yellow-50 text-yellow-800 ring-yellow-200
                                @elseif($order->status == 'waiting_confirmation') bg-blue-50 text-blue-800 ring-blue-200
                                @elseif($order->status == 'confirmed') bg-green-50 text-green-800 ring-green-200
                                @elseif($order->status == 'processing') bg-indigo-50 text-indigo-800 ring-indigo-200
                                @elseif($order->status == 'shipped') bg-purple-50 text-purple-800 ring-purple-200
                                @elseif($order->status == 'completed') bg-green-50 text-green-800 ring-green-200
                                @elseif($order->status == 'cancelled') bg-red-50 text-red-800 ring-red-200
                                @else bg-gray-50 text-gray-800 ring-gray-200
                                @endif">
                                @switch($order->status)
                                    @case('pending')
                                        Awaiting Payment
                                        @break
                                    @case('waiting_confirmation')
                                        Under Review
                                        @break
                                    @case('confirmed')
                                        Payment Confirmed
                                        @break
                                    @case('processing')
                                        Processing
                                        @break
                                    @case('shipped')
                                        Shipped
                                        @break
                                    @case('completed')
                                        Completed
                                        @break
                                    @case('cancelled')
                                        Cancelled
                                        @break
                                    @default
                                        {{ ucfirst($order->status) }}
                                @endswitch
                            </span>
                        </div>
                    </div>

                    {{-- Order Info --}}
                    <div class="rounded-2xl bg-amber-50 ring-1 ring-amber-200 p-5">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Order Number</span>
                            <span class="text-sm font-semibold font-mono text-gray-900">{{ $order->invoice }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Order Date</span>
                            <span class="text-sm font-medium text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Amount</span>
                            <span class="text-xl font-semibold text-amber-700">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <p class="mt-3 text-xs text-amber-900/70">
                            Please transfer the exact amount to help automatic matching.
                        </p>
                    </div>

                    {{-- Bank Details --}}
                    <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-900">Transfer Details</h3>
                        <p class="text-xs text-gray-600 mt-1">Use the account information below.</p>

                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Bank</span>
                                <span class="font-medium text-gray-900">BCA</span>
                            </div>

                            @php
                                $accountNumber = '240154';   // <— set your real account number here
                                $accountLabel  = '24 0154';  // <— how you want it displayed
                                $accountHolder = 'Ariel Kurniawan';
                            @endphp

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Account Number</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold font-mono text-gray-900">{{ $accountLabel }}</span>
                                    <button type="button"
                                            onclick="copyToClipboard('{{ $accountNumber }}')"
                                            class="text-amber-700 hover:text-amber-800 text-xs font-semibold transition">
                                        Copy
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Account Holder</span>
                                <span class="font-medium text-gray-900">{{ $accountHolder }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Transfer Amount</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                    <button type="button"
                                            onclick="copyToClipboard('{{ $order->total }}')"
                                            class="text-amber-700 hover:text-amber-800 text-xs font-semibold transition">
                                        Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Upload / Status Sections --}}
                    @if($order->status == 'pending')
                        <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900">Upload Payment Proof</h3>
                            <p class="text-xs text-gray-600 mt-1">Upload a screenshot or photo of your transfer receipt.</p>

                            <form id="paymentUploadForm" action="{{ route('payment.upload', $order) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                @csrf

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">File <span class="text-red-500">*</span></label>
                                    <input type="file" name="payment_proof" accept="image/*" required
                                           class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-amber-400 transition bg-white"
                                           onchange="previewImage(this)">
                                    <p class="text-xs text-gray-500 mt-2">Accepted: JPG, PNG (max 2MB).</p>

                                    <div id="imagePreview" class="mt-4 hidden">
                                        <p class="text-xs text-gray-600 mb-2">Preview</p>
                                        <img id="preview" src="" alt="Preview" class="max-w-xs rounded-2xl shadow-sm ring-1 ring-gray-200">
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Notes (optional)</label>
                                    <textarea name="notes" rows="2"
                                              placeholder="e.g., Transfer from my BCA account"
                                              class="w-full px-4 py-2.5 border border-gray-200 rounded-2xl text-sm outline-none
                                                     focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition"></textarea>
                                </div>

                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-orange-500 px-6 py-3.5
                                               text-sm font-semibold text-white shadow-sm hover:bg-orange-600 hover:shadow-md
                                               active:scale-[0.99] transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Submit Payment Proof
                                </button>
                            </form>
                        </div>

                    @elseif($order->status == 'waiting_confirmation')
                        <div class="rounded-2xl ring-1 ring-blue-200 bg-blue-50 p-5">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5">
                                    <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 002 0V7zm-1 8a1.25 1.25 0 100-2.5A1.25 1.25 0 0010 15z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900">Payment proof submitted</p>
                                    <p class="text-xs text-blue-800/80 mt-1">We’ll review it as soon as possible (usually within 24 hours).</p>
                                </div>
                            </div>

                            @if($order->payment_proof)
                                <div class="mt-4">
                                    <p class="text-xs text-blue-900/80 mb-2">Uploaded proof</p>
                                    <img src="{{ asset('storage/' . $order->payment_proof) }}"
                                         alt="Payment proof"
                                         class="max-w-xs rounded-2xl shadow-sm ring-1 ring-blue-200 cursor-pointer hover:opacity-95 transition"
                                         onclick="window.open(this.src, '_blank')">
                                    <p class="text-xs text-blue-900/70 mt-2">Click the image to view full size.</p>
                                </div>
                            @endif
                        </div>

                    @elseif(in_array($order->status, ['confirmed', 'processing', 'shipped', 'completed']))
                        <div class="rounded-2xl ring-1 ring-green-200 bg-green-50 p-5">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5">
                                    <svg class="w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-green-900">Payment confirmed</p>
                                    <p class="text-xs text-green-800/80 mt-1">
                                        @if($order->status == 'confirmed')
                                            Your order will be processed shortly.
                                        @elseif($order->status == 'processing')
                                            Your items are being packed.
                                        @elseif($order->status == 'shipped')
                                            Your order is on the way.
                                        @elseif($order->status == 'completed')
                                            Order completed. Thank you for shopping with us.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                    @elseif($order->status == 'cancelled')
                        <div class="rounded-2xl ring-1 ring-red-200 bg-red-50 p-5">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5">
                                    <svg class="w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-red-900">Order cancelled</p>
                                    <p class="text-xs text-red-800/80 mt-1">You can place a new order anytime.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Shipping Address --}}
                    <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-900">Shipping Address</h3>
                        <div class="mt-3 text-sm">
                            <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                            <p class="text-gray-600">{{ $order->shipping_phone }}</p>
                            <p class="text-gray-600 mt-2">{{ $order->shipping_address }}</p>
                            <p class="text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                            @if($order->notes)
                                <p class="text-gray-500 mt-2 italic">Notes: {{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Order Details --}}
                <div class="lg:col-span-5">
                    <div class="lg:sticky lg:top-24 space-y-6">

                        <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900">Order Details</h3>

                            <div class="mt-4 space-y-2 text-sm">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">{{ $item->product_name }} × {{ $item->quantity }}</span>
                                        <span class="text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach

                                <hr class="my-3 border-gray-100">

                                <div class="flex justify-between text-gray-700">
                                    <span>Subtotal</span>
                                    <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>

                                @if($order->discount > 0)
                                    <div class="flex justify-between text-green-700">
                                        <span>Discount</span>
                                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between font-semibold pt-3 border-t border-gray-100">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-amber-700">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="grid sm:grid-cols-2 gap-3">
                            <a href="{{ route('orders.index') }}"
                               class="text-center py-3 px-4 rounded-2xl ring-1 ring-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:text-amber-600 transition font-semibold text-sm">
                                View Orders
                            </a>
                            <a href="{{ route('products.index') }}"
                               class="text-center py-3 px-4 rounded-2xl bg-orange-500 text-white hover:bg-orange-600 transition font-semibold text-sm shadow-sm hover:shadow-md active:scale-[0.99]">
                                Shop Again
                            </a>
                        </div>

                        <p class="text-xs text-gray-500 text-center">
                            Need help? Contact support with your order number.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Order Created Popup --}}
@if(session('success') && !$order->payment_proof)
<div id="orderCreatedPopup" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 text-center animate-bounce-in">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h2 class="text-xl font-semibold text-gray-900 mb-2">Order placed</h2>
        <p class="text-gray-600 mb-2 text-sm">Order Number: <span class="font-semibold font-mono">{{ $order->invoice }}</span></p>
        <p class="text-gray-500 mb-6 text-sm">Please transfer the amount below, then upload your payment proof.</p>

        <div class="bg-amber-50 ring-1 ring-amber-200 rounded-2xl p-4 mb-6">
            <p class="text-xs text-gray-600">Total Amount</p>
            <p class="text-2xl font-semibold text-amber-700">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>

        <button type="button" onclick="closePopup()"
                class="w-full rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 transition shadow-sm hover:shadow-md active:scale-[0.99]">
            OK, upload payment proof
        </button>

        <p class="text-xs text-gray-400 mt-3">
            This window will close in <span id="countdown">10</span> seconds.
        </p>
    </div>
</div>

<style>
    @keyframes bounce-in {
        0% { transform: scale(0.96); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-bounce-in { animation: bounce-in 0.18s ease-out; }
</style>

<script>
    function closePopup() {
        const popup = document.getElementById('orderCreatedPopup');
        if (popup) popup.style.display = 'none';
        document.getElementById('paymentUploadForm')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    let timeLeft = 10;
    const countdownEl = document.getElementById('countdown');

    const countdownTimer = setInterval(function() {
        timeLeft--;
        if (countdownEl) countdownEl.textContent = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(countdownTimer);
            closePopup();
        }
    }, 1000);
</script>
@endif

{{-- Script Preview Image & Copy --}}
<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            if (input.files[0].size > 2 * 1024 * 1024) {
                alert('Maximum file size is 2MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('Copied to clipboard.');
        }).catch(function() {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast('Copied to clipboard.');
        });
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-xl shadow-lg z-50 animate-fade-in text-sm';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 2000);
    }
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.18s ease-out; }
</style>
@endsection
