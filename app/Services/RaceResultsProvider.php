<?php

namespace App\Services;

use App\Models\Race;
use App\Models\User;

interface RaceResultsProvider
{
    public function fetchResultFor(Race $race, User $user): array;
}
