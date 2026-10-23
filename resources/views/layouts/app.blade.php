<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Perpus Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .bg-app { background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
    </style>
</head>
<body class="bg-app text-gray-800 min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Top Bar (Mobile Only) -->
    <div class="lg:hidden fixed top-0 left-0 right-0 z-30 h-16 glass border-b border-gray-200/50 flex items-center justify-between px-4 no-print">
        <button @click="sidebarOpen = true" class="p-2 -ml-2 rounded-lg hover:bg-white/80 transition">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <span class="text-lg font-semibold tracking-tight">Perpus<span class="text-gray-400">.ID</span></span>
        <div class="w-10"></div> <!-- Spacer biar logo tetap di tengah -->
    </div>

    <!-- Spacer biar konten gak ketutup top bar di mobile -->
    <div class="h-16 lg:hidden no-print"></div>

    <!-- Mobile Sidebar Overlay -->
    <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" 
         x-transition.opacity
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden no-print" 
         style="display: none;"></div>

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-50 h-screen w-72 glass-sidebar border-r border-gray-200/50 transition-transform duration-300 ease-out lg:translate-x-0 lg:z-40" 
           id="sidebar"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           style="scroll-behavior: smooth;">
        
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-between border-b border-gray-200/50 px-5">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-slate-900 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"></path></svg>
                </div>
                <span class="text-xl font-semibold tracking-tight">Perpus<span class="text-gray-400">.ID</span></span>
            </div>
            <!-- Close button (mobile only) -->
            <button @click="sidebarOpen = false" class="lg:hidden p-2 -mr-2 text-gray-500 hover:text-gray-900 hover:bg-white/60 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Nav Menu -->
        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
            <a href="{{ route('dashboard') }}" id="sidebar-dashboard" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-white/80 hover:text-gray-900' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            @if(auth()->user()->role === 'super_admin')
                <!-- MENU SUPER ADMIN -->
                <div class="pt-4">
                    <p class="px-4 text-xs uppercase tracking-wider text-gray-400 mb-2">Manajemen Sistem</p>
                    <a href="{{ route('users.index') }}" id="sidebar-users" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('users.*') ? 'bg-white/80 text-gray-900' : '' }}">
                            </a>
                    <a href="{{ route('reports.index') }}" id="sidebar-reports" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('reports.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan
                    </a>
                </div>

            @elseif(auth()->user()->role === 'admin')
                <!-- MENU ADMIN -->
                <div class="pt-4">
                    <p class="px-4 text-xs uppercase tracking-wider text-gray-400 mb-2">Operasional</p>
                    <a href="{{ route('bookings.index') }}" id="sidebar-bookings" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('bookings.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Booking Masuk
                    </a>
                    <a href="{{ route('loans.index') }}" id="sidebar-loans" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('loans.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Peminjaman
                    </a>
                    <a href="{{ route('returns.index') }}" id="sidebar-returns" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('returns.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path></svg>
                        Pengembalian
                    </a>
                    <a href="{{ route('fines.index') }}" id="sidebar-fines" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('fines.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                        Denda
                    </a>
                </div>
                <div class="pt-4">
                    <p class="px-4 text-xs uppercase tracking-wider text-gray-400 mb-2">Master Data</p>
                    <a href="{{ route('members.index') }}" id="sidebar-members" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('members.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Anggota
                    </a>
                    <a href="{{ route('books.index') }}" id="sidebar-books" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('books.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Data Buku
                    </a>
                    <a href="{{ route('categories.index') }}" id="sidebar-categories" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('categories.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Kategori
                    </a>
                    <a href="{{ route('publishers.index') }}" id="sidebar-publishers" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('publishers.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Penerbit
                    </a>
                    <a href="{{ route('shelves.index') }}" id="sidebar-shelves" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('shelves.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Rak / Etalase
                    </a>
                </div>
                <div class="pt-4">
                    <p class="px-4 text-xs uppercase tracking-wider text-gray-400 mb-2">Setup</p>
                    <a href="{{ route('settings.index') }}" id="sidebar-settings" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('settings.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        Pengaturan
                    </a>
                </div>

            @elseif(auth()->user()->role === 'petugas')
                <!-- MENU PETUGAS -->
                <div class="pt-4">
                    <p class="px-4 text-xs uppercase tracking-wider text-gray-400 mb-2">Operasional</p>
                    <a href="{{ route('bookings.index') }}" id="sidebar-bookings" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('bookings.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Booking Masuk
                    </a>
                    <a href="{{ route('loans.index') }}" id="sidebar-loans" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('loans.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Peminjaman
                    </a>
                    <a href="{{ route('returns.index') }}" id="sidebar-returns" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('returns.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path></svg>
                        Pengembalian
                    </a>
                    <a href="{{ route('fines.index') }}" id="sidebar-fines" class="flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:bg-white/80 hover:text-gray-900 transition {{ request()->routeIs('fines.*') ? 'bg-white/80 text-gray-900' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                        Denda
                    </a>
                </div>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-72 min-h-screen flex flex-col">
        <header class="h-16 sticky top-0 z-30 glass border-b border-white/60 flex items-center justify-between px-4 sm:px-6">
            <h1 class="text-base sm:text-lg font-semibold tracking-tight hidden sm:block">@yield('header_title', 'Dashboard')</h1>
            
            <div class="relative ml-auto" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=e2e8f0&color=1e293b' }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 leading-none mt-1 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </button>

                <div x-cloak x-show="open" @click.outside="open = false" x-transition.origin.top.right class="absolute right-0 mt-2 w-56 glass border border-white/60 rounded-xl shadow-xl py-2">
                    <div class="px-4 py-2 border-b border-gray-200/50 sm:hidden">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50/50 transition">Keluar</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6">
            @yield('content')
        </main>
    </div>    <!-- WELCOME POPUP + TOUR -->
    @if(isset($showTour) && $showTour)
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userRole = '{{ auth()->user()->role }}';

        // ============================================
        // 0. CLEANUP sisa overlay Driver.js dari sesi sebelumnya
        // ============================================
        document.body.classList.remove('driver-active');
        document.querySelectorAll('#driver-page-overlay, #driver-popover-content, .driver-active').forEach(el => el.remove());
        document.body.style.pointerEvents = 'auto';

        // ============================================
        // 1. WELCOME POPUP HTML
        // ============================================
        const welcomeHtml = `
            <div class="fixed inset-0 z-[99999] flex items-center justify-center p-4" id="welcomeOverlay" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); pointer-events: auto;">
                <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl" style="pointer-events: auto; position: relative; z-index: 100000;">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Selamat Datang di Perpus Digital! 👋</h2>
                        <p class="text-sm text-slate-500 mb-6">Halo <strong>{{ auth()->user()->name }}</strong>, sebelum mulai, yuk kenalan dengan fitur-fitur sistem selama 30 detik!</p>
                        
                        <div class="bg-slate-50 rounded-2xl p-4 mb-6 text-left">
                            <p class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Yang akan kamu lihat:</p>
                            <ul class="space-y-2 text-sm text-slate-600">
                                <li class="flex items-center gap-2"><span>📋</span> Menu navigasi sesuai role kamu</li>
                                <li class="flex items-center gap-2"><span>📊</span> Statistik & ringkasan perpus</li>
                                <li class="flex items-center gap-2"><span>⚡</span> Aksi cepat transaksi</li>
                            </ul>
                        </div>
                        
                        <div class="flex gap-3" style="pointer-events: auto;">
                            <button id="skipTourBtn" type="button" class="flex-1 py-3 px-4 text-sm text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition font-medium" style="pointer-events: auto; cursor: pointer;">
                                Lewati
                            </button>
                            <button id="startTourBtn" type="button" class="flex-1 py-3 px-4 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition" style="pointer-events: auto; cursor: pointer;">
                                Mulai Tour 🚀
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', welcomeHtml);

        // ============================================
        // 2. TOUR STEPS (per role)
        // ============================================
        function getTourSteps() {
            const pos = { side: 'right', align: 'start' };
            const steps = [
                {
                    element: '#sidebar-dashboard',
                    popover: { 
                        title: '📌 Dashboard', 
                        description: 'Halaman utama berisi statistik dan aktivitas terbaru sistem.',
                        ...pos
                    }
                }
            ];

            if (userRole === 'admin') {
                steps.push(
                    { element: '#sidebar-members', popover: { title: '👥 Anggota', description: 'Kelola data anggota perpustakaan.', ...pos } },
                    { element: '#sidebar-books', popover: { title: '📚 Data Buku', description: 'Tambah, edit, hapus koleksi buku.', ...pos } },
                    { element: '#sidebar-categories', popover: { title: '🗂️ Kategori', description: 'Atur kategori buku (Fiksi, Sains, dll).', ...pos } },
                    { element: '#sidebar-publishers', popover: { title: '🏢 Penerbit', description: 'Kelola data penerbit buku.', ...pos } },
                    { element: '#sidebar-shelves', popover: { title: '📦 Rak / Etalase', description: 'Atur lokasi penyimpanan buku.', ...pos } },
                    { element: '#sidebar-loans', popover: { title: '📅 Peminjaman', description: 'Proses transaksi peminjaman buku.', ...pos } },
                    { element: '#sidebar-returns', popover: { title: '↩️ Pengembalian', description: 'Proses pengembalian + auto-hitung denda.', ...pos } },
                    { element: '#sidebar-fines', popover: { title: '💵 Denda', description: 'Lihat & tandai lunas denda anggota.', ...pos } },
                    { element: '#sidebar-settings', popover: { title: '⚙️ Pengaturan', description: 'Atur denda per hari, batas pinjam, dll.', ...pos } }
                );
            } else if (userRole === 'petugas') {
                steps.push(
                    { element: '#sidebar-loans', popover: { title: '📅 Peminjaman', description: 'Proses transaksi peminjaman buku ke anggota.', ...pos } },
                    { element: '#sidebar-returns', popover: { title: '↩️ Pengembalian', description: 'Proses pengembalian buku.', ...pos } },
                    { element: '#sidebar-fines', popover: { title: '💵 Denda', description: 'Tandai lunas denda yang sudah dibayar anggota.', ...pos } }
                );
            } else if (userRole === 'super_admin') {
                steps.push(
                    { element: '#sidebar-users', popover: { title: '👥 Manajemen User', description: 'Buat & kelola akun admin/petugas.', ...pos } },
                    { element: '#sidebar-reports', popover: { title: '📊 Laporan', description: 'Lihat grafik & rekap laporan perpustakaan.', ...pos } }
                );
            }

            return steps;
        }

        // ============================================
        // 3. MARK TOUR SEEN (AJAX ke server)
        // ============================================
        function markTourSeen() {
            fetch('{{ route("tour.complete") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            }).catch(err => console.log('Tour mark error:', err));
        }

        // ============================================
        // 4. SCROLL SIDEBAR KE ELEMEN (INSTANT, tanpa smooth)
        // ============================================
        function scrollToElement(el) {
            if (!el) return;
            const nav = document.querySelector('#sidebar nav');
            if (nav) {
                const navRect = nav.getBoundingClientRect();
                const elRect = el.getBoundingClientRect();
                nav.scrollTop = nav.scrollTop + (elRect.top - navRect.top) - (navRect.height - elRect.height) / 2;
            }
        }

        // ============================================
        // 5. START TOUR
        // ============================================
        function startTour() {
            document.getElementById('welcomeOverlay')?.remove();
            
            if (typeof window.driver !== 'function') {
                console.error('❌ driver.js belum ter-load.');
                alert('Library tour gagal dimuat. Cek koneksi internet lalu refresh halaman.');
                markTourSeen();
                return;
            }

            const firstStepEl = document.querySelector('#sidebar-dashboard');
            if (!firstStepEl) {
                console.error('❌ Element #sidebar-dashboard tidak ditemukan!');
                markTourSeen();
                return;
            }
            
            setTimeout(() => {
                try {
                    const driverObj = window.driver({
                        showProgress: true,
                        allowClose: true,
                        nextBtnText: 'Lanjut →',
                        prevBtnText: '← Kembali',
                        doneBtnText: 'Selesai ✓',
                        steps: getTourSteps(),
                        onDestroyed: markTourSeen,
                        onHighlightStarted: (el) => {
                            scrollToElement(el);
                        }
                    });
                    driverObj.drive();
                } catch (e) {
                    console.error('❌ Error starting tour:', e);
                    alert('Gagal mulai tour: ' + e.message);
                    markTourSeen();
                }
            }, 200);
        }

        // ============================================
        // 6. SKIP TOUR
        // ============================================
        function skipTour() {
            document.getElementById('welcomeOverlay')?.remove();
            markTourSeen();
        }

        // ============================================
        // 7. BIND BUTTONS
        // ============================================
        document.getElementById('startTourBtn').addEventListener('click', startTour);
        document.getElementById('skipTourBtn').addEventListener('click', skipTour);
    });
    </script>
    @endif
    <!-- END WELCOME POPUP + TOUR -->
</body>
</html>