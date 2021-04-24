<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePremierLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premier_leagues', function (Blueprint $table) {
            $table->id();
            $table->string('team');
            $table->integer('point');
            $table->integer('won');
            $table->integer('equalization');
            $table->integer('lose');
            $table->integer('weekly_score');
            $table->integer('week');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('premier_leagues');
    }
}
