<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Shed;
use App\Models\VisitorsLog;
use App\Models\BiosecurityAudit;
use App\Models\HealthAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dynamic farm dashboard.
     */
    public function index(Request $request)
    {
        // 1. Fetch user's farms
        // Note: For demonstration/practical purposes we use the first farm if no farm_id is provided,
        // or we can fall back to user's first farm or a mock farm.
        $user = Auth::user();
        
        // If not logged in, we can mock a user or restrict. Let's make sure it handles auth gracefully.
        $farms = $user ? $user->farms : Farm::all();

        if ($farms->isEmpty()) {
            return view('dashboard', [
                'farms' => collect(),
                'activeFarm' => null,
                'sheds' => collect(),
                'recentVisitors' => collect(),
                'recentAudits' => collect(),
                'activeAlerts' => collect(),
                'stats' => $this->emptyStats()
            ]);
        }

        // 2. Select active farm
        $activeFarmId = $request->get('farm_id', $farms->first()->id);
        $activeFarm = $farms->firstWhere('id', $activeFarmId) ?: $farms->first();

        // 3. Retrieve farm data
        $sheds = Shed::where('farm_id', $activeFarm->id)->get();
        $recentVisitors = VisitorsLog::where('farm_id', $activeFarm->id)
            ->orderBy('check_in_time', 'desc')
            ->take(5)
            ->get();
        $recentAudits = BiosecurityAudit::where('farm_id', $activeFarm->id)
            ->orderBy('audit_date', 'desc')
            ->take(5)
            ->get();
        
        $shedIds = $sheds->pluck('id');
        $activeAlerts = HealthAlert::whereIn('shed_id', $shedIds)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Calculate metrics
        $stats = [
            'total_population' => $sheds->sum('current_population'),
            'capacity' => $sheds->sum('capacity'),
            'occupancy_rate' => $sheds->sum('capacity') > 0 
                ? round(($sheds->sum('current_population') / $sheds->sum('capacity')) * 100, 1) 
                : 0,
            'avg_audit_score' => round(BiosecurityAudit::where('farm_id', $activeFarm->id)->avg('score') ?? 0, 1),
            'quarantined_visitors_count' => VisitorsLog::where('farm_id', $activeFarm->id)
                ->where('status', 'quarantined')
                ->count(),
            'active_alerts_count' => $activeAlerts->count(),
            'critical_outbreaks_count' => $activeAlerts->where('alert_level', 'critical')->count(),
        ];

        return view('dashboard', compact(
            'farms',
            'activeFarm',
            'sheds',
            'recentVisitors',
            'recentAudits',
            'activeAlerts',
            'stats'
        ));
    }

    /**
     * Helper for empty dashboard stats.
     */
    private function emptyStats(): array
    {
        return [
            'total_population' => 0,
            'capacity' => 0,
            'occupancy_rate' => 0,
            'avg_audit_score' => 0,
            'quarantined_visitors_count' => 0,
            'active_alerts_count' => 0,
            'critical_outbreaks_count' => 0,
        ];
    }
}
