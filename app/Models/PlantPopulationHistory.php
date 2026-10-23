<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantPopulationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'user_id',
        'recorder_name',
        'change_type',
        'location_type',
        'change_amount',
        'population_before',
        'population_after',
        'greenhouse_population_before',
        'greenhouse_population_after',
        'outdoor_population_before',
        'outdoor_population_after',
        'reason',
        'other_reason',
        'notes',
        'documentation_photo',
        'changed_at',
    ];

    protected $casts = [
        'change_amount' => 'integer',
        'population_before' => 'integer',
        'population_after' => 'integer',
        'greenhouse_population_before' => 'integer',
        'greenhouse_population_after' => 'integer',
        'outdoor_population_before' => 'integer',
        'outdoor_population_after' => 'integer',
        'changed_at' => 'datetime',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReasonLabelAttribute(): string
    {
        return $this->reason === 'other' ? ($this->other_reason ?: 'Lainnya') : $this->reason;
    }
}
