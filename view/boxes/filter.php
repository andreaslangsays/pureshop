<?php
function get_filter_q($tempval){
	global $selected_filters;
	$qstr='';
	foreach($selected_filters as $ky => $vl){
	if(!($ky == $tempval)){
		if(strlen($qstr)>0)
			$qstr .="+".$ky .( ($vl <> '1')?'='. str_replace(array(" ","%"), array("_",""),$vl) :'');
		else
			$qstr .=$ky .( ($vl <> '1')?'='. str_replace(array(" ","%"), array("_",""),$vl) :'');
		} 
	}
	if(strlen($qstr) > 0)
		$qstr="filter=" . str_replace(array(" ","%"), array("_",""),$qstr) ; 
	else
		$qstr="";//remove for productive system
	return $qstr;
}

?>
<!-- filter bof -->
<?php

//Look if filter is to be displayed
if((basename( $_SERVER['PHP_SELF'] )== FILENAME_DEFAULT)&&( isset($_GET['cPath'])|| isset($_GET['manufacturers_id'])|| isset($_GET['newproducts'])|| isset($_GET['specials']) ) ){

 
/*
 * Get List of valid Filters in category...
 * use products_extra_fields_id for selecting filters
 */
if($current_category_id > 0){
	$t=push_db_fetch_array(push_db_query("SELECT categories_filter FROM categories Where categories_id='" . $current_category_id . "';"));
	$active_filters=$t['categories_filter'];
}else{
	//load a default set of filters
	$active_filters="3, 300, 303, 307"; 
}

if(isset($_GET['filter'])){
	/* 
	 * eigentlicher Filter: (USERINPUT)
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
	 $add_filter_to= ((strlen($_GET['filter']) > 0)? "+". str_replace(' ', '+',$_GET['filter']) : '');
	/*
	 * Filter - Ende
	 */

}else{
	$add_filter_to='';
}


if(isset($_GET['cPath'])){
$filter_products_query="SELECT p.products_id
				FROM  products p
				JOIN (products_to_categories  p2c) ON ( p2c.products_id = p.products_id)
				WHERE
				p.products_model NOT LIKE  'ccb_%'
				AND p.products_status =  '1'
				AND p2c.categories_id =  '" . $current_category_id . "' ";

/*$filter_query="SELECT distinct `products_extra_fields_id` , count(`products_id`) AS anzahl FROM `products_to_products_extra_fields` WHERE 
				" 
				. ( (strlen($add_filter_to)>0)? str_replace( 'p.products_id', 'products_id', $filter_sub_query) . " AND ":'' ) 
				.  "
				products_id IN ( " . $filter_products_query . " )  GROUP BY `products_extra_fields_id`";
*/	//			echo $filter_query;
}elseif(isset($_GET['specials'])){
$filter_products_query ="	SELECT DISTINCT p.products_id
				FROM specials s, products p, ". TABLE_PRODUCTS_DESCRIPTION . " pd
				WHERE
				p.products_id=pd.products_id
				AND p.products_model NOT LIKE  'ccb_%'
				AND specials_date_added <= NOW( ) 
				AND (
				expires_date > NOW( ) 
				OR (
				expires_date =  '0000-00-00 00:00:00'
				OR expires_date <=> NULL
				)
				)
				AND p.products_status =  '1'
				AND s.products_id = p.products_id
				AND s.status =  '1'
				AND s.customers_group_id ='$customer_group_id'";
}elseif(isset($_GET['newproducts'])){
$filter_products_query="	SELECT DISTINCT p.products_id
				FROM products p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
				WHERE
				p.products_id=pd.products_id
				AND p.products_model NOT LIKE  'ccb_%'
				AND p.products_status =  '1'
				AND p.products_price >0
				AND DATE_SUB( CURDATE( ) , INTERVAL " . INTERVAL_NEW_PRODUCTS  . " 
				DAY ) <= p.products_date_added";
}elseif(isset($_GET['manufacturers_id'])){
$filter_products_query="SELECT p.products_id
				FROM products p,  manufacturers m, ". TABLE_PRODUCTS_DESCRIPTION . " pd
				WHERE
				p.products_id=pd.products_id
				AND p.products_model NOT LIKE  'ccb_%'
				AND p.products_status =  '1'
				AND p.manufacturers_id = m.manufacturers_id
				AND m.manufacturers_id = '" .$_GET['manufacturers_id'] . "'";

}

$filter_query="SELECT distinct p2pef.`products_extra_fields_id` , count(p2pef.`products_id`) AS anzahl FROM `products_to_products_extra_fields` p2pef JOIN products_extra_fields pef ON ( p2pef.`products_extra_fields_id`= pef.`products_extra_fields_id` ) WHERE 
				" 
				. ( (strlen($add_filter_to)>0)? str_replace( 'p.products_id', 'p2pef.products_id', $filter_sub_query) . " AND ":'' ) 
				.  "
				p2pef.products_id IN ( " . $filter_products_query . " )   GROUP BY `products_extra_fields_id`   ORDER BY  pef.products_extra_fields_type DESC, pef.products_extra_fields_name ASC";
//echo $filter_query;
$id_query = "SELECT p2pef.products_id FROM `products_to_products_extra_fields` p2pef JOIN products p ON (p2pef.products_id = p.products_id) WHERE 
				" 
				. ( (strlen($add_filter_to)>0)? str_replace( 'p.products_id', 'p2pef.products_id', $filter_sub_query) . " AND ":'' ) 
				.  "
				p2pef.products_id IN ( " . $filter_products_query . " ) ";
		}
?>
<?php //} 
?>
<!-- filters eof -->