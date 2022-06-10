<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triathlon_races', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('race_id');
            $table->enum('type', ['Supersprint Distanz', 'Sprintdistanz', 'Olympische Distanz', 'Mitteldistanz', 'Langdistanz']);
            $table->integer('swim_distance_in_m');
            $table->float('bike_distance_in_km');
            $table->float('run_distance_in_km', 8, 3);
            $table->enum('swim_venue_type', ['See', 'Meer', 'Fluss']);
            $table->float('bike_course_elevation_in_m')->nullable();
            $table->float('run_course_elevation_in_m')->nullable();
            $table->timestamps();

            $table->foreign('race_id')->references('id')->on('races')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('triathlon_races');
    }
};
