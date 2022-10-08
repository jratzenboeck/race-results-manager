<?php

namespace App\Http\Livewire;

use App\Models\BikeRace;
use App\Models\RaceResult;
use App\Models\RunRace;
use App\Models\TriathlonRace;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RaceResultsOverview extends Component
{
    public function render()
    {
        $raceResults = RaceResult::where('user_id', Auth::id())->get();

        return view('livewire.race-results-overview', compact('raceResults'));
    }
}
