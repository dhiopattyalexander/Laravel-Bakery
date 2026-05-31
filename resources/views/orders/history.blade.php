@extends('layouts.app')

@section('judul', 'Riwayat Pesanan')

@section('content')
    @if($orders->contains(fn($o) => in_array(strtolower($o->status ?? 'pending'), ['pending', 'processing'], true)))
        <script>
            setTimeout(function() {
                window.location.reload();
            }, 10000);
        </script>
    @endif

	<div class="mb-6">
		<h2 class="text-2xl font-bold text-gray-900">Riwayat Pesanan</h2>
	</div>

	<div class="py-12 bg-slate-50">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
				<div class="p-6 md:p-8 border-b border-gray-200">
					<h1 class="text-2xl font-bold text-gray-900">Riwayat Pesanan</h1>
					<p class="mt-2 text-sm text-gray-600">Lihat semua pesanan yang pernah kamu buat.</p>

					<form method="GET" action="{{ route('orders.history') }}" class="mt-5 grid gap-3 rounded-2xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-[1fr_220px_auto]">
						<div>
							<label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Cari Pesanan</label>
							<input
								id="q"
								name="q"
								type="text"
								value="{{ $q ?? '' }}"
								placeholder="Nomor order atau nama produk"
								class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
							>
						</div>

						<div>
							<label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Filter Status</label>
							<select id="status" name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none">
								<option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
								<option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
								<option value="paid" {{ ($statusFilter ?? '') === 'paid' ? 'selected' : '' }}>Paid</option>
								<option value="expired" {{ ($statusFilter ?? '') === 'expired' ? 'selected' : '' }}>Expired</option>
								<option value="processing" {{ ($statusFilter ?? '') === 'processing' ? 'selected' : '' }}>Processing</option>
								<option value="completed" {{ ($statusFilter ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
								<option value="cancelled" {{ ($statusFilter ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
							</select>
						</div>

						<div class="flex items-end gap-2">
							<button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-amber-800 px-4 text-sm font-semibold text-white transition hover:bg-amber-900">
								Terapkan
							</button>
							<a href="{{ route('orders.history') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">
								Reset
							</a>
						</div>
					</form>
				</div>

				<div class="p-6 md:p-8">
					@if($orders->isEmpty())
						<div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">
							<h2 class="text-lg font-semibold text-gray-900">Belum ada pesanan</h2>
							<p class="mt-2 text-sm text-gray-600">Saat ini belum ada order yang tercatat untuk akun ini.</p>
						</div>
					@else
						<div class="space-y-5">
							@foreach($orders as $order)
								@php
									$status = strtolower($order->status ?? 'pending');
									$meta = $order->checkoutMeta;
									$isPaid = ! empty($meta?->paid_at) && in_array($status, ['processing', 'completed'], true);
									$isExpired = ! empty($meta?->expired_at) && in_array($status, ['cancelled', 'canceled'], true);

									$label = ucfirst($order->status ?? 'Pending');
									if ($isPaid) {
										$label = 'Paid';
									}
									if ($isExpired) {
										$label = 'Expired';
									}

									$statusClasses = match ($status) {
										'pending' => 'bg-yellow-100 text-yellow-800',
										'processing' => $isPaid ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800',
										'completed', 'paid', 'success' => 'bg-green-100 text-green-800',
										'cancelled', 'canceled', 'failed' => $isExpired ? 'bg-rose-100 text-rose-800' : 'bg-red-100 text-red-800',
										default => 'bg-slate-100 text-slate-800',
									};
								@endphp

								<article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
									<div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
										<div class="flex flex-wrap items-center justify-between gap-3">
											<div class="space-y-1">
												<p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Order</p>
												<p class="text-lg font-bold text-gray-900">#{{ $order->id }}</p>
												<p class="text-sm text-gray-600">{{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}</p>
											</div>

											<div class="flex items-center gap-3">
												<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">{{ $label }}</span>
												<a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700">
													Lihat Invoice
												</a>
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
			</div>
		</div>
	</div>
@endsection
