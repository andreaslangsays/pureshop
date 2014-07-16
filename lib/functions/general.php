<?php
/*
  $Id: general.php,v 1.231 2003/07/09 01:15:48 hpdl Exp $
  adapted for Separate Pricing Per Customer v4.0 2005/02/08

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	function s($input)
	{
		//returns a safe string for processing
		return mysql_real_escape_string($input);	
	}
  	function freierTag($stamp=0 ) {
		if($stamp==0)
		{
			$stamp=time();
		}
		$tag = date('d',$stamp);
		$monat = date('m',$stamp);
		$jahr = date('Y',$stamp);
		// Wochentag berechnen
		$datum = getdate(mktime(0, 0, 0, $monat, $tag, $jahr));
		$wochentag = $datum['wday'];
		
		// Prüfen, ob Wochenende
		if($wochentag == 0 || $wochentag == 6) {
			return true;
		}
		
		// Feste Feiertage werden nach dem Schema ddmm eingetragen
		$feiertage[] = "0101"; // Neujahrstag
		$feiertage[] = "0105"; // Tag der Arbeit
		$feiertage[] = "0310"; // Tag der Deutschen Einheit
		$feiertage[] = "2412"; // Braunschweig frei
		$feiertage[] = "2512"; // Erster Weihnachtstag
		$feiertage[] = "2612"; // Zweiter Weihnachtstag
		$feiertage[] = "3112"; // Braunschweig frei
		
		// Bewegliche Feiertage berechnen
		$tage = 60 * 60 * 24;
		$ostersonntag = ostersonntag($jahr);
		$feiertage[] = date("dm", $ostersonntag - 2 * $tage);  // Karfreitag
		$feiertage[] = date("dm", $ostersonntag + 1 * $tage);  // Ostermontag
		$feiertage[] = date("dm", $ostersonntag + 39 * $tage); // Himmelfahrt
		$feiertage[] = date("dm", $ostersonntag + 50 * $tage); // Pfingstmontag
		
		// Prüfen, ob Feiertag
		$code = $tag.$monat;
		if(in_array($code, $feiertage)) {
			return true;
		} else {
			return false;
		}
	}

	function ostersonntag($Year) {
		$G = $Year % 19; 
		$C = (int)($Year / 100); 
		$H = (int)($C - (int)($C / 4) - (int)((8*$C+13) / 25) + 19*$G + 15) % 30; 
		$I = (int)$H - (int)($H / 28)*(1 - (int)($H / 28)*(int)(29 / ($H + 1))*((int)(21 - $G) / 11)); 
		$J = ($Year + (int)($Year/4) + $I + 2 - $C + (int)($C/4)) % 7; 
		$L = $I - $J; 
		$m = 3 + (int)(($L + 40) / 44); 
		$d = $L + 28 - 31 * ((int)($m / 4)); 
		$y = $Year; 
		$E = mktime(0,0,0, $m, $d, $y); 
		return $E; // Zeitstempel für Ostersonntag zurückgeben 
	} 
	function get_christmas(){
		$begin = @strtotime("24 December this year 0:0");
		$end = @strtotime("27 December this year 0:0");
		$today = time();
		if($today > $begin && $today < $end)
		{
			return true;
		}
		return false;
	}
	
	function get_silvester(){
		$begin = @strtotime("1 January this year 0:0");
		$end = @strtotime("7 January this year 0:0");
		$today = time();
		if($today > $begin && $today < $end)
		{
			return true;
		}
		return false;
	}

	function get_client_ip() {
		$ipaddress = '';
		if ($_SERVER['HTTP_CLIENT_IP'])
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
	 
		return $ipaddress;
	}

	function log_url()
	{
		global $name;
		$ip = get_client_ip();
		$brow= new Browser;
		$detect = new Mobile_Detect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
		
		if($res=push_db_fetch_array(push_db_query("SELECT * FROM url_logger WHERE ip ='" . $ip . "' AND date >= CURDATE() AND NOT countryCode='' ")))
		{
					$locations=array('countryCode'=>$res['countryCode'],'countryName'=>$res['countryName'], 'regionName'=>$res['regionName'],'cityName'=>$res['cityName']);		
		}
		else
		{
			$ipLite = new ip2location_lite;
			//Get errors and locations
			$locations = $ipLite->getCity($ip);
			if(empty($locations))
			{
				$locations=array('countryCode'=>'unbekannt','countryName'=>'unbekannt', 'regionName'=>'unbekannt','cityName'=>'unbekannt');	
			}
		}
			push_db_query("INSERT	INTO url_logger 
									SET 
										url = '" . $name . "', 
										date = NOW(), 
										ip='" . $ip . "',
										session_id='" . session_id() . "', 
										browser='" . $brow->getBrowser() . "', 
										version='" . $brow->getVersion() . "', 
										platform='" . $brow->getPlatform() . "', 
										device='" . $deviceType . "',
										countryCode='" . $locations['countryCode'] . "',
										countryName='" . $locations['countryName'] . "',
										regionName='" . $locations['regionName'] . "',
										cityName='" . $locations['cityName'] . "'
										; ");
	}

	function get_url($link)
	{
		global $name;
	
		if($t=push_db_fetch_array(push_db_query("SELECT url, name FROM url_shortener WHERE short='" . $link . "';")))
		{
			//$name=$t['name'] . " " . $link;
			$url = $t['url'];
			push_db_query("UPDATE url_shortener SET count = count+ 1 WHERE short='" . $link . "';");
			return $url;
		}
		else
		{
			return false;
		}
	}

	function get_advent(){
		$jahr = date("Y");
		$a1 = strtotime("+1 sunday",mktime(0,0,0,11,26,$jahr));
		$a2 = strtotime("+2 sunday",mktime(0,0,0,11,26,$jahr));
		$a3 = strtotime("+3 sunday",mktime(0,0,0,11,26,$jahr));
		$a4 = strtotime("+4 sunday",mktime(0,0,0,11,26,$jahr));
		$end =mktime(0,0,0,12,23,$jahr);
		$today = time();
		if($today > $end)
		{
			return false;
		}
		if($today > $a4 && $today < $end)
		{
			return 4;
		}
		elseif($today > $a3)
		{
			return 3;
		}
		elseif($today > $a2)
		{
			return 2;
		}
		elseif($today > $a1)
		{
			return 1;
		}
	}
	function curPageURL()
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on")
		{
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
		else 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
//
  function push_prefix_view($prefix_symbol) {
    if (strchr($prefix_symbol,'+')) return '+';
    if (strchr($prefix_symbol,'-')) return '-';
    if (strchr($prefix_symbol,'')) return '';
    return $prefix_symbol;
  }

  function push_prefix_options($price_prefix, $options_values_price, $options_values_qty, $qty) {
    if (strchr($price_prefix,'x')) $options_values_price = (((int)(floor($qty/$options_values_qty)))*$options_values_price)/$qty;
    if (strchr($price_prefix,'q')) {
      if ($qty < $options_values_qty) $options_values_price = 0;
    }
    return $options_values_price;
  }

  function push_linked_attributes($link_attributes, $prod_id) {
    if ($link_attributes) {
      $collegamento_query = push_db_query("select options_id, options_values_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $prod_id . "' and products_attributes_id = '" . $link_attributes . "'");
      if ($collegamento = push_db_fetch_array($collegamento_query)) {
        $attributes_add = '{' . $collegamento['options_id'] . '}' . $collegamento['options_values_id'];
      }
    } else {
      $attributes_add = '';
    }
    return $attributes_add;
  }

////
// Stop from parsing any further PHP code
  function push_exit() {
   push_session_close();
   exit();
  }

////
// Redirect to another page or site
 function push_redirect($url) {
  if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) {
    push_redirect(push_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
  }
  if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
    if (substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER) { // NONSSL url
      $url = HTTpush_SERVER . substr($url, strlen(HTTP_SERVER)); // Change it to SSL
    }
  }
  header('Location: ' . $url);
  push_exit();
}

////
// Parse the data used in the html tags to ensure the tags will not break
  function push_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }

  function push_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return push_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return push_parse_input_field_data($string, $translate);
      }
    }
  }

  function push_output_string_protected($string) {
    return push_output_string($string, false, true);
  }

  function push_sanitize_string($string) {
    $string = ereg_replace(' +', ' ', $string);

    return preg_replace("/[<>]/", '_', $string);
  }

////
// Return a random row from a database query
  function push_random_select($query) {
    $random_product = '';
    $random_query = push_db_query($query);
    $num_rows = push_db_num_rows($random_query);
    if ($num_rows > 0) {
      $random_row = push_rand(0, ($num_rows - 1));
      push_db_data_seek($random_query, $random_row);
      $random_product = push_db_fetch_array($random_query);
    }

    return $random_product;
  }

////
// Return a product's name
// TABLES: products
  function push_get_products_name($product_id, $language = '') {
    global $languages_id;

    if (empty($language)) $language = $languages_id;

    $product_query = push_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
    $product = push_db_fetch_array($product_query);

    return $product['products_name'];
  }

////
// Return a product's special price (returns nothing if there is no offer)
// TABLES: products
  function push_get_products_special_price($product_id) {
// BOF Separate Pricing Per Customer
  global $sppc_customer_group_id;

	$specials_without_duplicates = "SELECT products_id, status, customers_id, MIN(specials_new_products_price) AS specials_new_products_price FROM specials WHERE specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) GROUP BY products_id";
  /*  
  if(!push_session_is_registered('sppc_customer_group_id')) {
  $customer_group_id = '0';
  } else {
     $customer_group_id = $sppc_customer_group_id;
  }
  */
		$product_query = push_db_query("select s.specials_new_products_price from (" . $specials_without_duplicates . ") AS s where s.products_id = '" . (int)$product_id . "' and s.status and s.customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        //$product_query = push_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status and customers_group_id = '" . (int)$customer_group_id . "'");
// EOF Separate_Pricing Per Customer

    $product = push_db_fetch_array($product_query);

    return $product['specials_new_products_price'];
  }

// BOF Bundled Products
////
// Return a product's stock
// TABLES: products
  function push_get_products_stock($products_id) {
    $products_id = push_get_prid($products_id);
    $stock_query = push_db_query("select products_quantity, products_bundle from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $stock_values = push_db_fetch_array($stock_query);
    if ($stock_values['products_bundle'] == 'yes') {
      $bundle_query = push_db_query("select subproduct_id, subproduct_qty from " . TABLE_PRODUCTS_BUNDLES . " where bundle_id = " . (int)$products_id);
      $bundle_stock = array();
      while ($bundle_data = push_db_fetch_array($bundle_query)) {
        $bundle_stock[] = intval(push_get_products_stock($bundle_data['subproduct_id']) / $bundle_data['subproduct_qty']);
      }
      return min($bundle_stock); // return quantity of least plentiful subproduct
    } else {
      return $stock_values['products_quantity'];
    }
  }
// EOF Bundled Products

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
  function push_check_stock($products_id, $products_quantity) {
    $stock_left = push_get_products_stock($products_id) - $products_quantity;
    $out_of_stock = '';

    if ($stock_left < 0) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $out_of_stock;
  }

////
// Break a word in a string if it is longer than a specified length ($len)
  function push_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0, $n=strlen($string); $i<$n; $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }
  
////
// Break a string and put three dotes at the end if it is longer than a specified length ($length)
	function push_short_string($string, $length) {
		if (strlen($string) > $length) {
			return substr ($string, 0, $length-2) . '...';
		} else {
			return $string;
		}
	}
////
// Break it in a more elegant manner
	function push_elegant_short_string($string, $length) {
		if (strlen($string) > $length) {
			//get last space near by length
			$substring = substr ($string, 0, $length-1);
			$poslength = strrpos($substring, ' ');
			$substring = substr ($substring, 0, $poslength+1);
			return  $substring . '...';
		} else {
			return $string;
		}
	}
////
// Return all HTTP GET variables, except those passed as a parameter
  function push_get_all_get_params($exclude_array = '') {
    global $_GET;

    if (!is_array($exclude_array)) $exclude_array = array();

    $get_url = '';
    if (is_array($_GET) && (sizeof($_GET) > 0)) {
      reset($_GET);
      while (list($key, $value) = each($_GET)) {
        if ( (strlen($value) > 0) && ($key != push_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
          $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
        }
      }
    }

    return $get_url;
  }

////
// Returns an array with countries
// TABLES: countries
  function push_get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if (push_not_null($countries_id)) {
      if ($with_iso_codes == true) {
        $countries = push_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
        $countries_values = push_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = push_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
        $countries_values = push_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name']);
      }
    } else {
	if (COUNTRY_LOCALNAME == 'true') {
	      $countries = push_db_query("select countries_id, countries_localname from " . TABLE_COUNTRIES . " where country_visible = '1' order by countries_localname");
	      while ($countries_values = push_db_fetch_array($countries)) {
	      $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_localname']);
	      }
	} else {
	      $countries = push_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " where country_visible = '1' order by countries_name");
	      while ($countries_values = push_db_fetch_array($countries)) {
	      $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_name']);
	      }
	}
    }

    return $countries_array;
  }

////
// Alias function to push_get_countries, which also returns the countries iso codes
  function push_get_countries_with_iso_codes($countries_id) {
    return push_get_countries($countries_id, true);
  }

////
// Generate a path to categories
  function push_get_path($current_category_id = '') {
    global $cPath_array;

    if (push_not_null($current_category_id)) {
      $cp_size = sizeof($cPath_array);
      if ($cp_size == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = push_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size-1)] . "'");
        $last_category = push_db_fetch_array($last_category_query);

        $current_category_query = push_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
        $current_category = push_db_fetch_array($current_category_query);

        if ($last_category['parent_id'] == $current_category['parent_id']) {
          for ($i=0; $i<($cp_size-1); $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i=0; $i<$cp_size; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_category_id;

        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    } else {
      $cPath_new = implode('_', $cPath_array);
    }

    return 'cPath=' . $cPath_new;
  }

////
// Returns the clients browser
  function push_browser_detect($component) {
    global $HTTP_USER_AGENT;

    return stristr($HTTP_USER_AGENT, $component);
  }

////
// Alias function to push_get_countries()
  function push_get_country_name($country_id) {
    $country_array = push_get_countries($country_id);

    return $country_array['countries_name'];
  }

////
// Returns the zone (State/Province) name
// TABLES: zones
  function push_get_zone_name($country_id, $zone_id, $default_zone) {
    $zone_query = push_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
    if (push_db_num_rows($zone_query)) {
      $zone = push_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }

////
// Returns the zone (State/Province) code
// TABLES: zones
  function push_get_zone_code($country_id, $zone_id, $default_zone) {
    $zone_query = push_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
    if (push_db_num_rows($zone_query)) {
      $zone = push_db_fetch_array($zone_query);
      return $zone['zone_code'];
    } else {
      return $default_zone;
    }
  }

////
// Wrapper function for round()
  function push_round($number, $precision) {
    if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.')+1)) > $precision)) {
      $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

      if (substr($number, -1) >= 5) {
        if ($precision > 1) {
          $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
        } elseif ($precision == 1) {
          $number = substr($number, 0, -1) + 0.1;
        } else {
          $number = substr($number, 0, -1) + 1;
        }
      } else {
        $number = substr($number, 0, -1);
      }
    }

    return $number;
  }


///Return matching Tax Class (needed for shipping modules)
	function push_get_matching_tax_class($rate){
	$mataxq=push_db_fetch_array(push_db_query("SELECT tax_class_id FROM tax_class WHERE tax_class_description LIKE '" . $rate . "%' "));
	return $mataxq['tax_class_id'];
	}


////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
  function push_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {
    //global $customer_zone_id, $customer_country_id;///////////////////SPPC-Opfer///////////////
	//
	// BOF Separate Pricing Per Customer, tax exempt modification/////////////////////////
    global $customer_zone_id, $customer_country_id, $sppc_customer_group_tax_exempt;

     if(!push_session_is_registered('sppc_customer_group_tax_exempt'))
	 {
	     $customer_group_tax_exempt = '0';
     }
	 else
	 {
	     $customer_group_tax_exempt = $sppc_customer_group_tax_exempt;
     }

     if ($customer_group_tax_exempt == '1')
	 {
	     return 0;
     }
// EOF Separate Pricing Per Customer, tax exempt modification///////////////////////////////////////////

    if ( ($country_id == -1) && ($zone_id == -1) ) {
      if (!push_session_is_registered('customer_id')) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $customer_country_id;
        $zone_id = $customer_zone_id;
      }
    }

    $tax_query = push_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' group by tr.tax_priority");
    if (push_db_num_rows($tax_query)) {
      $tax_multiplier = 1.0;
      while ($tax = push_db_fetch_array($tax_query)) {
        $tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
      }
      return ($tax_multiplier - 1.0) * 100;
    } else {
      return 0;
    }
  }

////
// Return the tax description for a zone / class
// TABLES: tax_rates;
  function push_get_tax_description($class_id, $country_id, $zone_id) {
    $tax_query = push_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
    if (push_db_num_rows($tax_query)) {
      $tax_description = '';
      while ($tax = push_db_fetch_array($tax_query)) {
        $tax_description .= $tax['tax_description'] . ' + ';
      }
      $tax_description = substr($tax_description, 0, -3);

      return $tax_description;
    } else {
      return TEXT_UNKNOWN_TAX_RATE;
    }
  }

/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
// BOF AAP V1.0
// actual attribute price adjustment (used with no price prefix only)
  function push_adjust_price($attribute, $price) {
    global $currencies;

    $adjustment = ($attribute-$price);
    return $adjustment;
    }
//EOF AAP V1.0
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

// Add tax to a products price
  function push_add_tax($price, $tax) {
    global $currencies;
//    if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) ) {
// BOF Separate Pricing Per Customer, show_tax modification
// next line was original code
//    if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) ) {
    global $sppc_customer_group_show_tax;
    global $sppc_customer_group_tax_exempt;
     if(!push_session_is_registered('customer_show_tax')) {
     $customer_group_show_tax = '1';
     } else {
     $customer_group_show_tax = $_SESSION['customer_show_tax'];
     }

 //    echo '<br>cg_tax_exempt: ';
 //    echo $sppc_customer_group_tax_exempt;
     if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) && ($customer_group_show_tax == '1')) {
// EOF Separate Pricing Per Customer, show_tax modification
      return push_round(round($price * 100) / 100 , $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + push_calculate_tax($price, $tax);
    } else {
      return push_round(round($price * 100) / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
  }
//XML NEEDS
 function push_add_xml_tax($price, $tax) {
    global $currencies;

     if ($tax > 0) {
// EOF Separate Pricing Per Customer, show_tax modification
      return push_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + push_calculate_tax($price, $tax);
    } else {
      return push_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
  }

// Calculates Tax rounding the result
  function push_calculate_tax($price, $tax) {
    global $currencies;

    return push_round( (round($price * 100) / 100 ) * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
  function push_count_products_in_category($category_id, $include_inactive = false) {
    $products_count = 0;
    if ($include_inactive == true) {
      $products_query = push_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$category_id . "'");
    } else {
      $products_query = push_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$category_id . "'");
    }
    $products = push_db_fetch_array($products_query);
    $products_count += $products['total'];

    $child_categories_query = push_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    if (push_db_num_rows($child_categories_query)) {
      while ($child_categories = push_db_fetch_array($child_categories_query)) {
        $products_count += push_count_products_in_category($child_categories['categories_id'], $include_inactive);
      }
    }

    return $products_count;
  }

////
// Return true if the category has subcategories
// TABLES: categories
  function push_has_category_subcategories($category_id) {
    $child_category_query = push_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    $child_category = push_db_fetch_array($child_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

////
// Returns the address_format_id for the given country
// TABLES: countries;
  function push_get_address_format_id($country_id) {
    $address_format_query = push_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
    if (push_db_num_rows($address_format_query)) {
      $address_format = push_db_fetch_array($address_format_query);
      return $address_format['format_id'];
    } else {
      return '1';
    }
  }

////
// Return a formatted address
// TABLES: address_format
  function push_address_format($address_format_id, $address, $html, $boln, $eoln) {
    $address_format_query = push_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
    $address_format = push_db_fetch_array($address_format_query);

    $company = push_output_string_protected($address['company']);
    if (isset($address['firstname']) && push_not_null($address['firstname'])) {
      $firstname = push_output_string_protected($address['firstname']);
      $lastname = push_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && push_not_null($address['name'])) {
      $firstname = push_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
    $street = push_output_string_protected($address['street_address']);
    $suburb = push_output_string_protected($address['suburb']);
    $city = push_output_string_protected($address['city']);
    $state = push_output_string_protected($address['state']);
    if (isset($address['country_id']) && push_not_null($address['country_id'])) {
      $country = push_get_country_name($address['country_id']);

      if (isset($address['zone_id']) && push_not_null($address['zone_id'])) {
        $state = push_get_zone_code($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && push_not_null($address['country'])) {
      $country = push_output_string_protected($address['country']['title']);
    } else {
      $country = '';
    }
    $postcode = push_output_string_protected($address['postcode']);
    $zip = $postcode;

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
	  $bb = '<strong>';
	  $eb = '</strong>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br />';
        $cr = '<br />';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
	  $bb = '';
	  $eb = '';
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    //if ($country == '') $country = push_output_string_protected($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (push_not_null($company)) ) {
      $address = '<strong>' . $company . '</strong>' . $cr . $address;
    }

    return $address;
  }

////
// Return a formatted address
// TABLES: customers, address_book
  function push_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
    $address_query = push_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
    $address = push_db_fetch_array($address_query);

    $format_id = push_get_address_format_id($address['country_id']);

    return push_address_format($format_id, $address, $html, $boln, $eoln);
  }

  function push_row_number_format($number) {
    if ( ($number < 10) && (substr($number, 0, 1) != '0') ) $number = '0' . $number;

    return $number;
  }

  function push_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
    global $languages_id;

    if (!is_array($categories_array)) $categories_array = array();

    $categories_query = push_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    while ($categories = push_db_fetch_array($categories_query)) {
      if($categories['categories_name']<>''){
      	$categories_array[] = array('id' => $categories['categories_id'],
                                  'text' => $indent . $categories['categories_name']);
      }

      if ($categories['categories_id'] != $parent_id) {
        $categories_array = push_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
      }
    }

    return $categories_array;
  }
  
  function push_get_top_categories($categories_array = '') {
    global $languages_id;

    if (!is_array($categories_array)) $categories_array = array();

    $categories_query = push_db_query("select c.categories_id, c.parent_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = 0 and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    while ($categories = push_db_fetch_array($categories_query)) {
    	if(($categories['categories_name']<>'')&&($categories['parent_id']==0)){
    		$categories_array[] = array('id' => $categories['categories_id'],
                                  'text' => $indent . $categories['categories_name']);
    	}
    }

    return $categories_array;
  }
  function push_get_manufacturers($manufacturers_array = '') {
    if (!is_array($manufacturers_array)) $manufacturers_array = array();

    $manufacturers_query = push_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = push_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
    }

    return $manufacturers_array;
  }

////
// Return all subcategory IDs
// TABLES: categories
  function push_get_subcategories(&$subcategories_array, $parent_id = 0) {
    $subcategories_query = push_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
    while ($subcategories = push_db_fetch_array($subcategories_query)) {
      $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
      if ($subcategories['categories_id'] != $parent_id) {
        push_get_subcategories($subcategories_array, $subcategories['categories_id']);
      }
    }
  }

// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
  function push_date_long($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
  function push_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || empty($raw_date) ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
  }

////
// Parse search string into indivual objects
  function push_parse_search_string($search_str = '', &$objects) {
    $search_str = trim(strtolower($search_str));

// Break up $search_str on whitespace; quoted string will be reconstructed later
    $pieces = split('[[:space:]]+', $search_str);
    $objects = array();
    $tmpstring = '';
    $flag = '';

    for ($k=0; $k<count($pieces); $k++) {
      while (substr($pieces[$k], 0, 1) == '(') {
        $objects[] = '(';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 1);
        } else {
          $pieces[$k] = '';
        }
      }

      $post_objects = array();

      while (substr($pieces[$k], -1) == ')')  {
        $post_objects[] = ')';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 0, -1);
        } else {
          $pieces[$k] = '';
        }
      }

// Check individual words

      if ( (substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"') ) {
        $objects[] = trim($pieces[$k]);

        for ($j=0; $j<count($post_objects); $j++) {
          $objects[] = $post_objects[$j];
        }
      } else {
/* This means that the $piece is either the beginning or the end of a string.
   So, we'll slurp up the $pieces and stick them together until we get to the
   end of the string or run out of pieces.
*/

// Add this word to the $tmpstring, starting the $tmpstring
        $tmpstring = trim(ereg_replace('"', ' ', $pieces[$k]));

// Check for one possible exception to the rule. That there is a single quoted word.
        if (substr($pieces[$k], -1 ) == '"') {
// Turn the flag off for future iterations
          $flag = 'off';

          $objects[] = trim($pieces[$k]);

          for ($j=0; $j<count($post_objects); $j++) {
            $objects[] = $post_objects[$j];
          }

          unset($tmpstring);

// Stop looking for the end of the string and move onto the next word.
          continue;
        }

// Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
        $flag = 'on';

// Move on to the next word
        $k++;

// Keep reading until the end of the string as long as the $flag is on

        while ( ($flag == 'on') && ($k < count($pieces)) ) {
          while (substr($pieces[$k], -1) == ')') {
            $post_objects[] = ')';
            if (strlen($pieces[$k]) > 1) {
              $pieces[$k] = substr($pieces[$k], 0, -1);
            } else {
              $pieces[$k] = '';
            }
          }

// If the word doesn't end in double quotes, append it to the $tmpstring.
          if (substr($pieces[$k], -1) != '"') {
// Tack this word onto the current string entity
            $tmpstring .= ' ' . $pieces[$k];

// Move on to the next word
            $k++;
            continue;
          } else {
/* If the $piece ends in double quotes, strip the double quotes, tack the
   $piece onto the tail of the string, push the $tmpstring onto the $haves,
   kill the $tmpstring, turn the $flag "off", and return.
*/
            $tmpstring .= ' ' . trim(ereg_replace('"', ' ', $pieces[$k]));

// Push the $tmpstring onto the array of stuff to search for
            $objects[] = trim($tmpstring);

            for ($j=0; $j<count($post_objects); $j++) {
              $objects[] = $post_objects[$j];
            }

            unset($tmpstring);

// Turn off the flag to exit the loop
            $flag = 'off';
          }
        }
      }
    }

// add default logical operators if needed
    $temp = array();
    for($i=0; $i<(count($objects)-1); $i++) {
      $temp[] = $objects[$i];
      if ( ($objects[$i] != 'and') &&
           ($objects[$i] != 'or') &&
           ($objects[$i] != '(') &&
           ($objects[$i+1] != 'and') &&
           ($objects[$i+1] != 'or') &&
           ($objects[$i+1] != ')') ) {
        $temp[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
      }
    }
    $temp[] = $objects[$i];
    $objects = $temp;

    $keyword_count = 0;
    $operator_count = 0;
    $balance = 0;
    for($i=0; $i<count($objects); $i++) {
      if ($objects[$i] == '(') $balance --;
      if ($objects[$i] == ')') $balance ++;
      if ( ($objects[$i] == 'and') || ($objects[$i] == 'or') ) {
        $operator_count ++;
      } elseif ( ($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')') ) {
        $keyword_count ++;
      }
    }

    if ( ($operator_count < $keyword_count) && ($balance == 0) ) {
      return true;
    } else {
      return false;
    }
  }

////
// Check date
  function push_checkdate($date_to_check, $format_string, &$date_array) {
    $separator_idx = -1;

    $separators = array('-', ' ', '/', '.');
    $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
    $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $format_string = strtolower($format_string);

    if (strlen($date_to_check) != strlen($format_string)) {
      return false;
    }

    $size = sizeof($separators);
    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($date_to_check, $separators[$i]);
      if ($pos_separator != false) {
        $date_separator_idx = $i;
        break;
      }
    }

    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($format_string, $separators[$i]);
      if ($pos_separator != false) {
        $format_separator_idx = $i;
        break;
      }
    }

    if ($date_separator_idx != $format_separator_idx) {
		return false;
    }

    if ($date_separator_idx != -1) {
      $format_string_array = explode( $separators[$date_separator_idx], $format_string );
      if (sizeof($format_string_array) != 3) {
        return false;
      }

      $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
      if (sizeof($date_to_check_array) != 3) {
        return false;
      }

      $size = sizeof($format_string_array);
      for ($i=0; $i<$size; $i++) {
        if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
        if ($format_string_array[$i] == 'tt') $day = $date_to_check_array[$i];
        if ( ($format_string_array[$i] == 'jjjj') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
      }
    } else {
      if (strlen($format_string) == 8 || strlen($format_string) == 9) {
        $pos_month = strpos($format_string, 'mmm');
        if ($pos_month != false) {
          $month = substr( $date_to_check, $pos_month, 3 );
          $size = sizeof($month_abbr);
          for ($i=0; $i<$size; $i++) {
            if ($month == $month_abbr[$i]) {
              $month = $i;
              break;
            }
          }
        } else {
          $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
        }
      } else {
        return false;
      }

      $day = substr($date_to_check, strpos($format_string, 'tt'), 2);
      $year = substr($date_to_check, strpos($format_string, 'jjjj'), 4);
    }

    if (strlen($year) != 4) {
      return false;
    }

    if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
      return false;
    }

    if ($month > 12 || $month < 1) {
      return false;
    }

    if ($day < 1) {
      return false;
    }

    if (push_is_leap_year($year)) {
      $no_of_days[1] = 29;
    }

    if ($day > $no_of_days[$month - 1]) {
      return false;
    }

    $date_array = array($year, $month, $day);

    return true;
  }

////
// Check if year is a leap year
  function push_is_leap_year($year) {
    if ($year % 100 == 0) {
      if ($year % 400 == 0) return true;
    } else {
      if (($year % 4) == 0) return true;
    }

    return false;
  }

////
// Return table heading with sorting capabilities
  function push_create_sort_heading($sortby, $colnum, $heading) {
    global $PHP_SELF;

    $sort_prefix = '';
    $sort_suffix = '';

    if ($sortby) {
      $sort_prefix = '<a href="' . push_href_link(basename($_SERVER['SCRIPT_NAME']), push_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . push_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">' ;
      $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
  }

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
  function push_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = push_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
    while ($parent_categories = push_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        push_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }

////
// Construct a category path to the product
// TABLES: products_to_categories
  function push_get_product_path($products_id) {
    $cPath = '';

    $category_query = push_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
    if (push_db_num_rows($category_query)) {
      $category = push_db_fetch_array($category_query);

      $categories = array();
      push_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (push_not_null($cPath)) $cPath .= '_';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }

////
// Return a product ID with attributes
  function push_get_uprid($prid, $params) {
    if (is_numeric($prid)) {
      $uprid = $prid;

      if (is_array($params) && (sizeof($params) > 0)) {
        $attributes_check = true;
        $attributes_ids = '';

        reset($params);
        while (list($option, $value) = each($params)) {
          if (is_numeric($option) && is_numeric($value)) {
            $attributes_ids .= '{' . (int)$option . '}' . (int)$value;
          } else {
            $attributes_check = false;
            break;
          }
        }

        if ($attributes_check == true) {
          $uprid .= $attributes_ids;
        }
      }
    } else {
      $uprid = push_get_prid($prid);

      if (is_numeric($uprid)) {
        if (strpos($prid, '{') !== false) {
          $attributes_check = true;
          $attributes_ids = '';

// strpos()+1 to remove up to and including the first { which would create an empty array element in explode()
          $attributes = explode('{', substr($prid, strpos($prid, '{')+1));

          for ($i=0, $n=sizeof($attributes); $i<$n; $i++) {
            $pair = explode('}', $attributes[$i]);

            if (is_numeric($pair[0]) && is_numeric($pair[1])) {
              $attributes_ids .= '{' . (int)$pair[0] . '}' . (int)$pair[1];
            } else {
              $attributes_check = false;
              break;
            }
          }

          if ($attributes_check == true) {
            $uprid .= $attributes_ids;
          }
        }
      } else {
        return false;
      }
    }

    return $uprid;
  }

////
// Return a product ID from a product ID with attributes
  function push_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    if (is_numeric($pieces[0])) {
      return $pieces[0];
    } else {
      return false;
    }
  }

////
// Return a customer greeting
  function push_customer_greeting() {
    global  $customer_first_name;

    if (push_session_is_registered('customer_first_name') && push_session_is_registered('customer_id')) {
      $greeting_string = sprintf(TEXT_GREETING_PERSONAL, push_output_string_protected($customer_first_name), push_href_link(FILENAME_PRODUCTS_NEW));
    } else {
      $greeting_string = sprintf(TEXT_GREETING_GUEST, push_href_link(FILENAME_LOGIN, '', 'SSL'), push_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }

    return $greeting_string;
  }

////
//! Send email (text/html) using MIME
// This is the central mail function. The SMTP Server should be configured
// correct in php.ini
// Parameters:
// $to_name           The name of the recipient, e.g. "Jan Wildeboer"
// $to_email_address  The eMail address of the recipient,
//                    e.g. jan.wildeboer@gmx.de
// $email_subject     The subject of the eMail
// $email_text        The text of the eMail, may contain HTML entities
// $from_email_name   The name of the sender, e.g. Shop Administration
// $from_email_adress The eMail address of the sender,
//                    e.g. info@mytepshop.com

  function push_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    if (SEND_EMAILS != 'true') return false;

    // Instantiate a new mail object
    $message = new email(array('X-Mailer: osCommerce Mailer'));

    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
      $message->add_html($email_text, $text);
    } else {
      $message->add_text($text);
    }
	$from_email_name=utf8_decode($from_email_name);
	$email_subject=utf8_decode($email_subject);
    // Send message
    $message->build_message();
    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
  }

////
// Check if product has attributes
  function push_has_product_attributes($products_id) {
    $attributes_query = push_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "'");
    $attributes = push_db_fetch_array($attributes_query);

    if ($attributes['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

////
// Get the number of times a word/character is present in a string
  function push_word_count($string, $needle) {
    $temp_array = split($needle, $string);

    return sizeof($temp_array);
  }

  function push_count_modules($modules = '') {
    $count = 0;

    if (empty($modules)) return $count;

    $modules_array = split(';', $modules);

    for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
      $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

      if (is_object($GLOBALS[$class])) {
        if ($GLOBALS[$class]->enabled) {
          $count++;
        }
      }
    }

    return $count;
  }

  function push_count_payment_modules() {
    return push_count_modules(MODULE_PAYMENT_INSTALLED);
  }

  function push_count_shipping_modules() {
    return push_count_modules(MODULE_SHIPPING_INSTALLED);
  }

function push_count_giftwrap_modules() {
    return push_count_modules(MODULE_GIFTWRAP_INSTALLED);
  }

  function push_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $char = push_rand(0,9);
      } else {
        $char = chr(push_rand(0,255));
      }
      if ($type == 'mixed') {
        if (eregi('^[a-z0-9]$', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (eregi('^[a-z]$', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (ereg('^[0-9]$', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }

  function push_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
    if (!is_array($exclude)) $exclude = array();

    $get_string = '';
    if (sizeof($array) > 0) {
      while (list($key, $value) = each($array)) {
        if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
          $get_string .= $key . $equals . $value . $separator;
        }
      }
      $remove_chars = strlen($separator);
      $get_string = substr($get_string, 0, -$remove_chars);
    }

    return $get_string;
  }

  function push_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

////
// Output the tax percentage with optional padded decimals
  function push_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    if (strpos($value, '.')) {
      $loop = true;
      while ($loop) {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          $loop = false;
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }
        }
      }
    }

    if ($padding > 0) {
      if ($decimal_pos = strpos($value, '.')) {
        $decimals = strlen(substr($value, ($decimal_pos+1)));
        for ($i=$decimals; $i<$padding; $i++) {
          $value .= '0';
        }
      } else {
        $value .= '.';
        for ($i=0; $i<$padding; $i++) {
          $value .= '0';
        }
      }
    }

    return $value;
  }

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
  function push_currency_exists($code) {
    $code = push_db_prepare_input($code);

    $currency_code = push_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . push_db_input($code) . "'");
    if (push_db_num_rows($currency_code)) {
      return $code;
    } else {
      return false;
    }
  }

  function push_string_to_int($string) {
    return (int)$string;
  }

////
// Parse and secure the cPath parameter values
  function push_parse_category_path($cPath) {
// make sure the category IDs are integers
    $cPath_array = array_map('push_string_to_int', explode('_', $cPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
    $tmp_array = array();
    $n = sizeof($cPath_array);
    for ($i=0; $i<$n; $i++) {
      if (!in_array($cPath_array[$i], $tmp_array)) {
        $tmp_array[] = $cPath_array[$i];
      }
    }

    return $tmp_array;
  }

////
// Return a random value
  function push_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

  function push_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0) {
    setcookie($name, $value, $expire, $path, (push_not_null($domain) ? $domain : ''), $secure);
  }

  function push_get_ip_address() {
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

    return $ip;
  }

  function push_count_customer_orders($id = '', $check_session = true, $dblink='') {
    global  $customer;

    if (is_numeric($id) == false) {
      if (push_session_is_registered('customer_id')) {
        $id = $_SESSION['customer_id'];
      } else {
        return 0;
      }
    }

  
	$orders_check_query = push_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$id . "'");
    $orders_check = push_db_fetch_array($orders_check_query);
	if($orders_check['total'] == 0 ){
	$orders_check_query = push_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer->selectline_customers_id . "'");
    $orders_check = push_db_fetch_array($orders_check_query);
	}

    return $orders_check['total'];
  }


  function push_count_customer_address_book_entries($id = '', $check_session = true) {

    if (is_numeric($id) == false) {
      if (push_session_is_registered('customer_id')) {
        $id = $_SESSION['customer_id'];
      } else {
        return 0;
      }
    }

    if ($check_session == true) {
      if ( (push_session_is_registered('customer_id') == false) || ($id != $_SESSION['customer_id']) ) {
        return 0;
      }
    }

    $addresses_query = push_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$id . "'");
    $addresses = push_db_fetch_array($addresses_query);

    return $addresses['total'];
  }

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
  function push_convert_linefeeds($from, $to, $string) {
    if ((PHP_VERSION < "4.0.5") && is_array($from)) {
      return ereg_replace('(' . implode('|', $from) . ')', $to, $string);
    } else {
      return str_replace($from, $to, $string);
    }
  }
// Base Price Insert
// Base Price, Specials and Tax Included^
  function push_get_products_display_price($products_id, $base_price=false, $final_price=false) {
    global $currencies, $sppc_customer_group_id, $sppc_customer_group_show_tax;
/*
    if(!push_session_is_registered('sppc_customer_group_id')) {
      $customer_group_id = '0';
    } else {
      $customer_group_id = $sppc_customer_group_id;
    }
*/
    $customers_groupush_query = push_db_query("select customer_show_tax, customer_tax_exempt from " . TABLE_CUSTOMERS . " where customers_id =  '" . $_SESSION['customer_id'] . "'");
    $customers_groups = push_db_fetch_array($customers_groupush_query);

    $product_check_query = push_db_query("select products_tax_class_id, products_price, products_base_price, products_base_unit from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'" . " limit 1");
    $product_check = push_db_fetch_array($product_check_query);

    if ($_SESSION['customer_id'] !='0') {
      $customer_group_price_query = push_db_query("select products_price from " . TABLE_PRODUCTS_CUSTOMERS . " where products_id = '" . $products_id . "' and customers_id =  '" . $_SESSION['customer_id'] . "'");
      if ($customer_group_price = push_db_fetch_array($customer_group_price_query)) {
        $product_check['products_price'] = $customer_group_price['products_price'];
      }
    }
    $tax_rate = push_get_tax_rate($product_check['products_tax_class_id']);

    $display_price='';

    if ($final_price != false) {
      if ($base_price == true) {
        if ($product_check['products_base_price'] != 0) {
          $display_price =  $currencies->format($final_price * $product_check['products_base_price']) . '/&thinsp;' . $product_check['products_base_unit'] ;
        }
      } else {
        $display_price = $currencies->format($final_price);
      }
    } else if ($product_check['products_price'] != 0) {
      if ($base_price == true) {
        if ($product_check['products_base_price'] != 0) {
          if ($special = push_get_products_special_price($products_id)) {
            $display_price = '<span class="smallText">'  . $currencies->format(push_add_tax($special, $tax_rate) * $product_check['products_base_price']) . '/&thinsp;' . $product_check['products_base_unit'] . '</span>';
          } else {
            $display_price = '<span class="smallText">'  . $currencies->format(push_add_tax($product_check['products_price'], $tax_rate) * $product_check['products_base_price']) . '/&thinsp;' . $product_check['products_base_unit'] . '</span>';
          }
        }
      } else {
        $display_price = $currencies->format(push_add_tax($product_check['products_price'], $tax_rate));
        if ($add_special = push_get_products_special_price($products_id)) {
          $display_price = '<s>' . $display_price . '</s> <span class="productSpecialPrice"> ' . $currencies->format(push_add_tax($add_special, $tax_rate)) .  '</span> ';
        }
      }
    }
    if (TAX_INFO_ONOFF == 'Enabled') {
      if($base_price == false) {
        if (($customers_groups['customers_group_tax_exempt'] == 1) and ($customers_groups['customers_group_show_tax'] == 0)) {
          $display_price .= '<br><span class="tax_info">' . sprintf(STEUERFREI_OHNE_MWST, $tax_rate, push_href_link(FILENAME_SHIPPING)) . '</span>';
        } elseif (($customers_groups['customers_group_tax_exempt'] == 0) and ($customers_groups['customers_group_show_tax'] == 0)) {
          $display_price .= '<br><span class="tax_info">' . sprintf(OHNE_MWST, $tax_rate, push_href_link(FILENAME_SHIPPING)) . '</span>';
        } elseif  ($customers_groupush_id == 0) {
          $display_price .= '<br><span class="tax_info">' . sprintf(STANDARD_MWST, $tax_rate, push_href_link(FILENAME_SHIPPING)) . '</span>';
        }
      }
    }
    return $display_price;
  }
// Base Price End



// Return a product's description
// TABLES: products description
function push_get_products_description($product_id) {
global $languages_id;

$product_query = push_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $languages_id . "'");
$product_description = push_db_fetch_array($product_query);

return $product_description['products_description'];
}

// BOF PA
function push_get_products_availability($products_availability_array = '') {
	if (!is_array($products_availability_array)) $products_availability_array = array();

	$products_availability_query = push_db_query("select products_availability_id, products_availability_name from " . TABLE_PRODUCTS_AVAILABILITY . " order by products_availability_name");
	while ($products_availability = push_db_fetch_array($products_availability_query)) {
	$products_availability_array[] = array('id' => $products_availability['products_availability_id'], 'text' => $products_availability['products_availability_name']);
	}

	return $products_availability_array;
}
// EOF PA

// BOF Featured Products
function push_array_values_to_string($array, $separator = ',') {
	$get_string = '';
	if (sizeof($array) > 0) {
		while (list($key, $value) = each($array)) {
				$get_string .= $value . $separator;
		}
		$remove_chars = strlen($separator);
		$get_string = substr($get_string, 0, -$remove_chars);
	}
	return $get_string;
}
// EOF Featured Products

// BOF Products Description Hack
//
// grabs a length from a string, but goes back to the last space if it cut into
// the middle of a string.  If the string is longer than that, cut, then add '...'
// to the end of the string, if the $more == 1
	function osc_trunc_string($str="",$len=150,$more=1)
	{
		if ($str=="") return $str;
		if (is_array($str)) return $str;
		$str = trim($str);
		// if it's les than the size given, then return it
		if (strlen($str) <= $len) return $str;
		// else get that size of text
		$str = substr($str,0,$len);
		// backtrack to the end of a word
		if ($str != "") {
			// check to see if there are any spaces left
			if (!substr_count($str," ")) {
				if ($more) $str .= "...";
				return $str;
			}
			// backtrack
			while(strlen($str) && ($str[strlen($str)-1] != " ")) {
				$str = substr($str,0,-1);
			}
			$str = substr($str,0,-1);
			if ($more) $str .= "...";
		}
		return $str;
	}
// EOF Products Description Hack


// begin Bundled Products
// returns an array of all non-bundle products in the bundle with their quantities including products contained in nested bundles
  function get_all_bundle_products($bundle_id) {
    $bundle_query = $bundle_query = push_db_query('select pb.subproduct_id, pb.subproduct_qty, p.products_bundle from ' . TABLE_PRODUCTS_BUNDLES . ' pb, ' . TABLE_PRODUCTS . ' p where p.products_id = pb.subproduct_id and bundle_id = ' . (int)$bundle_id);
    $product_list = array();
    while ($bundle = push_db_fetch_array($bundle_query)) {
      if ($bundle['products_bundle'] == 'yes') {
        $bundle_list = get_all_bundle_products($bundle['subproduct_id']);
        foreach ($bundle_list as $id => $qty) {
          $product_list[$id] += $qty;
        }
      } else {
        $product_list[$bundle['subproduct_id']] += $bundle['subproduct_qty'];
      }
    }
    return $product_list;
  }
// end Bundled Products

//BOF jquery price-function
function get_pb_string($value){
	global $currencies, $sppc_customer_group_id, $sppc_customer_group_show_tax;
	    
/*	    
	if(!push_session_is_registered('sppc_customer_group_id')) {
      $customer_group_id = '0';
    } else {
      $customer_group_id = $sppc_customer_group_id;
    }
*/
   // $customers_groupush_query = push_db_query("select customers_group_show_tax, customers_group_tax_exempt from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id =  '" . $customer_group_id . "'");
   // $customers_groups = push_db_fetch_array($customers_groupush_query);
	   $tax_rate = push_get_tax_rate($value);
 		if($customers_groupush_id == 0) {
          $outputstring = '<br><span class="tax_info">' . sprintf(STANDARD_MWST, $tax_rate, push_href_link(FILENAME_SHIPPING)) . '</span>';
        }
 
    return $outputstring;    
}
  
  
//EOF jquery

//redesign - Imageprocessor

function push_copy_image($original, $copy, $newHeight, $newWidth){
	//generate the small image 
	if(class_exists('SimpleImage')){
	 $img = new SimpleImage();
	}
	$img->load($original);
	if($img->getHeight() > $img->getWidth())
		$img->resizeToHeight($newHeight);
	else
	$img->resizeToWidth($newWidth);
	$img->save($copy);
	
}
function push_make_cat_ul_list($id="fehlt", $style="")
	{
		global $cPath_array, $languages_id;

		$_SESSION['level'] = 0;

	if ( ( isset($cPath_array) ) && ($id=='fehlt')) {
			// get selected category only
			$parentQuery = 'AND (c.categories_id = "' . $cPath_array[0] . '" OR c.parent_id = "' . $cPath_array[0] . '"';
			for ($i = 0; $i < count($cPath_array); $i++) {
				$parentQuery .= ' OR c.parent_id = "' . $cPath_array[$i] . '"';
			}
			//sort order
			$so= " cd.categories_name";

		} else {
			//sort order
			$so= " c.categories_id";
			// get all categories
			$parentQuery = 'AND (c.parent_id = "0"';			
		}
		$parentQuery .= ')';
		
		$categoriesQuery = push_db_query('SELECT c.categories_id, cd.categories_name, c.parent_id FROM ' . TABLE_CATEGORIES . ' c, ' . TABLE_CATEGORIES_DESCRIPTION . ' cd WHERE c.categories_id = cd.categories_id AND cd.language_id="' . (int)$languages_id .'" ' . $parentQuery . ' ORDER BY ' . $so . ';');
		$c = 0;
		while ($row = push_db_fetch_array($categoriesQuery))
		{
		    if(!push_count_products_in_category($row['categories_id']) == 0)
			  {//0 products
				$categories[ $row['parent_id'] ][ $row['categories_id'] ] = $row['categories_name'];
			  }
		  $c++;
		}
		$output='';
		// creating nested lists
		if($id<>'fehlt')
		{
			$output .= '<div id="' . $id. '" style="' . $style . '">';
		}
		$output .= push_make_cat_ul_branch($categories, 0, 0, $id);

		if($id<>'fehlt')
		{
			$output .= '</div>';
		}

		$output .= "\n";

		return $output;
	}



	function push_make_cat_ul_branch($categories, $rootCatID, $level, $id='fehlt')
	{
		global $cPath_array;
		$output='';
		
		// collect only categories with $rootCatID as parentID
		$list = $categories[$rootCatID];
		if (sizeof($list) > 0 && $rootCatID != 0)
		{
			$output .= "\n";
		}

		foreach ($list as $catID => $catName)
		{
			// is parent? class="tx_13_20"
			$output .= "\n" . '<a class="tx_13_20 ' . ( push_has_category_subcategories($catID) ? CLASSNAME_FOR_PARENT : '');

			if ($_SESSION['level'] != $level)
				$_SESSION['level'] = $level;

			if (!$level)
			{
				unset($_SESSION['cPath_set']);
				$_SESSION['cPath_set'][0] = $catID;
				$cPath_new = 'cPath=' . $catID;
			}
			else
			{
				$_SESSION['cPath_set'][$level] = $catID;
				$cPath_new = 'cPath=' . implode( '_', array_slice($_SESSION['cPath_set'], 0, $level + 1) );
			}

			// some category selected? If not, set levels
			if (!isset($cPath_array) || ($id<>'fehlt')) {
				$output .= ' allCategories cat_' . $catID;
			} else {
				$output .= ' lvl' . $level . " pc_" . $catID;
			}

			// is active?
			$active = false;
			if (isset($cPath_array) && in_array($catID, $cPath_array)) {
				$output .= ' ' . CLASSNAME_FOR_ACTIVE;
				$active = true;
			}

			// is selected?
			if ($active && (sizeof($cPath_array) - 1) == $level)
			{
				$output .= ' ' . CLASSNAME_FOR_SELECTED;
			}

			$output .= ( (isset($cPath_array) && in_array($catID, $cPath_array) && ($id<>'fehlt'))? ' skipthis' : '') ;
			$output .=  '" title="' . $catName . '" href="' . push_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . $catName . '</a>';

			if (SHOW_COUNTS == 'true')
			{
				$products_in_category = push_count_products_in_category($catID);
				if ($products_in_category > 0)
				{
					$output .= '&nbsp;' .'</a>'. '(' . $products_in_category . ')';
				}
			}

			if ( isset($categories[$catID]) )
			{
				$output .= push_make_cat_ul_branch($categories, $catID, $level + 1);
			}
		}

		if (sizeof($list) > 0 && $rootCatID != 0)
		{
			$output .= "\n";
		}
		return $output;
	}
  
	function get_categories_default_image($pid){
		/**
		 * pick the corresponding image from db and return image html
		 */
		$cdpi=push_db_fetch_array(push_db_query("SELECT cpi.categories_image FROM categories_preview_images cpi JOIN products_to_categories p2c ON cpi.categories_id = p2c.categories_id WHERE p2c.products_id='" . $pid . "';"));
		if( (!is_dir(DIR_WS_IMAGES . 'fullsize/' . $cdpi['categories_image'])) && file_exists(DIR_WS_IMAGES . 'fullsize/' . $cdpi['categories_image']))
		{
			return $cdpi['categories_image'];
		}
		else
		{
			return 'default.jpg';
		}
	}

function is_selected($shop){
		$seite['shop'] = array (	FILENAME_DEFAULT,	
									FILENAME_PRODUCT_INFO,
									FILENAME_ACCOUNT_HISTORY_INFO,
									FILENAME_SHOPPING_CART,
									FILENAME_CHECKOUT_SHIPPING,
									FILENAME_CHECKOUT_PAYMENT,
									FILENAME_CHECKOUT_CONFIRMATION,
									FILENAME_CHECKOUT_SUCCESS,
									FILENAME_CHECKOUT_SHIPPING_ADDRESS,
									FILENAME_CHECKOUT_PAYMENT_ADDRESS,
									'ccb-app.php',
									FILENAME_ACCOUNT,
									FILENAME_ACCOUNT_INFO,
									FILENAME_ACCOUNT_EDIT,
									FILENAME_ACCOUNT_PASSWORD,
									FILENAME_ADDRESS_BOOK,
									FILENAME_ADDRESS_BOOK_PROCESS,
									FILENAME_SORTIMENT,
									FILENAME_ACCOUNT_HISTORY,
									'gv_info.php',
									'gv_send.php',
									'gv_faq.php',
									'account_torten_history.php',
									'account_torten_history_info.php'
									);   
		$seite['produkte'] = array( FILENAME_MANUFACTURERS,
									'brands.php');
		$seite['wir'] = array(	FILENAME_BKR,
								'profil.php',
								'philosophie.php',
								'geschichte.php',
								'zertifizierungen.php',
								'presse.php',
								'pressemeldungen.php',
								'pressemappe.php',
								FILENAME_JOBS
								);
								
		$seite['cafe'] =  array(FILENAME_CAFE,
								'coffee.php',
								'Kakaosorten.php',
								'roesten.php',
								'zubereitungsmethoden.php',
								'manufaktur.php',
								'chocolaterie.php',
								'torten.php',
								'sortiment.php',
								FILENAME_FIRMENPRAESENTE,
								'menu.php',
								'raeumlichkeiten.php',
								FILENAME_VERANSTALTUNGEN,
								FILENAME_CATERING,
								'oeffnungszeiten.php'								
								);
								
		// !!! if you change service or informationen arrays, change also the arrays in static_menu.php !!! 
								
		$seite['service'] = array(	FILENAME_PRODUKTSCHULUNG,
									FILENAME_EXISTENZGRUENDUNG,
									FILENAME_CALENDAR,
									FILENAME_SAISONKARTE,
									FILENAME_HOTCONTENT,
									FILENAME_KATALOG);
									
		$seite['informationen'] = array(FILENAME_NEUKUNDENINFORMATION,
										FILENAME_RABATTE,
										FILENAME_SHIPPING, 
										FILENAME_DOWNLOADCENTER,							
										FILENAME_FAQ,
										FILENAME_FAQ_PRODUKTE,
										FILENAME_FAQ_LIEFERUNG,
										FILENAME_FAQ_RECHNUNGSLEGUNG,
										FILENAME_CONTACT_US,
										FILENAME_RUECKRUFSERVICE,
										FILENAME_IMPRESSUM,
										FILENAME_PRIVACY,
										FILENAME_AGB,
										FILENAME_UEBER_UNS,
										FILENAME_JOBS,
										'partner.php');
	
					if(in_array( basename( $_SERVER['PHP_SELF'] ), $seite[$shop])){
						echo 'selected';
					}else{
						echo 'gradientblue';
					}
	
	}

/**
 * gives the opening link tag back 
 */
	function push_product_link_opener($produktid, $cssclass='', $zielseite=FILENAME_PRODUCT_INFO){
		global $keywordstr,$key_categories_id;
		if (isset($_GET['manufacturers_id'])){
			echo '<a class="' . $cssclass . '" href="' . generate_url_rewritten_string(push_href_link($zielseite, 'manufacturers_id=' . $_GET['manufacturers_id'] .'&'. ($_GET['filter']? 'filter=' . $_GET['filter'] . '&' : '') . 'products_id=' . $produktid) ). '">';
		}elseif (isset($_GET['specials'])){
			if($_GET['specials']=='mhd'){
				echo '<a class="' . $cssclass . '"  href="' . generate_url_rewritten_string(push_href_link($zielseite, 'specials=mhd&' . ($_GET['filter']? 'filter=' . $_GET['filter'] . '&' : '') . 'products_id=' . $produktid)) . '">';
			}else{
				echo '<a class="' . $cssclass . '"  href="' . generate_url_rewritten_string(push_href_link($zielseite, 'specials=week&' . ($_GET['filter']? 'filter=' . $_GET['filter'] . '&' : '') . 'products_id=' . $produktid)) . '">';
			}
		}elseif (isset($_GET['newproducts'])){
			echo '<a class="' . $cssclass . '"  href="' . generate_url_rewritten_string(push_href_link($zielseite, 'newproducts=all&' . ($_GET['filter']? 'filter=' . $_GET['filter'] . '&' : '') . 'products_id=' . $produktid)) . '">';
		}elseif (isset($_GET['keywords'])){
			echo '<a class="' . $cssclass . '"  href="' . generate_url_rewritten_string(push_href_link($zielseite, 'keywords=' . $keywordstr .'&categories_id=' . $key_categories_id . ((isset($_GET['search_in_description']))? '&search_in_description=1':'') .'&products_id=' . $produktid)) . '">';
		}else{
			echo '<a class="' . $cssclass . '"  href="' . generate_url_rewritten_string(push_href_link($zielseite, ($_GET['cPath']? 'cPath=' . $_GET['cPath'] . '&' : ''). (isset($_GET['filter'])? 'filter=' . $_GET['filter'] . '&' : '') . 'products_id=' . $produktid )) . '">';
		}
	}
	
	function push_get_product_listing_image($image, $product){
		if( ($image =='') || ($image =='bilder/noch_kein_bild.png') ){
			echo '		<span></span>';
			push_product_link_opener($product);
			echo '<img src="' .DIR_WS_IMAGES  . 'small/bilder/' . get_categories_default_image($product) . '" /></a>';
		}else{
		//generate smaller copies of small files 
		//sort of general function 
			if(!file_exists(DIR_WS_IMAGES . 'small/'. $image)){
				push_copy_image(DIR_WS_IMAGES . 'full_size/' . $image, DIR_WS_IMAGES  . 'small/'. $image, 140, 140);
			}
			
/*			if($ab_test_group==0){
				echo '		<span></span><a href="' . push_href_link(DIR_WS_IMAGES . 'full_size/' . $image) . '" class="bkrpop"><img src="' .DIR_WS_IMAGES  . 'small/' . $image . '" /></a>';
			}else{ */
				echo '		<span></span>';
				push_product_link_opener($product);		
				echo '<img src="' .DIR_WS_IMAGES  . 'small/' . $image . '" /></a>';
			/*}*/
		}

	}

function push_get_availability_info($products_availability){
	global $languages_id; 
	$igreen = array('1','4');
	$iyellow = array('5','6','7','8','9','13','15');
	$ired = array('10','11','12','16');
	$praiq = push_db_query("select e.products_availability_name from " . TABLE_PRODUCTS_AVAILABILITY . " e where e.products_availability_id = '" . (int)$products_availability . "' and e.language_id = '" . (int)$languages_id . "'");
	$prai = push_db_fetch_array($praiq);
	$products_availability_name = strip_tags($prai['products_availability_name']);
	$paid=$products_availability;

	if(in_array($paid,$igreen)){
		$paimage= '<image src="images/pixel_trans.gif" class="BKR icon_lieferbar_gruen" border="0">';
	}elseif(in_array($paid,$iyellow)){
		$paimage= '<image src="images/pixel_trans.gif" class="BKR icon_lieferbar_gelb" border="0">';	
	}elseif(in_array($paid,$ired)){
		$paimage= '<image src="images/pixel_trans.gif" class="BKR icon_lieferbar_rot" border="0">';		
	}else{
		$paimage= '<image src="images/pixel_trans.gif" class="BKR icon_lieferbar_gelb" border="0">';
	}
	echo '<span>' . $paimage . ' ' . $products_availability_name . '</span>';	
}


function push_get_stars_count($number){
	for($i=1;$i<6;$i++){
		echo '<img src="images/pixel_trans.gif" class="BKR ';
		if($i<=$number){
			echo "sterne_klein_1";
		}else{
			echo "sterne_klein";
		}
		echo '" title="'.$number .' von 5">';
	}

}
function get_mhd_info($product){
	$listingxx=push_db_fetch_array(push_db_query("SELECT products_mhd, expires_date FROM specials WHERE products_id='" . $product . "' AND status=1 "));
	if (isset($listingxx['products_mhd'])&&(push_not_null($listingxx['products_mhd']))){
		echo "<p class='info' style='font-size:10px; margin-top:2px; line-height: 13px;'>Mindestens haltbar bis: <b style='font-size:10px; margin-top:2px; line-height: 10px;display:block;'>" . $listingxx['products_mhd'] . "</b><span  style='font-size:10px; margin-top:2px; line-height: 13px;display:block'>Solange der Vorrat reicht.</span></p>";
	}elseif (isset($listingxx['expires_date'])&&(push_not_null($listingxx['expires_date']))){
		echo "<p class='info' style='font-size:10px;  line-height: 13px;'>Angebot gültig bis: <b style='font-size:10px; margin-top:2px; line-height: 10px;display:block;'>" . date("d.m.Y",strtotime($listingxx['expires_date']) ). "</b>
</p>";	
	}
}

/**
 * generate_url_rewritten_string
 */
function generate_url_rewritten_string($url, $ziel='pd'){
//pass through if already rewritten:
	if(strpos($url,'.html')){
		return $url;
	}
//tear url into 2 parts
	$parts=explode ('?',$url);
	$anfang=$parts[0];
	if(isset($parts[1]))
	{
		$ende=$parts[1];
	}
	else
	{
		$ende='';
	}
	$titel='';
//treat each target separate
	if($ziel=='pd'){ 
//product_info
		$anfang=str_replace('product_info.php','',$anfang);
//get products_name
		$snippets=explode('&', $parts[1]);
		foreach($snippets as $snippet){
			$teile=explode("=", $snippet);
			if($teile[0]=='products_id'){
				$product=$teile[1];
				break;
			}
		}

		$gpq="SELECT url_name FROM url_rewriting WHERE url_type='pd' AND url_type_id='" . (int)$product . "'";
		$p=push_db_fetch_array(push_db_query($gpq));
		if($p['url_name']<>'')
		{
			$titel = $p['url_name'];
		}
		else
		{
			$pq=push_db_fetch_array(push_db_query("SELECT products_name FROM products_description WHERE products_id='" .(int) $product . "' "));
			if($pq['products_name']<>''){
				$titel = make_url_string(trim(str_replace('-',' ',$pq['products_name'])));
				push_db_query("INSERT INTO url_rewriting (url_name, url_type, url_type_id) VALUES ('" . $titel . "', 'pd', '" . (int)$product . "' )");
			}
		}
		if($titel=='')
		{
			$titel = "Produkt-nicht-gefunden";
		}
	}
	elseif($ziel == 'b')
	{
		$anfang=str_replace('brands.php','',$anfang);
		$snippets=explode('&', $parts[1]);
		foreach($snippets as $snippet){
			$teile=explode("=", $snippet);
			if($teile[0]=='mid'){
				$manufacturer=$teile[1];
				break;
			}
		}
		$gpq="SELECT url_name FROM url_rewriting WHERE url_type='b' AND url_type_id='" . (int)$manufacturer . "m'";
		$p=push_db_fetch_array(push_db_query($gpq));
		if($p['url_name']<>'')
		{
			$titel = $p['url_name'];
		}
		else
		{
			$pq=push_db_fetch_array(push_db_query("SELECT manufacturers_name FROM manufacturers WHERE manufacturers_id='" .(int) $manufacturer . "m' "));
			if($pq['manufacturers_name']<>''){
				$titel = make_url_string(trim(str_replace('-',' ',$pq['manufacturers_name'])));
				push_db_query("INSERT INTO url_rewriting (url_name, url_type, url_type_id) VALUES ('" . $titel . "', 'b', '" . (int)$manufacturer . "m' )");
			}
		}
		if($titel=='')
		{
			$titel = "Marke-nicht-gefunden";
		}
		
		
	}
	if($ziel=='c')
	{	
//index.php
		$anfang=str_replace('index.php','',$anfang);
		$snippets=explode('&', $ende);
		foreach($snippets as $snippet){
			$teile=explode("=", $snippet);

			if( $teile[0]=='newproducts')
			{
				$titel="Neue-Produkte";
				break;
			}
			elseif( $teile[0]=='specials')
			{
				$titel="Angebote";
				break;
			}
			elseif( $teile[0]=='manufacturers_id')
			{
				$titel="Hesteller-nicht-gefunden";//Herstellername
				$tq = push_db_fetch_array(push_db_query("SELECT manufacturers_name FROM manufacturers WHERE manufacturers_id='" . $teile[1] . "'"));
				if($tq['manufacturers_name']){
					$titel= make_url_string($tq['manufacturers_name']);
				}
			break;
			}
			elseif( $teile[0]=='cPath')
			{
				$cats=explode('_',$teile[1]);
				foreach($cats as $id)
				{ 
					$tq = push_db_fetch_array(push_db_query("SELECT categories_name FROM categories_description WHERE categories_id='" . $id . "' and language_id='2'"));
					if($tq['categories_name']){
						$titel .=  " " .$tq['categories_name'];
					}
				}
				if($titel==""){//Fehlermeldung
					$titel="Kategorie wurde nicht gefunden";
				}
				else
				{
					$tstring= rtrim( $teile[1] , '_');
					$cq=push_db_query("SELECT url_name FROM url_rewriting WHERE url_type='c' AND url_type_id='" . $tstring . "'");
					if(!$cqr=push_db_fetch_array($cq))
					{
						$ins = make_url_string(trim($titel));
						push_db_query("INSERT INTO url_rewriting (url_name, url_type, url_type_id) VALUES ('" . $ins . "', 'c', '" .  $tstring . "' )");
					}
					
				}#
				$teile[1] =rtrim( $teile[1] , '_');
				break;
			}
			elseif( $teile[0]=='keywords')
			{
				$titel = 'Suchergebnisse ' . $teile[1];
				break;
			}			
		}
	$titel=make_url_string(trim($titel));
	}

	if( ($titel=='') && ($ziel=='c') ){
		return $anfang . $ende;
	}

	
	$revstring = $anfang . $titel .','.$ziel.','. str_replace('&redirected=true','',rtrim( $parts[1] , '_')) .'.html';
	return $revstring;
}

	function make_url_string($word)
	{ 
$look=array('€','‚','Š','Œ','Ž','‘','’','“','”','•','–','—','˜','™','š','‹','›','œ','ž','Ÿ','¡','¢','£','¤','¥','¦','§','¨','©','ª','«','¬','­','®','¯','°','±','²','³','´','µ','¶','·','¸','¹','º','¼','½','¾','¿','À','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','×','Ø','Ù','Ú','Û','Ü','Ý','Þ','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','÷','ø','ù','ú','û','ü','ý','þ','ÿ',"'",'"',' ',"\\", '/',"'",',','%','?');
$repl=array('Euro','-','S','CE','Z','-','-','-','-','-','-','-','-','TM','s','-','-','oe','z','Y','i','c','L','o','Y','I','Paragraph','-','copy','a','-','-','-','R','-','-','-','2','3','-','mikro','P','-','-','1','o','14','12','34','?','A','A','A','AE','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','OE','x','O','U','U','U','UE','Y','th','ss','a','a','a','a','ae','a','ae','c','e','e','e','e','i','i','i','i','th','n','o','o','o','o','oe','-','o','u','u','u','ue','y','dh','y','','','-','-','-','','','','');

		//$word = strtolower($word);
		$word = str_replace($look,$repl,$word);
		$look = array('------','-----','----', '---','--');
		$word = str_replace($look,'-',$word);
		return $word;
	}

function push_generate_order_id($prefix='S')
	{
		$part1 = substr(date('Y'),-1) ;
		do{
			$currenttime=time();
			if(($part1 < 1) || ($part1 > 9))
			{
				$part1 = 1;
			}
			if(rand(1,6) > 3)
			{
				$part2 = substr($currenttime,-5). rand(10 , 99);
			}
			else
			{
				$part2 = rand(10 , 99) . substr($currenttime,-5);
			}
			$backval=$part1 . $part2;
		}while($t=push_db_fetch_array(push_db_query('SELECT orders_id FROM orders WHERE orders_id="' . (int)$backval . '"')));
		
		return (int)$backval;
	}

	
	function push_get_store_address() {
		$address = push_db_fetch_array(push_db_query("SELECT configuration_value FROM configuration WHERE configuration_key = 'STORE_NAME_ADDRESS'"));
		return $address['configuration_value'];
	}

function push_is_on_homepage() {
	return strpos($_SERVER["REQUEST_URI"], str_replace('/','',DIR_WS_HTTP_CATALOG)) === false && basename($_SERVER['PHP_SELF']) == 'index.php';
}

function push_href_rel_home_link($url) {
	return push_is_on_homepage() ? str_replace('/','',DIR_WS_HTTP_CATALOG) . '/' . $url : $url;
}

  ?>
