@extends('layouts.app')

@section('title', 'Edit User')
@section('header_title', 'Edit Data User')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="glass border border-white/60 rounded-2xl shadow-sm p-8">
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Username</label>
                    <input type="text" name="username" value="{{ $user->username }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Password Baru (Kosongkan jika tak ubah)</label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wider">Role</label>
                    <select name="role" required class="w-full px-4 py-3 bg-white/70 backdrop-blur-md border border-white/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                        <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-4 py-3 text-sm text-gray-600 hover:text-gray-900 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection