<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TriathlonRace extends BaseModel
{
    use HasFactory;

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function raceResult(): MorphOne
    {
        return $this->morphOne(RaceResult::class, 'raceable');
    }

    public function raceable(): MorphOne
    {
        return $this->morphOne(Race::class, 'raceable');
    }

    public function totalDistanceInMeters(): int
    {
        return $this->swim_distance_in_m + $this->bike_distance_in_km * 1000 + $this->run_distance_in_km * 1000;
    }
}
