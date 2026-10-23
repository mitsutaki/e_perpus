@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('header_title', 'Tambah Buku Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Tambah Buku</h2>
            <p class="text-sm text-gray-500 mt-1">Masukkan data buku baru ke koleksi perpustakaan.</p>
        </div>
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition px-4 py-2 rounded-xl hover:bg-white/60">
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

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Judul Buku</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required autofocus
                       class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                       placeholder="Masukkan judul buku">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}"
                           class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                           placeholder="978-xxx-xxx">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', 2023) }}" required
                           class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                        @foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Penerbit</label>
                    <select name="publisher_id" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                        @foreach($publishers as $publisher)<option value="{{ $publisher->id }}">{{ $publisher->nama_penerbit }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Lokasi Rak</label>
                <select name="shelf_id" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                    <option value="">— Tidak Ada Rak —</option>
                    @foreach($shelves as $shelf)<option value="{{ $shelf->id }}">{{ $shelf->nama_rak }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Jumlah Stok</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" required min="1"
                           class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Jml Buku Rusak</label>
                    <input type="number" name="jumlah_rusak" value="{{ old('jumlah_rusak', 0) }}" min="0"
                           class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Cover Buku (Opsional)</label>
                <div class="flex items-center gap-4">
                    <!-- Preview -->
                    <div class="w-20 h-28 rounded-xl border-2 border-dashed border-gray-300 overflow-hidden bg-gray-50 flex items-center justify-center shrink-0" id="coverPreview">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <!-- Input -->
                    <div class="flex-1">
                        <input type="file" name="cover" accept="image/*" id="coverInput"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-gray-900 file:text-white hover:file:bg-gray-800 file:cursor-pointer cursor-pointer transition">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, WebP. Max 2MB.</p>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                          placeholder="Sinopsis atau keterangan buku">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('books.index') }}" class="px-5 py-3 text-sm text-gray-600 hover:text-gray-900 transition rounded-xl hover:bg-white/60">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Buku
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('coverInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('coverPreview');
                preview.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection