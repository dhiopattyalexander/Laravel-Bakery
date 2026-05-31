@extends('layouts.app')

@section('judul', 'Beranda - Toko Roti')

@section('content')
    <section class="relative overflow-hidden rounded-[2rem] border border-amber-100 bg-gradient-to-br from-amber-50 via-white to-orange-50 p-8 shadow-sm md:p-14">
        <div class="absolute -right-24 -top-24 h-64 w-64 rounded-full bg-amber-200/40 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-orange-200/30 blur-3xl"></div>

        <div class="relative grid gap-10 lg:grid-cols-2 lg:items-center">
            <div class="space-y-6">
                <span class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-xs font-bold uppercase tracking-[0.25em] text-amber-800">
                    Roti Segar Setiap Hari
                </span>
                <h1 class="max-w-xl text-4xl font-black leading-tight text-gray-900 md:text-6xl">
                    Hangat, lembut, dan dibuat dengan cita rasa terbaik untuk meja makanmu.
                </h1>
                <p class="max-w-2xl text-base leading-7 text-gray-600 md:text-lg">
                    Jelajahi pilihan roti terlaris dan menu baru yang dipanggang dari bahan pilihan. Temukan rasa favoritmu dan pesan langsung dari katalog kami.
                </p>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center rounded-xl bg-amber-800 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-900">
                        Lihat Katalog
                    </a>
                    <a href="{{ route('orders.history') }}" class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-white px-6 py-3 text-sm font-semibold text-amber-800 transition hover:bg-amber-50">
                        Riwayat Pesanan
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-amber-100">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Kualitas</p>
                    <h2 class="mt-3 text-2xl font-bold text-gray-900">Bahan Pilihan</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">Dipanggang dengan bahan segar agar rasa dan teksturnya tetap konsisten.</p>
                </div>
                <div class="rounded-3xl bg-slate-900 p-5 text-white shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-300">Pelayanan</p>
                    <h2 class="mt-3 text-2xl font-bold">Pesan Lebih Cepat</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Pilih roti favoritmu, cek detailnya, lalu lanjutkan ke proses pemesanan.</p>
                </div>
                <div class="rounded-3xl bg-amber-100 p-5 shadow-sm sm:col-span-2">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-800">Pilihan Hari Ini</p>
                    <h2 class="mt-3 text-2xl font-bold text-gray-900">Cocok untuk sarapan, camilan, atau hadiah.</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-16">
        <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-amber-700">Produk Terlaris</p>
                <h2 class="mt-2 text-3xl font-black text-gray-900">Pilihan yang paling sering dicari pelanggan</h2>
            </div>
            <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-amber-800 hover:underline">
                Buka katalog lengkap
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @forelse($produkTerlaris as $bread)
                <article class="overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <a href="{{ route('breads.show', $bread->id) }}" class="block h-32 sm:h-48 overflow-hidden bg-gray-100">
                        <img src="{{ $bread->image_path ? asset('storage/' . $bread->image_path) : 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $bread->name }}" class="h-full w-full object-cover">
                    </a>
                    <div class="p-3 sm:p-5">
                        <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">{{ $bread->category->name ?? 'Kategori belum tersedia' }}</p>
                        <a href="{{ route('breads.show', $bread->id) }}" class="mt-1 sm:mt-2 block text-sm sm:text-lg font-bold text-gray-900 hover:text-amber-700 line-clamp-1">
                            {{ $bread->name }}
                        </a>
                        <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500 line-clamp-1 sm:line-clamp-2">{{ $bread->description ?? 'Roti unggulan dengan rasa yang konsisten.' }}</p>
                        <div class="mt-3 sm:mt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <span class="text-sm sm:text-lg font-black text-amber-700">Rp {{ number_format($bread->price, 0, ',', '.') }}</span>
                            <a href="{{ route('breads.show', $bread->id) }}" class="inline-flex justify-center rounded-lg sm:rounded-xl bg-amber-800 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-white transition hover:bg-amber-900">
                                Lihat
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500">
                    Belum ada produk terlaris yang tercatat.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-16">
        <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-amber-700">Menu Baru</p>
                <h2 class="mt-2 text-3xl font-black text-gray-900">Roti yang baru hadir di etalase kami</h2>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @forelse($menuBaru as $bread)
                <article class="overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <a href="{{ route('breads.show', $bread->id) }}" class="block h-32 sm:h-48 overflow-hidden bg-gray-100">
                        <img src="{{ $bread->image_path ? asset('storage/' . $bread->image_path) : 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $bread->name }}" class="h-full w-full object-cover">
                    </a>
                    <div class="p-3 sm:p-5">
                        <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $bread->category->name ?? 'Kategori belum tersedia' }}</p>
                        <a href="{{ route('breads.show', $bread->id) }}" class="mt-1 sm:mt-2 block text-sm sm:text-lg font-bold text-gray-900 hover:text-amber-700 line-clamp-1">
                            {{ $bread->name }}
                        </a>
                        <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500 line-clamp-1 sm:line-clamp-2">{{ $bread->description ?? 'Menu baru yang siap dicoba.' }}</p>
                        <div class="mt-3 sm:mt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <span class="text-sm sm:text-lg font-black text-amber-700">Rp {{ number_format($bread->price, 0, ',', '.') }}</span>
                            <a href="{{ route('breads.show', $bread->id) }}" class="inline-flex justify-center rounded-lg sm:rounded-xl border border-amber-200 bg-amber-50 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-amber-800 transition hover:bg-amber-100">
                                Detail
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500">
                    Belum ada menu baru yang tersedia.
                </div>
            @endforelse
        </div>
    </section>
@endsection