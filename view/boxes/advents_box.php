<?php
function get_advent(){
	$jahr = date("Y");
	$a1 = strtotime("+1 sunday",mktime(0,0,0,11,26,$jahr));
	$a2 = strtotime("+2 sunday",mktime(0,0,0,11,26,$jahr));
	$a3 = strtotime("+3 sunday",mktime(0,0,0,11,26,$jahr));
	$a4 = strtotime("+4 sunday",mktime(0,0,0,11,26,$jahr));
	$end =mktime(0,0,0,12,25,$jahr);
	$today = time();
	if($today > $end)
	{
		return false;
	}
	if($today > $a4 && $today < $end)
	{
		return 4;
	}
	elseif($today > $a3)
	{
		return 3;
	}
	elseif($today > $a2)
	{
		return 2;
	}
	elseif($today > $a1)
	{
		return 1;
	}
}
?>