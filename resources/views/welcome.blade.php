@extends('layouts.app')

@section('judul', "Beranda — L'Artisan Bakery")

@section('content')

{{-- ========================================================
     HERO CAROUSEL
======================================================== --}}
<section class="relative overflow-hidden rounded-3xl" style="min-height: 520px;">
    <!-- Slides -->
    <div id="carousel-container" class="relative" style="min-height: 520px;">

        {{-- Slide 1: Sourdough --}}
        <div class="carousel-slide active absolute inset-0 flex items-center" style="background: linear-gradient(135deg, #451a03 0%, #78350f 40%, #b45309 100%);">
            <div class="absolute inset-0 pointer-events-none opacity-20" style="background-image: url('https://images.unsplash.com/photo-1549931319-a545dcf3bc73?auto=format&fit=crop&w=1400&q=80'); background-size: cover; background-position: center;"></div>
            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(135deg, rgba(69,26,3,0.85) 0%, rgba(180,83,9,0.5) 100%);"></div>
            <div class="relative mx-auto max-w-7xl px-6 sm:px-10 lg:px-12 py-16 w-full">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.25em] text-amber-200 backdrop-blur-sm">
                        <svg class="h-3.5 w-3.5 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Roti Segar Setiap Hari
                    </span>
                    <h1 class="mt-5 font-playfair text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl" style="font-family: 'Playfair Display', serif;">
                        Kehangatan Roti Klasik<br>yang <span class="text-amber-300">Memikat Selera</span>
                    </h1>
                    <p class="mt-5 text-base leading-7 text-amber-100 sm:text-lg">
                        Setiap potong dipanggang dengan resep turun-temurun dan bahan pilihan untuk kelembutan tiada tanding.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-amber-400 px-6 py-3 text-sm font-bold text-amber-900 shadow-lg transition hover:bg-amber-300 hover:shadow-xl">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Pesan Roti Segar
                        </a>
                        <a href="#produk-terlaris" class="inline-flex items-center gap-2 rounded-2xl border border-white/30 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                            Lihat Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 2: Cakes --}}
        <div class="carousel-slide absolute inset-0 flex items-center" style="background: linear-gradient(135deg, #1c1917 0%, #44403c 40%, #78716c 100%);">
            <div class="absolute inset-0 pointer-events-none opacity-25" style="background-image: url('https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1400&q=80'); background-size: cover; background-position: center;"></div>
            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(135deg, rgba(28,25,23,0.88) 0%, rgba(120,113,108,0.45) 100%);"></div>
            <div class="relative mx-auto max-w-7xl px-6 sm:px-10 lg:px-12 py-16 w-full">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.25em] text-amber-300 backdrop-blur-sm">
                        <svg class="h-3.5 w-3.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Rayakan Momen Spesial
                    </span>
                    <h1 class="mt-5 font-playfair text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl" style="font-family: 'Playfair Display', serif;">
                        Cakes Istimewa<br>untuk <span class="text-amber-400">Hari Bahagia</span>
                    </h1>
                    <p class="mt-5 text-base leading-7 text-stone-300 sm:text-lg">
                        Koleksi Black Forest dan Lemon Taart premium buatan master patisserie kami siap mempermanis hari spesial Anda.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('orders.index') }}?kategori=Cakes" class="inline-flex items-center gap-2 rounded-2xl bg-amber-500 px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-amber-400">
                            Jelajahi Cakes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 3: Pastry --}}
        <div class="carousel-slide absolute inset-0 flex items-center" style="background: linear-gradient(135deg, #14532d 0%, #166534 40%, #15803d 100%);">
            <div class="absolute inset-0 pointer-events-none opacity-20" style="background-image: url('https://images.unsplash.com/photo-1608198093002-ad4e005484ec?auto=format&fit=crop&w=1400&q=80'); background-size: cover; background-position: center;"></div>
            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(135deg, rgba(20,83,45,0.9) 0%, rgba(21,128,61,0.5) 100%);"></div>
            <div class="relative mx-auto max-w-7xl px-6 sm:px-10 lg:px-12 py-16 w-full">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.25em] text-green-200 backdrop-blur-sm">
                        <svg class="h-3.5 w-3.5 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Artisan Pastry
                    </span>
                    <h1 class="mt-5 font-playfair text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl" style="font-family: 'Playfair Display', serif;">
                        Flaky & Crispy<br><span class="text-green-300">Artisan Pastry</span>
                    </h1>
                    <p class="mt-5 text-base leading-7 text-green-100 sm:text-lg">
                        Rasakan renyahnya Cromboloni lumer dan Croissant mentega premium, teman terbaik kopi pagi Anda.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('orders.index') }}?kategori=Pastry%20%26%20Danish" class="inline-flex items-center gap-2 rounded-2xl bg-white px-6 py-3 text-sm font-bold text-green-800 shadow-lg transition hover:bg-green-50">
                            Pesan Pastry
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel Controls -->
    <button id="carousel-prev" aria-label="Slide sebelumnya"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-sm transition hover:bg-white/30">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button id="carousel-next" aria-label="Slide berikutnya"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-sm transition hover:bg-white/30">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
    </button>

    <!-- Carousel Dots -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        <button class="carousel-dot h-2.5 w-8 rounded-full bg-white transition-all duration-300" data-slide="0" aria-label="Slide 1"></button>
        <button class="carousel-dot h-2.5 w-2.5 rounded-full bg-white/50 transition-all duration-300" data-slide="1" aria-label="Slide 2"></button>
        <button class="carousel-dot h-2.5 w-2.5 rounded-full bg-white/50 transition-all duration-300" data-slide="2" aria-label="Slide 3"></button>
    </div>
</section>

{{-- ========================================================
     KEUNGGULAN
======================================================== --}}
<section class="mt-14">
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="group flex items-start gap-4 rounded-2xl border border-amber-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-amber-700" style="background: linear-gradient(135deg, #fef3e2, #fde8c8);">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Bahan Premium</h3>
                <p class="mt-1 text-sm text-gray-500 leading-6">Tepung pilihan dan bahan segar untuk rasa yang konsisten setiap hari.</p>
            </div>
        </div>
        <div class="group flex items-start gap-4 rounded-2xl border border-amber-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-amber-700" style="background: linear-gradient(135deg, #fef3e2, #fde8c8);">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Pesan Cepat</h3>
                <p class="mt-1 text-sm text-gray-500 leading-6">Tambahkan ke keranjang, bayar QRIS, dan pesananmu langsung diproses.</p>
            </div>
        </div>
        <div class="group flex items-start gap-4 rounded-2xl border border-amber-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-amber-700" style="background: linear-gradient(135deg, #fef3e2, #fde8c8);">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Pickup & Antar</h3>
                <p class="mt-1 text-sm text-gray-500 leading-6">Ambil langsung di toko atau pilih pengiriman instan lewat ojek online.</p>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================
     PRODUK TERLARIS
======================================================== --}}
<section id="produk-terlaris" class="mt-16">
    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.2em]" style="background: #fef3e2; color: #b45309;">
                Terlaris
            </span>
            <h2 class="mt-3 font-playfair text-3xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                Pilihan Paling Populer
            </h2>
            <p class="mt-2 text-sm text-gray-500">Satu produk terlaris & favorit dari setiap kategori roti kami.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-amber-700 transition hover:text-amber-900 hover:underline">
            Lihat semua produk
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <!-- Horizontal Scrollable Container -->
    <div class="relative">
        <!-- Navigation Buttons -->
        <button id="slide-left" aria-label="Geser Kiri"
                class="absolute -left-4 top-1/2 -translate-y-1/2 z-20 hidden md:flex h-12 w-12 items-center justify-center rounded-full bg-white text-amber-950 border border-amber-100 shadow-lg transition hover:bg-amber-50 hover:scale-105 active:scale-95">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        
        <button id="slide-right" aria-label="Geser Kanan"
                class="absolute -right-4 top-1/2 -translate-y-1/2 z-20 hidden md:flex h-12 w-12 items-center justify-center rounded-full bg-white text-amber-950 border border-amber-100 shadow-lg transition hover:bg-amber-50 hover:scale-105 active:scale-95">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div id="popular-slider" class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-6 pt-2 scrollbar-none" style="-ms-overflow-style: none; scrollbar-width: none;">
            @forelse($produkTerlaris as $bread)
                <article class="w-[280px] sm:w-[310px] shrink-0 snap-start group flex flex-col overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <a href="{{ route('breads.show', $bread->id) }}" class="block overflow-hidden bg-amber-50" style="height: 180px;">
                        <img src="{{ $bread->image_url }}"
                             alt="{{ $bread->name }}"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    </a>
                    <div class="flex flex-1 flex-col p-4 sm:p-5">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.18em] text-amber-600">{{ $bread->category->name ?? 'Lainnya' }}</p>
                        <a href="{{ route('breads.show', $bread->id) }}" class="mt-2 block text-base sm:text-lg font-bold text-gray-900 transition hover:text-amber-700 line-clamp-1">
                            {{ $bread->name }}
                        </a>
                        <p class="mt-1.5 text-xs text-gray-500 line-clamp-2 leading-5">{{ $bread->description ?? 'Roti unggulan pilihan pelanggan.' }}</p>
                        <div class="mt-auto pt-4 flex items-center justify-between gap-2">
                            <span class="text-base sm:text-lg font-black text-amber-700">Rp {{ number_format($bread->price, 0, ',', '.') }}</span>
                            <a href="{{ route('breads.show', $bread->id) }}" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-xs font-bold text-white transition hover:opacity-90" style="background: linear-gradient(135deg, #d97706, #b45309);">
                                Pesan
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="w-full rounded-2xl border border-dashed border-amber-200 bg-amber-50 p-8 text-center">
                    <p class="text-sm text-amber-700">Belum ada produk terlaris yang tercatat.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ========================================================
     MENU BARU
======================================================== --}}
<section class="mt-16">
    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.2em]" style="background: #ecfdf5; color: #059669;">
                Baru
            </span>
            <h2 class="mt-3 font-playfair text-3xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                Roti Terbaru di Etalase
            </h2>
            <p class="mt-2 text-sm text-gray-500">Menu baru yang baru saja hadir dan siap untuk dicoba.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:gap-5 xl:grid-cols-4">
        @forelse($menuBaru as $bread)
            <article class="group flex flex-col overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <a href="{{ route('breads.show', $bread->id) }}" class="relative block overflow-hidden bg-amber-50" style="height: 160px;">
                    <img src="{{ $bread->image_url }}"
                         alt="{{ $bread->name }}"
                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    <span class="absolute top-2 left-2 rounded-full bg-emerald-500 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white shadow">NEW</span>
                </a>
                <div class="flex flex-1 flex-col p-3 sm:p-4">
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.18em] text-gray-400">{{ $bread->category->name ?? 'Lainnya' }}</p>
                    <a href="{{ route('breads.show', $bread->id) }}" class="mt-1.5 block text-sm sm:text-base font-bold text-gray-900 transition hover:text-amber-700 line-clamp-1">
                        {{ $bread->name }}
                    </a>
                    <p class="mt-1 text-xs text-gray-400 line-clamp-2 leading-5">{{ $bread->description ?? 'Menu baru yang siap dicoba.' }}</p>
                    <div class="mt-auto pt-3 flex items-center justify-between gap-2">
                        <span class="text-sm sm:text-base font-black text-amber-700">Rp {{ number_format($bread->price, 0, ',', '.') }}</span>
                        <a href="{{ route('breads.show', $bread->id) }}" class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-800 transition hover:bg-amber-100">
                            Detail
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-8 text-center">
                <p class="text-sm text-gray-500">Belum ada menu baru yang tersedia.</p>
            </div>
        @endforelse
    </div>
</section>

{{-- ========================================================
     TENTANG KAMI
======================================================== --}}
<section id="tentang-kami" class="mt-16">
    <div class="overflow-hidden rounded-3xl" style="background: linear-gradient(135deg, #fef9f0 0%, #fef3e2 100%);">
        <div class="grid lg:grid-cols-2">
            <div class="p-8 sm:p-12 lg:p-14 flex flex-col justify-center">
                <span class="inline-flex w-fit items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] mb-5" style="background: #fde8c8; color: #b45309;">
                    Tentang Kami
                </span>
                <h2 class="font-playfair text-3xl font-bold text-gray-900 sm:text-4xl" style="font-family: 'Playfair Display', serif;">
                    Dari Dapur Kami <br>ke Meja Makanmu
                </h2>
                <p class="mt-5 text-base leading-7 text-gray-600">
                    L'Artisan Bakery berdiri dengan satu misi: menghadirkan roti berkualitas terbaik yang dibuat dengan cinta dan bahan pilihan. Setiap roti yang kami panggang merupakan hasil kerja keras dan dedikasi tim kami.
                </p>
                <p class="mt-3 text-base leading-7 text-gray-600">
                    Kami percaya bahwa roti yang baik bukan hanya soal rasa, tetapi juga soal kesegaran, kualitas bahan, dan konsistensi. Itulah mengapa kami memanggang roti segar setiap hari.
                </p>
                <div class="mt-8 flex flex-wrap gap-6">
                    <div class="text-center">
                        <p class="font-playfair text-3xl font-bold text-amber-700" style="font-family: 'Playfair Display', serif;">500+</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Pelanggan Puas</p>
                    </div>
                    <div class="text-center">
                        <p class="font-playfair text-3xl font-bold text-amber-700" style="font-family: 'Playfair Display', serif;">20+</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Varian Roti</p>
                    </div>
                    <div class="text-center">
                        <p class="font-playfair text-3xl font-bold text-amber-700" style="font-family: 'Playfair Display', serif;">5★</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Rating Pelanggan</p>
                    </div>
                </div>
                <div class="mt-8">
                    <a href="https://wa.me/6285888426839" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-2xl bg-green-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-green-700">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Hubungi Kami via WhatsApp
                    </a>
                </div>
            </div>
            <div class="relative hidden lg:block">
                <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=800&q=70"
                     alt="Bakery kami"
                     class="h-full w-full object-cover"
                     style="min-height: 420px;">
                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(254,249,240,0.3) 0%, transparent 100%);"></div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Carousel Logic
    (function () {
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        let current = 0;
        let timer;

        function goTo(index) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('w-8', 'bg-white');
            dots[current].classList.add('w-2.5', 'bg-white/50');

            current = (index + slides.length) % slides.length;

            slides[current].classList.add('active');
            dots[current].classList.add('w-8', 'bg-white');
            dots[current].classList.remove('w-2.5', 'bg-white/50');
        }

        function autoPlay() {
            timer = setInterval(() => goTo(current + 1), 5000);
        }

        function resetTimer() {
            clearInterval(timer);
            autoPlay();
        }

        document.getElementById('carousel-next')?.addEventListener('click', () => { goTo(current + 1); resetTimer(); });
        document.getElementById('carousel-prev')?.addEventListener('click', () => { goTo(current - 1); resetTimer(); });

        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                goTo(parseInt(dot.dataset.slide));
                resetTimer();
            });
        });

        autoPlay();
    })();

    // Popular Slider Logic
    (function() {
        const slider = document.getElementById('popular-slider');
        const btnLeft = document.getElementById('slide-left');
        const btnRight = document.getElementById('slide-right');
        if (slider && btnLeft && btnRight) {
            const scrollAmount = 330; // Card width + gap
            btnLeft.addEventListener('click', () => {
                slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            btnRight.addEventListener('click', () => {
                slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
            
            // Toggle button visibility based on scroll position
            function toggleButtons() {
                const isAtStart = slider.scrollLeft <= 5;
                const isAtEnd = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 5;
                btnLeft.style.opacity = isAtStart ? '0.3' : '1';
                btnLeft.style.pointerEvents = isAtStart ? 'none' : 'auto';
                btnRight.style.opacity = isAtEnd ? '0.3' : '1';
                btnRight.style.pointerEvents = isAtEnd ? 'none' : 'auto';
            }
            
            slider.addEventListener('scroll', toggleButtons);
            window.addEventListener('resize', toggleButtons);
            // Initial run
            setTimeout(toggleButtons, 100);
        }
    })();
</script>
@endpush