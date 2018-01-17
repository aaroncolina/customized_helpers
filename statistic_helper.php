<?php

function compute_standard_deviation($array){
	if(count($array)>0){
	    $total_count = count($array);
	    $variance = 0;
	    $sum = array_sum($array);
	    $average = $sum/$total_count;
	    foreach($array as $val) {
	      $variance += pow(($val - $average), 2);
	    }
	    return sqrt($variance / ($total_count-1));
	}
}
?>