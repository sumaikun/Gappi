<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Formulation;

use App\Http\Controllers\AskfactoryController;

use Cache;

class Rule3Builder extends Controller
{
	function __construct(){		
		$this->factory = new AskfactoryV2();
	}


	public function generate_question(){
		$question = Formulation::where('skill_id','=',5)->first();
		//$question = Formulation::where('id','=',18)->first();
		$question->Enunciado = $this->factory->random_vars($question->Enunciado);
		$question->respuesta = $this->factory->answer_structure($question->respuesta);
		//print_r($this->factory->get_ansvars());
		//echo 'variables de condiciones';
		//print_r($this->factory->get_prop());
		Cache::put($question->id,$question->respuesta, 10);
		return $question;
	}

	public function getfromcache($id,$answer){

		if((int)$answer==(int)Cache::get($id))
		{
			return response()->json(["message"=>"Correcto"]);
		}
		else{
			return response()->json(["message"=>"Incorrecto"]);
		}		  
	}

	public function take_five_questions(){
		$questions = Formulation::where('is_challengue','=',0)->paginate(5);
		foreach($questions as $question)
	    {   	
	    	$question->Enunciado = $this->factory->random_vars($question->Enunciado);

    		$question->respuesta = $this->factory->answer_structure($question->respuesta);
	
	    }
		return response()->json($questions);
		

	}    
}
