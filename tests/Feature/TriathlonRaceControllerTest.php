<?php

use App\Models\Race;
use App\Models\User;
use Illuminate\Support\Carbon;

it('stores a triathlon race', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/triathlon-races', [
        'name' => 'Linz Triathlon 2022',
        'location' => 'Linz',
        'date' => Carbon::create(2022, 5, 28),
        'type' => 'Mitteldistanz',
        'swim_distance_in_m' => 1900,
        'bike_distance_in_km' => 82,
        'run_distance_in_km' => 21,
        'swim_venue_type' => 'See'
    ]);

    $response->assertViewIs('welcome');

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

