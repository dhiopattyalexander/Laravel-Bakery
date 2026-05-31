@extends('account.layout')

@section('judul', 'Riwayat Pesanan')

@section('account_content')
    @if($orders->contains(fn($o) => in_array(strtolower($o->status ?? 'pending'), ['pending', 'processing'], true)))
        <script>
            setTimeout(function() {
                window.location.reload();
            }, 10000);
        </script>
    @endif

    <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900">Riwayat Pesanan</h1>
            <p class="mt-1 text-sm text-gray-600">Semua pesanan yang pernah kamu buat.</p>
        </div>

        @if($orders->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">
                <p class="text-sm text-gray-600">Belum ada pesanan.</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($orders as $order)
                    @php
                        $status = strtolower($order->status ?? 'pending');
                        $meta = $order->checkoutMeta;
                        $isPaid = ! empty($meta?->paid_at) && in_array($status, ['processing', 'completed', 'paid', 'success'], true);
                        $isExpired = ! empty($meta?->expired_at) && in_array($status, ['cancelled', 'canceled', 'failed'], true);

                        $statusLabel = match ($status) {
                            'completed' => 'Selesai',
                            'processing' => 'Processing',
                            'cancelled', 'canceled', 'failed' => 'Cancelled',
                            default => 'Pending',
                        };

                        if ($isPaid && $status !== 'completed') {
                            $statusLabel = 'Processing';
                        }

                        if ($status === 'completed') {
                            $statusLabel = 'Selesai';
                        }

                        if ($isExpired) {
                            $statusLabel = 'Expired';
                        }

                        $statusClasses = match ($status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => $isPaid ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800',
                            'completed', 'paid', 'success' => 'bg-green-100 text-green-800',
                            'cancelled', 'canceled', 'failed' => $isExpired ? 'bg-rose-100 text-rose-800' : 'bg-red-100 text-red-800',
                            default => 'bg-slate-100 text-slate-800',
                        };
                    @endphp

                    <article class="overflow-hidden rounded-2xl border border-gray-200">
                        <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Order #{{ $order->id }}</p>
                                    <p class="text-sm text-gray-600">{{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">{{ $statusLabel }}</span>
                                    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700">Lihat Invoice</a>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Produk</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kuantitas</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @forelse($order->items as $item)
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->bread->name ?? 'Produk tidak ditemukan' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">{{ $item->quantity }}</td>
                                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">Detail item belum tersedia.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                                <span class="text-sm font-medium text-gray-600">Total Pesanan</span>
                                <span class="text-lg font-black text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
