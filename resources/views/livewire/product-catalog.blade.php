<div class="grid gap-8 lg:grid-cols-[280px_1fr]">
    <aside class="space-y-5 rounded-3xl border border-amber-100 bg-white p-5 shadow-sm lg:sticky lg:top-24 lg:h-fit">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-700">Filter Kategori</p>
            <h3 class="mt-2 text-lg font-black text-gray-900">Saring menu</h3>
            <p class="mt-1 text-sm text-gray-500">Pilih kategori untuk mempersempit daftar produk.</p>
        </div>

        <div class="space-y-2">
            <button
                type="button"
                wire:click="$set('selectedCategory', null)"
                class="w-full rounded-2xl border px-4 py-3 text-left text-sm font-semibold transition {{ $selectedCategory === null ? 'border-amber-800 bg-amber-800 text-white' : 'border-gray-200 bg-white text-gray-700 hover:border-amber-200 hover:bg-amber-50' }}"
            >
                Semua Kategori
            </button>

            @foreach($categories as $category)
                <button
                    type="button"
                    wire:click="$set('selectedCategory', @js($category->name))"
                    class="w-full rounded-2xl border px-4 py-3 text-left transition {{ $selectedCategory === $category->name ? 'border-amber-800 bg-amber-800 text-white' : 'border-gray-200 bg-white text-gray-700 hover:border-amber-200 hover:bg-amber-50' }}"
                >
                    <span class="block text-sm font-semibold">{{ $category->name }}</span>
                    <span class="mt-1 block text-xs {{ $selectedCategory === $category->name ? 'text-amber-100' : 'text-gray-500' }}">
                        {{ $category->breads_count }} produk
                    </span>
                </button>
            @endforeach
        </div>

        <div class="rounded-2xl bg-amber-50 p-4">
            <p class="text-sm font-semibold text-amber-800">Filter aktif</p>
            <p class="mt-1 text-sm text-gray-600">
                {{ $selectedCategory ?: 'Semua kategori' }}
            </p>
        </div>
    </aside>

    <div>
        @if(session('success'))
            <div class="mb-8 rounded-xl border border-green-200 bg-green-100 px-4 py-4 text-green-800" role="alert">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="mx-auto mb-8 max-w-xl lg:max-w-none">
            <label for="pencarian-roti" class="sr-only">Cari roti</label>
            <input
                id="pencarian-roti"
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari nama roti..."
                class="w-full rounded-2xl border border-amber-200 bg-white px-4 py-3 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500"
            >
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-8 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            @forelse($breads as $bread)
                <article class="flex flex-col overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <a href="{{ route('breads.show', $bread->id) }}" class="group block h-32 sm:h-48 overflow-hidden bg-gray-100">
                        <img
                            src="{{ $bread->image_path ? asset('storage/' . $bread->image_path) : asset('images/roti-placeholder.svg') }}"
                            alt="{{ $bread->name }}"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                        >
                    </a>

                    <div class="flex flex-1 flex-col p-3 sm:p-5">
                        <div class="mb-2 sm:mb-3 flex flex-col sm:flex-row sm:items-start justify-between gap-1.5 sm:gap-3">
                            <div>
                                <a href="{{ route('breads.show', $bread->id) }}" class="text-sm sm:text-lg font-bold text-gray-900 transition hover:text-amber-700 line-clamp-1">
                                    {{ $bread->name }}
                                </a>
                                <p class="mt-0.5 sm:mt-1 text-[10px] sm:text-xs uppercase tracking-[0.2em] text-gray-400">
                                    {{ $bread->category->name ?? 'Kategori belum tersedia' }}
                                </p>
                            </div>
                            <span class="w-fit rounded-full bg-amber-50 px-2 py-0.5 sm:py-1 text-[10px] sm:text-xs font-semibold text-amber-700">
                                Stok: {{ $bread->stock }}
                            </span>
                        </div>

                        <p class="mb-3 sm:mb-5 line-clamp-1 sm:line-clamp-2 text-xs sm:text-sm leading-5 sm:leading-6 text-gray-500">
                            {{ $bread->description ?? 'Roti lezat untuk menemani harimu.' }}
                        </p>

                        <div class="mt-auto pt-3 sm:pt-4 border-t border-amber-50">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <div class="flex flex-col">
                                    <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-400 font-bold">Harga</span>
                                    <span class="text-sm sm:text-xl font-black text-amber-700">
                                        Rp {{ number_format($bread->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <a href="{{ route('breads.show', $bread->id) }}" class="hidden sm:flex text-xs font-bold text-amber-800 hover:text-amber-900 items-center gap-1 transition group">
                                    Lihat Detail
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                            @if($bread->stock > 0)
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <div class="flex items-center rounded-xl border border-amber-200 bg-white overflow-hidden shadow-sm justify-between w-full sm:w-auto">
                                        <button
                                            type="button"
                                            wire:click="kurangiJumlah({{ $bread->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="kurangiJumlah,tambahJumlah,tambahKeKeranjang"
                                            class="flex h-9 w-8 sm:w-8 items-center justify-center text-amber-800 hover:bg-amber-50 transition disabled:opacity-50"
                                        >
                                            <span class="text-lg font-bold">-</span>
                                        </button>

                                        <div class="flex h-9 min-w-[32px] items-center justify-center px-1 text-sm font-black text-gray-900 border-x border-amber-100">
                                            {{ $kuantitas[$bread->id] ?? 1 }}
                                        </div>

                                        <button
                                            type="button"
                                            wire:click="tambahJumlah({{ $bread->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="kurangiJumlah,tambahJumlah,tambahKeKeranjang"
                                            class="flex h-9 w-8 sm:w-8 items-center justify-center text-amber-800 hover:bg-amber-50 transition disabled:opacity-50"
                                        >
                                            <span class="text-lg font-bold">+</span>
                                        </button>
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="tambahKeKeranjang({{ $bread->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="tambahKeKeranjang"
                                        class="w-full sm:flex-1 flex items-center justify-center gap-1.5 sm:gap-2 h-9 rounded-xl bg-amber-800 px-3 text-xs font-bold text-white shadow-sm hover:bg-amber-900 active:scale-[0.98] transition disabled:opacity-50"
                                    >
                                        <span wire:loading.remove wire:target="tambahKeKeranjang" class="flex items-center gap-1">
                                            <span class="text-xs sm:text-sm">🛒</span>
                                            <span>Keranjang</span>
                                        </span>
                                        <span wire:loading wire:target="tambahKeKeranjang">
                                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4l3-3-3-3v4a8 8 0 0 0-8 8z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-red-200 bg-red-50 px-3 py-2 text-center text-xs font-semibold text-red-700">
                                    Stok habis
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">
                    <p class="text-lg font-semibold text-gray-900">Roti yang kamu cari belum ditemukan.</p>
                    <p class="mt-2 text-sm text-gray-500">Coba gunakan kata kunci atau kategori lain untuk mempersempit pencarian.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10 flex justify-center">
            <div class="inline-flex rounded-2xl bg-white p-2 shadow-sm ring-1 ring-amber-100">
                {{ $breads->links() }}
            </div>
        </div>
    </div>
</div>