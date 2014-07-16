<?php
/*
  $Id: telecash_connect.php,v 2.1.0.0 13/01/2007 16:30:28 Edith Karnitsch Exp $

  Copyright (c) 2004 osCommerce
  Released under the GNU General Public License

  Original Authors: Harald Ponce de Leon, Mark Evans
  Updates by PandA.nl, Navyhost, Zoeticlight, David, gravyface, AlexStudio, windfjf and Terra
  
  adapted as telecash_connect by Bruesselser Kakaoroesterei

*/

  class telecash_connect {
	var $code, $title, $description, $enabled, $identifier, $curDateTime, $index;

// class constructor
	function telecash_connect() {
		global $order;

		$this->curDateTime= date("Y:m:d-H:i:s");
		$this->code = 'telecash_connect';
		$this->title = MODULE_PAYMENT_TELECASH_CONNECT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_TELECASH_CONNECT_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_TELECASH_CONNECT_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_TELECASH_CONNECT_STATUS == 'True') ? true : false);
		$this->email_footer = MODULE_PAYMENT_TELECASH_CONNECT_TEXT_EMAIL_FOOTER;
		$this->identifier = 'osCommerce Telecash Connect';
		$this->index;

		if ((int)MODULE_PAYMENT_TELECASH_CONNECT_PREPARE_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_TELECASH_CONNECT_PREPARE_ORDER_STATUS_ID;
		}

		if (is_object($order)){
			$this->update_status();
		}

		if (MODULE_PAYMENT_TELECASH_CONNECT_GATEWAY_SERVER == 'Live') {
			$this->form_action_url = 'https://www.ipg-online.com/connect/gateway/processing';
		} else {
			$this->form_action_url = 'https://test.ipg-online.com/connect/gateway/processing';
		}
		
	}

// class methods
	function update_status() {
		global $order;
		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_TELECASH_CONNECT_ZONE > 0) ) {
			$check_flag = false;
			$check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_TELECASH_CONNECT_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

	function createHash($chargetotal, $currency) { 
		$storeId = MODULE_PAYMENT_TELECASH_CONNECT_ID; 
		$sharedSecret = MODULE_PAYMENT_TELECASH_CONNECT_EWP_CERT_ID; 
		$stringToHash = $storeId . $this->curDateTime . $chargetotal . $currency . $sharedSecret; 
		$ascii = bin2hex($stringToHash); 
		return sha1($ascii); 
	}

	function javascript_validation() {
		return false;
	}

	function selection() {
		return array('id' => $this->code,
					'module' => $this->title,
					'fields' => array(array('title' => MODULE_PAYMENT_TELECASH_CONNECT_TEXT_DESCRIPTION)));
	}

	function pre_confirmation_check() {
		return false;
	}

	function confirmation() {
		global $cartID, $scart_TELECASH_CONNECT_ID,  $languages_id, $order, $order_total_modules;

	// if (push_session_is_registered('cartID')) {
	// PandA.nl: register_globals fix
		if (array_key_exists('cartID', $_SESSION)) {
		$insert_order = false;

		if (push_session_is_registered('cart_TELECASH_CONNECT_ID')) {
			$order_id = substr($scart_TELECASH_CONNECT_ID, strpos($scart_TELECASH_CONNECT_ID, '-')+1);
			$curr_check = push_db_query("select currency from " . TABLE_ORDERS . " where orders_id = '" . $order_id . "'");
			$curr = push_db_fetch_array($curr_check);

	//if ( ($curr['currency'] != $order->info['currency']) || ($cartID != substr($scart_TELECASH_CONNECT_ID, 0, strlen($cartID))) ) {
	//$check_query = push_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');
			$check_query = push_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . $order_id . '" limit 2');  //1.4
			$update_order = false;//1.4

	//if (push_db_num_rows($check_query) < 1) {
	//push_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
			if (push_db_num_rows($check_query) == 1) { //1.4
				$update_order = true; //1.4
 				push_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . $order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . $order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . $order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . $order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . $order_id . '"');
			}
			$insert_order = true;
			//
			//echo '<script>alert("ID ist schon vorhanden") 
			// </ script>';
		  //}
		} else {
			$insert_order = true;
		}

        if ($insert_order == true) {
          $order_totals = array();
          if (is_array($order_total_modules->modules)) {
            reset($order_total_modules->modules);
            while (list(, $value) = each($order_total_modules->modules)) {
              $class = substr($value, 0, strrpos($value, '.'));
              if ($GLOBALS[$class]->enabled) {
                for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
                  if (push_not_null($GLOBALS[$class]->output[$i]['title']) && push_not_null($GLOBALS[$class]->output[$i]['text'])) {
                    $order_totals[] = array('code' => $GLOBALS[$class]->code,
                                            'title' => $GLOBALS[$class]->output[$i]['title'],
                                            'text' => $GLOBALS[$class]->output[$i]['text'],
                                            'value' => $GLOBALS[$class]->output[$i]['value'],
                                            'sort_order' => $GLOBALS[$class]->sort_order);
                  }
                }
              }
            }
          }
			
			
			$oID = push_generate_order_id('T');
			$sql_data_array = array('orders_id' => $oID,
									'customers_id' => $_SESSION['customer_id'],
									'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
									'customers_company' => $order->customer['company'],
									'customers_street_address' => $order->customer['street_address'],
									'customers_suburb' => $order->customer['suburb'],
									'customers_city' => $order->customer['city'],
									'customers_postcode' => $order->customer['postcode'],
									'customers_state' => $order->customer['state'],
									'customers_country' => $order->customer['country']['title'],
									'customers_telephone' => $order->customer['telephone'],
									'customers_email_address' => $order->customer['email_address'],
									'customers_address_format_id' => $order->customer['format_id'],
									'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
									'delivery_company' => $order->delivery['company'],
									'delivery_street_address' => $order->delivery['street_address'],
									'delivery_suburb' => $order->delivery['suburb'],
									'delivery_city' => $order->delivery['city'],
									'delivery_postcode' => $order->delivery['postcode'],
									'delivery_state' => $order->delivery['state'],
									'delivery_country' => $order->delivery['country']['title'],
									'delivery_address_format_id' => $order->delivery['format_id'],
									'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
									'billing_company' => $order->billing['company'],
									'billing_street_address' => $order->billing['street_address'],
									'billing_suburb' => $order->billing['suburb'],
									'billing_city' => $order->billing['city'],
									'billing_postcode' => $order->billing['postcode'],
									'billing_state' => $order->billing['state'],
									'billing_country' => $order->billing['country']['title'],
									'billing_address_format_id' => $order->billing['format_id'],
									'payment_method' => $order->info['payment_method'],
									'cc_type' => $order->info['cc_type'],
									'cc_owner' => $order->info['cc_owner'],
									'cc_number' => $order->info['cc_number'],
									'cc_expires' => $order->info['cc_expires'],
									'date_purchased' => 'now()',
									'orders_status' => $order->info['order_status'],
									'currency' => $order->info['currency'],
									'currency_value' => $order->info['currency_value']);

		  //+1.4
		  if ( $update_order ){
		    push_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = "' . $order_id . '"');
            $insert_id =  $order_id;
		  } else {
		  //-1.4
          push_db_perform(TABLE_ORDERS, $sql_data_array);

          $insert_id = $oID;
		  }//1.4

          for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
            $sql_data_array = array('orders_id' => $insert_id,
                                    'title' => $order_totals[$i]['title'],
                                    'text' => $order_totals[$i]['text'],
                                    'value' => $order_totals[$i]['value'],
                                    'class' => $order_totals[$i]['code'],
                                    'sort_order' => $order_totals[$i]['sort_order']);

            push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
          }
		  //+1.4
		  $sql_data_array = array('orders_id' => $insert_id,
                                    'orders_status_id' => $order->info['order_status'],
                                    'date_added' => 'now()',
						 			'customer_notified' => '0',
                                    'comments' => $order->info['comments']);
          push_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		  //-1.4

          for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
            $sql_data_array = array('orders_id' => $insert_id,
                                    'products_id' => push_get_prid($order->products[$i]['id']),
                                    'products_model' => $order->products[$i]['model'],
                                    'products_name' => $order->products[$i]['name'],
                                    'products_price' => $order->products[$i]['price'],
                                    'final_price' => $order->products[$i]['final_price'],
                                    'products_tax' => $order->products[$i]['tax'],
                                    'products_quantity' => $order->products[$i]['qty']);

            push_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
            $order_products_id = push_db_insert_id();
			$order_total_modules->update_credit_account($i);// CCGV

            $attributes_exist = '0';
            if (isset($order->products[$i]['attributes'])) {
              $attributes_exist = '1';
              for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
                if (DOWNLOAD_ENABLED == 'true') {
                  $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                       from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                       left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                       on pa.products_attributes_id=pad.products_attributes_id
                                       where pa.products_id = '" . $order->products[$i]['id'] . "'
                                       and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . $languages_id . "'
                                       and poval.language_id = '" . $languages_id . "'";
                  $attributes = push_db_query($attributes_query);
                } else {
                  $attributes = push_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
                }
                $attributes_values = push_db_fetch_array($attributes);

                $sql_data_array = array('orders_id' => $insert_id,
                                        'orders_products_id' => $order_products_id,
                                        'products_options' => $attributes_values['products_options_name'],
                                        'products_options_values' => $attributes_values['products_options_values_name'],
                                        'options_values_price' => $attributes_values['options_values_price'],
                                        'price_prefix' => $attributes_values['price_prefix']);

                push_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

                if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && push_not_null($attributes_values['products_attributes_filename'])) {
                  $sql_data_array = array('orders_id' => $insert_id,
                                          'orders_products_id' => $order_products_id,
                                          'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                          'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                          'download_count' => $attributes_values['products_attributes_maxcount']);

                  push_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
                }
              }
            }
          }
          push_session_register('cart_TELECASH_CONNECT_ID');
          // Terra register globals fix
          $_SESSION['cart_TELECASH_CONNECT_ID'] = $cartID . '-' . $insert_id;
        }
      }

      return false;
    }

	function process_button() {
		global $order, $languages_id, $currencies, $currency, $scart_TELECASH_CONNECT_ID, $shipping;

		echo "<!-- ...";
		//var_dump($order);
		echo "--> \n";
		$my_currency="978"; 
// billing information fix by gravyface
		$state_abbr = push_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], $order->delivery['state']);
		$name = $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];

		$amount = number_format($order->info['total'], 2);

		$parameters = array();
//Array of parameters for hidden fields
		$parameters['txntype'] = 'sale';
		$parameters['timezone'] = 'CET';
		$parameters['txndatetime'] = $this->curDateTime ;
		$parameters['storename'] = MODULE_PAYMENT_TELECASH_CONNECT_ID;
		$parameters['hash'] = $this->createHash($amount, $my_currency);
		$parameters['mode'] = "payonly" ;
		$parameters['currency'] = $my_currency ;

/*		if(MOVE_TAX_TO_TOTAL_AMOUNT == 'True') {
		// PandA.nl move tax to total amount
			$parameters['chargetotal'] = number_format(($order->info['total'] - $order->info['shipping_cost']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
		} else {
		//default
			$parameters['chargetotal'] = number_format(($order->info['total'] - $order->info['shipping_cost'] - $order->info['tax']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
		}
*/
		$parameters['chargetotal'] = $amount; //number_format(($order->info['total'] - $order->info['tax']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));



		$parameters['language'] = 'de_DE';
		$parameters['customerid'] = $_SESSION['customer_id'];
		$parameters['invoicenumber'] = substr($scart_TELECASH_CONNECT_ID, strpos($scart_TELECASH_CONNECT_ID, '-')+1);

		$parameters['transactionNotificationURL'] = push_href_link('ext/modules/payment/telecash_connect/ipn.php', '', 'SSL', false, false);
		$parameters['responseSuccessURL'] = push_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
//		$parameters['responseFailURL'] = push_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
		$parameters['responseFailURL'] = push_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');
//custom Fields
		$parameters['bn'] = $this->identifier;
		$parameters['zeit'] = urlencode($this->curDateTime);
		$parameters['kundenname'] = $name;
		$parameters['lc'] = $order->customer['country']['iso_code_2'];
		$parameters['customers_comment'] = $order->info['comments'];

		while (list($key, $value) = each($parameters)) {
			echo push_draw_hidden_field($key, $value) . " \n";
		}
		return $process_button_string;
	}

	function before_process() {
		global $cart;

		$cart->reset(true);

	// unregister session variables used during checkout
		push_session_unregister('sendto');
		push_session_unregister('billto');
		push_session_unregister('shipping');
		push_session_unregister('payment');
		push_session_unregister('comments');
		push_session_unregister('cart_TELECASH_CONNECT_ID');

		if(push_session_is_registered('credit_covers')) push_session_unregister('credit_covers');// CCGV
			$order_total_modules->clear_posts();// CCGV
		push_redirect(push_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
	}

	function after_process() {
		return false;
	}

	function output_error() {
		return false;
	}

	function check() {
		if (!isset($this->_check)){
			$check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_TELECASH_CONNECT_STATUS'");
			$this->_check = push_db_num_rows($check_query);
		}
		return $this->_check;
	}

	function install() {

	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable telecas Conect Module', 'MODULE_PAYMENT_TELECASH_CONNECT_STATUS', 'False', 'Wollen Sie Telecash Connect aktivieren?', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Gateway Server', 'MODULE_PAYMENT_TELECASH_CONNECT_GATEWAY_SERVER', 'Testing', 'Use the testing (sandbox) or live gateway server for transactions?', '6', '2', 'push_cfg_select_option(array(\'Testing\',\'Live\'), ', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_TELECASH_CONNECT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Force shipping address?', 'MODULE_PAYMENT_TELECASH_CONNECT_SHIPPING', 'False', 'Vorerst nicht benutzt.', '6', '4', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ShopID', 'MODULE_PAYMENT_TELECASH_CONNECT_ID', '', 'Ihre ShopID', '6', '5', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_TELECASH_CONNECT_CURRENCY', 'Selected Currency', 'The currency to use for transactions derzeit nicht benutzt', '6', '10', 'push_cfg_select_option(array(\'Selected Currency\',\'Only AUD\',\'Only CAD\',\'Only CHF\',\'Only CZK\',\'Only DKK\',\'Only EUR\',\'Only GBP\',\'Only HKD\',\'Only HUF\',\'Only JPY\',\'Only NOK\',\'Only NZD\',\'Only PLN\',\'Only SEK\',\'Only SGD\',\'Only USD\'), ', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_TELECASH_CONNECT_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '11', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Preparing Order Status', 'MODULE_PAYMENT_TELECASH_CONNECT_PREPARE_ORDER_STATUS_ID', '" . $status_id . "', 'Set the status of prepared orders made with this payment module to this value', '6', '12', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Connect Acknowledged Order Status', 'MODULE_PAYMENT_TELECASH_CONNECT_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '13', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Telecash Connect Completed Order Status', 'MODULE_PAYMENT_TELECASH_CONNECT_COMP_ORDER_STATUS_ID', '0', 'Set the status of orders which are confirmed as paid (completed) to this value', '6', '13', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Type', 'MODULE_PAYMENT_TELECASH_CONNECT_TRANSACTION_TYPE', 'Aggregate', 'Komplette Zahlungen? nicht verwendet.', '6', '14', 'push_cfg_select_option(array(\'Per Item\',\'Aggregate\'), ', now())");
// bof PandA.nl move tax to total amount
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Move tax to total amount', 'MOVE_TAX_TO_TOTAL_AMOUNT', 'True', 'Do you want to move the tax to the total amount? ebenfalls nicht verwendet', '6', '15', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
// eof PandA.nl move tax to total amount
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Debug E-Mail Address', 'MODULE_PAYMENT_TELECASH_CONNECT_DEBUG_EMAIL', '', 'All parameters of an Invalid Connect notification will be sent to this email address if one is entered.', '6', '21', now())");
	push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your Telecash Public Certificate ID', 'MODULE_PAYMENT_TELECASH_CONNECT_EWP_CERT_ID', '', 'The Certificate ID to use from your Connect Encrypted Payment Settings Profile.', '6', '34', now())");
	} 

	function remove() {
		push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
// PandA.nl move tax to total amount added: ", 'MOVE_TAX_TO_TOTAL_AMOUNT'"
		return array('MODULE_PAYMENT_TELECASH_CONNECT_STATUS', 'MODULE_PAYMENT_TELECASH_CONNECT_GATEWAY_SERVER', 'MODULE_PAYMENT_TELECASH_CONNECT_ID', 'MODULE_PAYMENT_TELECASH_CONNECT_SORT_ORDER', 'MODULE_PAYMENT_TELECASH_CONNECT_CURRENCY', 'MODULE_PAYMENT_TELECASH_CONNECT_ZONE', 'MODULE_PAYMENT_TELECASH_CONNECT_SHIPPING', 'MODULE_PAYMENT_TELECASH_CONNECT_PREPARE_ORDER_STATUS_ID', 'MODULE_PAYMENT_TELECASH_CONNECT_ORDER_STATUS_ID', 'MODULE_PAYMENT_TELECASH_CONNECT_COMP_ORDER_STATUS_ID', 'MODULE_PAYMENT_TELECASH_CONNECT_TRANSACTION_TYPE', 'MOVE_TAX_TO_TOTAL_AMOUNT', 'MODULE_PAYMENT_TELECASH_CONNECT_PAGE_STYLE', 'MODULE_PAYMENT_TELECASH_CONNECT_DEBUG_EMAIL', 'MODULE_PAYMENT_TELECASH_CONNECT_EWP_CERT_ID');
	}
  }
?>
