@extends('layouts.app')

@section('title', 'Tambah Anggota')
@section('header_title', 'Tambah Anggota Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Tambah Anggota</h2>
            <p class="text-sm text-gray-500 mt-1">Isi formulir data anggota + buat akun login.</p>
        </div>
        <a href="{{ route('members.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition px-4 py-2 rounded-xl hover:bg-white/60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50/80 backdrop-blur-md border border-red-200 rounded-2xl text-sm text-red-600">
                <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('members.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required autofocus class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition" placeholder="Masukkan nama lengkap">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">NIS / NIP</label>
                    <input type="text" name="nis_nip" value="{{ old('nis_nip') }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition" placeholder="Nomor Induk">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition" placeholder="08xx">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Status Keanggotaan</label>
                    <select name="status" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            
            <!-- separator -->
            <div class="border-t border-gray-200 pt-5 mt-5">
                <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-4">🔐 Akun Login Anggota</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Email (Buat Login)</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition" placeholder="anggota@email.com">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Password (Min. 6 Karakter)</label>
                <input type="password" name="password" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition" placeholder="Password untuk login anggota">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Alamat</label>
                <textarea name="alamat" rows="3" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('members.index') }}" class="px-5 py-3 text-sm text-gray-600 hover:text-gray-900 transition rounded-xl hover:bg-white/60">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection