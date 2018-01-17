<?php

function get_prior_dallas_array($string){
	if(strlen($string)>2){
		// $
	}
}

function convert_currency_to_number($number){
	$result = str_replace(',', '', $number);
	$result = str_replace('$', '', $result);

	if(substr($result, -1) === "."){
		$result = str_replace('.', '', $result);
	}

	return $result;
}

function convert_number_to_currency($number){
	$result = '$'.number_format($number);
	return $result;
}

function convert_percentage_to_number($number){
	$result = str_replace('%', '', $number);
	return $result;
}

function convert_number_to_percentage($number){
	$result = number_format($number) . '%';
	return $result;
}

function remove_double_quotes($string){
	$string = str_replace('"', '\"', $string);
	return $string;
}

?>