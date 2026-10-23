@extends('layouts.app')

@section('title', 'Data Buku')
@section('header_title', 'Master Data Buku')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Data Buku</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola koleksi buku perpustakaan.</p>
        </div>
        <a href="{{ route('books.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">+ Tambah Buku</a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 rounded-xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <!-- INSTANT SEARCH (Live Filter) -->
    <div class="relative max-w-md">
        <input type="text" id="bookSearch" placeholder="Cari judul, kode, atau kategori..." 
               class="w-full pl-12 pr-10 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <button id="clearSearch" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 hidden">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Counter -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <span id="bookCount" class="font-semibold text-gray-800">{{ $books->count() }}</span> dari <span class="font-semibold">{{ $books->count() }}</span> buku</p>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0">
                <tr class="bg-white/80 backdrop-blur-md border-b border-gray-200/50">
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Buku</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Kategori</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Stok</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50" id="bookTableBody">
                @forelse($books as $book)
                <tr class="hover:bg-white/50 transition book-row" data-search="{{ strtolower($book->judul . ' ' . $book->kode_buku . ' ' . ($book->category->nama_kategori ?? '') . ' ' . ($book->isbn ?? '')) }}">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover" class="w-10 h-14 object-cover rounded-md shadow-sm shrink-0">
                            @else
                                @php
                                    $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-orange-500', 'bg-pink-500', 'bg-indigo-500'];
                                    $color = $colors[$book->id % count($colors)];
                                    $initial = strtoupper(substr($book->judul, 0, 1));
                                @endphp
                                <div class="w-10 h-14 rounded-md {{ $color }} flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-sm">
                                    {{ $initial }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $book->judul }}</p>
                                <p class="text-xs text-gray-400">{{ $book->kode_buku }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $book->category->nama_kategori ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm">
                        <span class="font-medium text-gray-800">{{ $book->tersedia }}</span>
                        <span class="text-gray-400">/ {{ $book->jumlah }}</span>
                        @if($book->dibooking > 0)
                            <span class="ml-1 text-[10px] px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">{{ $book->dibooking }} dibooking</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('books.edit', $book) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus buku ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-8 text-center text-sm text-gray-400">Tidak ada buku ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- INSTANT SEARCH SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('bookSearch');
    const clearBtn = document.getElementById('clearSearch');
    const rows = document.querySelectorAll('.book-row');
    const counter = document.getElementById('bookCount');
    const total = {{ $books->count() }};

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let visibleCount = 0;
        
        rows.forEach(row => {
            const searchText = row.dataset.search;
            if (searchText.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        counter.textContent = visibleCount;
        clearBtn.classList.toggle('hidden', query === '');
    });

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    });
});
</script>
@endsection