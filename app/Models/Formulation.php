<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Formulation extends Model
{
    protected $table = 'formulation';
    protected $fillable = ['titulo','enunciado','respuesta','skill_id'];

    public function habilidades(){
    	return $this->belongs('App\models\skill','skill_id');
    }
}
