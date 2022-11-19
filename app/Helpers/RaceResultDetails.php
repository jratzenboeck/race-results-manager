<?php

namespace App\Helpers;

use App\Models\RaceResult;

class RaceResultDetails
{
    public function __construct(public RaceResult $raceResult, public array $raceSplits)
    {
    }
}
