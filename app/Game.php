<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {
  const BOARD_HEIGHT = 7;
  const BOARD_WIDTH = 7;

  protected $table = 'games';
  public $timestamps = false;

  public function moves() {
    return $this->hasMany('App\Move');
  }

  public function getLastMove() {
    $last_move = 0;
    foreach ($this->moves as $move) {
      if ($move->number > $last_move) {
        $last_move = $move;
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

  protected function initBoard() {
    $grid = [];
    for ($i = 0; $i < self::BOARD_WIDTH; $i++) {
      $row = [];
      for ($j = 0; $j < self::BOARD_WIDTH; $j++) {
        $row[] = 0;
      }
      $grid[] = $row;
    }

    return $grid;
  }

  public function getBoard() {
    $board = $this->initBoard();
    foreach ($this->moves as $move) {
      $board[$move->y][$move->x] = $move->player_id;
    }
    return $board;
  }

  public function wasLastMoveWinning($board = null, $last_move = null) {
    if ($board == null) {
      $board = $this->getBoard();
    }
    if ($last_move == null) {
      $last_move = $this->getLastMove();
    }
    $x = $last_move->x;
    $y = $last_move->y;

    $in_a_row = 0;
    // check if there is a horizontal 4 in a row
    $sp = ($x - 3 < 0) ? 0 : $x - 3; // starting point
    for ($i = $sp; $i < self::BOARD_WIDTH && $i <= $x + 3; $i++) {
      if ($board[$i][$y] === $last_move->player_id) {
        $in_a_row++;
      } else {
        $in_a_row = 0;
      }

      if ($in_a_row >= 4) {
        return true;
      }
    }

    $in_a_row = 0;
    // check if there is a vertical 4 in a row
    $sp = ($y - 3 < 0) ? 0 : $y - 3;
    for ($i = $sp; $i < self::BOARD_HEIGHT && $i <= $y + 3; $i++) {
      if ($board[$x][$i] === $last_move->player_id) {
        $in_a_row++;
      } else {
        $in_a_row = 0;
      }

      if ($in_a_row >= 4) {
        return true;
      }
    }

    $in_a_row = 0;
    // check if there is a diagonal (top left -> bottom right) 4 in a row
    for ($i = -3, $j = 3; $x + $i <= $x + 3, $y + $j >= $y - 3; $i++, $j--) {
      if ($x + $i < 0 || $x + $i >= self::BOARD_WIDTH || $y + $j < 0 || $y + $j >= self::BOARD_WIDTH) {
        continue;
      }

      if ($board[$x + $i][$y + $j] == $last_move->player_id) {
        $in_a_row++;
      } else {
        $in_a_row = 0;
      }

      if ($in_a_row >= 4) {
        return true;
      }
    }

    $in_a_row = 0;
    // check if there is a diagonal (bottom left -> top right) 4 in a row
    for ($i = -3, $j = -3; $x + $i <= $x + 3, $y + $j <= $y + 3; $i++, $j++) {
      if ($x + $i < 0 || $x + $i >= self::BOARD_WIDTH || $y + $j < 0 || $y + $j >= self::BOARD_WIDTH) {
        continue;
      }

      if ($board[$x + $i][$y + $j] == $last_move->player_id) {
        $in_a_row++;
      } else {
        $in_a_row = 0;
      }

      if ($in_a_row >= 4) {
        return true;
      }
    }

    return false;
  }
}
