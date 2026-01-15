@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="checkoutForm()">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <p class="text-sm text-gray-500">
                        <a href="{{ url('/') }}" class="hover:text-amber-600 transition">Home</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-700">Checkout</span>
                    </p>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight text-gray-900">
                        Checkout
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Complete your shipping details and review your order before confirming.
                    </p>
                </div>

                <a href="{{ route('cart.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 hover:text-amber-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Cart
                </a>
            </div>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 shadow-sm">
                <div class="flex gap-3">
                    <div class="mt-0.5">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5zM10 14.75a1 1 0 100-2 1 1 0 000 2z"
                                  clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-red-700">Please fix the following issues</p>
                        <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 shadow-sm">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" class="grid lg:grid-cols-12 gap-6">
            @csrf

            {{-- Left: Shipping Form --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200/70 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Shipping Details</h2>
                        <p class="text-sm text-gray-600 mt-1">Use an active address to avoid delivery issues.</p>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Name <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_name"
                                       value="{{ old('shipping_name', auth()->user()->name) }}" required
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                                              focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}" required
                                       placeholder="08xxxxxxxxxx"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                                              focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition" />
                                <p class="mt-1 text-xs text-gray-500">Used by the courier for delivery updates.</p>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Province <span class="text-red-500">*</span></label>
                                <select name="shipping_province" required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                                               focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition">
                                    <option value="">Select a province</option>
                                    <option value="Sumatera Barat" {{ old('shipping_province') == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                                    <option value="DKI Jakarta" {{ old('shipping_province') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                    <option value="Jawa Barat" {{ old('shipping_province') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                    <option value="Jawa Tengah" {{ old('shipping_province') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                    <option value="Jawa Timur" {{ old('shipping_province') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                    <option value="Banten" {{ old('shipping_province') == 'Banten' ? 'selected' : '' }}>Banten</option>
                                    <option value="Sumatera Utara" {{ old('shipping_province') == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                                    <option value="Riau" {{ old('shipping_province') == 'Riau' ? 'selected' : '' }}>Riau</option>
                                    <option value="Lainnya" {{ old('shipping_province') == 'Lainnya' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                       placeholder="e.g., Bukittinggi"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                                              focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required
                                       placeholder="e.g., 26111"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                                              focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition" />
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                                <input type="text" name="notes" value="{{ old('notes') }}"
                                       placeholder="e.g., Blue house near the mosque"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                                              focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Address <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" rows="4" required
                                      placeholder="Street name, house number, district, landmark"
                                      class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                                             focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition">{{ old('shipping_address') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Small trust card --}}
                <div class="mt-6 rounded-2xl bg-white/70 ring-1 ring-gray-200 p-5 shadow-sm">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21a7 7 0 10-14 0h14z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Your information is protected</p>
                            <p class="text-xs text-gray-600 mt-1">We only use your details to process your order and delivery.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200/70 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                            <p class="text-sm text-gray-600 mt-1">Review your items and total before confirming.</p>
                        </div>

                        <div class="p-6">
                            {{-- Items --}}
                            <div class="space-y-4">
                                @foreach($carts as $cart)
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-3">
                                            {{-- Optional thumbnail (jika field image ada) --}}
                                            @if(!empty($cart->product->image))
                                                <img
                                                    src="{{ asset('storage/' . $cart->product->image) }}"
                                                    alt="{{ $cart->product->name }}"
                                                    class="w-12 h-12 rounded-xl object-cover ring-1 ring-gray-200"
                                                    loading="lazy"
                                                    onerror="this.style.display='none'"
                                                />
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-gray-100 ring-1 ring-gray-200 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-2 6H8l-2-6m16 0H4" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 leading-snug">
                                                    {{ $cart->product->name }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $cart->quantity }} × Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>

                                        <p class="text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($cart->quantity * $cart->product->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="my-6 border-gray-100">

                            {{-- Promo --}}
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Promo Code</label>
                                    <button type="button"
                                            class="text-xs text-gray-500 hover:text-amber-600 transition"
                                            @click="showPromoHints = !showPromoHints">
                                        See examples
                                    </button>
                                </div>

                                <div class="flex gap-2">
                                    <div class="flex-1 relative">
                                        <input type="text"
                                               name="promo_code"
                                               x-model="promoCode"
                                               @input="resetPromo()"
                                               placeholder="Enter promo code"
                                               class="w-full rounded-xl border px-4 py-2.5 text-sm uppercase tracking-wide
                                                      focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition pr-10
                                                      border-gray-200"
                                               :class="{
                                                   'border-green-500 bg-green-50': promoValid === true,
                                                   'border-red-500 bg-red-50': promoValid === false
                                               }"
                                               :readonly="promoApplied">

                                        {{-- Status icon --}}
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                            <svg x-show="promoLoading" class="w-5 h-5 animate-spin text-amber-500" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>

                                            <svg x-show="promoValid === true && !promoLoading" class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>

                                            <svg x-show="promoValid === false && !promoLoading" class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <button type="button"
                                            x-show="!promoApplied"
                                            @click="validatePromo()"
                                            :disabled="promoLoading || promoCode.length < 3"
                                            class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white
                                                   shadow-sm hover:bg-orange-600 hover:shadow-md active:scale-[0.99] transition
                                                   disabled:bg-gray-300 disabled:text-white/80 disabled:cursor-not-allowed">
                                        <span x-show="!promoLoading">Apply</span>
                                        <span x-show="promoLoading">Checking...</span>
                                    </button>

                                    <button type="button"
                                            x-show="promoApplied"
                                            @click="removePromo()"
                                            class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-700
                                                   ring-1 ring-gray-200 hover:bg-gray-50 hover:text-red-600 transition">
                                        Remove
                                    </button>
                                </div>

                                <div x-show="promoMessage" x-transition class="text-sm"
                                     :class="promoValid ? 'text-green-700' : 'text-red-700'">
                                    <span x-text="promoMessage"></span>
                                </div>

                                <div x-show="showPromoHints" x-transition
                                     class="rounded-xl bg-gray-50 ring-1 ring-gray-200 p-3 text-xs text-gray-600">
                                    <span class="font-medium text-gray-700">Examples:</span>
                                    <button type="button" class="ml-2 font-mono px-2 py-1 rounded-lg bg-white ring-1 ring-gray-200 hover:ring-amber-300 hover:bg-amber-50 transition"
                                            @click="promoCode = 'WELCOME10'">WELCOME10</button>
                                    <button type="button" class="ml-2 font-mono px-2 py-1 rounded-lg bg-white ring-1 ring-gray-200 hover:ring-amber-300 hover:bg-amber-50 transition"
                                            @click="promoCode = 'GAMING'">GAMING</button>
                                    <button type="button" class="ml-2 font-mono px-2 py-1 rounded-lg bg-white ring-1 ring-gray-200 hover:ring-amber-300 hover:bg-amber-50 transition"
                                            @click="promoCode = 'BUKITTINGGI'">BUKITTINGGI</button>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-100">

                            {{-- Price summary --}}
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between text-gray-700">
                                    <span>Subtotal</span>
                                    <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between text-green-700" x-show="discount > 0" x-transition>
                                    <span>Discount (<span class="font-mono" x-text="promoCode"></span>)</span>
                                    <span class="font-semibold">- Rp <span x-text="formatNumber(discount)"></span></span>
                                </div>

                                <div class="flex justify-between text-gray-700">
                                    <span>Shipping</span>
                                    <span class="font-medium text-green-700">Free</span>
                                </div>

                                <hr class="my-3 border-gray-100">

                                <div class="flex justify-between items-center">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-semibold text-amber-600">
                                        Rp <span x-text="formatNumber(total)"></span>
                                    </span>
                                </div>

                                <div x-show="discount > 0" x-transition
                                     class="mt-3 rounded-xl bg-green-50 ring-1 ring-green-200 p-3">
                                    <p class="text-sm font-semibold text-green-800">
                                        You saved Rp <span x-text="formatNumber(discount)"></span> with this promo.
                                    </p>
                                    <p class="text-xs text-green-700 mt-1">Always nice to pay less.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-orange-500 px-6 py-4
                                   text-base font-semibold text-white shadow-sm hover:bg-orange-600 hover:shadow-md
                                   active:scale-[0.99] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Confirm Order
                    </button>

                    <p class="text-center text-xs text-gray-500">
                        By placing your order, you agree to our Terms &amp; Conditions.
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function checkoutForm() {
    return {
        // UI state
        showPromoHints: false,

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

        async validatePromo() {
            if (this.promoCode.length < 3) {
                this.promoMessage = 'Promo code must be at least 3 characters.';
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
                this.promoMessage = 'Something went wrong. Please try again.';
            } finally {
                this.promoLoading = false;
            }
        },

        removePromo() {
            this.promoCode = '';
            this.promoValid = null;
            this.promoApplied = false;
            this.promoMessage = '';
            this.discount = 0;
            this.total = this.subtotal;
        },

        resetPromo() {
            if (this.promoApplied) return;
            this.promoValid = null;
            this.promoMessage = '';
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }
}
</script>
@endpush
@endsection
