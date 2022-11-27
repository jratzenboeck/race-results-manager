<?php

namespace Database\Seeders;

use App\Models\Race;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Jürgen Ratzenböck',
            'email' => 'juergen.ratzenboeck@gmail.com',
            'password' => Hash::make('Triathlon4ever'),
            'email_verified_at' => now(),
            'gender' => 'm'
        ]);

        Race::factory()->for(User::first(), 'author')->create();
    }
}
