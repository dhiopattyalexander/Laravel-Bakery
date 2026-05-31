@extends('layouts.app')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[260px_1fr]">
        <aside class="rounded-3xl border border-amber-100 bg-white p-5 shadow-sm">
            <div class="mb-5 rounded-2xl bg-amber-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-700">Akun Saya</p>
                <h2 class="mt-1 text-lg font-extrabold text-gray-900">{{ auth()->user()->name }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ auth()->user()->email }}</p>
            </div>

            <nav class="space-y-2 text-sm font-medium">
                <a href="{{ route('account.profile') }}" class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('account.profile') ? 'bg-amber-100 text-amber-800' : 'text-gray-700 hover:bg-gray-50' }}">Edit Profil</a>
                <a href="{{ route('account.address') }}" class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('account.address') ? 'bg-amber-100 text-amber-800' : 'text-gray-700 hover:bg-gray-50' }}">Alamat</a>
                <a href="{{ route('account.orders') }}" class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('account.orders') ? 'bg-amber-100 text-amber-800' : 'text-gray-700 hover:bg-gray-50' }}">Riwayat Pesanan</a>
                @if((auth()->user()->role ?? '') === 'Admin')
                    <a href="/admin" class="block rounded-xl px-4 py-3 transition text-gray-700 hover:bg-gray-50">Panel Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full rounded-xl px-4 py-3 text-left font-semibold text-red-600 transition hover:bg-red-50">Logout</button>
                </form>
            </nav>
        </aside>

        <section class="min-w-0">
            @yield('account_content')
        </section>
    </div>
@endsection
