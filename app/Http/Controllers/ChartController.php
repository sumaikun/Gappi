<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Qualify;

use App\Models\User;

class ChartController extends Controller
{
    public function chart_gene_scores(){
    	$users = User::All();
    	$data = [];
    	foreach($users as $user)
    	{
    		$total_scores = 0;
    		$infos = Qualify::where('user','=',$user->id)->get();
    		foreach($infos as $info)
    		{
    			$total_scores +=  $info->score;
    		}

    		$array = array(
    		"label" => "",
    		"value" => "",);

    		$average = ($total_scores/count($infos));
    		$array["label"] = $user->name;
    		$array["value"] = $average;
    		array_push($data, $array);

    	}

    	 return response()->json($data);
    	
    }

    public function chart_gene_attemps(){
        $users = User::All();
        $data = [];
        foreach($users as $user)
        {
            $total_scores = 0;
            $count = Qualify::where('user','=',$user->id)->count();
            

            $array = array(
            "label" => "",
            "value" => "",);
            
            $array["label"] = $user->name;
            $array["value"] = $count;
            array_push($data, $array);

        }

         return response()->json($data);
        
    }  
}
