<?php
/* 
 * Functions 
 */

function log_client()
{
	return true;
}

function decode_query($q)
{
	return $q;	
}


function s_($input)
{
	return mysql_real_escape_string($input);	
}
?>