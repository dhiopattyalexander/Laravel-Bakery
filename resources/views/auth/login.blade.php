<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — L'Artisan Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .font-playfair { font-family: 'Playfair Display', Georgia, serif; }
        input:focus { outline: none; border-color: #d97706; box-shadow: 0 0 0 3px rgba(217,119,6,0.15); }
    </style>
</head>
<body class="min-h-screen" style="background: linear-gradient(135deg, #fef9f0 0%, #fef3e2 100%);">

    <div class="flex min-h-screen">
        {{-- Left Panel: Branding --}}
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 text-white" style="background: linear-gradient(135deg, #451a03 0%, #78350f 50%, #b45309 100%);">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-lg">🍞</div>
                <div>
                    <p class="font-playfair text-lg font-bold">L'Artisan Bakery</p>
                    <p class="text-xs text-amber-300">Roti Segar Setiap Hari</p>
                </div>
            </a>

            {{-- Content --}}
            <div>
                <h1 class="font-playfair text-4xl font-bold leading-tight">
                    Selamat Datang<br>Kembali! 👋
                </h1>
                <p class="mt-4 text-base leading-7 text-amber-100">
                    Masuk ke akun kamu untuk melihat riwayat pesanan, mengatur alamat, dan memesan roti favoritmu.
                </p>

                {{-- Features --}}
                <div class="mt-8 space-y-4">
                    @foreach(['Pesan roti segar dengan mudah', 'Lacak status pesanan realtime', 'Kelola alamat pengiriman'] as $feat)
                        <div class="flex items-center gap-3">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-400/30">
                                <svg class="h-3.5 w-3.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-amber-100">{{ $feat }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text-xs text-amber-300/60">&copy; {{ date('Y') }} L'Artisan Bakery</p>
        </div>

        {{-- Right Panel: Form --}}
        <div class="flex w-full flex-col justify-center px-6 py-12 lg:w-1/2 lg:px-16 xl:px-24">
            {{-- Mobile Logo --}}
            <div class="mb-8 flex justify-center lg:hidden">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl text-lg" style="background: linear-gradient(135deg, #d97706, #b45309);">🍞</div>
                    <span class="font-playfair text-xl font-bold text-amber-900">L'Artisan Bakery</span>
                </a>
            </div>

            <div class="mx-auto w-full max-w-sm">
                <div class="mb-8">
                    <h2 class="font-playfair text-3xl font-bold text-gray-900">Masuk Akun</h2>
                    <p class="mt-2 text-sm text-gray-500">Belum punya akun?
                        <a href="/register" class="font-semibold text-amber-700 transition hover:text-amber-900">Daftar gratis</a>
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-5 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/login" class="space-y-5">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="email@contoh.com"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">Password</label>
                        <input type="password" name="password" required
                               placeholder="••••••••"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition">
                    </div>

                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl py-3.5 text-sm font-bold text-white transition hover:opacity-90"
                            style="background: linear-gradient(135deg, #d97706, #b45309);">
                        Masuk Sekarang →
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 transition hover:text-amber-800">
                        ← Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>