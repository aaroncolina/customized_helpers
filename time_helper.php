<?php 
	# Returns the current microtime (Used for Tracking Elapsed time)
	function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}
?>