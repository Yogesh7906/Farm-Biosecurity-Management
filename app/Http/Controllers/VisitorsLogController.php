<?php

namespace App\Http\Controllers;

use App\Models\VisitorsLog;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorsLogController extends Controller
{
    /**
     * Display a listing of visitor logs.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $farmId = $request->get('farm_id');

        $query = VisitorsLog::with('farm');

        if ($farmId) {
            $query->where('farm_id', $farmId);
        } elseif ($user) {
            $query->whereIn('farm_id', $user->farms->pluck('id'));
        }

        $logs = $query->orderBy('check_in_time', 'desc')->paginate(10);
        $farms = $user ? $user->farms : Farm::all();

        return view('visitors.index', compact('logs', 'farms'));
    }

    /**
     * Show the visitor registration form.
     */
    public function create()
    {
        $user = Auth::user();
        $farms = $user ? $user->farms : Farm::all();

        return view('visitors.checkin', compact('farms'));
    }

    /**
     * Store a newly created visitor log check-in.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'purpose' => 'required|string|max:255',
            'temperature' => 'nullable|numeric|between:30,45',
            'visited_other_farm_past_48h' => 'required|boolean',
            'vehicle_plate' => 'nullable|string|max:50',
            'vehicle_sanitized' => 'nullable|boolean',
            'remarks' => 'nullable|string',
        ]);

        // Evaluate biosecurity clearance status
        $visitedOther = $request->boolean('visited_other_farm_past_48h');
        $vehicleSanitized = $request->boolean('vehicle_sanitized');
        $temperature = $validated['temperature'] ? floatval($validated['temperature']) : null;
        
        $status = 'cleared';
        $remarks = $validated['remarks'] ?? '';

        // Trigger biosecurity quarantine if visitor has high temp (>38°C) or visited other farm in 48h
        if ($visitedOther) {
            $status = 'quarantined';
            $remarks .= "\n[AUTOMATIC QUARANTINE: Visitor declared visiting another farm within 48 hours. Access restricted to quarantine zones only.]";
        }

        if ($temperature && $temperature >= 38.0) {
            $status = 'quarantined';
            $remarks .= "\n[AUTOMATIC QUARANTINE: High body temperature logged ({$temperature}°C). Critical fever risk.]";
        }

        if ($validated['vehicle_plate'] && !$vehicleSanitized) {
            $status = 'quarantined';
            $remarks .= "\n[AUTOMATIC QUARANTINE: Vehicle entered without undergoing wheel sanitation.]";
        }

        $log = VisitorsLog::create([
            'farm_id' => $validated['farm_id'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'purpose' => $validated['purpose'],
            'temperature' => $temperature,
            'visited_other_farm_past_48h' => $visitedOther,
            'vehicle_plate' => $validated['vehicle_plate'] ?? null,
            'vehicle_sanitized' => $vehicleSanitized,
            'check_in_time' => now(),
            'status' => $status,
            'remarks' => trim($remarks),
        ]);

        if ($status === 'quarantined') {
            return redirect()->route('visitors.index', ['farm_id' => $log->farm_id])
                ->with('error', "⚠️ WARNING: Visitor logged under quarantine status due to biosecurity exposure risks!");
        }

        return redirect()->route('visitors.index', ['farm_id' => $log->farm_id])
            ->with('success', "Visitor checked in successfully under 'Cleared' status.");
    }

    /**
     * Record visitor checkout.
     */
    public function checkout($id)
    {
        $log = VisitorsLog::findOrFail($id);
        $log->update([
            'check_out_time' => now()
        ]);

        return redirect()->back()->with('success', "Visitor {$log->name} checked out successfully.");
    }
}
