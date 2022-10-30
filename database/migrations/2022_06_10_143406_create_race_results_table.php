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
        Schema::create('race_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('raceable_id');
            $table->string('raceable_type');
            $table->string('age_group')->nullable();
            $table->integer('participants_total');
            $table->integer('participants_gender')->nullable();
            $table->integer('participants_age_group')->nullable();
            $table->integer('rank_total');
            $table->integer('rank_gender')->nullable();
            $table->integer('rank_age_group')->nullable();
            $table->time('total_time');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('raceable_id')->references('id')->on('races')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('race_results');
    }
};
