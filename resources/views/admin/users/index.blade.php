@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header_title', 'Manajemen Akun Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Data Pengguna</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola akun Admin dan Petugas perpustakaan.</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">+ Tambah User</a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 rounded-xl text-sm text-green-600">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50/80 backdrop-blur-md border border-red-200 rounded-xl text-sm text-red-600">{{ session('error') }}</div>
    @endif

    <!-- INSTANT SEARCH -->
    <div class="relative max-w-md">
        <input type="text" id="userSearch" placeholder="Cari nama, username, email, atau role..." 
               class="w-full pl-12 pr-10 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <button id="clearUserSearch" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 hidden">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Counter -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <span id="userCount" class="font-semibold text-gray-800">{{ $users->count() }}</span> dari <span class="font-semibold">{{ $users->count() }}</span> pengguna</p>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0">
                <tr class="bg-white/80 backdrop-blur-md border-b border-gray-200/50">
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Nama</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Username</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Email</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Role</th>
                    <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50" id="userTableBody">
                @forelse($users as $user)
                <tr class="hover:bg-white/50 transition user-row" data-search="{{ strtolower($user->name . ' ' . $user->username . ' ' . $user->email . ' ' . $user->role) }}">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=e2e8f0&color=1e293b' }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                            <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $user->username }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="py-4 px-6 text-sm">
                        @php
                            $roleColors = [
                                'super_admin' => 'bg-purple-100 text-purple-700',
                                'admin' => 'bg-blue-100 text-blue-700',
                                'petugas' => 'bg-emerald-100 text-emerald-700',
                                'anggota' => 'bg-gray-100 text-gray-700',
                            ];
                            $roleLabels = [
                                'super_admin' => '👑 Super Admin',
                                'admin' => '🛡️ Admin',
                                'petugas' => '🧑‍💼 Petugas',
                                'anggota' => '👤 Anggota',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-xs rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('users.edit', $user) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus akun ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-sm text-gray-400">Tidak ada pengguna ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- INSTANT SEARCH SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const clearBtn = document.getElementById('clearUserSearch');
    const rows = document.querySelectorAll('.user-row');
    const counter = document.getElementById('userCount');

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