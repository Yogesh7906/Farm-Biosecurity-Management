@extends('layouts.app')

@section('title', 'Visitor & Vehicle Registers')

@section('content')
<div class="space-y-6">
    
    <!-- Top Row: Headers and Filter Form -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Visitor & Vehicle Registers</h1>
            <p class="text-xs text-zinc-400 mt-1">Real-time biosecurity tracking of all personnel within farm borders.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('visitors.index') }}" class="flex items-center space-x-2 bg-zinc-900 border border-zinc-800 p-1 rounded-xl">
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

            <a href="{{ route('visitors.checkin') }}" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-zinc-950 font-extrabold text-xs rounded-xl shadow-lg transition-all glow-theme uppercase tracking-widest">
                + Visitor Check-In
            </a>
        </div>
    </div>

    <!-- Visitors Log Data Table -->
    <div class="glass-panel rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-800 bg-zinc-900/30 text-zinc-400 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Visitor & Purpose</th>
                        <th class="px-6 py-4">Farm Profile</th>
                        <th class="px-6 py-4">Temperature</th>
                        <th class="px-6 py-4">Vehicle Details</th>
                        <th class="px-6 py-4">Check-In / Out</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60 text-sm text-zinc-300">
                    @forelse($logs as $log)
                        <tr class="hover:bg-zinc-900/10 transition-colors">
                            <!-- Visitor & Purpose -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-white">{{ $log->name }}</div>
                                <div class="text-xs text-zinc-500 font-semibold">{{ $log->phone }}</div>
                                <span class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 text-zinc-400 mt-1 inline-block">{{ $log->purpose }}</span>
                            </td>

                            <!-- Farm Profile -->
                            <td class="px-6 py-4">
                                <div class="font-semibold text-zinc-200">{{ $log->farm->name }}</div>
                                <div class="text-[10px] text-zinc-500 capitalize">{{ $log->farm->farm_type }}</div>
                            </td>

                            <!-- Body Temperature -->
                            <td class="px-6 py-4">
                                @if($log->temperature)
                                    <span class="font-bold {{ $log->temperature >= 38.0 ? 'text-rose-400' : 'text-zinc-300' }}">
                                        {{ $log->temperature }}°C
                                    </span>
                                @else
                                    <span class="text-zinc-600">-</span>
                                @endif
                            </td>

                            <!-- Vehicle Details -->
                            <td class="px-6 py-4">
                                @if($log->vehicle_plate)
                                    <div class="font-semibold text-zinc-200 text-xs">{{ $log->vehicle_plate }}</div>
                                    <span class="text-[9px] font-bold uppercase {{ $log->vehicle_sanitized ? 'text-brand-400' : 'text-rose-400' }}">
                                        {{ $log->vehicle_sanitized ? 'Sanitized' : 'Not Sanitized' }}
                                    </span>
                                @else
                                    <span class="text-zinc-600 text-xs">No Vehicle</span>
                                @endif
                            </td>

                            <!-- Check-In / Out Times -->
                            <td class="px-6 py-4 text-xs">
                                <div><strong class="text-zinc-500">In:</strong> {{ $log->check_in_time->format('M d, H:i') }}</div>
                                <div class="mt-1">
                                    <strong class="text-zinc-500">Out:</strong> 
                                    @if($log->check_out_time)
                                        {{ $log->check_out_time->format('M d, H:i') }}
                                    @else
                                        <span class="text-amber-400 font-semibold animate-pulse">On Grounds</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider inline-block
                                    {{ $log->status === 'quarantined' ? 'bg-danger-500/20 text-danger-300 border border-danger-500/20 glow-red' : 'bg-brand-500/20 text-brand-300 border border-brand-500/20' }}">
                                    {{ $log->status }}
                                </span>
                            </td>

                            <!-- Check-out Action Button -->
                            <td class="px-6 py-4 text-right">
                                @if(!$log->check_out_time)
                                    <form action="{{ route('visitors.checkout', $log->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-3 py-1.5 bg-zinc-800 hover:bg-brand-600 hover:text-zinc-950 text-zinc-300 font-bold text-xs rounded-lg transition-all border border-zinc-700">
                                            Check-Out
                                        </button>
                                    </form>
                                @else
                                    <span class="text-zinc-600 text-xs font-semibold">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @if($log->remarks)
                            <tr class="bg-zinc-900/5">
                                <td colspan="7" class="px-6 py-2.5 text-[11px] text-zinc-500 italic border-b border-zinc-800/40">
                                    <strong>Declared notes:</strong> {{ $log->remarks }}
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-500">
                                <svg class="h-10 w-10 text-zinc-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>No visitor logs found. Check in your first visitor to record logs.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-900/10">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
