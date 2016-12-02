<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\AskfactoryController;

use App\Models\Formulation;

class ChallengueBuilder extends Controller
{
	function __construct(){		
		$this->factory = new AskfactoryController();
	}     

    public function generate_challengues($id)
    {
    	//Versión antigua con limites lo cual ha sido rechazado en el momento

	   	//$formulation_ch = Formulation::Where('id','=',$id)->first();

	    /*$limits = explode(',',$formulation_ch->size);

	    $limits[2] = ($limits[0]+$limits[1])/2;

	    $final_size = array_rand($limits,1);

	    $list_formulations = array();*/

	    //echo 'tamaño '.$limits[$final_size];

	    for($i=0;$i<10;$i++)
	    {	    	

	    	$formulation_ch = Formulation::Where('id','=',$id)->first();

	    	//echo 'original '.$formulation_ch;	    	

	    	$list_formulations[$i] = $formulation_ch;
	    	
	    	$list_formulations[$i]->Enunciado = $this->factory->random_vars($list_formulations[$i]->Enunciado);

    		$list_formulations[$i]->respuesta = $this->factory->method_answer($list_formulations[$i]->respuesta);

    		//echo $list_formulations[$i];
    		//echo '<br>';	
	    }	

    	return response()->json($list_formulations);

    	
    }

    
}
