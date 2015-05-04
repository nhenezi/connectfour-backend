<?php

use \App\Game;

class GameTest extends TestCase {

  public function testEmptyGrid() {
    $game = new Game();
    $last_move = new stdClass();
    $last_move->number = 3;

    $board = [
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [1, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 2;
    $last_move->y = 0;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));
  }

  public function testCorners() {
    $game = new Game();
    $last_move = new stdClass();
    $last_move->number = 3;

    $board = [
      [1, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 0;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [1, 0, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 6;
    $last_move->y = 0;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 1],
    ];
    $last_move->x = 6;
    $last_move->y = 6;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [0, 0, 0, 0, 0, 0, 1],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 6;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));
  }

  public function testHorizontalWins() {
    $game = new Game();
    $last_move = new stdClass();
    $last_move->number = 3;

    $board = [
      [2, 0, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 4;
    $last_move->y = 1;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 0, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 1;
    $last_move->y = 1;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 1, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 1;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 6;
    $last_move->y = 1;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));


    $board = [
      [1, 2, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 1;
    $last_move->y = 1;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 2, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 1, 0, 0, 0, 0, 0],
      [2, 1, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 1;
    $last_move->y = 1;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));

  }


  public function testVerticalWins() {
    $game = new Game();
    $last_move = new stdClass();
    $last_move->number = 3;


    $board = [
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [1, 1, 1, 1, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 2;
    $last_move->y = 3;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));


    $board = [
      [2, 0, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 1, 2, 0, 0, 0, 0],
      [1, 1, 1, 1, 0, 0, 0],
      [2, 2, 2, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 3;
    $last_move->y = 3;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));


    $board = [
      [2, 0, 0, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 1, 2, 0, 0, 0, 0],
      [1, 1, 1, 1, 0, 0, 0],
      [2, 2, 2, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 3;
    $last_move->y = 0;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [1, 2, 2, 1, 1, 1, 1],
      [1, 2, 1, 2, 1, 0, 0],
      [2, 1, 2, 0, 0, 0, 0],
      [2, 1, 1, 2, 0, 0, 0],
      [2, 2, 2, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 6;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));


    $board = [
      [1, 2, 2, 1, 1, 2, 1],
      [1, 2, 1, 2, 1, 0, 0],
      [2, 1, 2, 0, 0, 0, 0],
      [2, 1, 1, 2, 0, 0, 0],
      [2, 2, 2, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 6;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));

    $last_move->x = 0;
    $last_move->y = 3;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));
  }

  public function testTLBRWins() {
    $game = new Game();
    $last_move = new stdClass();
    $last_move->number = 3;

    $board = [
      [2, 2, 2, 1, 0, 0, 0],
      [1, 2, 1, 0, 0, 0, 0],
      [2, 1, 2, 0, 0, 0, 0],
      [1, 1, 2, 1, 0, 0, 0],
      [2, 2, 2, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 3;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 1, 2, 2, 1, 1, 2],
      [1, 2, 1, 1, 2, 2, 0],
      [2, 1, 2, 1, 2, 2, 0],
      [1, 1, 2, 2, 1, 0, 0],
      [2, 2, 1, 1, 2, 0, 0],
      [1, 2, 1, 2, 0, 0, 0],
      [2, 2, 1, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 6;
    $last_move->player_id = 2;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 1, 2, 2, 1, 1, 2],
      [1, 2, 1, 1, 2, 2, 0],
      [2, 1, 2, 1, 1, 2, 0],
      [1, 1, 2, 2, 1, 0, 0],
      [2, 2, 2, 1, 2, 0, 0],
      [1, 2, 1, 2, 0, 0, 0],
      [2, 2, 1, 0, 0, 0, 0],
    ];
    $last_move->x = 3;
    $last_move->y = 3;
    $last_move->player_id = 2;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 1, 2, 2, 1, 1, 2],
      [1, 2, 1, 1, 2, 2, 0],
      [2, 1, 2, 1, 1, 2, 0],
      [1, 1, 2, 2, 2, 0, 0],
      [2, 2, 2, 1, 2, 0, 0],
      [1, 2, 1, 2, 0, 0, 0],
      [2, 2, 1, 0, 0, 0, 0],
    ];
    $last_move->x = 2;
    $last_move->y = 3;
    $last_move->player_id = 1;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));
  }


  public function testBLTRWins() {
    $game = new Game();
    $last_move = new stdClass();
    $last_move->number = 3;

    $board = [
      [2, 2, 1, 2, 0, 0, 0],
      [1, 2, 1, 0, 0, 0, 0],
      [2, 1, 2, 0, 0, 0, 0],
      [1, 1, 2, 2, 0, 0, 0],
      [2, 2, 2, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 0;
    $last_move->player_id = 2;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 2, 1, 2, 0, 0, 0],
      [1, 1, 1, 0, 0, 0, 0],
      [2, 1, 2, 0, 0, 0, 0],
      [1, 1, 2, 2, 0, 0, 0],
      [2, 2, 2, 0, 0, 0, 0],
      [1, 2, 0, 0, 0, 0, 0],
      [2, 2, 0, 0, 0, 0, 0],
    ];
    $last_move->x = 0;
    $last_move->y = 0;
    $last_move->player_id = 2;
    $this->assertEquals(false, $game->wasLastMoveWinning($board, $last_move));

    $board = [
      [2, 2, 1, 2, 0, 0, 0],
      [1, 1, 1, 0, 0, 0, 0],
      [2, 1, 2, 0, 0, 0, 0],
      [1, 1, 2, 2, 0, 0, 0],
      [2, 2, 1, 0, 0, 0, 0],
      [1, 2, 2, 1, 0, 0, 0],
      [2, 2, 1, 2, 1, 0, 0],
    ];
    $last_move->x = 6;
    $last_move->y = 4;
    $last_move->player_id = 1;
    $this->assertEquals(true, $game->wasLastMoveWinning($board, $last_move));
  }
}
