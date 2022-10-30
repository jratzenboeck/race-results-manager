<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RaceSplit extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'type' => RaceSplitType::class
    ];
}
