@extends('layouts.app')

@section('judul', "Checkout — L'Artisan Bakery")
@section('hideFloatingCart', '1')

@section('content')
    {{-- Breadcrumb & Back button --}}
    <div class="mb-6 flex items-center gap-3">
        <button onclick="history.back()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-amber-200 bg-white text-amber-800 shadow-sm transition hover:bg-amber-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
            <a href="{{ url('/') }}" class="transition hover:text-amber-700">Beranda</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('orders.index') }}" class="transition hover:text-amber-700">Katalog</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-800">Checkout</span>
        </nav>
    </div>

<div class="grid gap-8 lg:grid-cols-[1.4fr_0.6fr]">

    {{-- LEFT: Cart Review --}}
    <section class="space-y-5">
        {{-- Header --}}
        <header class="overflow-hidden rounded-3xl p-6 text-white" style="background: linear-gradient(135deg, #451a03 0%, #78350f 50%, #b45309 100%);">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-200">Langkah 1 dari 2</p>
            <h1 class="mt-2 font-playfair text-2xl font-bold sm:text-3xl" style="font-family: 'Playfair Display', serif;">
                Review Pesanan
            </h1>
            <p class="mt-2 text-sm text-amber-100">Periksa item keranjang, pilih metode pengiriman, lalu lanjutkan ke pembayaran.</p>
        </header>

        {{-- Error Alerts --}}
        @if(session('error'))
            <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Cart Items --}}
        <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-amber-50 px-5 py-4">
                <h2 class="text-base font-bold text-gray-900">Item di Keranjang</h2>
                <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-amber-700 transition hover:text-amber-900 hover:underline">
                    + Tambah Produk
                </a>
            </div>

            <div class="divide-y divide-amber-50 px-5">
                @foreach($items as $item)
                    <div class="py-4">
                        <div class="flex gap-4">
                            {{-- Product Image --}}
                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-amber-50">
                                <img
                                    src="{{ \App\Models\Bread::getImageUrl($item['gambar'] ?? null) }}"
                                    alt="{{ $item['nama'] }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>

                            {{-- Product Detail --}}
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-gray-900 truncate">{{ $item['nama'] }}</h3>
                                <p class="mt-0.5 text-xs text-gray-400">Stok tersedia: {{ $item['stok'] }}</p>

                                <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                    {{-- Qty Control --}}
                                    <div class="flex items-center rounded-xl border border-amber-200 bg-amber-50 overflow-hidden">
                                        <form method="POST" action="{{ route('checkout.item') }}">
                                            @csrf
                                            <input type="hidden" name="bread_id" value="{{ $item['id'] }}">
                                            <input type="hidden" name="action" value="decrement">
                                            <button type="submit" class="flex h-9 w-9 items-center justify-center font-bold text-amber-800 hover:bg-amber-100 transition">−</button>
                                        </form>
                                        <span class="min-w-10 px-2 text-center text-sm font-black text-gray-900">{{ $item['jumlah'] }}</span>
                                        <form method="POST" action="{{ route('checkout.item') }}">
                                            @csrf
                                            <input type="hidden" name="bread_id" value="{{ $item['id'] }}">
                                            <input type="hidden" name="action" value="increment">
                                            <button type="submit" class="flex h-9 w-9 items-center justify-center font-bold text-amber-800 hover:bg-amber-100 transition">+</button>
                                        </form>
                                    </div>

                                    {{-- Price & Remove --}}
                                    <div class="text-right">
                                        <p class="text-base font-black text-amber-800">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</p>
                                        <form method="POST" action="{{ route('checkout.item') }}" class="mt-1">
                                            @csrf
                                            <input type="hidden" name="bread_id" value="{{ $item['id'] }}">
                                            <input type="hidden" name="action" value="remove">
                                            <button type="submit" class="text-xs font-semibold text-red-500 transition hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- RIGHT: Checkout Options --}}
    <aside>
        <form method="POST" action="{{ route('checkout.process') }}"
              class="sticky top-24 space-y-4 overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
            @csrf

            <div class="border-b border-amber-50 px-5 py-4">
                <h2 class="text-base font-bold text-gray-900">Opsi Checkout</h2>
            </div>

            <div class="space-y-4 px-5 pb-5">
                {{-- Delivery Method --}}
                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Metode Pengiriman</label>
                    <div class="space-y-2">
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-3 transition {{ old('delivery_method', 'instant') === 'pickup' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-200' }}">
                            <input type="radio" name="delivery_method" value="pickup"
                                   {{ old('delivery_method', 'instant') === 'pickup' ? 'checked' : '' }}
                                   {{ ! $pickupAvailable ? 'disabled' : '' }}
                                   class="h-4 w-4 text-amber-700 focus:ring-amber-500">
                            <div>
                                <span class="block text-sm font-semibold text-gray-800">🏪 Ambil di Toko</span>
                                <span class="block text-xs text-gray-500">Pickup langsung di lokasi kami</span>
                            </div>
                        </label>
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-3 transition {{ old('delivery_method', 'instant') === 'instant' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-200' }}">
                            <input type="radio" name="delivery_method" value="instant"
                                   {{ old('delivery_method', 'instant') === 'instant' ? 'checked' : '' }}
                                   class="h-4 w-4 text-amber-700 focus:ring-amber-500">
                            <div>
                                <span class="block text-sm font-semibold text-gray-800">🚀 Pengiriman Instan</span>
                                <span class="block text-xs text-gray-500">Dikirim via ojek online</span>
                            </div>
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-400">
                        Jam operasional pickup: {{ $storeOpenAt }} – {{ $storeCloseAt }}
                        @if(! $pickupAvailable)
                            <span class="font-semibold text-red-600"> (Saat ini nonaktif)</span>
                        @endif
                    </p>
                </div>

                {{-- Pickup Time --}}
                <div>
                    <label for="pickup_time" class="mb-1.5 block text-sm font-bold text-gray-700">Waktu Pickup</label>
                    <input id="pickup_time" name="pickup_time" type="time"
                           value="{{ old('pickup_time') }}" min="{{ $storeOpenAt }}" max="{{ $storeCloseAt }}"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    <p class="mt-1 text-xs text-gray-400">Wajib diisi jika memilih pickup.</p>
                </div>

                {{-- Notes --}}
                <div>
                    <label for="order_notes" class="mb-1.5 block text-sm font-bold text-gray-700">Catatan Khusus</label>
                    <textarea id="order_notes" name="order_notes" rows="3"
                              placeholder="Contoh: tolong plastiknya dipisah"
                              class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-amber-500 focus:outline-none resize-none">{{ old('order_notes') }}</textarea>
                </div>

                {{-- Payment --}}
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">Metode Pembayaran</label>
                    <label class="flex items-center gap-3 rounded-2xl border-2 border-amber-400 bg-amber-50 p-3">
                        <input type="radio" name="payment_method" value="qris" checked class="h-4 w-4 text-amber-700">
                        <div>
                            <span class="block text-sm font-semibold text-gray-800">📱 QRIS</span>
                            <span class="block text-xs text-gray-500">Scan QR untuk bayar</span>
                        </div>
                    </label>
                </div>

                {{-- Shipping Address --}}
                <div class="rounded-2xl border border-amber-100 p-4" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Alamat Pengiriman</p>
                            @if(! empty($shippingAddress['address']))
                                <p class="mt-2 text-sm font-bold text-gray-900">{{ $shippingAddress['label'] ?? 'Alamat Profil' }}</p>
                                <p class="text-sm text-gray-700">{{ $shippingAddress['recipient_name'] ?? auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ $shippingAddress['phone'] ?? ($profile->phone ?? '-') }}</p>
                                <p class="mt-1.5 text-sm leading-5 text-gray-600">{{ $shippingAddress['address'] }}</p>
                            @else
                                <p class="mt-1.5 text-sm font-semibold text-red-600">Alamat belum diatur.</p>
                            @endif
                        </div>
                        <a href="{{ route('account.address') }}" class="shrink-0 rounded-xl border border-amber-200 bg-white px-3 py-1.5 text-xs font-bold text-amber-800 transition hover:bg-amber-50">
                            Ubah
                        </a>
                    </div>
                </div>

                {{-- Total --}}
                <div class="rounded-2xl bg-gray-900 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-300">Total Belanja</span>
                        <span class="text-xl font-black text-white">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl py-3.5 text-sm font-bold text-white transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #d97706, #b45309);">
                    Lanjut ke Pembayaran →
                </button>
                <a href="{{ route('orders.index') }}" class="flex w-full items-center justify-center rounded-2xl border border-gray-200 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    ← Kembali Belanja
                </a>
            </div>
        </form>
    </aside>
</div>
@endsection
