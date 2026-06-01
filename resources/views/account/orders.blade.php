@extends('account.layout')

@section('judul', "Riwayat Pesanan — L'Artisan Bakery")

@section('account_content')
    @if($orders->contains(fn($o) => in_array(strtolower($o->status ?? 'pending'), ['pending', 'processing'], true)))
        <script>setTimeout(function() { window.location.reload(); }, 10000);</script>
    @endif

    <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
        {{-- Header --}}
        <div class="border-b border-amber-50 px-6 py-5" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
            <h1 class="font-playfair text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Riwayat Pesanan</h1>
            <p class="mt-1 text-sm text-gray-500">Semua pesanan yang pernah kamu buat.</p>
        </div>

        <div class="p-6">
            @if($orders->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 py-12 text-center">
                    <div class="mx-auto mb-4 text-4xl">📋</div>
                    <h2 class="text-base font-bold text-gray-800">Belum ada pesanan</h2>
                    <p class="mt-2 text-sm text-gray-500">Yuk mulai belanja roti favorit kamu!</p>
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
                            $isPaid = ! empty($meta?->paid_at) && in_array($status, ['processing', 'completed', 'paid', 'success'], true);
                            $isExpired = ! empty($meta?->expired_at) && in_array($status, ['cancelled', 'canceled', 'failed'], true);

                            $statusLabel = match ($status) {
                                'completed'                       => 'Selesai',
                                'processing'                      => 'Processing',
                                'cancelled', 'canceled', 'failed' => 'Dibatalkan',
                                default                           => 'Pending',
                            };
                            if ($isPaid && $status !== 'completed') $statusLabel = 'Processing';
                            if ($status === 'completed') $statusLabel = 'Selesai';
                            if ($isExpired) $statusLabel = 'Expired';

                            $statusStyle = match (true) {
                                $status === 'pending'   => 'background:#fef3c7;color:#92400e;',
                                $isPaid                 => 'background:#d1fae5;color:#065f46;',
                                $status === 'processing' => 'background:#dbeafe;color:#1e40af;',
                                $status === 'completed' => 'background:#d1fae5;color:#065f46;',
                                $isExpired              => 'background:#ffe4e6;color:#9f1239;',
                                in_array($status, ['cancelled','canceled','failed'], true) => 'background:#fee2e2;color:#991b1b;',
                                default                 => 'background:#f1f5f9;color:#475569;',
                            };
                        @endphp

                        <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Order #{{ $order->id }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                                    <a href="{{ route('orders.show', $order->id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-white transition hover:opacity-90"
                                       style="background: linear-gradient(135deg, #d97706, #b45309);">
                                        📄 Invoice
                                    </a>
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="overflow-x-auto rounded-xl border border-gray-100">
                                    <table class="min-w-full divide-y divide-gray-100">
                                        <thead class="bg-gray-50">
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
        </div>
    </div>
@endsection
