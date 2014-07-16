<?php

class Customer {
	
	const ERROR_COOKIES_DISABLED = "cookies disabled";
	
	var $customers_id, $customers_gender, $customers_firstname, $customers_lastname, $customers_dob, $customers_email_address, $customers_default_address_id, $customers_bill_address_id, $customers_shipping_address_id, $customers_telephone, $customers_fax, $customers_password, $customers_newsletter, $customers_banktransfer_owner, $customers_banktransfer_number, $customers_banktransfer_bankname, $customers_banktransfer_blz, $customers_invoice, $customers_notes, $customers_group_id, $customers_group_ra, $customers_payment_allowed, $customers_shipment_allowed, $customer_discount_table, $customer_discount_combines_all, $customer_discount_add_to_customers_product,  $customer_discount_add_to_overall_discount, $customer_only_ve, $customer_online_rabatt, $customer_image_usage, $customer_checkout_enabled, $customer_credit_amount, $customer_paper_bill, $customer_use_pallet, $customer_show_tax, $customer_tax_exempt, $customers_comment, $customers_gutschein, $selectline_customers_id, $kunde;
	
	
	public function __construct($customerId) {
		
		$customerQuery = push_db_query("	SELECT 
											customers_gender, 
											customers_firstname, 
											customers_lastname, 
											customers_dob, 
											customers_email_address, 
											customers_default_address_id, 
											customers_bill_address_id, 
											customers_shipping_address_id, 
											customers_telephone, 
											customers_fax, 
											customers_password, 
											customers_newsletter, 
											customers_banktransfer_owner, 
											customers_banktransfer_number, 
											customers_banktransfer_bankname, 
											customers_banktransfer_blz, 
											customers_invoice, 
											customers_notes, 
											customers_group_id, 
											customers_group_ra, 
											customers_payment_allowed, 
											customers_shipment_allowed, 
											customer_discount_table, 
											customer_discount_combines_all, 
											customer_discount_add_to_customers_product, 
											customer_discount_add_to_overall_discount, 
											customer_only_ve, 
											customer_online_rabatt, 
											customer_image_usage, 
											customer_checkout_enabled, 
											customer_credit_amount, 
											customer_paper_bill, 
											customer_use_pallet,
											customer_show_tax, 
											customer_tax_exempt, 
											customers_comment, 
											customers_gutschein,
											customers_cart,
											selectline_customers_id
										FROM " . TABLE_CUSTOMERS . " 
										WHERE customers_id = '" . push_db_input($customerId) . "'");	
										
		if ($customer = push_db_fetch_array($customerQuery)) {
			$this->customers_id 								= $customerId;
			$this->customers_gender 							= $customer['customers_gender'];
			$this->customers_firstname 							= $customer['customers_firstname'];
			$this->customers_lastname 							= $customer['customers_lastname'];
			$this->customers_dob 								= $customer['customers_dob'];
			$this->customers_email_address 						= $customer['customers_email_address'];
			$this->customers_default_address_id 				= $customer['customers_default_address_id'];
			$this->customers_bill_address_id 					= $customer['customers_bill_address_id'];
			$this->customers_shipping_address_id 				= $customer['customers_shipping_address_id'];
			$this->customers_telephone 							= $customer['customers_telephone'];
			$this->customers_fax 								= $customer['customers_fax'];
			$this->customers_password 							= $customer['customers_password'];
			$this->customers_newsletter 						= $customer['customers_newsletter'];
			$this->customers_banktransfer_owner 				= $customer['customers_banktransfer_owner'];
			$this->customers_banktransfer_number 				= $customer['customers_banktransfer_number'];
			$this->customers_banktransfer_bankname 				= $customer['customers_banktransfer_bankname'];
			$this->customers_banktransfer_blz 					= $customer['customers_banktransfer_blz'];
			$this->customers_invoice 							= $customer['customers_invoice'];
			$this->customers_notes 								= $customer['customers_notes'];
			$this->customers_group_id 							= $customer['customers_group_id'];
			$this->customers_group_ra 							= $customer['customers_group_ra'];
			$this->customers_payment_allowed 					= $customer['customers_payment_allowed'];
			$this->customers_shipment_allowed 					= $customer['customers_shipment_allowed'];
			$this->customer_discount_table 						= $customer['customer_discount_table'];
			$this->customer_discount_combines_all 				= $customer['customer_discount_combines_all'];
			$this->customer_discount_add_to_customers_product 	= $customer['customer_discount_add_to_customers_product'];
			$this->customer_discount_add_to_overall_discount 	= $customer['customer_discount_add_to_overall_discount'];
			$this->customer_only_ve 							= $customer['customer_only_ve'];
			$this->customer_online_rabatt 						= $customer['customer_online_rabatt'];
			$this->customer_image_usage 						= $customer['customer_image_usage'];
			$this->customer_checkout_enabled 					= $customer['customer_checkout_enabled'];
			$this->customer_credit_amount 						= $customer['customer_credit_amount'];
			$this->customer_paper_bill 							= $customer['customer_paper_bill'];
			$this->customer_use_pallet 							= $customer['customer_use_pallet'];
			$this->customer_show_tax 							= $customer['customer_show_tax'];
			$this->customer_tax_exempt 							= $customer['customer_tax_exempt'];
			$this->customers_comment 							= $customer['customers_comment'];
			$this->customers_gutschein 							= $customer['customers_gutschein'];
			$this->customers_cart 								= $customer['customers_cart'];
			$this->selectline_customers_id 						= $customer['selectline_customers_id'];
			$this->login										= true;
			$this->kunde										= (($customer['customer_checkout_enabled']  == 1)?true:false);
			return true;
		} else {
			$this->login =false;
			return false;
		}
	}
	
	public function logIn($email, $password, $redirectToLoginSiteOnError = true) {
		
		global $customer_default_address_id, $customer_bill_address_id, $customer_shipping_address_id, $customer_first_name, $customer_last_name, $session_started, $sppc_customer_group_tax_exempt, $sppc_customer_group_show_tax, $sppc_customer_group_id, $customer_discount_table, $customer_discount_combines_all, $customer_discount_add_to_customers_product, $customer_discount_add_to_overall_discount, $customer_country_id, $customer_zone_id, $cart, $wishList, $error_stack;
		
		$error = false;
		
		if ($session_started == false) {
			if ($redirectToLoginSiteOnError) {
				push_redirect(push_href_link(FILENAME_LOGIN));
			}
			$error = true;
   			$error_stack->add(Customer::ERROR_COOKIES_DISABLED);
		}
	
		$check_customer_query = push_db_query("	SELECT 
													customers_id, 
													customers_firstname, 
													customers_lastname, 
													customers_password, 
													customers_email_address, 
													customers_default_address_id, 
													customers_bill_address_id, 
													customers_shipping_address_id, 
													customer_discount_table, 
													customer_discount_combines_all, 
													customer_discount_add_to_customers_product, 
													customer_discount_add_to_overall_discount, 
													customer_only_ve,
													customer_online_rabatt,
													customer_image_usage,
													customer_checkout_enabled,
													customer_credit_amount,
													customer_paper_bill,
													customer_show_tax, 
													customer_tax_exempt 
												FROM " . TABLE_CUSTOMERS . " 
												WHERE customers_email_address = '" . push_db_input($email) . "'");
		if (!push_db_num_rows($check_customer_query)) 
		{
			if ($redirectToLoginSiteOnError)
			{
				push_redirect(push_href_link(FILENAME_LOGIN));
			}
			$error = true;
		} else {
			$check_customer = push_db_fetch_array($check_customer_query);
			
			// Check if password is set an if is correct
			if (empty($check_customer['customers_password']) || !push_validate_password($password, $check_customer['customers_password'])) {
				if ($redirectToLoginSiteOnError) {
					push_redirect(push_href_link(FILENAME_LOGIN));
				}
				$error = true;
			} else {
				if (SESSION_RECREATE == 'True') {
					push_session_recreate();
				}
			
				// register customer in session
		/*		if (!push_session_is_registered('customer')) {
					push_session_register('customer');
					$customer = new Customer($check_customer['customers_id']);
				}
		*/
	
				$check_country_query = push_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
				$check_country = push_db_fetch_array($check_country_query);
		
				$_SESSION['customer_id'] = $check_customer['customers_id'];
				
				$customer_default_address_id = $check_customer['customers_default_address_id'];
				if (!is_null($check_customer['customers_bill_address_id'])) {
					$customer_bill_address_id = $check_customer['customers_bill_address_id'];
				} else {
					$customer_bill_address_id = $check_customer['customers_default_address_id'];
				}
				if (!is_null($check_customer['customers_shipping_address_id'])) {
					$customer_shipping_address_id = $check_customer['customers_shipping_address_id'];
				} else {
					$customer_shipping_address_id = $check_customer['customers_default_address_id'];
				}
				$this->login=true;	
				$customer_first_name = $check_customer['customers_firstname'];
				$customer_last_name = $check_customer['customers_lastname'];
		
				// BOF Separate Pricing per Customer
				$sppc_customer_group_id = 0;
				$sppc_customer_group_show_tax = (int)$check_customer['customers_show_tax'];
				$sppc_customer_group_tax_exempt = (int)$check_customer['customers_tax_exempt'];
				
				$customer_discount_table = $check_customer['customer_discount_table'];
				$customer_discount_combines_all = (int)$check_customer['customer_discount_combines_all'];
				$customer_discount_add_to_customers_product = (int)$check_customer['customer_discount_add_to_customers_product'];
				$customer_discount_add_to_overall_discount= $check_customer['customer_discount_add_to_overall_discount'];
				// EOF Separate Pricing per Customer
				
				$_SESSION['customer_only_ve'] = $check_customer['customer_only_ve'];
				$_SESSION['customer_online_rabatt'] = $check_customer['customer_online_rabatt'];
				$_SESSION['customer_image_usage'] = $check_customer['customer_image_usage'];
				$_SESSION['customer_checkout_enabled'] = $check_customer['customer_checkout_enabled'];
				$_SESSION['customer_credit_amount'] = $check_customer['customer_credit_amount'];
				$_SESSION['customer_paper_bill'] = $check_customer['customer_paper_bill'];
				$customer_country_id = $check_country['entry_country_id'];
				$customer_zone_id = $check_country['entry_zone_id'];
				push_session_register('customer_id');
				push_session_register('customer_default_address_id');
				push_session_register('customer_bill_address_id');
				push_session_register('customer_shipping_address_id');
				push_session_register('customer_first_name');
				push_session_register('customer_last_name');
		
				// BOF Separate Pricing per Customer
				push_session_register('sppc_customer_group_id');
				push_session_register('customer_discount_table');
				push_session_register('customer_discount_combines_all');
				push_session_register('customer_discount_add_to_customers_product');
				push_session_register('customer_discount_add_to_overall_discount');
				if(!push_session_is_registered('sppc_customer_group_show_tax')){
					push_session_register('sppc_customer_group_show_tax');
				}
				push_session_register('sppc_customer_group_tax_exempt');
				// EOF Separate Pricing per Customer
		
				push_session_register('customer_country_id');
				push_session_register('customer_zone_id');
		
				push_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
				
				// restore cart contents
				$cart->restore_contents();
				// re-write cart cookie after member cart is restored from database and merged with visitor cart
//				include('includes/write_cart_to_cookie.php');
				// Begin Change: Cart Cookie V1.3
				// restore wishlist to sesssion
				if(is_object($wishList))
				{
					$wishList->restore_wishlist();		
				}
				else
				{
					$wishList = new wishlist; 
					unset($wishList->wishID[0]);
					$wishList->restore_wishlist();
				}
//				if (sizeof($navigation->snapshot) > 0) {
//					$origin_href = push_href_link($navigation->snapshot['page'], push_array_to_string($navigation->snapshot['get'], array(push_session_name())), $navigation->snapshot['mode']);
//					$navigation->clear_snapshot();
//					push_redirect($origin_href);
//				} else {
//					if (isset($_POST['redirectto']) && ($_POST['redirectto']<>'') ){
//						push_redirect(push_href_link($_POST['redirectto']));
//					} else {
//						push_redirect(push_href_link(FILENAME_DEFAULT));
//					}
//				}
			}			
		}
		return !$error;
	}
	
	public function findCustomerIdByChangePasswordCode($code) {
		
		$registrationQ = push_db_query("SELECT customers_id FROM customers_change_password_codes WHERE change_password_code = '$code';");
		$registration = push_db_fetch_array($registrationQ);
		
		if ($registration) {		
			return $registration['customers_id'];
		} else { 
			return false;
		}
	}
	
	public function findCustomerIdByEmail($email) {
		
		$customerQ = push_db_query("SELECT customers_id FROM " . TABLE_CUSTOMERS . " WHERE customers_email_address = '$email'");
		$customer = push_db_fetch_array($customerQ);
		
		if ($customer) {		
			return $customer['customers_id'];
		} else { 
			return false;
		}
	}
	
	// $customerRegistration: 
	// 		> true: 	redirect customer to default page
	//		> false: 	redirect customer to Kundenkonto
	// after he has set the new password
	public function resetPassword($customerRegistration = false, $sendConfirmationEmail = false) {
	
		srand();
		$code = md5(uniqid(mt_rand(), true));
	
		$result = push_db_query("INSERT INTO customers_change_password_codes VALUES('$code', $this->customers_id);");
		
		if ($result !== false) {
			$confirmUrl = 'http://www.if-bi.com/shop/password_reset.php?code=' . $code . ($customerRegistration ? "&r=true" : '');
			if ($sendConfirmationEmail) {
				if ($customerRegistration) {
					$this->sendConfirmationEmail($confirmUrl);
				} else {
					$this->sendConfirmationEmailPswReset($confirmUrl);
				}
			}
			return $confirmUrl;
		} else {
			return false;
		}
	}
	
	public function setPassword($password, $changePasswordCode = false) {
		
		$result = push_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_password = '" . push_encrypt_password($password) . "' WHERE customers_id = '" . $this->customers_id . "'");
		if ($result && $changePasswordCode) {
			push_db_query("DELETE FROM customers_change_password_codes WHERE change_password_code = '$changePasswordCode'");
		}
		return $result;
	}
    
	private function sendConfirmationEmail($confirmUrl) {
	
		$from = "orders@if-bi.com";
        $fromName = "orders@if-bi.com";
        $subject = "push registration";
        $head = sprintf("From: %s <%s>\n", $fromName, $from);
        $head .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
        $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<META name=GENERATOR content="MSHTML 9.00.8112.16437">
</HEAD>
<BODY>
<Div style="width:620px">
	<DIV style="margin-bottom:20px; margin-top:20px">
        <IMG src="http://if-bi.com/shop/images/push/push-logo.png"><span>&nbsp;&nbsp;&nbsp;</span>
        <FONT color=#1179cb face="Myriad Pro, Arial, Helvetica, sans-serif">TASTE IT. LOVE IT. SHOP IT.</FONT>
    </DIV>
<!-- Start MSG-->
    <DIV style="border:solid #ccc 1px; overflow:hidden; padding:20px; font-size:13px; line-height:1.5">
      <DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Lieber push Kunde!</FONT></DIV>
      <DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
      <DIV>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Willkommen bei push! Eine Vielzahl toller Ideen und Produkte wartet nun auf Sie.</FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Ihre persönliche Kundennummer ist:<b> ' . $this->selectline_customers_id . '</b></FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">&nbsp;</FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Natürlich können Sie auch unseren Onlineshop nutzen. Sollten Sie sogar! Denn dort gibt es die aktuellsten Sonderangebote und zusätzliche Vorteile wie zum Beispiel eine niedrigere Versandkostenfreigrenze. </FONT></P>
   
<!--Start Box-->      
      <DIV style="border:solid 1px #CCC; background-color:#f5f5f5; padding:5px;margin:20px 0;"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Hier können Sie Ihr persönliches Passwort wählen:&nbsp;</FONT><a href="' . $confirmUrl .  '" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; color:#1179cb; text-decoration:none" target="_blank">' . $confirmUrl .  '</a></span></DIV>
    </DIV>
<!--End Box-->         

        <P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">In Vorfreude auf die Zusammenarbeit grüßt Sie</FONT></P>
      <DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
      <DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Ihr push Team</FONT></DIV>
<!--End MSG-->
</DIV> 
' .  EMAIL_FOOTER_OFFICIAL . '</DIV>
</BODY>
</HTML>';
//  push_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . ' ' . $insert_id , $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		
        push_mail($this->customers_firstname . ' ' . $this->customers_lastname, $this->customers_email_address, $subject, $message,  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
	
	private function sendConfirmationEmailPswReset($confirmUrl) {
	
		$from = "orders@if-bi.com";
        $fromName = "orders@if-bi.com";
        $subject = "push Passwortänderung";
        $head = sprintf("From: %s <%s>\n", $fromName, $from);
        $head .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
        $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<META name=GENERATOR content="MSHTML 9.00.8112.16437">
</HEAD>
<BODY>
<Div style="width:620px">
    <DIV style="margin-bottom:20px; margin-top:20px">
        <IMG src="http://www.if-bi.com/shop/images/push/push-logo.png"><span>&nbsp;&nbsp;&nbsp;</span>
        <FONT color=#1179cb face="Myriad Pro, Arial, Helvetica, sans-serif">TASTE IT. LOVE IT. SHOP IT.</FONT>
    </DIV>
<!-- Start MSG-->
    <DIV style="border:solid #ccc 1px; overflow:hidden; padding:20px; font-size:13px; line-height:1.5">
      <DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Sehr geehrte ' . $this->customers_firstname  . ' ' . $this->customers_lastname . '</FONT></DIV>
      <DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
      <DIV>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">um ein neues Passwort zu vergeben, klicken Sie bitte auf folgenden Link:</FONT></P>
   
<!--Start Box-->      
      <p style="font-size:13px; line-height:1.5; border:solid 1px #CCC; background-color:#f5f5f5; padding:5px"><a href="' . $confirmUrl . '" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; color:#1179cb; text-decoration:none" target="_blank">' . $confirmUrl . '</a></span></DIV>
    </p>
<!--End Box-->   

        <P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Aus Sicherheitsgründen wird dieser Link nach 24 Stunden deaktiviert.</FONT></P>
		<DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
		<P style="MARGIN: 0cm 0cm 0pt"><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Falls Sie den Link nicht per Klick öffnen können, kopieren Sie ihn bitte vollständig in die Adresszeile Ihres Browsers. </FONT></P>      
		<DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
      <DIV><FONT color=#333 face="Myriad Pro, Arial, Helvetica, sans-serif">Ihr push Team</FONT></DIV>
<!--End MSG-->      
      </div>
   
' . EMAIL_FOOTER_OFFICIAL . '

</DIV>
</BODY>
</HTML>
';
		
        push_mail($this->customers_firstname . ' ' . $this->customers_lastname, $this->customers_email_address, $subject, $message,  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
	
	
	public function get_address_by_id($aid)
	{
		$q=push_db_query("SELECT entry_gender, entry_company, entry_company_tax_id, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city,	entry_state, entry_country_id FROM address_book WHERE address_book_id= '" . $aid . "' AND customers_id= '" . $this->customers_id . "'");
		if($r = push_db_fetch_array($q))
		{
			$ret['company'] 			= $r['entry_company'];
			$ret['firstname'] 			= $r['entry_firstname'];
			$ret['lastname'] 			= $r['entry_lastname'];
			$ret['street_address']		= $r['entry_street_address'];
			$ret['suburb'] 				= '';//$r['entry_suburb'];
			$ret['postcode'] 			= $r['entry_postcode'];
			$ret['city'] 				= $r['entry_city'];
			$ret['state'] 				= $r['entry_state'];
			$ret['country_id'] 			= $r['entry_country_id'];
			$ret['id'] 					= $aid;
			return $ret;
		}
		else
		{
			return false;
		}
			
	}
	
	
	public function getShippingAddressesIds() {
		
		$addresses = array();
		
		$addressesIdsQ = push_db_query("SELECT address_book_id FROM address_book WHERE address_book_id NOT IN ('" . $this->customers_default_address_id . "', '" . $this->customers_bill_address_id . "') AND customers_id = '" . $this->customers_id . "'");
				
		while ($addressId = push_db_fetch_array($addressesIdsQ)) {
			array_push($addresses, $addressId['address_book_id']);
		}
		
		return $addresses;
	}
	
	public function setImageUsage($value) {
		
		$result = push_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customer_image_usage = '$value' WHERE customers_id = '" . $this->customers_id . "'");
		
		if ($result === false) {
			return false;
		} else {
			$this->customer_image_usage = $value;
			$this->sendImageUsageEmail();
			return true;
		}
	}	
	
	public function setUsePallet($value) {
		
		$result = push_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customer_use_pallet = '$value' WHERE customers_id = '" . $this->customers_id . "'");
		
		if ($result === false) {
			return false;
		} else {
			$this->customer_use_pallet = $value;
			$this->sendUsePalletEmail($value);
			return true;
		}
	}	
	
	private function sendImageUsageEmail() {
	
		$to = "orders@if-bi.com";
		// $to = "loominpn@gmail.com";
        $subject = "Kunde " . $this->selectline_customers_id . " hat Bildnutzungsvereinbarung akzeptiert";
        $head = sprintf("From: %s <%s>\n", $fromName, $from);
        $head .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
        $message = $this->customers_firstname . ' ' . $this->customers_lastname . ' (Kundennr. ' . $this->selectline_customers_id . ') hat Bildnutzungsvereinbarung akzeptiert. <br />
Bitte die Kundendaten in Selectline aktualisieren.';
		
        push_mail($to, $to, $subject, $message,  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
	
	private function sendUsePalletEmail($value) {
	
		$to = "orders@if-bi.com";
		// $to = "loominpn@gmail.com";
        $subject = "Kunde " . $this->selectline_customers_id . " hat Palettenversand " . ($value == 1 ? 'Akzeptiert' : 'Abgelehnt');
        $head = sprintf("From: %s <%s>\n", $fromName, $from);
        $head .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
        $message = $this->customers_firstname . ' ' . $this->customers_lastname . ' (Kundennr. ' . $this->selectline_customers_id . ') hat folgende Einstellungen geändert: <br /><br />
- ich nehme gelieferte Paletten entgegen: ' . ($value == 1 ? 'ja' : 'nein') . ' <br /><br />
Bitte die Kundendaten in Selectline aktualisieren.';
		
        push_mail($to, $to, $subject, $message,  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
}
?>
