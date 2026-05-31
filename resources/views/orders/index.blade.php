@extends('layouts.app')

@section('judul', 'Katalog Roti - Toko Roti')

@section('content')
    <section class="overflow-hidden rounded-[2rem] border border-amber-100 bg-gradient-to-br from-amber-900 via-amber-800 to-orange-800 p-8 text-white shadow-sm md:p-12">
        <div class="max-w-3xl space-y-5">
            <span class="inline-flex rounded-full bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.25em] text-amber-100">
                Katalog Roti
            </span>
            <h1 class="text-4xl font-black leading-tight md:text-5xl">
                Temukan roti favoritmu dan pesan dengan cepat.
            </h1>
            <p class="text-base leading-7 text-amber-100 md:text-lg">
                Gunakan pencarian real-time untuk menemukan roti berdasarkan nama tanpa memuat ulang halaman.
            </p>
        </div>
    </section>

    <section class="mt-12">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Pilihan Menu Kami</h2>
            <p class="mt-2 text-sm text-gray-600">Koleksi roti yang sedang tersedia untuk dipesan.</p>
        </div>

        <livewire:product-catalog />
    </section>
@endsection