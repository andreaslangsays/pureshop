<?php
if(isset($_GET['WDg4Rtdaa7344$zttH66etffYSea623']))
{
	$message = "Jawoll!<br>";
}
else
{
	$message = "";
}
echo "try ... \n";
	require('includes/ajax_top.php');
	include_once("includes/classes/Customer.php");
//	$customers = array(50710, 50710);
//	$customers = array();
	$errors = array();
	// Interessenten die noch kein passwort gesetzt haben und noch kein code haben 
	$customersQ = push_db_query("SELECT c.customers_id FROM customers c WHERE c.customer_checkout_enabled = 1 AND c.customers_password = '' AND c.customers_id NOT IN (SELECT cp.customers_id FROM customers_change_password_codes cp);");
	$registered_accounts_num = 0;
	
//	echo 'Kunden - Links versenden: <br /><br />';
	
	while ($customerId = push_db_fetch_array($customersQ)) 
	{
	//	echo ;
	//		$customer = new Customer($customerId['customers_id']);
	//		echo $customer->customers_firstname . " - " . $customer->customers_lastname . "<br>";
	/*	}
		echo "B"; 
		foreach($customers as $cid)	{
		*/
		$customer = new Customer($customerId['customers_id']);

	//		$customer = new Customer($cid);
		$url = $customer->resetPassword(true, true);
		//echo "BLA";
		$registered_accounts_num++;
	/*		if ($url) {
			if ($customer->customers_gender == "H" || $customer->customers_gender == "h") {
				echo "Herr "; 
				$message .= "Herr ";
			} else if ($customer->customers_gender == "F" || $customer->customers_gender == "f") { 
				echo "Frau ";
				$message .= "Frau ";
			}
**/
			echo $customer->customers_firstname . " " . $customer->customers_lastname . "<br />";
			echo $customer->customers_email_address . "<br />";
			echo $url . "<br /><br />";
			$message .= $customer->customers_firstname . " " . $customer->customers_lastname . "\n";
			$message .= $customer->customers_email_address . "\n";
			$message .= $url . "\n\n";
		
	}
	
	if (push_not_null($message)) {
		$to2 = "andreas.lang@if-bi.com";
		$to = "kontakt@if-bi.com";
		$from = "kontakt@if-bi.com";
        $fromName = "kontakt@if-bi.com";
        $subject = "push customer registration codes";
        $head = sprintf("From: %s <%s>\n", $fromName, $from);
        $head .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
		$message.="<br><br>" . (implode(')...(',$_GET));
        //mail($to, $subject, $message, $head);
		mail($to2, $subject, $message, $head);
	}
	

	$logtxt = "Verfuegbarkeit " . date('d.m. Y H:i.s') . "\n"; 
	$q=push_db_fetch_array(push_db_query("SELECT count(products_id) as anz FROM products WHERE  products_status=1 AND products_quantity = 0 and products_drop_shipment = 0;"));
	$tanz=push_db_fetch_array(push_db_query("SELECT count(products_id) as tanz FROM products WHERE products_status=1;"));
	$prz=round( floatval(100/$tanz['tanz']) * ($tanz['tanz']-$q['anz'])*10) / 10;
	$logtxt .= $prz . '  %' . "\n\n";
	$h=fopen("admin/warelog.txt","a");
	//  var_dump($h);
	$logtxt .= file_get_contents("admin/warelog.txt");
	//fwrite($h,$logtxt);
	file_put_contents("admin/warelog.txt",$logtxt);
	fclose($h);
	echo "done";

?>