<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceSplit extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'type' => RaceSplitType::class
    ];

    public function raceResult(): BelongsTo
    {
        return $this->belongsTo(RaceResult::class);
    }
}
