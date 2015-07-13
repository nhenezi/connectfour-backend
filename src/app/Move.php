<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Move extends Model {
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = "moves";
  public $timestamps = false;

  public function moves() {
    return $this->belongsTo('App\Game');
  }
}
