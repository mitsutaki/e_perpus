@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header_title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="glass border border-white/60 rounded-2xl p-4 sm:p-6 shadow-sm">
        <h2 class="text-xl sm:text-2xl font-light tracking-tight">Selamat datang kembali, <span class="font-semibold">{{ auth()->user()->name }}</span></h2>
        <p class="text-gray-500 text-sm mt-1">Ringkasan aktivitas perpustakaan hari ini.</p>
    </div>

    <!-- Stat Cards (Responsive Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="glass border border-white/60 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Total Buku</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-semibold text-gray-800">{{ $totalBooks }}</h3>
        </div>
        <div class="glass border border-white/60 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Total Anggota</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-semibold text-gray-800">{{ $totalMembers }}</h3>
        </div>
        <div class="glass border border-white/60 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Sedang Dipinjam</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-semibold text-gray-800">{{ $activeLoans }}</h3>
        </div>
        <div class="glass border border-white/60 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Denda Tunggakan</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-semibold text-gray-800">Rp {{ number_format($unpaidFines, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200/50 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Aktivitas Terbaru Sistem</h3>
            <span class="text-xs text-gray-400">{{ $recentActivities->count() }} aktivitas</span>
        </div>
        <div class="divide-y divide-gray-200/50">
            @forelse($recentActivities as $activity)
                @php
                    $iconMap = [
                        'create'  => ['bg-green-100', 'text-green-600', '➕'],
                        'update'  => ['bg-blue-100', 'text-blue-600', '✏️'],
                        'delete'  => ['bg-red-100', 'text-red-600', '🗑️'],
                        'loan'    => ['bg-yellow-100', 'text-yellow-600', '📚'],
                        'return'  => ['bg-purple-100', 'text-purple-600', '↩️'],
                        'payment' => ['bg-emerald-100', 'text-emerald-600', '💵'],
                        'setting' => ['bg-gray-100', 'text-gray-600', '⚙️'],
                        'user'    => ['bg-indigo-100', 'text-indigo-600', '👤'],
                        'booking' => ['bg-orange-100', 'text-orange-600', '📥'],
                    ];
                    $icon = $iconMap[$activity->action] ?? ['bg-gray-100', 'text-gray-600', '📌'];
                @endphp
                <div class="flex items-start gap-3 p-4 hover:bg-white/40 transition">
                    <div class="w-9 h-9 rounded-full {{ $icon[0] }} {{ $icon[1] }} flex items-center justify-center text-sm shrink-0">
                        {{ $icon[2] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800">
                            <span class="font-medium">{{ $activity->user->name ?? 'System' }}</span>
                            <span class="text-gray-600">{{ $activity->description }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $activity->created_at->diffForHumans() }} · {{ $activity->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-gray-400">
                    Belum ada aktivitas tercatat.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection