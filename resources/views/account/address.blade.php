@extends('account.layout')

@section('judul', "Alamat Saya — L'Artisan Bakery")

@section('account_content')
    <div class="space-y-5">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Alamat Profil Utama --}}
        <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
            <div class="border-b border-amber-50 px-6 py-5" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                <h1 class="font-playfair text-xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Alamat Utama</h1>
                <p class="mt-1 text-sm text-gray-500">Dipakai sebagai fallback checkout bila belum ada alamat tersimpan.</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('account.address.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" rows="4"
                                  class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none resize-none transition">{{ old('address', $profile->address ?? '') }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold text-white transition hover:opacity-90"
                                style="background: linear-gradient(135deg, #d97706, #b45309);">
                            Simpan Alamat Utama
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tambah Alamat Baru --}}
        <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
            <div class="border-b border-amber-50 px-6 py-5" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                <h2 class="font-playfair text-xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Tambah Alamat Baru</h2>
                <p class="mt-1 text-sm text-gray-500">Tambahkan alamat rumah, kantor, atau lokasi lain.</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('account.address.store') }}" class="grid gap-5 md:grid-cols-2">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">Label Alamat</label>
                        <input type="text" name="label" value="{{ old('label') }}" placeholder="Contoh: Rumah, Kantor"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">Nama Penerima</label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">Jadikan Default</label>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 transition hover:border-amber-200 hover:bg-amber-50">
                            <input type="checkbox" name="is_default" value="1"
                                   class="h-4 w-4 rounded border-gray-300 text-amber-700 focus:ring-amber-500">
                            <span>Jadikan alamat utama checkout</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" rows="4"
                                  class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none resize-none transition">{{ old('address') }}</textarea>
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold text-white transition hover:opacity-90"
                                style="background: linear-gradient(135deg, #d97706, #b45309);">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Simpan Alamat Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Daftar Alamat Tersimpan --}}
        <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
            <div class="border-b border-amber-50 px-6 py-5" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
                <h2 class="font-playfair text-xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Alamat Tersimpan</h2>
                <p class="mt-1 text-sm text-gray-500">Checkout akan memakai alamat default secara otomatis.</p>
            </div>
            <div class="p-6">
                @if($addresses->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 py-10 text-center">
                        <div class="mx-auto mb-3 text-3xl">📍</div>
                        <p class="text-sm text-gray-500">Belum ada alamat tersimpan selain alamat profil utama.</p>
                    </div>
                @else
                    <div class="grid gap-4 lg:grid-cols-2">
                        @foreach($addresses as $address)
                            <article class="overflow-hidden rounded-2xl border {{ $address->is_default ? 'border-amber-300' : 'border-gray-200' }} bg-white">
                                @if($address->is_default)
                                    <div class="px-4 py-2 text-xs font-bold text-amber-800" style="background: linear-gradient(135deg, #fef3e2, #fde8c8);">
                                        ⭐ Alamat Default
                                    </div>
                                @endif
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="font-bold text-gray-900">{{ $address->label }}</h3>
                                            <p class="mt-1 text-sm font-semibold text-gray-700">{{ $address->recipient_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                                            <p class="mt-2 text-sm leading-6 text-gray-600">{{ $address->address }}</p>
                                        </div>
                                        <div class="flex flex-col gap-2 shrink-0">
                                            @unless($address->is_default)
                                                <form method="POST" action="{{ route('account.address.default', $address) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-bold text-amber-800 transition hover:bg-amber-100">
                                                        Default
                                                    </button>
                                                </form>
                                            @endunless
                                            <form method="POST" action="{{ route('account.address.delete', $address) }}" onsubmit="return confirm('Hapus alamat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
