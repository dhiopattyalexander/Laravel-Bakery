<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Roti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-amber-50 font-sans text-gray-900">

    <x-navbar />

    <main class="max-w-6xl mx-auto p-4 md:p-8">
        {{ $slot }}
    </main>

    <livewire:floating-cart-popup />

    @livewireScripts

</body>
</html>