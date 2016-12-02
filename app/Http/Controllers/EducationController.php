<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Tema;

use App\Models\skill;

use App\Models\Formulation;

use App\Models\Qualify;

use App\Helpers\Methods;

use App\Models\Achieve;

use Session;

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


    public function list_challengues()
    {
        $formulations = Formulation::Where('is_challengue','=',1)->get();
        foreach ($formulations as $formulation) {
            $score_value = 0;
            $ask = Qualify::Where('user','=',Session::get('id'))->where('id_ask','=',$formulation->id)->get();
            foreach($ask as $ask)
            {
                if($ask['score']>$score_value)
                {
                    $score_value=$ask['score'];
                }    
            }
            
            if($score_value==100)
            {$formulation->color ='#3ADF00'; }
            elseif($score_value>=70 and $score_value<=90)
            {$formulation->color ='#00FF80'; }
            elseif($score_value>=50 and $score_value<70)
            {$formulation->color ='#2EFEC8'; }
            elseif($score_value>=10 and $score_value<50)
            {$formulation->color ='#CEF6EC'; }
            else
            {
                $formulation->color ='white';
            }
                
            
        }
        //return 'dsdsd';
        return response()->json($formulations);
    }

    public function list_achivements()
    {
        $achivements = Achieve::Where('user','=',Session::get('id'))->get();
        foreach ($achivements as $achivement) {
            $achivement->ask = $achivement->formulation;
        }
        return $achivements;
    }

  

}
