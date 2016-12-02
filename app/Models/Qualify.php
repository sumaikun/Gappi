<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualify extends Model
{
     protected $table = 'Qualification';

      public function users(){
    	return $this->belongsTo('App\Models\User','user','id');
    }

       public function formulation(){
    	return $this->hasOne('App\Models\Formulation','id','id_ask');
    }
}
