<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preload" as="image" href="{{ asset('img/logo.png') }}" fetchpriority="high">
    <title>@yield('title', 'Rill Store')</title>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>



    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Custom Tailwind Config --}}
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
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
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>


    
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
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    {{-- FOOTER --}}
   @include('layouts.footer')

    @stack('scripts')

    {{-- AJAK add to cart --}}
    <script>
    (function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function setCartCount(count) {
        const badge = document.getElementById('cart-count-badge');
        if (!badge) return;

        const n = parseInt(count, 10) || 0;
        if (n <= 0) {
        badge.classList.add('hidden');
        badge.textContent = '';
        } else {
        badge.classList.remove('hidden');
        badge.textContent = String(n);
        }
    }

    function toast(message, type = 'success') {
        const el = document.createElement('div');
        el.className =
        'fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow text-white text-sm ' +
        (type === 'error' ? 'bg-red-600' : 'bg-green-600');
        el.textContent = message;

        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2500);
    }

    async function submitAjaxCart(form) {
        const btn = form.querySelector('button[type="submit"]');
        const oldHtml = btn ? btn.innerHTML : null;

        if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        btn.innerHTML = '...';
        }

        try {
        const res = await fetch(form.action, {
            method: (form.getAttribute('method') || 'POST').toUpperCase(),
            headers: {
            'X-Requested-With': 'XMLHttpRequest', // penting supaya $request->ajax() true
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            },
            body: new FormData(form),
            credentials: 'same-origin',
        });

        // kalau session/CSRF expired atau belum login
        if (res.status === 401 || res.status === 419) {
            window.location.href = '/login';
            return;
        }

        const data = await res.json().catch(() => null);

        if (!res.ok || !data || data.success === false) {
            toast(data?.message || 'Gagal menambahkan ke keranjang.', 'error');
            return;
        }

        setCartCount(data.cart_count);
        } catch (e) {
        toast('Koneksi bermasalah. Coba lagi.', 'error');
        } finally {
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = oldHtml ?? 'Tambah ke Keranjang';
        }
        }
    }

    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form && form.matches('form[data-ajax-cart]')) {
        e.preventDefault();
        submitAjaxCart(form);
        }
    });
    })();
    </script>

</body>
</html>
