<?php
/*
  $Id: flat.php,v 1.39 2003/01/14 03:20:42 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class giftwrap {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function giftwrap() {
      global $order;

      $this->code = 'giftwrap';
      $this->title = MODULE_GIFT_TEXT_TITLE;
      $this->description = MODULE_GIFT_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_GIFT_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_GIFT_ZONE > 0) ) {
        $check_flag = false;
        $check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_GIFT_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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
      $this->quotes = array('id' => $this->code,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_GIFT_TEXT_WAY,
                                                     'cost' => MODULE_GIFT_COST)));

      if (push_not_null($this->icon)) $this->quotes['icon'] = push_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_GIFT_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable GiftWrap', 'MODULE_GIFT_STATUS', 'True', 'Do you want to offer GiftWrap?', '6', '0', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('GifWrap Cost', 'MODULE_GIFT_COST', '5.00', 'The shipping cost for all orders wanting GiftWrap.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Gift Zone', 'MODULE_GIFT_ZONE', '0', 'If a zone is selected, only enable this giftwrap method for that zone.', '6', '0', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_GIFT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_GIFT_STATUS', 'MODULE_GIFT_COST', 'MODULE_GIFT_ZONE', 'MODULE_GIFT_SORT_ORDER');
    }
  }
?>
