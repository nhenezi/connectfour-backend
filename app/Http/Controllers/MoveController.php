<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Auth;
use App\ErrorString;
use App\User;
use App\Game;
use App\Move;
use Request;

class MoveController extends Controller {
  public function post($game_id, $access_token = null) {
    $auth = Auth::find($access_token);
    if ($auth === null) {
      $user = User::find(User::ANNON_ID);
    } else {
      $user = $auth->user;
    }

    $game = Game::find($game_id);
    if ($game === null) {
      throw new \Exception(ErrorString::INVALID_GAME_ID);
    }

    return response()->json($game->getLastMove()->toArray());
    if ($game->player_one !== $user->id && $game->player_two !== $user->id) {
      throw new \Exception(ErrorString::INVALID_GAME_ID);
    }

    $column_move = (int) Request::input('move', null);
    if ($column_move === null) {
      throw new \Exception(ErrorString::MISSING_COLUMN_MOVE);
    }

    $last_move = $game->getLastMove();
    $moves_so_far_in_column = $game->movesInColumn($column_move);
    $move = new Move();
    $move->game_id = $game->id;
    $move->player_id = $user->id;
    $move->time = date(DATE_ATOM);
    $move->x = $column_move;
    $move->y = $moves_so_far_in_column;
    $move->number = $last_move->number + 1;
    $move->save();

    $statusCode = 200;
    $response = $move->toArray();
    $response['board'] = $game->getBoard();
    $response['lm'] = $game->getLastMove()->toArray();
    $response['winning'] = $game->wasLastMoveWinning();

    return response()->json($response, $statusCode);
  }
}
