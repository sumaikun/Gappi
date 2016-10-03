<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Tema;

use App\Models\skill;

use App\Models\Formulation;

use App\Helpers\Methods;

class EducationController extends Controller
{


  


    public function create_Tema(Request $request)
    {
    	$tema = new Tema;
    	$tema->nombre = $request->name;
    	$tema->save();

    	return response()->json(["message"=>"Tema Guardado"]); 
    } 

    public function list_Tema()
    {
    	$temas = Tema::lists('nombre','id');

    	return response()->json($temas);
    }

    public function create_Habilidad(Request $request)
    {
        $skill = new skill;
        $skill->nombre = $request->name;
        $skill->tema_id = $request->tema;
        $skill->save();

        return response()->json(["message"=>"Habilidad Guardada"]);  
    }

    public function list_Habilidad()
    {
        $temas = skill::lists('nombre','id');

        return response()->json($temas);
    }

    public function create_Formulacion(Request $request)
    {

        if($request->reto==false)
        {
           $is_challengue = 0;
        }
        else
        {
           $is_challengue = 1;   
        }   

        $formulation = new Formulation;
        $formulation->titulo = $request->titulo;
        $formulation->enunciado = $request->enunciado; 
        $formulation->respuesta = $request->respuesta;
        $formulation->skill_id = $request->skill;
        $formulation->is_challengue = $is_challengue;

        $formulation->save();

        return response()->json(["message"=>"Formulacion Creada"]);
    }

  

}
