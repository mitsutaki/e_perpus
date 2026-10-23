<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Katalog') - Perpus Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .bg-app { background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
    </style>
</head>
<body class="bg-app text-gray-800 min-h-screen">

    <!-- Top Navigation -->
    <header class="sticky top-0 z-40 glass border-b border-white/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="text-xl font-semibold tracking-tight">Perpus<span class="text-gray-400">.ID</span></span>
                </div>

                <!-- Menu -->
                <nav class="flex items-center gap-1 sm:gap-4">
                    <a href="{{ route('katalog.index') }}" class="px-3 py-2 text-sm rounded-lg transition {{ request()->routeIs('katalog.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-white/80' }}">
                        <span class="hidden sm:inline">Katalog</span>
                        <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </a>
                    <a href="{{ route('anggota.bookings') }}" class="px-3 py-2 text-sm rounded-lg transition {{ request()->routeIs('anggota.*') ? 'bg-slate-900 text-white' : 'text-gray-600 hover:bg-white/80' }}">
                        <span class="hidden sm:inline">Bookinganku</span>
                        <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </a>
                </nav>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                        <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=e2e8f0&color=1e293b' }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 leading-none mt-1">Anggota</p>
                        </div>
                    </button>

                    <div x-cloak x-show="open" @click.outside="open = false" x-transition.origin.top.right class="absolute right-0 mt-2 w-56 glass border border-white/60 rounded-xl shadow-xl py-2">
                        <div class="px-4 py-2 border-b border-gray-200/50">
                            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50/50 transition">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>
</body>
</html>