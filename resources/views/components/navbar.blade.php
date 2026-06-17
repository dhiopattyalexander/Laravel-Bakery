<nav id="main-navbar" class="sticky top-0 z-50 border-b border-amber-100/80 bg-white/95 transition-all duration-300" style="backdrop-filter: blur(8px);">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-auto object-contain transition group-hover:scale-105">
                <div class="hidden sm:block">
                    <span class="font-playfair block text-base font-bold leading-tight text-amber-900" style="font-family: 'Playfair Display', serif;">L'Artisan</span>
                    <span class="block text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600">Bakery</span>
                </div>
            </a>

            <!-- Desktop Nav Center -->
            <div class="hidden md:flex items-center gap-1">
                <!-- Dropdown Menu Kategori -->
                <div class="nav-dropdown relative">
                    <button class="flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                        <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Menu
                        <svg class="h-3.5 w-3.5 text-gray-400 transition group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div class="dropdown-menu absolute left-0 top-full mt-2 w-56 overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-xl z-50">
                        <div class="border-b border-amber-50 px-4 py-3">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-700">Kategori Roti</p>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                                <svg class="h-4 w-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Semua Produk
                            </a>
                            @if(isset($navCategories) && $navCategories->isNotEmpty())
                                <div class="my-1.5 border-t border-gray-100"></div>
                                @foreach($navCategories as $cat)
                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm text-gray-600 transition hover:bg-amber-50 hover:text-amber-800">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 shrink-0"></span>
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tentang Kami -->
                <a href="{{ route('tentang-kami') }}" class="rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                    Tentang Kami
                </a>
            </div>

            <!-- Desktop Right -->
            <div class="hidden md:flex items-center gap-3">
                @guest
                    <a href="/login" class="text-sm font-semibold text-gray-600 transition hover:text-amber-800">Masuk</a>
                    <a href="/register" class="rounded-xl px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:opacity-90" style="background: linear-gradient(135deg, #d97706, #b45309);">
                        Daftar Gratis
                    </a>
                @endguest

                @auth
                    <details class="relative">
                        <summary class="flex cursor-pointer list-none items-center gap-2.5 rounded-2xl border border-amber-100 bg-white px-3 py-2 shadow-sm transition hover:border-amber-200 hover:bg-amber-50 hover:shadow">
                            <span class="hidden text-right lg:block">
                                <span class="block text-[10px] font-semibold uppercase tracking-[0.16em] text-amber-600">Halo,</span>
                                <span class="block text-sm font-bold text-gray-800">{{ auth()->user()->name }}</span>
                            </span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-sm font-black text-white" style="background: linear-gradient(135deg, #d97706, #b45309);">
                                {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                            <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </summary>

                            <div class="profile-menu-panel absolute right-0 top-full mt-3 w-64 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl z-50">
                            <div class="border-b border-gray-100 px-5 py-4" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full text-base font-black text-white" style="background: linear-gradient(135deg, #d97706, #b45309);">
                                        {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="mt-0.5 text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2 text-sm font-medium">
                                <a href="{{ route('account.profile') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Edit Profil
                                </a>
                                <a href="{{ route('account.address') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Alamat Saya
                                </a>
                                <a href="{{ route('account.orders') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Riwayat Pesanan
                                </a>

                                @if(auth()->user()->hasAnyRole(['Admin', 'Gudang', 'Kasir']))
                                    <div class="my-1.5 border-t border-gray-100"></div>
                                    <a href="/admin" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                                        <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Panel Admin
                                    </a>
                                @endif

                                <div class="my-1.5 border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-red-600 transition hover:bg-red-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </details>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <button id="mobile-menu-btn" aria-label="Toggle menu" aria-expanded="false"
                    class="flex md:hidden h-10 w-10 items-center justify-center rounded-xl border border-amber-100 bg-amber-50 text-amber-800 transition hover:bg-amber-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden border-t border-amber-100 bg-white">
            <div class="space-y-1 px-2 py-3">
                <!-- Kategori mobile -->
                <button id="mobile-cat-btn" class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Menu Roti
                    </span>
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div id="mobile-cat-list" class="hidden pl-10 space-y-1">
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-gray-600 transition hover:bg-amber-50 hover:text-amber-800">
                        <svg class="h-4 w-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Semua Produk
                    </a>
                    @if(isset($navCategories))
                        @foreach($navCategories as $cat)
                            <a href="{{ route('orders.index') }}" class="block rounded-xl px-3 py-2 text-sm text-gray-600 transition hover:bg-amber-50 hover:text-amber-800">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    @endif
                </div>

                <a href="{{ route('tentang-kami') }}" class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tentang Kami
                </a>

                <div class="my-2 border-t border-amber-50"></div>

                @guest
                    <a href="/login" class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-amber-50 hover:text-amber-800">
                        <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Masuk
                    </a>
                    <a href="/register" class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-bold text-white transition hover:opacity-90" style="background: linear-gradient(135deg, #d97706, #b45309);">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Daftar Gratis
                    </a>
                @endguest

                @auth
                    <div class="rounded-2xl border border-amber-100 p-3 mb-2" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full text-sm font-black text-white" style="background: linear-gradient(135deg, #d97706, #b45309);">
                                {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('account.profile') }}" class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-amber-50">Edit Profil</a>
                    <a href="{{ route('account.orders') }}" class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-amber-50">Riwayat Pesanan</a>
                    @if(auth()->user()->hasAnyRole(['Admin', 'Gudang', 'Kasir']))
                        <a href="/admin" class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-amber-50">Panel Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="px-2">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>