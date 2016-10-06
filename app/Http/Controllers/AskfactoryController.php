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

    private function get_ansvars()
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
            
    
             return response()->json($formulaciones[$index]); 
        }   
        else{
            return response()->json(["message"=>"NO hay preguntas relacionadas"]);
        } 
    }

     //Metodos encargados de fabricar la pregunta


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
                	$this->set_var($index,$string_array[$i+5]);
                	$string_array = $this->rpl_text($string_array,$i,true,true);                	
				}                
                //$array_merge = array($index=>$string_array[$i+1]);
                //echo var_dump($array_merge);
                //array_push($this->answer_vars,$string_array[$i+1]);
                
            }


        }

        return implode($string_array);
    }

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
	    	for($i=$n+6;$i<$n+12;$i++)	
    		{
    			$string_array[$i] = '';
    		}	
	    }	
    	

    	return $string_array;	
    }

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

    /*public function method_answer ($kind_answer)
    {

       $datos = $this->get_ansvars();      
       $kind_answer = " ".$kind_answer." "; 
       $string_array = str_split($kind_answer);

       if(strrpos($kind_answer,'sum'))
       {
         $suma=null;               
            for($i=0;$i<strlen($kind_answer);$i++)Confirmar correo electrónico
            {
               if($string_array[$i]=='<')
                {   
                	if($this->labels($string_array,$i)=='var')
					{
						$index = $string_array[$i+5];
                      
	                    if($suma==null)
	                    {                                               
	                        $suma = $this->answer_vars[$index];                         
	                           
	                    }   
	                    else
	                    {
	                       $suma = $suma+$this->answer_vars[$index];
	                    }	
					}

					if($this->labels($string_array,$i)=='cos')
                    {

                    	$index = 'K';

                    	if($suma==null)
	                    {                                               
	                        $suma = $this->answer_vars[$index];                         
	                           
	                    }   
	                    else
	                    {
	                       $suma = $suma+$this->answer_vars[$index];
	                    }	
                    }   
                   
                }
            }

          return $suma;  

       }

       elseif(strrpos($kind_answer,'rest')) {

            $resta=null;

            for($i=0;$i<strlen($kind_answer);$i++)
            {
               if($string_array[$i]=='$')
                {                   
                     $index = $string_array[$i+1];
                    if($resta==null)
                    {
                      $resta = $this->answer_vars[$index];                    
                    }
                    else
                    {
                      $resta = $resta-$this->answer_vars[$index];
                    }
                   
                }
            }

          return $resta;
            
       } 

       elseif(strrpos($kind_answer,'mult')) {

          $mult = null;

            for($i=0;$i<strlen($kind_answer);$i++)
            {
               if($string_array[$i]=='$')
                {                   
                    $index = $string_array[$i+1];
                    if($mult==null){

                        $mult = $this->answer_vars[$index];
                    }
                    else {

                        $mult = $mult*$this->answer_vars[$index];
                    }                   
                   
                }
            }

          return $mult;
            
       }

       elseif(strrpos($kind_answer,'div')) {

         $divi = null;

            for($i=0;$i<strlen($kind_answer);$i++)
            {
               if($string_array[$i]=='$')
                {                   
                    $index = $string_array[$i+1];
                    if($divi==null){
                        $divi = $this->answer_vars[$index];
                    }
                    else {
                        $divi = $divi/$this->answer_vars[$index];    
                    }
                    
                   
                }
            }

          return number_format($divi,2);
            
       }


    }*/

   /*   public function list_challengues()
    {
        $formulations = Formulation::Where('is_challengue','=',1)->get();

        foreach($formulations as $formulation)
        {
            $formulation->Enunciado = $this->random_vars($formulation->Enunciado);
            $formulation->respuesta = $this->method_answer($formulation->respuesta); 
        }

        return response()->json($formulations);
    }*/

         

}
