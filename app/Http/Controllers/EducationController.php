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

        $formulation = new Formulation;
        $formulation->titulo = $request->titulo;
        $formulation->enunciado = $request->enunciado; 
        $formulation->respuesta = $request->respuesta;
        $formulation->skill_id = $request->skill;

        $formulation->save();

        return response()->json(["message"=>"Formulacion Creada"]);
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

            if($string_array[$i]=='$')
            {                   
                $index = $string_array[$i+1];
                $string_array[$i+1]=rand(1,10);
                //$array_merge = array($index=>$string_array[$i+1]);
                //echo var_dump($array_merge);
                //array_push($this->answer_vars,$string_array[$i+1]);
                $this->set_var($index,$string_array[$i+1]);
                $string_array[$i] = ' ';
            }


        }

        return implode($string_array);
    }

    public function method_answer ($kind_answer)
    {

       $datos = $this->get_ansvars();      
       $kind_answer = " ".$kind_answer." "; 
       $string_array = str_split($kind_answer);

       if(strrpos($kind_answer,'sum'))
       {
         $suma=null;               
            for($i=0;$i<strlen($kind_answer);$i++)
            {
               if($string_array[$i]=='$')
                {   

                    $index = $string_array[$i+1];
                      
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


    }


}
