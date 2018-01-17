<?php

function get_substageid_name($substageid){
	$CI =& get_instance();
	return $CI->session->substageid_substage_array[$substageid];
}

?>