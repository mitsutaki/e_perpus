@extends('layouts.app')

@section('title', 'Data Penerbit')
@section('header_title', 'Master Data Penerbit')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Data Penerbit</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data penerbit buku.</p>
        </div>
        <a href="{{ route('publishers.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">+ Tambah Penerbit</a>
    </div>

    <!-- SEARCH FORM -->
    <form method="GET" action="{{ route('publishers.index') }}" class="relative max-w-md">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau kode penerbit..." 
               class="w-full pl-12 pr-10 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        @if($search)
            <a href="{{ route('publishers.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        @endif
    </form>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 rounded-xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/40 border-b border-gray-200/50">
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Kode</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Nama Penerbit</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Telepon</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50">
                @forelse($publishers as $publisher)
                <tr class="hover:bg-white/50 transition">
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $publisher->kode_penerbit }}</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-800">{{ $publisher->nama_penerbit }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $publisher->telepon ?? '-' }}</td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('publishers.edit', $publisher) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('publishers.destroy', $publisher) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus penerbit ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-8 text-center text-sm text-gray-400">Tidak ada penerbit ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200/50">
            {{ $publishers->links() }}
        </div>
    </div>
</div>
@endsection