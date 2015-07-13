<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('games', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('player_one')->unsigned();
      $table->foreign('player_one')->references('id')->on('users');
      $table->integer('player_two')->unsigned();
      $table->foreign('player_two')->references('id')->on('users');
      $table->integer('winner')->default(0);
      $table->timestamp('start_time');
      $table->timestamp('end_time');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('games');
  }

}
