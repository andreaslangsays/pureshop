<?php
/* Ä
*/
	require('includes/ajax_top.php');
	//error_reporting(E_ALL);
	include_once("includes/classes/Customer.php");

	// $customers = array(50710, 50710);
	$errors = array();
	$message = "";

	// Interessenten die noch kein passwort gesetzt haben und noch kein code haben 
	$customersQ = push_db_query("SELECT c.customers_id FROM customers c WHERE c.customer_checkout_enabled = 0 AND c.customers_password = '' AND c.customers_id NOT IN (SELECT cp.customers_id FROM customers_change_password_codes cp);");
	
	$registered_accounts_num = 0;
	
	echo 'Neue Interessenten: <br /><br /><br />';
	
	while ($customerId = push_db_fetch_array($customersQ)) {
		
		$customer = new Customer($customerId['customers_id']);
		$url = $customer->resetPassword(true, false);
		
		if ($url) {
			$registered_accounts_num++;
			if ($customer->customers_gender == "H" || $customer->customers_gender == "h") {
				echo "Herr "; 
				$message .= "Herr ";
			} else if ($customer->customers_gender == "F" || $customer->customers_gender == "f") { 
				echo "Frau ";
				$message .= "Frau ";
			}
			echo $customer->customers_firstname . " " . $customer->customers_lastname . "<br />";
			echo $customer->customers_email_address . "<br />";
			echo $url . "<br /><br />";
			
			$message .= $customer->customers_firstname . " " . $customer->customers_lastname . "\n";
			$message .= $customer->customers_email_address . "\n";
			$message .= $url . "\n\n";
			
		} else {
			array_push($errors, $customerId);
		}
	}
	
	echo '<br />Insgesamt Kunden: ' . $registered_accounts_num . "<br />"; 
	if (empty($errors)) {
		echo "No errors.";
	} else {		
		echo "Couldn't register customers: <br />";
		foreach ($errors as $error) {
			echo $error . ', ';
		}
	}
	
	
	if (!empty($message)) {
		$to = "kontakt@if-bi.com";
		$from = "kontakt@if-bi.com";
        $fromName = "kontakt@if-bi.com";
        $subject = "push customer registration codes";
        $head = sprintf("From: %s <%s>\n", $fromName, $from);
        $head .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
		
        mail($to, $subject, $message, $head);
	}
?>