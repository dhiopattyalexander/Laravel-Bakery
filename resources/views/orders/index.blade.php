@extends('layouts.app')

@section('judul', "Katalog Roti — L'Artisan Bakery")

@section('content')
    {{-- Hero Banner --}}
    <section class="relative overflow-hidden rounded-3xl p-8 sm:p-12" style="background: linear-gradient(135deg, #451a03 0%, #78350f 45%, #b45309 100%); min-height: 200px;">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1568254183919-78a4f43a2877?auto=format&fit=crop&w=1200&q=40'); background-size: cover; background-position: center;"></div>
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full opacity-10 blur-3xl" style="background: radial-gradient(circle, #fbbf24, transparent);"></div>

        <div class="relative max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.25em] text-amber-200 backdrop-blur-sm mb-4">
                🧺 Katalog Lengkap
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