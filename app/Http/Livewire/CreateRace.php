<?php

namespace App\Http\Livewire;

use App\Models\BikeRace;
use App\Models\Race;
use App\Models\RunRace;
use App\Models\TriathlonRace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'sport_type' => ['required', Rule::in('triathlon', 'bike', 'run')],
            'type' => 'required|string',
            'swim_distance_in_m' => 'required_if:sport_type,triathlon|numeric|nullable',
            'bike_distance_in_km' => 'required_if:sport_type,triathlon,bike|numeric|nullable',
            'run_distance_in_km' => 'required_if:sport_type,triathlon,run|numeric|nullable',
            'swim_venue_type' => 'required_if:sport_type,triathlon|string|nullable',
            'bike_course_elevation_in_m' => 'prohibited_unless:sport_type,triathlon,bike|nullable|numeric',
            'run_course_elevation_in_m' => 'prohibited_unless:sport_type,triathlon,run|nullable|numeric'
        ];
    }

    public function render()
    {
        return view('livewire.create-race');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
        $this->validate();

        $race = new Race(['name' => $this->name, 'location' => $this->location, 'date' => $this->date]);
        $race->author()->associate(Auth::user());

        $concreteRace = $this->buildConcreteRace();
        $concreteRace->save();
        $concreteRace->raceable()->save($race);

        session()->flash('successMessage', 'Der Wettkampf wurde erfolgreich gespeichert');

        return to_route('dashboard');
    }

    private function buildConcreteRace()
    {
        switch ($this->sport_type) {
            case 'triathlon':
                return new TriathlonRace([
                    'type' => $this->type,
                    'swim_distance_in_m' => $this->swim_distance_in_m,
                    'bike_distance_in_km' => $this->bike_distance_in_km,
                    'run_distance_in_km' => $this->run_distance_in_km,
                    'swim_venue_type' => $this->swim_venue_type,
                    'bike_course_elevation_in_m' => $this->bike_course_elevation_in_m,
                    'run_course_elevation_in_m' => $this->run_course_elevation_in_m
                ]);
            case 'bike':
                return new BikeRace([
                    'type' => $this->type,
                    'distance_in_km' => $this->bike_distance_in_km,
                    'elevation_in_m' => $this->bike_course_elevation_in_m
                ]);
            case 'run':
                return new RunRace([
                    'type' => $this->type,
                    'distance_in_km' => $this->run_distance_in_km,
                    'elevation_in_m' => $this->run_course_elevation_in_m
                ]);
            default:
                abort(404, 'Ungültige Sportart: ' . $this->sport_type);
        }
    }
}
