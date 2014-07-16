<?php
/*
  $Id: telecash_ipn.php,v 1.28 2012/01/20 05:51:31 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class telecash_ipn {
    var $code, $title, $description, $enabled;

// class constructor
    function telecash_ipn() {
      global $order;

      $this->code = 'telecash_ipn';
      $this->title = MODULE_PAYMENT_TELECASH_IPN_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_TELECASH_IPN_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_TELECASH_IPN_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_TELECASH_IPN_STATUS == 'True') ? true : false);


      if ((int)MODULE_PAYMENT_TELECASH_IPN_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_TELECASH_IPN_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }

// class methods
   function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_TELECASH_IPN_ZONE > 0) ) {
        $check_flag = false;
        $check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_TELECASH_IPN_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = push_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }
	function parsing($tag,$string) {
			  $start=strpos($string,"<" . $tag . ">" );
			  $start=$start + strlen("<" . $tag . ">");
			  $end=(strpos($string, "</" . $tag . ">"));
			  $num= ($end - $start);
		   if($num>0){
			   $tagvalue=substr($string,$start,$num);
			   return $tagvalue;
			}
	 }
	
	
    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    function selection() {
      global $order;

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => "<label class='grid_3 alpha'>" . MODULE_PAYMENT_TELECASH_IPN_TEXT_CREDIT_CARD_OWNER . "</label>",
                                                 'field' => push_draw_input_field('cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'],' onclick="document.checkout_payment.payment['.$this->index.'].checked=true" class="grid_6"'). "<div class='grid_3 omega' style='margin-left:-20px;margin-bottom:20px;min-height:20px'>&nbsp;</div><br>"),
                                           array('title' => "<label class='grid_3 alpha'>" . MODULE_PAYMENT_TELECASH_IPN_TEXT_CREDIT_CARD_NUMBER . "</label>",
                                                 'field' => push_draw_input_field('cc_number','',' onclick="document.checkout_payment.payment['.$this->index.'].checked=true" class="grid_6"'). "<div class='grid_3 omega' style='margin-left:-20px;margin-bottom:20px;min-height:20px'>&nbsp;</div><br>"),
                                           array('title' => "<label class='grid_3 alpha'>" . MODULE_PAYMENT_TELECASH_IPN_TEXT_CREDIT_CARD_CODE ."</label>",
                                                 'field' => push_draw_input_field('cc_code','',' onclick="document.checkout_payment.payment['.$this->index.'].checked=true"  class="grid_6"') . "<div class='grid_3 omega' style='margin-left:-20px;margin-bottom:20px;min-height:20px'>&nbsp;</div><br>"),
                                           array('title' => "<label class='grid_3 alpha'>" .MODULE_PAYMENT_TELECASH_IPN_TEXT_CREDIT_CARD_EXPIRES . "</label>",
                                                 'field' => push_draw_pull_down_menu('cc_expires_month', $expires_month,'','class="grid_4"') . '&nbsp;' . push_draw_pull_down_menu('cc_expires_year', $expires_year,'',' class="grid_2" style="width:115px;"') . "<div class='grid_3 omega' style='margin-bottom:20px;min-height:20px'>&nbsp;</div><br>")));

      return $selection;
    }

    function pre_confirmation_check() {
      global $_POST;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['cc_number'], $_POST['cc_expires_month'], $_POST['cc_expires_year'],$_POST['cc_code']);

      $error = '';
      switch ($result) {
        case -1:
          $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
          break;
        case -2:
        case -3:
        case -4:
          $error = TEXT_CCVAL_ERROR_INVALID_DATE;
          break;
        case false:
          $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
          break;
      }

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&cc_owner=' . urlencode($_POST['cc_owner']) . '&cc_expires_month=' . $_POST['cc_expires_month'] . '&cc_expires_year=' . $_POST['cc_expires_year'];

        push_redirect(push_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
    }

    function confirmation() {
      global $_POST;

      $confirmation = array('title' => "<b class='grid_3'>" .$this->title . '</b> <div class="grid_13">' . $this->cc_card_type . "</div>",
                            'fields' => array(array('title' => MODULE_PAYMENT_TELECASH_IPN_TEXT_CREDIT_CARD_OWNER,
                             'field' => $_POST['cc_owner']),
                                              array('title' => MODULE_PAYMENT_TELECASH_IPN_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_TELECASH_IPN_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['cc_expires_month'], 1, '20' . $_POST['cc_expires_year'])))));

      return $confirmation;
    }

    function process_button() {
      global $_POST;

      $process_button_string = push_draw_hidden_field('cc_owner', $_POST['cc_owner']) .
                               push_draw_hidden_field('cc_expires', $_POST['cc_expires_month'] . $_POST['cc_expires_year']) .
                               push_draw_hidden_field('cc_type', $this->cc_card_type) .
                               push_draw_hidden_field('cc_number', $this->cc_card_number) .
							   push_draw_hidden_field('cc_code', $this->cc_card_code);

      return $process_button_string;
    }

 function before_process() {
      global $_POST, $order,$order_totals, $customer_id;
	  $soaptags = Array( 
		"ipgapi:ApprovalCode", 
		"ipgapi:AVSResponse", 
		"ipgapi:OrderId", 
		"ipgapi:ProcessorApprovalCode", 
		"ipgapi:ProcessorReceiptNumber", 
		"ipgapi:ProcessorCCVResponse",
		"ipgapi:ProcessorReferenceNumber",
		"ipgapi:ProcessorResponseCode",
		"ipgapi:ProcessorResponseMessage",
		"ipgapi:ProcessorTraceNumber",
		"ipgapi:TDate",
		"ipgapi:TDateFormatted",
		"ipgapi:TerminalID",
		"ipgapi:TransactionResult",
		"ipgapi:TransactionTime");
/**
 * Transfer Payment (just try!!)
 */
$cardnr=$order->info["cc_number"];
$validmonth= substr($_POST["cc_expires"], 0, 2);
$validyear= substr($_POST["cc_expires"], 2, 2);
$cardcode=$_POST["cc_code"];
$value=push_round($order_totals[sizeof($order_totals)-1]['value'],2);
$order->info["cc_number"]=substr($order->info["cc_number"], 0,3) . "xxxxxxxxxxxx";
$order->info["payment_method"]="Telecash Kreditkarte";
$userid=$customer_id;
$username= $order->customer['firstname'] . ' ' . $order->customer['lastname'];
//var_dump($order_totals);
$xml=
"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
	<SOAP-ENV:Envelope
		xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">
		<SOAP-ENV:Header />
			<SOAP-ENV:Body>
			<ipgapi:IPGApiOrderRequest
					xmlns:v1=\"http://ipg-online.com/ipgapi/schemas/v1\"
					xmlns:ipgapi=\"http://ipg-online.com/ipgapi/schemas/ipgapi\">
				<v1:Transaction>
					<v1:CreditCardTxType>
						<v1:Type>sale</v1:Type>
					</v1:CreditCardTxType>
					<v1:CreditCardData>
						<v1:CardNumber>$cardnr</v1:CardNumber>
						<v1:ExpMonth>$validmonth</v1:ExpMonth>
						<v1:ExpYear>$validyear</v1:ExpYear>
						<v1:CardCodeValue>$cardcode</v1:CardCodeValue>
					</v1:CreditCardData>
					<v1:Payment>
						<v1:ChargeTotal>$value</v1:ChargeTotal>
						<v1:Currency>978</v1:Currency>
					</v1:Payment>
					<v1:Billing>
						<v1:Name>
							$username
						</v1:Name>
						<v1:CustomerID>
						$customerid
						</v1:CustomerID>
					</v1:Billing>
				</v1:Transaction>
			</ipgapi:IPGApiOrderRequest>
		</SOAP-ENV:Body>
</SOAP-ENV:Envelope>";
$body = utf8_encode($xml);
// initializing cURL with the IPG API URL:
$ch = curl_init("https://www.ipg-online.com/ipgapi/services");
// setting the request type to POST:
curl_setopt($ch, CURLOPT_POST, 1);
// setting the content type:
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
// setting the authorization method to BASIC:
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
// supplying your credentials:
curl_setopt($ch, CURLOPT_USERPWD, MODULE_PAYMENT_TELECASH_IPN_USERPWD);
// filling the request body with your SOAP message:
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
// telling cURL to verify the server certificate:
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
// setting the path where cURL can find the certificate to verify the
// received server certificate against:
curl_setopt($ch, CURLOPT_CAINFO, "/srv/www/vhosts/Bruesselser-Kakaoroesterei.de/httpdocs/private/geotrust.pem");
// setting the path where cURL can find the client certificate:
curl_setopt($ch, CURLOPT_SSLCERT, "/srv/www/vhosts/Bruesselser-Kakaoroesterei.de/httpdocs/private/WS1295967201._.1.pem");
// setting the path where cURL can find the client certificates
// private key:
curl_setopt($ch, CURLOPT_SSLKEY, "/srv/www/vhosts/Bruesselser-Kakaoroesterei.de/httpdocs/private/WS1295967201._.1.key");
// setting the key password:
curl_setopt($ch, CURLOPT_SSLKEYPASSWD, MODULE_PAYMENT_TELECASH_SSLPWD);
// telling cURL to return the HTTP response body as operation result
// value when calling curl_exec:
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// calling cURL and saving the SOAP response message in a variable which
// contains a string like "<SOAP-ENV:Envelope ...>...</SOAP-ENV:Envelope>":
$result = curl_exec($ch);
// closing cURL:
curl_close($ch);
/**
 * and now treat the Result!!!
 */
 if($this->parsing("ipgapi:TransactionResult", $result) == "APPROVED"){
  
	 $order->info["cc_orderid"]= $this->parsing("ipgapi:OrderId", $result);
	 $cc_response='';
	 foreach($soaptags AS $tag){
		$cc_response .= $tag . " -> {" . $this->parsing($tag, $result) ."}<br>\n\n";
	 } 
	 $order->info["cc_response"]=$cc_response;
	}else{
		return "decline";
	}	 
 
 
 }

    function after_process() {
      global $insert_id;

    }

    function get_error() {
      global $_GET;

      $error = array('title' => MODULE_PAYMENT_TELECASH_IPN_TEXT_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_TELECASH_IPN_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }
      return $this->_check;
    }
    function install() {  push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Telecash IPN Credit Card Module', 'MODULE_PAYMENT_TELECASH_IPN_STATUS', 'True', 'Do you want to accept credit card payments?', '6', '0', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
  //    push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Split Credit Card E-Mail Address', 'MODULE_PAYMENT_TELECASH_IPN_EMAIL', '', 'If an e-mail address is entered, the middle digits of the credit card number will be sent to the e-mail address (the outside digits are stored in the database with the middle digits censored)', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_TELECASH_IPN_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");
	    push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Zugangsdaten', 'MODULE_PAYMENT_TELECASH_IPN_USERPWD', '0', 'Zugang zum API <br>Format: API-UserID:API Passwort', '6', '0' , now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Das SSL-Passwort.', 'MODULE_PAYMENT_TELECASH_SSLPWD', '0', 'SSL-Passwort (Test: T6QbLSDsvP).', '6', '0' , now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_TELECASH_IPN_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_TELECASH_IPN_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_TELECASH_IPN_STATUS', 'MODULE_PAYMENT_TELECASH_IPN_ZONE', 'MODULE_PAYMENT_TELECASH_IPN_ORDER_STATUS_ID','MODULE_PAYMENT_TELECASH_SSLPWD','MODULE_PAYMENT_TELECASH_IPN_USERPWD', 'MODULE_PAYMENT_TELECASH_IPN_SORT_ORDER');
    }
  }
?>
