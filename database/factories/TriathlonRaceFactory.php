<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory
 */
class TriathlonRaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => Arr::random(['Supersprint Distanz', 'Sprintdistanz', 'Olympische Distanz', 'Mitteldistanz', 'Langdistanz']),
            'swim_distance_in_m' => $this->faker->randomNumber(4),
            'bike_distance_in_km' => $this->faker->randomFloat(2, 1, 1000),
            'run_distance_in_km' => $this->faker->randomFloat(2, 1, 100),
            'swim_venue_type' => Arr::random(['See', 'Meer', 'Fluss']),
            'bike_course_elevation_in_m' => $this->faker->randomNumber(5),
            'run_course_elevation_in_m' => $this->faker->randomNumber(5),
        ];
    }
}
