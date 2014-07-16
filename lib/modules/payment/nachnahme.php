<?php
/*
  $Id: nachnahme.php,v 1.0 2006/06/25 13:05:31 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Created by Lukas Havranek - www.LED-Store.ch

  Released under the GNU General Public License
*/

  class nachnahme {
    var $code, $title, $description, $enabled;

// class constructor
    function nachnahme() {
      global $order;

      $this->code = 'nachnahme';
      $this->title = MODULE_PAYMENT_NACHNAHME_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_NACHNAHME_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_NACHNAHME_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_NACHNAHME_STATUS == 'True') ? true : false);
	  $this->index;
      if ((int)MODULE_PAYMENT_NACHNAHME_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_NACHNAHME_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
      $this->email_footer = MODULE_PAYMENT_NACHNAHME_TEXT_EMAIL_FOOTER;
    }

// class methods
function update_status() {
global $order, $shipping, $currency;


      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_NACHNAHME_ZONE > 0) ) {
        $check_flag = false;
        $check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_NACHNAHME_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

// Deaktivieren wenn Währung nicht Standardwährung
if ($currency != DEFAULT_CURRENCY) {
$this->enabled = false;
}

// Deaktivieren wenn Lieferadresse nicht CH
//if ($order->delivery['country_id'] != "204") {
//$this->enabled = false;
//}

// Deaktivieren wenn Versandkostenfrei
//if ($shipping['id']=="free_free") {
//$this->enabled = false;
//}

    }

    function javascript_validation() {
      return false;
    }

    function selection() {

      $selectione= array('id' => $this->code,
                   'module' => $this->title,
                   'fields' => array(array( //'title' => MODULE_PAYMENT_NACHNAHME_TEXT_D_TITLE,
											'title' => MODULE_PAYMENT_NACHNAHME_TEXT_DESCRIPTION)));
		if( ($_SESSION['shipping']['id']=='mzmt_table3') || ($_SESSION['shipping']['id']=='mzmt_table4') ){
			return "";
		}
	
	 return $selectione;

    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return array('title' => MODULE_PAYMENT_NACHNAHME_TEXT_CONFIRMATION);
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
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NACHNAHME_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Aktiviere Nachnahme Modul', 'MODULE_PAYMENT_NACHNAHME_STATUS', 'True', 'Möchten Sie die Bezahlung via Nachnahme akzeptieren?', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now());");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sortierung', 'MODULE_PAYMENT_NACHNAHME_SORT_ORDER', '0', 'Reihenfolge für die Zahlungsmöglichkeiten. (Die kleinste Nummer kommt als erstes)', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Zone', 'MODULE_PAYMENT_NACHNAHME_ZONE', '0', 'Wählen Sie für welche Zone dieses Modul aktiviert werden soll.', '6', '2', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Status der Bestellung', 'MODULE_PAYMENT_NACHNAHME_ORDER_STATUS_ID', '0', 'Wählen Sie den Status den Sie per default gesetzt haben möchten.', '6', '0', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_NACHNAHME_STATUS', 'MODULE_PAYMENT_NACHNAHME_ZONE', 'MODULE_PAYMENT_NACHNAHME_ORDER_STATUS_ID', 'MODULE_PAYMENT_NACHNAHME_SORT_ORDER');
    }
  }
?>
