@extends('layouts.app')

@section('title', 'Detail Pinjaman')
@section('header_title', 'Detail Peminjaman')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Detail Pinjaman: {{ $loan->kode_pinjam }}</h2>
            <p class="text-gray-500 text-sm mt-1">Dibuat pada: {{ $loan->tanggal_pinjam->format('d M Y') }}</p>
        </div>
        <a href="{{ route('loans.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 uppercase text-xs tracking-wider">Anggota</p>
                <p class="font-medium text-gray-800 mt-1">{{ $loan->member->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500 uppercase text-xs tracking-wider">Petugas</p>
                <p class="font-medium text-gray-800 mt-1">{{ $loan->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500 uppercase text-xs tracking-wider">Batas Pengembalian</p>
                <p class="font-medium text-gray-800 mt-1">{{ $loan->tanggal_kembali->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500 uppercase text-xs tracking-wider">Status</p>
                <p class="font-medium mt-1"><span class="px-3 py-1 text-xs rounded-full {{ $loan->status == 'dipinjam' ? 'bg-yellow-100/80 text-yellow-700' : 'bg-green-100/80 text-green-700' }}">{{ ucfirst($loan->status) }}</span></p>
            </div>
        </div>

        <div class="border-t border-gray-200/50 pt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Daftar Buku Dipinjam</h3>
            <ul class="space-y-2">
                @foreach($loan->details as $detail)
                    <li class="flex justify-between items-center bg-white/50 p-3 rounded-lg">
                        <span class="text-sm text-gray-800">{{ $detail->book->judul }}</span>
                        <span class="text-xs text-gray-500">Kondisi: {{ ucfirst($detail->kondisi) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection