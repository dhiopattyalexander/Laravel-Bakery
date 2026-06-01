<div class="space-y-4 overflow-hidden rounded-3xl border border-amber-100 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold text-amber-800">Jumlah pesanan</p>
            <p class="mt-0.5 text-xs text-gray-500">Maksimal {{ $bread->stock }} item</p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-bold" style="background: #d1fae5; color: #065f46;">
            Stok: {{ $bread->stock }}
        </span>
    </div>

    @if($bread->stock > 0)
        <div class="flex items-center gap-3">
            <button
                type="button"
                wire:click="kurangiJumlah"
                wire:loading.attr="disabled"
                wire:target="kurangiJumlah,tambahJumlah,tambahKeKeranjang"
                class="flex h-11 w-11 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 text-xl font-bold text-amber-800 transition hover:bg-amber-100 disabled:cursor-not-allowed disabled:opacity-40"
            >−</button>

            <div class="flex h-11 flex-1 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 text-lg font-black text-gray-900">
                {{ $jumlah }}
            </div>

            <button
                type="button"
                wire:click="tambahJumlah"
                wire:loading.attr="disabled"
                wire:target="kurangiJumlah,tambahJumlah,tambahKeKeranjang"
                class="flex h-11 w-11 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 text-xl font-bold text-amber-800 transition hover:bg-amber-100 disabled:cursor-not-allowed disabled:opacity-40"
            >+</button>

            <button
                type="button"
                wire:click="tambahKeKeranjang"
                wire:loading.attr="disabled"
                wire:target="tambahKeKeranjang"
                class="flex flex-1 items-center justify-center gap-2 rounded-2xl h-11 px-4 text-sm font-bold text-white transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-40"
                style="background: linear-gradient(135deg, #d97706, #b45309);"
            >
                <span wire:loading.remove wire:target="tambahKeKeranjang" class="flex items-center gap-2">
                    🛒 Tambah ke Keranjang
                </span>
                <span wire:loading wire:target="tambahKeKeranjang">
                    <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4l3-3-3-3v4a8 8 0 0 0-8 8z"></path>
                    </svg>
                </span>
            </button>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-3 py-2 text-xs font-semibold text-green-700">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif
    @else
        <div class="overflow-hidden rounded-2xl border border-dashed border-red-200 bg-red-50 p-5 text-center">
            <p class="text-2xl">😔</p>
            <p class="mt-2 font-bold text-red-700">Stok Habis</p>
            <p class="mt-1 text-sm text-gray-500">Roti ini sedang tidak tersedia. Coba kembali lagi nanti.</p>
        </div>
    @endif
</div>