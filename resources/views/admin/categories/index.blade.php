@extends('layouts.app')

@section('title', 'Data Kategori')
@section('header_title', 'Master Data Kategori')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Data Kategori</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola kategori buku perpustakaan.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">+ Tambah Kategori</a>
    </div>

    <!-- SEARCH FORM -->
    <form method="GET" action="{{ route('categories.index') }}" class="relative max-w-md">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau kode kategori..." 
               class="w-full pl-12 pr-10 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        @if($search)
            <a href="{{ route('categories.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
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
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Nama Kategori</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Deskripsi</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50">
                @forelse($categories as $category)
                <tr class="hover:bg-white/50 transition">
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $category->kode_kategori }}</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-800">{{ $category->nama_kategori }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $category->deskripsi ?? '-' }}</td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('categories.edit', $category) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-8 text-center text-sm text-gray-400">Tidak ada kategori ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200/50">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection