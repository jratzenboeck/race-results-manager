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
        Schema::create('bike_races', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('race_id');
            $table->string('type');
            $table->float('distance_in_km');
            $table->float('elevation_in_m')->nullable();
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
        Schema::dropIfExists('bike_races');
    }
};
