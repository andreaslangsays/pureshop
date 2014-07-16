<?php
chdir('../../');
include('includes/ajax_top.php');
$filename=session_id();
chdir('images/captcha/');

$fn= DIR_FS_CATALOG. DIR_WS_MODULES . 'captcha/'.$filename;
function randomString($len) {
	function make_seed(){
		list($usec , $sec) = explode (' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
	}
	srand(make_seed());
	//Der String $possible enthält alle Zeichen, die verwendet werden sollen 
	$possible="ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz123456789";
	$possible="ABCDEFGHJKLMNPRSTUVWXYZ123456789";
	$str="";
	while(strlen($str)<$len) {
		$str.=substr($possible,(rand()%(strlen($possible))),1);
	}
	return($str);
}

$text = randomString(5);  //Die Zahl bestimmt die Anzahl stellen
if(file_exists($fn)) unlink($fn);
$ff=fopen($fn,'w+');
if($ff){
fwrite($ff,$text);
fclose($ff);
}else{
	exit;
}

header('Content-type: image/png');
$img = ImageCreateFromPNG('captcha.PNG'); //Backgroundimage
$color = ImageColorAllocate($img, 0, 0, 0); //Farbe
// Set the enviroment variable for GD
putenv('GDFONTPATH=' . realpath('.'));
$ttf = "XFILES.TTF"; //Schriftart
$ttfsize = 22; //Schriftgrösse 
$angle = rand(0,5);
$t_x = rand(5,30);
$t_y = 35;
imagettftext($img, $ttfsize, $angle, $t_x, $t_y, $color, $ttf, $text);
imagepng($img);
imagedestroy($img);
?>
