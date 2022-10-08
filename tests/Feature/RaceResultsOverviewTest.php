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
    // Create user
    $user = User::factory()->create();

    actingAs($user);

    $this->travelTo(now());

    // Create three races
    [$race1, $race2, $race3] = Race::factory(3)->create();
    $triRace = TriathlonRace::factory()->for($race1)->create();
    $bikeRace = BikeRace::factory()->for($race2)->create();
    $runRace = RunRace::factory()->for($race3)->create();
    RaceResult::factory()->for($triRace, 'raceable')->for($user)->create();
    RaceResult::factory()->for($bikeRace, 'raceable')->for($user)->create();
    RaceResult::factory()->for($runRace, 'raceable')->for($user)->create();

    // Access dashboard
    Livewire::test(RaceResultsOverview::class)
        ->assertSeeHtml(Race::first()->name);
});
