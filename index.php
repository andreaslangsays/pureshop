<?php
/*
 * PureShop
 * 2014
 */
require('lib/app.php');

//Auswertung der Query;
//Einbinden der entsprechenden View
//DISPATCHER
/*
 * Ziel: alle Funktionen und Klassen in /lib halten
 * die Darstellung in /view stattfinden lassen
 * den Index zum Weiterleiten sämtlicher Queries nutzen
 * die /boxes spucken HTML Schnipsel aus - Status noch unklar
 * /ajax funktioniert analog - aber mit Parametern €:])
 * so tun es auch andere Systeme
 * weiteres Ziel: KISS! Es soll einfach und schlank bleiben
 *
 */

if(DEBUG_ON)
{
	echo "<pre><code>---cookie---<br>";
	print_r($_COOKIE);
	echo "<br>---post---<br>";
	print_r($_POST);
	echo "<br>---get---<br>";
	print_r($_GET);
	echo "<br>---session---<br>";
	print_r($_SESSION);
	echo "</code></pre>";
}

$view=(isset($_GET['view']))? s_($_GET['view']):'default';
$action = (isset($_GET['action']))? s_($_GET['action']):'false';
echo " Anzeige: ".$view. "<br>";
echo " Aktion: ".$action. "<br>";
$lname= strtolower($view);
if($url = get_url($lname))
{
	log_url();
}
//I Intend to redirect different if link appears to come from root directory...
$tr=push_db_fetch_array(push_db_query("SELECT * FROM url_rewriting WHERE url_name='" . mysql_real_escape_string($name) . "'"));
$page=$tr['url_type'];
$pid=$tr['url_type_id'];
if($page=='pd')
{
	log_url();

}
elseif($page=='c')
{
	log_url();

}



require(DIR_WS_LIB . 'end.php'); /**/
?>
