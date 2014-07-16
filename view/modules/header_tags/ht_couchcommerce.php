<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License 
*/

  class ht_couchcommerce {
    var $code = 'ht_couchcommerce';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function ht_couchcommerce() {
      $this->title = MODULE_HEADER_TAGS_COUCHCOMMERCE_TITLE;
      $this->description = MODULE_HEADER_TAGS_COUCHCOMMERCE_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_COUCHCOMMERCE_STATUS') ) {
        //$this->sort_order = MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_COUCHCOMMERCE_STATUS == 'True');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate;

        if (MODULE_HEADER_TAGS_COUCHCOMMERCE_CNAME != "") {
            $redirectUrl = 'http://' . MODULE_HEADER_TAGS_COUCHCOMMERCE_CNAME. '/';
        } elseif (MODULE_HEADER_TAGS_COUCHCOMMERCE_ALIAS != "") {
            $redirectUrl = 'http://' . MODULE_HEADER_TAGS_COUCHCOMMERCE_ALIAS . '.couchcommerce.com/';
        } else {
            $redirectUrl = '';
        }
    
      if (true) {
          
        $html = '

            <script type="text/javascript" src="http://cdn.couchcommerce.com/js/devdetect.sir.js"></script>

        '."

        <script>

    function readCouchCommerceCookie() {
            var name= 'couchcommerce_noredirect';
        	var nameEQ = name + '=';
        	var ca = document.cookie.split(';');
        	for(var i=0;i < ca.length;i++) {
        		var c = ca[i];
        		while (c.charAt(0)==' ') c = c.substring(1,c.length);
        		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        	}
        	return null;
        }  " .
            '

    var ua = navigator.userAgent;

    var isGood =  /Chrome/i.test(ua) || /Safari/i.test(ua);
    ';

    if(MODULE_HEADER_TAGS_COUCHCOMMERE_TABELTS){
        $html .= 'if ((DetectIpad() || DetectAndroidTablet() && isGood && !readCouchCommerceCookie()) {
        location.href = "' . $redirectUrl . ' "   }';
    }
    if(MODULE_HEADER_TAGS_COUCHCOMMERE_SMARTPHONE){
        $html .= 'if ((DetectIphoneOrIpod() || DetectAndroidPhone()) && isGood && !readCouchCommerceCookie()) {
        location.href = "' . $redirectUrl . ' "   }';

        
    }
    }
    $html .= '</script>';        
    $oscTemplate->addBlock($html, $this->group);
    }

    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency;

      if (empty($currency_code) || !$currencies->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(push_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_COUCHCOMMERCE_STATUS');
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CouchCommerce Module', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_STATUS', 'True', 'Do you want to add CouchCommerce to your shop?', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Customer Identifier', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_CUSTOMER_IDENTIFIER', '', '', '6', '1',   now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shop Number', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_SHOP_NUMBER', '', '', '6', '1',  now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Api-Key', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_API_KEY', '', '', '6', '1',   now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Activate for Tablets', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_TABLETS', 'True', 'Do you want to add CouchCommerce to your shop?', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");      
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Activate for Smart-Tv', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_SMARTTV', 'False', 'Do you want to add CouchCommerce to your shop?', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");      
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Activate for Smartphone', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_SMARTPHONE', 'False', 'Do you want to add CouchCommerce to your shop?', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");      
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CNAME', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_CNAME', '', '', '6', '1',   now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ALIAS', 'MODULE_HEADER_TAGS_COUCHCOMMERCE_ALIAS', '', '', '6', '1',   now())");
      
      }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_COUCHCOMMERCE_STATUS','MODULE_HEADER_TAGS_COUCHCOMMERCE_CUSTOMER_IDENTIFIER','MODULE_HEADER_TAGS_COUCHCOMMERCE_SHOP_NUMBER','MODULE_HEADER_TAGS_COUCHCOMMERCE_API_KEY','MODULE_HEADER_TAGS_COUCHCOMMERCE_TABLETS','MODULE_HEADER_TAGS_COUCHCOMMERCE_SMARTTV','MODULE_HEADER_TAGS_COUCHCOMMERCE_SMARTPHONE','MODULE_HEADER_TAGS_COUCHCOMMERCE_CNAME','MODULE_HEADER_TAGS_COUCHCOMMERCE_ALIAS');
    }
  }
?>
