@extends('layouts.app')

@section('title', 'Proses Pengembalian')
@section('header_title', 'Transaksi Pengembalian')

@section('content')
<div x-data="returnForm()" class="max-w-6xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Proses Pengembalian</h2>
            <p class="text-sm text-gray-500 mt-1">Pilih transaksi aktif, sistem menghitung denda otomatis.</p>
        </div>
        <a href="{{ route('returns.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition px-4 py-2 rounded-xl hover:bg-white/60">
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

    <form action="{{ route('returns.store') }}" method="POST">
        @csrf
        <input type="hidden" name="loan_id" :value="selected ? selected.id : ''">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- KOLOM KIRI: List Transaksi -->
            <div class="glass border border-white/60 rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-7 h-7 rounded-full bg-gray-900 text-white text-xs flex items-center justify-center font-medium">1</span>
                    <h3 class="text-sm font-semibold text-gray-800">Pilih Transaksi Aktif</h3>
                </div>

                <!-- SEARCH BAR -->
                <div class="relative mb-4">
                    <input type="text" x-model="searchQuery" placeholder="Cari nama anggota atau kode peminjaman..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-white/70 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 transition">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <div class="space-y-2 max-h-[32rem] overflow-y-auto pr-1 -mr-1">
                    <template x-for="loan in filteredLoans" :key="loan.id">
                        <button type="button" @click="selected = loan"
                                class="w-full text-left p-4 rounded-2xl border transition-all duration-200"
                                :class="selected && selected.id === loan.id
                                    ? 'bg-gray-900 border-gray-900 text-white shadow-lg'
                                    : 'bg-white/50 border-gray-200 hover:bg-white/80 hover:border-gray-300 text-gray-800'">
                            <div class="flex justify-between items-center gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium truncate" x-text="loan.member"></p>
                                    <p class="text-xs opacity-60 mt-0.5 font-mono" x-text="loan.kode"></p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] uppercase opacity-60">Batas</p>
                                    <p class="text-xs font-medium" x-text="loan.batas"></p>
                                </div>
                            </div>
                            <template x-if="loan.terlambat > 0">
                                <div class="mt-2 inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded-full"
                                     :class="selected && selected.id === loan.id ? 'bg-red-500/20 text-red-200' : 'bg-red-100 text-red-700'">
                                    <span>Telat</span>
                                    <strong x-text="loan.terlambat + ' hari'"></strong>
                                </div>
                            </template>
                        </button>
                    </template>
                    <p x-show="filteredLoans.length === 0" class="text-sm text-gray-400 text-center py-12">
                        Tidak ada transaksi peminjaman aktif cocok.
                    </p>
                </div>
            </div>

            <!-- KOLOM KANAN: Detail -->
            <div class="glass border border-white/60 rounded-2xl shadow-sm p-6 flex flex-col">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-7 h-7 rounded-full bg-gray-900 text-white text-xs flex items-center justify-center font-medium">2</span>
                    <h3 class="text-sm font-semibold text-gray-800">Detail Pengembalian</h3>
                </div>

                <template x-if="selected">
                    <div class="space-y-4 flex-1">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white/50 rounded-xl p-3 border border-gray-200">
                                <p class="text-[10px] uppercase text-gray-500">Anggota</p>
                                <p class="font-medium mt-1 text-sm" x-text="selected.member"></p>
                            </div>
                            <div class="bg-white/50 rounded-xl p-3 border border-gray-200">
                                <p class="text-[10px] uppercase text-gray-500">Kode Pinjam</p>
                                <p class="font-medium mt-1 text-sm font-mono" x-text="selected.kode"></p>
                            </div>
                        </div>

                        <div x-show="selected.terlambat > 0" 
                             class="p-4 bg-red-50/80 border border-red-200 rounded-2xl text-sm text-red-700 flex items-start gap-3">
                            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <p class="font-medium">Terlambat <span x-text="selected.terlambat"></span> hari</p>
                                <p class="text-xs mt-1">Estimasi denda: <strong x-text="formatRupiah(selected.estimasi_denda)"></strong></p>
                            </div>
                        </div>
                        <div x-show="selected.terlambat === 0"
                             class="p-4 bg-green-50/80 border border-green-200 rounded-2xl text-sm text-green-700 flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tepat waktu — tidak ada denda.
                        </div>

                        <div>
                            <p class="text-[10px] uppercase text-gray-500 mb-2">Buku Dikembalikan</p>
                            <ul class="space-y-2">
                                <template x-for="(book, index) in selected.books" :key="index">
                                    <li class="flex justify-between items-center bg-white/50 border border-gray-200 p-3 rounded-xl text-sm">
                                        <span x-text="book.judul"></span>
                                        <span class="text-xs text-gray-500" x-text="book.jumlah + ' buku'"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <button type="submit"
                                class="w-full py-3.5 mt-auto bg-gray-900 text-white text-sm font-medium rounded-2xl hover:bg-gray-800 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Konfirmasi Pengembalian
                        </button>
                    </div>
                </template>

                <div x-show="!selected" class="flex-1 flex flex-col items-center justify-center text-center py-16">
                    <div class="w-16 h-16 bg-white/60 rounded-2xl flex items-center justify-center mb-4 border border-gray-200">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-400 font-medium">Belum ada transaksi dipilih</p>
                    <p class="text-xs text-gray-400 mt-1">Pilih salah satu transaksi di sebelah kiri.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function returnForm() {
        return {
            loans: @json($loansData),
            selected: null,
            searchQuery: '',
            get filteredLoans() {
                if (!this.searchQuery) return this.loans;
                const q = this.searchQuery.toLowerCase();
                return this.loans.filter(l => 
                    (l.member && l.member.toLowerCase().includes(q)) ||
                    (l.kode && l.kode.toLowerCase().includes(q))
                );
            },
            formatRupiah(num) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
            }
        };
    }
</script>
@endsection