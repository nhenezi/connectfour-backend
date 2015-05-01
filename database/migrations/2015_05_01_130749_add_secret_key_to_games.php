<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecretKeyToGames extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('games', function(Blueprint $table) {
      $table->string('secret_key')->unique();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('games', function(Blueprint $table) {
      $table->dropColumn('secret_key');
    });
  }

}
