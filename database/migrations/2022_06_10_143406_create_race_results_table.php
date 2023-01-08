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
            $table->string('age_group')->nullable();
            $table->integer('participants_total');
            $table->integer('participants_gender')->nullable();
            $table->integer('participants_age_group')->nullable();
            $table->integer('rank_total');
            $table->integer('rank_gender')->nullable();
            $table->integer('rank_age_group')->nullable();
            $table->string('total_time', 8);
            $table->text('notes')->nullable();
            $table->morphs('raceable');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
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
