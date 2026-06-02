@extends('layouts.app')

@section('content')
    {{-- Breadcrumb & Back button --}}
    <div class="mb-6 flex items-center gap-3">
        <button onclick="history.back()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-amber-200 bg-white text-amber-800 shadow-sm transition hover:bg-amber-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
            <a href="{{ url('/') }}" class="transition hover:text-amber-700">Beranda</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600">Akun Saya</span>
            @if(request()->routeIs('account.profile'))
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-800">Edit Profil</span>
            @elseif(request()->routeIs('account.address'))
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-800">Alamat</span>
            @elseif(request()->routeIs('account.orders'))
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-800">Riwayat Pesanan</span>
            @endif
        </nav>
    </div>

    <div class="grid gap-6 lg:grid-cols-[260px_1fr]">
        {{-- Sidebar --}}
        <aside class="lg:sticky lg:top-24 lg:h-fit">
            <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
                {{-- User Card --}}
                <div class="p-5 text-center" style="background: linear-gradient(135deg, #451a03, #b45309);">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full text-2xl font-black text-white shadow-lg" style="background: rgba(255,255,255,0.25);">
                        {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <h2 class="mt-3 font-playfair text-base font-bold text-white" style="font-family: 'Playfair Display', serif;">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="mt-0.5 text-xs text-amber-200 truncate">{{ auth()->user()->email }}</p>
                    @if((auth()->user()->role ?? '') === 'Admin')
                        <span class="mt-2 inline-flex rounded-full bg-amber-400 px-3 py-0.5 text-[11px] font-bold text-amber-900">Admin</span>
                    @endif
                </div>

                {{-- Navigation --}}
                <nav class="p-3 space-y-1 text-sm font-semibold">
                    <a href="{{ route('account.profile') }}"
                       class="flex items-center gap-2.5 rounded-xl px-3.5 py-3 transition {{ request()->routeIs('account.profile') ? 'text-amber-800' : 'text-gray-700 hover:bg-gray-50' }}"
                       @if(request()->routeIs('account.profile')) style="background: linear-gradient(135deg, #fef3e2, #fde8c8);" @endif>
                        <svg class="h-4 w-4 {{ request()->routeIs('account.profile') ? 'text-amber-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Edit Profil
                    </a>
                    <a href="{{ route('account.address') }}"
                       class="flex items-center gap-2.5 rounded-xl px-3.5 py-3 transition {{ request()->routeIs('account.address') ? 'text-amber-800' : 'text-gray-700 hover:bg-gray-50' }}"
                       @if(request()->routeIs('account.address')) style="background: linear-gradient(135deg, #fef3e2, #fde8c8);" @endif>
                        <svg class="h-4 w-4 {{ request()->routeIs('account.address') ? 'text-amber-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Alamat
                    </a>
                    <a href="{{ route('account.orders') }}"
                       class="flex items-center gap-2.5 rounded-xl px-3.5 py-3 transition {{ request()->routeIs('account.orders') ? 'text-amber-800' : 'text-gray-700 hover:bg-gray-50' }}"
                       @if(request()->routeIs('account.orders')) style="background: linear-gradient(135deg, #fef3e2, #fde8c8);" @endif>
                        <svg class="h-4 w-4 {{ request()->routeIs('account.orders') ? 'text-amber-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Riwayat Pesanan
                    </a>

                    @if((auth()->user()->role ?? '') === 'Admin')
                        <div class="my-2 border-t border-gray-100"></div>
                        <a href="/admin" class="flex items-center gap-2.5 rounded-xl px-3.5 py-3 text-gray-700 transition hover:bg-gray-50">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                            Panel Admin
                        </a>
                    @endif

                    <div class="my-2 border-t border-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3.5 py-3 text-left text-red-600 transition hover:bg-red-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        {{-- Main Content --}}
        <section class="min-w-0">
            @yield('account_content')
        </section>
    </div>
@endsection
