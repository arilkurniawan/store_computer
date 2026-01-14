@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="checkoutForm()">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 p-4 rounded">
                <p class="font-bold text-red-700">❌ Ada kesalahan:</p>
                <ul class="list-disc list-inside text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-700">❌ {{ session('error') }}</p>
            </div>
        @endif

        <h1 class="text-2xl font-bold mb-6">🛒 Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                {{-- ================================ --}}
                {{-- Form Pengiriman --}}
                {{-- ================================ --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold mb-4">📍 Shipping Address</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Recipient Name *</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Phone Number *</label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" required
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Province *</label>
                            <select name="shipping_province" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="">-- Select Province --</option>
                                <option value="Sumatera Barat" {{ old('shipping_province') == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                                <option value="DKI Jakarta" {{ old('shipping_province') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                <option value="Jawa Barat" {{ old('shipping_province') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                <option value="Jawa Tengah" {{ old('shipping_province') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                <option value="Jawa Timur" {{ old('shipping_province') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                <option value="Banten" {{ old('shipping_province') == 'Banten' ? 'selected' : '' }}>Banten</option>
                                <option value="Sumatera Utara" {{ old('shipping_province') == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                                <option value="Riau" {{ old('shipping_province') == 'Riau' ? 'selected' : '' }}>Riau</option>
                                <option value="Lainnya" {{ old('shipping_province') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">City *</label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                   placeholder="Example: Bukittinggi"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Post Code *</label>
                            <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required
                                   placeholder="Example: 26111"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Full Address *</label>
                            <textarea name="shipping_address" rows="3" required
                                      placeholder="Street name, house number, district, city"
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('shipping_address') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Notes (Opsional)</label>
                            <textarea name="notes" rows="2"
                                      placeholder="e.g. Blue house near the mosque"
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ================================ --}}
                {{-- Ringkasan Pesanan --}}
                {{-- ================================ --}}
                <div>
                    <div class="bg-white rounded-lg shadow p-6 mb-4">
                        <h2 class="text-lg font-bold mb-4">📦 Order Summary</h2>

                        {{-- Cart Items --}}
                        <div class="space-y-3 mb-4">
                            @foreach($carts as $cart)
                                <div class="flex justify-between items-center py-2 border-b">
                                    <div>
                                        <p class="font-medium">{{ $cart->product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $cart->quantity }} x Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-medium">Rp {{ number_format($cart->quantity * $cart->product->price, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- ================================ --}}
                        {{-- PROMO CODE WITH AJAX VALIDATION --}}
                        {{-- ================================ --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">🎟️ Promo Code</label>
                            <div class="flex gap-2">
                                <div class="flex-1 relative">
                                    <input type="text" 
                                           name="promo_code" 
                                           x-model="promoCode"
                                           @input="resetPromo()"
                                           placeholder="Enter promo code"
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 uppercase"
                                           :class="{ 
                                               'border-green-500 bg-green-50': promoValid === true,
                                               'border-red-500 bg-red-50': promoValid === false
                                           }"
                                           :disabled="promoApplied">
                                    
                                    {{-- Status Icon --}}
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                        {{-- Loading --}}
                                        <svg x-show="promoLoading" class="w-5 h-5 animate-spin text-amber-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{-- Success --}}
                                        <svg x-show="promoValid === true && !promoLoading" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{-- Error --}}
                                        <svg x-show="promoValid === false && !promoLoading" class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                {{-- Apply/Remove Button --}}
                                <button type="button" 
                                        x-show="!promoApplied"
                                        @click="validatePromo()"
                                        :disabled="promoLoading || promoCode.length < 3"
                                        class="bg-amber-500 hover:bg-amber-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg transition font-medium">
                                    <span x-show="!promoLoading">Apply</span>
                                    <span x-show="promoLoading">...</span>
                                </button>
                                
                                <button type="button" 
                                        x-show="promoApplied"
                                        @click="removePromo()"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition font-medium">
                                    Delete
                                </button>
                            </div>
                            
                            {{-- Promo Message --}}
                            <div x-show="promoMessage" 
                                 x-transition
                                 class="mt-2 text-sm"
                                 :class="promoValid ? 'text-green-600' : 'text-red-600'">
                                <span x-text="promoMessage"></span>
                            </div>
                            
                            {{-- Available Promo Codes Hint --}}
                            <p class="text-xs text-gray-500 mt-2">
                                💡 Coba: <span class="font-mono bg-gray-100 px-1 rounded cursor-pointer hover:bg-amber-100" @click="promoCode = 'WELCOME10'">WELCOME10</span>, 
                                <span class="font-mono bg-gray-100 px-1 rounded cursor-pointer hover:bg-amber-100" @click="promoCode = 'GAMING'">GAMING</span>, 
                                <span class="font-mono bg-gray-100 px-1 rounded cursor-pointer hover:bg-amber-100" @click="promoCode = 'BUKITTINGGI'">BUKITTINGGI</span>
                            </p>
                        </div>

                        <hr class="my-4">

                        {{-- ================================ --}}
                        {{-- PRICE SUMMARY --}}
                        {{-- ================================ --}}
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            
                            {{-- Discount Row (Dynamic) --}}
                            <div class="flex justify-between text-green-600" x-show="discount > 0" x-transition>
                                <span>Discount (<span x-text="promoCode"></span>)</span>
                                <span>- Rp <span x-text="formatNumber(discount)"></span></span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-green-600">FREE</span>
                            </div>
                            
                            <hr>
                            
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-amber-600">Rp <span x-text="formatNumber(total)"></span></span>
                            </div>
                            
                            {{-- Savings Info --}}
                            <div x-show="discount > 0" x-transition 
                                 class="bg-green-50 border border-green-200 rounded-lg p-3 mt-2">
                                <p class="text-green-700 text-sm font-medium">
                                    🎉 Yay! You saved Rp <span x-text="formatNumber(discount)"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-lg text-lg transition flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        CONFIRM ORDER
                    </button>

                    <p class="text-center text-sm text-gray-500 mt-3">
                        By placing your order, you agree to our Terms & Conditions.
                    </p>
                    
                    {{-- Back to Cart --}}
                    <a href="{{ route('cart.index') }}" class="block text-center text-gray-600 hover:text-amber-600 mt-4">
                        ← Back to Cart
                    </a>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- ================================ --}}
{{-- ALPINE.JS CHECKOUT COMPONENT --}}
{{-- ================================ --}}
@push('scripts')
<script>
function checkoutForm() {
    return {
        // Promo state
        promoCode: '{{ old('promo_code', '') }}',
        promoLoading: false,
        promoValid: null,
        promoMessage: '',
        promoApplied: false,
        
        // Price state
        subtotal: {{ $subtotal }},
        discount: 0,
        total: {{ $subtotal }},
        
        // Validate promo code via AJAX
        async validatePromo() {
            if (this.promoCode.length < 3) {
                this.promoMessage = 'Kode promo minimal 3 karakter';
                this.promoValid = false;
                return;
            }
            
            this.promoLoading = true;
            this.promoMessage = '';
            
            try {
                const response = await fetch('{{ route('promo.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        code: this.promoCode.toUpperCase(),
                        subtotal: this.subtotal
                    })
                });
                
                const data = await response.json();
                
                if (data.valid) {
                    this.promoValid = true;
                    this.promoApplied = true;
                    this.promoMessage = data.message;
                    this.discount = data.promo.discount;
                    this.total = data.total;
                    this.promoCode = data.promo.code;
                } else {
                    this.promoValid = false;
                    this.promoMessage = data.message;
                    this.discount = 0;
                    this.total = this.subtotal;
                }
            } catch (error) {
                console.error('Promo validation error:', error);
                this.promoValid = false;
                this.promoMessage = 'Terjadi kesalahan. Silakan coba lagi.';
            } finally {
                this.promoLoading = false;
            }
        },
        
        // Remove applied promo
        removePromo() {
            this.promoCode = '';
            this.promoValid = null;
            this.promoApplied = false;
            this.promoMessage = '';
            this.discount = 0;
            this.total = this.subtotal;
        },
        
        // Reset promo state when typing
        resetPromo() {
            if (this.promoApplied) return;
            this.promoValid = null;
            this.promoMessage = '';
        },
        
        // Format number to Indonesian format
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }
}
</script>
@endpush
@endsection