<footer class="bg-gray-800 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Brand --}}
            <div>
                <h3 class="text-xl font-bold mb-4">Keripik Sanjai</h3>
                <p class="text-gray-400 text-sm">
                    Keripik singkong khas Bukittinggi dengan cita rasa autentik dan kualitas terbaik.
                </p>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="font-semibold mb-4">Menu</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition">Beranda</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-amber-400 transition">Produk</a></li>
                    @auth
                        <li><a href="{{ route('orders.index') }}" class="hover:text-amber-400 transition">Pesanan Saya</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="font-semibold mb-4">Kontak</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li>📍 Bukittinggi, Sumatera Barat</li>
                    <li>📞 0869-6969-6969</li>
                    <li>✉️ sanjai@rizky.com</li>
                </ul>
            </div>
        </div>

    </div>
</footer>
