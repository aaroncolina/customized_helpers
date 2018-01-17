<?php

/* fetches the specific filter name and returns a codeigniter-form-ready array data structure */
function fetch_filter($filter_name, $has_default_var=0, $db_name=''){
	$return = array();

	/* If dropdown has a default variable
	*/
	if($has_default_var==1){
		$return[''] = '---';
	}

	$condition = array();
	$condition[] = '`enabled` = "Y"';
	$condition[] = '`filter_name` = "'. $filter_name .'"';


	$query = 'SELECT 
			    `id`, `filter_value`, `display_value`
			FROM
			    `l_filters`
			WHERE 1=1  AND ' . implode(' AND ', $condition).
			' ORDER BY `manual_order` ASC;';
	if(strlen($query)>1){
		foreach(fetch_rows($query) as $key => $value){
			$return[$value['filter_value']] = $value['display_value'];
		}
	}
	return $return;
}


/* This will trim down the given main_array base on the given filter field name and value (can be multiple filters) */
function filter_array_by_field($main_array, $filter=array()){
	$new_array = array_filter($main_array, function ($var) use ($filter) {
		$result[] = $var;
		if(isset($filter) && is_array($filter)){
			foreach($filter as $filter_name => $filter_value){
				// $var[$filter_name] = (is_null($var[$filter_name])? '': $var[$filter_name]);
			   	if($var[$filter_name] != $filter[$filter_name]){

			   		$result = null;

			   	} 
			}
		}
	    return $result;
	});
	return array_values($new_array);
}

?>