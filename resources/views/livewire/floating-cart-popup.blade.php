<div class="fixed bottom-5 right-5 z-50 w-[22rem] max-w-[calc(100vw-1.5rem)]">
    {{-- Cart Toggle Button --}}
    <div class="flex justify-end">
        <button
            type="button"
            wire:click="toggle"
            class="flex items-center gap-2.5 rounded-2xl px-4 py-3 text-white shadow-2xl transition hover:scale-105 hover:shadow-amber-200/60"
            style="background: linear-gradient(135deg, #d97706, #92400e);"
        >
            <span class="relative flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-base">
                🛒
                @if($jumlahItem > 0)
                    <span class="absolute -right-1.5 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-black text-white badge-pop">
                        {{ $jumlahItem }}
                    </span>
                @endif
            </span>
            <span class="text-left">
                <span class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-amber-200">Keranjang</span>
                <span class="block text-sm font-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
            </span>
        </button>
    </div>

    {{-- Popup Panel --}}
    <div class="mt-3 overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-2xl transition-all duration-300 {{ $terbuka ? 'max-h-[42rem] opacity-100' : 'pointer-events-none max-h-0 opacity-0' }}">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Keranjang Belanja</h2>
                <p class="text-xs text-gray-500">{{ $jumlahItem }} item dipilih</p>
            </div>
            <button type="button" wire:click="tutup"
                    class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-gray-500 shadow-sm transition hover:bg-gray-100 hover:text-gray-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="mx-4 mt-3 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-3 py-2 text-xs font-medium text-green-700">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 mt-3 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-700">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Items --}}
        <div class="max-h-[24rem] overflow-y-auto p-4 space-y-2.5">
            @forelse($items as $item)
                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-3">
                    <div class="flex items-start gap-3">
                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-amber-100">
                            <img
                                src="{{ ! empty($item['gambar']) ? asset('storage/' . $item['gambar']) : asset('images/roti-placeholder.svg') }}"
                                alt="{{ $item['nama'] }}"
                                class="h-full w-full object-cover"
                            >
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-bold text-gray-900">{{ $item['nama'] }}</h3>
                            <p class="mt-0.5 text-[11px] text-gray-400">Stok: {{ $item['stok'] }}</p>

                            <div class="mt-2 flex items-center justify-between gap-2">
                                {{-- Qty Controls --}}
                                <div class="flex items-center rounded-lg border border-amber-200 bg-white overflow-hidden">
                                    <button
                                        type="button"
                                        wire:click="kurangi({{ $item['id'] }})"
                                        wire:loading.attr="disabled"
                                        wire:target="kurangi"
                                        class="flex h-7 w-7 items-center justify-center text-amber-800 hover:bg-amber-50 transition font-bold disabled:opacity-40"
                                    >−</button>
                                    <span class="w-7 text-center text-xs font-black text-gray-900">{{ $item['jumlah'] }}</span>
                                    <button
                                        type="button"
                                        wire:click="tambah({{ $item['id'] }})"
                                        wire:loading.attr="disabled"
                                        wire:target="tambah"
                                        class="flex h-7 w-7 items-center justify-center text-amber-800 hover:bg-amber-50 transition font-bold disabled:opacity-40"
                                    >+</button>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs font-bold text-amber-800">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</p>
                                    <button type="button" wire:click="hapus({{ $item['id'] }})"
                                            class="mt-0.5 text-[11px] font-semibold text-red-500 transition hover:text-red-700">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-gray-50 py-10 text-center">
                    <div class="mb-3 text-4xl">🧺</div>
                    <h3 class="text-sm font-bold text-gray-900">Keranjang kosong</h3>
                    <p class="mt-1 text-xs text-gray-500">Pilih roti dari katalog untuk mulai berbelanja.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-100 px-4 py-4" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-600">Total belanja</span>
                <span class="text-base font-black text-gray-900">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
            </div>

            <div class="flex gap-2">
                <button
                    type="button"
                    wire:click="kosongkan"
                    wire:loading.attr="disabled"
                    wire:target="kosongkan"
                    class="flex flex-1 items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 disabled:opacity-50"
                >
                    Kosongkan
                </button>

                @if(! empty($items))
                    <button
                        type="button"
                        wire:click="checkout"
                        wire:loading.attr="disabled"
                        wire:target="checkout"
                        class="flex flex-1 items-center justify-center rounded-xl px-3 py-2.5 text-xs font-bold text-white transition hover:opacity-90 disabled:opacity-50"
                        style="background: linear-gradient(135deg, #d97706, #b45309);"
                    >
                        Checkout →
                    </button>
                @else
                    <button type="button" disabled
                            class="flex flex-1 cursor-not-allowed items-center justify-center rounded-xl bg-amber-200 px-3 py-2.5 text-xs font-bold text-white">
                        Checkout
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>