@extends('layouts.app')

@section('judul', "Katalog Roti — L'Artisan Bakery")

@section('content')
    {{-- Breadcrumb & Back button --}}
    <div class="mb-6 flex items-center gap-3">
        <button onclick="history.back()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-amber-200 bg-white text-amber-800 shadow-sm transition hover:bg-amber-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
            <a href="{{ url('/') }}" class="transition hover:text-amber-700">Beranda</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-800">Katalog Roti</span>
        </nav>
    </div>

    {{-- Hero Banner --}}
    <section class="relative overflow-hidden rounded-3xl p-8 sm:p-12" style="background: linear-gradient(135deg, #451a03 0%, #78350f 45%, #b45309 100%); min-height: 200px;">
        <div class="absolute inset-0 pointer-events-none opacity-10" style="background-image: url('https://images.unsplash.com/photo-1568254183919-78a4f43a2877?auto=format&fit=crop&w=1200&q=40'); background-size: cover; background-position: center;"></div>
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full pointer-events-none opacity-10 blur-3xl" style="background: radial-gradient(circle, #fbbf24, transparent);"></div>

        <div class="relative max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.25em] text-amber-200 backdrop-blur-sm mb-4">
                <svg class="h-3.5 w-3.5 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                Katalog Lengkap
            </span>
            <h1 class="font-playfair text-3xl font-bold text-white sm:text-4xl lg:text-5xl" style="font-family: 'Playfair Display', serif;">
                Temukan Roti <span class="text-amber-300">Favoritmu</span>
            </h1>
            <p class="mt-4 text-base leading-7 text-amber-100">
                Jelajahi seluruh koleksi roti kami — gunakan filter kategori atau pencarian untuk menemukan yang kamu inginkan.
            </p>
        </div>
    </section>

    {{-- Catalog --}}
    <section class="mt-10">
        <livewire:product-catalog />
    </section>
@endsection