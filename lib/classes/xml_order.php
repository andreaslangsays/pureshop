<?php
/**
 * XML-MAIL-GENERATOR
 * TO GENERATE XML-FILES BASED ON ORDERS ID
 * 2012 Bruesselser Kakaoroesterei / AL
 */

class xml_order{
	var $order_empty, $order_id, $orderstatus, $customer, $products, $payment, $paymentbank, $delivery, $billing, $status, $cc, $datum, $comments, $dhl, $currency, $total, $xml, $taxref;
	
	/*
	 * Constructor
	 */
	function xml_order($id){
		global $currencies, $p;
		$this->order_id = (int)$id;
		$this->order_empty=true;
		//get order details
		$oq=push_db_fetch_array(push_db_query("SELECT * FROM " . TABLE_ORDERS ." WHERE orders_id ='" . $this->order_id .  "' LIMIT 1;"));
		if($oq)
		{		
//GENERAL ORDER DATA
//SL customers_id:
			$SLCID =  push_db_fetch_array(push_db_query("SELECT selectline_customers_id, customers_email_address FROM customers WHERE selectline_customers_id = '" . $oq['customers_id'] . "' "));
			$this->customer['id'] = $SLCID['selectline_customers_id'];
			$this->customer['name'] = $oq['customers_name'];
			$this->customer['lastname'] = $oq['customers_lastname'];
			$this->customer['company'] = $oq['customers_company'];
			$this->customer['street'] = $oq['customers_street_address'];
			$this->customer['suburb'] = $oq['customers_suburb'];
			$this->customer['city'] = $oq['customers_city'];
			$this->customer['postcode'] = $oq['customers_postcode'];
			$this->customer['state'] = $oq['customers_state'];
			$this->customer['country'] = (($oq['customers_country']<>"")? $oq['customers_country'] : $oq['billing_country']);
			$this->customer['telephone'] = $oq['customers_telephone'];
			$this->customer['email_address'] = (($oq['customers_email_address']<>'')?$oq['customers_email_address']:$SLCID['customers_email_address']);
			$this->customer['address_format_id'] = substr($oq['customers_address_format_id'],0,-2);
			$this->delivery['name'] = $oq['delivery_name'];
			$this->delivery['company'] = $oq['delivery_company'];
			$this->delivery['street'] = $oq['delivery_street_address'];
			$this->delivery['suburb'] = $oq['delivery_suburb'];
			$this->delivery['city'] = $oq['delivery_city'];
			$this->delivery['postcode'] = $oq['delivery_postcode'];
			$this->delivery['state'] = $oq['delivery_state'];
			$this->delivery['country'] = $oq['delivery_country'];
			$this->delivery['address_format_id'] = substr($oq['delivery_address_format_id'],0,-2);
			$this->billing['name'] = $oq['billing_name'];
			$this->billing['company'] = $oq['billing_company'];
			$this->billing['street'] = $oq['billing_street_address'];
			$this->billing['suburb'] = $oq['billing_suburb'];
			$this->billing['city'] = $oq['billing_city'];
			$this->billing['postcode'] = $oq['billing_postcode'];
			$this->billing['state'] = $oq['billing_state'];
			$this->billing['country'] = $oq['billing_country'];
			$this->billing['address_format_id'] = substr($oq['billing_address_format_id'],0 ,-2);
			$this->payment['method'] = $oq['payment_method'];
			$this->payment['info'] = $oq['payment_info'];
			$this->cc['type'] = $oq['cc_type'];
			$this->cc['owner'] = $oq['cc_owner'];
			$this->cc['number'] = $oq['cc_number'];
			$this->cc['orderid'] = $oq['cc_orderid'];
			$this->cc['full'] = $oq['cc_full'];
			$this->cc['expires'] = $oq['cc_expires'];
			$this->datum['purchased'] = $oq['date_purchased'];
			$this->orderstatus['current_status_id'] = $oq['orders_status'];
			$this->dhl['num1'] = $oq['dhl_track_num'];
			$this->dhl['num2'] = $oq['dhl_track_num2'];
			$this->dhl['num3'] = $oq['dhl_track_num3'];
			$this->currency['code'] = $oq['currency'];
			$this->currency['value'] = $oq['currency_value'];
			
			$bank = push_db_fetch_array(push_db_query("SELECT * FROM " . TABLE_GERMANBT . " WHERE orders_id='" . $this->order_id . "'"));
			$this->paymentbank['banktransfer_owner'] = $bank['banktransfer_owner'];
			$this->paymentbank['banktransfer_number'] = $bank['banktransfer_number'];
			$this->paymentbank['banktransfer_bankname'] = $bank['banktransfer_bankname'];
			$this->paymentbank['banktransfer_blz'] = $bank['banktransfer_blz'];
			$this->paymentbank['banktransfer_status'] = $bank['banktransfer_status'];
			$this->allover_discount_tax[2]=0;
			$this->allover_discount_tax[3]=0;
			$this->discount_tax[2]=0;
			$this->discount_tax[3]=0;
			
/*
//CUSTOMER GROUP
			$cg=push_db_fetch_array(push_db_query("SELECT c.customers_group_id , c.customers_group_ra, cg.customers_group_tax_exempt, cg.customers_group_show_tax FROM customers c JOIN customers_groups cg ON (c.customers_group_id = cg.customers_group_id) WHERE customers_id='" . $this->customer['id'] . "'"));
			$this->price_type['price_type'] = ((($cg['customers_group_ra'] == 0) && ($cg['customers_group_show_tax'] == '0')) ? "N" : "B" ) ;

			$sppc_customer_group_show_tax = (int)$cg['customers_group_show_tax'];
			$sppc_customer_group_tax_exempt = (int)$cg['customers_group_tax_exempt'];
			// EOF Separate Pricing per Customer
			// BOF Separate Pricing per Customer
			push_session_register('sppc_customer_group_id');
			if(!push_session_is_registered('sppc_customer_group_show_tax')){
			push_session_register('sppc_customer_group_show_tax');
			}
			push_session_register('sppc_customer_group_tax_exempt');
			// EOF Separate Pricing per Customer
*/
			
//ORDER PRODUCTS
			$pq=push_db_query("SELECT * FROM " . TABLE_ORDERS_PRODUCTS . " WHERE orders_id='" . $this->order_id . "' AND products_id > 0;");
			$i=0;
			while($pr = push_db_fetch_array($pq))
			{
				$this->products[$i]['id'] = $pr['products_id'];
				$this->products[$i]['model'] = $pr['products_model'];
				$this->products[$i]['name'] = $pr['products_name'];
				$p->load_product($this->products[$i]['id']);
				if(substr($this->products[$i]['name'],-2) == 'VE')
				{
					if($p->has_ve)
					{
						$this->products[$i]['price'] = $p->ve_single_price;
						$this->products[$i]['final_price'] = number_format( $p->ve_single_price ,4 , '.', '' );
						$this->products[$i]['quantity'] = ($pr['products_quantity'] * $p->ve_multiplier);
					}
				}
				else
				{
					$this->products[$i]['price'] = $pr['products_price'];
					$this->products[$i]['final_price'] = $pr['final_price'];
					$this->products[$i]['quantity'] = $pr['products_quantity'];				
				}
				$this->products[$i]['tax'] = $pr['products_tax'];	
				$opr = push_db_fetch_array(push_db_query('SELECT products_tax_class_id FROM products WHERE products_id="' . $pr['products_id'] . '";'));
				$this->products[$i]['tax_id'] = $opr['products_tax_class_id'];
				$this->products[$i]['brutto'] = $this->products[$i]['final_price'];//$currencies->display_xml_price($pr['final_price'], $pr['products_tax']);
				$attq= push_db_query( "SELECT * FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " WHERE orders_id = '" . $this->order_id . "' AND orders_products_id = '" . $pr['orders_products_id'] . "' " );
				$this->taxref[$opr['products_tax_class_id']] += $opr['products_tax_class_id'];
				$a=0;
				while($attr = push_db_fetch_array($attq))
				{
					$this->products[$i]['attribute'][$a]['option'] = $attr['products_options'];
					$this->products[$i]['attribute'][$a]['option_value'] = $attr['products_options_values'];
					$this->products[$i]['attribute'][$a]['options_values_price'] = $attr['options_values_price'];
					$this->products[$i]['attribute'][$a]['options_price_prefix'] = $attr['price_prefix'];
					$a++;
				}
				//Tax Handling
				$this->allover_discount_tax[$p->tax_class_id] += $this->products[$i]['brutto'] * $this->products[$i]['quantity'];
				if($p->include_discount == 1)
				{
					$this->discount_tax[$p->tax_class_id] += $this->products[$i]['brutto'] * $this->products[$i]['quantity'];
				}
###################################################################################################
				$i++;
			}
			if($i == 0)
			{
				$this->order_empty=true;
			}
			else
			{
				$this->order_empty=false;	
			}
//DISCOUNT TAX SEPARATION
			$allover_tax_fact=( $this->allover_discount_tax[2] + $this->allover_discount_tax[3] ) / 100;
			$staffel_tax_fact=( $this->discount_tax[2] + $this->discount_tax[3] ) / 100;
			//allover:
			if($allover_tax_fact > 0)
			{
				$this->allover_tax_value[7] = $this->allover_discount_tax[2] / $allover_tax_fact;
				$this->allover_tax_value[19] = $this->allover_discount_tax[3] / $allover_tax_fact;
			}
			//staffel
			if($staffel_tax_fact > 0)
			{
				$this->staffel_tax_value[7] = $this->discount_tax[2] / $staffel_tax_fact;
				$this->staffel_tax_value[19] = $this->discount_tax[3] / $staffel_tax_fact;
			}

//ORDER TOTAL DATA
			$this->get_tax_rate();
			$this->ot_classes = array('ot_shipping', 'ot_giftwrap', 'ot_modul_spesen','push_discount_5', 'push_discount_C', 'push_discount_10', 'push_discount_S','push_tax_7','push_tax_19');
			$this->classarray = array();
			$i=0;
			$otq= push_db_query( "SELECT * FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . $this->order_id . "'; " );
			while($ot = push_db_fetch_array($otq))
			{
				//Steuerid Nettopreis und % bei to_shipping/nachnahme/ot_giftwrap 
				$this->total[$i]['title'] = trim(strip_tags($ot['title']));
				//TAX-SEPARATION 
				if($ot['class'] == 'push_onlinerabatt')
				{
					$this->allover_tax_value[7] =  $currencies->format_clean(($ot['value']/100) * $this->allover_tax_value[7]);
					$this->allover_tax_value[19] = $ot['value'] - $this->allover_tax_value[7];
				}
				if(in_array($ot['class'], array('push_discount_5', 'push_discount_C', 'push_discount_10', 'push_discount_S')))
				{
					$this->staffel_tax_value[7] =  $currencies->format_clean(($ot['value']/100) * $this->staffel_tax_value[7]);
					$this->staffel_tax_value[19] = $ot['value'] - $this->staffel_tax_value[7];
				}
				elseif($ot['class']=='push_discount_0')
				{
					$this->staffel_tax_value[7] =  0;//$currencies->format_clean(($ot['value']/100) * $this->staffel_tax_value[7]);
					$this->staffel_tax_value[19] = 0;//$ot['value'] - $this->staffel_tax_value[7];	
				}
				//EOF TAX SEPARATION
				/*
				if(in_array($ot['class'], $this->ot_classes) && ($ot['value'] > 0))
				{
					if($this->price_type['price_type'] == "N")
					{
						$this->total[$i]['value'] = $ot['value'];
					}
					else
					{
						$this->total[$i]['value'] = $currencies->format_clean(($ot['value'] / (100 + $this->taxrate)) * 100);
					}
					$this->total[$i]['brutto'] = $currencies->display_xml_price($this->total[$i]['value'] , $this->taxrate);
					$this->total[$i]['tax_id'] = $this->taxidentifier;
					$this->total[$i]['tax_rate'] = $this->taxrate;
				}
				else*/
				if(true)
				{
					$this->total[$i]['value'] =$currencies->format_clean($ot['value']);
					$this->total[$i]['brutto'] = $currencies->display_xml_price($ot['value'],0);
					$this->total[$i]['tax_id'] = '';
				}
				$this->classarray[] = $ot['class'];
				$this->total[$i]['text'] = trim(strip_tags($ot['text']));
				$this->total[$i]['class'] = $ot['class'];
				$i++;
			}

			//treat missing classes:
			foreach($this->ot_classes as $tclass)
			{
				if(!(in_array($tclass,$this->classarray)))
				{
					$this->total[$i]['class'] = $tclass;
					$this->total[$i]['value'] = '';
					$this->total[$i]['tax_class'] = '';
					$i++;
				}
			}
//ORDER STATUS HISTORY
			$sq= push_db_query("SELECT * FROM " . TABLE_ORDERS_STATUS_HISTORY . " WHERE orders_id = '" . $this->order_id . "';");
			$i=0;
			while($sr = push_db_fetch_array($sq))
			{
				$this->status[$i]['id'] = $sr['orders_status_id'];
				$this->status[$i]['date'] = $sr['date_added'];
				$this->status[$i]['customer_notified'] = $sr['customer_notified'];
				$this->status[$i]['comments'] = trim(strip_tags($sr['comments']));
				$i++;
			}
		
		}
		else //NO ORDER MATCHED
		{
			$this->order_empty=true;
			return false;
		}
	}

	/**
	* generate_xml returns ordersxml as string
	*/
	function generate_xml(){
		global $currencies;
		if($this->order_empty)
		{
			return false;	
		}
//PROCESS ALL ORDER DATA
		$cxml = new DOMDocument();
		
		//create the root element
		$root = $cxml->appendChild($cxml->createElement("order"));

		$root->appendChild($cxml->createElement("order_id", $this->order_id));

		$node = $root->appendChild($cxml->createElement("customer"));
		foreach($this->customer AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}
		

		
		$node = $root->appendChild($cxml->createElement("delivery"));
		foreach($this->delivery AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}

		$node = $root->appendChild($cxml->createElement("billing"));
		foreach($this->billing AS $key => $value)
		{

			$node->appendChild($cxml->createElement($key, $value));
		}

		$node = $root->appendChild($cxml->createElement("payment"));
		foreach($this->payment AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}

		foreach($this->paymentbank AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}

		$node = $root->appendChild($cxml->createElement("cc"));
		foreach($this->cc AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}

		$node = $root->appendChild($cxml->createElement("datum"));
		foreach($this->datum AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}

		$node = $root->appendChild($cxml->createElement("dhl"));
		foreach($this->dhl AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}

		$node = $root->appendChild($cxml->createElement("orderstatus"));
		foreach($this->orderstatus AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}

		$node = $root->appendChild($cxml->createElement("currency"));
		foreach($this->currency AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}

		$node = $root->appendChild($cxml->createElement("products"));
		foreach($this->price_type AS $key => $value)
		{
			$node->appendChild($cxml->createElement($key, $value));
		}
		foreach($this->products AS $key => $value)
		{
			$subnode = $node->appendChild($cxml->createElement("product"));
			foreach($value AS $keyp => $valuep)
			{
				if($keyp <> 'attribute'){
					$subnode->appendChild($cxml->createElement($keyp, $valuep));
				}
				else
				{
					foreach($valuep AS $keya1 => $valuea1)
					{
						$attrnode = $subnode->appendChild($cxml->createElement('attribute'));
						foreach($valuea1 AS $keya => $valuea)
						{
						
							$attrnode->appendChild($cxml->createElement($keya, $valuea));					
						}
					}
				
				}
			}
		}
		
		$node = $root->appendChild($cxml->createElement("status"));
		foreach($this->status AS $key => $value)
		{
			$subnode = $node->appendChild($cxml->createElement("item"));
			foreach($value AS $keyp => $valuep)
			{
			$subnode->appendChild($cxml->createElement($keyp, $valuep));
			}
		}


		$node = $root->appendChild($cxml->createElement("total"));
		foreach($this->total AS $key => $value)
		{
			$subnode = $node->appendChild($cxml->createElement($value['class']));
			$subnode->appendChild($cxml->createElement("title", $value['title']));
			$subnode->appendChild($cxml->createElement("text", $value['text']));
			$subnode->appendChild($cxml->createElement("value", $value['value']));
			$subnode->appendChild($cxml->createElement("brutto",$value['brutto']));
			$subnode->appendChild($cxml->createElement("tax_class", $value['tax_id']));
			$subnode->appendChild($cxml->createElement("tax_rate", $value['tax_rate']));
		}
		
		$node = $root->appendChild($cxml->createElement("staffel_discount_tax_values"));
		foreach($this->staffel_tax_value AS $dit => $div){
			$node->appendChild($cxml->createElement("discount_tax_value_".$dit,  $currencies->format_clean($div)));
		}
		
		$node = $root->appendChild($cxml->createElement("overall_discount_tax_values"));
		foreach($this->allover_tax_value AS $dit => $div){
			$node->appendChild($cxml->createElement("overall_tax_value_".$dit, $currencies->format_clean($div)));
		}
	
		//make the output pretty
		$cxml->formatOutput = true;
		$this->xml = $cxml->saveXML();
	}
	
	function output_xml(){
		return $this->xml;
	}
	
	function send_xml(){
		if($this->order_empty)
		{
			return false;	
		}
		$xml_file= DIR_FS_CATALOG . 'includes/xml/order_' . $this->order_id .'.xml';
		file_put_contents($xml_file ,$this->clean($this->xml));
		$file_name = 'order_' . $this->order_id . '.xml';
		$path = DIR_FS_CATALOG . "includes/xml/";
		// Read the file content
		$file = $path.$file_name;
		$file_size = filesize($file);
		$handle = fopen($file, "r");
		$content = fread($handle, $file_size);
		fclose($handle);
		
		$content = chunk_split(base64_encode($content));
		
		/* Set the email header */
		// Generate a boundary
		$boundary = md5(uniqid(time()));
		
		// Email header
		$header = "From: "."Onlineshop <onlineshop@Bruesselser-Kakaoroesterei.de>" ."\r\n";
		$header .= "Reply-To: ". STORE_OWNER_EMAIL_ADDRESS . "\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		
		// Multipart wraps the Email Content and Attachment
		$header .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		$header .= "--".$boundary."\r\n";
		
		// Email content
		// Content-type can be text/plain or text/html
		$header .= "Content-type:text/plain; charset=utf-8\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$header .= "XML Siehe Anhang:\r\n";
		$header .= "--".$boundary."\r\n";
		
		// Attachment
		// Edit content type for different file extensions
		$header .= "Content-Type: application/xml; name=\"".$file_name."\"\r\n";
		$header .= "Content-Transfer-Encoding: base64\r\n";
		$header .= "Content-Disposition: attachment; filename=\"".$file_name."\"\r\n\r\n";
		$header .= $content."\r\n";
		$header .= "--".$boundary."--";
		
		// Send email
		//set off for the first run
		//mail(STORE_OWNER_EMAIL_ADDRESS,  utf8_decode(STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . ' ' . $this->order_id . ' ' .EMAIL_TEXT_SUBJECT_2), "", $header);

	}
	
	function clean($string){
		$find=		array('&nbsp;', '& ', 		'&ouml;', 	'&uuml;', 	'&auml;', 	'&Ouml;', 	'&Uuml;', 	'&Auml;', 	'&szlig;'	);
		$replace=	array(' ', 		'&amp; ', 	'ö',		'ü', 		'ä', 		'Ö',		'Ü', 		'Ä', 		'ß'			);
		$string = str_replace($find, $replace, $string ); //html_entity_decode($string, ENT_NOQUOTES | ENT_XML1 ,'UTF-8' );
		return $string;
	}
	
	function get_tax_rate(){
		foreach($this->taxref AS $ID => $VALUE)
		{
			if(isset($currentid))
			{
				if($currentvalue < $VALUE){
				$currentid = $ID;
				$currentvalue = $VALUE;
				}
			}
			else
			{
				$currentid = $ID;
				$currentvalue = $VALUE;
			}
		}

		$this->taxidentifier = $currentid;
		$tq=push_db_fetch_array(push_db_query("SELECT tax_rate FROM tax_rates WHERE tax_class_id ='" . $this->taxidentifier . "'"));
		$this->taxrate = $tq['tax_rate'];
	}
}
?>
