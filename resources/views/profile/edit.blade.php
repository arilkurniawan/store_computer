@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>
            <p class="mt-2 text-gray-600">Kelola informasi profil akun Anda</p>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Profile Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Profil</h2>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror"
                            required
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon
                        </label>
                        <input 
                            type="tel" 
                            name="phone" 
                            id="phone"
                            value="{{ old('phone', $user->phone ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            placeholder="08xxxxxxxxxx"
                        >
                    </div>

                    <div class="flex justify-end">
                        <button 
                            type="submit"
                            class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-6 rounded-lg transition"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Back Link --}}
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-amber-600 transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
