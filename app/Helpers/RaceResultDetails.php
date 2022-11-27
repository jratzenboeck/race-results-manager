<?php

namespace App\Helpers;

use App\Models\RaceResult;
use Illuminate\Support\Collection;

class RaceResultDetails
{
    public function __construct(public RaceResult $raceResult, public Collection $raceSplits)
    {
    }
}
