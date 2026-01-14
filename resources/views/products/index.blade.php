@extends('layouts.app')

@section('title', isset($activeCategory) ? $activeCategory->name . ' - Rill Store' : 'Products - Rill Store')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- Sidebar (Sticky) --}}
            <aside class="w-full lg:w-72 flex-shrink-0 space-y-6 lg:sticky lg:top-24 h-fit">

                {{-- ✅ Title moved here (sticky with sidebar) --}}
                <div class="pt-1">
                    <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-orange-600">
                        {{ isset($activeCategory) ? $activeCategory->name : 'ALL PRODUCTS' }}
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ isset($activeCategory) ? 'Browse items in this category.' : 'Explore our latest items and deals.' }}
                    </p>
                </div>

                {{-- Category --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Category</h3>

                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('products.index') }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg transition
                               {{ !isset($activeCategory) ? 'bg-orange-50 text-orange-700 border border-orange-100' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="font-medium">All products</span>
                                <span class="text-xs {{ !isset($activeCategory) ? 'text-orange-700/70' : 'text-gray-400' }}">
                                    {{ $products->total() ?? 0 }}
                                </span>
                            </a>
                        </li>

                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-lg transition
                                   {{ (isset($activeCategory) && $activeCategory->id == $category->id)
                                        ? 'bg-orange-50 text-orange-700 border border-orange-100'
                                        : 'text-gray-600 hover:bg-gray-50' }}">
                                    <span class="font-medium">{{ $category->name }}</span>
                                    <span class="text-xs {{ (isset($activeCategory) && $activeCategory->id == $category->id) ? 'text-orange-700/70' : 'text-gray-400' }}">
                                        {{ $category->products_count ?? 0 }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Sort --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Sort</h3>

                    <form action="{{ route('products.index') }}" method="GET">
                        @if(isset($activeCategory))
                            <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <select name="sort" onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm
                                       focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Newest</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A–Z)</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price (Low → High)</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price (High → Low)</option>
                        </select>
                    </form>
                </div>
            </aside>

            {{-- Main --}}
            <main class="flex-1 min-w-0">

                {{-- Search bar (now aligned with title) --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
                    <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                        @if(isset($activeCategory))
                            <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <div class="flex-1 relative">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search products..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm
                                          focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <button type="submit"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
                            Search
                        </button>

                        @if(request('search'))
                            <a href="{{ route('products.index', isset($activeCategory) ? ['category' => $activeCategory->slug] : []) }}"
                               class="px-4 py-2 rounded-lg text-sm font-semibold border border-orange-200 text-orange-700 hover:bg-orange-50 transition">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Active filters --}}
                @if(request('search') || request('sort'))
                    <div class="mb-5 flex items-center gap-2 text-xs text-gray-600 flex-wrap">
                        @if(request('search'))
                            <span class="px-3 py-1 rounded-full bg-white border border-gray-200">
                                “{{ request('search') }}”
                            </span>
                        @endif
                        @if(request('sort'))
                            <span class="px-3 py-1 rounded-full bg-white border border-gray-200">
                                @switch(request('sort'))
                                    @case('price_low') Price: Low → High @break
                                    @case('price_high') Price: High → Low @break
                                    @case('name') Name: A–Z @break
                                    @default Newest
                                @endswitch
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Grid --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition flex flex-col">

                                <a href="{{ route('products.show', $product->slug) }}" class="block">
                                    <div class="relative bg-gray-100">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-52 object-cover"
                                                 loading="lazy"
                                                 decoding="async">
                                        @else
                                            <div class="w-full h-52 flex items-center justify-center">
                                                <div class="w-14 h-14 rounded-full bg-white/70 border border-gray-200 flex items-center justify-center text-gray-400">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M3 7h18M3 7l2 14h14l2-14M9 7V5a3 3 0 116 0v2"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="absolute top-3 left-3 flex gap-2">
                                            @if($product->is_recommended)
                                                <span class="text-[11px] font-semibold px-2 py-1 rounded-full bg-white/90 border border-orange-200 text-orange-700">
                                                    Recommended
                                                </span>
                                            @endif

                                            @if($product->stock > 0 && $product->stock <= 5)
                                                <span class="text-[11px] font-semibold px-2 py-1 rounded-full bg-white/90 border border-gray-200 text-gray-700">
                                                    Low stock
                                                </span>
                                            @endif
                                        </div>

                                        @if($product->stock <= 0)
                                            <div class="absolute inset-0 bg-black/35 flex items-center justify-center">
                                                <span class="px-3 py-1.5 rounded-full bg-white text-gray-900 text-sm font-semibold">
                                                    Out of stock
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                <div class="p-4 flex flex-col flex-1">
                                    <p class="text-xs text-gray-500">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </p>

                                    <a href="{{ route('products.show', $product->slug) }}" class="mt-1">
                                        <h3 class="font-semibold text-gray-900 line-clamp-2 hover:text-orange-600 transition">
                                            {{ $product->name }}
                                        </h3>
                                    </a>

                                    <div class="mt-3 flex items-center justify-between">
                                        <p class="text-lg font-semibold text-orange-600">
                                            {{ $product->formatted_price ?? ('Rp ' . number_format($product->price, 0, ',', '.')) }}
                                        </p>

                                        @if($product->stock > 0)
                                            <span class="text-xs text-green-700 bg-green-50 border border-green-100 px-2 py-1 rounded-full">
                                                In stock
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-700 bg-gray-100 border border-gray-200 px-2 py-1 rounded-full">
                                                Unavailable
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-4">
                                        @auth
                                            @if($product->stock > 0)
                                                <form action="{{ route('cart.store') }}" method="POST" data-ajax-cart>
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit"
                                                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg transition">
                                                        Add to cart
                                                    </button>
                                                </form>
                                            @else
                                                <button disabled
                                                        class="w-full bg-gray-200 text-gray-500 font-semibold py-2.5 rounded-lg cursor-not-allowed">
                                                    Unavailable
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="block w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg transition text-center">
                                                Login to buy
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                        <h3 class="text-lg font-semibold text-gray-900">No products found</h3>
                        <p class="text-sm text-gray-500 mt-2">
                            @if(request('search'))
                                No results for “{{ request('search') }}”.
                            @else
                                There are no products in this category yet.
                            @endif
                        </p>

                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center justify-center mt-6 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                            View all products
                        </a>
                    </div>
                @endif

            </main>
        </div>

    </div>
</div>
@endsection
