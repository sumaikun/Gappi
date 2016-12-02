<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achieve extends Model
{
    protected $table = "achievement";

  	public function formulation(){
    	return $this->hasOne('App\Models\Formulation','id','ask_id');
    }
}
