@extends('layouts.app')

@section('title', 'Data Pengembalian')
@section('header_title', 'Transaksi Pengembalian')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Data Pengembalian</h2>
            <p class="text-gray-500 text-sm mt-1">Riwayat buku yang sudah dikembalikan.</p>
        </div>
        <a href="{{ route('returns.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">+ Proses Pengembalian</a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 rounded-xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/40 border-b border-gray-200/50">
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Kode Kembali</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Anggota</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Tgl Dikembalikan</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Terlambat</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Denda</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50">
                @foreach($returns as $return)
                <tr class="hover:bg-white/50 transition">
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $return->kode_kembali }}</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-800">{{ $return->loan->member->nama ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ \Carbon\Carbon::parse($return->tanggal_kembali)->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $return->terlambat_hari }} Hari</td>
                    <td class="py-4 px-6 text-sm font-medium {{ $return->denda > 0 ? 'text-red-600' : 'text-gray-500' }}">Rp {{ number_format($return->denda, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection