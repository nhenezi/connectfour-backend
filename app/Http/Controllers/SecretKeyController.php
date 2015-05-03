<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Auth;
use App\ErrorString;
use App\User;

use Illuminate\Http\Request;

class SecretKeyController extends Controller {
  /**
   * Creates new secret key
   *
   * @return Response
   */
  public function post($access_token) {
    try {
      $auth = Auth::find($access_token);
      if ($auth === null) {
        throw new \Exception(ErrorString::INVALID_ACCESS_TOKEN);
      } else {
        $user = $auth->user;
      }

      do  {
        $secret = \App\SecretKey::generateSecret();
      } while (!!\App\SecretKey::find($secret));

      $secretKey = new \App\SecretKey();
      $secretKey->secret = $secret;
      $secretKey->creator_id = $user->id;
      $secretKey->created_at = time();
      $secretKey->save();

      $statusCode = 200;
      $response = $secretKey->toArray();
    } catch (\Exception $e) {
      $statusCode = 500;
      $response = ["error" => $e->getMessage()];
    } finally {
      return response()->json($response, $statusCode);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function put($secret, $access_token = null) {
    try {
      $auth = Auth::find($access_token);
      if ($auth === null) {
        $user = User::find(USER::ANNON_ID);
      } else {
        $user = $auth->user;
      }

      $secretKey = \App\SecretKey::find($secret);
      if ($secretKey === null) {
        throw new \Exception(ErrorString::INVALID_SECRET_KEY);
      }
      if ($user->id === $secretKey->player_one) {
        throw new \Exception(ErrorString::CANNOT_JOIN_OWN_GAME);
      }

      $game = new \App\Game();
      $game->player_one = $secretKey->creator_id;
      $game->player_two = $user->id;
      $game->start_time = date(DATE_ATOM);
      $game->end_time = date(DATE_ATOM);
      $game->save();
      $secretKey->delete();

      $statusCode = 200;
      $response = $game->toArray();
    } catch (\Exception $e) {
      $statusCode = 500;
      $response = ["error" => $e->getMessage()];
    } finally {
      return response()->json($response, $statusCode);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    //
  }

}
