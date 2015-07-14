<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Request;
use Hash;
use App\ErrorString;
use App\User;
use App\Auth;

class AuthController extends Controller {

  /**
   * Retrieves authentication status
   *
   * @return Response
   */
  public function get($access_token) {
    try {
      $auth = Auth::find($access_token);

      if ($auth === null) {
        throw new \Exception(ErrorString::INVALID_ACCESS_TOKEN);
      }

      $statusCode = 200;
      $response['user'] = $auth->user->toArray();
      $response['success'] = true;
    } catch (\Exception $e) {
      $statusCode = 200;
      $response['error'] = $e->getMessage();
      $response['success'] = false;
    } finally {
      return response()->json($response, $statusCode);
    }
  }

  /**
   * Logs in a user and creates new access_token
   *
   * @return Response
   */
  public function post() {
    try {
      $email = Request::input('email', null);
      $password = Request::input('password', null);
      if ($email === null or $password === null) {
        throw new \Exception(ErrorString::INVALID_USERNAME_OR_PW);
      }

      $user = User::where('email', $email)->first();
      if ($user === null || !Hash::check($password, $user->password)) {
        throw new \Exception(ErrorString::INVALID_USERNAME_OR_PW);
      }

      do  {
        $new_token = Auth::generateToken();
      } while (!!Auth::find($new_token));

      $auth = $user->auth;
      $auth->token = $new_token;
      $auth->save();

      $statusCode = 200;
      $response['user'] = $user->toArray();
      $response['user']['access_token'] = $auth['token'];
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
