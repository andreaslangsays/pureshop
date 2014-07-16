<?php
/*
  Shipping Insurance Module
  ot_insurance.php, v 1.0 2003/12/03
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class ot_insurance {
    var $title, $output;

    function ot_insurance() {
      $this->code = 'ot_insurance';
      $this->title = MODULE_ORDER_TOTAL_INSURANCE_TITLE;
      $this->description = MODULE_ORDER_TOTAL_INSURANCE_DESCRIPTION;
      $this->enabled = MODULE_ORDER_TOTAL_INSURANCE_STATUS;
      $this->sort_order = MODULE_ORDER_TOTAL_INSURANCE_SORT_ORDER;
	  $this->multiplier = MODULE_ORDER_TOTAL_INSURANCE_INT_MULT;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;

			$choose_insurance = $_SESSION['choose_insurance'];
 	  
  	  if (MODULE_ORDER_TOTAL_INSURANCE_STATUS == 'true') {
  	    switch (MODULE_ORDER_TOTAL_INSURANCE_DESTINATION) {
  	      case 'national':
  	        if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
  	      case 'international':
  	        if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
  	      case 'both':
  	        $pass = true; break;
  	      default:
  	        $pass = false; break;
  	    }
  	  }
			if(($choose_insurance != '1') && (MODULE_ORDER_TOTAL_INSURANCE_USE == 'true')){
				$pass = false;
      }
			// Added in by Juan Velez to stop any negative amount
			if ($order->info['total'] < MODULE_ORDER_TOTAL_INSURANCE_OVER) {
				$pass = false;
			}
			// End of add by Juan Velez
        if ($pass == true) {
          $tax = push_get_tax_rate(MODULE_ORDER_TOTAL_INSURANCE_TAX_CLASS);
					$how_often = ceil(($order->info['total']- $order->info['tax'] - MODULE_ORDER_TOTAL_INSURANCE_OVER)/MODULE_ORDER_TOTAL_INSURANCE_INCREMENT);
          $order->info['tax'] += push_calculate_tax((MODULE_ORDER_TOTAL_INSURANCE_FEE * $how_often), $tax);
          $order->info['tax_groups']["{$tax}"] += push_calculate_tax((MODULE_ORDER_TOTAL_INSURANCE_FEE * $how_often), $tax);

//variable $how_often is the amount of times to multiply the insurance rate.
//variable $this_amount becomes the total insurance fee once multiplied by $how_often below.
					$this_amount = MODULE_ORDER_TOTAL_INSURANCE_FEE * $how_often;
   				if ($this_amount < MODULE_ORDER_TOTAL_INSURANCE_MIN_CHARGE ) {
   					$this_amount = MODULE_ORDER_TOTAL_INSURANCE_MIN_CHARGE;
   				}
				
// If international shipment, multiply insurance charge by multiplier
				if ($order->delivery['country_id'] != STORE_COUNTRY) {
				  $this_amount *= $this->multiplier;
				}
			   		 
          $order->info['total'] += $this_amount + push_calculate_tax(MODULE_ORDER_TOTAL_INSURANCE_FEE, $tax);
   
          $this->output[] = array('title' => $this->title . ':',
                                  'text' => $currencies->format(push_add_tax(($this_amount), $tax), true, $order->info['currency'], $order->info['currency_value']),
                                  'value' => push_add_tax(($this_amount), $tax));
        }
      }

    function check() {
      if (!isset($this->check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_INSURANCE_STATUS'");
        $this->check = push_db_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_INSURANCE_STATUS', 'MODULE_ORDER_TOTAL_INSURANCE_SORT_ORDER', 'MODULE_ORDER_TOTAL_INSURANCE_USE', 'MODULE_ORDER_TOTAL_INSURANCE_OVER', 'MODULE_ORDER_TOTAL_INSURANCE_INCREMENT', 'MODULE_ORDER_TOTAL_INSURANCE_FEE', 'MODULE_ORDER_TOTAL_INSURANCE_MIN_CHARGE', 'MODULE_ORDER_TOTAL_INSURANCE_DESTINATION', 'MODULE_ORDER_TOTAL_INSURANCE_TAX_CLASS', 'MODULE_ORDER_TOTAL_INSURANCE_INT_MULT');
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Shipping Insurance', 'MODULE_ORDER_TOTAL_INSURANCE_STATUS', 'true', 'Do you want to offer Shipping Insurance?', '6', '1','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_INSURANCE_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Chooses?', 'MODULE_ORDER_TOTAL_INSURANCE_USE', 'false', 'Do you want the customer to have the choice?', '6', '3', 'push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Amount Exempt From Fee', 'MODULE_ORDER_TOTAL_INSURANCE_OVER', '100', 'At what total amount do you start charging insurance?  For example, UPS insures amounts up to $100, so that is what you would put here.', '6', '4', 'currencies->format', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Increment Amount', 'MODULE_ORDER_TOTAL_INSURANCE_INCREMENT', '100', 'For each <b>how many dollars,</b> ie. the increment amount,  of the total (e.g. 100 here and .40 for the rate below would mean 40 cents fee for every $100 of the amount to be insured).', '6', '5', 'currencies->format', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Insurance Rate', 'MODULE_ORDER_TOTAL_INSURANCE_FEE', '.40', 'The amount charged per Increment Amount above.', '6', '6', 'currencies->format', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Minimum Charge', 'MODULE_ORDER_TOTAL_INSURANCE_MIN_CHARGE', '1.20', 'The minimum amount to be charged if order is over minimum total.', '6', '7', 'currencies->format', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Apply Insurance Fee To Which Orders', 'MODULE_ORDER_TOTAL_INSURANCE_DESTINATION', 'both', 'Apply insurance fee for orders sent to the set destination.', '6', '8', 'push_cfg_select_option(array(\'national\', \'international\', \'both\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_INSURANCE_TAX_CLASS', '0', 'Use the following tax class on the insurance fee.', '6', '9', 'push_get_tax_class_title', 'push_cfg_pull_down_tax_classes(', now())");
	  push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('International Multiplier', 'MODULE_ORDER_TOTAL_INSURANCE_INT_MULT', '1', 'For International Orders, multiply the total insurance cost by this number:', '6', '10', now())");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }
  }
?>