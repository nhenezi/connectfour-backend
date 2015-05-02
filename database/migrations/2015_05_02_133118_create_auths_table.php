<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('auths', function(Blueprint $table) {
      $table->integer('user_id')->unsigned()->unique();
      $table->foreign('user_id')->references('id')->on('users');
      $table->string('token')->primary();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('auths');
  }

}
