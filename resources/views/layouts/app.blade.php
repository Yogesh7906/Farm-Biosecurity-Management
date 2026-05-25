<!DOCTYPE html>
<html lang="en" class="h-full bg-zinc-950 text-zinc-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Biosecurity Portal') | Digital Farm Management</title>
    
    <!-- Premium Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: 'rgba(var(--theme-brand-rgb, 16, 185, 129), 0.05)',
                            100: 'rgba(var(--theme-brand-rgb, 16, 185, 129), 0.1)',
                            300: 'var(--theme-color-300)',
                            400: 'var(--theme-color-400)',
                            500: 'var(--theme-color-500)',
                            600: 'var(--theme-color-600)',
                            700: 'var(--theme-color-700)',
                        },
                        danger: {
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- AlpineJS for Interactive Widgets -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --theme-brand-rgb: 16, 185, 129;
            --theme-color-300: #6ee7b7;
            --theme-color-400: #34d399;
            --theme-color-500: #10b981;
            --theme-color-600: #059669;
            --theme-color-700: #047857;
            --theme-glow: rgba(16, 185, 129, 0.18);
            --theme-bg-gradient: radial-gradient(circle at top right, rgba(16, 185, 129, 0.08), transparent 45%),
                                 radial-gradient(circle at bottom left, rgba(239, 68, 68, 0.05), transparent 45%),
                                 #09090b;
        }

        [data-theme="cyber"] {
            --theme-brand-rgb: 249, 115, 22;
            --theme-color-300: #fdba74;
            --theme-color-400: #fb923c;
            --theme-color-500: #f97316;
            --theme-color-600: #ea580c;
            --theme-color-700: #c2410c;
            --theme-glow: rgba(249, 115, 22, 0.18);
            --theme-bg-gradient: radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 45%),
                                 radial-gradient(circle at bottom left, rgba(139, 92, 246, 0.08), transparent 45%),
                                 #0c0614;
        }

        [data-theme="crimson"] {
            --theme-brand-rgb: 244, 63, 94;
            --theme-color-300: #fda4af;
            --theme-color-400: #fb7185;
            --theme-color-500: #f43f5e;
            --theme-color-600: #e11d48;
            --theme-color-700: #be123c;
            --theme-glow: rgba(244, 63, 94, 0.18);
            --theme-bg-gradient: radial-gradient(circle at top right, rgba(244, 63, 94, 0.12), transparent 45%),
                                 radial-gradient(circle at bottom left, rgba(0, 0, 0, 0.95), transparent 45%),
                                 #090103;
        }

        [data-theme="aqua"] {
            --theme-brand-rgb: 6, 182, 212;
            --theme-color-300: #67e8f9;
            --theme-color-400: #22d3ee;
            --theme-color-500: #06b6d4;
            --theme-color-600: #0891b2;
            --theme-color-700: #0e7490;
            --theme-glow: rgba(6, 182, 212, 0.18);
            --theme-bg-gradient: radial-gradient(circle at top right, rgba(6, 182, 212, 0.12), transparent 45%),
                                 radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.08), transparent 45%),
                                 #03070f;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--theme-bg-gradient);
            transition: background 0.4s ease, color 0.4s ease;
        }
        .glass-panel {
            background: rgba(20, 20, 25, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-panel:hover {
            border-color: rgba(var(--theme-brand-rgb), 0.15);
            box-shadow: 0 4px 20px -2px rgba(var(--theme-brand-rgb), 0.05);
        }
        .glow-theme {
            box-shadow: 0 0 25px var(--theme-glow);
        }
        .glow-red {
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.18);
        }
    </style>
    @yield('styles')
</head>
<body class="h-full flex flex-col" 
      x-data="{ portalTheme: localStorage.getItem('portal-theme') || 'emerald' }" 
      x-init="$watch('portalTheme', val => { localStorage.setItem('portal-theme', val); document.documentElement.setAttribute('data-theme', val) }); document.documentElement.setAttribute('data-theme', portalTheme)" 
      :data-theme="portalTheme">
    <!-- Dynamic Header -->
    <header class="glass-panel sticky top-0 z-40 border-b border-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo / Title -->
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-brand-600 to-brand-400 flex items-center justify-center glow-theme">
                        <svg class="h-6 w-6 text-zinc-950 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold bg-gradient-to-r from-brand-400 via-zinc-100 to-brand-500 bg-clip-text text-transparent">BIO-GUARD</span>
                        <span class="block text-[10px] text-zinc-400 uppercase tracking-widest -mt-1 font-semibold">Farm Management Portal</span>
                    </div>
                </div>

                <!-- Navigation Routes -->
                <nav class="hidden md:flex space-x-1">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-zinc-800/80 text-brand-400 border border-brand-500/20 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('visitors.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('visitors.*') ? 'bg-zinc-800/80 text-brand-400 border border-brand-500/20 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900' }}">
                        Visitor Logs
                    </a>
                    <a href="{{ route('audits.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('audits.*') ? 'bg-zinc-800/80 text-brand-400 border border-brand-500/20 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900' }}">
                        Biosecurity Audits
                    </a>
                    <a href="{{ route('alerts.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('alerts.*') ? 'bg-zinc-800/80 text-brand-400 border border-brand-500/20 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900' }}">
                        Health Alerts
                    </a>
                </nav>

                <!-- Profile and Logout -->
                <div class="flex items-center space-x-4">
                    <!-- Theme Switcher Dropdown -->
                    <div class="relative mr-2" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="p-2 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-400 hover:text-brand-400 hover:border-brand-500/30 transition-all flex items-center justify-center" 
                                title="Switch Biosecurity Theme">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </button>
                        <!-- Options -->
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-xl bg-zinc-950 border border-zinc-800 shadow-xl py-1.5 z-50 text-left glass-panel" 
                             style="display: none;">
                            <span class="block px-3 py-1 text-[10px] font-extrabold uppercase tracking-widest text-zinc-500 border-b border-zinc-900 mb-1">Select Shield Color</span>
                            <button @click="portalTheme = 'emerald'; open = false" class="w-full px-3 py-2 text-xs font-semibold text-zinc-300 hover:text-emerald-400 hover:bg-emerald-500/10 flex items-center space-x-2 transition-all">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                                <span>Emerald Portal</span>
                            </button>
                            <button @click="portalTheme = 'cyber'; open = false" class="w-full px-3 py-2 text-xs font-semibold text-zinc-300 hover:text-orange-400 hover:bg-orange-500/10 flex items-center space-x-2 transition-all">
                                <span class="h-2.5 w-2.5 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.4)]"></span>
                                <span>Cyber Quarantine</span>
                            </button>
                            <button @click="portalTheme = 'crimson'; open = false" class="w-full px-3 py-2 text-xs font-semibold text-zinc-300 hover:text-rose-400 hover:bg-rose-500/10 flex items-center space-x-2 transition-all">
                                <span class="h-2.5 w-2.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]"></span>
                                <span>Crimson Containment</span>
                            </button>
                            <button @click="portalTheme = 'aqua'; open = false" class="w-full px-3 py-2 text-xs font-semibold text-zinc-300 hover:text-cyan-400 hover:bg-cyan-500/10 flex items-center space-x-2 transition-all">
                                <span class="h-2.5 w-2.5 rounded-full bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.4)]"></span>
                                <span>Aqua Clean-Zone</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 border-l border-zinc-800 pl-4">
                        <div class="h-8 w-8 rounded-full bg-zinc-800 flex items-center justify-center text-sm font-bold text-brand-400 border border-brand-500/20">
                            {{ substr(Auth::user()->name ?? 'V', 0, 1) }}
                        </div>
                        <span class="hidden sm:inline-block text-xs font-semibold text-zinc-300">
                            {{ Auth::user()->name ?? 'Veterinarian/Admin' }}
                        </span>
                        
                        @auth
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-400 hover:text-rose-400 hover:border-rose-500/30 transition-all" title="Secure Logout">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Toast Notifications -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl border border-brand-500/20 bg-brand-500/10 text-brand-300 flex items-center justify-between glow-theme">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl border border-danger-500/20 bg-danger-500/10 text-danger-300 flex items-center justify-between glow-red">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-danger-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl border border-danger-500/20 bg-danger-500/10 text-danger-300">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-danger-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <span class="text-sm font-semibold">Please correct the errors below:</span>
                            <ul class="mt-1 list-disc list-inside text-xs text-danger-400 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="glass-panel border-t border-zinc-800 py-6 mt-12 text-center text-xs text-zinc-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>© {{ date('Y') }} Bio-Guard Biosecurity Portal. Built strictly in compliance with Smart India Hackathon SIH25006.</p>
            <p class="mt-1 text-zinc-600">Dynamic Pig & Poultry Segregations • Automatic Outbreak Containment • Real-time Mortality Tracking</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
