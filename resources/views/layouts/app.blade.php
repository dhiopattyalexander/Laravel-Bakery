<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="L'Artisan Bakery — Roti segar setiap hari, dipanggang dengan bahan pilihan terbaik.">
    <title>@yield('judul', "L'Artisan Bakery")</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'playfair': ['"Playfair Display"', 'Georgia', 'serif'],
                        'inter': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        cream: {
                            50: '#fef9f0',
                            100: '#fef3e2',
                            200: '#fde8c8',
                        }
                    }
                }
            }
        }
    </script>

    @livewireStyles

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Playfair Display', Georgia, serif; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #fef3e2; }
        ::-webkit-scrollbar-thumb { background: #b45309; border-radius: 6px; }

        /* Navbar scroll effect */
        .navbar-scroll { backdrop-filter: blur(12px); background: rgba(255,255,255,0.92) !important; }

        /* Smooth transitions */
        * { transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); }

        /* Carousel */
        .carousel-slide { display: none; animation: fadeSlide 0.6s ease-in-out; }
        .carousel-slide.active { display: block; }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Dropdown hover */
        .nav-dropdown:hover .dropdown-menu,
        .nav-dropdown:focus-within .dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-menu { opacity: 0; visibility: hidden; transform: translateY(-8px); transition: all 0.2s ease; }

        /* Profile dropdown */
        details[open] > summary::after { display: none; }
        details summary::-webkit-details-marker { display: none; }

        /* Pagination — handled by vendor/pagination/tailwind.blade.php */

        /* Mobile menu */
        #mobile-menu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        #mobile-menu.open { max-height: 600px; }

        /* Input focus */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.12);
        }

        /* Badge animation */
        @keyframes badgePop {
            0% { transform: scale(0.8); }
            50% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }
        .badge-pop { animation: badgePop 0.3s ease; }
    </style>
</head>
<body class="min-h-screen bg-cream-50 text-gray-900 antialiased" style="background-color: #fef9f0;">
    <x-navbar />

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="mt-20 border-t border-amber-100" style="background: linear-gradient(135deg, #451a03 0%, #78350f 50%, #92400e 100%);">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-10 md:grid-cols-[2fr_1fr_1fr_1fr]">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500 text-white text-lg font-bold">🍞</div>
                        <div>
                            <p class="font-playfair text-lg font-bold text-white">L'Artisan Bakery</p>
                            <p class="text-xs text-amber-300">Roti Segar Setiap Hari</p>
                        </div>
                    </div>
                    <p class="text-sm leading-7 text-amber-200/80">
                        Kami hadir dengan roti yang dipanggang segar setiap hari menggunakan bahan pilihan berkualitas terbaik untuk memastikan cita rasa yang konsisten di setiap gigitan.
                    </p>
                    <div class="mt-5 flex items-center gap-3">
                        <a href="https://wa.me/6285888426839" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp Kami
                        </a>
                    </div>
                </div>

                <!-- Menu -->
                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-[0.2em] text-amber-400">Menu</h3>
                    <ul class="space-y-2.5 text-sm text-amber-200/80">
                        <li><a href="{{ url('/') }}" class="transition hover:text-white">Beranda</a></li>
                        <li><a href="{{ route('orders.index') }}" class="transition hover:text-white">Katalog Roti</a></li>
                        @if(isset($navCategories))
                            @foreach($navCategories->take(4) as $cat)
                                <li><a href="{{ route('orders.index') }}?kategori={{ urlencode($cat->name) }}" class="transition hover:text-white">{{ $cat->name }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Akun -->
                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-[0.2em] text-amber-400">Akun</h3>
                    <ul class="space-y-2.5 text-sm text-amber-200/80">
                        @auth
                            <li><a href="{{ route('account.profile') }}" class="transition hover:text-white">Edit Profil</a></li>
                            <li><a href="{{ route('account.orders') }}" class="transition hover:text-white">Riwayat Pesanan</a></li>
                            <li><a href="{{ route('account.address') }}" class="transition hover:text-white">Alamat Saya</a></li>
                        @else
                            <li><a href="/login" class="transition hover:text-white">Masuk</a></li>
                            <li><a href="/register" class="transition hover:text-white">Daftar Akun</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-[0.2em] text-amber-400">Hubungi Kami</h3>
                    <ul class="space-y-3 text-sm text-amber-200/80">
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="https://wa.me/6285888426839" target="_blank" class="transition hover:text-white">0858-8842-6839</a>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jl. Roti Lezat No. 1<br>Kota Kuliner</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Senin – Sabtu<br>07.00 – 20.00 WIB</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 border-t border-white/10 pt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-amber-300/60">&copy; {{ date('Y') }} L'Artisan Bakery. Semua hak dilindungi.</p>
                <p class="text-xs text-amber-300/60">Dibuat dengan ❤️ untuk pecinta roti</p>
            </div>
        </div>
    </footer>

    @if(! View::hasSection('hideFloatingCart'))
        <livewire:floating-cart-popup />
    @endif

    @livewireScripts
    @stack('scripts')

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('main-navbar');
        if (navbar) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    navbar.classList.add('navbar-scroll', 'shadow-lg');
                } else {
                    navbar.classList.remove('navbar-scroll', 'shadow-lg');
                }
            });
        }

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
                const icon = mobileMenuBtn.querySelector('svg');
                mobileMenuBtn.setAttribute('aria-expanded', mobileMenu.classList.contains('open'));
            });
        }

        // Mobile category toggle
        const mobileCatBtn = document.getElementById('mobile-cat-btn');
        const mobileCatList = document.getElementById('mobile-cat-list');
        if (mobileCatBtn && mobileCatList) {
            mobileCatBtn.addEventListener('click', () => {
                mobileCatList.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>