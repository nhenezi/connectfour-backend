<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Request;
use Hash;
use App\ErrorString;
use App\User;
use App\Auth;
use App\Game;
use Redis;

class MatchController extends Controller {

  /**
   * Tries to find match for user. If no match is found user is
   * added to redis queue until match is found.
   *
   * @return Response
   */
  public function post($access_token) {
    try {
      $auth = Auth::find($access_token);

      if ($auth === null) {
        throw new \Exception(ErrorString::INVALID_SECRET_KEY);
      }

      Redis::rpush('users in poll', $auth->token);
      $match = Game::findMatch($auth->user);
      $response['match'] = $match;

      $statusCode = 200;
      $response['success'] = true;
    } catch (\Exception $e) {
      $statusCode = 200;
      $response['error'] = $e->getMessage();
      $response['success'] = false;
      $response['trace'] = $e->getTrace();
    } finally {
      return response()->json($response, $statusCode);
    }
  }

  /**
   * Removes user from matching poll
   *
   * @return Response
   */
  public function cancelSearch($access_token) {
    try {
      $auth = Auth::find($access_token);

      if ($auth === null) {
        throw new \Exception(ErrorString::INVALID_SECRET_KEY);
      }

      Redis::lrem('users in poll', 0, $auth->token);

      $statusCode = 200;
      $response['success'] = true;
    } catch (\Exception $e) {
      $statusCode = 200;
      $response['error'] = $e->getMessage();
      $response['success'] = false;
    } finally {
      return response()->json($response, $statusCode);
    }

  }
}
