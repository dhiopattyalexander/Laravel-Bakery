<div class="space-y-4 rounded-3xl border border-amber-100 bg-amber-50 p-5">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-amber-800">Jumlah pesanan</p>
            <p class="text-xs text-gray-600">Maksimal {{ $bread->stock }} item</p>
        </div>

        <span class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-amber-800">
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
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-amber-200 bg-white text-base font-bold text-amber-800 transition hover:bg-amber-100 disabled:cursor-not-allowed disabled:opacity-50"
            >
                -
            </button>

            <div class="flex h-10 min-w-16 items-center justify-center rounded-lg border border-amber-200 bg-white px-4 text-base font-black text-gray-900">
                {{ $jumlah }}
            </div>

            <button
                type="button"
                wire:click="tambahJumlah"
                wire:loading.attr="disabled"
                wire:target="kurangiJumlah,tambahJumlah,tambahKeKeranjang"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-amber-200 bg-white text-base font-bold text-amber-800 transition hover:bg-amber-100 disabled:cursor-not-allowed disabled:opacity-50"
            >
                +
            </button>

            <button
                type="button"
                wire:click="tambahKeKeranjang"
                wire:loading.attr="disabled"
                wire:target="tambahKeKeranjang"
                class="inline-flex flex-1 items-center justify-center rounded-lg bg-amber-800 px-4 py-2 text-xs font-semibold text-white transition hover:bg-amber-900 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Tambah ke Keranjang
            </button>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-red-200 bg-white p-5 text-center">
            <p class="font-semibold text-red-700">Stok habis</p>
            <p class="mt-1 text-sm text-gray-500">Roti ini sedang tidak tersedia untuk dibeli.</p>
        </div>
    @endif
</div>