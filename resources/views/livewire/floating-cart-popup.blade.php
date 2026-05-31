<div class="fixed bottom-4 right-4 z-50 w-[20rem] max-w-[calc(100vw-2rem)]">
    <button
        type="button"
        wire:click="toggle"
        class="ml-auto flex items-center gap-2 rounded-full bg-amber-800 px-3 py-2 text-white shadow-2xl transition hover:bg-amber-900"
    >
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/10 text-xs font-black">
            🛒
        </span>
        <span class="text-left">
            <span class="block text-[10px] uppercase tracking-[0.18em] text-amber-100">Keranjang</span>
            <span class="block text-xs font-bold">{{ $jumlahItem }} item</span>
        </span>
        <span class="rounded-full bg-white px-2.5 py-1 text-xs font-black text-amber-800">
            Rp {{ number_format($totalHarga, 0, ',', '.') }}
        </span>
    </button>

    <div class="mt-3 overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-2xl transition-all duration-300 {{ $terbuka ? 'max-h-[38rem] opacity-100' : 'pointer-events-none max-h-0 opacity-0' }}">
        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
            <div>
                <h2 class="text-base font-bold text-gray-900">Keranjang Belanja</h2>
                <p class="text-xs text-gray-500">Kelola roti yang sudah kamu pilih.</p>
            </div>

                <button type="button" wire:click="tutup" class="rounded-full px-2.5 py-1.5 text-xs font-semibold text-gray-500 transition hover:bg-gray-100 hover:text-gray-800">
                Tutup
            </button>
        </div>

        @if(session('success'))
            <div class="mx-5 mt-4 rounded-xl border border-green-200 bg-green-50 px-3 py-2 text-xs font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-5 mt-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-h-[28rem] overflow-y-auto p-5">
            @forelse($items as $item)
                <div class="mb-3 rounded-2xl border border-gray-200 bg-gray-50 p-3 last:mb-0">
                    <div class="flex items-start gap-3">
                        <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-gray-200">
                            <img
                                src="{{ ! empty($item['gambar']) ? asset('storage/' . $item['gambar']) : asset('images/roti-placeholder.svg') }}"
                                alt="{{ $item['nama'] }}"
                                class="h-full w-full object-cover"
                            >
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-bold text-gray-900">{{ $item['nama'] }}</h3>
                            <p class="mt-1 text-[11px] text-gray-500">Stok tersisa: {{ $item['stok'] }}</p>

                            <div class="mt-2 flex items-center justify-between gap-2">
                                <div class="flex items-center rounded-lg border border-gray-200 bg-white">
                                    <button
                                        type="button"
                                        wire:click="kurangi({{ $item['id'] }})"
                                        wire:loading.attr="disabled"
                                        wire:target="kurangi"
                                        class="px-2 py-1 text-gray-700 transition hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 text-xs"
                                    >
                                        -
                                    </button>
                                    <span class="min-w-7 px-1.5 py-1 text-center text-xs font-bold text-gray-900">{{ $item['jumlah'] }}</span>
                                    <button
                                        type="button"
                                        wire:click="tambah({{ $item['id'] }})"
                                        wire:loading.attr="disabled"
                                        wire:target="tambah"
                                        class="px-2 py-1 text-gray-700 transition hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 text-xs"
                                    >
                                        +
                                    </button>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs font-bold text-amber-800">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</p>
                                    <button type="button" wire:click="hapus({{ $item['id'] }})" class="mt-1 text-[11px] font-semibold text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-gray-300 bg-gray-50 p-7 text-center">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-lg text-amber-700">
                        🧺
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Keranjang Anda masih kosong</h3>
                    <p class="mt-2 text-sm text-gray-600">Silakan pilih roti dari katalog atau halaman detail produk.</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-gray-200 bg-white px-4 py-3">
            <div class="flex items-center justify-between text-xs text-gray-600">
                <span>Total belanja</span>
                <span class="font-bold text-gray-900">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
            </div>

            <div class="mt-3 flex gap-2">
                <button
                    type="button"
                    wire:click="kosongkan"
                    wire:loading.attr="disabled"
                    wire:target="kosongkan"
                    class="inline-flex flex-1 items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Kosongkan
                </button>

                @if(! empty($items))
                    <button
                        type="button"
                        wire:click="checkout"
                        wire:loading.attr="disabled"
                        wire:target="checkout"
                        class="inline-flex flex-1 items-center justify-center rounded-xl bg-amber-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-900 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Lanjut ke Checkout
                    </button>
                @else
                    <button
                        type="button"
                        disabled
                        class="inline-flex flex-1 cursor-not-allowed items-center justify-center rounded-xl bg-amber-300 px-3 py-2 text-xs font-semibold text-white"
                    >
                        Checkout Sekarang
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>