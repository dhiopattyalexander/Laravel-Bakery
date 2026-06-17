@extends('layouts.app')

@section('judul', 'Pembayaran QRIS — Order #' . $order->id)
@section('hideFloatingCart', '1')

@section('content')
    @php
        $hasQrisImage = file_exists(public_path('images/qris-full.png'));
    @endphp

    {{-- Breadcrumb & Back button --}}
    <div class="mx-auto max-w-lg mb-6 flex items-center gap-3">
        <button onclick="history.back()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-amber-200 bg-white text-amber-800 shadow-sm transition hover:bg-amber-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
            <a href="{{ url('/') }}" class="transition hover:text-amber-700">Beranda</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('orders.index') }}" class="transition hover:text-amber-700">Katalog</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-800">Pembayaran</span>
        </nav>
    </div>

    <div class="mx-auto max-w-lg space-y-5">
        {{-- Header --}}
        <header class="overflow-hidden rounded-3xl p-6 text-white" style="background: linear-gradient(135deg, #451a03 0%, #78350f 50%, #b45309 100%);">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-200">Langkah 2 dari 2</p>
            <h1 class="mt-2 font-playfair text-2xl font-bold" style="font-family: 'Playfair Display', serif;">
                Pembayaran QRIS
            </h1>
            <p class="mt-1.5 text-sm text-amber-100">Pesanan #{{ $order->id }} — scan QR lalu konfirmasi pembayaran.</p>
        </header>

        {{-- Error Alert --}}
        @if(session('error'))
            <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Paid Alert --}}
        @if($isPaid)
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Pembayaran sudah berhasil! Status: <strong>Processing</strong></span>
            </div>
        @endif

        {{-- Payment Card --}}
        <section class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
            {{-- Amount Header --}}
            <div class="border-b border-amber-50 px-6 py-5 text-center" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-700">Total Pembayaran</p>
                <p class="mt-2 font-playfair text-3xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Metode: QRIS</p>
            </div>

            <div class="p-6 space-y-5">
                {{-- QR Code --}}
                <div class="flex justify-center">
                    @if($hasQrisImage)
                        <div class="overflow-hidden rounded-2xl border-4 border-amber-100 shadow-inner p-2 bg-white" style="height: 280px; width: 280px;">
                            <img
                                src="{{ asset('images/qris-full.png') }}"
                                alt="QRIS pembayaran"
                                class="h-full w-full object-contain"
                            >
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-amber-200 bg-amber-50 p-8 text-center" style="width: 280px; height: 280px;">
                            <span class="text-5xl mb-3">📱</span>
                            <p class="text-sm font-semibold text-amber-800">QR Code Belum Tersedia</p>
                            <p class="mt-2 text-xs text-gray-500">
                                Simpan file gambar ke<br>
                                <code class="font-bold text-gray-700">public/images/qris-full.png</code>
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Countdown --}}
                <div class="overflow-hidden rounded-2xl" style="background: linear-gradient(135deg, #451a03, #b45309);">
                    <div class="p-4 text-center">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-200">⏱ Batas Waktu Pembayaran</p>
                        <p id="payment-countdown" data-seconds="{{ $secondsLeft }}" class="mt-2 font-playfair text-4xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                            10:00
                        </p>
                        <p class="mt-1 text-xs text-amber-200">Selesaikan sebelum waktu habis</p>
                    </div>
                </div>

                {{-- Instructions --}}
                <div class="space-y-2">
                    @foreach(['Buka aplikasi pembayaran (GoPay, OVO, Dana, dll)', 'Pilih menu "Scan QR" atau "Bayar"', 'Scan kode QR di atas', 'Konfirmasi nominal dan selesaikan pembayaran', 'Klik tombol konfirmasi di bawah'] as $i => $step)
                        <div class="flex items-start gap-3">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white" style="background: linear-gradient(135deg, #d97706, #b45309);">{{ $i + 1 }}</span>
                            <p class="text-sm text-gray-600 leading-6">{{ $step }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Confirm Button --}}
                <form method="POST" action="{{ route('checkout.payment.confirm', $order) }}">
                    @csrf
                    <button id="confirm-payment-btn" type="submit"
                            {{ ($isExpired || $isPaid) ? 'disabled' : '' }}
                            class="flex w-full items-center justify-center gap-2 rounded-2xl py-3.5 text-sm font-bold text-white transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50"
                            style="{{ ($isExpired || $isPaid) ? 'background: #9ca3af;' : 'background: linear-gradient(135deg, #059669, #047857);' }}">
                        @if($isPaid)
                            ✓ Sudah Berhasil Dibayar
                        @elseif($isExpired)
                            ✗ Pembayaran Kedaluwarsa
                        @else
                            ✓ Konfirmasi Sudah Bayar
                        @endif
                    </button>
                </form>

                <a href="{{ route('orders.show', $order) }}"
                   class="flex w-full items-center justify-center rounded-2xl border border-gray-200 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                    Lihat Invoice Saja
                </a>
            </div>
        </section>
    </div>

    <script>
        (function () {
            const el = document.getElementById('payment-countdown');
            const confirmBtn = document.getElementById('confirm-payment-btn');
            if (!el) return;

            let secondsLeft = Math.max(0, Math.floor(Number(el.dataset.seconds || 0)));

            const render = () => {
                const s = Math.max(0, Math.floor(secondsLeft));
                const m = String(Math.floor(s / 60)).padStart(2, '0');
                const sec = String(s % 60).padStart(2, '0');
                el.textContent = `${m}:${sec}`;
                // Change color when urgent
                if (s < 60) el.style.color = '#fca5a5';
            };

            render();
            const timer = setInterval(() => {
                secondsLeft -= 1;
                render();
                if (secondsLeft <= 0) {
                    clearInterval(timer);
                    if (confirmBtn) {
                        confirmBtn.disabled = true;
                        confirmBtn.textContent = '✗ Pembayaran Kedaluwarsa';
                        confirmBtn.style.background = '#9ca3af';
                    }
                    setTimeout(() => window.location.reload(), 1200);
                }
            }, 1000);
        })();
    </script>
@endsection
