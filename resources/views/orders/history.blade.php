@extends('layouts.app')

@section('judul', "Riwayat Pesanan — L'Artisan Bakery")

@section('content')
    @if($orders->contains(fn($o) => in_array(strtolower($o->status ?? 'pending'), ['pending', 'processing'], true)))
        <script>setTimeout(function() { window.location.reload(); }, 10000);</script>
    @endif

    {{-- Breadcrumb & Back button --}}
    <div class="mb-6 flex items-center gap-3">
        <button onclick="history.back()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-amber-200 bg-white text-amber-800 shadow-sm transition hover:bg-amber-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
            <a href="{{ url('/') }}" class="transition hover:text-amber-700">Beranda</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-800">Riwayat Pesanan</span>
        </nav>
    </div>

    {{-- Page Header --}}
    <div class="mb-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-700">Akun Saya</p>
        <h1 class="mt-2 font-playfair text-3xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Riwayat Pesanan</h1>
        <p class="mt-1.5 text-sm text-gray-500">Semua pesanan yang pernah kamu buat.</p>
    </div>

    {{-- Filter Bar --}}
    <div class="mb-6 overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm">
        <form method="GET" action="{{ route('orders.history') }}" class="grid gap-3 p-4 md:grid-cols-[1fr_220px_auto]">
            <div>
                <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Cari Pesanan</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input id="q" name="q" type="text" value="{{ $q ?? '' }}"
                           placeholder="Nomor order atau nama produk"
                           class="w-full rounded-xl border border-gray-200 pl-9 pr-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
                <select id="status" name="status" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    <option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ ($statusFilter ?? '') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="expired" {{ ($statusFilter ?? '') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="processing" {{ ($statusFilter ?? '') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ ($statusFilter ?? '') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ ($statusFilter ?? '') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl px-4 text-sm font-bold text-white transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #d97706, #b45309);">
                    Terapkan
                </button>
                <a href="{{ route('orders.history') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-gray-200 bg-white px-4 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Orders List --}}
    @if($orders->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-12 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-3xl">📋</div>
            <h2 class="text-lg font-bold text-gray-900">Belum ada pesanan</h2>
            <p class="mt-2 text-sm text-gray-500">Mulai berbelanja untuk membuat pesanan pertamamu!</p>
            <a href="{{ route('orders.index') }}" class="mt-5 inline-flex items-center gap-2 rounded-2xl px-6 py-3 text-sm font-bold text-white transition hover:opacity-90"
               style="background: linear-gradient(135deg, #d97706, #b45309);">
                Lihat Katalog
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    $status = strtolower($order->status ?? 'pending');
                    $meta = $order->checkoutMeta;
                    $isPaid = ! empty($meta?->paid_at) && in_array($status, ['processing', 'completed'], true);
                    $isExpired = ! empty($meta?->expired_at) && in_array($status, ['cancelled', 'canceled'], true);

                    $label = ucfirst($order->status ?? 'Pending');
                    if ($isPaid) $label = 'Paid';
                    if ($isExpired) $label = 'Expired';
                    if ($status === 'completed') $label = 'Selesai';

                    $statusStyle = match (true) {
                        $status === 'pending'  => 'background:#fef3c7;color:#92400e;',
                        $isPaid                => 'background:#d1fae5;color:#065f46;',
                        $status === 'processing' => 'background:#dbeafe;color:#1e40af;',
                        $status === 'completed' => 'background:#d1fae5;color:#065f46;',
                        $isExpired             => 'background:#ffe4e6;color:#9f1239;',
                        in_array($status, ['cancelled','canceled','failed'], true) => 'background:#fee2e2;color:#991b1b;',
                        default                => 'background:#f1f5f9;color:#475569;',
                    };
                @endphp

                <article class="overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm">
                    {{-- Article Header --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-amber-50 px-5 py-4" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Order</p>
                            <p class="text-base font-black text-gray-900">#{{ $order->id }}</p>
                            <p class="mt-0.5 text-xs text-gray-500">{{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="{{ $statusStyle }}">{{ $label }}</span>
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-white transition hover:opacity-90"
                               style="background: linear-gradient(135deg, #d97706, #b45309);">
                                📄 Invoice
                            </a>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="p-5">
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Produk</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Qty</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-400">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse($order->items as $item)
                                        <tr class="transition hover:bg-amber-50/30">
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $item->bread->name ?? 'Produk tidak ditemukan' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-500">{{ $item->quantity }}×</td>
                                            <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-4 py-4 text-center text-sm text-gray-400">Detail item belum tersedia.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex items-center justify-between rounded-2xl border border-amber-200 px-5 py-3.5" style="background: linear-gradient(135deg, #fef3e2, #fde8c8);">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                                <span class="text-xs font-bold uppercase tracking-wide text-amber-700">Total Pembayaran</span>
                            </div>
                            <span class="font-playfair text-lg font-bold text-amber-900" style="font-family: 'Playfair Display', serif;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
