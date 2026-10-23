@extends('layouts.app')

@section('title', 'Data Denda')
@section('header_title', 'Manajemen Denda')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-light tracking-tight">Data Denda Anggota</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola pembayaran denda keterlambatan.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 rounded-xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/40 border-b border-gray-200/50">
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Kode Denda</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Anggota</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Jumlah</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Status</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50">
                @foreach($fines as $fine)
                <tr class="hover:bg-white/50 transition">
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $fine->kode_denda }}</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-800">{{ $fine->member->nama ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm font-medium text-red-600">Rp {{ number_format($fine->jumlah, 0, ',', '.') }}</td>
                    <td class="py-4 px-6 text-sm">
                        <span class="px-3 py-1 text-xs rounded-full {{ $fine->status == 'belum_bayar' ? 'bg-red-100/80 text-red-700' : 'bg-green-100/80 text-green-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $fine->status)) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        @if($fine->status == 'belum_bayar')
                        <form action="{{ route('fines.update', $fine) }}" method="POST" class="inline-block" onsubmit="return confirm('Tandai denda ini sudah LUNAS?')">
                            @csrf @method('PUT')
                            <button type="submit" class="text-xs text-green-600 hover:underline font-medium">Tandai Lunas</button>
                        </form>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
