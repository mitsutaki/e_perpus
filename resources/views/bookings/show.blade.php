@extends('layouts.app')

@section('title', 'Konfirmasi Booking')
@section('header_title', 'Konfirmasi Booking Anggota')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Konfirmasi Booking</h2>
            <p class="text-sm text-gray-500 mt-1">Cek data anggota & buku, lalu konfirmasi untuk jadikan peminjaman resmi.</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition px-4 py-2 rounded-xl hover:bg-white/60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="p-4 bg-red-50/80 border border-red-200 rounded-2xl text-sm text-red-600">{{ session('error') }}</div>
    @endif

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Cover Buku -->
            <div class="aspect-[3/4] bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl overflow-hidden">
                @if($booking->book->cover)
                    <img src="{{ asset('storage/' . $booking->book->cover) }}" class="w-full h-full object-cover">
                @else
                    @php
                        $colors = ['from-blue-500 to-indigo-600', 'from-green-500 to-emerald-600', 'from-purple-500 to-pink-600'];
                        $color = $colors[$booking->book->id % count($colors)];
                    @endphp
                    <div class="w-full h-full bg-gradient-to-br {{ $color }} flex items-center justify-center">
                        <span class="text-white text-6xl font-bold">{{ strtoupper(substr($booking->book->judul, 0, 1)) }}</span>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="col-span-2 space-y-4">
                <div>
                    <span class="text-xs px-2 py-1 bg-slate-100 text-slate-600 rounded-full">{{ $booking->book->category->nama_kategori ?? '-' }}</span>
                    <h3 class="text-xl font-bold text-gray-900 mt-2">{{ $booking->book->judul }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $booking->book->publisher->nama_penerbit ?? '-' }} · {{ $booking->book->tahun_terbit }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Kode Booking</p>
                        <p class="text-sm font-mono text-gray-700 mt-1">{{ $booking->kode_booking }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Tanggal Booking</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $booking->tanggal_booking->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Anggota</p>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $booking->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Batas Ambil</p>
                        <p class="text-sm font-medium @if(now() > $booking->batas_ambil) text-red-600 @else text-gray-800 @endif mt-1">{{ $booking->batas_ambil->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action -->
        @php
            $member = \App\Models\Member::where('user_id', $booking->user_id)->first();
        @endphp
        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
            <form action="{{ route('bookings.reject', $booking) }}" method="POST" onsubmit="return confirm('Tolak booking ini?')">
                @csrf
                <button type="submit" class="px-5 py-3 text-sm text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition">
                    Tolak Booking
                </button>
            </form>
            <form action="{{ route('bookings.confirm', $booking) }}" method="POST" onsubmit="return confirm('Konfirmasi booking jadi peminjaman resmi?')">
                @csrf
                @if($member)
                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                @endif
                <button type="submit" class="px-6 py-3 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Konfirmasi & Jadikan Peminjaman
                </button>
            </form>
        </div>
    </div>
</div>
@endsection