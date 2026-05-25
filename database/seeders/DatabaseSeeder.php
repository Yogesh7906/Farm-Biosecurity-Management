<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\Shed;
use App\Models\VisitorsLog;
use App\Models\BiosecurityAudit;
use App\Models\HealthAlert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Primary Admin/Vet User
        $user = User::create([
            'name' => 'Dr. Amit Sharma',
            'email' => 'amit@biosecurity.gov.in',
            'password' => Hash::make('password'),
        ]);

        // 2. Create dynamic Pig and Poultry Farms
        $poultryFarm = Farm::create([
            'name' => 'Green Valley Poultry Farm',
            'farm_type' => 'poultry',
            'location' => 'Punjab, Sector 4',
            'owner_id' => $user->id,
        ]);

        $pigFarm = Farm::create([
            'name' => 'Apex Swine Breeding Center',
            'farm_type' => 'pig',
            'location' => 'Haryana, Sector 12',
            'owner_id' => $user->id,
        ]);

        // 3. Create Sheds for Poultry Farm
        $broilerShed = Shed::create([
            'farm_id' => $poultryFarm->id,
            'name' => 'Coop A - Broilers',
            'capacity' => 10000,
            'current_population' => 8500,
        ]);

        $layerShed = Shed::create([
            'farm_id' => $poultryFarm->id,
            'name' => 'Coop B - Layers',
            'capacity' => 8000,
            'current_population' => 7200,
        ]);

        // Create Sheds for Pig Farm
        $farrowingPen = Shed::create([
            'farm_id' => $pigFarm->id,
            'name' => 'Pen Alpha - Farrowing',
            'capacity' => 500,
            'current_population' => 420,
        ]);

        $growerPen = Shed::create([
            'farm_id' => $pigFarm->id,
            'name' => 'Pen Beta - Growers',
            'capacity' => 800,
            'current_population' => 750,
        ]);

        // 4. Create Seed Biosecurity Audits
        BiosecurityAudit::create([
            'farm_id' => $poultryFarm->id,
            'auditor_name' => 'Dr. Amit Sharma',
            'audit_date' => now()->subDays(2),
            'cleaning_done' => true,
            'sanitation_zones_checked' => true,
            'boundary_checks_passed' => true,
            'score' => 100,
            'remarks' => 'Full biosecurity parameters checked. Outstanding compliance.',
        ]);

        BiosecurityAudit::create([
            'farm_id' => $pigFarm->id,
            'auditor_name' => 'Dr. Amit Sharma',
            'audit_date' => now()->subDays(1),
            'cleaning_done' => true,
            'sanitation_zones_checked' => false,
            'boundary_checks_passed' => true,
            'score' => 60,
            'remarks' => 'Slight issue: chemical solutions at entrance footbath require replenishing. Critical alert raised.',
        ]);

        // 5. Create Seed Visitor Logs (with dynamic quarantines)
        VisitorsLog::create([
            'farm_id' => $poultryFarm->id,
            'name' => 'Vikram Singh',
            'phone' => '+91 9999999999',
            'purpose' => 'Feed Delivery',
            'temperature' => 36.5,
            'visited_other_farm_past_48h' => false,
            'vehicle_plate' => 'HR 26 BY 5511',
            'vehicle_sanitized' => true,
            'check_in_time' => now()->subHours(5),
            'check_out_time' => now()->subHours(4),
            'status' => 'cleared',
            'remarks' => 'Delivered 20 tons of organic layer feed pellets.',
        ]);

        VisitorsLog::create([
            'farm_id' => $poultryFarm->id,
            'name' => 'Rajesh Kumar',
            'phone' => '+91 8888888888',
            'purpose' => 'Equipment Repair',
            'temperature' => 38.2,
            'visited_other_farm_past_48h' => true,
            'vehicle_plate' => 'DL 3C AW 4422',
            'vehicle_sanitized' => false,
            'check_in_time' => now()->subHours(2),
            'check_out_time' => null,
            'status' => 'quarantined',
            'remarks' => 'AUTOMATIC QUARANTINE: High body temperature logged (38.2°C). Declared visiting other poultry farm in Punjab within 48h. Restricting access to outer zone perimeter only.',
        ]);

        // 6. Create Seed Health Alerts (One Normal and One Critical Outbreak Trigger)
        HealthAlert::create([
            'shed_id' => $broilerShed->id,
            'date_logged' => now()->subDays(1),
            'daily_mortality_count' => 15,
            'mortality_rate' => 0.18,
            'alert_level' => 'normal',
            'quarantine_triggered' => false,
            'vaccine_drop_scheduled' => false,
            'status' => 'active',
            'remarks' => 'Normal daily fluctuations. Fluctuations well within 2% margin.',
        ]);

        // This one exceeds 5.0% threshold (25 / 420 * 100 = 5.95%)
        HealthAlert::create([
            'shed_id' => $farrowingPen->id,
            'date_logged' => now(),
            'daily_mortality_count' => 25,
            'mortality_rate' => 5.95,
            'alert_level' => 'critical',
            'quarantine_triggered' => true,
            'vaccine_drop_scheduled' => true,
            'status' => 'active',
            'remarks' => '🚨 AUTOMATIC LOCKDOWN: Mortality threshold of 5.0% breached at 5.95%! Swine containment quarantine triggered, veterinary team notified, and mandatory vaccine drop scheduled.',
        ]);
        
        // Deduct seed mortalities from population
        $farrowingPen->decrement('current_population', 25);
        $broilerShed->decrement('current_population', 15);
    }
}
