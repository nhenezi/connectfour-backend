<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name');
      $table->string('email')->unique();
      $table->string('password', 60);
      $table->timestamps();
    });

    DB::table('users')->insert([
      'name' => 'Anon',
      'email' => 'annon@annon.com',
      'password' => 'invalid pw',
      'created_at' => date(DATE_ATOM),
      'updated_at' => date(DATE_ATOM)
    ]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('users');
  }

}
