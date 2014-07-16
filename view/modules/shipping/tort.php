<?php
/*
  $Id: flat.php,v 1.40 2003/02/05 22:41:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  delivered by BKR Tortenshop
  
*/

class tort {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function tort() {
      global $order;

      $this->code = 'tort';
      $this->title = MODULE_SHIPPING_TORT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_TORT_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_TORT_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_TORT_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_TORT_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_TORT_ZONE > 0) ) {
        $check_flag = false;
        $check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_TORT_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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
//*****************************************************************
// class methods
	function quote($method = '') {
		global $order, $cart, $tort_distance, $tort_plz, $sendto;
	  require(DIR_WS_FUNCTIONS . 'distance.php');

/*
 * hier die Preisberechnung
 * categories based
 */
	$shippingcost = MODULE_SHIPPING_TORT_MAX_COST;
	$order_total = $cart->show_total(true);
	$torttext = MODULE_SHIPPING_TORT_TEXT_WAY;
	$regionallowed = 0;


/**
 * Entfernung zur Grundlage der Restriction machen
 */
 	//DB REQUEST
	$db_distance = push_db_fetch_array(push_db_query("SELECT distance FROM distances WHERE address_id= " . $sendto . ";"));
	if(push_not_null($db_distance['distance'])){
			$distance=$db_distance['distance'];
		}else{
		//look for distance via google
			$address=push_db_fetch_array(push_db_query("SELECT entry_street_address AS street, entry_postcode AS plz, entry_city AS city, countries_name AS country FROM address_book JOIN countries ON entry_country_id=countries_id WHERE address_book_id='" . $sendto . "'"));
			$distancerequest=get_distance($address['street'], $address['plz'],$address['city'], $address['country']);
			if($distancerequest<>false){
				$distance=$distancerequest;
			}else{
				$distance=99999999;
			}
			push_db_query('INSERT INTO distances (address_id, distance) VALUES (' . $sendto . ', ' . $distance . ') ');
		
		}

		if($distance <= MODULE_SHIPPING_TORT_MAX_DISTANCE){
			$regionallowed=1;		
		}else{
			$regionallowed=0;
		}

		if($regionallowed == 1){
			if($order_total >= MODULE_SHIPPING_TORT_FREE){
					$shippingcost=0;
				}elseif($distance <= MODULE_SHIPPING_TORT_DISTANCE1){
					$shippingcost = MODULE_SHIPPING_TORT_COST1;
				}elseif($distance <= MODULE_SHIPPING_TORT_DISTANCE2){
					$shippingcost = MODULE_SHIPPING_TORT_COST2;
				}elseif($distance <= MODULE_SHIPPING_TORT_DISTANCE3){
					$shippingcost = MODULE_SHIPPING_TORT_COST3;
				}elseif($distance <= MODULE_SHIPPING_TORT_DISTANCE4){
					$shippingcost = MODULE_SHIPPING_TORT_COST4;
				}elseif($distance <= MODULE_SHIPPING_TORT_DISTANCE5){
					$shippingcost = MODULE_SHIPPING_TORT_COST5;
				}elseif($distance <= MODULE_SHIPPING_TORT_DISTANCE6){
					$shippingcost = MODULE_SHIPPING_TORT_COST6;
				}

			$this->quotes = array(	'id' => $this->code,
									'module' => MODULE_SHIPPING_TORT_TEXT_TITLE,
									'methods' => array(array(	'id' => $this->code,
																'title' => $torttext ,
																'cost' => $shippingcost)));
		}else{
			$this->quotes['error'] = 'Die Entfernung Ihrer Lieferadresse von unserer Pâtisserie darf nicht mehr als ' . (MODULE_SHIPPING_TORT_MAX_DISTANCE/1000) . " Kilometer betragen.<br> " . (($distance==99999999)? '<b>Die Entfernung lässt sich nicht bestimmen.</b>':'Ihrer aktuelle Lieferadresse liegt in etwa: <b>' . ceil($distance/1000) . ' Kilometer</b> Entfernung.') . "<br> Bitte wählen Sie eine andere Lieferadresse aus.";
			return $this->quotes;
		}
	
		if ($this->tax_class > 0) {
			$this->quotes['tax'] = push_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
		}

		if (push_not_null($this->icon)) $this->quotes['icon'] = push_image($this->icon, $this->title);

		return $this->quotes;
	}

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TORT_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Schalte Tortenlieferung ein', 'MODULE_SHIPPING_TORT_STATUS', 'True', 'Wollen Sie Tortenlieferung anbieten?', '6', '0', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
   //   push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Lieferkosten Maximum', 'MODULE_SHIPPING_TORT_MAX_COST', '14.95', 'Maximale Versandkosten (Wert der gew&auml;hlt wird, sobald Artikel keine Zuordnung  zu einer Versandkategorie erhalten)', '6', '0', now())");

      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('VK Kategorie 1 (niedrigste VK)', 'MODULE_SHIPPING_TORT_COST1', '4.95', 'Versandkosten für diese Kategorie - niedrigste VK weil höchster Bestellwert.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Entfernung Kategorie 1', 'MODULE_SHIPPING_TORT_DISTANCE1', '5000', 'Entfernung bis zu der die VK anfallen in Meter', '6', '0', now())");
   //   push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bezeichnung der Kategorie 1', 'MODULE_SHIPPING_TORT_NAME1', 'Super Premium', 'Name für die Kategorie', '6', '0', now())");
      
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('VK Kategorie 2', 'MODULE_SHIPPING_TORT_COST2', '5.95', 'Versandkosten für einzelne Artikel dieser Kategorie.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Entfernung Kategorie 2', 'MODULE_SHIPPING_TORT_DISTANCE2', '15000', 'Entfernung bis zu der die VK anfallen in Meter <br>(muss größer sein als die vorherige Entfernung).', '6', '0', now())");
   //   push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bezeichnung der Kategorie 2', 'MODULE_SHIPPING_TORT_NAME2', 'Premium', 'Name für die Kategorie', '6', '0', now())");

      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('VK Kategorie 3', 'MODULE_SHIPPING_TORT_COST3', '10.95', 'Versandkosten für einzelne Artikel dieser Kategorie.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Entfernung Kategorie 3', 'MODULE_SHIPPING_TORT_DISTANCE3', '20000', 'Entfernung bis zu der die VK anfallen in Meter <br>(muss größer sein als die vorherige Entfernung)', '6', '0', now())");
    //  push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bezeichnung der Kategorie 3', 'MODULE_SHIPPING_TORT_NAME3', 'Premium Light', 'Name für die Kategorie', '6', '0', now())");
      
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('VK Kategorie 4', 'MODULE_SHIPPING_TORT_COST4', '15.95', 'Versandkosten für einzelne Artikel dieser Kategorie.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Entfernung Kategorie 4', 'MODULE_SHIPPING_TORT_DISTANCE4', '25000', 'Entfernung bis zu der die VK anfallen in Meter <br>(muss größer sein als die vorherige Entfernung)', '6', '0', now())");
  //    push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bezeichnung der Kategorie 1', 'MODULE_SHIPPING_TORT_NAME4', 'Entfernung', 'Name für die Kategorie', '6', '0', now())");
      
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('VK Kategorie 5', 'MODULE_SHIPPING_TORT_COST5', '20.95', 'Versandkosten für einzelne Artikel dieser Kategorie.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Entfernung Kategorie 5', 'MODULE_SHIPPING_TORT_DISTANCE5', '30000', 'Entfernung bis zu der die VK anfallen in Meter <br>(muss größer sein als die vorherige Entfernung)', '6', '0', now())");
  //    push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bezeichnung der Kategorie 5', 'MODULE_SHIPPING_TORT_NAME5', 'Premium Mini Spezial', 'Name für die Kategorie', '6', '0', now())");
      
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('VK Kategorie 6', 'MODULE_SHIPPING_TORT_COST6', '25,95', 'Versandkosten für einzelne Artikel dieser Kategorie.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Entfernung Kategorie 6', 'MODULE_SHIPPING_TORT_DISTANCE6', '35000', 'Entfernung bis zu der die VK anfallen in Meter <br>(muss größer sein als die vorherige Entfernung)', '6', '0', now())");
//      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bezeichnung der Kategorie 6', 'MODULE_SHIPPING_TORT_NAME6', 'Premium Mini To Go', 'Name für die Kategorie', '6', '0', now())");

      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Warenkorbwert ab dem kostenlos geliefert wird', 'MODULE_SHIPPING_TORT_FREE', '150.00', 'Versandkostenfrei ab', '6', '0', now())");

//      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Wie soll der Kundenkreis eingeschr&auml;nkt werden?', 'MODULE_SHIPPING_TORT_RESTRICT_MODE', 'Entfernung', 'Durch Entfernungsangabe oder Postleitzahlgebiet?', '6', '0', 'push_cfg_select_option(array(\'Entfernung\', \'PLZ\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximale Entfernung (Meter)', 'MODULE_SHIPPING_TORT_MAX_DISTANCE', '40000', 'Die maximale Entfernung über die noch geliefert wird (Straßenverbindung) in Meter.<br>Verhindert Lieferungen an weitere Entfernungen in den Kategorien', '6', '0', now())");


      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_TORT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'push_get_tax_class_title', 'push_cfg_pull_down_tax_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_TORT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
  
	  push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_TORT_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array(	'MODULE_SHIPPING_TORT_STATUS',
			//'MODULE_SHIPPING_TORT_MAX_COST',
			
			'MODULE_SHIPPING_TORT_COST1',
			'MODULE_SHIPPING_TORT_DISTANCE1',
			//'MODULE_SHIPPING_TORT_NAME1',
			
			'MODULE_SHIPPING_TORT_COST2',
			'MODULE_SHIPPING_TORT_DISTANCE2',
			//'MODULE_SHIPPING_TORT_NAME2',
			
			'MODULE_SHIPPING_TORT_COST3',
			'MODULE_SHIPPING_TORT_DISTANCE3',
			//'MODULE_SHIPPING_TORT_NAME3',
			
			'MODULE_SHIPPING_TORT_COST4',
			'MODULE_SHIPPING_TORT_DISTANCE4',
			//'MODULE_SHIPPING_TORT_NAME4',
			
			'MODULE_SHIPPING_TORT_COST5',
			'MODULE_SHIPPING_TORT_DISTANCE5',
			//'MODULE_SHIPPING_TORT_NAME5',
			
			'MODULE_SHIPPING_TORT_COST6',
			'MODULE_SHIPPING_TORT_DISTANCE6',
			//'MODULE_SHIPPING_TORT_NAME6',
			
			'MODULE_SHIPPING_TORT_FREE',
			//'MODULE_SHIPPING_TORT_RESTRICT_MODE',
			'MODULE_SHIPPING_TORT_MAX_DISTANCE',
			//'MODULE_SHIPPING_TORT_PLZ',
			'MODULE_SHIPPING_TORT_TAX_CLASS',
			'MODULE_SHIPPING_TORT_ZONE',
			'MODULE_SHIPPING_TORT_SORT_ORDER');
    }
  }
?>