<?php
//@Hier weiter
//testen ob captcha stimmt!

chdir('../../../');
include('includes/ajax_top.php');
$filename=DIR_WS_MODULES . 'captcha/' .session_id();
$h=fopen($filename, 'r');
$text = fgets($h);
if($_POST['captcha_code'] == $text)
{
	echo 'true';
}
else
{
	echo 'false';
}
?>