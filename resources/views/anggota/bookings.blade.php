@extends('layouts.anggota')

@section('title', 'Bookinganku')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Bookinganku 📖</h1>
        <p class="text-gray-500 mt-1">Status pemesanan buku kamu.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 border border-green-200 rounded-2xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    @if($bookings->isEmpty())
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Belum ada booking</h3>
            <p class="text-sm text-gray-500 mt-1">Yuk <a href="{{ route('katalog.index') }}" class="text-slate-900 font-medium underline">cari buku</a> dan booking sekarang!</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($bookings as $booking)
                @php
                    $statusColors = [
                        'menunggu' => 'bg-yellow-100 text-yellow-700',
                        'dikonfirmasi' => 'bg-blue-100 text-blue-700',
                        'dipinjam' => 'bg-green-100 text-green-700',
                        'dibatalkan' => 'bg-red-100 text-red-700',
                    ];
                    $statusText = [
                        'menunggu' => '⏳ Menunggu Konfirmasi',
                        'dikonfirmasi' => '✓ Dikonfirmasi',
                        'dipinjam' => '📚 Dipinjam',
                        'dibatalkan' => '✗ Dibatalkan',
                    ];
                    $isExpired = $booking->status === 'menunggu' && now() > $booking->batas_ambil;
                @endphp
                <div class="glass border border-white/60 rounded-2xl p-5 shadow-sm flex gap-4">
                    <!-- Cover -->
                    <div class="w-20 h-28 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl overflow-hidden shrink-0">
                        @if($booking->book->cover)
                            <img src="{{ asset('storage/' . $booking->book->cover) }}" class="w-full h-full object-cover">
                        @else
                            @php
                                $colors = ['from-blue-500 to-indigo-600', 'from-green-500 to-emerald-600', 'from-purple-500 to-pink-600', 'from-orange-500 to-red-500'];
                                $color = $colors[$booking->book->id % count($colors)];
                            @endphp
                            <div class="w-full h-full bg-gradient-to-br {{ $color }} flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">{{ strtoupper(substr($booking->book->judul, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-3">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $booking->book->judul }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Kode: {{ $booking->kode_booking }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$booking->status] }} shrink-0">
                                {{ $statusText[$booking->status] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-3 text-xs">
                            <div>
                                <p class="text-gray-400">Booking</p>
                                <p class="font-medium text-gray-700">{{ $booking->tanggal_booking->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Batas Ambil</p>
                                <p class="font-medium @if($isExpired) text-red-600 @else text-gray-700 @endif">
                                    {{ $booking->batas_ambil->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($isExpired)
                            <div class="mt-3 text-xs text-red-600 bg-red-50/80 border border-red-200 rounded-lg p-2">
                                ⚠️ Waktu ambil sudah habis! Booking akan otomatis dibatalkan sistem.
                            </div>
                        @endif

                        <!-- Aksi -->
                        @if($booking->status === 'menunggu')
                            <div class="mt-3 flex gap-2">
                                <form action="{{ route('anggota.booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                        Batalkan
                                    </button>
                                </form>
                            </div>
                        @elseif($booking->status === 'dipinjam')
                            <div class="mt-3 text-xs text-gray-500">
                                Petugas: {{ $booking->confirmedBy->name ?? '-' }} · {{ $booking->confirmed_at?->format('d M Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection