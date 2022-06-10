<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\TriathlonRace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TriathlonRaceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => ['required', Rule::in(['Supersprint Distanz', 'Sprintdistanz', 'Olympische Distanz', 'Mitteldistanz', 'Langdistanz'])],
            'swim_distance_in_m' => 'required|numeric',
            'bike_distance_in_km' => 'required|numeric',
            'run_distance_in_km' => 'required|numeric',
            'swim_venue_type' => ['required', Rule::in('See', 'Meer', 'Fluss')],
            'bike_course_elevation_in_m' => 'nullable|numeric',
            'run_course_elevation_in_m' => 'nullable|numeric'
        ]);

        $race = new Race($request->only('name', 'location', 'date'));
        $race->author()->associate(Auth::user());
        $race->save();

        $triathlonRace = new TriathlonRace($request->only('type', 'swim_distance_in_m', 'bike_distance_in_km', 'run_distance_in_km', 'swim_venue_type', 'bike_course_elevation_in_m', 'run_course_elevation_in_m'));
        $triathlonRace->race()->associate($race);
        $triathlonRace->save();

        return view('welcome');
    }
}
