<?php
/*
  $Id: mzmt.php,v 1.100 2004-11-09 Josh Dechant Exp $

  Copyright (c) 2004 Josh Dechant

  ösCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class mzmt {
    var $code, $_check, $title, $description, $icon, $enabled, $num_zones, $num_tables, $delivery_geozone, $geozone_mode, $order_total, $current_tax_group, $minvalue;

    function mzmt() {
      global $order;
      $this->code = 'mzmt';
      $this->title = MODULE_SHIPPING_MZMT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_MZMT_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_MZMT_SORT_ORDER;
	  $this->minvalue=0;
//BOF german Tax hack
if( is_array($order->info['tax_groups']) ){
	if(count($order->info['tax_groups']) > 1){
		$wert=0;
		foreach( $order->info['tax_groups'] AS $key => $val)
		{
			if($val> $wert)
			{
				$this->current_tax_group = (int)$key;
				$wert = $val;
			}
		}
	}
	else
	{
		$this->current_tax_group = (int)key($order->info['tax_groups'] );
	}
	$this->tax_class = push_get_matching_tax_class($this->current_tax_group);
	}else{
	  $this->tax_class = MODULE_SHIPPING_MZMT_TAX_CLASS;
	}
	//HÄCK FOR push!
	$this->current_tax_group=0;
	$this->tax_class = MODULE_SHIPPING_MZMT_TAX_CLASS;
 //     $this->tax_class = MODULE_SHIPPING_MZMT_TAX_CLASS;
//	  $this->current_tax_group = 0;

      $this->enabled = ((MODULE_SHIPPING_MZMT_STATUS == 'True') ? true : false);

      $this->num_geozones = MODULE_SHIPPING_MZMT_NUMBER_GEOZONES;
      $this->num_tables = MODULE_SHIPPING_MZMT_NUMBER_TABLES;

	if(isset($order)){
		$mcountry_id = $order->delivery['country']['id'];
		$mzone_id = $order->delivery['zone_id'];
	}
	elseif(isset($_SESSION['customer_country_id'])){
		$mcountry_id = $_SESSION['customer_country_id'];
		$mzone_id = $_SESSION['customer_zone_id'];
	}

      if ($this->enabled == true && isset($mcountry_id)) {
        $this->enabled = false;
        for ($n=1; $n<=$this->num_geozones; $n++) {
          if ( ((int)constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_ID') > 0) && ((int)constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_ID') == $this->getGeoZoneID($mcountry_id, $mzone_id)) ) {
            $this->enabled = true;
            $this->delivery_geozone = $n;
            break;
          } elseif ( ((int)constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_ID') == 0) && ($n == (int)$this->num_geozones) ) {
            $this->enabled = true;
            $this->delivery_geozone = $n;
            break;
          }
        }
      }else $this->delivery_geozone = 1;

    }

// class methods
    function quote($method = '') {
      global $order, $shipping_weight;
      $this->quotes = array('id' => $this->code,
                            'module' => constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TEXT_TITLE') . ' ' . $shipping_weight . ' ',
                            'methods' => array());

      $this->determineTableMethod(constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_MODE'));

      if ($method) {
        $j = substr($method, 5);

        $shipping = $this->determineShipping(split("[:,]" , constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $j)));

        $this->quotes['methods'][] = array('id' => 'table' . $j,
											'title' => constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $j . '_TEXT_WAY'),
											'cost' => (($shipping + constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_HANDLING')) / ( 100 + $this->current_tax_group ) *100 ),
											'min' => $this->minvalue );
      } else {
        for ($j=1; $j<=$this->num_tables; $j++) {
          if (!push_not_null(constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $j))) continue;

          $shipping = $this->determineShipping(split("[:,]" , constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $j)));
          $this->quotes['methods'][] = array('id' => 'table' . $j,
                                             'title' => constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $j . '_TEXT_WAY'),
                                             'cost' => ( ($shipping + constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_HANDLING') ) / ( 100 + $this->current_tax_group ) * 100),
											 'min' => $this->minvalue  );
        }
      }

      //if ($this->tax_class > 0) {
        $this->quotes['tax'] = push_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      //}

//      if (push_not_null(constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_ICON'))) $this->quotes['icon'] = push_image(DIR_WS_ICONS . constant('MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_ICON'), $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_MZMT_STATUS'");
        $this->_check = mysql_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable MultiRegion MultiTable Method', 'MODULE_SHIPPING_MZMT_STATUS', 'True', 'Do you want to offer multi-region multi-table rate shipping?', '6', '0', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_MZMT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'push_get_tax_class_title', 'push_cfg_pull_down_tax_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_MZMT_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");

      for ($n=1; $n<=$this->num_geozones; $n++) {
        push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('<hr />Geo Zone $n', 'MODULE_SHIPPING_MZMT_GEOZONE_{$n}_ID', '', 'Enable this for the following geo zone.', '6', '0', 'push_get_zone_class_title', '_cfg_pull_down_geozones(', now())");
        push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Geo Zone $n Table Method', 'MODULE_SHIPPING_MZMT_GEOZONE_{$n}_MODE', 'weight', 'The shipping cost is based on the total weight, total price, or total count of the items ordered.', '6', '0', 'push_cfg_select_option(array(\'weight\', \'price\', \'count\'), ', now())");

        for ($j=1; $j<=$this->num_tables; $j++) {
          push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Geo Zone $n Shipping Table $j', 'MODULE_SHIPPING_MZMT_GEOZONE_{$n}_TABLE_{$j}', '', 'Shipping table $j for this geo zone', '6', '0', now())");
        }

        push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Geo Zone $n Handling Fee', 'MODULE_SHIPPING_MZMT_GEOZONE_{$n}_HANDLING', '0', 'Handling Fee for this shipping geo zone', '6', '0', now())");
      }
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_MZMT_STATUS', 'MODULE_SHIPPING_MZMT_TAX_CLASS', 'MODULE_SHIPPING_MZMT_SORT_ORDER');

      for ($n=1; $n<=$this->num_geozones; $n++) {
        $keys[] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_ID';
        $keys[] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_MODE';
        $keys[] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_HANDLING';

        for ($j=1; $j<=$this->num_tables; $j++) {
          $keys[] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_TABLE_' . $j;
        }
      }

      return $keys;
    }

    function determineTableMethod($geozone_mode) {
      global $total_count, $shipping_weight,$cart;

      $this->geozone_mode = $geozone_mode;

      if ($this->geozone_mode == 'price') {
        $this->order_total = $cart->show_total();
      } elseif ($this->geozone_mode == 'count') {
        $this->order_total = $total_count;
      } else {
        $this->order_total = $shipping_weight;
      }

      return true;
    }

	function determineShipping($table_cost) {
		global $shipping_num_boxes;
		
		for ($i=0, $n=sizeof($table_cost); $i<$n; $i+=2)
		{
			if ($this->order_total >= $table_cost[$i]) {
				$shipping_factor = $table_cost[$i+1];
			}
			$this->minvalue=$table_cost[$i];
		}
		
		if (substr_count($shipping_factor, '%') > 0) {
		$shipping = ((($this->order_total*10)/10)*((str_replace('%', '', $shipping_factor))/100));
		} else {
		$shipping = str_replace('$', '', $shipping_factor);
		}
		
		if ($this->geozone_mode == 'weight') {
		$shipping = $shipping * $shipping_num_boxes;
		}
		
		return $shipping;
	}

    function getGeoZoneID($country_id, $zone_id) {
      // First, check for a Geo Zone that explicity includes the country & specific zone (useful for splitting countries with zones up)
      $zone_query = push_db_query("select gz.geo_zone_id from " . TABLE_GEO_ZONES . " gz left join " . TABLE_ZONES_TO_GEO_ZONES . " ztgz on (gz.geo_zone_id = ztgz.geo_zone_id) where ztgz.zone_country_id = '" . (int)$country_id . "' and ztgz.zone_id = '" . (int)$zone_id . "' and LOWER(gz.geo_zone_name) like 'shp%'");

      if (mysql_num_rows($zone_query)) {
        $zone = mysql_fetch_assoc($zone_query);
        return $zone['geo_zone_id'];
      } else {
        // No luck…  Now check for a Geo Zone for the country and "All Zones" of the country.
        $zone_query = push_db_query("select gz.geo_zone_id from " . TABLE_GEO_ZONES . " gz left join " . TABLE_ZONES_TO_GEO_ZONES . " ztgz on (gz.geo_zone_id = ztgz.geo_zone_id) where ztgz.zone_country_id = '" . (int)$country_id . "' and (ztgz.zone_id = '0' or ztgz.zone_id is NULL) and LOWER(gz.geo_zone_name) like 'shp%'");

        if (mysql_num_rows($zone_query)) {
          $zone = mysql_fetch_assoc($zone_query);
          return $zone['geo_zone_id'];
        } else {
          return false;
        }
      }
    }

  }

  function _cfg_pull_down_geozones($zone_class_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $zone_class_array = array(array('id' => '0', 'text' => 'Rest of the World'));
    $zone_class_query = push_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " where LOWER(geo_zone_name) like 'shp%' order by geo_zone_name");

    while ($zone_class = mysql_fetch_assoc($zone_class_query)) {
      $zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
                                  'text' => $zone_class['geo_zone_name']);
    }

    return push_draw_pull_down_menu($name, $zone_class_array, $zone_class_id);
  }
?>
