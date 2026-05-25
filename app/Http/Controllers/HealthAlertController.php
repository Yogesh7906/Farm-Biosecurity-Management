<?php

namespace App\Http\Controllers;

use App\Models\HealthAlert;
use App\Models\Shed;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthAlertController extends Controller
{
    /**
     * Display a list of health alerts.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $farmId = $request->get('farm_id');

        $query = HealthAlert::with('shed.farm');

        if ($farmId) {
            $query->whereHas('shed', function ($q) use ($farmId) {
                $q->where('farm_id', $farmId);
            });
        } elseif ($user) {
            $query->whereHas('shed', function ($q) use ($user) {
                $q->whereIn('farm_id', $user->farms->pluck('id'));
            });
        }

        $alerts = $query->orderBy('date_logged', 'desc')->paginate(10);
        $farms = $user ? $user->farms : Farm::all();

        return view('alerts.index', compact('alerts', 'farms'));
    }

    /**
     * Show form to log daily mortality metrics.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user) {
            $sheds = Shed::whereIn('farm_id', $user->farms->pluck('id'))->with('farm')->get();
        } else {
            $sheds = Shed::with('farm')->get();
        }

        return view('alerts.create', compact('sheds'));
    }

    /**
     * Store and analyze daily mortality, executing automatic quarantine alerts.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shed_id' => 'required|exists:sheds,id',
            'date_logged' => 'required|date|before_or_equal:today',
            'daily_mortality_count' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ]);

        $shed = Shed::findOrFail($validated['shed_id']);
        $population = $shed->current_population;
        $mortality = (int)$validated['daily_mortality_count'];

        // 1. Calculate mortality rate dynamically
        $mortalityRate = 0.00;
        if ($population > 0) {
            $mortalityRate = round(($mortality / $population) * 100, 2);
        }

        // 2. Determine thresholds & triggers
        $alertLevel = 'normal';
        $quarantineTriggered = false;
        $vaccineDropScheduled = false;

        if ($mortalityRate >= 5.00) {
            // OUTBREAK THRESHOLD EXCEEDED (>= 5% mortality)
            $alertLevel = 'critical';
            $quarantineTriggered = true;
            $vaccineDropScheduled = true;
        } elseif ($mortalityRate >= 2.00) {
            // WARNING THRESHOLD (>= 2% but < 5%)
            $alertLevel = 'warning';
            // Vaccine drops may be pre-scheduled on warnings for pig/poultry as a precaution
            $vaccineDropScheduled = true; 
        }

        // 3. Create the alert
        $alert = HealthAlert::create([
            'shed_id' => $shed->id,
            'date_logged' => $validated['date_logged'],
            'daily_mortality_count' => $mortality,
            'mortality_rate' => $mortalityRate,
            'alert_level' => $alertLevel,
            'quarantine_triggered' => $quarantineTriggered,
            'vaccine_drop_scheduled' => $vaccineDropScheduled,
            'status' => 'active',
            'remarks' => $validated['remarks'] ?? ($quarantineTriggered 
                ? "CRITICAL: Mortality threshold of 5% breached! Quarantine protocol activated automatically."
                : "Daily check completed successfully."),
        ]);

        // 4. Update Shed Population (subtract mortalities)
        if ($mortality > 0) {
            $newPopulation = max(0, $population - $mortality);
            $shed->update(['current_population' => $newPopulation]);
        }

        $messageType = ($alertLevel === 'critical') ? 'error' : (($alertLevel === 'warning') ? 'warning' : 'success');
        $message = "Daily log recorded. Mortality Rate: {$mortalityRate}%. ";
        
        if ($quarantineTriggered) {
            $message .= "🚨 EMERGENCY WARNING: 5% Mortality exceeded! Quarantine activated for {$shed->name}!";
            return redirect()->route('alerts.index', ['farm_id' => $shed->farm_id])
                ->with('error', $message);
        }

        return redirect()->route('alerts.index', ['farm_id' => $shed->farm_id])
            ->with('success', $message);
    }

    /**
     * Resolve a critical health alert.
     */
    public function resolve($id)
    {
        $alert = HealthAlert::findOrFail($id);
        $alert->update([
            'status' => 'resolved',
            'remarks' => $alert->remarks . "\n[Resolved on " . date('Y-m-d H:i:s') . " by authorized veterinarian]"
        ]);

        return redirect()->back()->with('success', "Health alert #{$id} has been marked as RESOLVED.");
    }
}
