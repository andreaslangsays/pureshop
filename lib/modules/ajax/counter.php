<?php
/**
 *
 *
 */
$weekday=date(w,time());
if(($weekday == 0)||($weekday == 5)||($weekday == 6))
{

	$targetdate = date('m/d/Y', strtotime("next monday")) . " 2:00 PM";
	//DisplayFormat = "%%D%%d %%H%%h:%%M%%m:%%S%%s";
	$customhourinterval = 72;
	if($weekday == 5 )
	{
		$lieferung =  time() < strtotime("today 14:00:00") ? "noch heute" : "am Montag";
	}
	else
	{
		$lieferung =   "am Montag";
	}
}
else
{
	$targetdate = "<?= date('m/d/Y', strtotime("tomorrow")) ?> 2:00 PM";
	$customhourinterval = 24;
	$lieferung = time() < strtotime("today 14:00:00") ? "noch heute" : "schon morgen";
}
echo '{ "datum": "' . $targetdate . '", "interval": "' . $customhourinterval . '", "delivery": "' . $lieferung . '"}';
?>