<?php  
/**
* 
*/
namespace App\Helpers;

class Methods{


public static function id_generator($table,$id){
		$query = $table::lists($id)->last();
		if($query!=null)
		{
			return $query+1;	
		}	
		else {
			return 1;
		} 
	}


}	