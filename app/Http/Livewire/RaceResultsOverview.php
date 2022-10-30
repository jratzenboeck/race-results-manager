<?php

namespace App\Http\Livewire;

use App\Models\RaceResult;
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
