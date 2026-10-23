@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('header_title', 'Edit Kategori')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-light tracking-tight">Edit Kategori</h2>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ $category->nama_kategori }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">{{ $category->deskripsi }}</textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('categories.index') }}" class="px-4 py-3 text-sm text-gray-600 hover:text-gray-900 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection