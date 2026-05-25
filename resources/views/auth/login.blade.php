<!DOCTYPE html>
<html lang="en" class="h-full bg-zinc-950 text-zinc-100"
      x-data="{ portalTheme: localStorage.getItem('portal-theme') || 'emerald' }" 
      x-init="document.documentElement.setAttribute('data-theme', portalTheme)" 
      :data-theme="portalTheme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biosecurity Portal Login | Digital Farm Management</title>
    
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
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glow-theme {
            box-shadow: 0 0 30px var(--theme-glow);
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4">

    <div class="w-full max-w-md space-y-6">
        
        <!-- Logo / Title -->
        <div class="text-center space-y-2">
            <div class="mx-auto h-12 w-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-brand-400 flex items-center justify-center glow-theme">
                <svg class="h-7 w-7 text-zinc-950 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">BIO-GUARD PORTAL</h1>
            <p class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Digital Biosecurity surveillance</p>
        </div>

        <!-- Session Status & Errors -->
        @if(session('success'))
            <div class="p-4 rounded-xl border border-brand-500/20 bg-brand-500/10 text-brand-300 text-xs font-semibold text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl border border-danger-500/20 bg-danger-500/10 text-danger-300 text-xs font-semibold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Container -->
        <div class="glass-panel rounded-3xl p-8 glow-theme space-y-6">
            
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="e.g. amit@biosecurity.gov.in"
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-700 focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">Password</label>
                    </div>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-700 focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center space-x-2 select-none">
                    <input type="checkbox" name="remember" id="remember"
                           class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-900 border-zinc-800 rounded">
                    <label for="remember" class="text-xs text-zinc-400 cursor-pointer">Remember this hardware station</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-brand-500 hover:bg-brand-600 text-zinc-950 font-extrabold text-xs rounded-xl shadow-lg transition-all glow-theme uppercase tracking-widest mt-2">
                    Establish Connection
                </button>
            </form>

            <!-- Seeded demo details -->
            <div class="p-4 rounded-xl bg-zinc-950/60 border border-zinc-800 text-[11px] text-zinc-400 space-y-1">
                <span class="block font-bold text-brand-500 uppercase tracking-wider">🔬 Demo Access Details</span>
                <span class="block mt-1"><strong>Username:</strong> amit@biosecurity.gov.in</span>
                <span class="block"><strong>Password:</strong> password</span>
            </div>
            
            <!-- Register link -->
            <p class="text-center text-xs text-zinc-500">
                Need to register a new livestock establishment? 
                <a href="{{ route('register') }}" class="text-brand-400 font-bold hover:underline">Sign Up Profile</a>
            </p>
        </div>

    </div>

</body>
</html>
