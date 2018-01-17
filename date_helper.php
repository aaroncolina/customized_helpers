<?php 

function convert_date_on_array($array, $method){
	if(is_array($array) && count($array)>0){
		foreach ($array as $key => $value) {
			$array[$key] = convert_date_to_format($value, $method);
		}
	}
	return $array;
}

function convert_date_to_format($date, $method){
	$formatted_date = '';
	switch($method){
		case "american_to_mysql":
			if (DateTime::createFromFormat('m/d/Y', $date) !== FALSE) {
			 	$formatted_date = date('Y-m-d', strtotime($date));
			}
			break;
		case "mysql_to_american":
			if (DateTime::createFromFormat('Y-m-d',$date) !== FALSE) {
			 	$formatted_date = date('m/d/Y', strtotime($date));
			}else if (DateTime::createFromFormat('Y-m-d H:i:s',$date) !== FALSE) {
			 	$formatted_date = date('m/d/Y g:i A', strtotime($date));
			}
			break;
	}

	return (!empty($formatted_date)) ? $formatted_date : $date;
}

function get_date_now(){
	return date('Y-m-d H:i:s');
}


?>