<?php

function log_instance($log_type, $log_info){
	$buffer_directory = dir_path('application/logs/custom_log_'.date('Y-m-d').'.txt');

	if(is_array($log_info)){
		$log_info = json_encode($log_info, JSON_PRETTY_PRINT);
	}

	$txt = '['.date('Y-m-d H:i:s').'] - ['.$log_type.'] '. $log_info;
	file_put_contents($buffer_directory, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
}
?>