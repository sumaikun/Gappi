<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\User;

use Auth;

class UserController extends Controller
{

	public function __construct(){
		$this->middleware('cors');
	}

    public function store(Request $request) {

       

      
      $file = $request->file('dt');
      $original_name=$file->getClientOriginalName();
     // return response()->json(["message"=>'esta es'.$request->image]);
      return 'finish';
      $user = new User;
    	$user->name = $request->name;
    	$user->email = $request->email;
    	$user->password = $request->password;
    	$user->age = $request->age;
    	$user->rol = $request->rol;
    	$user->save();
    	return response()->json(["message"=>"Usuario Registrado"]);
    }

    public function index()
    {
    	$user = new User;
    	return response()->json(["message"=>"conexión establecida"]);
    }

    public function loggin(Request $request){

       $userdata=["email" =>$request->email,"password"=>$request->password];
	   
  	   if (Auth::attempt($userdata))
  	   {
  		   return response()->json(["message"=>"Logeo exitoso"]);	                 
       }

       else 
       {
          return response()->json(["message"=>"Los datos de inicio estan mal"]);
       }        
    	
    } 
}
