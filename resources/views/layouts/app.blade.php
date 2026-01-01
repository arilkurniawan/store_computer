<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Keripik Sanjai')</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Custom Tailwind Config --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
    
    {{-- Custom Styles --}}
    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- Navbar --}}
    @include('layouts.navigation')

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 w-full">
        @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" 
                 x-data="{ show: true }" 
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)">
                <div class="flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-700 hover:text-green-900">&times;</button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"
                 x-data="{ show: true }" 
                 x-show="show">
                <div class="flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-700 hover:text-red-900">&times;</button>
                </div>
            </div>
        @endif
    </div>

    {{-- ======================= --}}
    {{-- MAIN CONTENT AREA --}}
    {{-- ======================= --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">🥔 Keripik Sanjai</h3>
                    <p class="text-gray-400">
                        Oleh-oleh khas Bukittinggi, Sumatera Barat. 
                        Dibuat dari singkong pilihan dengan resep turun temurun.
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <p class="text-gray-400">📍 Bukittinggi, Sumatera Barat</p>
                    <p class="text-gray-400">📞 0812-3456-7890</p>
                    <p class="text-gray-400">✉️ info@keripik-sanjai.com</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Link</h3>
                    <ul class="text-gray-400 space-y-2">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition">Produk</a></li>
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        @auth
                            <li><a href="{{ route('orders.index') }}" class="hover:text-white transition">Pesanan Saya</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Keripik Sanjai. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
