@extends('layouts.app')

@section('judul', $bread->name . " — L'Artisan Bakery")

@section('content')
    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400">
        <a href="{{ url('/') }}" class="transition hover:text-amber-700">Beranda</a>
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('orders.index') }}" class="transition hover:text-amber-700">Katalog</a>
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-600">{{ $bread->name }}</span>
    </nav>

    <div class="grid gap-8 lg:grid-cols-2">
        {{-- Product Image --}}
        <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
            <div class="relative overflow-hidden" style="min-height: 380px; background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                <img
                    src="{{ $bread->image_path ? asset('storage/' . $bread->image_path) : 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80' }}"
                    alt="{{ $bread->name }}"
                    class="h-full w-full object-cover transition duration-700 hover:scale-105"
                    style="min-height: 380px;"
                >
                @if($bread->stock <= 0)
                    <div class="absolute inset-0 flex items-center justify-center" style="background: rgba(0,0,0,0.5);">
                        <span class="rounded-2xl bg-red-600 px-6 py-3 text-base font-black text-white">Stok Habis</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Product Info --}}
        <div class="space-y-5">
            {{-- Category & Title --}}
            <div>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide transition hover:opacity-80" style="background: #fef3e2; color: #b45309;">
                    {{ $bread->category->name ?? 'Lainnya' }}
                </a>
                <h1 class="mt-3 font-playfair text-3xl font-bold text-gray-900 sm:text-4xl" style="font-family: 'Playfair Display', serif;">
                    {{ $bread->name }}
                </h1>
            </div>

            {{-- Description --}}
            <p class="text-base leading-7 text-gray-600">
                {{ $bread->description ?? 'Roti lezat ini belum memiliki deskripsi tambahan. Tapi kami jamin rasanya tidak akan mengecewakan!' }}
            </p>

            {{-- Price & Stock --}}
            <div class="flex flex-wrap items-center gap-4">
                <span class="font-playfair text-4xl font-bold text-amber-700" style="font-family: 'Playfair Display', serif;">
                    Rp {{ number_format($bread->price, 0, ',', '.') }}
                </span>
                @if($bread->stock > 0)
                    <span class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-semibold" style="background: #d1fae5; color: #065f46;">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Stok tersedia: {{ $bread->stock }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-semibold" style="background: #fee2e2; color: #991b1b;">
                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                        Stok habis
                    </span>
                @endif
            </div>

            {{-- Info Box --}}
            <div class="rounded-2xl border border-amber-200 p-4" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                <div class="flex items-start gap-3">
                    <div class="text-2xl">🍞</div>
                    <div>
                        <p class="text-sm font-bold text-amber-900">Roti Segar Setiap Hari</p>
                        <p class="mt-1 text-xs text-amber-700">Dipanggang dari bahan pilihan dengan standar kualitas tertinggi. Gunakan kontrol di bawah untuk menambahkan ke keranjang.</p>
                    </div>
                </div>
            </div>

            {{-- Quick Add --}}
            <livewire:bread-quick-add :bread="$bread" />

            {{-- Back Button --}}
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center gap-2 rounded-2xl border border-amber-200 bg-amber-50 px-6 py-3 text-sm font-semibold text-amber-800 transition hover:bg-amber-100">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Katalog
            </a>
        </div>
    </div>
@endsection