@extends('layouts.app')

@section('judul', 'Pembayaran QRIS')
@section('hideFloatingCart', '1')

@section('content')
    @php
        $hasQrisImage = file_exists(public_path('images/qris-full.png'));
    @endphp

    <div class="mx-auto max-w-2xl space-y-6">
        <header class="rounded-3xl border border-amber-200 bg-gradient-to-r from-amber-700 via-amber-800 to-orange-700 p-6 text-white shadow">
            <p class="text-xs uppercase tracking-[0.22em] text-amber-100">Pembayaran</p>
            <h1 class="mt-2 text-3xl font-black">Bayar Pesanan #{{ $order->id }}</h1>
            <p class="mt-2 text-sm text-amber-100">Scan QRIS, selesaikan pembayaran, lalu konfirmasi untuk lanjut ke invoice.</p>
        </header>

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($isPaid)
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                Pembayaran sudah berhasil. Status pesanan: <span class="font-semibold">Processing</span>.
            </div>
        @endif

        <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="text-center">
                <h2 class="text-lg font-bold text-gray-900">Metode: QRIS</h2>
                <p class="mt-1 text-sm text-gray-500">Total pembayaran: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>

            <div class="mt-6 flex justify-center">
                @if($hasQrisImage)
                    <div class="h-[340px] w-[340px] overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-inner">
                        <img
                            src="{{ asset('images/qris-full.png') }}"
                            alt="QRIS pembayaran"
                            class="max-w-none"
                            style="width: 768px; height: 1118px; transform: translate(-108px, -300px);"
                        >
                    </div>
                @else
                    <div class="w-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center text-sm text-gray-600">
                        File QRIS belum ada. Simpan gambar full ke
                        <span class="font-semibold text-gray-900">public/images/qris-full.png</span>
                        agar area QR otomatis di-crop dan tampil di sini.
                    </div>
                @endif
            </div>

            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-center">
                <p class="text-xs uppercase tracking-[0.18em] text-amber-700">Batas Waktu Pembayaran</p>
                <p id="payment-countdown" data-seconds="{{ $secondsLeft }}" class="mt-2 text-3xl font-black text-amber-900">10:00</p>
                <p class="mt-1 text-xs text-amber-700">Selesaikan pembayaran sebelum waktu habis.</p>
            </div>

            <form method="POST" action="{{ route('checkout.payment.confirm', $order) }}" class="mt-6">
                @csrf
                <button id="confirm-payment-btn" type="submit" {{ ($isExpired || $isPaid) ? 'disabled' : '' }} class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-800 disabled:cursor-not-allowed disabled:bg-gray-400">
                    {{ $isPaid ? 'Sudah Berhasil Dibayar' : ($isExpired ? 'Pembayaran Kedaluwarsa' : 'Konfirmasi Pembayaran') }}
                </button>
            </form>

            <a href="{{ route('orders.show', $order) }}" class="mt-3 inline-flex w-full items-center justify-center rounded-xl border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Lihat Invoice Tanpa Konfirmasi
            </a>
        </section>
    </div>

    <script>
        (function () {
            const el = document.getElementById('payment-countdown');
            const confirmBtn = document.getElementById('confirm-payment-btn');
            if (!el) return;

            let secondsLeft = Math.max(0, Math.floor(Number(el.dataset.seconds || 0)));

            const render = () => {
                const safeSeconds = Math.max(0, Math.floor(secondsLeft));
                const minutes = String(Math.floor(safeSeconds / 60)).padStart(2, '0');
                const seconds = String(safeSeconds % 60).padStart(2, '0');
                el.textContent = `${minutes}:${seconds}`;
            };

            render();

            const timer = setInterval(() => {
                secondsLeft -= 1;
                render();

                if (secondsLeft <= 0) {
                    clearInterval(timer);
                    if (confirmBtn) {
                        confirmBtn.disabled = true;
                        confirmBtn.textContent = 'Pembayaran Kedaluwarsa';
                    }

                    // Reload sekali agar server bisa menandai order pending menjadi cancelled.
                    window.setTimeout(() => window.location.reload(), 1200);
                }
            }, 1000);
        })();
    </script>
@endsection
