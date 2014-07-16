<?php
/**
 * outsourced search queries
 */

 	include(DIR_WS_MODULES . "advanced_search.php");
	#######################################################################################################
	$select_str = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, p.products_price, p.products_tax_class_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_TAX_RATES . " tr, " . TABLE_PRODUCTS . " p  left join (" . TABLE_MANUFACTURERS . " m)
 on (p.manufacturers_id = m.manufacturers_id) left join (specials s,  products_to_products_extra_fields p2pef) on ( s.products_id=p.products_id AND p2pef.products_id=p.products_id) , " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' AND p.products_tax_class_id = tr.tax_class_id  and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' ";
				//if category plays a role
				if (isset($_GET['categories_id']) && push_not_null($_GET['categories_id']) &&($_GET['categories_id']<>'all') ) {
					if (isset($_GET['inc_subcat']) && ($_GET['inc_subcat'] == '1')) {
						$subcategories_array = array();
						push_get_subcategories($subcategories_array, $_GET['categories_id']);
						$where_str .= " and (p2c.categories_id = '" . (int)$_GET['categories_id'] . "'";
						for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
							$where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
						}
						$where_str .= ")";
					} else {
						$where_str .= " and p2c.categories_id = '" . (int)$_GET['categories_id'] . "'";
					}
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
				//Insert advanced query
				
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
    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * ( 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * ( 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
  } else {
    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$pto . ")";
  }
  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (push_not_null($pfrom) || push_not_null($pto)) ) {
    $where_str .= " group by p.products_id, tr.tax_priority";
  }


// Begin Buchstaben verdreht
// Beispiel Serveitte wird als Serviette erkannt
$listing_sql = $select_str . $from_str . $where_str . $order_str;
$kontrolle = push_db_query($listing_sql);
$keywordstr=$_GET['keywords'];
$key_categories_id= $_GET['categories_id'];
if (push_db_num_rows($kontrolle) == 0)
{
	include( DIR_WS_MODULES . 'lvst.php');

}
?>