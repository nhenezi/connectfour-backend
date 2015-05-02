<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model {
  protected $table = "auths";
  protected $primaryKey = 'token';
  public $timestamps = false;

  const MIN_KEY_LENGTH = 45;
  const MAX_KEY_LENGTH = 55;

  public static function generateToken() {
    $length = rand(SELF::MIN_KEY_LENGTH, SELF::MAX_KEY_LENGTH);
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public function user() {
    return $this->hasOne('App\User');
  }
}
