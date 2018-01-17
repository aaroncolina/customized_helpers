<?php

/* Formats the Directory path from backslash to forward slash (For Uniformity of URLs and DIR)*/
function dir_path($path){
	return str_replace('\\', '/', FCPATH).$path;
}

?>