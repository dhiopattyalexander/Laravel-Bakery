@extends('layouts.app')

@section('judul', $bread->name . ' - Toko Roti')

@section('content')
    <div class="grid gap-8 lg:grid-cols-2">
        <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
            <div class="relative min-h-[24rem] bg-gray-100">
                <img
                    src="{{ $bread->image_path ? asset('storage/' . $bread->image_path) : 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80' }}"
                    alt="{{ $bread->name }}"
                    class="h-full w-full object-cover"
                >
            </div>
        </div>

        <div class="space-y-6 rounded-3xl border border-amber-100 bg-white p-8 shadow-sm">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">
                    {{ $bread->category->name ?? 'Kategori belum tersedia' }}
                </p>
                <h1 class="mt-2 text-3xl font-extrabold text-gray-900">
                    {{ $bread->name }}
                </h1>
            </div>

            <p class="text-base leading-7 text-gray-600">
                {{ $bread->description ?? 'Roti lezat ini belum memiliki deskripsi tambahan.' }}
            </p>

            <div class="flex flex-wrap items-center gap-4">
                <span class="text-3xl font-black text-amber-700">
                    Rp {{ number_format($bread->price, 0, ',', '.') }}
                </span>
                <span class="rounded-full bg-amber-50 px-3 py-1 text-sm font-semibold text-amber-700">
                    Stok tersedia: {{ $bread->stock }}
                </span>
            </div>

            <div class="rounded-2xl border border-dashed border-amber-200 bg-amber-50 p-4">
                <p class="text-sm text-amber-900">
                    Gunakan kontrol jumlah di bawah untuk menambahkan roti ini ke keranjang belanja.
                </p>
            </div>

            <livewire:bread-quick-add :bread="$bread" />

            <div>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-white px-6 py-3 text-sm font-semibold text-amber-800 transition hover:bg-amber-50">
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>
@endsection