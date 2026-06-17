@extends('layouts.app')

@section('judul', 'Invoice Pesanan #' . $order->id . " — L'Artisan Bakery")

@section('content')
    @if(in_array(strtolower($order->status ?? 'pending'), ['pending', 'processing'], true))
        <script>
            setTimeout(function() { window.location.reload(); }, 10000);
        </script>
    @endif

    @php
        $status = strtolower($order->status ?? 'pending');
        $hasPaidAt = ! empty($checkoutMeta['paid_at']);
        $hasExpiredAt = ! empty($checkoutMeta['expired_at']);

        $statusLabel = match ($status) {
            'completed'                      => 'Selesai',
            'processing'                     => 'Processing',
            'cancelled', 'canceled', 'failed' => 'Dibatalkan',
            default                          => 'Pending',
        };

        $statusStyle = match ($status) {
            'completed', 'paid', 'success'   => 'background:#d1fae5;color:#065f46;',
            'processing'                     => 'background:#dbeafe;color:#1e40af;',
            'pending'                        => 'background:#fef3c7;color:#92400e;',
            'cancelled', 'canceled', 'failed' => 'background:#fee2e2;color:#991b1b;',
            default                          => 'background:#f1f5f9;color:#475569;',
        };
    @endphp

    <div class="mx-auto max-w-3xl space-y-6">
        {{-- Breadcrumb & Back button --}}
        <div class="mb-6 flex items-center gap-3">
            <button onclick="history.back()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-amber-200 bg-white text-amber-800 shadow-sm transition hover:bg-amber-50">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                <a href="{{ url('/') }}" class="transition hover:text-amber-700">Beranda</a>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('account.orders') }}" class="transition hover:text-amber-700">Riwayat Pesanan</a>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-800">Detail Pesanan #{{ $order->id }}</span>
            </nav>
        </div>

        {{-- Invoice Card --}}
        <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-lg">

            {{-- Header --}}
            <div class="p-6 sm:p-8 text-white" style="background: linear-gradient(135deg, #451a03 0%, #78350f 50%, #b45309 100%);">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2.5 mb-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/20 text-white">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-playfair text-base font-bold" style="font-family: 'Playfair Display', serif;">L'Artisan Bakery</p>
                                <p class="text-xs text-amber-300">Invoice Resmi</p>
                            </div>
                        </div>
                        <p class="text-xs uppercase tracking-[0.2em] text-amber-200">Struk / Invoice</p>
                        <h1 class="mt-1.5 font-playfair text-2xl font-bold" style="font-family: 'Playfair Display', serif;">
                            Detail Pesanan
                        </h1>
                    </div>

                    <div class="flex flex-wrap gap-3 sm:flex-col sm:items-end">
                        <div class="rounded-2xl bg-white/15 px-4 py-3 backdrop-blur-sm">
                            <span class="block text-xs text-amber-200">ID Pesanan</span>
                            <span class="mt-1 block text-lg font-black">#{{ $order->id }}</span>
                        </div>
                        <div class="rounded-2xl bg-white/15 px-4 py-3 backdrop-blur-sm">
                            <span class="block text-xs text-amber-200">Status</span>
                            <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold" style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                        </div>
                        <div class="rounded-2xl bg-white/15 px-4 py-3 backdrop-blur-sm">
                            <span class="block text-xs text-amber-200">Tanggal</span>
                            <span class="mt-1 block text-sm font-semibold">{{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                {{-- Status Alerts --}}
                @if($hasPaidAt && in_array($status, ['processing', 'completed', 'paid', 'success'], true))
                    <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pembayaran berhasil. Status: <strong>{{ $statusLabel }}</strong>
                    </div>
                @elseif($hasExpiredAt || in_array($status, ['cancelled', 'canceled', 'failed'], true))
                    <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pembayaran melewati batas waktu. Pesanan dibatalkan otomatis.
                    </div>
                @elseif(in_array($status, ['completed', 'processing'], true))
                    <div class="flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pesanan diproses. Status: <strong>{{ $statusLabel }}</strong>
                    </div>
                @else
                    <div class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Menunggu pembayaran. Status: <strong>Pending</strong>
                    </div>
                @endif

                {{-- Delivery Details --}}
                @if(! empty($checkoutMeta))
                    <div class="rounded-2xl border border-amber-100 p-5" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                        <h2 class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-amber-800">Detail Pengiriman</h2>
                        <div class="grid gap-4 text-sm sm:grid-cols-2">
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wide">Metode</span>
                                <span class="mt-1 block font-semibold text-gray-800">
                                    {{ ($checkoutMeta['delivery_method'] ?? '') === 'pickup' ? 'Ambil di Toko' : 'Pengiriman Instan' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wide">Waktu Ambil</span>
                                <span class="mt-1 block font-semibold text-gray-800">{{ ! empty($checkoutMeta['pickup_time']) ? $checkoutMeta['pickup_time'] : '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wide">Metode Bayar</span>
                                <span class="mt-1 block font-semibold text-gray-800 uppercase">{{ ! empty($checkoutMeta['payment_method']) ? $checkoutMeta['payment_method'] : '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wide">Waktu Bayar</span>
                                <span class="mt-1 block font-semibold text-gray-800">
                                    {{ ! empty($checkoutMeta['paid_at']) ? \Illuminate\Support\Carbon::parse($checkoutMeta['paid_at'])->format('d M Y, H:i') : '—' }}
                                </span>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wide">Alamat Pengiriman</span>
                                <span class="mt-1 block font-semibold text-gray-800 leading-6">{{ ! empty($checkoutMeta['shipping_address']) ? $checkoutMeta['shipping_address'] : '—' }}</span>
                            </div>
                            @if(! empty($checkoutMeta['order_notes']))
                                <div class="sm:col-span-2">
                                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wide">Catatan</span>
                                    <span class="mt-1 block font-semibold text-gray-800">{{ $checkoutMeta['order_notes'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Items Table --}}
                <div class="overflow-hidden rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                            <tr>
                                <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-amber-700">Nama Produk</th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-amber-700">Qty</th>
                                <th class="px-5 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-amber-700">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($orderDetails as $detail)
                                <tr class="transition hover:bg-amber-50/30">
                                    <td class="px-5 py-4 text-sm font-semibold text-gray-800">{{ $detail->bread_name }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600">{{ $detail->quantity }}×</td>
                                    <td class="px-5 py-4 text-right text-sm font-bold text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10 text-center text-sm text-gray-400">Tidak ada detail pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Total & Actions --}}
                <div class="overflow-hidden rounded-2xl border border-amber-200" style="background: linear-gradient(135deg, #fef3e2, #fde8c8);">
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl text-amber-700" style="background: rgba(217,119,6,0.15);">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Total Pembayaran</p>
                                <p class="font-playfair text-2xl font-bold text-amber-900 sm:text-3xl" style="font-family: 'Playfair Display', serif;">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @if(! $hasPaidAt && ! $hasExpiredAt && $status === 'pending')
                                <a href="{{ route('checkout.payment', $order) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-bold text-white transition hover:opacity-90"
                                   style="background: linear-gradient(135deg, #d97706, #b45309);">
                                    Lanjut Bayar
                                </a>
                            @endif
                            <a href="{{ route('account.orders') }}"
                               class="inline-flex items-center justify-center gap-2 rounded-2xl border border-amber-300 bg-white px-5 py-3 text-sm font-semibold text-amber-800 transition hover:bg-amber-50">
                                ← Riwayat Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection