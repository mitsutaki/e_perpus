@extends('layouts.app')

@section('title', 'Booking Masuk')
@section('header_title', 'Daftar Booking Anggota')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Booking Masuk 📥</h2>
            <p class="text-sm text-gray-500 mt-1">Konfirmasi booking dari anggota untuk jadi peminjaman resmi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 border border-green-200 rounded-2xl text-sm text-green-600">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50/80 border border-red-200 rounded-2xl text-sm text-red-600">{{ session('error') }}</div>
    @endif

    <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
        @if($bookings->isEmpty())
            <div class="p-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Tidak ada booking menunggu</h3>
                <p class="text-sm text-gray-500 mt-1">Booking yang masuk dari anggota akan muncul di sini.</p>
            </div>
        @else
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/40 border-b border-gray-200/50">
                        <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Kode Booking</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Anggota</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Buku</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium">Batas Ambil</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/50">
                    @foreach($bookings as $booking)
                        @php
                            $isExpired = now() > $booking->batas_ambil;
                        @endphp
                        <tr class="hover:bg-white/50 transition {{ $isExpired ? 'bg-red-50/30' : '' }}">
                            <td class="py-4 px-6 text-sm text-gray-700 font-mono">{{ $booking->kode_booking }}</td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-800">{{ $booking->user->name ?? '-' }}</td>
                            <td class="py-4 px-6 text-sm text-gray-700">{{ $booking->book->judul }}</td>
                            <td class="py-4 px-6 text-sm @if($isExpired) text-red-600 font-medium @else text-gray-500 @endif">
                                {{ $booking->batas_ambil->format('d M Y, H:i') }}
                                @if($isExpired)<span class="text-xs">(Lewat!)</span>@endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('bookings.show', $booking) }}" class="text-xs text-blue-600 hover:underline font-medium">Konfirmasi</a>
                                <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="inline-block" onsubmit="return confirm('Tolak booking ini? Stok buku akan dikembalikan.')">
                                    @csrf
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Tolak</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection