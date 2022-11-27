<?php

namespace Database\Factories;

use App\Models\TriathlonRace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class RaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'location' => $this->faker->city,
            'date' => now(),
            'raceable_id' => TriathlonRace::factory()->create(),
            'raceable_type' => TriathlonRace::class
        ];
    }
}
