<?php  
/**
* 
*/
namespace App\Helpers;

class maths_basic_oper{


	public static function sum($a,$b){
		return ($a+$b);
	}

	public static function rest($a,$b){
		return ($a-$b);
	}

	public static function mult($a,$b){
		return ($a*$b);
	}
	public static function divi($a,$b){
		//solucion temporal a division por 0 (zero)

		if($b==0){
			$b = 1 ;
		}
		return number_format(($a/$b),2); 
	}


}	