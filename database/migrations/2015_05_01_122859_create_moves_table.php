<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('moves', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('game_id')->unsigned();
      $table->foreign('game_id')->references('id')->on('games');
      $table->integer('player_id')->unsigned();
      $table->foreign('player_id')->references('id')->on('users');
      $table->integer('x')->unsigned();
      $table->integer('y')->unsigned();
      $table->timestamp('time');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('moves');
  }
}
