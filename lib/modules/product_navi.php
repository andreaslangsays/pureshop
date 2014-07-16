<?php //ÖÖ
if(!isset($_GET['t'])){

	$special_without_duplicates = "SELECT MIN(specials_new_products_price) AS specials_new_products_price FROM specials WHERE products_id = p.products_id AND specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND status = 1 GROUP BY products_id";
		$specials_without_duplicates_exists = "SELECT COUNT(*) FROM specials WHERE products_id = p.products_id AND specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND status = 1 GROUP BY products_id";
		
		if (isset($_GET['manufacturers_id']) || isset($_GET['cPath']) ||  isset($_GET['specials'])|| isset($_GET['categories_id']) || isset($_GET['newproducts'])|| isset($_GET['keywords']) || isset($cPath)) {
	
	// create column list
/*
	// BOF Separate Pricing Per Customer
	   if(!push_session_is_registered('sppc_customer_group_id')) {
		 $customer_group_id = '0';
		 } else {
		  $customer_group_id = $sppc_customer_group_id;
	   }
	*/
		$select_column_list = '';
		$select_column_list .= 'p.products_model, ';
		$select_column_list .= 'pd.products_name, ';
		$select_column_list .= 'pd.products_description, ';
		$select_column_list .= 'p.products_image, ';
		$select_column_list .= 'm.manufacturers_name, ';
		$select_column_list .= 'p.products_quantity, ';
	
	//perform a simple search
		if(isset($_GET['keywords'])){
		include(DIR_WS_MODULES."advanced_search.php");
		if(isset($_GET['categories_id'])){
			$breadcrumb->reset();
			$breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
			$breadcrumb->add(HEADER_TITLE_CATALOG, push_href_link(FILENAME_DEFAULT));
			$breadcrumb->add('Suche',push_href_link(FILENAME_DEFAULT, 'categories_id='.$_GET['categories_id']."&keywords=".$_GET['keywords'] ));//, push_href_link(FILENAME_ADVANCED_SEARCH, push_get_all_get_params() ));
			$breadcrumb->add( $_GET['keywords'] ,push_href_link(FILENAME_DEFAULT, 'categories_id='.$_GET['categories_id']."&keywords=".$_GET['keywords'] ));
	}
	
/*		#######################################################################################################
	$select_str = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, p.products_price, p.products_tax_class_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_TAX_RATES . " tr, " . TABLE_PRODUCTS . " p  left join (" . TABLE_MANUFACTURERS . " m, specials s,  products_to_products_extra_fields p2pef) on (p.manufacturers_id = m.manufacturers_id AND s.products_id=p.products_id AND p2pef.products_id=p.products_id) , " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' AND p.products_tax_class_id = tr.tax_class_id  and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' ";
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
									// ##Begin fehlertolerante Suche mit Search enhancement mod
									// das + wird benoetigt um die suchwoerter nachher wieder zu trennen
									$keywordstring.=$keyword.'+';
	
									//$Anzahlsuch = Anzahl der Suchwoerter
									$Anzahlsuch=$Anzahlsuch+1;
									// ##Ende fehlertolerante Suche mit Search enhancement mod
	
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
	
	$listing_sql = $select_str . $from_str . $where_str . $order_str;
	$kontrolle = push_db_query($listing_sql);
	if (push_db_num_rows($kontrolle) == 0)
	{
		include('lvst.php');
	}*/
	
	require(DIR_WS_CLASSES . "search.php");
	$search = new oscsearch();
	$search->set_keyword($_GET['keywords']);
	$listing_sql = $search->search();
	$search->register_user_keywords();
	$keywordstr = $search->original;
	$key_categories_id = $search->category;
	######################################################################################################
	// show the products of a specified manufacturer
		}elseif (isset($_GET['manufacturers_id'])) {
		  if (isset($_GET['filter_id']) && push_not_null($_GET['filter_id'])) {
	// We are asked to show only a specific category
	// BOF Separate Pricing Per Customer
		if ($status_product_prices_table == true) {
		$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id,  p.products_availability_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd ) left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'";
	
		} else { // either retail or no need to get correct special prices
		$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, p.products_price, p.products_tax_class_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from (" . TABLE_PRODUCTS . " p ), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'";
		} // end else { // either retail...
	// EOF Separate Pricing Per Customer
	
		  } else {
	// We show them all
	// BOF Separate Pricing Per Customer
			if ($status_product_prices_table == true) {
			$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from (" . TABLE_PRODUCTS . " p ) left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
		} else { // either retail or no need to get correct special prices
			$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, p.products_price, p.products_tax_class_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from (" . TABLE_PRODUCTS . " p ), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
		} // end else { // either retail...
	// EOF Separate Pricing Per Customer
	
		  }
		} else {
	// show the products in a given categorie
		  if (isset($_GET['filter_id']) && push_not_null($_GET['filter_id'])) {
	// We are asked to show only specific catgeory
	
	// BOF Separate Pricing Per Customer
			if ($status_product_prices_table == true) {
			$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from (" . TABLE_PRODUCTS . " p ) left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
			} else { // either retail or no need to get correct special prices
			$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, p.products_price, p.products_tax_class_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from ( " . TABLE_PRODUCTS . " p ), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
			} // end else { // either retail...
	// EOF Separate Pricing Per Customer
	
		  } else {
	// We show them all
	$status_product_prices_table =false;
	// BOF Separate Pricing Per Customer
			if ($status_product_prices_table == true) {
			$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from ((" . TABLE_PRODUCTS_DESCRIPTION . " pd ) left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS . " p ) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
			}elseif(isset($_GET['specials'])){
		
			if($_GET['specials']=='mhd'){
				$special_where=" AND products_mhd NOT LIKE '' ";
			}else{
				$special_where=" AND products_mhd = '' ";
			}
			
			$specials_without_duplicates = "SELECT products_id, status, customers_id, specials_date_added, MIN(specials_new_products_price) AS specials_new_products_price, product_of_the_day FROM specials WHERE specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND product_of_the_day=0 " . $special_where . " GROUP BY products_id";
			
			$listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, pd.products_name, pd.products_description, p.products_price, p.products_tax_class_id, p.products_availability_id, p.products_image, s.specials_new_products_price as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, (" . $specials_without_duplicates . ") s , " . TABLE_PRODUCTS . " p  left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id  where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' and s.customers_id = '0'";
			}elseif(isset($_GET['newproducts'])){
			$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, p.products_price, p.products_tax_class_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from ((" . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p ) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id ), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.products_id = p2c.products_id and p.products_price> 0 and pd.products_id = p2c.products_id and DATE_SUB(CURDATE(),INTERVAL " . INTERVAL_NEW_PRODUCTS . " DAY) <= p.products_date_added  and pd.language_id = '" . (int)$languages_id . "'";
			} else { // either retail or no need to get correct special prices
					//DEFAULT QUERY HERE!
	   $cPathA = explode("_", $cPath);
	   $size = sizeof($cPathA)-1;
	   $subcategories_array = array();
	   push_get_subcategories($subcategories_array, $cPathA[$size]);
	   $size_sc = sizeof($subcategories_array); //Subcat count
	   $cat_Search = "(";
	   for($i = 0; $i < $size_sc; $i++){
		  $cat_Search .= "p2c.categories_id = '" . $subcategories_array[$i] . "' or ";
	   }
	   $cat_Search .= "p2c.categories_id = '" . $cPathA[$size] . "'" . ")";
			$listing_sql = "select distinct " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_availability_id, p.products_price, p.products_tax_class_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from ((" . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p ) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id ), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and " . $cat_Search . "";// "' and 'p2c.categories_id = '" . (int)$current_category_id . "'";
			} // end else { // either retail...
	// EOF Separate Pricing per Customer
	
		  }
		}
	if(isset($_GET['filter'])){
	/* 
	 * eigentlicher Filter:
	 */
//1. Query dekodieren
	$manufacturers_sub_query='';
	$filter_query_raw = $_GET['filter'];
	if(strlen($filter_query_raw)>0){
	$filter_parts= explode(' ',$filter_query_raw);
//2.jeden Teil in query-Teil verarbeiten (Where Part)
		foreach($filter_parts as $fp){
//2.1. jeder Teil der Query wird zu eigenem subquery!
			$wp=explode('=', $fp);
//include manufacturers filter!!
			if($wp[0] == 'manufacturer'){
				$manufacturers_sub_query = " p.products_id in (select products_id from products where manufacturers_id = '" . mysql_escape_string($wp[1]) . "' ) ";
			}else{
				$filter_where = " products_extra_fields_id='" . (int)$wp[0] . "' and ";
				if(count($wp) > 1){
				$wparam= str_replace('_',' ', $wp[1]);
					$filter_where .= "products_extra_fields_value like '" . mysql_escape_string($wparam) . "%' ";
				}else{
					$filter_where .= "products_extra_fields_value='1' ";
				}
			$filter_array[] = $filter_where;
			
			}
		}

		$filter_sub_query='';
		if(isset($filter_array)){
		if(strlen($manufacturers_sub_query)>0){
			$manufacturers_sub_query = ' and ' .$manufacturers_sub_query;
		}
			foreach($filter_array as $filter_a){
//3. Subquery
				$filter_sub_query .= " p.products_id IN (select products_id from products_to_products_extra_fields where " . $filter_a . " ) AND ";
			}
			$filter_sub_query = substr($filter_sub_query, 0, -4);
		}
		$filter_sub_query .= $manufacturers_sub_query;
		}

	/*
	 * um den Teil zu addieren:
	 */
	// $add_filter_to= ((strlen($_GET['filter']) > 0)? '+'.$_GET['filter'] : '');
	/*
	 * Filter - Ende
	 */


}

	if(isset($_GET['filter']) && isset($filter_sub_query) ){
	$listing_sql .= " and " . $filter_sub_query; 
	//echo $filter_sub_query;
	}
			$sort_col = substr($sortorder, 0 , 1);
			$sort_order = substr($sortorder, 1);
			$listing_sql .= ' order by ';
			switch ($sort_col) {
				case '4':
				$listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
				break;
				case '5':
				$listing_sql .= "p.products_date_added " . ($sort_order == 'd' ? 'desc' : '');
				break;
				case '3':
				$listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
				break;
				case '2':
				$listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
				break;
				case '1':
				$listing_sql .= "p.products_ordered " . ($sort_order == 'd' ? 'desc' : '');
				break;
				default:
				$listing_sql .= "p.products_ordered " . ($sort_order == 'd' ? 'desc' : '');
				break;

			}
	
		//echo $listing_sql;
		//Suchergebnis in Array -> Array in DB mit Schlüssel
		//
		$rv=md5($listing_sql);
		//echo $rv;
		$pnq=push_db_query($listing_sql);
		while($pnr=push_db_fetch_array($pnq)){
					$pn_i[]=$pnr['products_id'];
					$pn_n[]=$pnr['products_name'];
		}
		if(count($pn_i) > 0){
			$all_pn_i= mysql_escape_string(implode('|', $pn_i));
			$all_pn_n= mysql_escape_string(implode('|', $pn_n));
			
			$apnq=push_db_query("SELECT pnkey FROM productnavi WHERE pnkey='" . $rv . "'");
			if($qres=push_db_fetch_array($apnq)){
				push_db_query("UPDATE productnavi SET pn_values='" . $all_pn_i . "', pn_names='" . $all_pn_n . "' WHERE pnkey='" . $rv . "'");
			}else{
				push_db_query("INSERT INTO productnavi (pnkey, pn_values, pn_names ) VALUES ('" . $rv . "', '" . $all_pn_i . "', '" . $all_pn_n . "')");
			}
		}/**/
	}
}else{
//we have a $_GET['t']
	$pn_q=push_db_query("SELECT pn_values, pn_names FROM productnavi WHERE pnkey='" . mysql_escape_string($_GET['t']) . "'");
		if($pn_r=push_db_fetch_array($pn_q)){
			$pn_i= explode('|', stripslashes($pn_r['pn_values']) );
			$pn_n= explode('|', stripslashes($pn_r['pn_names']) );
		}
	$rv= mysql_escape_string($_GET['t']);
}


$k=array_search($_GET['products_id'],$pn_i);
if($k>0){
	$before =$pn_i[$k-1];
	$before_name=$pn_na[$k-1];
}
if(($k+1) <= count($pn_i)){
	$after = $pn_i[$k+1];
	$after_name = $pn_n[$k+1];
}
$x=$k+1;
$a=count($pn_i);


//insert page number

//current entries per page default 20
if($epp>0){
	if(ceil($x/$epp) > 1 ){
		$pagestring = "&page=" . ceil($x/$epp);
	}else{
		$pagestring='';
	}
}else{
	$pagestring='';
}

	
	if( isset( $titleadd)&&( $titleadd<>"")){
		if(isset($_GET['specials']))
			$categories_name= 'specials';
		elseif(isset($_GET['newproducts']))
			$categories_name= 'newproducts';
		elseif(isset($_GET['manufacturers_id']))
			$categories_name= $manufacturers['manufacturers_name'];
		else 
			$categories_name= $categories['categories_name'];
		}
		else{
			$categories_name= "ohne Kategorie";
		}

		if(isset($_GET['categories_id'])){
			$breadcrumb->reset();
	//		$breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
			$breadcrumb->add(HEADER_TITLE_CATALOG, push_href_link(FILENAME_DEFAULT));
			$breadcrumb->add('Suche',push_href_link(FILENAME_DEFAULT, $current_category_query ));//, push_href_link(FILENAME_ADVANCED_SEARCH, push_get_all_get_params() ));
			$breadcrumb->add( $_GET['keywords'] ,push_href_link(FILENAME_DEFAULT, $current_category_query ));
			$categories_name= "Suchergebnis: " . $_GET['keywords'];
	}else{
			$breadcrumb->reset();
	//		$breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
			$breadcrumb->add(HEADER_TITLE_CATALOG, push_href_link(FILENAME_DEFAULT));
			if(isset($_GET['specials'])){
					if($_GET['specials']=='mhd'){
 	   					$breadcrumb->add('Angebote mit kurzem MHD', push_href_link(FILENAME_DEFAULT, 'specials=mhd' . ($_GET['filter']? '&filter=' . $_GET['filter']  : '') . $pagestring));
					}else{
						$breadcrumb->add('Angebote der Woche', push_href_link(FILENAME_DEFAULT, 'specials=week' . ($_GET['filter']? '&filter=' . $_GET['filter']  : '') . $pagestring));
					}
 			}elseif(isset($_GET['newproducts'])){
 			   $breadcrumb->add('Neue Produkte', push_href_link(FILENAME_DEFAULT, 'newproducts=all'. ($_GET['filter']? '&filter=' . $_GET['filter']  : '') .$pagestring));
 			}elseif (isset($cPath_array)) {
				for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
				  $categories_query = push_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
				  if (push_db_num_rows($categories_query) > 0) {
					$categories = push_db_fetch_array($categories_query);
					if($i==($n-1) )
						$addQ=$pagestring;
					else
						$addQ='';
					$breadcrumb->add($categories['categories_name'], push_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1))) . $addQ ));
				  } else {
					break;
				  }
				}
			} elseif (isset($_GET['manufacturers_id'])) {
				$manufacturers_query = push_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
				if (push_db_num_rows($manufacturers_query)) {
					$manufacturers = push_db_fetch_array($manufacturers_query);
					$breadcrumb->add($manufacturers['manufacturers_name'], push_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id'] . ($_GET['filter']? '&filter=' . $_GET['filter']  : '') . $pagestring));
					if($titleadd > "")
						$titleadd .= " - ";
					$titleadd .= $manufacturers['manufacturers_name'];
					$actualtitle = $manufacturers['manufacturers_name'];
				}
			}
		
			  
	}
	if(defined("FILTERADD"))
	{
		$breadcrumb->add(FILTERADD,push_href_link(FILENAME_DEFAULT, $current_category_query));
	}
?>
