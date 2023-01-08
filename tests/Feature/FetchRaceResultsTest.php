<?php

use App\Models\Race;
use App\Models\RaceResult;
use App\Models\RaceSplitType;
use App\Models\TriathlonRace;
use App\Models\User;
use Carbon\Carbon;

it('fetches a race result from Pentek Timing', function () {
    // Create a triathlon race (Linz Triathlon with timestamp being in May 2022)
    $triRace = TriathlonRace::factory()->create([
        'type' => 'Mitteldistanz',
        'swim_distance_in_m' => 1900,
        'bike_distance_in_km' => 83.9,
        'run_distance_in_km' => 20
    ]);
    Race::factory()
        ->for(User::factory(['name' => 'Jürgen Ratzenböck', 'gender' => 'm'])->create(), 'author')
        ->for($triRace, 'raceable')
        ->create([
            'name' => 'Linz Triathlon',
            'date' => Carbon::create(2022, 5, 28, 12),
        ]);
    // Execute artisan command to fetch race results
    $this->artisan('fetch:race-results');
    // Assert that race result of me is stored in database
    $this->assertDatabaseHas('race_results', [
        'user_id' => User::first()->id,
        'raceable_id' => $triRace->id,
        'raceable_type' => TriathlonRace::class,
        'age_group' => 'M-30-39',
        'participants_total' => 202,
        'participants_gender' => 170,
        'rank_total' => 51,
        'rank_gender' => 48,
        'rank_age_group' => 15,
        'total_time' => '04:43:26'
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_result_id' => RaceResult::first()->id,
        'type' => RaceSplitType::SWIM,
        'distance' => $triRace->swim_distance_in_m,
        'distance_unit' => 'Meter',
        'time' => '00:33:50',
        'rank_total' => 76,
        'rank_gender' => 67,
        'rank_age_group' => 25
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_result_id' => RaceResult::first()->id,
        'type' => RaceSplitType::TRANSITION1,
        'time' => '00:02:23',
        'rank_total' => 61,
        'rank_gender' => 56,
        'rank_age_group' => 19
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_result_id' => RaceResult::first()->id,
        'type' => RaceSplitType::BIKE,
        'distance' => $triRace->bike_distance_in_km,
        'distance_unit' => 'Kilometer',
        'time' => '02:34:04',
        'rank_total' => 80,
        'rank_gender' => 77,
        'rank_age_group' => 24
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_result_id' => RaceResult::first()->id,
        'type' => RaceSplitType::TRANSITION2,
        'time' => '00:02:15',
        'rank_total' => 82,
        'rank_gender' => 78,
        'rank_age_group' => 24
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_result_id' => RaceResult::first()->id,
        'type' => RaceSplitType::RUN,
        'distance' => $triRace->run_distance_in_km,
        'distance_unit' => 'Kilometer',
        'time' => '01:30:52',
        'rank_total' => 51,
        'rank_gender' => 48,
        'rank_age_group' => 15
    ]);
});

it('cannot fetch race results because race already has a result', function () {
    // Create a user with my credentials and act as this user
    $user = User::factory(['name' => 'Jürgen Ratzenböck'])->create();
    // Create a triathlon race (Linz Triathlon with timestamp being in May 2022)
    $triRace = TriathlonRace::factory()->create();
    Race::factory()
        ->for($user, 'author')
        ->for($triRace, 'raceable')
        ->create([
            'name' => 'Linz Triathlon',
            'date' => Carbon::create(2022, 5, 28, 12),
        ]);
    RaceResult::factory()->for($triRace, 'raceable')->for($user)->create();
    // Execute artisan command to fetch race results
    $this->artisan('fetch:race-results');

    $this->assertDatabaseCount('race_results', 1);
});

it('cannot fetch race result because race does not exist at given provider', function () {
    // Create a triathlon race (Linz Triathlon with timestamp being in May 2022)

    Race::factory()
        ->for(User::factory(['name' => 'Jürgen Ratzenböck'])->create(), 'author')
        ->for(TriathlonRace::factory(), 'raceable')
        ->create([
            'name' => 'Linz Marathon',
            'date' => Carbon::create(2022, 5, 28, 12),
        ]);

    $this->expectExceptionMessage('Pentek timing project could not be found for race name Linz Marathon');

    $this->artisan('fetch:race-results');
});

it('cannot fetch race result because provided user did not participate', function () {
    $name = 'Carina Ratzenböck';

    Race::factory()
        ->for(User::factory(compact('name'))->create(), 'author')
        ->for(TriathlonRace::factory()->create([
            'type' => 'Mitteldistanz',
            'swim_distance_in_m' => 1900,
            'bike_distance_in_km' => 83.9,
            'run_distance_in_km' => 20
        ]), 'raceable')
        ->create([
            'name' => 'Linz Triathlon',
            'date' => Carbon::create(2022, 5, 28, 12),
        ]);

    $this->expectExceptionMessage('Pentek timing athlete ' . $name .
        ' could not be found for project number 14216 and competition number 1');

    $this->artisan('fetch:race-results');
});
