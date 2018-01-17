<?php

function prearr($array, $is_die=0){
	echo "<pre>";
	print_r($array);
	echo "</pre>";

	if($is_die==1){
		die;
	}
}

function preecho($data, $is_die=0){
	if(is_array($data)){
		prearr($data);
	} else {
		echo '<pre>' . $data . '</pre>'; 
	}

	if($is_die==1){
		die;
	}
}

/* Used for Associative Array (String as Key)*/
function move_key_from_array($offset_key, $to_move_key, $current_array){
$offset = array_search($offset_key,array_keys($current_array))+1;
$to_move_array = array($to_move_key => $current_array[$to_move_key]);
unset($current_array[$to_move_key]);

$sorted_array = array_slice($current_array, 0, $offset, true) + $to_move_array + array_slice($current_array, $offset, NULL, true);
// prearr($sorted_array);die;
return $sorted_array;
}

// function concat_string_for_names($data, $alter_message="Not yet assigned"){
// 	$result = $alter_message;
// 	if(is_valid($data)){
// 		foreach ($data as $key => $value) {
// 			end($array);
// 			$key = key($array);
// 		}
// 	} else {
// 		return $result;
// 	}
// }

?>