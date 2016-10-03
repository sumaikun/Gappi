<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class skill extends Model
{
    protected $table = 'skill';
    protected $fillable = ['nombre','tema_id'];

    public function temas(){
    	return $this->belongsTo('App\models\Tema','tema_id');
    }
}
