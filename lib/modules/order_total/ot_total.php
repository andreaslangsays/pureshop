<?php
/*
  $Id: ot_total.php,v 1.7 2003/02/13 00:12:04 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_total {
    var $title, $output;

    function ot_total() {
      $this->code = 'ot_total';
      $this->title = MODULE_ORDER_TOTAL_TOTAL_TITLE;
      $this->description = MODULE_ORDER_TOTAL_TOTAL_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_TOTAL_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;

      $this->output[] = array('title' => $this->title . ':',
                              'text' => '<b>' . $currencies->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value']) . '</b>',
                              'value' => $order->info['total']);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_TOTAL_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_TOTAL_STATUS', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER');
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Do you want to display the total order value?', '6', '1','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
