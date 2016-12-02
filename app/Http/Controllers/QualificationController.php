<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Helpers\Methods;

use App\Models\Qualify;

use App\Models\Achieve;

use Session;

class QualificationController extends Controller
{
    public function get_score(Request $request){

        $qualification = new Qualify;
        $qualification->id = Methods::id_generator($qualification,'id');
        $qualification->user= Session::get('id');
        $qualification->id_ask=$request->ask_id ;
        $qualification->score=$request->score ;        

    	$existences = Qualify::where('id_ask','=',$request->ask_id)->where('user','=',Session::get('id'))->get();
        
    	if($existences!='[]')
    	{
            foreach($existences as $existence)
            {    
        		if($request->score>$existence['score'])
        		{
        			$qualification->save();
                    if($request->score==100)
                    {
                        $this->achievement_unlocked($request->ask_id);
                    }
                    return $qualification;
        		}
            }    
    	}	
    	else{
    	   $qualification->save();
           if($request->score==100)
            {
                $this->achievement_unlocked($request->ask_id);
            }
    	}


        return $qualification;
    	
    }

    public function achievement_unlocked($ask_id){
        $existence = Achieve::where('ask_id','=',$ask_id)->where('user','=',Session::get('id'))->first();
        if($existence==null)
        {
            $achieve = new Achieve;
            $achieve->id = Methods::id_generator($achieve,'id');
            $achieve->user= Session::get('id');
            $achieve->ask_id=$ask_id ;
            $achieve->save();
        }

    }
}
