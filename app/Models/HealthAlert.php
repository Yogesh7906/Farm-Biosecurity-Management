<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthAlert extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shed_id',
        'date_logged',
        'daily_mortality_count',
        'mortality_rate',
        'alert_level',
        'quarantine_triggered',
        'vaccine_drop_scheduled',
        'status',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quarantine_triggered' => 'boolean',
        'vaccine_drop_scheduled' => 'boolean',
        'date_logged' => 'date',
        'daily_mortality_count' => 'integer',
        'mortality_rate' => 'float',
    ];

    /**
     * Get the validation rules for the health alert model.
     */
    public static function validationRules(): array
    {
        return [
            'shed_id' => ['required', 'exists:sheds,id'],
            'date_logged' => ['required', 'date'],
            'daily_mortality_count' => ['required', 'integer', 'min:0'],
            'mortality_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'alert_level' => ['required', 'in:normal,warning,critical'],
            'quarantine_triggered' => ['required', 'boolean'],
            'vaccine_drop_scheduled' => ['required', 'boolean'],
            'status' => ['required', 'in:active,resolved'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    /**
     * Relationship: Health alert belongs to a Shed.
     */
    public function shed(): BelongsTo
    {
        return $this->belongsTo(Shed::class, 'shed_id');
    }
}
