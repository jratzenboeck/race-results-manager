<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class TriathlonRaceRequest extends RaceRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'type' => ['required', Rule::in(['Supersprint Distanz', 'Sprintdistanz', 'Olympische Distanz', 'Mitteldistanz', 'Langdistanz'])],
            'swim_distance_in_m' => 'required|numeric',
            'bike_distance_in_km' => 'required|numeric',
            'run_distance_in_km' => 'required|numeric',
            'swim_venue_type' => ['required', Rule::in('See', 'Meer', 'Fluss')],
            'bike_course_elevation_in_m' => 'nullable|numeric',
            'run_course_elevation_in_m' => 'nullable|numeric'
        ]);
    }
}
