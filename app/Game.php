<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {
  protected $table = 'games';
  public $timestamps = false;

  public function moves() {
    return $this->hasMany('App\Move');
  }

  public function getLastMove() {
    $last_move = 0;
    foreach ($this->moves as $move) {
      if ($move->number > $last_move) {
        $last_move = $move->number;
      }
    }

    return $last_move;
  }


  public function movesInColumn($column) {
    $moves = 0;

    foreach ($this->moves as $move) {
      if ($move->x === $column) {
        $moves++;
      }
    }

    return $moves;
  }
}
