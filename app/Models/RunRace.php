<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RunRace extends BaseModel
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
}
