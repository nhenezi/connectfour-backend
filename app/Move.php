<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Move extends Model {
  protected $table = "moves";
  public $timestamps = false;

  public function moves() {
    return $this->belongsTo('App\Game');
  }
}
