<?php

function set_result_parameter($status, $function, $message, $data_array=""){
	$result = array();
	$result['status'] = $status;
	$result['function'] = $function;
	$result['message'] = $message;
	if(is_array($data_array) && count($data_array) > 0){
		foreach($data_array as $key => $value){
			$result[$key] = $value;
		}
	}
	return $result;
}

function get_fields_from_array($array){
	$result = array();

	if(isset($array[0]) && count($array[0])>0){
		foreach($array[0] as $key => $value){
			$result[] = $key;
		}
	}
	return $result;

}

?>