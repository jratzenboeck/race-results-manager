<?php

namespace App\Http\Livewire;

use App\Models\BikeRace;
use App\Models\Race;
use App\Models\RunRace;
use App\Models\TriathlonRace;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateRace extends Component
{
    public $name;
    public $location;
    public $date;
    public $sport_type = 'triathlon';
    public $swim_distance_in_m;
    public $bike_distance_in_km;
    public $run_distance_in_km;
    public $swim_venue_type = 'See';
    public $bike_course_elevation_in_m;
    public $run_course_elevation_in_m;
    public $type = 'Supersprint Distanz';

    public function render()
    {
        return view('livewire.create-race');
    }

    public function updatedSportType($value)
    {
        if ($value == 'triathlon') {
            $this->type = 'Supersprint Distanz';
        } else {
            $this->type = '';
        }
    }

    public function submit()
    {
        $race = new Race(['name' => $this->name, 'location' => $this->location, 'date' => $this->date]);
        $race->author()->associate(Auth::user());
        $race->save();

        if ($this->sport_type == 'triathlon') {
            $concreteRace = new TriathlonRace([
                'type' => $this->type,
                'swim_distance_in_m' => $this->swim_distance_in_m,
                'bike_distance_in_km' => $this->bike_distance_in_km,
                'run_distance_in_km' => $this->run_distance_in_km,
                'swim_venue_type' => $this->swim_venue_type,
                'bike_course_elevation_in_m' => $this->bike_course_elevation_in_m,
                'run_course_elevation_in_m' => $this->run_course_elevation_in_m
            ]);
        } else if ($this->sport_type == 'bike') {
            $concreteRace = new BikeRace([
                'type' => $this->type,
                'bike_distance_in_km' => $this->bike_distance_in_km,
                'bike_course_elevation_in_m' => $this->bike_course_elevation_in_m
            ]);
        } else {
            $concreteRace = new RunRace([
                'type' => $this->type,
                'run_distance_in_km' => $this->run_distance_in_km,
                'run_course_elevation_in_m' => $this->run_course_elevation_in_m
            ]);
        }

        $concreteRace->race()->associate($race);
        $concreteRace->save();

        session()->flash('successMessage', 'Der Wettkampf wurde erfolgreich gespeichert');

        return redirect('/');
    }
}
