<?php
/**
 * @version sofortueberweisung.de - $Date: 2011-02-02 17:58:01 +0100 (Mi, 02 Feb 2011) $
 * @author Payment Network AG (integration@payment-network.com)
 * @link http://www.payment-network.com/

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2006 - 2007 Henri Schmidhuber (http://www.in-solution.de)
 Copyright (c) 2008 osCommerce
 Copyright (c) 2010 Payment Network AG

 Released under the GNU General Public License

 $Id: pn_sofortueberweisung.php 423 2011-02-02 16:58:01Z poser $

 */

require(DIR_FS_CATALOG.'ext/modules/payment/pn_sofortueberweisung/classPnSofortueberweisung.php');

class pn_sofortueberweisung {
	var $code, $title, $description, $enabled, $pnSofortueberweisung;

	// class constructor
	function pn_sofortueberweisung() {
		global $order;

		$this->signature = 'pn|pn_sofortueberweisung|2.3.6|2.2';

		$this->code = 'pn_sofortueberweisung';
		$this->version = 'pn_osc_2.3.6';
		$this->title = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_TITLE;
		$this->public_title = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_PUBLIC_TITLE;
		$this->description = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS == 'True') ? true : false);

		if ((int)MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID;
		}

		if (is_object($order)) 
			$this->update_status();

		$this->email_footer = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_EMAIL_FOOTER;

		$this->pnSofortueberweisung = new classPnSofortueberweisung(MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD, MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM);
		$this->form_action_url = DIR_WS_CATALOG . 'ext/modules/payment/pn_sofortueberweisung/redirect.php'; 
		$this->pnSofortueberweisung->version = $this->version;

	}

	// class methods
	function update_status() {
		global $order;

		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE > 0) ) {
			$check_flag = false;
			$check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

	function javascript_validation() {
		return false;
	}

	function selection() {
		global $cart_pn_sofortueberweisung_ID, $language;

		if (push_session_is_registered('cart_pn_sofortueberweisung_ID')) {
			$order_id = substr($cart_pn_sofortueberweisung_ID, strpos($cart_pn_sofortueberweisung_ID, '-')+1);

			$check_query = push_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . $order_id . '" limit 1');

			if (push_db_num_rows($check_query) < 1) {
				// Restock = false
				// Product quantity will be reduced in before_process(), AFTER successful payment
				$this->_remove_order($order_id);
				push_session_unregister('cart_pn_sofortueberweisung_ID');
			}
		}

		$title = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT;
		switch (MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE) {
			case 'Logo & Text':
				$image = push_image(sprintf('includes/languages/%s/images/buttons/sofortueberweisung_logo.gif', $language), MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGEALT);
				$title = str_replace('{{image}}', $image, sprintf(MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGE, MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_TEXT));
				break;
			case 'Logo':
				$image = push_image(sprintf('includes/languages/%s/images/buttons/sofortueberweisung_logo.gif', $language), MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGEALT);
				$title = str_replace('{{image}}', $image, sprintf(MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGE, ''));
				break;
			case 'Infographic':
				$image = push_image(sprintf('includes/languages/%s/images/buttons/sofortueberweisung_info.gif', $language), MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGEALT);
				$title = str_replace('{{image}}', $image, sprintf(MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGE, ''));
				break;
		}
		return array('id' => $this->code,
                   'module' => $this->public_title,
                   'fields' => array(array('title' => $title)));
	}

	function pre_confirmation_check() {
		global $cartID, $cart;

		// We need the cartID
		if (empty($cart->cartID)) {
			$cartID = $cart->cartID = $cart->generate_cart_id();
		}

		if (!push_session_is_registered('cartID')) {
			push_session_register('cartID');
		}
	}
	
	function confirmation() {
		global $cartID, $cart_pn_sofortueberweisung_ID, $customer_id, $languages_id, $language, $order, $order_total_modules;
		global $redirect;
		if($redirect !== true)
			return false;
		
		$insert_order = false;

		if (push_session_is_registered('cart_pn_sofortueberweisung_ID')) {
			$order_id = substr($cart_pn_sofortueberweisung_ID, strpos($cart_pn_sofortueberweisung_ID, '-')+1);

			$curr_check = push_db_query("select currency from " . TABLE_ORDERS . " where orders_id = '" . $order_id . "'");
			$curr = push_db_fetch_array($curr_check);

			if ( ($curr['currency'] != $order->info['currency']) || ($cartID != substr($cart_pn_sofortueberweisung_ID, 0, strlen($cartID))) ) {
				$check_query = push_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . $order_id . '" limit 1');

				if (push_db_num_rows($check_query) < 1) {
					// Restock = false
					// Product quantity will be reduced in before_process(), AFTER successful payment
					$this->_remove_order($order_id);
				}

				$insert_order = true;
			}
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

			$oID = push_generate_order_id('U');
			$sql_data_array = array('orders_id' => $oID,
								'customers_id' => $customer_id,
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

			push_db_perform(TABLE_ORDERS, $sql_data_array);

			$insert_id = $oID;

			for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
				$sql_data_array = array('orders_id' => $insert_id,
                                  'title' => $order_totals[$i]['title'],
                                  'text' => $order_totals[$i]['text'],
                                  'value' => $order_totals[$i]['value'],
                                  'class' => $order_totals[$i]['code'],
                                  'sort_order' => $order_totals[$i]['sort_order']);

				push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
			}

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

			$cart_pn_sofortueberweisung_ID = $cartID . '-' . $insert_id;
			push_session_register('cart_pn_sofortueberweisung_ID');
		}

		return array('title' => MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_CONFIRMATION);
	}

	function process_button() {
		global $order, $cart, $customer_id, $currencies, $currency, $cart_pn_sofortueberweisung_ID, $redirect;

		$order_id = substr($cart_pn_sofortueberweisung_ID, strpos($cart_pn_sofortueberweisung_ID, '-')+1);

		$amount = number_format($order->info['total'] * $currencies->get_value($currency), 2, '.','');

		$reason_1 = str_replace('{{order_id}}', $order_id, MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1);
		$reason_1 = str_replace('{{customer_id}}', $customer_id, $reason_1);
		$reason_1 = push_output_string(substr($reason_1, 0, 27));
		
		$reason_2 = str_replace('{{order_id}}', $order_id, MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2);
		$reason_2 = str_replace('{{customer_id}}', $customer_id, $reason_2);
		$reason_2 = str_replace('{{order_date}}', strftime(DATE_FORMAT_SHORT), $reason_2);
		$reason_2 = str_replace('{{customer_name}}', $order->customer['firstname'] . ' ' . $order->customer['lastname'], $reason_2);
		$reason_2 = str_replace('{{customer_company}}', $order->customer['company'], $reason_2);
		$reason_2 = str_replace('{{customer_email}}', $order->customer['email_address'], $reason_2);
		$reason_2 = push_output_string(substr($reason_2, 0, 27));

		$user_variable_0 = push_output_string($order_id);
		$user_variable_1 = push_output_string($customer_id);

		$session = session_name() . '=' . session_id();

		if (ENABLE_SSL == true)
			$server = HTTpush_SERVER;
		else
			$server = HTTP_SERVER;

		//protocol is defined on server
		$server = str_replace('https://', '', $server);
		$server = str_replace('http://', '', $server);

		// success return url:
		$user_variable_2 = $server . DIR_WS_CATALOG . FILENAME_CHECKOUT_PROCESS . '?' . $session;
		// cancel return url:
		$user_variable_3 = $server . DIR_WS_CATALOG . FILENAME_CHECKOUT_PAYMENT . '?payment_error=pn_sofortueberweisung&' . $session;
		// notification url: (depricated)
		$user_variable_4 = $server . DIR_WS_CATALOG . 'ext/modules/payment/pn_sofortueberweisung/callback.php';
		$user_variable_5 =  push_output_string($cart->cartID);

		if($redirect === true)
			return $this->pnSofortueberweisung->getPaymentUrl(MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID,
				MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID, $amount, $currency, $reason_1, $reason_2, 
				$user_variable_0, $user_variable_1, $user_variable_2, $user_variable_3, $user_variable_4, $user_variable_5);
		else
			return '';
	}

	// Code from checkout_process
	function before_process() {
		global $customer_id, $order, $order_totals, $sendto, $billto, $languages_id, $payment, $currencies, $currency, $cart, $cart_pn_sofortueberweisung_ID;
		global $$payment;
		
		//fix bug in < osc 2.2 RC2 
		if(!is_array($order_totals)){
			include(DIR_WS_CLASSES . 'order_total.php');
			$order_total_modules = new order_total;
			$order_totals = $order_total_modules->process();
		}

		// Statusupdate nur wenn keine Cartänderung vorgenommen
		$order_total = number_format($order->info['total'] * $currencies->get_value($currency), 2, '.','');
		$amount = number_format($_GET['amount'], 2, '.','');
		$error = false;
		$error_text = '';
		$comment =  '';
		$order_id = substr($cart_pn_sofortueberweisung_ID, strpos($cart_pn_sofortueberweisung_ID, '-')+1);

		$sql_data_array = array('orders_id' => $order_id,
                              'orders_status_id' => (MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID > 0 ? (int)MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID : (int) DEFAULT_ORDERS_STATUS_ID),
                              'date_added' => 'now()',
                              'customer_notified' => (SEND_EMAILS == 'true') ? '1' : '0',
                              'comments' => $order->info['comments']);

		push_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

		$order_totals_table_beginn = '<table border="0" cellpadding="5" cellspacing="0">';
		$order_totals_zelle_beginn = '<tr><td width="280" style="font-size: 12px">';
		$order_totals_zelle_mitte = '</td><td style="font-size: 12px" align="right">';
		$order_totals_zelle_end = '</td></tr>';
		$order_totals_table_end = '</table>';
		
		// initialized for the email confirmation
		if (EMAIL_USE_HTML == 'true'){
		  $products_ordered = $order_totals_table_beginn;
		}
		else{
		  $products_ordered = '';
		}
		
		  $subtotal = 0;
		  $total_tax = 0;

		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
			// Stock Update - Joao Correia
			if (STOCK_LIMITED == 'true') {
				if (DOWNLOAD_ENABLED == 'true') {
					$stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                                  FROM " . TABLE_PRODUCTS . " p
                                  LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                  ON p.products_id=pa.products_id
                                  LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                  ON pa.products_attributes_id=pad.products_attributes_id
                                  WHERE p.products_id = '" . push_get_prid($order->products[$i]['id']) . "'";
					// Will work with only one option for downloadable products
					// otherwise, we have to build the query dynamically with a loop
					$products_attributes = $order->products[$i]['attributes'];
					if (is_array($products_attributes)) {
						$stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
					}
					$stock_query = push_db_query($stock_query_raw);
				} else {
					$stock_query = push_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . push_get_prid($order->products[$i]['id']) . "'");
				}
				if (push_db_num_rows($stock_query) > 0) {
					$stock_values = push_db_fetch_array($stock_query);
					// do not decrement quantities if products_attributes_filename exists
					if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
						$stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
					} else {
						$stock_left = $stock_values['products_quantity'];
					}
					push_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . push_get_prid($order->products[$i]['id']) . "'");
					if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
						push_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . push_get_prid($order->products[$i]['id']) . "'");
					}
				}
			}

			// Update products_ordered (for bestsellers list)
			push_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . push_get_prid($order->products[$i]['id']) . "'");

			//------insert customer choosen option to order--------
			$attributes_exist = '0';
			$products_ordered_attributes = '';
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

					$products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
				}
			}
	//------insert customer choosen option eof ----
	    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
	    $total_tax += push_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
	    $total_cost += $total_products_price;
	        if (EMAIL_USE_HTML == 'true'){
	          $products_ordered .= $order_totals_zelle_beginn . $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $order_totals_zelle_mitte . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . $order_totals_zelle_end;
	        }
	        else{
		  $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";	
	        }
	  }
	
	  
	
		 if (EMAIL_USE_HTML == 'true'){
		  $products_ordered .= $order_totals_table_end;
		}
		 if (EMAIL_USE_HTML == 'true')
		 	$text = preg_replace('#([\n\t])|([ ]+)#', ' ', MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_EMAIL_HTML_TEXT);
		 else
		 	$text = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_EMAIL_TEXT;
		 

		$text = str_replace('{{ORDER_ID}}', $order_id, $text);
		$text = str_replace('{{INVOICE_URL}}', push_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL', false), $text);
		$text = str_replace('{{DATE_ORDERED}}', strftime(DATE_FORMAT_LONG), $text);
		if ($order->info['comments']) {
			$text = str_replace('{{CUSTOMER_COMMENT}}', push_db_output($order->info['comments']), $text);
		}
		else{
		  	$text = str_replace('{{CUSTOMER_COMMENT}}', '', $text);
		}  
		$text = str_replace('{{Item_List}}', $products_ordered, $text);
		if (EMAIL_USE_HTML == 'true'){	
		    $list_total = $order_totals_table_beginn;
		    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
			$list_total .= $order_totals_zelle_beginn . strip_tags($order_totals[$i]['title']) . $order_totals_zelle_mitte . strip_tags($order_totals[$i]['text']) . $order_totals_zelle_end;
			}
		    $list_total .= $order_totals_table_end;
		}
		else{
		    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
			$list_total .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
			}
		}	
		$text = str_replace('{{List_Total}}', $list_total, $text);
		if ($order->content_type != 'virtual') {
			$text = str_replace('{{DELIVERY_ADRESS}}', push_address_label($customer_id, $sendto, 0, '', "\n"), $text);
		}
		elseif($order->content_type == 'virtual') {	
			if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && push_not_null($attributes_values['products_attributes_filename'])) {
			  $text = str_replace('{{DELIVERY_ADRESS}}', push_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL', false), $text);
			}
			else{
			  $text = str_replace('{{DELIVERY_ADRESS}}', '', $text);
			}	
		}
		else{
		  $text = str_replace('{{DELIVERY_ADRESS}}', '', $text);
		}
		$text = str_replace('{{BILLING_ADRESS}}', push_address_label($customer_id, $billto, 0, '', "\n"), $text);  
		  if (is_object($$payment)) {
		    $payment_class = $$payment;
		    $text = str_replace('{{Payment_Modul_Text}}', $payment_class->title, $text);
		    if ($payment_class->email_footer) { 
		      $text = str_replace('{{Payment_Modul_Text_Footer}}', $payment_class->email_footer, $text);
		    }
		 }
	 
		//replace all {{STORE_*}} {{OWNER_*}} {{EMAIL_*}} with defines
		//STORE_NAME STORE_NAME_ADDRESS OWNER_BANK_FA OWNER_BANK_TAX_NUMBER OWNER_BANK_UST_NUMBER OWNER_BANK_NAME OWNER_BANK_ACCOUNT STORE_OWNER_BLZ OWNER_BANK OWNER_BANK_SWIFT OWNER_BANK_IBAN 
		 $text = preg_replace("#\{\{((STORE_|OWNER_|EMAIL_)[A-Z0-9_]+)\}\}#e", '$1', $text);
	  
	  	$email_order = $text;
	  	$email_subject = MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_EMAIL_SUBJECT;
		$email_subject = preg_replace("#\{\{((STORE_|OWNER_|EMAIL_)[A-Z0-9_]+)\}\}#e", '$1', $email_subject);
		$email_subject = str_replace('{{ORDER_ID}}', $order_id, $email_subject);
		$email_subject = str_replace('{{DATE_ORDERED}}', strftime(DATE_FORMAT_LONG), $email_subject);
	  	

		push_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], $email_subject , $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

		// send emails to other people
		if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
			push_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $email_subject , $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		}

		// load the after_process function from the payment modules
		$this->after_process();

		$cart->reset(true);

		// unregister session variables used during checkout
		push_session_unregister('sendto');
		push_session_unregister('billto');
		push_session_unregister('shipping');
		push_session_unregister('payment');
		push_session_unregister('comments');
		if (push_session_is_registered('cart_pn_sofortueberweisung_ID'))
			push_session_unregister('cart_pn_sofortueberweisung_ID');

		push_redirect(push_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
	}

	function after_process() {
		return false;
	}

	function get_error() {
		$error = array('title' => MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_ERROR_HEADING,
                     'error' => MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_ERROR_MESSAGE);

		return $error;
	}


	function check() {
		if (!isset($this->_check)) {
			$check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS'");
			$this->_check = push_db_num_rows($check_query);

			if (defined('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS')	&& (MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS == 'True')) {
				//upgrade db
				$check_query = push_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD'");
				if(push_db_num_rows($check_query) < 1) {
					push_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD."', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD_DESC."', '6', '1', now());");
					push_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM', 'sha1', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM_DESC."', '6', '1', now());");
				}			
			}
		}
		return $this->_check;
	}

	function autoinstall() {
		global $pn_sofortueberweisung_pw, $pn_sofortueberweisung_pw2, $pn_sofortueberweisung_hashAlgorithm;
		
		$backlink = push_href_link(FILENAME_MODULES, 'set=payment&module=pn_sofortueberweisung&action=install');

		$header_redir_url = 'http://-USER_VARIABLE_2-';
		if (ENABLE_SSL == 'true' && strpos(HTTpush_SERVER, 'tps://') === 2) {
			$header_redir_url = 'https://-USER_VARIABLE_2-'; //
		}
		$html_abortlink = 'http://-USER_VARIABLE_3-';
		if (ENABLE_SSL == 'true' && strpos(HTTpush_SERVER, 'tps://') === 2) {
			$html_abortlink = 'https://-USER_VARIABLE_3-'; //
		}
		$alert_http_url = HTTP_SERVER . DIR_WS_CATALOG . 'ext/modules/payment/pn_sofortueberweisung/callback.php';
		if (ENABLE_SSL == 'true' && strpos(HTTpush_SERVER, 'tps://') === 2) {
			$alert_http_url = HTTpush_SERVER . DIR_WS_CATALOG . 'ext/modules/payment/pn_sofortueberweisung/callback.php';
		}
		

		$html = $this->pnSofortueberweisung->getAutoInstallPage(STORE_NAME, push_catalog_href_link(), 
					STORE_OWNER_EMAIL_ADDRESS, DEFAULT_LANGUAGE, DEFAULT_CURRENCY, 
					$html_abortlink, $header_redir_url, $alert_http_url, $backlink, 3);

		if (!push_session_is_registered('pn_sofortueberweisung_pw')) {
			push_session_register('pn_sofortueberweisung_pw');
			push_session_register('pn_sofortueberweisung_pw2');
			push_session_register('pn_sofortueberweisung_hashAlgorithm');
		}		
		
		$pn_sofortueberweisung_pw = $this->pnSofortueberweisung->password;
		$pn_sofortueberweisung_pw2 = $this->pnSofortueberweisung->password2;
		$pn_sofortueberweisung_hashAlgorithm = $this->pnSofortueberweisung->hashfunction;
		
		return $html;
	}

	function install() {
		global $pn_sofortueberweisung_pw, $pn_sofortueberweisung_pw2, $pn_sofortueberweisung_hashAlgorithm;

		if (isset($_GET['autoinstall']) && ($_GET['autoinstall'] == '1')) {
			// Module already installed
			if (defined('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS') && (MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS == 'True')) {
				push_redirect(push_href_link(FILENAME_MODULES, 'set=payment&module=pn_sofortueberweisung', 'SSL'));
			}
			print $this->autoinstall();
			exit();
		} else {

			$error = false;
			$defaultLang = '';
			$languages = push_get_languages();
			foreach ($languages as $language) {
				if ($language['code'] == DEFAULT_LANGUAGE) {
					$defaultLang = $language['directory'];
					break;
				}
			}
			$defaultLangFile = '../' . DIR_WS_LANGUAGES . $defaultLang . '/modules/payment/' . $this->code . '.php';
			$englishLangFile = '../' . DIR_WS_LANGUAGES . 'english/modules/payment/' . $this->code . '.php';

			if (file_exists($defaultLangFile))
				require_once($defaultLangFile);
			else {
				if  (file_exists($englishLangFile))
				require_once($englishLangFile);
				else $error = true;
			}

			if ($error)
				printf('Failed to install module %s. Language files missing!<br>', $this->code);
			else {
					
				$user_id = (!empty($_GET['user_id'])) ? push_db_prepare_input($_GET['user_id']) : '10000';
				$project_id = (!empty($_GET['project_id'])) ? push_db_prepare_input($_GET['project_id']) : '500000';

				if (push_session_is_registered('pn_sofortueberweisung_pw') && !empty($pn_sofortueberweisung_pw)) {
					$project_password = $pn_sofortueberweisung_pw;
					push_session_unregister('pn_sofortueberweisung_pw');
				} else $project_password = '';
				if (push_session_is_registered('pn_sofortueberweisung_pw2') && !empty($pn_sofortueberweisung_pw2)) {
					$project_password2 = $pn_sofortueberweisung_pw2;
					push_session_unregister('pn_sofortueberweisung_pw2');
				} else $project_password2 = '';
				if (push_session_is_registered('pn_sofortueberweisung_hashAlgorithm') && !empty($pn_sofortueberweisung_hashAlgorithm)) {
					$hashAlgorithm = $pn_sofortueberweisung_hashAlgorithm;
					push_session_unregister('pn_sofortueberweisung_hashAlgorithm');
				} else $hashAlgorithm = $this->pnSofortueberweisung->getSupportedHashAlgorithm();
				
				$zonetitle = str_replace('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM', $hashAlgorithm, MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE_TITLE);
				
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS', 'True', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS_DESC."', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now());");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID', '" . (int)$user_id . "', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID_DESC."', '6', '1', now());");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID', '" . (int)$project_id . "', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID_DESC."', '6', '1', now());");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD', '" . push_db_input($project_password) . "', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD_DESC."', '6', '1', now());");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD', '" . push_db_input($project_password2) . "', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD_DESC."', '6', '1', now());");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM', '" . push_db_input($hashAlgorithm) . "', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM_DESC."', '6', '1', now());");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER', '1', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER_DESC."', '6', '0', now())");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('".$zonetitle."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE', '0', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE_DESC."', '6', '2', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID', '".DEFAULT_ORDERS_STATUS_ID."', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID_DESC."', '6', '0', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID', '".DEFAULT_ORDERS_STATUS_ID."', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID_DESC."', '6', '0', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_UNC_STATUS_ID_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_UNC_STATUS_ID', '".DEFAULT_ORDERS_STATUS_ID."', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_UNC_STATUS_ID_DESC."', '6', '0', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1', 'Nr. {{order_id}} Kd-Nr. {{customer_id}}', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1_DESC."', '6', '1', 'push_cfg_select_option(array(\'Nr. {{order_id}} Kd-Nr. {{customer_id}}\',\'-TRANSACTION-\'), ', now())");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2', '".push_db_input(STORE_NAME)."', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2_DESC."', '6', '1', now());");
				push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE_TITLE."', 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE', 'Logo & Text', '".MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE_DESC."', '6', '6', 'push_cfg_select_option(array(\'Infographic\',\'Logo & Text\',\'Logo\'), ', now())");
			}

		} // normal install
	}
	

	function remove() {
		push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
		push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM'");
	}

	function keys() {
		return array('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD',
				'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD', 
				'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_UNC_STATUS_ID',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID',
      	      	'MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER');
	}


	// push_remove_order() in admin/includes/functions/general.php
	function _remove_order($order_id, $restock = false) {
		if ($restock == 'on') {
			$order_query = push_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
			while ($order = push_db_fetch_array($order_query)) {
				push_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
			}
		}

		push_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
		push_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
		push_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "'");
		push_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$order_id . "'");
		push_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "'");
	}

}

?>