<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Race extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
