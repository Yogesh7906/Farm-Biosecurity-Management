@extends('layouts.app')

@section('title', 'Log Daily Shed Metrics')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    
    <div>
        <a href="{{ route('alerts.index') }}" class="text-xs text-brand-400 hover:underline flex items-center space-x-1">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Outbreak Alert Logs</span>
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-white mt-2">Log Daily Mortality Metrics</h1>
        <p class="text-xs text-zinc-400 mt-1">Submit daily population logs. Outbreak quarantine alerts trigger automatically above 5.0% mortality.</p>
    </div>

    <!-- Instructions / Biosecurity info box -->
    <div class="p-5 rounded-2xl bg-zinc-900 border border-zinc-800 text-xs text-zinc-400 space-y-2">
        <h4 class="font-bold text-zinc-200 uppercase tracking-wider">Outbreak Assessment Thresholds</h4>
        <p>Mortality rate is dynamically evaluated on submission relative to the current population of the selected shed:</p>
        <ul class="list-disc list-inside space-y-1 mt-1 pl-1">
            <li><span class="text-brand-400 font-bold">&lt; 2.0%</span>: Normal operating bounds. Green status clearance.</li>
            <li><span class="text-amber-400 font-bold">2.0% - 4.9%</span>: Precautionary warning state. Pre-emptive vaccine recommendations logged.</li>
            <li><span class="text-rose-400 font-bold">&ge; 5.0%</span>: Outbreak alert status. **Mandatory shed quarantine locks and immediate vaccination drops scheduled.**</li>
        </ul>
    </div>

    <!-- Logging Card Form -->
    <div class="glass-panel rounded-2xl p-8">
        <form action="{{ route('alerts.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Target Shed Selection -->
            <div class="space-y-2">
                <label for="shed_id" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Target Shed Zone *</label>
                <select name="shed_id" id="shed_id" required 
                        class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="" disabled selected>-- Select Shed --</option>
                    @foreach($sheds as $shed)
                        <option value="{{ $shed->id }}">
                            {{ $shed->farm->name }} &rarr; {{ $shed->name }} (Pop: {{ number_format($shed->current_population) }} / Cap: {{ number_format($shed->capacity) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date Logged -->
                <div class="space-y-2">
                    <label for="date_logged" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Date Logged *</label>
                    <input type="date" name="date_logged" id="date_logged" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}"
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <!-- Daily Mortality Count -->
                <div class="space-y-2">
                    <label for="daily_mortality_count" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Daily Mortality Count *</label>
                    <input type="number" name="daily_mortality_count" id="daily_mortality_count" min="0" placeholder="e.g. 5" required
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors">
                </div>
            </div>

            <!-- Remarks -->
            <div class="space-y-2">
                <label for="remarks" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Symptoms Observed / Remarks</label>
                <textarea name="remarks" id="remarks" rows="3" placeholder="Describe symptoms (e.g., lethargy, coughing, skin discoloration) if exceptional mortalities occurred..."
                          class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 border-t border-zinc-800 pt-6">
                <a href="{{ route('alerts.index') }}" class="px-5 py-3 text-xs font-bold text-zinc-400 hover:text-white uppercase tracking-wider">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-brand-500 hover:bg-brand-600 text-zinc-950 font-extrabold text-xs rounded-xl shadow-lg transition-all glow-theme uppercase tracking-widest">
                    Record Metrics
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
