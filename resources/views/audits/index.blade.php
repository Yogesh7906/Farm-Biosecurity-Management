@extends('layouts.app')

@section('title', 'Biosecurity Audit Register')

@section('content')
<div class="space-y-6">
    
    <!-- Top Row: Headers and Filter Form -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Biosecurity Audit Register</h1>
            <p class="text-xs text-zinc-400 mt-1">Logs of all biosecurity compliance and checklist audits executed on site.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('audits.index') }}" class="flex items-center space-x-2 bg-zinc-900 border border-zinc-800 p-1 rounded-xl">
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

            <a href="{{ route('audits.create') }}" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-zinc-950 font-extrabold text-xs rounded-xl shadow-lg transition-all glow-theme uppercase tracking-widest">
                + Run Audit
            </a>
        </div>
    </div>

    <!-- Audits Log Data Table -->
    <div class="glass-panel rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-800 bg-zinc-900/30 text-zinc-400 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Audit Date & Auditor</th>
                        <th class="px-6 py-4">Farm Profile</th>
                        <th class="px-6 py-4 text-center">Cleaning Done</th>
                        <th class="px-6 py-4 text-center">Sanitation Zones</th>
                        <th class="px-6 py-4 text-center">Boundaries Passed</th>
                        <th class="px-6 py-4 text-center">Score</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60 text-sm text-zinc-300">
                    @forelse($audits as $audit)
                        <tr class="hover:bg-zinc-900/10 transition-colors">
                            <!-- Auditor details -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-white">{{ $audit->auditor_name }}</div>
                                <div class="text-xs text-zinc-500 font-semibold mt-0.5">{{ $audit->audit_date->format('M d, Y') }}</div>
                            </td>

                            <!-- Farm Profile -->
                            <td class="px-6 py-4">
                                <div class="font-semibold text-zinc-200">{{ $audit->farm->name }}</div>
                                <div class="text-[10px] text-zinc-500 capitalize">{{ $audit->farm->farm_type }}</div>
                            </td>

                            <!-- Cleaning -->
                            <td class="px-6 py-4 text-center">
                                @if($audit->cleaning_done)
                                    <span class="inline-flex h-6 w-6 rounded-full bg-brand-500/10 text-brand-400 items-center justify-center font-bold text-xs">✓</span>
                                @else
                                    <span class="inline-flex h-6 w-6 rounded-full bg-rose-500/10 text-rose-400 items-center justify-center font-bold text-xs">✗</span>
                                @endif
                            </td>

                            <!-- Sanitation -->
                            <td class="px-6 py-4 text-center">
                                @if($audit->sanitation_zones_checked)
                                    <span class="inline-flex h-6 w-6 rounded-full bg-brand-500/10 text-brand-400 items-center justify-center font-bold text-xs">✓</span>
                                @else
                                    <span class="inline-flex h-6 w-6 rounded-full bg-rose-500/10 text-rose-400 items-center justify-center font-bold text-xs">✗</span>
                                @endif
                            </td>

                            <!-- Boundaries -->
                            <td class="px-6 py-4 text-center">
                                @if($audit->boundary_checks_passed)
                                    <span class="inline-flex h-6 w-6 rounded-full bg-brand-500/10 text-brand-400 items-center justify-center font-bold text-xs">✓</span>
                                @else
                                    <span class="inline-flex h-6 w-6 rounded-full bg-rose-500/10 text-rose-400 items-center justify-center font-bold text-xs">✗</span>
                                @endif
                            </td>

                            <!-- Score -->
                            <td class="px-6 py-4 text-center font-extrabold text-base">
                                <span class="{{ $audit->score >= 80 ? 'text-brand-400' : ($audit->score >= 50 ? 'text-amber-400' : 'text-rose-500') }}">
                                    {{ $audit->score }}%
                                </span>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider 
                                    {{ $audit->score >= 80 ? 'bg-brand-500/20 text-brand-300 border border-brand-500/30' : ($audit->score >= 50 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-danger-500/20 text-danger-300 border border-danger-500/30') }}">
                                    {{ $audit->score >= 80 ? 'EXCELLENT' : ($audit->score >= 50 ? 'ATTN NEEDED' : 'RISKY') }}
                                </span>
                            </td>
                        </tr>
                        @if($audit->remarks)
                            <tr class="bg-zinc-900/5">
                                <td colspan="7" class="px-6 py-2.5 text-[11px] text-zinc-500 italic border-b border-zinc-800/40">
                                    <strong>Inspector Notes:</strong> {{ $audit->remarks }}
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-500">
                                <svg class="h-10 w-10 text-zinc-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>No biosecurity audits recorded yet. Conduct your first audit to list logs.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($audits->hasPages())
            <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-900/10">
                {{ $audits->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
