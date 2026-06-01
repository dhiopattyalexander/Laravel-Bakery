@extends('account.layout')

@section('judul', "Edit Profil — L'Artisan Bakery")

@section('account_content')
    <div class="overflow-hidden rounded-3xl border border-amber-100 bg-white shadow-sm">
        {{-- Header --}}
        <div class="border-b border-amber-50 px-6 py-5" style="background: linear-gradient(135deg, #fef9f0, #fef3e2);">
            <h1 class="font-playfair text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Edit Profil</h1>
            <p class="mt-1 text-sm text-gray-500">Perbarui informasi identitas akun kamu.</p>
        </div>

        <div class="p-6">
            {{-- Success --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Errors --}}
            @if($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('account.profile.update') }}" class="grid gap-5 md:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                           placeholder="08xx-xxxx-xxxx"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="birth_date"
                           value="{{ old('birth_date', optional($profile->birth_date)->format('Y-m-d')) }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">Gender</label>
                    <select name="gender" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition">
                        <option value="">Pilih gender</option>
                        <option value="Male" {{ old('gender', $profile->gender ?? '') === 'Male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Female" {{ old('gender', $profile->gender ?? '') === 'Female' ? 'selected' : '' }}>Perempuan</option>
                        <option value="Other" {{ old('gender', $profile->gender ?? '') === 'Other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold text-white transition hover:opacity-90"
                            style="background: linear-gradient(135deg, #d97706, #b45309);">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
