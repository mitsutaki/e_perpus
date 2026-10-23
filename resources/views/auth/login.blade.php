@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="glass-card rounded-3xl p-8 sm:p-10">
    
    <!-- Mobile Logo (karena left side hidden di mobile) -->
    <div class="lg:hidden text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-900 rounded-2xl mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <h1 class="text-xl font-bold text-slate-900">Perpus Digital</h1>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang 👋</h2>
        <p class="text-sm text-slate-500 mt-2">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf
        
        <!-- Floating Label: Username/Email -->
        <div class="floating-label">
            <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus
                   placeholder=" "
                   class="w-full px-4 py-3.5 rounded-xl text-slate-900 focus:outline-none transition">
            <label for="login">Username atau Email</label>
        </div>

        <!-- Floating Label: Password -->
        <div class="floating-label">
            <input type="password" id="password" name="password" required
                   placeholder=" "
                   class="w-full px-4 py-3.5 rounded-xl text-slate-900 focus:outline-none transition">
            <label for="password">Password</label>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded border-gray-300 text-slate-900 focus:ring-slate-900">
                <span class="text-sm text-slate-600">Ingat saya</span>
            </label>
            <a href="#" class="text-sm text-slate-600 hover:text-slate-900 font-medium">Lupa password?</a>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full py-3.5 mt-2 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all duration-200 hover:shadow-lg hover:shadow-slate-900/20">
            Masuk
        </button>
    </form>

    <!-- Footer -->
    <p class="text-xs text-center text-slate-400 mt-8">
        © 2026 Perpus Digital · Sistem Manajemen Perpustakaan
    </p>
</div>
@endsection