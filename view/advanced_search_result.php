<?php
/*
  $Id: advanced_search_result.php,v 1.72 2003/06/23 06:50:11 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');
   push_redirect(push_href_link(FILENAME_ADVANCED_SEARCH, push_get_all_get_params(), 'NONSSL', true, false));
//TRY to cut this down!!!
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);

  $error = false;

  if ( (isset($_GET['keywords']) && empty($_GET['keywords'])) &&
       (isset($_GET['dfrom']) && (empty($_GET['dfrom']) || ($_GET['dfrom'] == DOB_FORMAT_STRING))) &&
       (isset($_GET['dto']) && (empty($_GET['dto']) || ($_GET['dto'] == DOB_FORMAT_STRING))) &&
       (isset($_GET['pfrom']) && !is_numeric($_GET['pfrom'])) &&
       (isset($_GET['pto']) && !is_numeric($_GET['pto'])) ) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  } else {
    $dfrom = '';
    $dto = '';
    $pfrom = '';
    $pto = '';
    $keywords = '';

    if (isset($_GET['dfrom'])) {
      $dfrom = (($_GET['dfrom'] == DOB_FORMAT_STRING) ? '' : $_GET['dfrom']);
    }

    if (isset($_GET['dto'])) {
      $dto = (($_GET['dto'] == DOB_FORMAT_STRING) ? '' : $_GET['dto']);
    }

    if (isset($_GET['pfrom'])) {
      $pfrom = $_GET['pfrom'];
    }

    if (isset($_GET['pto'])) {
      $pto = $_GET['pto'];
    }

    if (isset($_GET['keywords'])) {
      $keywords = $_GET['keywords'];
    }

    $date_check_error = false;
    if (push_not_null($dfrom)) {
      if (!push_checkdate($dfrom, DOB_FORMAT_STRING, $dfrom_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_FROM_DATE);
      }
    }

    if (push_not_null($dto)) {
      if (!push_checkdate($dto, DOB_FORMAT_STRING, $dto_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_TO_DATE);
      }
    }

    if (($date_check_error == false) && push_not_null($dfrom) && push_not_null($dto)) {
      if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
        $error = true;

        $messageStack->add_session('search', ERROR_TO_DATE_LESS_THAN_FROM_DATE);
      }
    }

    $price_check_error = false;
    if (push_not_null($pfrom)) {
      if (!settype($pfrom, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_FROM_MUST_BE_NUM);
      }
    }

    if (push_not_null($pto)) {
      if (!settype($pto, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_MUST_BE_NUM);
      }
    }

    if (($price_check_error == false) && is_float($pfrom) && is_float($pto)) {
      if ($pfrom >= $pto) {
        $error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_LESS_THAN_PRICE_FROM);
      }
    }

    if (push_not_null($keywords)) {
      if (!push_parse_search_string($keywords, $search_keywords)) {
        $error = true;

        $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
      }
    }
  }

  if (empty($dfrom) && empty($dto) && empty($pfrom) && empty($pto) && empty($keywords)) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  }

  if ($error == true) {
    push_redirect(push_href_link(FILENAME_ADVANCED_SEARCH, push_get_all_get_params(), 'NONSSL', true, false));
  }
/**
 * 
 * define $_SESSION['sortorder'] as session-var ...
 */
if (!push_session_is_registered('sortorder')) {
    push_session_register('sortorder');
    	$_SESSION['sortorder']="1d";
 }
if(isset($_POST['sort']))
$_SESSION['sortorder'] = $_POST['sort'];

  $breadcrumb->add(NAVBAR_TITLE_1, push_href_link(FILENAME_ADVANCED_SEARCH));
  $breadcrumb->add(NAVBAR_TITLE_2, push_href_link(FILENAME_ADVANCED_SEARCH_RESULT, push_get_all_get_params(), 'NONSSL', true, false));
require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'bkr_static_menu.php'); ?>
<!-- /#left-column --> 
</div>
<div id="center-column">
<!-- body_text //-->
<div class="maincontent">
 	  <h1><?php echo HEADING_TITLE_2; ?></h1>
    <div>
<?php
// create column list
  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
// Products Description Hack begins
                       'PRODUCT_LIST_DESCRIPTION' => PRODUCT_LIST_DESCRIPTION,
// Products Description Hack ends                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

  asort($define_list);

  $column_list = array();
  reset($define_list);
  while (list($key, $value) = each($define_list)) {
    if ($value > 0) $column_list[] = $key;
  }

  $select_column_list = '';

  for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
    switch ($column_list[$i]) {
      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model, ';
        break;
// Products Description Hack begins
      case 'PRODUCT_LIST_DESCRIPTION':
        $select_column_list .= 'pd.products_description, ';
		$select_column_list .= 'p.products_image, ';
        break;
// Products Description Hack ends      case 'PRODUCT_LIST_MANUFACTURER':
        $select_column_list .= 'm.manufacturers_name, ';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $select_column_list .= 'p.products_quantity, ';
        break;
      case 'PRODUCT_LIST_IMAGE':
        //$select_column_list .= 'p.products_image, ';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $select_column_list .= 'p.products_weight, ';
        break;
    }
  }

  $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_availability_id, p.products_id, pd.products_name, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (push_not_null($pfrom) || push_not_null($pto)) ) {
    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
  }

// BOF

$specials_without_duplicates = "SELECT products_id, status, customers_group_id, MIN(specials_new_products_price) AS specials_new_products_price FROM specials WHERE specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) GROUP BY products_id";

   $from_str = "from (((" . TABLE_PRODUCTS . " p ) left join " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " p2pef on p.products_id=p2pef.products_id) left join " . TABLE_MANUFACTURERS . " m on m.manufacturers_id=p.manufacturers_id ) left join (" . $specials_without_duplicates . ") s on p.products_id = s.products_id";
// EOF

if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (push_not_null($pfrom) || push_not_null($pto)) ) {
  if (!push_session_is_registered('customer_country_id')) {
    $customer_country_id = STORE_COUNTRY;
    $customer_zone_id = STORE_ZONE;
  }
  $from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "')";
}

$from_str .= ", " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";

$where_str = " where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' AND products_price > 0 AND p.products_id = pd.products_id AND pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";

  if (isset($_GET['categories_id']) && push_not_null($_GET['categories_id'])) {
    if (isset($_GET['inc_subcat']) && ($_GET['inc_subcat'] == '1')) {
      $subcategories_array = array();
      push_get_subcategories($subcategories_array, $_GET['categories_id']);

      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$_GET['categories_id'] . "'";

      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
      }

      $where_str .= ")";
    } else {
      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['categories_id'] . "'";
    }
  }

  if (isset($_GET['manufacturers_id']) && push_not_null($_GET['manufacturers_id'])) {
    $where_str .= " and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
  }

  if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {
    $where_str .= " and (";
    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
      switch ($search_keywords[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
          $where_str .= " " . $search_keywords[$i] . " ";
          break;
        default:
          $keyword = push_db_prepare_input($search_keywords[$i]);

// START: Extra Fields Contribution
// $where_str .= "(pd.products_name like '%" . push_db_input($keyword) . "%' or p.products_model like '%" . push_db_input($keyword) . "%' or m.manufacturers_name like '%" . push_db_input($keyword) . "%'";
	$where_str .= "(pd.products_name like '%" . push_db_input($keyword) . "%' or p.products_model like '%" . push_db_input($keyword) . "%' or m.manufacturers_name like '%" . push_db_input($keyword) . "%' or p2pef.products_extra_fields_value like '%" . push_db_input($keyword) . "%'";
// END: Extra Fields Contribution

          if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) $where_str .= " or pd.products_description like '%" . push_db_input($keyword) . "%'";
          $where_str .= ')';
          break;
      }
    }
	$where_str .= " )";
  }

  if (push_not_null($dfrom)) {
    $where_str .= " and p.products_date_added >= '" . push_date_raw($dfrom) . "'";
  }

  if (push_not_null($dto)) {
    $where_str .= " and p.products_date_added <= '" . push_date_raw($dto) . "'";
  }

  if (push_not_null($pfrom)) {
    if ($currencies->is_set($currency)) {
      $rate = $currencies->get_value($currency);

      $pfrom = $pfrom / $rate;
    }
  }

  if (push_not_null($pto)) {
    if (isset($rate)) {
      $pto = $pto / $rate;
    }
  }

  if (DISPLAY_PRICE_WITH_TAX == 'true') {
    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
  } else {
    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$pto . ")";
  }

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (push_not_null($pfrom) || push_not_null($pto)) ) {
    $where_str .= " group by p.products_id, tr.tax_priority";
  }

 /*   if ( (!isset($_GET['sort'])) || (!ereg('[1-8][ad]', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > sizeof($column_list)) ) {
    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
        $_GET['sort'] = $i+1 . 'a';
        $order_str = ' order by pd.products_name';
        break;
      }
    }
  } else {
   $sort_col = substr($_GET['sort'], 0 , 1);
    $sort_order = substr($_GET['sort'], 1);
    $order_str = ' order by ';
    switch ($column_list[$sort_col-1]) {
      case 'PRODUCT_LIST_MODEL':
        $order_str .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_NAME':
        $order_str .= "pd.products_name " . ($sort_order == 'd' ? "desc" : "");
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $order_str .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $order_str .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_IMAGE':
        $order_str .= "pd.products_name";
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $order_str .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_PRICE':
        $order_str .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
    }
	
  }*/
		$sort_col = substr($_SESSION['sortorder'], 0 , 1);
		$sort_order = substr($_SESSION['sortorder'], 1);
		$cuqu=' ORDER BY ';
		$listing_sql .= ' order by ';
		switch ($sort_col) {
			case '4':
			$listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
			$cuqu .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
			break;
			case '5':
			$listing_sql .= "p.products_date_added " . ($sort_order == 'd' ? 'desc' : '');
			$cuqu .= "p.products_date_added " . ($sort_order == 'd' ? 'desc' : '');
			break;
			case '3':
			$listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
			$cuqu .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
			break;
			case '2':
			$listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
			$cuqu .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
			break;
			case '1':
			$listing_sql .= "p.products_ordered " . ($sort_order == 'd' ? 'desc' : '');
			$cuqu .= "p.products_ordered " . ($sort_order == 'd' ? 'desc' : '');
			break;
		}

  $listing_sql = $select_str . $from_str . $where_str . $order_str;

// echo $listing_sql;
// require(DIR_WS_MODULES . "product_listing_new.php");
?>
</div></div>
 <!-- body_text_eof //-->
</div>
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>
