@extends('layouts.app')

@section('title', 'Dynamic Biosecurity Dashboard')

@section('content')
<div class="space-y-8" x-data="{ farmType: '{{ $activeFarm->farm_type ?? 'poultry' }}' }">
    
    <!-- Top Row: Farm Profile Picker & Telemetry Grid Title -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 relative z-10">
        <div>
            <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-brand-400">Enterprise Monitoring</span>
            <h1 class="text-3xl font-black font-orbitron tracking-wider text-white mt-1 uppercase">Farm Biosecurity Management</h1>
        </div>

        <!-- Farm Profile Selector Tab/Pills -->
        <div class="flex items-center space-x-2 bg-zinc-950/80 p-1.5 rounded-xl border border-zinc-900 self-start lg:self-center backdrop-blur-md">
            @foreach($farms as $farm)
                <a href="?farm_id={{ $farm->id }}" 
                   class="px-4 py-2 rounded-lg text-[10px] font-bold font-orbitron uppercase tracking-wider transition-all flex items-center space-x-2 {{ $activeFarm->id === $farm->id ? 'bg-brand-500 text-zinc-950 glow-theme' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900/60' }}">
                    @if($farm->farm_type === 'poultry')
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span>{{ $farm->name }} (Poultry)</span>
                    @else
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>{{ $farm->name }} (Pig)</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <!-- Active Outbreak Alarm Banner (Pulsing Red) -->
    @if($stats['critical_outbreaks_count'] > 0)
        <div class="p-6 rounded-2xl bg-danger-500/10 border border-danger-500/30 text-danger-300 flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-pulse glow-red relative z-10">
            <div class="flex items-center space-x-4">
                <div class="h-12 w-12 rounded-xl bg-danger-500 flex items-center justify-center text-zinc-950 font-bold shrink-0 shadow-[0_0_15px_rgba(255,42,95,0.4)]">
                    <svg class="h-7 w-7 text-zinc-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold font-orbitron text-white uppercase tracking-wide">Critical Biosecurity Alert!</h3>
                    <p class="text-xs font-mono text-danger-400 mt-1">Mortality rate threshold (5%) breached in {{ $stats['critical_outbreaks_count'] }} active containment zones. Quarantine protocols initiated.</p>
                </div>
            </div>
            <a href="{{ route('alerts.index', ['farm_id' => $activeFarm->id]) }}" class="px-5 py-2.5 bg-danger-600 hover:bg-danger-700 text-white font-extrabold font-orbitron text-[10px] rounded-xl shadow-lg transition-all self-start md:self-center uppercase tracking-widest glow-red">
                Manage Containments
            </a>
        </div>
    @endif

    <!-- Stat Summary Cards Row (Frosted glass depth-mid with Anti-Gravity drift hover physics) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
        <!-- Stat: Population -->
        <div class="glass-panel depth-mid rounded-2xl p-6 relative overflow-hidden anti-gravity-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Total Herd/Flock</p>
                    <h3 class="text-3xl font-extrabold font-mono text-white mt-2">{{ number_format($stats['total_population']) }}</h3>
                </div>
                <div class="p-2 bg-zinc-950/80 border border-zinc-900 rounded-xl text-brand-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-[10px] font-mono text-zinc-400 mb-1">
                    <span>Occupancy Rate</span>
                    <span>{{ $stats['occupancy_rate'] }}%</span>
                </div>
                <div class="w-full bg-zinc-950 h-2 rounded-full border border-zinc-900 overflow-hidden">
                    <div class="bg-brand-500 h-full rounded-full transition-all duration-500 shadow-[0_0_8px_rgba(0,240,255,0.6)]" style="width: {{ min(100, $stats['occupancy_rate']) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Stat: Compliance Score -->
        <div class="glass-panel depth-mid rounded-2xl p-6 relative overflow-hidden anti-gravity-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Biosecurity Score</p>
                    <h3 class="text-3xl font-extrabold font-mono mt-2 {{ $stats['avg_audit_score'] >= 80 ? 'text-brand-400 glow-cyan' : ($stats['avg_audit_score'] >= 50 ? 'text-amber-400' : 'text-danger-500 glow-red') }}">
                        {{ $stats['avg_audit_score'] }}%
                    </h3>
                </div>
                <div class="p-2 bg-zinc-950/80 border border-zinc-900 rounded-xl text-brand-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center space-x-2">
                <span class="text-[9px] font-mono px-2 py-0.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300 font-semibold uppercase tracking-wider">
                    @if($stats['avg_audit_score'] >= 80)
                        Highly Secure
                    @elseif($stats['avg_audit_score'] >= 50)
                        Needs Attention
                    @else
                        At High Risk
                    @endif
                </span>
                <span class="text-[9px] font-mono text-zinc-500">Based on recent audits</span>
            </div>
        </div>

        <!-- Stat: Quarantine Visitors -->
        <div class="glass-panel depth-mid rounded-2xl p-6 relative overflow-hidden anti-gravity-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Active Quarantines</p>
                    <h3 class="text-3xl font-extrabold font-mono mt-2 {{ $stats['quarantined_visitors_count'] > 0 ? 'text-danger-500 glow-red' : 'text-brand-400 glow-cyan' }}">
                        {{ $stats['quarantined_visitors_count'] }}
                    </h3>
                </div>
                <div class="p-2 bg-zinc-950/80 border border-zinc-900 rounded-xl text-brand-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-mono text-zinc-400 leading-tight">
                    {{ $stats['quarantined_visitors_count'] > 0 ? 'Exposure risk identified.' : 'No biosecurity exposures detected today.' }}
                </p>
            </div>
        </div>

        <!-- Stat: Health Incidents -->
        <div class="glass-panel depth-mid rounded-2xl p-6 relative overflow-hidden anti-gravity-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-widest">Containment Status</p>
                    <h3 class="text-3xl font-extrabold font-mono mt-2 {{ $stats['active_alerts_count'] > 0 ? 'text-danger-500 glow-red animate-pulse' : 'text-brand-400 glow-cyan' }}">
                        {{ $stats['active_alerts_count'] > 0 ? 'WARNING' : 'SECURE' }}
                    </h3>
                </div>
                <div class="p-2 bg-zinc-950/80 border border-zinc-900 rounded-xl text-brand-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center space-x-2 text-[10px] font-mono">
                <span class="{{ $stats['active_alerts_count'] > 0 ? 'text-danger-400' : 'text-brand-400' }} font-bold">{{ $stats['active_alerts_count'] }}</span>
                <span class="text-zinc-500">active health alert logs</span>
            </div>
        </div>
    </div>

    <!-- Real-time Biosecurity Compliance & Telemetry Curves (High-Intensity Neon Gradients) -->
    <div class="glass-panel depth-mid rounded-2xl p-6 relative overflow-hidden z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-zinc-900 pb-4 mb-6 gap-4">
            <div>
                <h2 class="text-base font-bold font-orbitron text-white flex items-center space-x-2 uppercase tracking-wide">
                    <svg class="h-5 w-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Biosecurity Telemetry & Vector Analysis</span>
                </h2>
                <p class="text-[10px] text-zinc-400 mt-1 font-mono">Real-time compliance tracking, visitor sanitization curves, and containment thresholds.</p>
            </div>
            
            <div class="flex items-center space-x-4 text-[10px] font-mono">
                <span class="flex items-center space-x-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-brand-500 shadow-[0_0_8px_rgba(0,240,255,0.6)]"></span>
                    <span class="text-zinc-300 font-bold uppercase">Compliance Index</span>
                </span>
                <span class="flex items-center space-x-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-danger-500 shadow-[0_0_8px_rgba(255,42,95,0.6)]"></span>
                    <span class="text-zinc-300 font-bold uppercase">Mortality Threshold</span>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- SVG Chart Area (Left 2 cols) -->
            <div class="lg:col-span-2 relative h-64 w-full bg-zinc-950/60 rounded-xl p-4 border border-zinc-900 flex flex-col justify-between">
                <!-- SVG Line Graph with glowing linear gradients -->
                <div class="relative flex-grow">
                    <svg class="w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none">
                        <defs>
                            <!-- Cyan Neon Gradient -->
                            <linearGradient id="chart-cyan-gradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#00f0ff" stop-opacity="0.32" />
                                <stop offset="100%" stop-color="#00f0ff" stop-opacity="0.0" />
                            </linearGradient>
                            <!-- Pulse Neon Red Gradient -->
                            <linearGradient id="chart-red-gradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#ff2a5f" stop-opacity="0.22" />
                                <stop offset="100%" stop-color="#ff2a5f" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        
                        <!-- Dotted Containment Grid Lines -->
                        <line x1="0" y1="30" x2="500" y2="30" stroke="#18181b" stroke-width="0.8" stroke-dasharray="4,4" />
                        <line x1="0" y1="60" x2="500" y2="60" stroke="#18181b" stroke-width="0.8" stroke-dasharray="4,4" />
                        <line x1="0" y1="90" x2="500" y2="90" stroke="#18181b" stroke-width="0.8" stroke-dasharray="4,4" />
                        <line x1="0" y1="120" x2="500" y2="120" stroke="#18181b" stroke-width="0.8" stroke-dasharray="4,4" />

                        <!-- Area Under Compliance Line -->
                        <path d="M 0 150 L 0 60 Q 80 40 160 85 T 320 30 Q 410 45 500 15 L 500 150 Z" fill="url(#chart-cyan-gradient)" />
                        <!-- Area Under Mortality Line -->
                        <path d="M 0 150 L 0 130 Q 80 125 160 140 T 320 110 Q 410 135 500 138 L 500 150 Z" fill="url(#chart-red-gradient)" />

                        <!-- glowing neon lines -->
                        <path d="M 0 60 Q 80 40 160 85 T 320 30 Q 410 45 500 15" fill="none" stroke="#00f0ff" stroke-width="3" stroke-linecap="round" filter="drop-shadow(0px 0px 4px rgba(0,240,255,0.5))" />
                        <path d="M 0 130 Q 80 125 160 140 T 320 110 Q 410 135 500 138" fill="none" stroke="#ff2a5f" stroke-width="2" stroke-linecap="round" stroke-dasharray="2,2" filter="drop-shadow(0px 0px 3px rgba(255,42,95,0.4))" />

                        <!-- Interactive Nodes -->
                        <circle cx="160" cy="85" r="5.5" fill="#020205" stroke="#00f0ff" stroke-width="2.5" />
                        <circle cx="320" cy="30" r="5.5" fill="#020205" stroke="#00f0ff" stroke-width="2.5" />
                        <circle cx="320" cy="110" r="5" fill="#020205" stroke="#ff2a5f" stroke-width="2" />
                    </svg>
                </div>
                
                <!-- X-Axis Labels -->
                <div class="flex justify-between text-[8px] font-mono text-zinc-500 font-semibold mt-2 pt-2 border-t border-zinc-900/60 uppercase tracking-widest">
                    <span>Mon (22nd)</span>
                    <span>Tue (23rd)</span>
                    <span>Wed (24th)</span>
                    <span>Thu (25th)</span>
                    <span>Fri (Today)</span>
                </div>
            </div>

            <!-- Side Tech Metrics Column -->
            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-zinc-950/60 border border-zinc-900 flex items-center justify-between">
                    <div>
                        <span class="text-[8px] font-mono uppercase font-bold text-zinc-500 tracking-widest">Containment Speed</span>
                        <h4 class="text-base font-bold font-orbitron text-white mt-0.5">14.80 MS</h4>
                        <p class="text-[9px] font-mono text-brand-400 flex items-center space-x-1 mt-0.5">
                            <span>▲ 8.2% faster response today</span>
                        </p>
                    </div>
                    <div class="p-2 bg-brand-500/10 rounded-lg border border-brand-500/20 text-brand-400 glow-theme">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-zinc-950/60 border border-zinc-900 flex items-center justify-between">
                    <div>
                        <span class="text-[8px] font-mono uppercase font-bold text-zinc-500 tracking-widest">Isolation Strictness</span>
                        <h4 class="text-base font-bold font-orbitron text-white mt-0.5">99.84% Verified</h4>
                        <p class="text-[9px] font-mono text-zinc-500 flex items-center space-x-1 mt-0.5">
                            <span>Zero perimeter containment leaks</span>
                        </p>
                    </div>
                    <div class="p-2 bg-danger-500/10 rounded-lg border border-danger-500/20 text-danger-400 glow-red">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-zinc-950/60 border border-zinc-900 flex items-center justify-between">
                    <div>
                        <span class="text-[8px] font-mono uppercase font-bold text-zinc-500 tracking-widest">Disinfections Logged</span>
                        <h4 class="text-base font-bold font-orbitron text-white mt-0.5">42 Cycles</h4>
                        <p class="text-[9px] font-mono text-zinc-500 flex items-center space-x-1 mt-0.5">
                            <span>Across all coops and farrowing pens</span>
                        </p>
                    </div>
                    <div class="p-2 bg-cyan-500/10 rounded-lg border border-cyan-500/20 text-cyan-400 glow-theme">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout Split: Dynamic Profiling and Shed Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
        
        <!-- Left 2-Columns: Shed Layouts & Dynamic Biosecurity Checklists -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Poultry Farm Segment vs Pig Farm Segment -->
            <div class="glass-panel depth-mid rounded-2xl p-6 relative overflow-hidden">
                <div class="border-b border-zinc-900 pb-4 mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold font-orbitron text-white flex items-center space-x-2 uppercase tracking-wide">
                            <span>Bio-Profiling:</span>
                            <span class="text-brand-400 capitalize">{{ $activeFarm->farm_type }} Dynamic Framework</span>
                        </h2>
                        <p class="text-[10px] text-zinc-400 mt-1 font-mono">Specific guidelines in effect for this sub-category of operations.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold font-orbitron uppercase tracking-widest {{ $activeFarm->farm_type === 'poultry' ? 'bg-amber-400/10 text-amber-300 border border-amber-500/20 shadow-[0_0_8px_rgba(251,191,36,0.15)]' : 'bg-brand-500/10 text-brand-300 border border-brand-500/20 glow-theme' }}">
                        {{ $activeFarm->farm_type }}
                    </span>
                </div>

                @if($activeFarm->farm_type === 'poultry')
                    <!-- Poultry Segment Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-mono">
                        <div class="p-4 rounded-xl bg-zinc-950/40 border border-zinc-900">
                            <h3 class="text-xs font-bold font-orbitron text-zinc-200 flex items-center space-x-2 uppercase tracking-wider">
                                <span class="h-2 w-2 rounded-full bg-brand-400 animate-pulse glow-cyan"></span>
                                <span>Avian Influenza (H5N1) Containment</span>
                            </h3>
                            <p class="text-[11px] text-zinc-400 mt-3.5 leading-relaxed">
                                Maintaining full footbath dip systems with 200ppm chlorine at all coop exits. Air intake filters must undergo pressure-wash sanitization bi-weekly. Keep dynamic boundary nets active to exclude wild avian contacts.
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-zinc-950/40 border border-zinc-900">
                            <h3 class="text-xs font-bold font-orbitron text-zinc-200 flex items-center space-x-2 uppercase tracking-wider">
                                <span class="h-2 w-2 rounded-full bg-amber-400 glow-theme"></span>
                                <span>Broiler/Layer Environmental Logs</span>
                            </h3>
                            <ul class="text-[11px] text-zinc-400 mt-3.5 space-y-2.5">
                                <li class="flex justify-between border-b border-zinc-900/60 pb-1.5">
                                    <span class="text-zinc-500">Humidity Range</span>
                                    <span class="text-brand-400 font-bold">50% - 60% (Optimal)</span>
                                </li>
                                <li class="flex justify-between border-b border-zinc-900/60 pb-1.5">
                                    <span class="text-zinc-500">Air Flow Turnover</span>
                                    <span class="text-brand-400 font-bold">4.2 m³/h per bird</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-zinc-500">Ammonia Levels</span>
                                    <span class="text-amber-400 font-bold">&lt; 15 ppm (Caution)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <!-- Pig Segment Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-mono">
                        <div class="p-4 rounded-xl bg-zinc-950/40 border border-zinc-900">
                            <h3 class="text-xs font-bold font-orbitron text-zinc-200 flex items-center space-x-2 uppercase tracking-wider">
                                <span class="h-2 w-2 rounded-full bg-danger-400 animate-pulse glow-red"></span>
                                <span>African Swine Fever (ASF) Security</span>
                            </h3>
                            <p class="text-[11px] text-zinc-400 mt-3.5 leading-relaxed">
                                Mandatory double-fencing active around perimeter bounds. Vehicles are absolutely restricted from farrowing zones unless undergoing thermal-pressure disinfection. Zero swill feeding allowed; feed must be certified high-heat pelleted.
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-zinc-950/40 border border-zinc-900">
                            <h3 class="text-xs font-bold font-orbitron text-zinc-200 flex items-center space-x-2 uppercase tracking-wider">
                                <span class="h-2 w-2 rounded-full bg-brand-400 glow-theme"></span>
                                <span>Swine Housing Environmental Logs</span>
                            </h3>
                            <ul class="text-[11px] text-zinc-400 mt-3.5 space-y-2.5">
                                <li class="flex justify-between border-b border-zinc-900/60 pb-1.5">
                                    <span class="text-zinc-500">Farrowing Temp</span>
                                    <span class="text-brand-400 font-bold">22°C - 24°C (Normal)</span>
                                </li>
                                <li class="flex justify-between border-b border-zinc-900/60 pb-1.5">
                                    <span class="text-zinc-500">Slurry Pit Extraction</span>
                                    <span class="text-brand-400 font-bold">Continuous Exhaust</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-zinc-500">Disinfection Cycle</span>
                                    <span class="text-brand-400 font-bold">Every 7 Days</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Shed Listing Status -->
            <div class="glass-panel depth-mid rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6 border-b border-zinc-900 pb-3">
                    <div>
                        <h2 class="text-base font-bold font-orbitron text-white uppercase tracking-wide">Shed Status & Containment Tracking</h2>
                        <p class="text-[10px] text-zinc-400 mt-1 font-mono">Real-time health status of individual operational zones.</p>
                    </div>
                    <a href="{{ route('alerts.create') }}" class="px-4 py-2 bg-zinc-950/80 hover:bg-zinc-900 border border-zinc-900 hover:border-brand-500/20 text-brand-400 hover:text-white font-bold font-orbitron text-[9px] rounded-xl transition-all uppercase tracking-widest glow-theme">
                        + Daily Metrics Log
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($sheds as $shed)
                        @php
                            $activeShedAlert = $activeAlerts->firstWhere('shed_id', $shed->id);
                            $fillRate = $shed->capacity > 0 ? round(($shed->current_population / $shed->capacity) * 100, 1) : 0;
                        @endphp
                        <div class="p-5 rounded-xl border transition-all duration-300 anti-gravity-hover {{ $activeShedAlert && $activeShedAlert->alert_level === 'critical' ? 'bg-danger-500/5 border-danger-500/30 glow-red' : 'bg-zinc-950/45 border-zinc-900' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xs font-bold font-orbitron text-white uppercase tracking-wider">{{ $shed->name }}</h3>
                                    <span class="text-[9px] font-mono text-zinc-500 block mt-1">Capacity: {{ number_format($shed->capacity) }} | Current: {{ number_format($shed->current_population) }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[8px] font-mono font-bold uppercase tracking-wider 
                                    {{ $activeShedAlert && $activeShedAlert->alert_level === 'critical' ? 'bg-danger-500/25 text-danger-300 border border-danger-500/30 shadow-[0_0_8px_rgba(255,42,95,0.3)]' : ($activeShedAlert && $activeShedAlert->alert_level === 'warning' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-brand-500/25 text-brand-300 border border-brand-500/30 shadow-[0_0_8px_rgba(0,240,255,0.3)]') }}">
                                    {{ $activeShedAlert ? strtoupper($activeShedAlert->alert_level) : 'NORMAL' }}
                                </span>
                            </div>

                            <!-- Shed Progress Occupancy Bar -->
                            <div class="mt-4">
                                <div class="flex justify-between text-[10px] font-mono text-zinc-400 mb-1">
                                    <span>Occupancy</span>
                                    <span>{{ $fillRate }}%</span>
                                </div>
                                <div class="w-full bg-zinc-900 border border-zinc-950 h-2 rounded-full overflow-hidden">
                                    <div class="bg-brand-500 h-full rounded-full transition-all duration-500 shadow-[0_0_6px_rgba(0,240,255,0.4)]" style="width: {{ min(100, $fillRate) }}%"></div>
                                </div>
                            </div>

                            @if($activeShedAlert && $activeShedAlert->alert_level === 'critical')
                                <div class="mt-3.5 text-[10px] font-mono text-danger-400 flex items-center space-x-2 bg-danger-500/10 p-2.5 rounded-lg border border-danger-500/20">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-danger-400 animate-ping"></span>
                                    <span><strong>Quarantine active!</strong> Mortality spikes logged ({{ $activeShedAlert->mortality_rate }}%). Vaccines drops active.</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs font-mono text-zinc-500 col-span-2 py-6 text-center bg-zinc-950/20 rounded-xl border border-dashed border-zinc-900">No sheds registered on this farm profile yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right 1-Column: Logs Sidebar -->
        <div class="space-y-8">
            
            <!-- Quick Actions -->
            <div class="glass-panel depth-mid rounded-2xl p-6">
                <h2 class="text-xs font-bold font-orbitron uppercase tracking-widest text-zinc-400 mb-4 border-b border-zinc-900 pb-2">Quick Operations</h2>
                <div class="grid grid-cols-2 gap-3 font-orbitron">
                    <a href="{{ route('visitors.checkin') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-900 hover:border-brand-500/30 text-center transition-all duration-300 anti-gravity-hover">
                        <svg class="h-5 w-5 text-brand-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span class="text-[9px] font-bold text-white uppercase tracking-widest">Visitor Check-In</span>
                    </a>
                    <a href="{{ route('audits.create') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-900 hover:border-brand-500/30 text-center transition-all duration-300 anti-gravity-hover">
                        <svg class="h-5 w-5 text-brand-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span class="text-[9px] font-bold text-white uppercase tracking-widest">Run Audit</span>
                    </a>
                </div>
            </div>

            <!-- Recent Visitors Sidebar -->
            <div class="glass-panel depth-mid rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-zinc-900">
                    <h2 class="text-xs font-bold font-orbitron uppercase tracking-widest text-zinc-400">Visitor Clearance Logs</h2>
                    <a href="{{ route('visitors.index', ['farm_id' => $activeFarm->id]) }}" class="text-[10px] font-mono text-brand-400 hover:underline uppercase tracking-wider">View All</a>
                </div>

                <div class="space-y-4">
                    @forelse($recentVisitors as $visitor)
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-900/60 transition-all duration-300 anti-gravity-hover">
                            <div>
                                <h4 class="text-xs font-bold font-orbitron text-white uppercase tracking-wider">{{ $visitor->name }}</h4>
                                <span class="text-[9px] font-mono text-zinc-500 block mt-1">Purpose: {{ $visitor->purpose }}</span>
                                <span class="text-[8px] font-mono text-zinc-600 block mt-0.5">{{ $visitor->check_in_time->diffForHumans() }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[8px] font-mono font-bold uppercase tracking-wider 
                                {{ $visitor->status === 'quarantined' ? 'bg-danger-500/20 text-danger-300 border border-danger-500/20 shadow-[0_0_6px_rgba(255,42,95,0.2)]' : 'bg-brand-500/20 text-brand-300 border border-brand-500/20 shadow-[0_0_6px_rgba(0,240,255,0.2)]' }}">
                                {{ $visitor->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs font-mono text-zinc-600 py-2">No visitors recorded today.</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Audits Sidebar -->
            <div class="glass-panel depth-mid rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-zinc-900">
                    <h2 class="text-xs font-bold font-orbitron uppercase tracking-widest text-zinc-400">Biosecurity Audits</h2>
                    <a href="{{ route('audits.index', ['farm_id' => $activeFarm->id]) }}" class="text-[10px] font-mono text-brand-400 hover:underline uppercase tracking-wider">View All</a>
                </div>

                <div class="space-y-4">
                    @forelse($recentAudits as $audit)
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-900/60 transition-all duration-300 anti-gravity-hover">
                            <div>
                                <h4 class="text-xs font-mono font-bold text-white uppercase tracking-wider">Score: {{ $audit->score }}%</h4>
                                <span class="text-[9px] font-mono text-zinc-500 block mt-1">By: {{ $audit->auditor_name }}</span>
                                <span class="text-[8px] font-mono text-zinc-600 block mt-0.5">{{ $audit->audit_date->format('M d, Y') }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[8px] font-mono font-bold uppercase tracking-wider 
                                {{ $audit->score >= 80 ? 'bg-brand-500/20 text-brand-300 border border-brand-500/20 shadow-[0_0_6px_rgba(0,240,255,0.2)]' : ($audit->score >= 50 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/20' : 'bg-danger-500/20 text-danger-300 border border-danger-500/20 shadow-[0_0_6px_rgba(255,42,95,0.2)]') }}">
                                {{ $audit->score >= 80 ? 'Pass' : 'Warning' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs font-mono text-zinc-600 py-2">No audits conducted yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
