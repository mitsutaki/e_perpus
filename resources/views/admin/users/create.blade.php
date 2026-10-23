@extends('layouts.app')

@section('title', 'Tambah User')
@section('header_title', 'Tambah Akun Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Tambah Akun Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Buat akun baru untuk Admin atau Petugas perpustakaan.</p>
        </div>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition px-4 py-2 rounded-xl hover:bg-white/60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
        <!-- Header Card (Lebih Menonjol) -->
        <div class="bg-slate-900 px-8 py-6 flex items-center gap-4">
            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white">Formulir Akun Baru</h3>
                <p class="text-sm text-slate-400 mt-0.5">Isi data berikut untuk membuat akun pengguna sistem</p>
            </div>
        </div>

        <!-- Body Card -->
        <div class="p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
                    <p class="font-semibold mb-1">Gagal menyimpan, periksa berikut:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Section: Data Diri -->
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200">Data Pengguna</p>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition"
                                   placeholder="Masukkan nama lengkap">
                        </div>
                    </div>
                </div>

                <!-- Section: Kredensial Login -->
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200">Kredensial Login</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition"
                                   placeholder="username unik">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition"
                                   placeholder="email@contoh.com">
                        </div>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Password <span class="text-gray-400 normal-case">(Min. 6 Karakter)</span></label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Section: Role -->
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200">Hak Akses</p>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Role / Jabatan</label>
                        <select name="role" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition">
                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>🧑‍💼 Petugas — Transaksi harian</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>🛡️ Admin — Kelola master data</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-2">Pilih role sesuai tanggung jawab pengguna di sistem.</p>
                    </div>
                </div>

                <!-- Action -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.index') }}" class="px-5 py-3 text-sm text-gray-600 hover:text-gray-900 transition rounded-xl hover:bg-gray-100">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition flex items-center gap-2 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection