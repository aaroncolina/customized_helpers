<?php

function get_rfq_upload_path($rid, $path_type=0){
    $upload_path = 'assets/'.DIR_PROJECT_RFQ_PDF.$rid.'/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, TRUE);
        copy(str_replace('\\', '/', FCPATH).'assets/index.html', str_replace('\\', '/', FCPATH).$upload_path.'index.html');
    }
    switch($path_type){
    	# Exact Directory
    	case 1:
    		$upload_path = str_replace('\\', '/', FCPATH).$upload_path;
    		break;
		# Exact URL
		case 2:
    		$upload_path = base_url($upload_path);
			break;

    }
    return $upload_path;
}


?>