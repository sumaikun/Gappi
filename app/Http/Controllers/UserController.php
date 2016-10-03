<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\User;

use Auth;

use Storage;

use Session;

class UserController extends Controller
{

	public function __construct(){
		$this->middleware('cors');
	}

    public function store(Request $request){

	    $file = $request->file('file');     
      $original_name = $file->getClientOriginalName();     
      $upload=Storage::disk('profiles')->put($original_name,  \File::get($file) );
      if($upload)
      {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->age = $request->age;
        $user->rol = $request->rol;
        $user->image = $original_name;
        $user->save();
        return response()->json(["message"=>"Usuario Registrado"]);
      }
      else
      {
        return response()->json(["message"=>"Error al subir archivo"]); 
      }  

      
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

          $user = User::Where('email','=',$request->email)->get();

          Session::put('id',$user[0]->id); 
          Session::put('name', $user[0]->name);
          Session::put('age', $user[0]->age);
          Session::put('rol', $user[0]->rol);
          Session::put('image', $user[0]->image);
          

  		   return response()->json(["message"=>"Logeo exitoso"]);	                 
       }

       else 
       {
          return response()->json(["message"=>"Los datos de inicio estan mal"]);
       }        
    	
    }

   public function upload(Request $request){

    $file = $request->file('file');
     
    $original_name = $file->getClientOriginalName();
     
    $upload=Storage::disk('archivos')->put($original_name,  \File::get($file) );    //echo var_dump($request->file);

    

     return 'his '.$original_name;
   } 

   public function credentials()
   {   

     return response()->json(["id"=>Session::get('id'),"name"=>Session::get('name'),"age"=>Session::get('age'),"rol"=>Session::get('rol'),"image"=>Session::get('image')]);
      
   }

   public function logout()
   {
      Auth::logout();
      Session::flush();
   } 
}
