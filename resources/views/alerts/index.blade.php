@extends('layouts.app')

@section('title', 'Outbreak Alert Logs & Containment')

@section('content')
<div class="space-y-6">
    
    <!-- Top Row: Headers and Filter Form -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Health Alerts & Containment Logs</h1>
            <p class="text-xs text-zinc-400 mt-1">Real-time epidemiological containment logs, quarantine levels, and automatic vaccination triggers.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('alerts.index') }}" class="flex items-center space-x-2 bg-zinc-900 border border-zinc-800 p-1 rounded-xl">
                <select name="farm_id" onchange="this.form.submit()" 
                        class="bg-transparent border-0 text-xs text-zinc-300 font-bold px-3 py-1.5 focus:outline-none focus:ring-0">
                    <option value="">All Farms</option>
                    @foreach($farms as $farm)
                        <option value="{{ $farm->id }}" {{ request('farm_id') == $farm->id ? 'selected' : '' }}>
                            {{ $farm->name }} ({{ ucfirst($farm->farm_type) }})
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('alerts.create') }}" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-zinc-950 font-extrabold text-xs rounded-xl shadow-lg transition-all glow-theme uppercase tracking-widest">
                + Daily Metrics Log
            </a>
        </div>
    </div>

    <!-- Health Alerts Data Table -->
    <div class="glass-panel rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-800 bg-zinc-900/30 text-zinc-400 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Shed & Farm Zone</th>
                        <th class="px-6 py-4">Inspection Date</th>
                        <th class="px-6 py-4 text-center">Daily Mortality</th>
                        <th class="px-6 py-4 text-center">Mortality Rate</th>
                        <th class="px-6 py-4 text-center">Quarantine Status</th>
                        <th class="px-6 py-4 text-center">Vaccine Schedule</th>
                        <th class="px-6 py-4 text-center">Alert Level</th>
                        <th class="px-6 py-4 text-right">Resolution</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60 text-sm text-zinc-300">
                    @forelse($alerts as $alert)
                        <tr class="hover:bg-zinc-900/10 transition-colors {{ $alert->alert_level === 'critical' && $alert->status === 'active' ? 'bg-danger-500/5' : '' }}">
                            <!-- Shed & Farm Zone -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-white">{{ $alert->shed->name }}</div>
                                <div class="text-xs text-zinc-500 font-semibold">{{ $alert->shed->farm->name }} ({{ ucfirst($alert->shed->farm->farm_type) }})</div>
                            </td>

                            <!-- Date Logged -->
                            <td class="px-6 py-4 font-semibold text-zinc-300">
                                {{ $alert->date_logged->format('M d, Y') }}
                            </td>

                            <!-- Daily Mortality Count -->
                            <td class="px-6 py-4 text-center font-bold">
                                {{ number_format($alert->daily_mortality_count) }}
                            </td>

                            <!-- Calculated Mortality Rate -->
                            <td class="px-6 py-4 text-center font-extrabold text-sm">
                                <span class="{{ $alert->mortality_rate >= 5.0 ? 'text-rose-400' : ($alert->mortality_rate >= 2.0 ? 'text-amber-400' : 'text-brand-400') }}">
                                    {{ number_format($alert->mortality_rate, 2) }}%
                                </span>
                            </td>

                            <!-- Quarantine Status -->
                            <td class="px-6 py-4 text-center">
                                @if($alert->quarantine_triggered)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[9px] font-extrabold bg-danger-500/20 text-danger-300 border border-danger-500/30 glow-red animate-pulse">
                                        QUARANTINED
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold bg-zinc-800 text-zinc-500">
                                        UNRESTRICTED
                                    </span>
                                @endif
                            </td>

                            <!-- Vaccine Drops -->
                            <td class="px-6 py-4 text-center">
                                @if($alert->vaccine_drop_scheduled)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[9px] font-extrabold bg-brand-500/20 text-brand-300 border border-brand-500/30">
                                        💉 VACCINE DROP
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold bg-zinc-800 text-zinc-500">
                                        NONE
                                    </span>
                                @endif
                            </td>

                            <!-- Alert Level -->
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider inline-block
                                    {{ $alert->alert_level === 'critical' ? 'bg-danger-500/20 text-danger-300 border border-danger-500/30 glow-red' : ($alert->alert_level === 'warning' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-brand-500/20 text-brand-300 border border-brand-500/20') }}">
                                    {{ $alert->alert_level }}
                                </span>
                            </td>

                            <!-- Action / Resolution Status -->
                            <td class="px-6 py-4 text-right">
                                @if($alert->status === 'active')
                                    @if($alert->alert_level === 'critical' || $alert->alert_level === 'warning')
                                        <form action="{{ route('alerts.resolve', $alert->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1.5 bg-brand-500 hover:bg-brand-600 text-zinc-950 font-extrabold text-xs rounded-lg transition-all glow-theme uppercase tracking-wide">
                                                Resolve Alert
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-brand-400 text-xs font-semibold">Active Clear</span>
                                    @endif
                                @else
                                    <div class="text-zinc-500 text-xs font-semibold flex items-center justify-end space-x-1.5">
                                        <svg class="h-4 w-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Resolved</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @if($alert->remarks)
                            <tr class="bg-zinc-900/5">
                                <td colspan="8" class="px-6 py-2.5 text-[11px] text-zinc-500 italic border-b border-zinc-800/40">
                                    <strong>Outbreak log notes:</strong> {{ $alert->remarks }}
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-zinc-500">
                                <svg class="h-10 w-10 text-zinc-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>No health alert incidents recorded. Keep monitoring daily logs.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($alerts->hasPages())
            <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-900/10">
                {{ $alerts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
