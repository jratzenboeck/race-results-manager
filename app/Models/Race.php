<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Race extends BaseModel
{
    use HasFactory;

    protected $with = ['raceable'];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function raceable(): MorphTo
    {
        return $this->morphTo();
    }
}
