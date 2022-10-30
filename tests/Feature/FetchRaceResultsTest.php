<?php

use App\Models\Race;
use App\Models\RaceResult;
use App\Models\RaceSplitType;
use App\Models\TriathlonRace;
use App\Models\User;
use Carbon\Carbon;

use function Pest\Laravel\actingAs;

it('fetches a race result from Pentek Timing', function () {
    // Create a user with my credentials and act as this user
    $user = User::factory(['name' => 'Jürgen Ratzenböck'])->create();
    actingAs($user);
    // Create a triathlon race (Linz Triathlon with timestamp being in May 2022)
    $triRace = TriathlonRace::factory()
        ->for(Race::factory()
            ->for($user, 'author')
            ->create([
                'name' => 'Linz Triathlon',
                'date' => Carbon::create(2022, 5, 28, 12),
            ]))->create();
    // Execute artisan command to fetch race results
    $this->artisan('fetch:race-results');
    // Assert that race result of me is stored in database
    $this->assertDatabaseHas('race_results', [
        'user_id' => $user->id,
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
        'race_results_id' => RaceResult::first()->id,
        'type' => RaceSplitType::SWIM,
        'distance' => $triRace->swim_distance_in_m,
        'distance_unit' => 'Meter',
        'time' => '00:33:50',
        'rank_total' => 76,
        'rank_gender' => 67,
        'rank_age_group' => 25
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_results_id' => RaceResult::first()->id,
        'type' => RaceSplitType::TRANSITION1,
        'time' => '00:02:23',
        'rank_total' => 61,
        'rank_gender' => 56,
        'rank_age_group' => 19
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_results_id' => RaceResult::first()->id,
        'type' => RaceSplitType::BIKE,
        'distance' => $triRace->bike_distance_in_km,
        'distance_unit' => 'Kilometer',
        'time' => '02:34:04',
        'rank_total' => 80,
        'rank_gender' => 77,
        'rank_age_group' => 24
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_results_id' => RaceResult::first()->id,
        'type' => RaceSplitType::TRANSITION2,
        'time' => '00:02:15',
        'rank_total' => 82,
        'rank_gender' => 78,
        'rank_age_group' => 24
    ]);
    $this->assertDatabaseHas('race_splits', [
        'race_results_id' => RaceResult::first()->id,
        'type' => RaceSplitType::RUN,
        'distance' => $triRace->run_distance_in_km,
        'distance_unit' => 'Kilometer',
        'time' => '01:30:52',
        'rank_total' => 51,
        'rank_gender' => 48,
        'rank_age_group' => 15
    ]);
});
