<?php

use App\Http\Livewire\CreateRace;
use App\Models\Race;
use App\Models\User;
use Carbon\Carbon;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('stores a triathlon race', function () {
    $user = User::factory()->create();

    actingAs($user);

    livewire(CreateRace::class)
        ->set('name', 'Linz Triathlon 2022')
        ->set('location', 'Linz')
        ->set('date', Carbon::create(2022, 5, 28))
        ->set('sport_type', 'triathlon')
        ->set('type', 'Mitteldistanz')
        ->set('swim_distance_in_m', 1900)
        ->set('bike_distance_in_km', 82)
        ->set('run_distance_in_km', 21)
        ->set('swim_venue_type', 'See')
        ->call('submit')
        ->assertSessionHas('successMessage')
        ->assertRedirect('/');

    $this->assertDatabaseHas('races', [
        'name' => 'Linz Triathlon 2022',
        'location' => 'Linz',
        'date' => Carbon::create(2022, 5, 28),
        'author_id' => $user->id
    ]);
    $this->assertDatabaseHas('triathlon_races', [
        'race_id' => Race::first()->id,
        'type' => 'Mitteldistanz',
        'swim_distance_in_m' => 1900,
        'bike_distance_in_km' => 82,
        'run_distance_in_km' => 21,
        'swim_venue_type' => 'See'
    ]);
});

it('fails to store a triathlon race because of missing name', function() {
    $user = User::factory()->create();

    actingAs($user);

    livewire(CreateRace::class)
        ->set('location', 'Linz')
        ->set('date', Carbon::create(2022, 5, 28))
        ->set('sport_type', 'triathlon')
        ->set('type', 'Mitteldistanz')
        ->set('swim_distance_in_m', 1900)
        ->set('bike_distance_in_km', 82)
        ->set('run_distance_in_km', 21)
        ->set('swim_venue_type', 'See')
        ->call('submit')
        ->assertHasErrors('name');
});
