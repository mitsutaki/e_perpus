    @extends('layouts.app')

@section('title', 'Edit Buku')
@section('header_title', 'Edit Data Buku')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-light tracking-tight">Edit Buku</h2>
        <p class="text-gray-500 text-sm mt-1">Perbarui data buku.</p>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        <!-- PASTIKAN METHOD FORM ADALAH POST -->
        <form action="{{ route('books.update', $book) }}" method="POST" class="space-y-5">
            @csrf
            <!-- INI ADALAH BARIS PENTING YANG WAJIB ADA -->
            @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Cover Buku</label>
                    <div class="flex items-center gap-4">
                    <!-- Preview (pakai cover existing kalau ada) -->
                        <div class="w-20 h-28 rounded-xl border-2 border-dashed border-gray-300 overflow-hidden bg-gray-50 flex items-center justify-center shrink-0" id="coverPreview">
                        @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
                        @else
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                        <!-- Input -->
                    <div class="flex-1">
                        <input type="file" name="cover" accept="image/*" id="coverInput"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-gray-900 file:text-white hover:file:bg-gray-800 file:cursor-pointer cursor-pointer transition">
                            <p class="text-xs text-gray-400 mt-2">Kosongkan jika tidak ingin ganti. Format: JPG, PNG, WebP. Max 2MB.</p>
                    </div>
            </div>
    </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Judul Buku</label>
                    <input type="text" name="judul" value="{{ $book->judul }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">ISBN</label>
                    <input type="text" name="isbn" value="{{ $book->isbn }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="{{ $book->tahun_terbit }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                        @foreach($categories as $category) 
                            <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option> 
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Penerbit</label>
                    <select name="publisher_id" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                        @foreach($publishers as $publisher) 
                            <option value="{{ $publisher->id }}" {{ $book->publisher_id == $publisher->id ? 'selected' : '' }}>{{ $publisher->nama_penerbit }}</option> 
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Lokasi Rak</label>
                    <select name="shelf_id" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                        <option value="">- Tidak Ada Rak -</option>
                        @foreach($shelves as $shelf) 
                            <option value="{{ $shelf->id }}" {{ $book->shelf_id == $shelf->id ? 'selected' : '' }}>{{ $shelf->nama_rak }}</option> 
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Jumlah Stok</label>
                    <input type="number" name="jumlah" value="{{ $book->jumlah }}" required min="1" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">{{ $book->deskripsi }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('books.index') }}" class="px-4 py-3 text-sm text-gray-600 hover:text-gray-900 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition">Update</button>
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