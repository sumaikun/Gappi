<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Formulation;

use App\Helpers\maths_basic_oper;


class AskfactoryV2 extends Controller
{
    private $answer_vars =  array();

    private $condition_prop = array(); 

    private function set_var($index,$value)
    {           
        //array_push($this->answer_vars,$parameter);        
        $this->answer_vars[$index] = $value;
    }

    public function get_ansvars()
    {
        return $this->answer_vars;
    }

        public function get_prop()
    {
        return $this->condition_prop;
    }

    public function random_vars ($parameter)
    {
        $string_array = str_split($parameter);        

        for($i=0;$i<strlen($parameter);$i++)
        {

            if($string_array[$i]=='<')
            {
                if($this->labels($string_array,$i)=='var')
                {
                	$index = $string_array[$i+5];
                	$value =rand(1,30);

                	if (!array_key_exists($index, $this->answer_vars))
                	{
                		$this->set_var($index,$value);

	                	if(isset($this->condition_prop['first']))
	                	{
	                		//echo
	                		 $this->validate_condition($index,$value);
	                	}
                	
                		$string_array[$i+5] = $this->answer_vars[$index]; 	
					}
					else
					{
						$string_array[$i+5] = $this->answer_vars[$index];	
					}                 	
                	
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

				if($this->labels($string_array,$i)=='con')
                {
                	$string_array = $this->rpl_text($string_array,($i),true,false);
                	$n=$i;

                	while($string_array[$n]!=')')
                	{
                		if($string_array[$n]=='('){
            			  $string_array = $this->basic_condition($string_array,$n);   				
                		}                		                		
                		$n+=1;
                	}
                	$string_array[$n]="";
                	//echo 'valor de n '.$n;
                	$string_array = $this->rpl_text($string_array,($n-5),false,true);	
				}             
                
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

    	/*if($string_array[$i].$string_array[$i+1].$string_array[$i+2].$string_array[$i+3].$string_array[$i+4]=='<sum>')
    	{
    		return 'sum';
    	}*/

    	if($string_array[$i].$string_array[$i+1].$string_array[$i+2].$string_array[$i+3].$string_array[$i+4]=='<con>')
    	{
    		return 'con';
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
            if(is_numeric($string_array[$n+5]))
            {
               $flag = $n+5; 
                while(is_numeric($string_array[$flag]))
                {
                    $flag +=1;
                }

            }
            else
            {
                $flag = $n+6;
            }

	    	for($i=$flag;$i<$flag+6;$i++)	
    		{
    			$string_array[$i] = '';
    		}	
	    }    	

    	return $string_array;	
    } 


    public function basic_condition($string_array,$n){

    	$this->condition_prop['first'] = $string_array[$n+1]; 
		$this->condition_prop['cond'] = $string_array[$n+2];
		$this->condition_prop['second'] = $string_array[$n+3];

		
		$string_array[$n]="";
		$string_array[$n+1]="";
		$string_array[$n+2]="";
		$string_array[$n+3]="";

		return $string_array;   	
    }

    public function validate_condition($index,$value){

    	//print_r($this->answer_vars);
    	if(isset($this->answer_vars[$this->condition_prop['first']]) && isset($this->answer_vars[$this->condition_prop['second']]))
    	{
    		if($this->condition_prop['cond']=='>')
    		{
    			if(!($this->answer_vars[$this->condition_prop['first']]>=$this->answer_vars[$this->condition_prop['second']]))
				{
					while($this->answer_vars[$this->condition_prop['first']]<=$this->answer_vars[$this->condition_prop['second']])
					{
						$this->answer_vars[$this->condition_prop['first']] = rand(1,30);
					}
				}
			
    		}
    		if($this->condition_prop['cond']=='<')
    		{
    			return 'menor que';
    		}

    	}
    	else{
    		return 'en indice '.$index.' aun no puedo realizar el proceso ';
    	}
    }

    public function answer_structure($text_answer)
    {
    	//return $text_answer;
    	$oper_array =  array();
    	$oper_pos = array();
    	$asoc_var = array();
    	for($i=0;$i<strlen($text_answer);$i++)
    	{
    		if($text_answer[$i]=='<')
    		{
    			$oper = '';
    			$vari = '';
    			$n = $i ;    			
    		

    			while($text_answer[$i]!='>' and $text_answer[$i]!='/')
    			{    				
    				$i+=1;	

            		if($text_answer[$i]=='v')
            		{
            			
            			if($this->labels($text_answer,$i-1)=='var')
            			{            				            				
                			$vari= $vari.$text_answer[$i+4];
                			array_push($asoc_var, $vari);
            			}
            		}            		
					
    				if($text_answer[$i]!='>'and $text_answer[$i]!='/')
    				{
    					$oper = $oper.$text_answer[$i];
    				}           		 	  				    				

    			}
    			if(!(in_array($oper, $oper_array)) and $oper!='var' and $oper!='cos' and $oper!='' )
    			{
    				array_push($oper_array, $oper);
    				array_push($oper_pos, $n);    				
    			}
    			 
    		}
    	}

    	//print_r($oper_array);
    	//print_r($oper_pos);
    	//print_r($asoc_var);

    	$change = '';
    	$temp_text = '';
    	$last_value = 0;
    	
    	for($i=count($oper_array)-1;$i>-1;$i--)
    	{    		

    		$str = "";
    		//echo $oper_array[$i];
			$j=$oper_pos[$i];
			$text_oper = $oper_array[$i];			

			while($text_answer[$j].$text_answer[$j+1].$text_answer[$j+2]!='</'.$text_oper[0])
			{
				$str=$str.$text_answer[$j];
				$j++;	
			}
			$str = $str.'</'.$oper_array[$i].'>';
			//echo $str;
			if($change=='')
			{
				$last_value = $this->method_answer($str);
				$answer = $last_value; 
				//echo 'esta es una respuesta '.$last_value;				
				$temp_text = $str; 
				$change = 'change';	
			}
    		else{
    			$key = array_search($last_value, $this->answer_vars);
    			//echo 'esta es la key '.$key;
    			$conca_key = '<var>'.$key.'</var>';
	    		$str_copy = str_replace($temp_text,'',$str);
	    		$temp_text = $str;	    		
    			//echo 'segundo texto '.$str_copy;
    			$final_text =substr($str_copy, 0,strlen($oper_array[$i])+2).$conca_key.substr($str_copy,strlen($oper_array[$i])+2,strlen($str_copy));
    			//echo 'texto final '.$final_text; 
    			//echo 'operacion final '.$this->method_answer($final_text);
    			$answer = $this->method_answer($final_text);

    		}    		
    	}


    	return $answer;
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
	                   //echo 'operadores '.$operation.' y '.$this->answer_vars[$index];	
	                   $operation = maths_basic_oper::$process($operation,$this->answer_vars[$index]);
	                   $this->set_var($index,$operation);
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
