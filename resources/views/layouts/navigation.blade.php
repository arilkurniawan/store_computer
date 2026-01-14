<div x-data="searchComponent()">
<nav class="bg-white border-b border-[#bbbab7] fixed top-0 left-0 right-0 py-2 z-50">
    <div class="max-w-9xl mx-auto px-3">
        <div class="flex items-center justify-between h-16 ">
            {{-- Logo --}}
            <a href="/" class="flex-shrink-0">
                <img src="{{ asset('img/logo.png') }}" alt="logo" width="110" height="40" class="h-10 w-auto object-contain" loading="eager" decoding="async"
/>

            </a>

            {{-- Search Bar (BARU) --}}
                <div class="hidden md:block flex-1 max-w-md mx-6 relative">
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="query"
                            @input.debounce.300ms="search()"
                            @focus="showResults = true"
                            @keydown.escape="closeSearch()"
                            placeholder="Search Products..." 
                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        {{-- Search Icon / Loading --}}
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        {{-- Clear Button --}}
                        <button 
                            x-show="query.length > 0" 
                            @click="clearSearch()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Search Results Dropdown --}}
                    <div 
                        x-show="showResults && query.length >= 2" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        @click.away="showResults = false"
                        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto z-50">
                        {{-- Loading State --}}
                        <div x-show="loading" class="p-4 text-center text-gray-500">
                            <svg class="w-6 h-6 animate-spin mx-auto mb-2 text-orange-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm">Mencari...</span>
                        </div>

                        {{-- Results --}}
                        <div x-show="!loading">
                            {{-- Products Found --}}
                            <template x-if="products.length > 0">
                                <div>
                                    <div class="px-4 py-2 bg-gray-50 border-b">
                                        <span class="text-sm text-gray-600">
                                            Ditemukan <span x-text="products.length" class="font-semibold text-orange-600"></span> produk
                                        </span>
                                    </div>
                                    <template x-for="product in products" :key="product.id">
                                        <a :href="product.url" class="flex items-center gap-3 p-3 hover:bg-orange-50 border-b last:border-b-0 transition">
                                            {{-- Product Image --}}
                                            <div class="w-12 h-12 flex-shrink-0 rounded-lg bg-gray-100">
                                                <template x-if="product.image">
                                                    <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!product.image">
                                                    <div class="w-full h-full flex items-center justify-center text-xl bg-orange-100">🥔</div>
                                                </template>
                                            </div>
                                            {{-- Product Info --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-gray-800 truncate" x-text="product.name"></p>
                                                <p class="text-xs text-gray-500" x-text="product.category"></p>
                                            </div>
                                            {{-- Price & Stock --}}
                                            <div class="text-right flex-shrink-0">
                                                <p class="font-bold text-orange-600" x-text="product.price_formatted"></p>
                                                <p class="text-xs" :class="product.stock > 0 ? 'text-green-600' : 'text-red-500'" x-text="product.stock > 0 ? 'Stok: ' + product.stock : 'Habis'"></p>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </template>

                            {{-- No Results --}}
                            <template x-if="products.length === 0 && !loading && searched">
                                <div class="p-6 text-center">
                                    <div class="text-4xl mb-2">🔍</div>
                                    <p class="text-gray-600 font-medium">Produk tidak ditemukan</p>
                                    <p class="text-sm text-gray-400 mt-1">Coba kata kunci lain</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            {{-- Desktop Menu --}}
            <div class="md:flex items-center space-x-6">
                <a href="/" class="text-gray-700 hover:text-orange-600 font-medium {{ request()->is('/') ? 'text-orange-600' : '' }}">
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-orange-600 font-medium {{ request()->routeIs('products.*') ? 'text-orange-600' : '' }}">
                    Product
                </a>
                
                @auth
                    
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-orange-600 font-medium {{ request()->routeIs('orders.*') ? 'text-orange-600' : '' }}">
                        Order
                    </a>
                    
                    {{-- Keranjang dengan Badge --}}
                    <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-orange-600 font-medium">
                        🛒
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                        @endphp

                        <span id="cart-count-badge" class="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">
                            {{ $cartCount > 0 ? $cartCount : '' }}
                        </span>
                    </a>

                    <span class="text-gray-400">|</span>
                    <span class="text-gray-600">{{ Auth::user()->name }}</span>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-600 font-medium">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600 font-medium">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                        Daftar
                    </a>
                @endauth
            </div>
    </div>
</nav>
</div>
{{-- Mobile Menu Toggle Script --}}
<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

<script>
function searchComponent() {
    return {
        query: '',
        products: [],
        loading: false,
        showResults: false,
        searched: false,

        async search() {
            if (this.query.length < 2) {
                this.products = [];
                this.searched = false;
                return;
            }

            this.loading = true;
            this.showResults = true;

            try {
                const response = await fetch(`/api/products/search?q=${encodeURIComponent(this.query)}`);
                const data = await response.json();
                
                if (data.success) {
                    this.products = data.products;
                }
                this.searched = true;
            } catch (error) {
                console.error('Search error:', error);
                this.products = [];
            } finally {
                this.loading = false;
            }
        },

        clearSearch() {
            this.query = '';
            this.products = [];
            this.showResults = false;
            this.searched = false;
        },

        closeSearch() {
            this.showResults = false;
        }
    }
}
</script>
