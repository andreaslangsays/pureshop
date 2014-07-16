<?php
/*
  $Id: gls.php,v 1.0b 2002/03/27 10:00:13 dogu Exp $

  The Exchange Project - Community Made Shopping!
  http://www.osCommerce.org

  Copyright (c) 2000,2001,2002 The Exchange Project

  Released under the GNU General Public License

  Addon Released under GLSL V2.0 by Gunter Sammet (Gunter@SammySolutions.com)
*/

  class gls {
    var $code, $title, $description, $enabled, $icon;

// class constructor
    function gls() {
      global $order;

      $this->code = 'gls';
      $this->title = MODULE_SHIPPING_GLS_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_GLS_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_GLS_SORT_ORDER;
      $this->icon = DIR_WS_ICONS . 'shipping_gls.gif';
      $this->tax_class = MODULE_SHIPPING_GLS_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_GLS_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_GLS_ZONE > 0) ) {
        $check_flag = false;
        $check_query_string = "select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_GLS_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id";
        $check_query = push_db_query($check_query_string);
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


    function quote($method = '') {
      global $shipping_quote_gls, $shipping_quote_all, $shipping_weight, $shipping_quoted, $shipping_gls_cost, $shipping_gls_method, $order;

        $error = false;
        $dest_country = $order->delivery['country']['iso_code_2'];
//        $dest_zone = 0;
        $dest_postal_code = $order->delivery['postcode'];
        //get rid of spaces in the postal code (e.g Great Britain or Canada)
        $dest_postal_code = strtoupper(str_replace(' ', '', $dest_postal_code));
//        print('Postal: ' . $dest_postal_code);
        //Don't know exactly how to deal with GB. For now, we check for GB and then only use the 2 leftmost characters.
        if($dest_country == 'GB') {
          $dest_postal_code = substr($dest_postal_code, 0, 2);
        }

//print('Dest. country: ' . $dest_country . ', Postalcode: ' . $dest_postal_code . ', Weight: ' . $shipping_weight);


//Since the postal codes are empty for a lot of countries (all have only 2 chars), we need to determine the length of pw.gls_wiehgt_ref to find out which SQL statement we want to use.
      $string_check_length ="SELECT pw.gls_weight_ref FROM gls_postal_to_weight pw, gls_country_to_postal cp WHERE cp.gls_postal_reference = pw.gls_postal_reference AND cp.gls_country = '" . $dest_country . "'";
      $country_length = push_db_query($string_check_length);
      $country_length_result = push_db_fetch_array($country_length);

//print('country: ' . $country_length_result['gls_weight_ref'] . ' ');
      
      if(strlen($country_length_result['gls_weight_ref']) == 3){
      $query_string ="SELECT w.gls_weight_price_string, w.gls_free_shipping_over, w.gls_shipping_subsidized FROM gls_weight w, gls_postal_to_weight pw, gls_country_to_postal cp WHERE cp.gls_postal_reference = pw.gls_postal_reference AND pw.gls_weight_ref = w.gls_weight_ref AND cp.gls_postal_reference = pw.gls_postal_reference AND cp.gls_country = '" . $dest_country . "' AND '" . $dest_postal_code . "' BETWEEN pw.gls_from_postal AND pw.gls_to_postal";
        
      } else {
      $query_string ="SELECT w.gls_weight_price_string, w.gls_free_shipping_over, w.gls_shipping_subsidized FROM gls_weight w, gls_postal_to_weight pw, gls_country_to_postal cp WHERE cp.gls_postal_reference = pw.gls_postal_reference AND pw.gls_weight_ref = w.gls_weight_ref AND cp.gls_postal_reference = pw.gls_postal_reference AND cp.gls_country = '" . $dest_country . "'";
        
      }


//print($query_string);

      $country_query = push_db_query($query_string);
      $gls_cost = push_db_fetch_array($country_query);
      

      if (!$country_length_result['gls_weight_ref']) {
        $this->quotes = array('id' => $this->code,
                              'module' => MODULE_SHIPPING_GLS_TEXT_TITLE,
                              'error' => MODULE_SHIPPING_GLS_INVALID_ZONE,
                              'methods' => array(array('id' => $this->code,
                                                       'title' => MODULE_SHIPPING_GLS_TEXT_TITLE,
                                                       'cost' => 0)));
  
        if ($this->tax_class > 0) {
          $this->quotes['tax'] = push_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
        }
  
        if (push_not_null($this->icon)) $this->quotes['icon'] = push_image($this->icon, $this->title);
  
        return $this->quotes;
      }
      $shipping = -1;
//        $gls_cost = constant('MODULE_SHIPPING_GLS_COST_' . $i);
      $gls_table = split("[-:,]" , $gls_cost['gls_weight_price_string']); // get the string and process it
      $n=1;
      $y=2;
      for ($i = 0; $i < count($gls_table); $i ++) {
        if ( ($shipping_weight > $gls_table[$i]) && ($shipping_weight <= $gls_table[$n]) ) {
          $shipping = $gls_table[$y];
          $shipping_gls_method = MODULE_SHIPPING_GLS_TEXT_WAY . ' ' . $dest_country . " : " . $shipping_weight . ' ' . MODULE_SHIPPING_GLS_TEXT_UNITS;
          break;
        }
        $i = $i + 2;
        $n = $n + 3;
        $y = $y + 3;
      }
      if ( $shipping == -1) {
        $shipping_gls_cost = 0;
        $shipping_gls_method = MODULE_SHIPPING_GLS_UNDEFINED_RATE;
        $error = true;
      } else {
        //Check if there is free shipping in the database.
        if($gls_cost['gls_free_shipping_over'] == -1.0000){
          //do normal processing of shipping
          $shipping_gls_cost = ($shipping + MODULE_SHIPPING_GLS_HANDLING + SHIPPING_HANDLING);
        } else if(($gls_cost['gls_free_shipping_over'] != -1.0000) && ($gls_cost['gls_shipping_subsidized'] == -1.0000)){
          //free shipping if over amount
          if($order->info['subtotal'] >= $gls_cost['gls_free_shipping_over']){
//              print('Free Order: ' . $order->info['subtotal'] . ' Cost: ' . $gls_cost['gls_free_shipping_over']);
            //shipping is free
            $shipping_gls_cost = 0;
            $shipping_gls_method = MODULE_SHIPPING_GLS_FREE_SHIPPING;
          } else {
//              print('Free Else Order: ' . $order->info['subtotal'] . ' Cost: ' . $gls_cost['gls_free_shipping_over']);
            //charge for shipping
            $shipping_gls_cost = ($shipping + MODULE_SHIPPING_GLS_HANDLING + SHIPPING_HANDLING);
          }
        //subsidized shipping over amount
        } else {
          if($order->info['subtotal'] >= $gls_cost['gls_free_shipping_over']){
//              print('Sub Order: ' . $order->info['subtotal'] . ' Cost: ' . $gls_cost['gls_free_shipping_over']);
            //shipping is subsidized
            $shipping_gls_cost = (($shipping + MODULE_SHIPPING_GLS_HANDLING + SHIPPING_HANDLING)-$gls_cost['gls_shipping_subsidized']);
            $shipping_gls_method = MODULE_SHIPPING_GLS_SUBSIDIZED_SHIPPING . ' ' .MODULE_SHIPPING_GLS_TEXT_WAY . ' ' . $dest_country . " : " . $shipping_weight . ' ' .             MODULE_SHIPPING_GLS_TEXT_UNITS;
          } else {
//              print('Sub Else Order: ' . $order->info['subtotal'] . ' Cost: ' . $gls_cost['gls_free_shipping_over']);
            //charge for shipping
            $shipping_gls_cost = ($shipping + MODULE_SHIPPING_GLS_HANDLING + SHIPPING_HANDLING);
            
          }
        }
      }
     $this->quotes = array('id' => $this->code,
                     'module' => MODULE_SHIPPING_GLS_TEXT_TITLE,
                     'methods' => array(array('id' => $this->code,
                                              'title' => $shipping_gls_method,
                                              'cost' => $shipping_gls_cost)));
      
      if ($this->tax_class > 0) {
       $this->quotes['tax'] = push_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
      
      if (push_not_null($this->icon)) $this->quotes['icon'] = push_image($this->icon, $this->title);
      
      if($error){
        $this->quotes['error'] = $shipping_gls_method;
      }
      return $this->quotes;

    }


    function check() {
      $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_GLS_STATUS'");
      $this->_check = push_db_num_rows($check_query);

      return $this->_check;
    }

    function install() {
// put out a notice to make sure that the tables are created

//disabled the next one because of some problems: If module is installed and this set to 0, checkout doesn't work.
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Table Method', 'MODULE_SHIPPING_GLS_STATUS', 'True', 'Do you want to offer table rate shipping?', '6', '0', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_GLS_HANDLING', '0', 'Handling Fee for this shipping method', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_GLS_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_GLS_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'push_get_tax_class_title', 'push_cfg_pull_down_tax_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_GLS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
    }

    function remove() {
// put out a notice to drop the tables or leave them

//disabled the next one because of some problems: If module is installed and this set to 0, checkout doesn't work.
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_GLS_STATUS'");
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_GLS_HANDLING'");
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_GLS_SORT_ORDER'");
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_GLS_TAX_CLASS'");
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_GLS_ZONE'");
    }

    function keys() {
// This function is used to display the keys, if they are stored in the configuration table. We have separate tables for the setup. Only the handlingfee need to be displayed.
      $keys = array('MODULE_SHIPPING_GLS_STATUS', 'MODULE_SHIPPING_GLS_HANDLING', 'MODULE_SHIPPING_GLS_SORT_ORDER', 'MODULE_SHIPPING_GLS_TAX_CLASS', 'MODULE_SHIPPING_GLS_ZONE');
      return $keys;
    }
  }
?>
