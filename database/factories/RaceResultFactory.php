<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RaceResult>
 */
class RaceResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'age_group' => 'M30-35',
            'participants_total' => 2000,
            'participants_gender' => 1500,
            'participants_age_group' => 200,
            'rank_total' => 1,
            'rank_gender' => 1,
            'rank_age_group' => 1,
            'total_time' => '10:00:00'
        ];
    }
}
