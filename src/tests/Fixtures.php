<?php

$db_config = require __DIR__.'/../config/database.php';
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bootstrap\Dotenv;

$env = Dotenv::load(__DIR__.'/../.env');
print_r($env);

class Fixture {
}

foreach (glob("./tests/fixtures/*.php") as $filename) {
  $fixture = require_once($filename);
  $user = new $fixture['model']();
  $user->save();
}
