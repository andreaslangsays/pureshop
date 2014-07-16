<?php
/*
  $Id: invoice.php,v 1.25 2003/02/19 02:14:00 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  class invoice {
    var $code, $title, $description, $enabled;

// class constructor
    function invoice() {
      global $order, $bt_array;

      $this->code = 'invoice';
      $this->title = MODULE_PAYMENT_INVOICE_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_INVOICE_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_INVOICE_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_INVOICE_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }

// class methods
    function update_status() {
      global $order, $customer_id;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_INVOICE_ZONE > 0) ) {
        $check_flag = false;
        $check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_INVOICE_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
		  // check banktransfer after x times
	  if (MODULE_PAYMENT_INVOICE_FROM_ORDER > 0){
			$test_query = push_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id='" . $customer_id . "' AND orders_status = 3" );
			$result = push_db_fetch_array($test_query);
			$total = $result['total'];
			
			if ( $total > MODULE_PAYMENT_INVOICE_FROM_ORDER ) {
				$this->enabled = true;
			}else{
				$this->enabled = false;
			}
		}
      }

	  // start änderung
	  $test_query = push_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id='".$customer_id."' AND orders_status>'" . MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID . "'");
	  $result = push_db_fetch_array($test_query);

	  $inv_query = push_db_query("select customers_invoice from " . TABLE_CUSTOMERS . " where customers_id='".$customer_id."'");
	  $inv_info = push_db_fetch_array($inv_query);

  $inv_check = $inv_info['customers_invoice'];
  switch ($inv_check) {
      case 0:

	  $total = $result['total'];
	  if (($total+1) < MODULE_PAYMENT_INVOICE_FROM_ORDER) {
		$this->enabled = false;
		}
      break;
      case 1:
      if (($inv_info['customers_invoice']) < 1) { 
      $this->enabled = false; 
     }
      break;
	 }
	  // ende änderung

// disable the module if the order only contains virtual products
	  // start änderung
      /*if ($this->enabled == true) {
        if ($order->content_type == 'virtual') {
          $this->enabled = false;
        }
      }*/
	  // ende änderung
    }
	

// class methods
    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check(){
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_INVOICE_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Rechnung', 'MODULE_PAYMENT_INVOICE_STATUS', 'True', 'Wollen Sie Zahlungen per Rechnung anbieten?', '6', '0', 'push_cfg_select_option(array(\'True\', \'False\'), ', now());");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_INVOICE_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Reihenfolge der Anzeige', 'MODULE_PAYMENT_INVOICE_SORT_ORDER', '0', 'Niedrigste wird zuerst angezeigt.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
	  // start änderung
	  push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Stammkunden', 'MODULE_PAYMENT_INVOICE_FROM_ORDER', '3', 'Rechnung ab x-ter Bestellung möglich', '6', '0', now())");
	  // ende änderung
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
	  // start änderung
      /*return array('MODULE_PAYMENT_INVOICE_STATUS', 'MODULE_PAYMENT_INVOICE_ZONE', 'MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID', 'MODULE_PAYMENT_INVOICE_SORT_ORDER');*/
	  return array('MODULE_PAYMENT_INVOICE_STATUS', 'MODULE_PAYMENT_INVOICE_ZONE', 'MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID', 'MODULE_PAYMENT_INVOICE_SORT_ORDER',
	  'MODULE_PAYMENT_INVOICE_FROM_ORDER');
	  // ende änderung
    }
  }
?>