@extends('account.layout')

@section('judul', 'Edit Profil')

@section('account_content')
    <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900">Edit Profil</h1>
            <p class="mt-1 text-sm text-gray-600">Perbarui identitas akun kamu.</p>
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

        <form method="POST" action="{{ route('account.profile.update') }}" class="grid gap-5 md:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', optional($profile->birth_date)->format('Y-m-d')) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Gender</label>
                <select name="gender" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
                    <option value="">Pilih gender</option>
                    <option value="Male" {{ old('gender', $profile->gender ?? '') === 'Male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Female" {{ old('gender', $profile->gender ?? '') === 'Female' ? 'selected' : '' }}>Perempuan</option>
                    <option value="Other" {{ old('gender', $profile->gender ?? '') === 'Other' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="rounded-xl bg-amber-800 px-5 py-3 text-sm font-bold text-white transition hover:bg-amber-900">Simpan Profil</button>
            </div>
        </form>
    </div>
@endsection
