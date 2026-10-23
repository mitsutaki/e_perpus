@extends('layouts.app')

@section('title', 'Data Peminjaman')
@section('header_title', 'Transaksi Peminjaman')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Data Peminjaman</h2>
            <p class="text-gray-500 text-sm mt-1">Riwayat transaksi peminjaman buku.</p>
        </div>
        <a href="{{ route('loans.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">+ Buat Pinjaman</a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 rounded-xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/40 border-b border-gray-200/50">
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Kode</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Anggota</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Petugas</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Batas Kembali</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Status</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50">
                @foreach($loans as $loan)
                <tr class="hover:bg-white/50 transition">
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $loan->kode_pinjam }}</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-800">{{ $loan->member->nama ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $loan->user->name ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $loan->tanggal_kembali->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-sm">
                        <span class="px-3 py-1 text-xs rounded-full {{ $loan->status == 'dipinjam' ? 'bg-yellow-100/80 text-yellow-700' : 'bg-green-100/80 text-green-700' }}">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('loans.show', $loan) }}" class="text-xs text-blue-600 hover:underline">Detail</a>
                        <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data pinjaman ini? Stok buku akan dikembalikan.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection