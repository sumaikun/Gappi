<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Formulation;

use App\Helpers\maths_basic_oper;

class AskfactoryController extends Controller
{


	private $answer_vars =  array();

    private function set_var($index,$value)
    {           
        //array_push($this->answer_vars,$parameter);        
        $this->answer_vars[$index] = $value;
    }

    public function get_ansvars()
    {
        return $this->answer_vars;
    }  

        public function search_Formulacion($id)
    {
        $formulaciones = Formulation::Where('skill_id','=',$id)->get();
        if($formulaciones!='[]')
        {
            //echo count($formulaciones);

            $index = rand(0,count($formulaciones)-1);

            //echo $index;

            $formulaciones[$index]->Enunciado = $this->random_vars($formulaciones[$index]->Enunciado);

            //return response()->json($formulaciones[$index]);
           
            $formulaciones[$index]->respuesta = $this->method_answer($formulaciones[$index]->respuesta);
            
              //print_r($this->answer_vars);
             return response()->json($formulaciones[$index]); 
        }   
        else{
            return response()->json(["message"=>"NO hay preguntas relacionadas"]);
        } 
    }

     //Metodos encargados de fabricar la pregunta


    //metodo que se encarga de darle un numero a las variables y constantes

    public function random_vars ($parameter)
    {
        $string_array = str_split($parameter);
        

        for($i=0;$i<strlen($parameter);$i++)
        {
            //echo $string_array[$i];

            if($string_array[$i]=='<')
            {
                if($this->labels($string_array,$i)=='var')
                {
                	$index = $string_array[$i+5];
                	$string_array[$i+5]=rand(1,30);                	
                	$this->set_var($index,$string_array[$i+5]);
         			$string_array = $this->rpl_text($string_array,$i,true,true);           
                }   

                 if($this->labels($string_array,$i)=='cos')
                {
                	//la K identificara constantes
                	$index = 'K';

                    $flag=$i+5;
                    $number = '';
                    while(is_numeric($string_array[$flag]))
                    {
                        //echo 'numero '.$string_array[$flag];
                       
                        $number = $number.$string_array[$flag];
                        $flag +=1; 
                    }

                	$this->set_var($index,$number);

                    /*print_r($string_array);
                    echo 'esto es i '.$i;
                    echo '<br>';*/ 
                	$string_array = $this->rpl_text($string_array,($i),true,true);                	
				}                
             
                
            }


        }

        return implode($string_array);
    }

    //metodo que se encarga de identificar las etiquetas para reconocer un proceso
    public function labels($string_array,$i)
    {
    	if($string_array[$i].$string_array[$i+1].$string_array[$i+2].$string_array[$i+3].$string_array[$i+4]=='<var>')
    	{
    		return 'var';
    	}

    	if($string_array[$i].$string_array[$i+1].$string_array[$i+2].$string_array[$i+3].$string_array[$i+4]=='<cos>')
    	{
    		return 'cos';
    	}

    	if($string_array[$i].$string_array[$i+1].$string_array[$i+2].$string_array[$i+3].$string_array[$i+4]=='<sum>')
    	{
    		return 'sum';
    	}	
    }

    //metodo que se encarga de reemplazar el texto de las etiquetas
    public function rpl_text($string_array,$n,$left,$right)
    {

    	if($left == true)
    	{
    		for($i=$n;$i<$n+5;$i++)
    		{
    			$string_array[$i] = '';
    		}
	    }	
    	
	    if($right == true)
	    {
            if(is_numeric($string_array[$n+5])){
               $flag = $n+5; 
                while(is_numeric($string_array[$flag]))
                {
                    $flag +=1;
                }

            }
            else{
                $flag = $n+6;
            }

	    	for($i=$flag;$i<$flag+6;$i++)	
    		{
    			$string_array[$i] = '';
    		}	
	    }	
    	

    	return $string_array;	
    }

    //metodo que se encarga de gestionar el proceso matematico basico
	public function method_answer ($kind_answer)
	{
		$kind_answer = " ".$kind_answer." "; 
        $string_array = str_split($kind_answer);
        if(strrpos($kind_answer,'sum')){$process = 'sum';}
        if(strrpos($kind_answer,'rest')){$process = 'rest';}
        if(strrpos($kind_answer,'mult')){$process = 'mult';}
        if(strrpos($kind_answer,'divi')){$process = 'divi';}

        $operation = null;	
        for($i=0;$i<strlen($kind_answer);$i++)
        {
	        if($string_array[$i]=='<')
	        {
	        	if($this->labels($string_array,$i)=='var')
				{
					$index = $string_array[$i+5];
	              
	                if($operation==null)
	                {                                               
	                    $operation = $this->answer_vars[$index];                         
	                       
	                }   
	                else
	                {
	                   //$operation = $operation+$this->answer_vars[$index];
	                   	
	                   $operation = maths_basic_oper::$process($operation,$this->answer_vars[$index]);
	                }	
				}

				if($this->labels($string_array,$i)=='cos')
	            {

	            	$index = 'K';

	            	if($operation==null)
	                {                                               
	                    $operation = $this->answer_vars[$index];                         
	                       
	                }   
	                else
	                {
	                    $operation = maths_basic_oper::$process($operation,$this->answer_vars[$index]);
	                }	
	            }
	        }	
        }

        return $operation;
	}    


}
