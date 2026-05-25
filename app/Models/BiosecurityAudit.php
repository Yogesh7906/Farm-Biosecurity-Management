<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiosecurityAudit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'farm_id',
        'auditor_name',
        'audit_date',
        'cleaning_done',
        'sanitation_zones_checked',
        'boundary_checks_passed',
        'score',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cleaning_done' => 'boolean',
        'sanitation_zones_checked' => 'boolean',
        'boundary_checks_passed' => 'boolean',
        'audit_date' => 'date',
    ];

    /**
     * Get the validation rules for the biosecurity audit model.
     */
    public static function validationRules(): array
    {
        return [
            'farm_id' => ['required', 'exists:farms,id'],
            'auditor_name' => ['required', 'string', 'max:255'],
            'audit_date' => ['required', 'date'],
            'cleaning_done' => ['required', 'boolean'],
            'sanitation_zones_checked' => ['required', 'boolean'],
            'boundary_checks_passed' => ['required', 'boolean'],
            'score' => ['required', 'integer', 'between:0,100'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    /**
     * Relationship: Biosecurity audit belongs to a Farm.
     */
    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }
}
