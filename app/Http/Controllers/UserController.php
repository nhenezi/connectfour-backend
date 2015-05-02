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
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store() {
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
      do  {
        $new_token = Auth::generateToken();
      } while (!!Auth::find($new_token));
      $auth->token = $new_token;
      $auth->save();

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
