@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('header_title', 'Pengaturan Sistem')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-light tracking-tight">Pengaturan Sistem</h2>
        <p class="text-gray-500 text-sm mt-1">Konfigurasi global aplikasi perpustakaan.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 rounded-xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Nama Perpustakaan</label>
                    <input type="text" name="nama_perpustakaan" value="{{ $settings['nama_perpustakaan'] ?? 'Perpus Digital' }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Denda per Hari (Rp)</label>
                    <input type="number" name="denda_per_hari" value="{{ $settings['denda_per_hari'] ?? 1000 }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Maksimal Hari Pinjam</label>
                    <input type="number" name="maksimal_hari_pinjam" value="{{ $settings['maksimal_hari_pinjam'] ?? 7 }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Maksimal Buku per Transaksi</label>
                    <input type="number" name="maksimal_jumlah_buku" value="{{ $settings['maksimal_jumlah_buku'] ?? 3 }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
@endsection