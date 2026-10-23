@extends('layouts.app')

@section('title', 'Buat Peminjaman')
@section('header_title', 'Transaksi Peminjaman')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Buat Peminjaman</h2>
            <p class="text-sm text-gray-500 mt-1">Pilih anggota, klik kartu buku, lalu konfirmasi.</p>
        </div>
        <a href="{{ route('loans.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition px-4 py-2 rounded-xl hover:bg-white/60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50/80 backdrop-blur-md border border-red-200 rounded-2xl text-sm text-red-600">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('loans.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ 
            selectedBooks: [], 
            memberId: '', 
            memberName: '',
            maxBooks: {{ $maxBooks }},
            bookSearch: '',
            memberSearch: '',
            get isOverLimit() { return this.selectedBooks.length > this.maxBooks; }
        }">
            
            <!-- KOLOM KIRI: Anggota + Ringkasan -->
            <div class="space-y-6">
                <!-- Card: Pilih Anggota (SEARCHABLE) -->
                <div class="glass border border-white/60 rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-7 h-7 rounded-full bg-gray-900 text-white text-xs flex items-center justify-center font-medium">1</span>
                        <h3 class="text-sm font-semibold text-gray-800">Pilih Anggota</h3>
                    </div>
                    
                    <!-- Hidden input buat submit form -->
                    <input type="hidden" name="member_id" :value="memberId" required>
                    
                    <!-- Search Anggota -->
                    <div class="relative mb-3">
                        <input type="text" x-model="memberSearch" placeholder="Cari nama atau kode anggota..." 
                               class="w-full pl-10 pr-4 py-2.5 bg-white/70 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <!-- Daftar Anggota (Filter via x-show) -->
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1 -mr-1">
                        @foreach($members as $member)
                            <button type="button" 
                                    x-show="!memberSearch || '{{ strtolower($member->nama) . ' ' . strtolower($member->kode_member) }}'.includes(memberSearch.toLowerCase())"
                                    @click="memberId = '{{ $member->id }}'; memberName = '{{ $member->nama }}'"
                                    class="w-full text-left p-3 rounded-xl border-2 transition"
                                    :class="memberId == '{{ $member->id }}' ? 'border-gray-900 bg-gray-50 shadow-md' : 'border-gray-200 bg-white/70 hover:border-gray-300'">
                                <div class="flex justify-between items-center">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $member->nama }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $member->kode_member }}</p>
                                    </div>
                                    <div class="shrink-0 ml-2">
                                        <span class="px-2 py-0.5 text-[10px] rounded-full {{ $member->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($member->status) }}</span>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Card: Ringkasan -->
                <div class="glass border border-white/60 rounded-2xl shadow-sm p-6">
                    <h3 class="text-xs uppercase tracking-wider text-gray-500 font-medium mb-4">Ringkasan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Anggota</span>
                            <span class="font-medium text-gray-800 text-right max-w-[60%] truncate" x-text="memberName || '—'"></span>
                        </div>
                        <div class="h-px bg-gray-200/50"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Buku Dipilih</span>
                            <span class="font-medium px-2.5 py-0.5 rounded-full text-xs transition"
                                  :class="isOverLimit ? 'bg-red-100 text-red-700' : 'bg-gray-900 text-white'"
                                  x-text="selectedBooks.length + ' / ' + maxBooks"></span>
                        </div>
                        <div class="h-px bg-gray-200/50"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Batas Kembali</span>
                            <span class="font-medium text-gray-800">{{ $dueDate }}</span>
                        </div>
                    </div>

                    <div x-show="isOverLimit" x-cloak class="mt-4 text-xs text-red-600 bg-red-50/80 border border-red-200 rounded-lg p-3 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Melebihi batas maksimal <strong>{{ $maxBooks }} buku</strong> per transaksi.</span>
                    </div>
                </div>

                <button type="submit" :disabled="selectedBooks.length === 0 || !memberId || isOverLimit"
                        class="w-full py-3.5 bg-gray-900 text-white text-sm font-medium rounded-2xl hover:bg-gray-800 disabled:opacity-40 disabled:cursor-not-allowed transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Proses Peminjaman
                </button>
            </div>

            <!-- KOLOM KANAN: Buku -->
            <div class="lg:col-span-2">
                <div class="glass border border-white/60 rounded-2xl shadow-sm p-6 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-gray-900 text-white text-xs flex items-center justify-center font-medium">2</span>
                            <h3 class="text-sm font-semibold text-gray-800">Pilih Buku</h3>
                        </div>
                        <span class="text-xs text-gray-400">{{ count($books) }} judul tersedia</span>
                    </div>

                    <!-- SEARCH BAR BUKU -->
                    <div class="relative mb-4">
                        <input type="text" x-model="bookSearch" placeholder="Cari judul atau kode buku..." 
                               class="w-full pl-10 pr-4 py-2.5 bg-white/70 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <!-- GRID BUKU (Filter via x-show) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 overflow-y-auto max-h-[32rem] pr-1 -mr-1">
                        @forelse($books as $book)
                            <label class="cursor-pointer group relative" 
                                   x-show="!bookSearch || ('{{ strtolower($book->judul) }}'.includes(bookSearch.toLowerCase()) || '{{ strtolower($book->kode_buku) }}'.includes(bookSearch.toLowerCase()))">
                                <input type="checkbox" name="books[]" value="{{ $book->id }}" x-model.number="selectedBooks" class="sr-only peer">
                                <div class="p-4 rounded-2xl border-2 border-gray-200 bg-white/70 hover:bg-white hover:border-gray-300 peer-checked:border-gray-900 peer-checked:bg-gray-50 peer-checked:shadow-md transition-all duration-200 relative">
                                    <div class="absolute top-3 right-3 w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center opacity-0 scale-0 group-[:has(input:checked)]:opacity-100 group-[:has(input:checked)]:scale-100 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="text-sm font-medium leading-snug line-clamp-2 text-gray-800 pr-8">{{ $book->judul }}</p>
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="text-[10px] text-gray-400 font-mono">{{ $book->kode_buku }}</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-green-100/90 text-green-700 font-medium">Stok {{ $book->tersedia }}</span>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-2 py-16 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"></path></svg>
                                <p class="text-sm text-gray-400">Tidak ada buku tersedia saat ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection