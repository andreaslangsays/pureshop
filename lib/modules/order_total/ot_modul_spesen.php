<?php
/*
  $Id: ot_modul_spesen.php,v 1.0 2006/06/25 14:28:12 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Created by Lukas Havranek @ www.LED-Store.ch
  (original ot_fixed_payment_chg, thanks 2 all creators!)

  Released under the GNU General Public License
*/

  class ot_modul_spesen {
    var $title, $output;
    
    function ot_modul_spesen() {
      global $payment;
      
      $this->code = 'ot_modul_spesen';
      $this->title = MODULE_MODUL_SPESEN_TITLE;
      $this->description = MODULE_MODUL_SPESEN_DESCRIPTION;
      // bol ct: dynamic title/description of fee
      if (isset($payment)) {
        $table = split("[:,]" , MODULE_MODUL_SPESEN_TYPE_DESCRIPTION);
        for ($i = 0; $i < count($table); $i+=2) {
          if ($payment == $table[$i]) {
            $this->title = $table[$i+1];
            $this->description = $table[$i+1];
          }
        }
      }
      // eol ct: dynamic title/description of fee
      $this->enabled = MODULE_MODUL_SPESEN_STATUS;
      $this->sort_order = MODULE_MODUL_SPESEN_SORT_ORDER;
      $this->type = MODULE_MODUL_SPESEN_TYPE;
      $this->tax_class = MODULE_MODUL_SPESEN_TAX_CLASS;
      $this->output = array();
    }

    function process() {
      global $order, $ot_subtotal, $currencies;
      
      $od_amount = $this->calculate_charge($this->get_order_total());
      if ($od_amount != 0) {
        $this->deduction = $od_amount;
        $this->output[] = array('title' => $this->title . ':',
                                'text' => $currencies->format($od_amount),
                                'value' => $od_amount);
	      $order->info['total'] = $order->info['total'] + $od_amount;
        if ($this->sort_order < $ot_subtotal->sort_order) {
          $order->info['subtotal'] = $order->info['subtotal'] - $od_amount;
        }
      }
    }

    function calculate_charge($amount) {
      global $order, $customer_id, $payment;
      $od_amount=0;
      $table = split("[:,]" , MODULE_MODUL_SPESEN_TYPE);
      for ($i = 0; $i < count($table); $i+=2) {
        if ($payment == $table[$i]) {
          $od_am=$table[$i+1];
          
          // use either a fixed amount or percentage of total incl. shipping
          if (substr($od_am,0,1) == '%') {
            $od_am=substr($od_am, 1);
            $od_am=round($amount/100*$od_am,1); // choose the decimal position for rounding
          }
          
          if (MODULE_MODUL_SPESEN_TAX_CLASS > 0) {
            $tod_rate = push_get_tax_rate(MODULE_MODUL_SPESEN_TAX_CLASS);
            $tod_description = push_get_tax_description(MODULE_MODUL_SPESEN_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
            $tod_amount =  push_calculate_tax($od_am, $tod_rate);
            $order->info['tax'] += $tod_amount;
            $order->info['tax_groups'][$tod_description] += push_calculate_tax($table[$i+1], $tod_rate);
          }
          
          if (DISPLAY_PRICE_WITH_TAX) { 
            $od_amount = $od_am + $tod_amount;
          } else {       
            $od_amount = $od_am;
            $order->info['total'] += $tod_amount;
          }
        }
      }
      return $od_amount;
    }

    function get_order_total() {
      global  $order, $cart;
      $order_total = $order->info['total'];
      // Check if gift voucher is in cart and adjust total
      $products = $cart->get_products();
      for ($i=0; $i<sizeof($products); $i++) {
        $t_prid = push_get_prid($products[$i]['id']);
        $gv_query = push_db_query("select products_price, products_tax_class_id, products_model from " . TABLE_PRODUCTS . " where products_id = '" . $t_prid . "'");
        $gv_result = push_db_fetch_array($gv_query);
        if (ereg('^GIFT', addslashes($gv_result['products_model']))) { 
          $qty = $cart->get_quantity($t_prid);
          $products_tax = push_get_tax_rate($gv_result['products_tax_class_id']);
          if ($this->include_tax =='false') {
            $gv_amount = $gv_result['products_price'] * $qty;
          } else {
            $gv_amount = ($gv_result['products_price'] + push_calculate_tax($gv_result['products_price'],$products_tax)) * $qty;
          }
          $order_total=$order_total - $gv_amount;
        }
      }
      if ($this->include_tax == 'false') $order_total=$order_total-$order->info['tax'];
      if ($this->include_shipping == 'false') $order_total=$order_total-$order->info['shipping_cost'];
      return $order_total;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_MODUL_SPESEN_STATUS'");
        $this->check = push_db_num_rows($check_query);
      }
      
      return $this->check;
    }

    function keys() {
      return array('MODULE_MODUL_SPESEN_STATUS', 'MODULE_MODUL_SPESEN_SORT_ORDER', 'MODULE_MODUL_SPESEN_TYPE', 'MODULE_MODUL_SPESEN_TYPE_DESCRIPTION', 'MODULE_MODUL_SPESEN_TAX_CLASS');
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Zeige Modul-Spesen', 'MODULE_MODUL_SPESEN_STATUS', 'true', 'Möchten Sie die Modul-Spesen anzeigen?', '6', '1','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sortierung', 'MODULE_MODUL_SPESEN_SORT_ORDER', '79', 'Sortierreihenfolge.', '6', '2', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zahlungsweisen', 'MODULE_MODUL_SPESEN_TYPE', 'nachnahme:15,rechnung:%3', 'Spezifische Zahlungsweisen welche belastet werden.', '6', '2', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zahlungsweisen Beschreibung', 'MODULE_MODUL_SPESEN_TYPE_DESCRIPTION', 'nachnahme:Nachnahme-Gebühren,rechnung:3% Rechnungs-Spesen', 'Spezifische Beschreibung zu jeder Zahlungsweise.', '6', '3', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Steuer Klasse', 'MODULE_MODUL_SPESEN_TAX_CLASS', '0', 'Folgende Steuer-Klasse mit Modul-Spesen verwenden.', '6', '6', 'push_get_tax_class_title', 'push_cfg_pull_down_tax_classes(', now())");
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