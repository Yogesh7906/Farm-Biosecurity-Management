<!DOCTYPE html>
<html lang="en" class="h-full bg-zinc-950 text-zinc-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Biosecurity Portal') | Digital Farm Management</title>
    
    <!-- Premium Google Fonts for Zero-G Sci-Fi Aesthetics -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS via CDN with Futuristic Extensions -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        orbitron: ['Orbitron', 'sans-serif'],
                        mono: ['Share Tech Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: 'rgba(0, 240, 255, 0.05)',
                            100: 'rgba(0, 240, 255, 0.1)',
                            300: '#67e8f9',
                            400: '#22d3ee',
                            500: '#00f0ff', // Cyber Neon Cyan!
                            600: '#0891b2',
                            700: '#0e7490',
                        },
                        danger: {
                            50: 'rgba(255, 42, 95, 0.05)',
                            100: 'rgba(255, 42, 95, 0.1)',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#ff2a5f', // Pulse Neon Red!
                            600: '#e11d48',
                            700: '#be123c',
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
            --neon-cyan-rgb: 0, 240, 255;
            --neon-red-rgb: 255, 42, 95;
            --theme-brand-rgb: var(--neon-cyan-rgb);
            --theme-color-300: #67e8f9;
            --theme-color-400: #22d3ee;
            --theme-color-500: #00f0ff;
            --theme-color-600: #0891b2;
            --theme-color-700: #0e7490;
            --theme-glow: rgba(0, 240, 255, 0.22);
            --theme-bg-gradient: radial-gradient(circle at top right, rgba(0, 240, 255, 0.09), transparent 45%),
                                 radial-gradient(circle at bottom left, rgba(255, 42, 95, 0.04), transparent 45%),
                                 #020205;
        }

        [data-theme="cyber"] {
            --theme-brand-rgb: 249, 115, 22;
            --theme-color-300: #fdba74;
            --theme-color-400: #fb923c;
            --theme-color-500: #f97316;
            --theme-color-600: #ea580c;
            --theme-color-700: #c2410c;
            --theme-glow: rgba(249, 115, 22, 0.20);
            --theme-bg-gradient: radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 45%),
                                 radial-gradient(circle at bottom left, rgba(139, 92, 246, 0.06), transparent 45%),
                                 #040207;
        }

        [data-theme="crimson"] {
            --theme-brand-rgb: var(--neon-red-rgb);
            --theme-color-300: #fda4af;
            --theme-color-400: #fb7185;
            --theme-color-500: #ff2a5f;
            --theme-color-600: #e11d48;
            --theme-color-700: #be123c;
            --theme-glow: rgba(255, 42, 95, 0.20);
            --theme-bg-gradient: radial-gradient(circle at top right, rgba(255, 42, 95, 0.12), transparent 45%),
                                 radial-gradient(circle at bottom left, rgba(0, 0, 0, 0.95), transparent 45%),
                                 #030001;
        }

        [data-theme="aqua"] {
            --theme-brand-rgb: 6, 182, 212;
            --theme-color-300: #67e8f9;
            --theme-color-400: #22d3ee;
            --theme-color-500: #06b6d4;
            --theme-color-600: #0891b2;
            --theme-color-700: #0e7490;
            --theme-glow: rgba(6, 182, 212, 0.20);
            --theme-bg-gradient: radial-gradient(circle at top right, rgba(6, 182, 212, 0.12), transparent 45%),
                                 radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.06), transparent 45%),
                                 #010205;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--theme-bg-gradient);
            transition: background 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), color 0.4s ease;
        }

        /* Glassmorphism Panels with backdrop-blur & thin glowing borders */
        .glass-panel {
            background: rgba(10, 10, 15, 0.68);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.04);
            transition: all 0.45s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Z-Axis Depth scales for floating effect */
        .depth-low {
            border-color: rgba(255, 255, 255, 0.03);
            background: rgba(6, 6, 9, 0.6);
            box-shadow: 0 4px 18px -2px rgba(0, 0, 0, 0.65);
        }

        .depth-mid {
            border-color: rgba(255, 255, 255, 0.05);
            background: rgba(11, 11, 16, 0.72);
            box-shadow: 0 10px 28px -5px rgba(0, 0, 0, 0.75), 
                        0 0 15px rgba(var(--theme-brand-rgb), 0.015);
        }

        .depth-high {
            border-color: rgba(var(--theme-brand-rgb), 0.12);
            background: rgba(17, 17, 24, 0.8);
            box-shadow: 0 20px 42px -10px rgba(0, 0, 0, 0.85), 
                        0 0 25px rgba(var(--theme-brand-rgb), 0.035);
        }

        /* Ambient Glow Displays */
        .glow-theme {
            box-shadow: 0 0 15px var(--theme-glow),
                        inset 0 0 8px rgba(var(--theme-brand-rgb), 0.12);
        }
        .glow-red {
            box-shadow: 0 0 15px rgba(255, 42, 95, 0.22),
                        inset 0 0 8px rgba(255, 42, 95, 0.12);
        }

        /* Anti-Gravity physics for interactive hover cards */
        .anti-gravity-hover {
            transition: transform 0.45s cubic-bezier(0.25, 1, 0.3, 1),
                        border-color 0.45s ease,
                        box-shadow 0.45s ease;
        }
        .anti-gravity-hover:hover {
            transform: translateY(-8px) scale(1.015);
            border-color: rgba(var(--theme-brand-rgb), 0.28);
            box-shadow: 0 22px 38px -8px rgba(0, 0, 0, 0.85),
                        0 0 25px rgba(var(--theme-brand-rgb), 0.1),
                        inset 0 0 10px rgba(var(--theme-brand-rgb), 0.06);
        }
    </style>
    @yield('styles')
</head>
<body class="h-full flex flex-col relative overflow-x-hidden select-none" 
      x-data="{ portalTheme: localStorage.getItem('portal-theme') || 'emerald' }" 
      x-init="$watch('portalTheme', val => { localStorage.setItem('portal-theme', val); document.documentElement.setAttribute('data-theme', val) }); document.documentElement.setAttribute('data-theme', portalTheme)" 
      :data-theme="portalTheme">

    <!-- Zero-Gravity Particle Dust Canvas Field -->
    <canvas id="zero-g-dust" class="fixed inset-0 pointer-events-none z-0 opacity-40"></canvas>

    <!-- Header Grid Container -->
    <header class="glass-panel sticky top-0 z-40 border-b border-zinc-900/60 backdrop-blur-xl relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Title and High-Tech Badge -->
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-brand-600 to-brand-500 flex items-center justify-center glow-theme border border-brand-400/20">
                        <svg class="h-5.5 w-5.5 text-zinc-950 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold font-orbitron bg-gradient-to-r from-brand-400 via-zinc-100 to-brand-600 bg-clip-text text-transparent uppercase tracking-wider">BIO-GUARD</span>
                        <span class="block text-[8px] font-mono font-semibold tracking-widest text-zinc-400 uppercase -mt-0.5">Farm Management Portal</span>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <nav class="hidden md:flex space-x-2">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-bold font-orbitron uppercase tracking-wider transition-all {{ request()->routeIs('dashboard') ? 'bg-zinc-900/60 text-brand-400 border border-brand-500/20 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900/40' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('visitors.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold font-orbitron uppercase tracking-wider transition-all {{ request()->routeIs('visitors.*') ? 'bg-zinc-900/60 text-brand-400 border border-brand-500/20 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900/40' }}">
                        Visitor Logs
                    </a>
                    <a href="{{ route('audits.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold font-orbitron uppercase tracking-wider transition-all {{ request()->routeIs('audits.*') ? 'bg-zinc-900/60 text-brand-400 border border-brand-500/20 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900/40' }}">
                        Biosecurity Audits
                    </a>
                    <a href="{{ route('alerts.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold font-orbitron uppercase tracking-wider transition-all {{ request()->routeIs('alerts.*') ? 'bg-zinc-900/60 text-brand-400 border border-brand-500/20 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900/40' }}">
                        Health Alerts
                    </a>
                </nav>

                <!-- Operations & Shield System Selector -->
                <div class="flex items-center space-x-4 z-50">
                    <div class="relative mr-1" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="p-2 rounded-lg bg-zinc-900/80 border border-zinc-800 text-zinc-400 hover:text-brand-400 hover:border-brand-500/20 transition-all flex items-center justify-center" 
                                title="Select Biosecurity Matrix Theme">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-xl bg-zinc-950/95 border border-zinc-900 shadow-2xl py-1.5 z-50 text-left glass-panel" 
                             style="display: none;">
                            <span class="block px-3 py-1 text-[8px] font-mono font-extrabold uppercase tracking-widest text-zinc-500 border-b border-zinc-900 mb-1">Containment Matrix</span>
                            
                            <button @click="portalTheme = 'emerald'; open = false" class="w-full px-3 py-2 text-[10px] font-bold font-orbitron uppercase text-zinc-300 hover:text-cyan-400 hover:bg-cyan-500/10 flex items-center space-x-2 transition-all">
                                <span class="h-2.5 w-2.5 rounded-full bg-cyan-400 shadow-[0_0_8px_rgba(0,240,255,0.4)]"></span>
                                <span>Emerald Portal</span>
                            </button>
                            <button @click="portalTheme = 'cyber'; open = false" class="w-full px-3 py-2 text-[10px] font-bold font-orbitron uppercase text-zinc-300 hover:text-orange-400 hover:bg-orange-500/10 flex items-center space-x-2 transition-all">
                                <span class="h-2.5 w-2.5 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.4)]"></span>
                                <span>Cyber Quarantine</span>
                            </button>
                            <button @click="portalTheme = 'crimson'; open = false" class="w-full px-3 py-2 text-[10px] font-bold font-orbitron uppercase text-zinc-300 hover:text-rose-400 hover:bg-rose-500/10 flex items-center space-x-2 transition-all">
                                <span class="h-2.5 w-2.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(255,42,95,0.4)]"></span>
                                <span>Crimson Containment</span>
                            </button>
                            <button @click="portalTheme = 'aqua'; open = false" class="w-full px-3 py-2 text-[10px] font-bold font-orbitron uppercase text-zinc-300 hover:text-cyan-400 hover:bg-cyan-500/10 flex items-center space-x-2 transition-all">
                                <span class="h-2.5 w-2.5 rounded-full bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.4)]"></span>
                                <span>Aqua Clean-Zone</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 border-l border-zinc-900 pl-4 z-50">
                        <div class="h-8 w-8 rounded-full bg-zinc-900 border border-brand-500/20 flex items-center justify-center text-xs font-mono font-bold text-brand-400">
                            {{ substr(Auth::user()->name ?? 'V', 0, 1) }}
                        </div>
                        <span class="hidden sm:inline-block text-[10px] font-bold font-orbitron uppercase tracking-wider text-zinc-300">
                            {{ Auth::user()->name ?? 'Veterinarian/Admin' }}
                        </span>
                        
                        @auth
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg bg-zinc-900/80 border border-zinc-800 text-zinc-400 hover:text-danger-400 hover:border-danger-500/30 transition-all" title="Secure Logout">
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

    <!-- Main Dynamic Interface -->
    <main class="flex-grow py-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Toast Telemetry Notifications -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl border border-brand-500/30 bg-brand-500/10 text-brand-300 flex items-center justify-between glow-theme">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-brand-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-mono uppercase tracking-wider">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl border border-danger-500/30 bg-danger-500/10 text-danger-300 flex items-center justify-between glow-red">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-danger-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="text-xs font-mono uppercase tracking-wider text-danger-300">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl border border-danger-500/30 bg-danger-500/10 text-danger-300">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-danger-400 mt-0.5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <span class="text-xs font-bold font-orbitron uppercase tracking-wider">Please correct the errors below:</span>
                            <ul class="mt-1.5 list-disc list-inside text-xs font-mono text-danger-400 space-y-1">
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

    <!-- Cyber Compliance Footer -->
    <footer class="glass-panel border-t border-zinc-900/60 py-6 mt-12 text-center text-[10px] text-zinc-500 relative z-10">
        <div class="max-w-7xl mx-auto px-4">
            <p class="font-semibold">© {{ date('Y') }} Bio-Guard Biosecurity Portal. Built strictly in compliance with Smart India Hackathon SIH25006.</p>
            <p class="mt-1 text-zinc-600 font-mono">Dynamic Pig & Poultry Segregations • Automatic Outbreak Containment • Real-time Mortality Tracking</p>
        </div>
    </footer>

    <!-- Zero-Gravity Particle Drift Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('zero-g-dust');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;

            const particles = [];
            const count = 70;

            for(let i=0; i<count; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    r: 0.8 + Math.random() * 1.5,
                    d: Math.random() * 100,
                    speedY: - (0.05 + Math.random() * 0.15), // Majestic slow drift upward
                    speedX: (Math.random() - 0.5) * 0.08,
                    opacity: 0.15 + Math.random() * 0.45
                });
            }

            function draw() {
                ctx.clearRect(0, 0, width, height);
                for(let i=0; i<count; i++) {
                    const p = particles[i];
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2, true);
                    ctx.fillStyle = `rgba(0, 240, 255, ${p.opacity})`;
                    ctx.fill();

                    // Wiggle and drift
                    p.y += p.speedY;
                    p.x += p.speedX + Math.sin(p.d + p.y * 0.005) * 0.04;

                    // Loop back to bottom
                    if(p.y < -10) {
                        p.y = height + 10;
                        p.x = Math.random() * width;
                    }
                    if(p.x < -10 || p.x > width + 10) {
                        p.x = Math.random() * width;
                    }
                }
                requestAnimationFrame(draw);
            }

            draw();

            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
