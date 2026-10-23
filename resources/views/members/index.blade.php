@extends('layouts.app')

@section('title', 'Data Anggota')
@section('header_title', 'Manajemen Anggota')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Data Anggota</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar anggota perpustakaan.</p>
        </div>
        <a href="{{ route('members.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">+ Tambah Anggota</a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 rounded-xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <!-- INSTANT SEARCH -->
    <div class="relative max-w-md">
        <input type="text" id="memberSearch" placeholder="Cari nama, kode, telepon, atau email..." 
               class="w-full pl-12 pr-10 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <button id="clearMemberSearch" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 hidden">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Counter -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <span id="memberCount" class="font-semibold text-gray-800">{{ $members->count() }}</span> dari <span class="font-semibold">{{ $members->count() }}</span> anggota</p>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0">
                <tr class="bg-white/80 backdrop-blur-md border-b border-gray-200/50">
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Kode</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Nama</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Telepon</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Status</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50" id="memberTableBody">
                @forelse($members as $member)
                <tr class="hover:bg-white/50 transition member-row" data-search="{{ strtolower($member->nama . ' ' . $member->kode_member . ' ' . $member->telepon . ' ' . ($member->email ?? '')) }}">
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $member->kode_member }}</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-800">{{ $member->nama }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $member->telepon }}</td>
                    <td class="py-4 px-6 text-sm">
                        <span class="px-3 py-1 text-xs rounded-full {{ $member->status == 'aktif' ? 'bg-green-100/80 text-green-700' : 'bg-red-100/80 text-red-700' }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('members.edit', $member) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus anggota beserta akun loginnya?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-sm text-gray-400">Tidak ada anggota ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- INSTANT SEARCH SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('memberSearch');
    const clearBtn = document.getElementById('clearMemberSearch');
    const rows = document.querySelectorAll('.member-row');
    const counter = document.getElementById('memberCount');
    const total = {{ $members->count() }};

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