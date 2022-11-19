<?php

namespace App\Services;

use App\Helpers\RaceResultDetails;
use App\Models\Race;
use App\Models\User;

interface RaceResultsProvider
{
    public function fetchResultFor(Race $race, User $user): RaceResultDetails;
}
