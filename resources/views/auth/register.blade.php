<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | L'Artisan Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body class="bg-amber-50 min-h-screen flex items-center justify-center font-[Poppins] py-10">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-amber-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-amber-800 font-[Playfair\ Display]">L'Artisan Bakery</h1>
            <p class="text-gray-500 mt-2 text-sm">Buat akun baru untuk mulai memesan.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
            </div>

            <button type="submit" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-semibold py-2 px-4 rounded-lg transition-colors mt-2">
                Daftar Akun
            </button>
        </form>
        
        <div class="mt-6 text-center text-sm text-gray-600">
            Sudah punya akun? <a href="/login" class="text-amber-700 hover:text-amber-900 font-semibold underline">Masuk di sini</a>
        </div>
        
        <div class="mt-4 text-center text-sm text-gray-600">
            <a href="/orders" class="hover:text-amber-800 underline">← Kembali ke Katalog</a>
        </div>
    </div>

</body>
</html>