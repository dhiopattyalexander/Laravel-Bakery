@extends('layouts.app')

@section('judul', 'Invoice Pesanan #' . $order->id)

@section('content')
    @if(in_array(strtolower($order->status ?? 'pending'), ['pending', 'processing'], true))
        <script>
            setTimeout(function() {
                window.location.reload();
            }, 10000);
        </script>
    @endif

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Invoice Pesanan #{{ $order->id }}</h2>
    </div>

    <div class="py-12 bg-slate-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <div class="p-8 md:p-10 border-b border-gray-200 bg-gradient-to-r from-slate-900 to-slate-700 text-white">
                    <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-slate-300">Struk / Invoice</p>
                            <h1 class="mt-2 text-3xl font-bold">Detail Pesanan</h1>
                            <p class="mt-2 text-sm text-slate-300">Ringkasan transaksi dan daftar produk dalam pesanan ini.</p>
                        </div>

                        <div class="flex flex-col gap-3 text-sm">
                            <div class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur">
                                <span class="block text-slate-300">ID Pesanan</span>
                                <span class="mt-1 block text-lg font-semibold">#{{ $order->id }}</span>
                            </div>

                            @php
                                $status = strtolower($order->status ?? 'pending');
                                $hasPaidAt = ! empty($checkoutMeta['paid_at']);
                                $hasExpiredAt = ! empty($checkoutMeta['expired_at']);

                                $statusLabel = match ($status) {
                                    'completed' => 'Selesai',
                                    'processing' => 'Processing',
                                    'cancelled', 'canceled', 'failed' => 'Cancelled',
                                    default => 'Pending',
                                };

                                $statusClasses = match ($status) {
                                    'completed', 'paid', 'success' => 'bg-green-100 text-green-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'cancelled', 'canceled', 'failed' => 'bg-red-100 text-red-800',
                                    default => 'bg-slate-100 text-slate-800',
                                };
                            @endphp

                            <div class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur">
                                <span class="block text-slate-300">Status</span>
                                <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur">
                                <span class="block text-slate-300">Tanggal</span>
                                <span class="mt-1 block text-lg font-semibold">
                                    {{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    @if($hasPaidAt && in_array($status, ['processing', 'completed', 'paid', 'success'], true))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                            Berhasil dibayar. Status pesanan saat ini <span class="font-semibold">{{ $statusLabel }}</span>.
                        </div>
                    @elseif($hasExpiredAt || in_array($status, ['cancelled', 'canceled', 'failed'], true))
                        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            Pembayaran melewati batas waktu. Pesanan otomatis dibatalkan.
                        </div>
                    @elseif(in_array($status, ['completed', 'processing'], true))
                        <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700">
                            Pesanan telah diproses. Status saat ini <span class="font-semibold">{{ $statusLabel }}</span>.
                        </div>
                    @else
                        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                            Menunggu pembayaran. Status saat ini <span class="font-semibold">Pending</span>.
                        </div>
                    @endif

                    @if(! empty($checkoutMeta))
                        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">
                            <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-amber-800">Detail Pengiriman</h2>
                            <div class="mt-3 grid gap-3 text-sm text-gray-700 sm:grid-cols-2">
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500">Metode</span>
                                    <span class="mt-1 block font-medium">
                                        {{ ($checkoutMeta['delivery_method'] ?? '') === 'pickup' ? 'Ambil di Toko' : 'Pengiriman Instan' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500">Waktu Ambil</span>
                                    <span class="mt-1 block font-medium">
                                        {{ ! empty($checkoutMeta['pickup_time']) ? $checkoutMeta['pickup_time'] : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500">Metode Bayar</span>
                                    <span class="mt-1 block font-medium uppercase">
                                        {{ ! empty($checkoutMeta['payment_method']) ? $checkoutMeta['payment_method'] : '-' }}
                                    </span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="block text-xs font-semibold text-gray-500">Alamat Pengiriman</span>
                                    <span class="mt-1 block font-medium leading-6">
                                        {{ ! empty($checkoutMeta['shipping_address']) ? $checkoutMeta['shipping_address'] : '-' }}
                                    </span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="block text-xs font-semibold text-gray-500">Catatan Khusus</span>
                                    <span class="mt-1 block font-medium">
                                        {{ ! empty($checkoutMeta['order_notes']) ? $checkoutMeta['order_notes'] : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500">Waktu Bayar</span>
                                    <span class="mt-1 block font-medium">
                                        {{ ! empty($checkoutMeta['paid_at']) ? \Illuminate\Support\Carbon::parse($checkoutMeta['paid_at'])->format('d M Y, H:i') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500">Kadaluarsa Bayar</span>
                                    <span class="mt-1 block font-medium">
                                        {{ ! empty($checkoutMeta['expired_at']) ? \Illuminate\Support\Carbon::parse($checkoutMeta['expired_at'])->format('d M Y, H:i') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="overflow-hidden rounded-2xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama Produk</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jumlah</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($orderDetails as $detail)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $detail->bread_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $detail->quantity }}</td>
                                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                                            Tidak ada detail pesanan yang tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col gap-6 rounded-2xl bg-slate-50 p-6 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Total Belanja</p>
                            <p class="mt-2 text-3xl font-extrabold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            @if(! $hasPaidAt && ! $hasExpiredAt && $status === 'pending')
                                <a href="{{ route('checkout.payment', $order) }}" class="inline-flex items-center justify-center rounded-xl bg-amber-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-800">
                                    Kembali ke Gerbang Pembayaran
                                </a>
                            @endif

                            <a href="{{ route('account.orders') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                                Kembali ke Riwayat Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection