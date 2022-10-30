<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('race_splits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('race_results_id');
            $table->string('type', 100);
            $table->float('distance')->nullable();
            $table->string('distance_unit', 50)->nullable();
            $table->time('time');
            $table->integer('rank_total');
            $table->integer('rank_gender');
            $table->integer('rank_age_group');
            $table->timestamps();

            $table
                ->foreign('race_results_id')
                ->references('id')
                ->on('race_results')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('race_splits');
    }
};
