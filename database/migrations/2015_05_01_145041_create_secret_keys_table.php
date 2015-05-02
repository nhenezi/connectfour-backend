<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecretKeysTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('games', function(Blueprint $table) {
      $table->dropColumn('secret_key');
    });
    Schema::create('game_keys', function(lueprint $table) {
      $table->string('secret')->primary();
      $table->integer('creator_id')->unsigned();
      $table->foreign('creator_id')->references('id')->on('users');
      $table->timestamp('created_at');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('games', function(Blueprint $table) {
      $table->string('secret_key')->unique();
    });
    Schema::drop('game_keys');
  }

}
