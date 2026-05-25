<?php

namespace App\Http\Controllers;

use App\Models\BiosecurityAudit;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BiosecurityAuditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $farmId = $request->get('farm_id');

        $query = BiosecurityAudit::with('farm');

        if ($farmId) {
            $query->where('farm_id', $farmId);
        } elseif ($user) {
            $query->whereIn('farm_id', $user->farms->pluck('id'));
        }

        $audits = $query->orderBy('audit_date', 'desc')->paginate(10);
        $farms = $user ? $user->farms : Farm::all();

        return view('audits.index', compact('audits', 'farms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $farms = $user ? $user->farms : Farm::all();
        
        return view('audits.create', compact('farms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'auditor_name' => 'required|string|max:255',
            'audit_date' => 'required|date',
            'cleaning_done' => 'nullable|boolean',
            'sanitation_zones_checked' => 'nullable|boolean',
            'boundary_checks_passed' => 'nullable|boolean',
            'remarks' => 'nullable|string',
        ]);

        // Convert nullable checkboxes to booleans
        $cleaningDone = $request->boolean('cleaning_done');
        $sanitationChecked = $request->boolean('sanitation_zones_checked');
        $boundaryPassed = $request->boolean('boundary_checks_passed');

        // Dynamically compute the biosecurity compliance score out of 100
        $score = 0;
        if ($cleaningDone) $score += 30;
        if ($sanitationChecked) $score += 40;
        if ($boundaryPassed) $score += 30;

        $audit = BiosecurityAudit::create([
            'farm_id' => $validated['farm_id'],
            'auditor_name' => $validated['auditor_name'],
            'audit_date' => $validated['audit_date'],
            'cleaning_done' => $cleaningDone,
            'sanitation_zones_checked' => $sanitationChecked,
            'boundary_checks_passed' => $boundaryPassed,
            'score' => $score,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('audits.index', ['farm_id' => $audit->farm_id])
            ->with('success', "Biosecurity audit logged successfully! Compliance Score: {$score}%");
    }
}
