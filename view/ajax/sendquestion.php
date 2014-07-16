<?php
//Hier weiter
chdir('../../../');
include('includes/ajax_top.php');
$filename=DIR_WS_MODULES . 'captcha/' .session_id();
$h=fopen($filename, 'r');
$text = fgets($h);
fclose($h);
if( (intval($_POST['productid']) > 0 ) && ($_POST['captcha_code'] == $text || $customer->login))
{
	if(strlen($text)>1)
	{
		unlink($filename);
	}
	$product->load_product($_POST['productid']);
	
	$txt  = "FRAGE ZUM PRODUKT: \n ". $product->products_name . " ArtNr. " . $product->products_model . "\n\n";
	$txt .= "Kundendetails: \n";
	$txt .= "Name: " . $_POST['name'] . "\n";
	$txt .= "KdNr(falls vorhanden): " . $_POST['customer_id'] . "\n";
	$txt .= "Firma: " . $_POST['company'] . "\n";
	$txt .= "E-Mail: " . $_POST['email'] . "\n\n\n";
	$txt .= "Frage: \n" . $_POST['question'] . "\n\n";
	if($_POST['date'] <> "TT.MM.JJJJ")
	{
		$txt .= "Rückruf erbeten: \nRückrufdatum: " . $_POST['date'] . "\n";
		$txt .= "Zeitrahmen: " . $_POST['timeslot'] . "\n";
		$txt .= "Telefonnummer: " . $_POST['phone'] . "\n";
	}
	mail('orders@if-bi.com',"Frage zu " . utf8_decode($product->products_name), utf8_decode($txt));
	mail('andreas.lang@if-bi.com',"Frage zu " . utf8_decode($product->products_name), utf8_decode($txt));
	echo "true";
}
else
{
	echo 'false';
}
?>