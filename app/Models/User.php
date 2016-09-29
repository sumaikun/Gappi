<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract 
{

    use  SoftDeletes,Authenticatable,CanResetPassword;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'age','rol','image'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

   // protected $dates = ['deleted_at']; 

       public function setPasswordAttribute($valor)
      {
        if(!empty($valor))
         {
           $this->attributes['password'] = \Hash::make($valor);
         }
      }
}
