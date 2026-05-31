@extends('layouts.app')

@section('judul', 'Checkout Pesanan')
@section('hideFloatingCart', '1')

@section('content')
    <div class="grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
        <section class="space-y-6">
            <header class="rounded-3xl border border-amber-200 bg-gradient-to-r from-amber-700 via-amber-800 to-orange-700 p-6 text-white shadow">
                <p class="text-xs uppercase tracking-[0.22em] text-amber-100">Checkout</p>
                <h1 class="mt-2 text-3xl font-black">Review Keranjang & Pengiriman</h1>
                <p class="mt-2 text-sm text-amber-100">Atur jumlah item, pilih pengiriman, lalu lanjutkan ke pembayaran QRIS.</p>
            </header>

            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Item di Keranjang</h2>
                    <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-amber-800 hover:underline">Tambah produk lagi</a>
                </div>

                <div class="space-y-4">
                    @foreach($items as $item)
                        <article class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                            <div class="flex gap-3">
                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl bg-gray-100">
                                    <img
                                        src="{{ ! empty($item['gambar']) ? asset('storage/' . $item['gambar']) : asset('images/roti-placeholder.svg') }}"
                                        alt="{{ $item['nama'] }}"
                                        class="h-full w-full object-cover"
                                    >
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-base font-bold text-gray-900">{{ $item['nama'] }}</h3>
                                    <p class="mt-1 text-xs text-gray-500">Stok tersedia: {{ $item['stok'] }}</p>

                                    <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                        <div class="flex items-center rounded-lg border border-gray-200 bg-white">
                                            <form method="POST" action="{{ route('checkout.item') }}">
                                                @csrf
                                                <input type="hidden" name="bread_id" value="{{ $item['id'] }}">
                                                <input type="hidden" name="action" value="decrement">
                                                <button type="submit" class="px-3 py-1.5 text-gray-700 hover:bg-gray-100">-</button>
                                            </form>

                                            <span class="min-w-10 px-2 text-center text-sm font-bold text-gray-900">{{ $item['jumlah'] }}</span>

                                            <form method="POST" action="{{ route('checkout.item') }}">
                                                @csrf
                                                <input type="hidden" name="bread_id" value="{{ $item['id'] }}">
                                                <input type="hidden" name="action" value="increment">
                                                <button type="submit" class="px-3 py-1.5 text-gray-700 hover:bg-gray-100">+</button>
                                            </form>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm font-black text-amber-800">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</p>
                                            <form method="POST" action="{{ route('checkout.item') }}" class="mt-1">
                                                @csrf
                                                <input type="hidden" name="bread_id" value="{{ $item['id'] }}">
                                                <input type="hidden" name="action" value="remove">
                                                <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <aside>
            <form method="POST" action="{{ route('checkout.process') }}" class="sticky top-24 space-y-5 rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                @csrf

                <h2 class="text-lg font-bold text-gray-900">Opsi Checkout</h2>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Opsi Pengiriman</label>
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 px-3 py-2 text-sm">
                            <input type="radio" name="delivery_method" value="pickup" class="h-4 w-4" {{ old('delivery_method', 'instant') === 'pickup' ? 'checked' : '' }} {{ ! $pickupAvailable ? 'disabled' : '' }}>
                            <span>Ambil di Toko (Pickup)</span>
                        </label>

                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 px-3 py-2 text-sm">
                            <input type="radio" name="delivery_method" value="instant" class="h-4 w-4" {{ old('delivery_method', 'instant') === 'instant' ? 'checked' : '' }}>
                            <span>Pengiriman Instan (Ojol)</span>
                        </label>
                    </div>

                    <p class="mt-2 text-xs text-gray-500">
                        Jam operasional pickup: {{ $storeOpenAt }} - {{ $storeCloseAt }}
                        @if(! $pickupAvailable)
                            <span class="font-semibold text-red-600">(Saat ini pickup nonaktif)</span>
                        @endif
                    </p>
                </div>

                <div>
                    <label for="pickup_time" class="text-sm font-semibold text-gray-700">Time Picker Pickup</label>
                    <input
                        id="pickup_time"
                        name="pickup_time"
                        type="time"
                        value="{{ old('pickup_time') }}"
                        min="{{ $storeOpenAt }}"
                        max="{{ $storeCloseAt }}"
                        class="mt-2 w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                    >
                    <p class="mt-1 text-xs text-gray-500">Wajib diisi jika memilih Pickup.</p>
                </div>

                <div>
                    <label for="order_notes" class="text-sm font-semibold text-gray-700">Catatan Khusus</label>
                    <textarea
                        id="order_notes"
                        name="order_notes"
                        rows="4"
                        placeholder="Contoh: tolong plastiknya dipisah"
                        class="mt-2 w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                    >{{ old('order_notes') }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Metode Pembayaran</label>
                    <label class="mt-2 flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm">
                        <input type="radio" name="payment_method" value="qris" class="h-4 w-4" checked>
                        <span>QRIS</span>
                    </label>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Alamat Pengiriman Default</p>
                            @if(! empty($shippingAddress['address']))
                                <p class="mt-2 text-sm font-bold text-gray-900">{{ $shippingAddress['label'] ?? 'Alamat Profil' }}</p>
                                <p class="mt-1 text-sm text-gray-600">{{ $shippingAddress['recipient_name'] ?? auth()->user()->name }}</p>
                                <p class="text-sm text-gray-600">{{ $shippingAddress['phone'] ?? ($profile->phone ?? '-') }}</p>
                                <p class="mt-2 text-sm leading-6 text-gray-700">{{ $shippingAddress['address'] }}</p>
                            @else
                                <p class="mt-1 text-sm text-red-700">Alamat pengiriman belum diatur.</p>
                            @endif
                            <p class="mt-2 text-xs text-gray-500">Checkout akan memakai alamat default ini secara otomatis.</p>
                        </div>

                        <a href="{{ route('account.address') }}" class="shrink-0 rounded-lg border border-amber-200 bg-white px-3 py-2 text-xs font-semibold text-amber-800 transition hover:bg-amber-100">
                            Ubah
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-50 p-4">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>Total Belanja</span>
                        <span class="text-lg font-black text-gray-900">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-amber-800 px-4 py-3 text-sm font-bold text-white transition hover:bg-amber-900">
                    Checkout & Lanjut Bayar
                </button>

                <a href="{{ route('orders.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Kembali Belanja
                </a>
            </form>
        </aside>
    </div>
@endsection
