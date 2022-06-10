<?php

namespace App\Http\Controllers;

use App\Http\Requests\TriathlonRaceRequest;
use App\Models\Race;
use App\Models\TriathlonRace;
use Illuminate\Support\Facades\Auth;

class TriathlonRaceController extends Controller
{
    public function store(TriathlonRaceRequest $request)
    {
        $race = new Race($request->only('name', 'location', 'date'));
        $race->author()->associate(Auth::user());
        $race->save();

        $triathlonRace = new TriathlonRace($request->only('type', 'swim_distance_in_m', 'bike_distance_in_km', 'run_distance_in_km', 'swim_venue_type', 'bike_course_elevation_in_m', 'run_course_elevation_in_m'));
        $triathlonRace->race()->associate($race);
        $triathlonRace->save();

        return view('welcome');
    }
}
