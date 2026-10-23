@extends('layouts.app')

@section('title', 'Edit Rak')
@section('header_title', 'Edit Data Rak')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-light tracking-tight">Edit Rak / Etalase</h2>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        <form action="{{ route('shelves.update', $shelf) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Nama Rak / Etalase</label>
                <input type="text" name="nama_rak" value="{{ $shelf->nama_rak }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Tipe</label>
                    <select name="tipe" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                        <option value="rak" {{ $shelf->tipe == 'rak' ? 'selected' : '' }}>Rak Simpan</option>
                        <option value="etalase" {{ $shelf->tipe == 'etalase' ? 'selected' : '' }}>Etalase</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Kapasitas</label>
                    <input type="number" name="kapasitas" value="{{ $shelf->kapasitas }}" required min="0" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Lokasi (Opsional)</label>
                <input type="text" name="lokasi" value="{{ $shelf->lokasi }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">{{ $shelf->deskripsi }}</textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('shelves.index') }}" class="px-4 py-3 text-sm text-gray-600 hover:text-gray-900 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection