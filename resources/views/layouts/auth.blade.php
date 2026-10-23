<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth') - Perpus Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Split Screen Layout */
        .split-container {
            display: flex;
            min-height: 100vh;
        }
        .split-left {
            width: 50%;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #1e293b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .split-right {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 2rem;
        }
        
        @media (max-width: 1024px) {
            .split-left { display: none; }
            .split-right { width: 100%; }
        }
        
        /* Animated Background Gradient */
        .split-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 80%, rgba(168, 85, 247, 0.1) 0%, transparent 50%);
            animation: gradientMove 15s ease-in-out infinite;
        }
        @keyframes gradientMove {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(5%, 5%); }
        }
        
        /* Floating Books Animation */
        .floating-book {
            position: absolute;
            opacity: 0.1;
            color: white;
            animation: floatBook 8s ease-in-out infinite;
        }
        .floating-book:nth-child(1) { top: 15%; left: 10%; animation-delay: 0s; font-size: 60px; }
        .floating-book:nth-child(2) { top: 60%; left: 75%; animation-delay: 2s; font-size: 80px; }
        .floating-book:nth-child(3) { top: 80%; left: 15%; animation-delay: 4s; font-size: 50px; }
        .floating-book:nth-child(4) { top: 25%; left: 80%; animation-delay: 1s; font-size: 70px; }
        .floating-book:nth-child(5) { top: 50%; left: 50%; animation-delay: 3s; font-size: 90px; }
        
        @keyframes floatBook {
            0%, 100% { transform: translateY(0) rotate(-5deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        /* Glass Card on Right */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }
        
        /* Fade In Animation */
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Floating Label */
        .floating-label {
            position: relative;
        }
        .floating-label input {
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.8);
        }
        .floating-label input:focus {
            border-color: #1e293b;
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
            background: white;
        }
        .floating-label label {
            position: absolute;
            left: 16px;
            top: 14px;
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
            transition: all 0.2s ease;
            background: transparent;
            padding: 0 4px;
        }
        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label {
            top: -8px;
            font-size: 11px;
            color: #1e293b;
            font-weight: 500;
            background: white;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        
        /* Logo Animation */
        .logo-pulse {
            animation: pulse 3s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body>

<div class="split-container">

    <!-- LEFT SIDE: Branding -->
    <div class="split-left">
        <!-- Floating Books Ornaments -->
        <div class="floating-book">
            <svg width="60" height="60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div class="floating-book">
            <svg width="80" height="80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div class="floating-book">
            <svg width="50" height="50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div class="floating-book">
            <svg width="70" height="70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div class="floating-book">
            <svg width="90" height="90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>

        <!-- Branding Content -->
        <div class="relative z-10 text-white text-center px-12 fade-in">
            <!-- Logo -->
            <div class="logo-pulse inline-flex items-center justify-center w-24 h-24 bg-white/10 backdrop-blur-md rounded-3xl mb-8 border border-white/20">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            
            <h1 class="text-4xl font-bold tracking-tight mb-3">Perpus Digital</h1>
            <p class="text-lg text-slate-300 mb-8 max-w-md mx-auto">Sistem Informasi Manajemen Perpustakaan Modern</p>
            
            <!-- Feature Highlights -->
            <div class="grid grid-cols-3 gap-6 max-w-md mx-auto mt-12">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-2xl mb-3">
                        <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"></path></svg>
                    </div>
                    <p class="text-sm text-slate-300 font-medium">Kelola Buku</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-2xl mb-3">
                        <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm text-slate-300 font-medium">Transaksi</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-2xl mb-3">
                        <svg class="w-6 h-6 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <p class="text-sm text-slate-300 font-medium">Laporan</p>
                </div>
            </div>
            
            <!-- Quote -->
            <p class="text-xs text-slate-400 mt-12 italic">"Membaca adalah jendela dunia"</p>
        </div>
    </div>

    <!-- RIGHT SIDE: Form -->
    <div class="split-right">
        <main class="w-full max-w-md fade-in">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>