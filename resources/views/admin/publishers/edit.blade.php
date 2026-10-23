@extends('layouts.app')

@section('title', 'Edit Penerbit')
@section('header_title', 'Edit Data Penerbit')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-light tracking-tight">Edit Penerbit</h2>
    </div>

    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        <form action="{{ route('publishers.update', $publisher) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Nama Penerbit</label>
                <input type="text" name="nama_penerbit" value="{{ $publisher->nama_penerbit }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Telepon</label>
                <input type="text" name="telepon" value="{{ $publisher->telepon }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ $publisher->email }}" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">{{ $publisher->alamat }}</textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('publishers.index') }}" class="px-4 py-3 text-sm text-gray-600 hover:text-gray-900 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection