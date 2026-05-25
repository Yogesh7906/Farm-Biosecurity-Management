<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shed extends Model
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
        'capacity',
        'current_population',
    ];

    /**
     * Get the validation rules for the shed model.
     */
    public static function validationRules(): array
    {
        return [
            'farm_id' => ['required', 'exists:farms,id'],
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'current_population' => ['required', 'integer', 'min:0', 'lte:capacity'],
        ];
    }

    /**
     * Relationship: Shed belongs to a Farm.
     */
    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }

    /**
     * Relationship: Shed has many HealthAlerts.
     */
    public function healthAlerts(): HasMany
    {
        return $this->hasMany(HealthAlert::class, 'shed_id');
    }
}
