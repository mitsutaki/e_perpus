@extends('layouts.anggota')

@section('title', 'Katalog Buku')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Katalog Buku 📚</h1>
        <p class="text-gray-500 mt-1">Temukan buku favoritmu dan booking sekarang!</p>
    </div>

    <form method="GET" action="{{ route('katalog.index') }}" class="relative max-w-md">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul atau ISBN..." 
               class="w-full pl-12 pr-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
    </form>

    @if(session('error'))
        <div class="p-4 bg-red-50/80 border border-red-200 rounded-2xl text-sm text-red-600">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="p-4 bg-green-50/80 border border-green-200 rounded-2xl text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach($books as $book)
            @php
                $colors = ['from-blue-500 to-indigo-600', 'from-green-500 to-emerald-600', 'from-purple-500 to-pink-600', 'from-orange-500 to-red-500', 'from-cyan-500 to-blue-600'];
                $color = $colors[$book->id % count($colors)];
                $initial = strtoupper(substr($book->judul, 0, 1));
            @endphp
            
            <!-- Wrapper dengan x-data sendiri per card -->
            <div x-data="{ show: false }">
                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group h-full">
                    <div class="aspect-[3/4] bg-gradient-to-br from-slate-100 to-slate-200 relative overflow-hidden">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $color }} flex items-center justify-center">
                                <span class="text-white text-6xl font-bold opacity-80">{{ $initial }}</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-2 right-2">
                            @if($book->tersedia > 0)
                                <span class="px-2 py-1 text-[10px] font-bold bg-green-500 text-white rounded-full shadow-sm">Tersedia {{ $book->tersedia }}</span>
                            @else
                                <span class="px-2 py-1 text-[10px] font-bold bg-red-500 text-white rounded-full shadow-sm">Habis</span>
                            @endif
                        </div>

                        @if(in_array($book->id, $activeBookings))
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-1 text-[10px] font-bold bg-yellow-500 text-white rounded-full shadow-sm">Dibooking</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-3">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-1">{{ $book->judul }}</h3>
                        <p class="text-xs text-gray-500 mb-3">{{ $book->category->nama_kategori ?? '-' }}</p>

                        <button type="button" @@click="show = true" class="w-full py-2 text-xs text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition mb-2">
                            Lihat Detail
                        </button>

                        <form action="{{ route('anggota.booking.store', $book) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    @if($book->tersedia <= 0 || in_array($book->id, $activeBookings)) disabled @endif
                                    class="w-full py-2 text-xs font-medium rounded-lg transition @if($book->tersedia <= 0) bg-gray-200 text-gray-400 cursor-not-allowed @elseif(in_array($book->id, $activeBookings)) bg-yellow-100 text-yellow-700 cursor-not-allowed @else bg-slate-900 text-white hover:bg-slate-800 @endif">
                                @if($book->tersedia <= 0)
                                    Stok Habis
                                @elseif(in_array($book->id, $activeBookings))
                                    ✓ Dibooking
                                @else
                                    Booking Sekarang
                                @endif
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Modal (di luar card biar gak ke-clip overflow) -->
                <div x-show="show" x-cloak @@click="show = false" @@keydown.escape.window="show = false"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" style="display: none;">
                    <div @@click.stop class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" x-transition>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-0">
                            <div class="aspect-[3/4] bg-gradient-to-br from-slate-100 to-slate-200 sm:rounded-l-3xl overflow-hidden">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br {{ $color }} flex items-center justify-center">
                                        <span class="text-white text-8xl font-bold opacity-80">{{ $initial }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="col-span-2 p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-xs px-2 py-1 bg-slate-100 text-slate-600 rounded-full">{{ $book->category->nama_kategori ?? '-' }}</span>
                                        <h2 class="text-2xl font-bold text-gray-900 mt-3">{{ $book->judul }}</h2>
                                        <p class="text-sm text-gray-500 mt-1">{{ $book->publisher->nama_penerbit ?? '-' }} · {{ $book->tahun_terbit }}</p>
                                    </div>
                                    <button @@click="show = false" class="text-gray-400 hover:text-gray-900">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <div class="mt-6 space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">ISBN</span>
                                        <span class="font-medium text-gray-800">{{ $book->isbn ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Kode Buku</span>
                                        <span class="font-medium text-gray-800 font-mono">{{ $book->kode_buku }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Stok Tersedia</span>
                                        <span class="font-bold @if($book->tersedia > 0) text-green-600 @else text-red-600 @endif">{{ $book->tersedia }} buku</span>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Sinopsis</p>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $book->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    <form action="{{ route('anggota.booking.store', $book) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                @if($book->tersedia <= 0 || in_array($book->id, $activeBookings)) disabled @endif
                                                class="w-full py-3 text-sm font-medium rounded-xl transition @if($book->tersedia <= 0) bg-gray-200 text-gray-400 @elseif(in_array($book->id, $activeBookings)) bg-yellow-100 text-yellow-700 @else bg-slate-900 text-white hover:bg-slate-800 @endif">
                                            @if($book->tersedia <= 0)
                                                Maaf, Stok Habis
                                            @elseif(in_array($book->id, $activeBookings))
                                                ✓ Anda Sudah Membooking
                                            @else
                                                Booking Sekarang
                                            @endif
                                        </button>
                                        <p class="text-xs text-gray-400 text-center mt-2">Buku harus diambil dalam 24 jam</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $books->links() }}
    </div>
</div>
@endsection