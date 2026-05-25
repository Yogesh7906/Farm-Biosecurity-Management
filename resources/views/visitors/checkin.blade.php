@extends('layouts.app')

@section('title', 'Visitor & Vehicle Digital Check-In')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="{ hasVehicle: false, visitedOther: false }">
    
    <div>
        <a href="{{ route('visitors.index') }}" class="text-xs text-brand-400 hover:underline flex items-center space-x-1">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Visitor Logs</span>
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-white mt-2">Digital Visitor Check-In</h1>
        <p class="text-xs text-zinc-400 mt-1">Mandatory biosecurity declaration in accordance with poultry and pig health regulations.</p>
    </div>

    <!-- Registration Card -->
    <div class="glass-panel rounded-2xl p-8">
        <form action="{{ route('visitors.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Select Farm Profile -->
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

            <!-- Primary Contact details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Visitor Full Name *</label>
                    <input type="text" name="name" id="name" placeholder="e.g. Dr. Jane Doe" required
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div class="space-y-2">
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Phone Number *</label>
                    <input type="tel" name="phone" id="phone" placeholder="e.g. +91 9876543210" required
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Purpose of Visit -->
                <div class="space-y-2">
                    <label for="purpose" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Purpose of Visit *</label>
                    <select name="purpose" id="purpose" required 
                            class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="" disabled selected>-- Select Purpose --</option>
                        <option value="Veterinary Inspection">Veterinary Inspection</option>
                        <option value="Feed Delivery">Feed Delivery</option>
                        <option value="Equipment Repair">Equipment Repair / Maintenance</option>
                        <option value="Auditing & Inspection">Compliance Auditing</option>
                        <option value="General Operations">General Operations</option>
                    </select>
                </div>

                <!-- Body Temp Check -->
                <div class="space-y-2">
                    <label for="temperature" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Body Temp Check (°C)</label>
                    <input type="number" step="0.1" name="temperature" id="temperature" placeholder="e.g. 36.8" 
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors">
                    <span class="block text-[10px] text-zinc-500">Automated warning will trigger for readings &ge; 38.0°C.</span>
                </div>
            </div>

            <!-- MANDATORY BIOSECURITY QUARANTINE QUESTIONS -->
            <div class="p-5 rounded-xl bg-zinc-950/60 border border-zinc-800/80 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-brand-500 flex items-center space-x-1.5">
                    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Biosecurity Clearance Check</span>
                </h3>

                <!-- Question: Visited other farm in 48 hours -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-zinc-300">Has the visitor entered another livestock/poultry farm in the past 48 hours? *</label>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center space-x-2 text-sm text-zinc-300 cursor-pointer">
                            <input type="radio" name="visited_other_farm_past_48h" value="1" x-on:change="visitedOther = true"
                                   class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-900 border-zinc-800">
                            <span>Yes</span>
                        </label>
                        <label class="flex items-center space-x-2 text-sm text-zinc-300 cursor-pointer">
                            <input type="radio" name="visited_other_farm_past_48h" value="0" x-on:change="visitedOther = false" checked
                                   class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-900 border-zinc-800">
                            <span>No (Safe clearance)</span>
                        </label>
                    </div>
                    
                    <!-- Alert dynamic helper -->
                    <div x-show="visitedOther" x-transition class="mt-3 p-3 rounded-lg bg-danger-500/10 border border-danger-500/10 text-danger-400 text-xs">
                        🚨 Warning: Answering **YES** places this visitor under mandatory biosecurity quarantine. Direct entry to animal containment coops/pens will be restricted.
                    </div>
                </div>
            </div>

            <!-- Vehicle Log Trigger (Dynamic Alpine) -->
            <div class="space-y-4 border-t border-zinc-800/80 pt-6">
                <div class="flex items-center space-x-3 cursor-pointer select-none">
                    <input type="checkbox" id="toggle_vehicle" x-model="hasVehicle"
                           class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-900 border-zinc-800 rounded">
                    <label for="toggle_vehicle" class="text-xs font-bold uppercase tracking-wider text-zinc-300 cursor-pointer">Vehicle is entering farm grounds</label>
                </div>

                <div x-show="hasVehicle" x-transition class="p-5 rounded-xl bg-zinc-950/60 border border-zinc-800/80 space-y-4 mt-2">
                    <div class="space-y-2">
                        <label for="vehicle_plate" class="block text-xs font-semibold text-zinc-300">Vehicle License Plate Number</label>
                        <input type="text" name="vehicle_plate" id="vehicle_plate" placeholder="e.g. DL 1C AB 1234"
                               class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-zinc-300">Has the vehicle wheels & undercarriage undergone chemical pressure sanitation at the entry checkpoint?</label>
                        <div class="flex items-center space-x-6 mt-1">
                            <label class="flex items-center space-x-2 text-sm text-zinc-300 cursor-pointer">
                                <input type="radio" name="vehicle_sanitized" value="1" checked
                                       class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-900 border-zinc-800">
                                <span>Yes (Sanitized)</span>
                            </label>
                            <label class="flex items-center space-x-2 text-sm text-zinc-300 cursor-pointer">
                                <input type="radio" name="vehicle_sanitized" value="0"
                                       class="h-4 w-4 text-brand-500 focus:ring-0 focus:ring-offset-0 bg-zinc-900 border-zinc-800">
                                <span>No (Sanitation Pending)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div class="space-y-2">
                <label for="remarks" class="block text-xs font-bold uppercase tracking-wider text-zinc-300">Additional Remarks / Declared Cargo</label>
                <textarea name="remarks" id="remarks" rows="3" placeholder="List any items or cargo being brought into farm boundaries..."
                          class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-brand-500 transition-colors"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 border-t border-zinc-800 pt-6">
                <a href="{{ route('visitors.index') }}" class="px-5 py-3 text-xs font-bold text-zinc-400 hover:text-white uppercase tracking-wider">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-brand-500 hover:bg-brand-600 text-zinc-950 font-extrabold text-xs rounded-xl shadow-lg transition-all glow-theme uppercase tracking-widest">
                    Confirm Check-In
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
