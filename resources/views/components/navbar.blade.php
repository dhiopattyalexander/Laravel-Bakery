<nav class="sticky top-0 z-50 border-b border-amber-100 bg-white p-3 sm:p-4 shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 sm:gap-8 px-2 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 sm:gap-8">
            <a href="/" class="font-bold text-lg sm:text-xl text-amber-800 shrink-0">Toko Roti</a>

            <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm font-semibold text-gray-700">
                <a href="{{ url('/') }}" class="rounded-lg px-2.5 py-1.5 transition hover:bg-amber-100 hover:text-amber-800">
                    Beranda
                </a>
                <a href="{{ route('orders.index') }}" class="rounded-lg px-2.5 py-1.5 transition hover:bg-amber-100 hover:text-amber-800">
                    Katalog
                </a>
            </div>
        </div>
        
        <div class="ml-auto flex items-center gap-2 sm:gap-4">
            @guest
                <a href="/login" class="text-xs sm:text-sm text-gray-600 hover:text-amber-800 font-medium">Masuk</a>
                <a href="/register" class="text-xs sm:text-sm px-3 py-1.5 sm:px-4 sm:py-2 bg-amber-800 text-white rounded-lg hover:bg-amber-900 transition">Daftar</a>
            @endguest

            @auth
                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-2 sm:list-none sm:items-center sm:gap-3 rounded-2xl border border-amber-100 bg-white px-2 py-1.5 sm:px-3 sm:py-2 shadow-sm transition hover:border-amber-200 hover:bg-amber-50">
                        <span class="hidden text-right sm:block">
                            <span class="block text-[11px] uppercase tracking-[0.16em] text-gray-500">Halo,</span>
                            <span class="block text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</span>
                        </span>

                        <span class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-amber-800 text-xs sm:text-sm font-black text-white">
                            {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>
                    </summary>

                    <div class="absolute right-0 mt-3 w-64 sm:w-72 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl z-50">
                        <div class="border-b border-gray-100 px-4 py-4">
                            <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="p-2 text-sm font-medium">
                            <a href="{{ route('account.profile') }}" class="block rounded-xl px-3 py-2 text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">Edit Profil</a>
                            <a href="{{ route('account.address') }}" class="block rounded-xl px-3 py-2 text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">Alamat</a>
                            <a href="{{ route('account.orders') }}" class="block rounded-xl px-3 py-2 text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">Riwayat Pesanan</a>

                            @if((auth()->user()->role ?? '') === 'Admin')
                                <a href="/admin" class="block rounded-xl px-3 py-2 text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">Panel Admin</a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="pt-1">
                                @csrf
                                <button type="submit" class="block w-full rounded-xl px-3 py-2 text-left text-red-600 transition hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </details>
            @endauth
        </div>
    </div>
</nav>