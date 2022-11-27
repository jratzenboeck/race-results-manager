<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceResult extends BaseModel
{
    use HasFactory;

    public function raceable(): BelongsTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function from(array $data, User $user, $raceable)
    {
        $raceResult = new RaceResult($data);
        $raceResult->user()->associate($user);
        $raceResult->raceable()->associate($raceable);

        return $raceResult;
    }
}
