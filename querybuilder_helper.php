<?php

function fetch_rows($query, $db_name='', $activate_cache=false){
	$CI =& get_instance();
	$return = array();
	$db = $CI->load->database($db_name, true);

	if(strlen($query)>1){
		// if($activate_cache){
		// 	$CI->db->cache_on();
		// }
		$result = $db->query($query);
		$return = $result->result_array();
	}

	$db->close();
	return $return;
}

function execute_query($query, $db_name=''){
	$CI =& get_instance();
	$return = array();

	$db = $CI->load->database($db_name, true);

	if(strlen($query)>1){
		$result = $db->query($query);
	}

	$db->close();
	return $return;
}

/* Escapes all string in the array parameter given */
function escape_array($array, $db_name='', $bypass=array()){
	$CI =& get_instance();

	$db = $CI->load->database($db_name, true);
	if(is_array($array)){
		foreach($array as $key => $value){
			$value = ((is_valid($value))? $value : null);
			$array[$key] = $value;
			if(count($bypass) > 0 && !in_array($key, $bypass)){
				$array[$key] = $db->escape_str($value);
			}
		}
	}
	return $array;
}

function execute_transaction($query='', $return_query='', $db_name=''){

	$CI =& get_instance();
	$return = array();

	$db = $CI->load->database($db_name, true);
	$db->trans_start();
	$db->trans_strict(TRUE);

	/* Check if String or Array Query was given as parameter on $query */
	if(is_array($query)){
		foreach($query as $key => $value){
			$db->query($value);
		}
	} elseif(strlen($query)>1) {
		$db->query($query);
	}
	/**/

	/* Check if String was given as parameter on $query */
	if(strlen($return_query)>1) {
		$result = $db->query($return_query);
		$return = $result->result_array();
	}
	/**/

	$db->trans_complete();

	$db->close();
    return $return;
}


function create_condition($condition, $array){
	$query_generated = '';
	if(is_array($array)){
		//INCLUDE
		if(is_array($array['include'])){
			if(count($array['include'])==1 && $array['include'][0] == ""){
				$query_generated .= $condition . ' IS NULL ';
			}elseif(count($array['include'])==1){
				$query_generated .= $condition . ' = "' . $array['include'][0] . '" ';
			} elseif(count($array['include'])>1) {
				$include_vars = array();
				foreach($array['include'] as $key => $value){
					$include_vars[] = '"'.$value.'"';
				}
				$query_generated .= $condition . ' IN(' . implode(',', $include_vars) . ') ';
			}
		}

		//EXCLUDE
		if(is_array($array['exclude']) && count($array['exclude'])>0){
			// add "AND" on the query to build
			$query_generated .= ((strlen($query_generated)>0)? " AND " : '');
			if(count($array['exclude'])==1){
				$query_generated .= $condition . ' <> "' . $array['exclude'][0] . '" ';
			} elseif(count($array['exclude'])>1) {
				$exclude_vars = array();
				foreach($array['exclude'] as $key => $value){
					$exclude_vars[] = '"'.$value.'"';
				}
				$query_generated .= $condition . 'NOT IN(' . implode(',', $exclude_vars) . ') ';
			}
		}

	}

	return $query_generated;
}

function create_substage_condition($data){
	
	$CI =& get_instance();
	$pcb_substageid_default = 'substageid';
	if(is_array($data)){
    	if(is_valid($data['stage'])){
    		$pcb_substageid_field = ((is_valid($data['substageid_field'])) ? $data['substageid_field'] : $pcb_substageid_default);
    		
		 	$condition[$pcb_substageid_field]['include'] = $CI->session->stage_substageid_array[$data['stage']];
    		if(is_valid($data['exclude_substageid'])){
    			foreach($condition[$pcb_substageid_field]['include'] as $skey => $svalue){
    				if(in_array($svalue, $data['exclude_substageid'])){
    					unset($condition[$pcb_substageid_field]['include'][$skey]);
    				}
    			}

    		}
    		if(is_valid($data['substageid'])){
    			foreach($condition[$pcb_substageid_field]['include'] as $skey => $svalue){
    				if(!in_array($svalue, $data['substageid'])){
    					unset($condition[$pcb_substageid_field]['include'][$skey]);
    				}
    			}
    		}
    		$condition[$pcb_substageid_field]['include'] = array_values($condition[$pcb_substageid_field]['include']);
    	}
    	if(is_valid($data['main_role'])){
    		foreach($data['main_role'] as $skey => $svalue){
		 		$condition['main_role']['include'][] = $svalue;
    		}
    	}

    	if(is_valid($data['emp_num'])){
    		foreach($data['emp_num'] as $skey => $svalue){
		 		$condition['emp_num']['include'][] = $svalue;
    		}
    	}
    	if(is_valid($data['assigned_by'])){
    		foreach($data['assigned_by'] as $skey => $svalue){
		 		$condition['assigned_by']['include'][] = $svalue;
    		}
    	}

    	if(is_valid($data['requester'])){
    		foreach($data['requester'] as $skey => $svalue){
		 		$condition['requester']['include'][] = $svalue;
    		}
    	}

    	if(is_valid($data['signal_integrity_queue'])){
	 		$condition['signal_integrity_queue'] = 1;
    	}
    	
    	if(is_valid($data['functional_simulation_queue'])){
	 		$condition['functional_simulation_queue'] = 1;
    	}
	}
	return $condition;
}


?>