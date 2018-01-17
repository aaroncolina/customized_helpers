<?php

    function get_system($request_data){
        $result = ((strpos($request_data['layout_design_location'], CON_DALLAS) !== false)? 1 : 0);
        return $result;
    }

    /* Gets the Org chart base on the given Emp_num*/
    function get_organization_chart($emp_num){
		$CI =& get_instance();
        $result = array();
        $temp_data = array();
        $result[] = $CI->session->employees[$emp_num];

        $manager = $CI->session->employees[$emp_num]['manager'];
        while($manager!=''){
            $temp_data = $CI->session->employees[$manager];
            $result[] = $temp_data;
            $manager = $temp_data['manager'];
        }
        return $result;
    }

    function get_organization_chart_to_string($emp_num){
		$CI =& get_instance();
        $result = '';
        $count = 0;
        $temp_data = get_organization_chart($emp_num);
        foreach($temp_data as $key => $value){
            $count++;
            $result .= $value['fullname'];
            if(count($temp_data)>$count){
                $result .= ' > ';
            }
        }
        return $result;
    }

    
    function generate_unique_string($string = ''){
        return hash_hmac('sha256', time(), $string);
    }
?>