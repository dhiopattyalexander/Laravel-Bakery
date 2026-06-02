<div class="grid gap-6 lg:grid-cols-[260px_1fr]">
    {{-- Sidebar Filter --}}
    <aside class="space-y-4 rounded-3xl border border-amber-100 bg-white p-5 shadow-sm lg:sticky lg:top-24 lg:h-fit">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-700">Filter Kategori</p>
            <h3 class="mt-2 font-playfair text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Saring Menu</h3>
            <p class="mt-1 text-sm text-gray-500">Pilih kategori untuk mempersempit pilihan produk.</p>
        </div>

        <div class="space-y-1.5">
            <button
                type="button"
                wire:click="$set('selectedCategory', null)"
                class="w-full rounded-2xl border px-4 py-3 text-left text-sm font-semibold transition {{ $selectedCategory === null ? 'border-amber-700 text-white shadow-sm' : 'border-gray-200 bg-white text-gray-700 hover:border-amber-200 hover:bg-amber-50' }}"
                @if($selectedCategory === null) style="background: linear-gradient(135deg, #d97706, #b45309);" @endif
            >
                🧺 Semua Kategori
            </button>

            @foreach($categories as $category)
                <button
                    type="button"
                    wire:click="$set('selectedCategory', @js($category->name))"
                    class="w-full rounded-2xl border px-4 py-3 text-left transition {{ $selectedCategory === $category->name ? 'border-amber-700 text-white shadow-sm' : 'border-gray-200 bg-white text-gray-700 hover:border-amber-200 hover:bg-amber-50' }}"
                    @if($selectedCategory === $category->name) style="background: linear-gradient(135deg, #d97706, #b45309);" @endif
                >
                    <span class="block text-sm font-semibold">{{ $category->name }}</span>
                    <span class="mt-0.5 block text-xs {{ $selectedCategory === $category->name ? 'text-amber-100' : 'text-gray-400' }}">
                        {{ $category->breads_count }} produk
                    </span>
                </button>
            @endforeach
        </div>

        @if($selectedCategory)
            <div class="rounded-2xl p-4" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                <p class="text-xs font-bold text-amber-700">Filter aktif</p>
                <p class="mt-1 text-sm font-semibold text-gray-800">{{ $selectedCategory }}</p>
            </div>
        @endif
    </aside>

    {{-- Main Content --}}
    <div>
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="mb-6">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    id="pencarian-roti"
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama roti..."
                    class="w-full rounded-2xl border border-amber-200 bg-white py-3.5 pl-11 pr-4 text-sm shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                >
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-2 gap-3 sm:gap-5 xl:grid-cols-3 2xl:grid-cols-4">
            @forelse($breads as $bread)
                <article class="group flex flex-col overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    {{-- Product Image --}}
                    <a href="{{ route('breads.show', $bread->id) }}" class="relative block overflow-hidden bg-amber-50" style="height: 150px;">
                            @php
                                $imgPath = $bread->image_path ?? '';
                                if (\Illuminate\Support\Str::startsWith($imgPath, 'images/')) {
                                    $src = asset($imgPath);
                                } elseif (!empty($imgPath)) {
                                    $src = asset('storage/' . $imgPath);
                                } else {
                                    $src = asset('images/roti-placeholder.svg');
                                }
                            @endphp
                            <img
                                src="{{ $src }}"
                                alt="{{ $bread->name }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            >
                        @if($bread->stock <= 0)
                            <div class="absolute inset-0 flex items-center justify-center bg-black/50">
                                <span class="rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white">Stok Habis</span>
                            </div>
                        @endif
                    </a>

                    {{-- Product Info --}}
                    <div class="flex flex-1 flex-col p-3 sm:p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="min-w-0">
                                <a href="{{ route('breads.show', $bread->id) }}" class="block text-sm sm:text-base font-bold text-gray-900 transition hover:text-amber-700 line-clamp-1">
                                    {{ $bread->name }}
                                </a>
                                <p class="mt-0.5 text-[10px] sm:text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    {{ $bread->category->name ?? '—' }}
                                </p>
                            </div>
                            @if($bread->stock > 0)
                                <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold text-amber-700" style="background: #fef3e2;">
                                    {{ $bread->stock }}
                                </span>
                            @endif
                        </div>

                        <p class="mb-3 line-clamp-2 text-xs leading-5 text-gray-400">
                            {{ $bread->description ?? 'Roti lezat pilihan kami.' }}
                        </p>

                        <div class="mt-auto border-t border-amber-50 pt-3">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm sm:text-lg font-black text-amber-700">
                                    Rp {{ number_format($bread->price, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('breads.show', $bread->id) }}" class="hidden sm:flex text-xs font-semibold text-amber-700 hover:text-amber-900 items-center gap-1 transition">
                                    Detail
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>

                            @if($bread->stock > 0)
                                {{-- Quantity + Cart Button --}}
                                <div class="space-y-2">
                                    {{-- Qty Controls --}}
                                    <div class="flex items-center justify-between rounded-xl border border-amber-200 bg-amber-50 overflow-hidden">
                                        <button
                                            type="button"
                                            wire:click="kurangiJumlah({{ $bread->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="kurangiJumlah,tambahJumlah,tambahKeKeranjang"
                                            class="flex h-9 w-10 items-center justify-center text-amber-800 hover:bg-amber-100 transition font-bold text-lg disabled:opacity-40"
                                        >−</button>

                                        <span class="flex-1 text-center text-sm font-black text-gray-900">
                                            {{ $kuantitas[$bread->id] ?? 1 }}
                                        </span>

                                        <button
                                            type="button"
                                            wire:click="tambahJumlah({{ $bread->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="kurangiJumlah,tambahJumlah,tambahKeKeranjang"
                                            class="flex h-9 w-10 items-center justify-center text-amber-800 hover:bg-amber-100 transition font-bold text-lg disabled:opacity-40"
                                        >+</button>
                                    </div>

                                    {{-- Add to Cart --}}
                                    <button
                                        type="button"
                                        wire:click="tambahKeKeranjang({{ $bread->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="tambahKeKeranjang"
                                        class="flex w-full items-center justify-center gap-1.5 h-9 rounded-xl text-xs font-bold text-white transition hover:opacity-90 active:scale-[0.98] disabled:opacity-50"
                                        style="background: linear-gradient(135deg, #d97706, #b45309);"
                                    >
                                        <span wire:loading.remove wire:target="tambahKeKeranjang" class="flex items-center gap-1.5">
                                            🛒 <span>Keranjang</span>
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
                                <div class="rounded-xl border border-dashed border-red-200 bg-red-50 py-2.5 text-center text-xs font-semibold text-red-600">
                                    Stok Habis
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-12 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-3xl">🔍</div>
                    <p class="text-base font-semibold text-gray-800">Roti tidak ditemukan.</p>
                    <p class="mt-2 text-sm text-gray-500">Coba kata kunci atau kategori yang berbeda.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $breads->links() }}
        </div>
    </div>
</div>