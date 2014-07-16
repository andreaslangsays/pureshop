<?php
/*
  $Id: ot_netto.php,v 1.0.0.0 2004/03/07 19:30:00 Stephan Hilchenbach Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_netto {
    var $title, $output;

    function ot_netto() {
      $this->code = 'ot_netto';
      $this->title = MODULE_ORDER_TOTAL_NETTO_TITLE;
      $this->description = MODULE_ORDER_TOTAL_NETTO_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_NETTO_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_NETTO_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;
      $Tax_total = 0;

      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups'])) {
        // sum all tax values to calculate total tax:
        if ($value > 0) $Tax_total += $value;
      }
      
      // subtract total tax from total invoice amount to calculate net amount:
      $Netto = $order->info['total']-$Tax_total;
      
      // output net amount:
      $this->output[] = array('title' => '' . $this->title . ':',
                        'text' => $currencies->format($Netto, true, $order->info['currency'], $order->info['currency_value']) . '',
                        'value' => $Netto);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_NETTO_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_NETTO_STATUS', 'MODULE_ORDER_TOTAL_NETTO_SORT_ORDER');
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display net', 'MODULE_ORDER_TOTAL_NETTO_STATUS', 'true', 'Do you want to display the net amount?', '6', '1','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_NETTO_SORT_ORDER', '6', 'Sort order of display.', '6', '6', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
