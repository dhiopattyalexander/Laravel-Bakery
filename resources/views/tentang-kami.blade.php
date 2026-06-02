@extends('layouts.app')

@section('judul', "Tentang Kami — L'Artisan Bakery")

@section('content')
    {{-- Breadcrumb & Back button --}}
    <div class="mb-6 flex items-center gap-3">
        <button onclick="history.back()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-amber-200 bg-white text-amber-800 shadow-sm transition hover:bg-amber-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
            <a href="{{ url('/') }}" class="transition hover:text-amber-700">Beranda</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-800">Tentang Kami</span>
        </nav>
    </div>

    {{-- Hero Section --}}
    <section class="relative overflow-hidden rounded-3xl p-8 sm:p-16 text-white mb-10" style="background: linear-gradient(135deg, #451a03 0%, #78350f 50%, #b45309 100%);">
        <div class="absolute inset-0 pointer-events-none opacity-20" style="background-image: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;"></div>
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full pointer-events-none opacity-25 blur-3xl" style="background: radial-gradient(circle, #fbbf24, transparent);"></div>

        <div class="relative max-w-3xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.25em] text-amber-200 backdrop-blur-sm mb-6">
                🏪 Kenali Kami Lebih Dekat
            </span>
            <h1 class="font-playfair text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl" style="font-family: 'Playfair Display', serif;">
                Dedikasi Rasa <br>Dari <span class="text-amber-300">Dapur Klasik Kami</span>
            </h1>
            <p class="mt-6 text-base leading-7 text-amber-100 sm:text-lg max-w-xl">
                L'Artisan Bakery hadir untuk menyajikan kehangatan roti yang dipanggang segar setiap pagi dengan komitmen rasa autentik dan bahan-bahan alami premium.
            </p>
        </div>
    </section>

    {{-- Our Story & Philosophy --}}
    <div class="grid gap-10 lg:grid-cols-2 items-center mb-16">
        <div>
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide" style="background: #fef3e2; color: #b45309;">
                📜 Sejarah & Filosofi
            </span>
            <h2 class="mt-4 font-playfair text-3xl font-bold text-gray-900 sm:text-4xl" style="font-family: 'Playfair Display', serif;">
                Awal Mula Kehangatan Dapur L'Artisan
            </h2>
            <p class="mt-5 text-sm sm:text-base leading-7 text-gray-600">
                Didirikan dengan kecintaan mendalam pada seni pembuatan roti klasik Prancis dan tradisional Indonesia, L'Artisan Bakery berawal dari dapur kecil rumahan. Kami percaya bahwa roti terbaik lahir dari kesabaran, keahlian tangan, dan bahan berkualitas premium.
            </p>
            <p class="mt-4 text-sm sm:text-base leading-7 text-gray-600">
                Setiap adonan difermentasi secara alami untuk menghasilkan tekstur lembut di dalam dan renyah di luar. Kami menghindari pengawet buatan dan pemanis sintetis, memastikan setiap gigitan tidak hanya lezat tetapi juga sehat bagi keluarga Anda.
            </p>
        </div>
        <div class="relative overflow-hidden rounded-3xl shadow-xl aspect-video lg:aspect-square">
            <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=800&q=80"
                 alt="Master baker preparing dough"
                 class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        </div>
    </div>

    {{-- Vision & Mission --}}
    <section class="grid gap-6 sm:grid-cols-2 mb-16">
        <div class="group rounded-3xl border border-amber-100 bg-white p-6 sm:p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl text-2xl mb-5" style="background: linear-gradient(135deg, #fef3e2, #fde8c8);">👁️</div>
            <h3 class="font-playfair text-xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Visi Kami</h3>
            <p class="mt-3 text-sm leading-6 text-gray-500">
                Menjadi toko roti artisan pilihan utama keluarga Indonesia yang dikenal karena kualitas bahan premium, keaslian rasa, dan kesegaran produk yang dipanggang segar setiap hari.
            </p>
        </div>
        <div class="group rounded-3xl border border-amber-100 bg-white p-6 sm:p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl text-2xl mb-5" style="background: linear-gradient(135deg, #fef3e2, #fde8c8);">🎯</div>
            <h3 class="font-playfair text-xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Misi Kami</h3>
            <p class="mt-3 text-sm leading-6 text-gray-500">
                Memadukan resep tradisional dengan teknologi pemanggangan modern untuk menyajikan roti sehat tanpa bahan pengawet, sekaligus memberikan pelayanan terbaik dan kemudahan pemesanan bagi para pelanggan setia kami.
            </p>
        </div>
    </section>

    {{-- Dapur & Nilai Kami --}}
    <section class="overflow-hidden rounded-3xl p-6 sm:p-10 mb-10 text-center" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4" style="background: #fde8c8; color: #b45309;">
            🍪 Nilai Kami
        </span>
        <h2 class="font-playfair text-2xl font-bold text-gray-900 sm:text-3xl mb-8" style="font-family: 'Playfair Display', serif;">
            Komitmen Kualitas Terbaik
        </h2>
        <div class="grid gap-6 sm:grid-cols-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm">
                <p class="text-3xl mb-2">🌾</p>
                <h4 class="font-bold text-sm text-gray-900">Bahan Alami</h4>
                <p class="mt-1 text-xs text-gray-500">Tanpa zat aditif buatan</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm">
                <p class="text-3xl mb-2">🔥</p>
                <h4 class="font-bold text-sm text-gray-900">Freshly Baked</h4>
                <p class="mt-1 text-xs text-gray-500">Dipanggang setiap pagi</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm">
                <p class="text-3xl mb-2">🧑‍🍳</p>
                <h4 class="font-bold text-sm text-gray-900">Chef Profesional</h4>
                <p class="mt-1 text-xs text-gray-500">Keahlian artisan tinggi</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm">
                <p class="text-3xl mb-2">❤️</p>
                <h4 class="font-bold text-sm text-gray-900">Penuh Cinta</h4>
                <p class="mt-1 text-xs text-gray-500">Dibuat tulus untuk Anda</p>
            </div>
        </div>
    </section>
@endsection
