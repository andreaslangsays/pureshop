<?php
/*
  $Idä: ot_tax.php,v 1.14 2003/02/14 05:58:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_tax {
    var $title, $output;

    function ot_tax() {
      $this->code = 'ot_tax';
      $this->title = MODULE_ORDER_TOTAL_TAX_TITLE;
      $this->description = MODULE_ORDER_TOTAL_TAX_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_TAX_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_TAX_SORT_ORDER;

      $this->output = array();
    }

	function process() {
		global $order, $currencies, $discount;
		$discountprocent= $discount->actual_discount_procent + $discount->online_rabatt_procent;
		if($discount->shipping_cost > 0)
		{
			$shipping_tax= $discount->shipping_cost /100 * 19;
			$sflag=true;
		}
		reset($order->info['tax_groups']);
		while (list($key, $value) = each($order->info['tax_groups']))
		{
			if ($value > 0)
			{
				if($key > 0)
				{
					$stk=$key;
				}
				else
				{
					$stk='7';
				}
				if($discountprocent > 0)
				{
					$value = ($value/100) * (100 - $discount->actual_discount_procent);
					$value = ($value/100) * (100 -  $discount->online_rabatt_procent);
				}
				if($stk =='19' && $sflag)
				{
					$value += $shipping_tax;
					//
					$sflag  = false;
				}
					$this->output[] = array('class' => $stk,
											'title' => $this->title. ' ' . $stk . '% :',
											'text' => $currencies->format($value, true, $order->info['currency'], $order->info['currency_value']),
											'value' => $value);
			}

		}
		if($sflag)
		{
			$this->output[] = array('class' => '19',
									'title' => $this->title. ' 19% :',
									'text' => $currencies->format($shipping_tax, true, $order->info['currency'], $order->info['currency_value']),
									'value' => $shipping_tax);
		}
	}

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_TAX_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_TAX_STATUS', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER');
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Do you want to display the order tax value?', '6', '1','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '3', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
