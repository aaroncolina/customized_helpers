<?php

function validate_array($array){
	return ((is_array($array) && count($array)>0) ? $array : 'error');
}

function is_valid($string){
	return ((isset($string) && !empty($string) && $string !== '-') ? $string : false);
}

?>