<?php

use App\Http\Livewire\RaceResultsOverview;
use App\Models\BikeRace;
use App\Models\Race;
use App\Models\RaceResult;
use App\Models\RunRace;
use App\Models\TriathlonRace;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

it('shows race results of user', function () {
    $user = User::factory()->create();
    actingAs($user);

    $this->travelTo(now());

    [$race1, $race2, $race3] = Race::factory(3)->create();
    $triRace = TriathlonRace::factory()->for($race1)->create();
    $bikeRace = BikeRace::factory()->for($race2)->create();
    $runRace = RunRace::factory()->for($race3)->create();
    foreach ([$triRace, $bikeRace, $runRace] as $concreteRace) {
        RaceResult::factory()->for($concreteRace, 'raceable')->for($user)->create();
    }

    $response = Livewire::test(RaceResultsOverview::class);

    Race::all()->each(fn ($race) => $response->assertSeeHtml($race->name));
    RaceResult::all()->each(fn ($result) => $response->assertSeeHtml($result->total_time));
});
