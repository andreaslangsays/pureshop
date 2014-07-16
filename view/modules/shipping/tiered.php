<?php
/*
$Id: tiered v 1.0 09/12/2004
Developed by Dave Ferrise (dave@ferrisedesign.com)
Based on: tiered.phps,v 2.2 2003/05/03 modified:WebyMaster-TWM dgw_ Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2001,2002, 2003, 2004 osCommerce

Released under the GNU General Public License
*/
class tiered {
var $code, $title, $description, $icon, $enabled;

// class constructor
function tiered() {
	global $order;
	
	$this->code = 'tiered';
	$this->title = MODULE_SHIPPING_TIERED_TEXT_TITLE;
	$this->description = MODULE_SHIPPING_TIERED_TEXT_DESCRIPTION;
	$this->sort_order = MODULE_SHIPPING_TIERED_SORT_ORDER;
	$this->icon = '';
	$this->tax_class = MODULE_SHIPPING_TIERED_TAX_CLASS;
	$this->enabled = ((MODULE_SHIPPING_TIERED_STATUS == 'True') ? true : false);

	if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_TIERED_ZONE > 0) ) {
		$check_flag = false;
		$check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_TIERED_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
		while ($check = push_db_fetch_array($check_query)) {
			if ($check['zone_id'] < 1) {
				$check_flag = true;
				break;
			} elseif ($check['zone_id'] == $order->delivery['zone_id']) {
				$check_flag = true;
				break;
			}
		}

		if ($check_flag == false) {
			$this->enabled = false;
			}
		}
	}

// class methods
	function quote($method = '') {
		global $order, $cart;

		if (MODULE_SHIPPING_TIERED_STATUS == 'True') {
			$order_total = $cart->show_total();
		}
		if ($order_total < MODULE_SHIPPING_TIERED_LEVEL_2) {
			$shipping_tiered = MODULE_SHIPPING_TIERED_RATE_1;
		}
		else {
			if ($order_total < MODULE_SHIPPING_TIERED_LEVEL_3) {
				$shipping_tiered = MODULE_SHIPPING_TIERED_RATE_2;
		}
		else {
			$shipping_tiered = MODULE_SHIPPING_TIERED_RATE_3;
		}
	}
	$this->quotes = array('id' => $this->code,
	'module' => MODULE_SHIPPING_TIERED_TEXT_TITLE,
	'methods' => array(array('id' => $this->code,
	'title' => MODULE_SHIPPING_TIERED_TEXT_WAY,
	'cost' => $shipping_tiered)));

	if ($this->tax_class > 0) {
		$this->quotes['tax'] = push_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
	}

	if (push_not_null($this->icon)) $this->quotes['icon'] = push_image($this->icon, $this->title);
		return $this->quotes;
	}

	function check() {
		if (!isset($this->_check)) {
			$check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TIERED_STATUS'");
			$this->_check = push_db_num_rows($check_query);
		}
		return $this->_check;
	}

	function install() {
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Tiered Shipping', 'MODULE_SHIPPING_TIERED_STATUS', 'True', 'Do you want to offer tiered rate shipping?', '6', '0', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Tier 1 Shipping Rate', 'MODULE_SHIPPING_TIERED_RATE_1', '15.00', 'The shipping cost for all orders totalling less than tier 2 Order Level.', '6', '0', now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Tier 2 Order Total Level', 'MODULE_SHIPPING_TIERED_LEVEL_2', '50.00', 'Order total qualifying for second tier shipping rate.', '6', '0', now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Tier 2 Shipping Rate', 'MODULE_SHIPPING_TIERED_RATE_2', '7.50', 'The shipping cost for all orders totalling more than tier 2 value and less than tier 3 value.', '6', '0', now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Tier 3 Order Total Level', 'MODULE_SHIPPING_TIERED_LEVEL_3', '100.00', 'Order total qualifying for third tier shipping rate.', '6', '0', now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Tier 3 Shipping Rate', 'MODULE_SHIPPING_TIERED_RATE_3', '0.00', 'The shipping cost for all orders totalling more than tier 3 value.', '6', '0', now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_TIERED_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'push_get_tax_class_title', 'push_cfg_pull_down_tax_classes(', now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_TIERED_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
		push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_TIERED_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
	}

	function remove() {
		push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
		return array('MODULE_SHIPPING_TIERED_STATUS', 'MODULE_SHIPPING_TIERED_RATE_1', 'MODULE_SHIPPING_TIERED_LEVEL_2', 'MODULE_SHIPPING_TIERED_RATE_2', 'MODULE_SHIPPING_TIERED_LEVEL_3', 'MODULE_SHIPPING_TIERED_RATE_3', 'MODULE_SHIPPING_TIERED_TAX_CLASS', 'MODULE_SHIPPING_TIERED_ZONE', 'MODULE_SHIPPING_TIERED_SORT_ORDER');
	}
}
?>