@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
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
                {{-- Form Pengiriman --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold mb-4">📍 Alamat Pengiriman</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Penerima *</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">No. Telepon *</label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" required
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Provinsi *</label>
                            <select name="shipping_province" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="">-- Pilih Provinsi --</option>
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

                        {{-- ⭐ TAMBAHAN: Kota --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Kota/Kabupaten *</label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                   placeholder="Contoh: Bukittinggi"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        {{-- ⭐ TAMBAHAN: Kode Pos --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Kode Pos *</label>
                            <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required
                                   placeholder="Contoh: 26111"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Alamat Lengkap *</label>
                            <textarea name="shipping_address" rows="3" required
                                      placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan"
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('shipping_address') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Catatan (Opsional)</label>
                            <textarea name="notes" rows="2"
                                      placeholder="Contoh: Rumah warna biru, dekat masjid"
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan Pesanan --}}
                <div>
                    <div class="bg-white rounded-lg shadow p-6 mb-4">
                        <h2 class="text-lg font-bold mb-4">📦 Ringkasan Pesanan</h2>

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

                        {{-- Promo Code --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">🎟️ Kode Promo</label>
                            <input type="text" name="promo_code" value="{{ old('promo_code') }}"
                                   placeholder="Masukkan kode promo"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 uppercase">
                            <p class="text-xs text-gray-500 mt-1">Coba: WELCOME10, SANJAI20, BUKITTINGGI</p>
                        </div>

                        <hr class="my-4">

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="text-green-600">GRATIS</span>
                            </div>
                            <hr>
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-amber-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-lg text-lg transition">
                        🛒 BUAT PESANAN
                    </button>

                    <p class="text-center text-sm text-gray-500 mt-3">
                        Dengan memesan, Anda menyetujui syarat & ketentuan kami
                    </p>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
