@extends('account.layout')

@section('judul', 'Alamat')

@section('account_content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900">Alamat</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola alamat utama dan tambahkan alamat pengiriman lebih dari satu.</p>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('account.address.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Alamat Profil Utama</label>
                    <textarea name="address" rows="4" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">{{ old('address', $profile->address ?? '') }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">Alamat ini dipakai sebagai fallback untuk checkout bila belum ada alamat tersimpan.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-amber-800 px-5 py-3 text-sm font-bold text-white transition hover:bg-amber-900">Simpan Alamat Profil</button>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-extrabold text-gray-900">Tambahkan Alamat Baru</h2>
                <p class="mt-1 text-sm text-gray-600">Gunakan label agar mudah memilih alamat rumah, kantor, atau alamat lain.</p>
            </div>

            <form method="POST" action="{{ route('account.address.store') }}" class="grid gap-5 md:grid-cols-2">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Label Alamat</label>
                    <input type="text" name="label" value="{{ old('label') }}" placeholder="Contoh: Rumah" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Nama Penerima</label>
                    <input type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Jadikan Default</label>
                    <label class="flex items-center gap-3 rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-700">
                        <input type="checkbox" name="is_default" value="1" class="h-4 w-4 rounded border-gray-300 text-amber-800 focus:ring-amber-500">
                        <span>Gunakan sebagai alamat utama checkout</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Alamat Lengkap</label>
                    <textarea name="address" rows="5" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">{{ old('address') }}</textarea>
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="rounded-xl bg-amber-800 px-5 py-3 text-sm font-bold text-white transition hover:bg-amber-900">Simpan Alamat Baru</button>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-extrabold text-gray-900">Daftar Alamat Tersimpan</h2>
                <p class="mt-1 text-sm text-gray-600">Checkout akan otomatis memakai alamat default di sini.</p>
            </div>

            @if($addresses->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">
                    <p class="text-sm text-gray-600">Belum ada alamat tersimpan selain alamat profil utama.</p>
                </div>
            @else
                <div class="grid gap-4 lg:grid-cols-2">
                    @foreach($addresses as $address)
                        <article class="rounded-2xl border border-gray-200 p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-base font-bold text-gray-900">{{ $address->label }}</h3>
                                        @if($address->is_default)
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-[0.14em] text-amber-800">Default</span>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-sm font-semibold text-gray-700">{{ $address->recipient_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                                </div>

                                <div class="flex flex-col gap-2">
                                    @unless($address->is_default)
                                        <form method="POST" action="{{ route('account.address.default', $address) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-800 transition hover:bg-amber-100">Jadikan Default</button>
                                        </form>
                                    @endunless

                                    <form method="POST" action="{{ route('account.address.delete', $address) }}" onsubmit="return confirm('Hapus alamat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                                    </form>
                                </div>
                            </div>

                            <p class="mt-4 text-sm leading-6 text-gray-600">{{ $address->address }}</p>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
