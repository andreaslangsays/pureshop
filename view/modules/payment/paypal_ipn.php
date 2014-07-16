<?php
/*
  $Id: paypal_ipn.php,v 2.1.0.0 13/01/2007 16:30:28 Edith Karnitsch Exp $

  Copyright (c) 2004 osCommerce
  Released under the GNU General Public License

  Original Authors: Harald Ponce de Leon, Mark Evans
  Updates by PandA.nl, Navyhost, Zoeticlight, David, gravyface, AlexStudio, windfjf and Terra

*/

  class paypal_ipn {
    var $code, $title, $description, $enabled, $identifier;

// class constructor
    function paypal_ipn() {
      global $order;

      $this->code = 'paypal_ipn';
      $this->title = MODULE_PAYMENT_PAYPAL_IPN_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYPAL_IPN_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PAYPAL_IPN_STATUS == 'True') ? true : false);
      $this->email_footer = MODULE_PAYMENT_PAYPAL_IPN_TEXT_EMAIL_FOOTER;
      $this->identifier = 'osCommerce PayPal IPN v2.1';
	  $this->index;

      if ((int)MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      if (MODULE_PAYMENT_PAYPAL_IPN_GATEWAY_SERVER == 'Live') {
        $this->form_action_url = 'https://www.paypal.com/cgi-bin/webscr';
      } else {
        $this->form_action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
      }
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYPAL_IPN_ZONE > 0) ) {
        $check_flag = false;
        $check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYPAL_IPN_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
      return array('id' => $this->code,
                   'module' => $this->title,
                   'fields' => array(array('title' => MODULE_PAYMENT_PAYPAL_IPN_TEXT_DESCRIPTION)));
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      global $cartID, $scart_PayPal_IPN_ID, $languages_id, $order, $order_total_modules;

     // if (push_session_is_registered('cartID')) {
	 // PandA.nl: register_globals fix
     if (array_key_exists('cartID', $_SESSION)) {
        $insert_order = false;

        if (push_session_is_registered('cart_PayPal_IPN_ID')) {
          $order_id = substr($scart_PayPal_IPN_ID, strpos($scart_PayPal_IPN_ID, '-')+1);

          $curr_check = push_db_query("select currency from " . TABLE_ORDERS . " where orders_id = '" . $order_id . "'");
          $curr = push_db_fetch_array($curr_check);

           //if ( ($curr['currency'] != $order->info['currency']) || ($cartID != substr($scart_PayPal_IPN_ID, 0, strlen($cartID))) ) {
            //$check_query = push_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . $order_id . '" limit 1');
			$check_query = push_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . $order_id . '" limit 2');  //1.4

            $update_order = false;//1.4

			//if (push_db_num_rows($check_query) < 1) {
              //push_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . $order_id . '"');
			if (push_db_num_rows($check_query) == 1) { //1.4
			  //$update_order = true; //1.4
				push_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . $order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . $order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . $order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . $order_id . '"');
				push_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . $order_id . '"');
            }

            $insert_order = true;
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

			$oID = push_generate_order_id('P');
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
		    push_db_perform(TABLE_ORDERS, $sql_data_array, 'update');
            $insert_id = (int)$order_id;
		  } else {
		    //-1.4
          push_db_perform(TABLE_ORDERS, $sql_data_array);
          $insert_id = $oID;
		 //1.4
		}
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
          push_session_register('cart_PayPal_IPN_ID');
          // Terra register globals fix
          $_SESSION['cart_PayPal_IPN_ID'] = $cartID . '-' . $insert_id;
        }
      }

      return false;
    }

    function process_button() {
      global  $order, $languages_id, $currencies, $currency, $scart_PayPal_IPN_ID, $shipping;

      if (MODULE_PAYMENT_PAYPAL_IPN_CURRENCY == 'Selected Currency') {
        $my_currency = $currency;
      } else {
        $my_currency = substr(MODULE_PAYMENT_PAYPAL_IPN_CURRENCY, 5);
      }

      if (!in_array($my_currency, array('AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'))) {
        $my_currency = 'USD';
      }

      $parameters = array();

      if ( (MODULE_PAYMENT_PAYPAL_IPN_TRANSACTION_TYPE == 'Per Item') && (MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS == 'False') ) {
        $parameters['cmd'] = '_cart';
        $parameters['upload'] = '1';

        for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
          $item = $i+1;

          $tax_value = ($order->products[$i]['tax'] / 100) * $order->products[$i]['final_price'];

          $parameters['item_name_' . $item] = $order->products[$i]['name'];
          $parameters['amount_' . $item] = number_format($order->products[$i]['final_price'] * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
          $parameters['tax_' . $item] = number_format($tax_value * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
          $parameters['quantity_' . $item] = $order->products[$i]['qty'];

          if ($i == 0) {
            if (DISPLAY_PRICE_WITH_TAX == 'true') {
              $shipping_cost = $order->info['shipping_cost'];
            } else {
              $module = substr($shipping['id'], 0, strpos($shipping['id'], '_'));
              $shipping_tax = push_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
              $shipping_cost = $order->info['shipping_cost'] + push_calculate_tax($order->info['shipping_cost'], $shipping_tax);
            }

            $parameters['shipping_' . $item] = number_format($shipping_cost * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
          }

          if (isset($order->products[$i]['attributes'])) {
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

// Unfortunately PayPal only accepts two attributes per product, so the
// third attribute onwards will not be shown at PayPal
              $parameters['on' . $j . '_' . $item] = $attributes_values['products_options_name'];
              $parameters['os' . $j . '_' . $item] = $attributes_values['products_options_values_name'];
            }
          }
        }

        $parameters['num_cart_items'] = $item;

              if(MOVE_TAX_TO_TOTAL_AMOUNT == 'True') {
           // PandA.nl move tax to total amount
           $parameters['amount'] = number_format(($order->info['total'] - $order->info['shipping_cost']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
           } else {
           // default
          $parameters['amount'] = number_format(($order->info['total'] - $order->info['shipping_cost'] - $order->info['tax']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
      }

      } else {
        $parameters['cmd'] = '_ext-enter';
        $parameters['redirect_cmd'] = '_xclick';
        $parameters['item_name'] = utf8_decode(STORE_NAME);
        $parameters['shipping'] = '0';
      if(MOVE_TAX_TO_TOTAL_AMOUNT == 'True') {
           // PandA.nl move tax to total amount
           $parameters['amount'] = number_format($order->info['total'] * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
           } else {
           // default
          $parameters['amount'] = number_format(($order->info['total'] - $order->info['tax']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
      }
       }

       // billing information fix by gravyface
       // for pre-populating the fiels if customer has no PayPal account
       // only works if force shipping address is set to FALSE
      $state_abbr = push_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], $order->delivery['state']);
      $name = $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];

      $parameters['business'] = MODULE_PAYMENT_PAYPAL_IPN_ID;

      // let's check what has been defined in the shop admin for the shipping address
      if (MODULE_PAYMENT_PAYPAL_IPN_SHIPPING == 'True') {
      // all that matters is that we send the variables
      // what they contain is irrelevant as PayPal overwrites it with the customer's confirmed PayPal address
      // so what we send is probably not what we'll get back
      $parameters['no_shipping'] = '2';
      $parameters['address_name'] 		= $name;
      $parameters['address_street'] 	= $order->delivery['street_address'];
      $parameters['address_city'] 		= $order->delivery['city'];
      $parameters['address_zip'] 		= $order->delivery['postcode'];
      $parameters['address_state'] 		= $state_abbr;
      $parameters['address_country_code']	= $order->delivery['country']['iso_code_2'];
      $parameters['address_country']	= $order->delivery['country']['title'];
      $parameters['payer_email'] 		= $order->customer['email_address'];
     } else {
      $parameters['no_shipping'] = '1';
      $parameters['H_PhoneNumber'] 	      = $order->customer['telephone'];
      $parameters['first_name'] 		= $order->delivery['firstname'];
      $parameters['last_name'] 		= $order->delivery['lastname'];
      $parameters['address1'] 		= $order->delivery['street_address'];
      $parameters['address2'] 		= $order->delivery['suburb'];
      $parameters['city'] 			= $order->delivery['city'];
      $parameters['zip'] 			= $order->delivery['postcode'];
      $parameters['state'] 			= $state_abbr;
      $parameters['country'] 			= $order->delivery['country']['iso_code_2'];
      $parameters['email'] 			= $order->customer['email_address'];
          }
	  $parameters['on0'] 		= 'Kommentar:' . $order->info['comments'];
	  $parameters['memo'] 		= 'Kommentar:' . $order->info['comments'];
	  $parameters['meinung'] 		= 'Kommentar:' . $order->info['comments'];
//	  $parameters['on0'] 		= 'Kommentar:';
//	  $parameters['os0'] 		= $order->info['comments'];
      $parameters['currency_code'] = $my_currency;
      $parameters['invoice'] = substr($scart_PayPal_IPN_ID, strpos($scart_PayPal_IPN_ID, '-')+1);
      $parameters['custom'] = $_SESSION['customer_id'];
      $parameters['no_note'] = '0';
      $parameters['notify_url'] = push_href_link('ext/modules/payment/paypal_ipn/ipn.php', '', 'SSL', false, false);
      $parameters['cbt'] = CONFIRMATION_BUTTON_TEXT;
      $parameters['return'] = push_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
      $parameters['cancel_return'] = push_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');
      $parameters['bn'] = $this->identifier;
      $parameters['lc'] = $order->customer['country']['iso_code_2'];
	//$parameters['token'] ="jKxW3adIucYLB3f-Wk4S2eB8JGJauzGwjMXlsIRAMfdmgAAY3q50Z1XP-7O";

      if (push_not_null(MODULE_PAYMENT_PAYPAL_IPN_PAGE_STYLE)) {
        $parameters['page_style'] = MODULE_PAYMENT_PAYPAL_IPN_PAGE_STYLE;
      }

      if (MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS == 'True') {
        $parameters['cert_id'] = MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID;

        $random_string = rand(100000, 999999) . '-' . $_SESSION['customer_id'] . '-';

        $data = '';
        while (list($key, $value) = each($parameters)) {
          $data .= $key . '=' . $value . "\n";
        }

        $fp = fopen(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt', 'w');
        fwrite($fp, $data);
        fclose($fp);

        unset($data);

        if (function_exists('openssl_pkcs7_sign') && function_exists('openssl_pkcs7_encrypt')) {
          openssl_pkcs7_sign(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt', MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt', file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY), file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY), array('From' => MODULE_PAYMENT_PAYPAL_IPN_ID), PKCS7_BINARY);

          unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt');

// remove headers from the signature
          $signed = file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt');
          $signed = explode("\n\n", $signed);
          $signed = base64_decode($signed[1]);

          $fp = fopen(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt', 'w');
          fwrite($fp, $signed);
          fclose($fp);

          unset($signed);

          openssl_pkcs7_encrypt(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt', MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt', file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY), array('From' => MODULE_PAYMENT_PAYPAL_IPN_ID), PKCS7_BINARY);

          unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt');

// remove headers from the encrypted result
          $data = file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt');
          $data = explode("\n\n", $data);
          $data = '-----BEGIN PKCS7-----' . "\n" . $data[1] . "\n" . '-----END PKCS7-----';

          unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt');
        } else {
          exec(MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL . ' smime -sign -in ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt -signer ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY . ' -inkey ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY . ' -outform der -nodetach -binary > ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt');
          unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt');

          exec(MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL . ' smime -encrypt -des3 -binary -outform pem ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY . ' < ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt > ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt');
          unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt');

          $fh = fopen(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt', 'rb');
          $data = fread($fh, filesize(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt'));
          fclose($fh);

          unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt');
        }

        $process_button_string = push_draw_hidden_field('cmd', '_s-xclick') .
                                 push_draw_hidden_field('encrypted', $data);

        unset($data);
      } else {
        while (list($key, $value) = each($parameters)) {
          echo push_draw_hidden_field($key, $value);
        }
      }

      return $process_button_string;
    }

	function before_process() {
		global $cart,$order_total_modules;
		$cart->reset(true);
// unregister session variables used during checkout
		push_session_unregister('sendto');
		push_session_unregister('billto');
		push_session_unregister('shipping');
		push_session_unregister('payment');
		push_session_unregister('comments');
		push_session_unregister('cart_PayPal_IPN_ID');
		if(push_session_is_registered('credit_covers'))
		{
			push_session_unregister('credit_covers');// CCGV
		}

		if(is_object($order_total_modules))
		{
			$order_total_modules->clear_posts();// CCGV
		}
		
		include(DIR_WS_CLASSES ."xml_order.php");
		$xo = new xml_order($_POST['invoice']);
		$xo->generate_xml();
		$xo->send_xml();
		push_redirect(push_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $_POST['invoice'] , 'SSL'));
	}

    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_IPN_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      $check_query = push_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Preparing [PayPal IPN]' limit 1");

      if (push_db_num_rows($check_query) < 1) {
        $status_query = push_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
        $status = push_db_fetch_array($status_query);

        $status_id = $status['status_id']+1;

        $languages = push_get_languages();

        foreach ($languages as $lang) {
          push_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'Preparing [PayPal IPN]')");
        }
      } else {
        $check = push_db_fetch_array($check_query);

        $status_id = $check['orders_status_id'];
      }

      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayPal IPN Module', 'MODULE_PAYMENT_PAYPAL_IPN_STATUS', 'False', 'Do you want to accept PayPal IPN payments?', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
            push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Gateway Server', 'MODULE_PAYMENT_PAYPAL_IPN_GATEWAY_SERVER', 'Testing', 'Use the testing (sandbox) or live gateway server for transactions?', '6', '2', 'push_cfg_select_option(array(\'Testing\',\'Live\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
            push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Force shipping address?', 'MODULE_PAYMENT_PAYPAL_IPN_SHIPPING', 'False', 'If TRUE the address details for the PayPal Seller Protection Policy are sent but customers without a PayPal account must re-enter their details. If set to FALSE order is not eligible for Seller Protection but customers without acount will have their address fiels pre-populated.', '6', '4', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'MODULE_PAYMENT_PAYPAL_IPN_ID', '', 'The e-mail address to use for the PayPal IPN service', '6', '5', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_IPN_CURRENCY', 'Selected Currency', 'The currency to use for transactions', '6', '10', 'push_cfg_select_option(array(\'Selected Currency\',\'Only AUD\',\'Only CAD\',\'Only CHF\',\'Only CZK\',\'Only DKK\',\'Only EUR\',\'Only GBP\',\'Only HKD\',\'Only HUF\',\'Only JPY\',\'Only NOK\',\'Only NZD\',\'Only PLN\',\'Only SEK\',\'Only SGD\',\'Only USD\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_IPN_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '11', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Preparing Order Status', 'MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID', '" . $status_id . "', 'Set the status of prepared orders made with this payment module to this value', '6', '12', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set PayPal Acknowledged Order Status', 'MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '13', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
            push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set PayPal Completed Order Status', 'MODULE_PAYMENT_PAYPAL_IPN_COMP_ORDER_STATUS_ID', '0', 'Set the status of orders which are confirmed as paid (completed) to this value', '6', '13', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Type', 'MODULE_PAYMENT_PAYPAL_IPN_TRANSACTION_TYPE', 'Aggregate', 'Send individual items to PayPal or aggregate all as one total item?', '6', '14', 'push_cfg_select_option(array(\'Per Item\',\'Aggregate\'), ', now())");
      // bof PandA.nl move tax to total amount
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Move tax to total amount', 'MOVE_TAX_TO_TOTAL_AMOUNT', 'True', 'Do you want to move the tax to the total amount? If true PayPal will allways show the total amount including tax. (needs Aggregate instead of Per Item to function)', '6', '15', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
      // eof PandA.nl move tax to total amount
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Page Style', 'MODULE_PAYMENT_PAYPAL_IPN_PAGE_STYLE', '', 'The page style to use for the transaction procedure (defined at your PayPal Profile page)', '6', '20', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Debug E-Mail Address', 'MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL', '', 'All parameters of an Invalid IPN notification will be sent to this email address if one is entered.', '6', '21', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Encrypted Web Payments', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS', 'False', 'Do you want to enable Encrypted Web Payments?', '6', '30', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your Private Key', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY', '', 'The location of your Private Key to use for signing the data. (*.pem)', '6', '31', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your Public Certificate', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY', '', 'The location of your Public Certificate to use for signing the data. (*.pem)', '6', '32', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPals Public Certificate', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY', '', 'The location of the PayPal Public Certificate for encrypting the data.', '6', '33', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your PayPal Public Certificate ID', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID', '', 'The Certificate ID to use from your PayPal Encrypted Payment Settings Profile.', '6', '34', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Working Directory', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY', '', 'The working directory to use for temporary files. (trailing slash needed)', '6', '35', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('OpenSSL Location', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL', '/usr/bin/openssl', 'The location of the openssl binary file.', '6', '36', now())");

    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

   function keys() {
    // PandA.nl move tax to total amount added: ", 'MOVE_TAX_TO_TOTAL_AMOUNT'"
     return array('MODULE_PAYMENT_PAYPAL_IPN_STATUS', 'MODULE_PAYMENT_PAYPAL_IPN_GATEWAY_SERVER', 'MODULE_PAYMENT_PAYPAL_IPN_ID', 'MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER', 'MODULE_PAYMENT_PAYPAL_IPN_CURRENCY', 'MODULE_PAYMENT_PAYPAL_IPN_ZONE', 'MODULE_PAYMENT_PAYPAL_IPN_SHIPPING', 'MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_IPN_COMP_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_IPN_TRANSACTION_TYPE', 'MOVE_TAX_TO_TOTAL_AMOUNT', 'MODULE_PAYMENT_PAYPAL_IPN_PAGE_STYLE', 'MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL',  'MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL');
   }
  }
?>
