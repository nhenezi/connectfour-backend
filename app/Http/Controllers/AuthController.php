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
  public function index($access_token) {
    try {
      $auth = Auth::find($access_token);

      if ($auth === null) {
        throw new \Exception(ErrorString::INVALID_ACCESS_TOKEN);
      }

      $statusCode = 200;
      $response = $auth->user->toArray();
    } catch (\Exception $e) {
      $statusCode = 500;
      $response = $e->getMessage();
    } finally {
      return response()->json($response, $statusCode);
    }
  }

  /**
   * Logs in a user and creates new access_token
   *
   * @return Response
   */
  public function store() {
    try {
      $email = Request::input('email', null);
      $password = Request::input('password', null);
      $data = [$email, $password];
      if ($email === null or $password === null) {
        throw new \Exception(ErrorString::INVALID_USERNAME_OR_PW);
      }

      $user = User::find(['email' => $email]);
      if ($user === null || !Hash::check($password, $user->password)) {
        throw new \Exception(ErrorString::INVALID_USERNAME_OR_PW);
      }

      do  {
        $new_token = Auth::generateToken();
      } while (!!Auth::find($new_token));

      $auth = Auth::find($new_token);
      $auth->token = $new_token;
      $auth->save;

      $statusCode = 200;
      $response = $user->toArray();
      $response['access_token'] = $auth->token;
    } catch (\Exception $e) {
      $statusCode = 500;
      $response = $e->getMessage();
    } finally {
      return response()->json($response, $statusCode);
    }
  }

}
