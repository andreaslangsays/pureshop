<?php
/**
 *	übersetzer für URLS
 */
require('includes/ajax_top.php');
require_once 'includes/classes/Mobile_Detect.php';
include('includes/classes/ip2locationlite.class.php');

/*
include(DIR_WS_CLASSES . 'ip2locationlite.class.php');
 
//Load the class
$ipLite = new ip2location_lite;
$ipLite->setKey('38888a1ce8237dcac1f5f1e1047357b322442933593557a9b2bd3cf084458ce0');

//Get errors and locations
$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
$errors = $ipLite->getError();
 
//Getting the result
echo "<p>\n";
echo "<strong>First result</strong><br />\n";
if (!empty($locations) && is_array($locations)) {
  foreach ($locations as $field => $val) {
    echo $field . ' : ' . $val . "<br />\n";
  }
}
echo "</p>\n";
 
//Show errors
echo "<p>\n";
echo "<strong>Dump of all errors</strong><br />\n";
if (!empty($errors) && is_array($errors)) {
  foreach ($errors as $error) {
    echo var_dump($error) . "<br /><br />\n";
  }
} else {
  echo "No errors" . "<br />\n";
}
echo "</p>\n";
*/


function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
function log_url()
{
	global $name;
	$ip = get_client_ip();
	$brow= new Browser;
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	
	if($res=push_db_fetch_array(push_db_query("SELECT * FROM url_logger WHERE ip ='" . $ip . "' AND date >= CURDATE() AND NOT countryCode='' ")))
	{
				$locations=array('countryCode'=>$res['countryCode'],'countryName'=>$res['countryName'], 'regionName'=>$res['regionName'],'cityName'=>$res['cityName']);		
	}
	else
	{
		$ipLite = new ip2location_lite;
		//Get errors and locations
		$locations = $ipLite->getCity($ip);
		if(empty($locations))
		{
			$locations=array('countryCode'=>'unbekannt','countryName'=>'unbekannt', 'regionName'=>'unbekannt','cityName'=>'unbekannt');	
		}
	}
		push_db_query("INSERT	INTO url_logger 
								SET 
									url = '" . $name . "', 
									date = NOW(), 
									ip='" . $ip . "',
									session_id='" . session_id() . "', 
									browser='" . $brow->getBrowser() . "', 
									version='" . $brow->getVersion() . "', 
									platform='" . $brow->getPlatform() . "', 
									device='" . $deviceType . "',
									countryCode='" . $locations['countryCode'] . "',
									countryName='" . $locations['countryName'] . "',
									regionName='" . $locations['regionName'] . "',
									cityName='" . $locations['cityName'] . "'
									; ");
}

function get_url($link)
{
	global $name;

	if($t=push_db_fetch_array(push_db_query("SELECT url, name FROM url_shortener WHERE short='" . $link . "';")))
	{
		//$name=$t['name'] . " " . $link;
		$url = $t['url'];
		push_db_query("UPDATE url_shortener SET count = count+ 1 WHERE short='" . $link . "';");
		return $url;
	}
	else
	{
		return false;
	}
}
$name=$_GET['url_name'];

$lname= strtolower($name);
/*if($lname == 'davidrio')
{
	log_url();
	push_redirect(push_href_link('David-Rio,b,mid=10101.html'));
}
elseif($lname == 'torani')
{
	log_url();
	push_redirect(push_href_link('Torani,b,mid=10302.html'));
}
elseif($lname == 'bloemboom')
{
	log_url();
	push_redirect(push_href_link('Bloemboom,b,mid=10201.html'));
}
elseif($lname == 'schluerf')
{
	log_url();
	push_redirect(push_href_link('Schluerf,b,mid=10103.html'));
}
elseif($lname == 'hario')
{
	log_url();
	push_redirect(push_href_link('Hario,b,mid=10709.html'));
}
elseif($lname == 'anticatorroneria')
{
	log_url();
	push_redirect(push_href_link('Antica-Torroneria,b,mid=10507.html'));
}
elseif($lname == 'artisanbakery')
{
	log_url();
	push_redirect(push_href_link('Artisan-Bakery,b,mid=10503.html'));
}
elseif($lname == 'aeropress')
{
	log_url();
	push_redirect(push_href_link('Aeropress,b,mid=10710.html'));
}
elseif($lname == 'byronbay')
{
	log_url();
	push_redirect(push_href_link('Byron-Bay,b,mid=10501.html'));
}
elseif($lname == 'celestialseasonings')
{
	log_url();
	push_redirect(push_href_link('Celestial-Seasonings,b,mid=10105.html'));
}
elseif($lname == 'capedorato')
{
	log_url();
	push_redirect(push_href_link('Suchergebnisse-cape20dorato,c,categories_id=all&keywords=cape%20dorato.html'));
}
elseif($lname == 'capedoratosmoothies')
{
	log_url();
	push_redirect(push_href_link('Cape-Dorato-Smoothies,b,mid=1040101.html'));
}
elseif($lname == 'capedoratosyrups')
{
	log_url();
	push_redirect(push_href_link('Cape-Dorato-Syrups,b,mid=10301.html'));
}
elseif($lname == 'capedoratofrappes')
{
	log_url();
	push_redirect(push_href_link('Cape-Dorato-Frappes,b,mid=1040102.html'));
}
elseif($lname == 'crips')
{
	log_url();
	push_redirect(push_href_link('Sweets-&-Snacks-Crips,c,cPath=105_10511.html'));
}
elseif($lname == 'devondale')
{
	log_url();
	push_redirect(push_href_link('Devonvale,b,mid=10506.html'));
}
elseif($lname == 'drinkmechai')
{
	log_url();
	push_redirect(push_href_link('Drink-Me-Chai,b,mid=10102.html'));
}
elseif($lname == 'greendream')
{
	log_url();
	push_redirect(push_href_link('Green-Dream,b,mid=10509.html'));
}
elseif($lname == 'micheletaugustin' || $lname == 'michel-et-augustin')
{
	log_url();
	push_redirect(push_href_link('Michel-et-Augustin,b,mid=10505.html'));
}
elseif($lname == 'revolution' || $name == 'revolutiontea')
{
	log_url();
	push_redirect(push_href_link('Revolution-Tea,b,mid=10104.html'));
}
elseif($lname == 'thefinecookie') // http://www.if-bi.com/shop/
{
	log_url();
	push_redirect(push_href_link('The-Fine-Cookie,b,mid=10502.html'));
}
elseif($lname == 'pimpmycup')
{
	log_url();
	push_redirect(push_href_link('pimp_my_cup.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'sweets-snacks')
{
	log_url();
	push_redirect(push_href_link('Sweets-&-Snacks,c,cPath=105.html'));
}
elseif($lname == 'cups-packaging')
{
	log_url();
	push_redirect(push_href_link('Cups-&-Packaging,c,cPath=106.html'));
}
elseif($lname == 'sweetssnacks')
{
	log_url();
	push_redirect(push_href_link('Sweets-&-Snacks,c,cPath=105.html'));
}
elseif($lname == 'cupspackaging')
{
	log_url();
	push_redirect(push_href_link('Cups-&-Packaging,c,cPath=106.html'));
}
elseif($lname == 'syrups-sauces')
{
	log_url();
	push_redirect(push_href_link('Syrups-&-Sauces,c,cPath=103.html'));
}
elseif($lname == 'syrupssauces')
{
	log_url();
	push_redirect(push_href_link('Syrups-&-Sauces,c,cPath=103.html'));
}
elseif($lname == 'ice-cold')
{
	log_url();
	push_redirect(push_href_link('Ice-Cold,c,cPath=104.html'));
}
elseif($lname == 'icecold')
{
	log_url();
	push_redirect(push_href_link('Ice-Cold,c,cPath=104.html'));
}
elseif($lname == 'toraniicecold')
{
	log_url();
	push_redirect(push_href_link('Ice-Cold-Torani-Ice-Cold,c,cPath=104_10402.html'));
}
elseif($lname == 'frozenyogurt')
{
	log_url();
	push_redirect(push_href_link('Ice-Cold-Frozen-Yogurt,c,cPath=104_10404.html'));
}
elseif($lname == 'byronbay')
{
	log_url();
	push_redirect(push_href_link('Sweets-&-Snacks-Byron-Bay,c,cPath=105_10501.html'));
}
elseif($lname == 'anticatorroneria')
{
	log_url();
	push_redirect(push_href_link('Sweets-&-Snacks-Antica-Torroneria,c,cPath=105_10507.html'));
}
elseif($lname == 'coffeekids')
{
	log_url();
	push_redirect(push_href_link('Cups-&-Packaging-Coffee-Kids,c,cPath=106_10606.html'));
}
elseif($lname == 'abittoeat')
{
	log_url();
	push_redirect(push_href_link('Sweets-&-Snacks,c,cPath=105.html'));
}
elseif($lname == 'chocup' || $lname == 'chocups')
{
	log_url();
	push_redirect(push_href_link('Sweets-&-Snacks,c,cPath=105.html'));
}
elseif($lname == 'leone')
{
	log_url();
	push_redirect(push_href_link('Leone,b,mid=10508.html'));
}
elseif($lname == 'mahlkoenig')
{
	log_url();
	push_redirect(push_href_link('Mahlkoenig,b,mid=10903.html'));
}
elseif($lname == 'nosch')
{
	log_url();
	push_redirect(push_href_link('Nosch,b,mid=1090203.html'));
}
elseif($lname == 'santos')
{
	log_url();
	push_redirect(push_href_link('Santos,b,mid=1090401.html'));
}
elseif($lname == 'vitamix')
{
	log_url();
	push_redirect(push_href_link('Vitamix,b,mid=1090201.html'));
}
elseif($lname == 'waring')
{
	log_url();
	push_redirect(push_href_link('Waring,b,mid=1090202.html'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'agb')
{
	log_url();
	push_redirect(push_href_link('agb.php'));
}
elseif($lname == 'downloads')
{
	log_url();
	push_redirect(push_href_link('downloadcenter.php'));
}*/
if($url = get_url($lname))
{
	log_url();
	push_redirect($url);	
}
//I Intend to redirect different if link appears to come from root directory...
$tr=push_db_fetch_array(push_db_query("SELECT * FROM url_rewriting WHERE url_name='" . mysql_escape_string($name) . "'"));
$page=$tr['url_type'];
$pid=$tr['url_type_id'];
if($page=='pd')
{
	log_url();
	push_redirect(push_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$pid ));
}
elseif($page=='c')
{
	log_url();
	push_redirect(push_href_link(FILENAME_DEFAULT , 'cPath=' . $pid));
}
else
{
	push_redirect(push_href_link(FILENAME_DEFAULT));	
}
?>