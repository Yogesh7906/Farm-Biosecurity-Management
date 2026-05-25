<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'farm_type',
        'location',
        'owner_id',
    ];

    /**
     * Get the validation rules for the farm model.
     */
    public static function validationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'farm_type' => ['required', 'in:poultry,pig'],
            'location' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * Relationship: Farm belongs to a User (Owner).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relationship: Farm has many Sheds.
     */
    public function sheds(): HasMany
    {
        return $this->hasMany(Shed::class, 'farm_id');
    }

    /**
     * Relationship: Farm has many VisitorsLogs.
     */
    public function visitorsLogs(): HasMany
    {
        return $this->hasMany(VisitorsLog::class, 'farm_id');
    }

    /**
     * Relationship: Farm has many BiosecurityAudits.
     */
    public function biosecurityAudits(): HasMany
    {
        return $this->hasMany(BiosecurityAudit::class, 'farm_id');
    }
}
