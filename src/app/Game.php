<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Redis;
use App\Game;

class Game extends Model {
  const BOARD_HEIGHT = 6;
  const BOARD_WIDTH = 7;

  protected $table = 'games';
  public $timestamps = false;

  public function moves() {
    return $this->hasMany('App\Move');
  }

  public function getLastMove() {
    $last_move = new \stdClass();
    $last_move->number = -1;
    $last_move->toArray = function() {
      return ['number' => -1];
    };
    $this->load('moves');
    foreach ($this->moves as $move) {
      if ($move->number > $last_move->number) {
        $last_move = $move;
      }
    }

    return $last_move;
  }

  /**
   * Tries to find match for $user
   */
  public static function findMatch($user) {
    $i = 0;
    $players = [];
    // aggregate all players in poll
    do {
      $players[] = Redis::lindex('users in poll', $i);
      $i++;
    } while (Redis::lindex('users in poll', $i));

    if (count($players) < 2) {
      return false;
    }

    $pairs = [];
    // pair players
    for ($i = 0; 2 * ($i + 1) <= count($players); $i++) {
      $pairs[] = [$players[2 * $i], $players[2 * $i + 1]];
      Redis::lrem('users in poll', 0, $players[2 * $i]);
      Redis::lrem('users in poll', 0, $players[2 * $i + 1]);
    }

    // generate new game and notify players
    foreach ($pairs as $pair) {
      $p1 = Auth::find($pair[0])->user;
      $p2 = Auth::find($pair[1])->user;

      $game = new Game;
      $game->player_one = $p1->id;
      $game->player_two = $p2->id;
      $game->start_time = date(DATE_ATOM);
      $game->end_time = date(DATE_ATOM);
      $game->save();

      $first_to_play = rand(1, 2) === 1 ? $p1->id : $p2->id;

      $p1_data = [
        'game' => $game->toJson(),
        'partner' => $p2->toJson(),
        'next_move' => $first_to_play
      ];

      $p2_data = [
        'game' => $game->toJson(),
        'partner' => $p1->toJson(),
        'next_move' => $first_to_play
      ];

      Redis::publish($pair[0].'|match found', json_encode($p1_data));
      Redis::publish($pair[1].'|match found', json_encode($p2_data));
    }
  }

  public static function searchingForMatch($user) {
    return false;
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

  /**
   * Returns BOARD_WIDTH x BOARD_HEIGTH 2d zero array
   */
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

  /**
   * Generates 2d array representation of a board
   */
  public function getBoard() {
    $board = $this->initBoard();
    $this->load('moves');
    foreach ($this->moves as $move) {
      $board[$move->x][$move->y] = $move->player_id;
    }
    return $board;
  }

  public function wasLastMoveWinning($board = null, $last_move = null) {
    if ($board == null) {
      $board = $this->getBoard();
    }
    if ($last_move === null) {
      $last_move = $this->getLastMove();
    }

    if ($last_move->number === -1) {
      //this is a first move
      return false;
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
      if ($x + $i < 0 || $x + $i >= self::BOARD_WIDTH -1 || $y + $j < 0 || $y + $j >= self::BOARD_WIDTH -1) {
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
      if ($x + $i < 0 || $x + $i >= self::BOARD_WIDTH || $y + $j < 0 || $y + $j >= self::BOARD_HEIGHT) {
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
