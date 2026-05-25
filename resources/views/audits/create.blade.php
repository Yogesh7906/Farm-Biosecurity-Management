@extends('layouts.app')

@section('title', 'Conduct Biosecurity Audit')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="{ cleaning: false, sanitation: false, boundary: false }">
    
    <div>
        <a href="{{ route('audits.index') }}" class="text-xs text-brand-400 hover:underline flex items-center space-x-1">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Audit Register</span>
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-white mt-2">Log Scheduled Biosecurity Audit</h1>
        <p class="text-xs text-zinc-400 mt-1">Conduct standard perimeter checks, boundary logs, and cleaning checkmarks.</p>
    </div>

    <!-- Live Compliance Score Dynamic Display -->
    <div class="p-6 rounded-2xl border transition-all flex items-center justify-between shadow-lg"
         x-bind:class="(cleaning ? 30 : 0) + (sanitation ? 40 : 0) + (boundary ? 30 : 0) >= 80 ? 'bg-brand-500/10 border-brand-500/20 text-brand-300 glow-theme' : ((cleaning ? 30 : 0) + (sanitation ? 40 : 0) + (boundary ? 30 : 0) >= 50 ? 'bg-amber-500/10 border-amber-500/20 text-amber-300' : 'bg-danger-500/10 border-danger-500/20 text-danger-300 glow-red')">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-white">Live Compliance Score</h3>
            <p class="text-xs opacity-80 mt-1">Estimated score based on selected checklist points.</p>
        </div>
        <div class="text-right">
            <span class="text-4xl font-extrabold" x-text="(cleaning ? 30 : 0) + (sanitation ? 40 : 0) + (boundary ? 30 : 0)">0</span>
            <span class="text-lg font-bold opacity-60">/ 100</span>
        </div>
    </div>

    <!-- Auditing Log Card Form -->
    <div class="glass-panel rounded-2xl p-8">
        <form action="{{ route('audits.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Form parameters -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Select Farm -->
                <div class="space-y-2">
                    <label for="farm_id" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Target Farm Profile *</label>
                    <select name="farm_id" id="farm_id" required 
                            class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="" disabled selected>-- Select Farm --</option>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}">{{ $farm->name }} ({{ ucfirst($farm->farm_type) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Audit Date -->
                <div class="space-y-2">
                    <label for="audit_date" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Date of Inspection *</label>
                    <input type="date" name="audit_date" id="audit_date" value="{{ date('Y-m-d') }}" required
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:border-brand-500 transition-colors">
                </div>
            </div>

            <!-- Auditor Name -->
            <div class="space-y-2">
                <label for="auditor_name" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Auditor Name *</label>
                <input type="text" name="auditor_name" id="auditor_name" placeholder="e.g. Inspector Charles" required
                       class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors">
            </div>

            <!-- CHECKLIST ITEMS (DYNAMIC IMPACT ON SCORE) -->
            <div class="space-y-4 border-t border-zinc-800/80 pt-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-2">Checklist & Compliance</h3>

                <!-- 1. Cleaning -->
                <div class="flex items-start p-4 rounded-xl bg-zinc-900/60 border border-zinc-800 hover:border-zinc-700/80 transition-all cursor-pointer"
                     x-on:click="cleaning = !cleaning">
                    <div class="flex items-center h-5 mt-0.5">
                        <input type="checkbox" name="cleaning_done" id="cleaning_done" value="1" x-model="cleaning" x-on:click.stop
                               class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-950 border-zinc-800 rounded">
                    </div>
                    <div class="ml-4 select-none">
                        <label for="cleaning_done" class="block text-xs font-bold uppercase tracking-wider text-zinc-200 cursor-pointer">
                            1. Cleaning & Disinfection Completed (+30 Points)
                        </label>
                        <span class="block text-xs text-zinc-400 mt-1">
                            Sheds power washed, feeding tubes cleared, new disinfectant shavings applied, and organic residues completely eliminated.
                        </span>
                    </div>
                </div>

                <!-- 2. Sanitation Zones -->
                <div class="flex items-start p-4 rounded-xl bg-zinc-900/60 border border-zinc-800 hover:border-zinc-700/80 transition-all cursor-pointer"
                     x-on:click="sanitation = !sanitation">
                    <div class="flex items-center h-5 mt-0.5">
                        <input type="checkbox" name="sanitation_zones_checked" id="sanitation_zones_checked" value="1" x-model="sanitation" x-on:click.stop
                               class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-950 border-zinc-800 rounded">
                    </div>
                    <div class="ml-4 select-none">
                        <label for="sanitation_zones_checked" class="block text-xs font-bold uppercase tracking-wider text-zinc-200 cursor-pointer">
                            2. Sanitation Checkpoints Replenished (+40 Points)
                        </label>
                        <span class="block text-xs text-zinc-400 mt-1">
                            Entry footbaths completely refilled with fresh chlorine mixture, hand sanitizers active, and quarantine lines clearly barricaded.
                        </span>
                    </div>
                </div>

                <!-- 3. Boundary Checks -->
                <div class="flex items-start p-4 rounded-xl bg-zinc-900/60 border border-zinc-800 hover:border-zinc-700/80 transition-all cursor-pointer"
                     x-on:click="boundary = !boundary">
                    <div class="flex items-center h-5 mt-0.5">
                        <input type="checkbox" name="boundary_checks_passed" id="boundary_checks_passed" value="1" x-model="boundary" x-on:click.stop
                               class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-950 border-zinc-800 rounded">
                    </div>
                    <div class="ml-4 select-none">
                        <label for="boundary_checks_passed" class="block text-xs font-bold uppercase tracking-wider text-zinc-200 cursor-pointer">
                            3. Boundary & Exclusion Nets Secured (+30 Points)
                        </label>
                        <span class="block text-xs text-zinc-400 mt-1">
                            Double fences, wild bird exclusion nets, and rodent bait structures completely evaluated for integrity. No breaches found.
                        </span>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div class="space-y-2">
                <label for="remarks" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Auditor Observations / Remarks</label>
                <textarea name="remarks" id="remarks" rows="3" placeholder="Provide detailed remarks if certain checklists are skipped..."
                          class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 border-t border-zinc-800 pt-6">
                <a href="{{ route('audits.index') }}" class="px-5 py-3 text-xs font-bold text-zinc-400 hover:text-white uppercase tracking-wider">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-brand-500 hover:bg-brand-600 text-zinc-950 font-extrabold text-xs rounded-xl shadow-lg transition-all glow-theme uppercase tracking-widest">
                    Submit Audit Log
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
