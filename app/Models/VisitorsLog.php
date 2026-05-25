<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class VisitorsLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'farm_id',
        'name',
        'phone',
        'purpose',
        'temperature',
        'visited_other_farm_past_48h',
        'vehicle_plate',
        'vehicle_sanitized',
        'check_in_time',
        'check_out_time',
        'status',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visited_other_farm_past_48h' => 'boolean',
        'vehicle_sanitized' => 'boolean',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    /**
     * Get the validation rules for the visitors log model.
     */
    public static function validationRules(): array
    {
        return [
            'farm_id' => ['required', 'exists:farms,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'purpose' => ['required', 'string', 'max:255'],
            'temperature' => ['nullable', 'numeric', 'min:30', 'max:45'],
            'visited_other_farm_past_48h' => ['required', 'boolean'],
            'vehicle_plate' => ['nullable', 'string', 'max:50'],
            'vehicle_sanitized' => ['required_with:vehicle_plate', 'boolean'],
            'check_in_time' => ['required', 'date'],
            'check_out_time' => ['nullable', 'date', 'after_or_equal:check_in_time'],
            'status' => ['required', 'in:quarantined,cleared'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    /**
     * Relationship: Visitor log entry belongs to a Farm.
     */
    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }

    /**
     * Scope: Filter active quarantine logs.
     */
    public function scopeQuarantined(Builder $query): Builder
    {
        return $query->where('status', 'quarantined');
    }

    /**
     * Scope: Filter cleared logs.
     */
    public function scopeCleared(Builder $query): Builder
    {
        return $query->where('status', 'cleared');
    }
}
