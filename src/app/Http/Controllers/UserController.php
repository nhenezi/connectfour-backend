<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Request;
use Hash;
use App\ErrorString;
use App\User;
use App\Auth;

class UserController extends Controller {

  /**
   * Creates new user and generates new access_token
   *
   * @return Response
   */
  public function post() {
    try {
      $email = Request::input('email', null);
      $name = Request::input('name', null);
      $password = Request::input('password', null);

      if ($email === null) {
        throw new \Exception(ErrorString::MISSING_EMAIL);
      }
      if ($name === null) {
        throw new \Exception(ErrorString::MISSING_NAME);
      }
      if ($password === null) {
        throw new \Exception(ErrorString::MISSING_PASSWORD);
      }

      $user = new User();
      $user->email = $email;
      $user->name = $name;
      $user->password = Hash::make($password);
      $user->save();

      $auth = new Auth();
      $auth->user_id = $user->id;
      do {
        $new_token = Auth::generateToken();
      } while (!!Auth::find($new_token));
      $auth->token = $new_token;
      $auth->save();

      $statusCode = 200;
      $response['user'] = $user->toArray();
      $response['user']['access_token'] = $auth->token;
      $response['success'] = true;
    } catch (\Exception $e) {
      $statusCode = 500;
      $response = $e->getMessage();
      $response['success'] = false;
    } finally {
      return response()->json($response, $statusCode);
    }
  }


  /**
   * Retrieves information for dashboard
   */
  public function stats($access_token) {
    try {
      $auth = Auth::find($access_token);

      if ($auth === null) {
        throw new \Exception(ErrorString::INVALID_ACCESS_TOKEN);
      }

      $user = $auth->user;
      $games = $user->games();
      $total_games = 0;
      $won_games = 0;
      $lost_games = 0;
      $tied_games = 0;
      $total_number_of_moves = 0;
      $games_arr = [];
      foreach ($games as $game) {
        $total_games++;
        if ($game->winner === $user->id) {
          $won_games++;
        } elseif ($game->winner === 0) {
          $tied_games++;
        } else {
          $lost_games++;
        }

        if ($game->player_one === $user->id) {
          $game['partner'] = User::find($game->player_two);
        } else {
          $game['partner'] = User::find($game->player_one);
        }

        $game['number_of_moves'] = count($game->moves);
        $total_number_of_moves += $game['number_of_moves'];
        $games_arr[] = $game;
      }
      $statusCode = 200;
      $response['games'] = array_slice($games_arr, 0, 5);
      $response['won_games'] = $won_games;
      $response['lost_games'] = $lost_games;
      $response['tied_games'] = $tied_games;
      $response['total_games'] = $total_games;
    } catch (\Exception $e) {
      $statusCode = 200;
      $response['error'] = $e->getMessage();
      $response['success'] = false;
    } finally {
      return response()->json($response, $statusCode);
    }
  }
}
