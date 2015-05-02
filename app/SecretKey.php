<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SecretKey extends Model {
  const MIN_KEY_LENGTH = 35;
  const MAX_KEY_LENGTH = 45;

  protected $table = 'game_keys';
  protected $primaryKey = 'secret';
  public $timestamps = false;

  public static function generateSecret() {
    $length = rand(SELF::MIN_KEY_LENGTH, SELF::MAX_KEY_LENGTH);
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
