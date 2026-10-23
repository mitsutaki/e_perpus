@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('header_title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Tambah Kategori</h2>
            <p class="text-sm text-gray-500 mt-1">Buat kategori buku baru.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition px-4 py-2 rounded-xl hover:bg-white/60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50/80 border border-red-200 rounded-2xl text-sm text-red-600">
                <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required autofocus
                       class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                       placeholder="Contoh: Fiksi, Sains, Sejarah">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                          placeholder="Keterangan kategori">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('categories.index') }}" class="px-5 py-3 text-sm text-gray-600 hover:text-gray-900 transition rounded-xl hover:bg-white/60">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection