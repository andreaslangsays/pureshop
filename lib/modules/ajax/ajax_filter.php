<?php
/**
 * Filter für Produkt Listen
 */
chdir('../../../');
require('includes/ajax_top.php');
$ausgabe='';
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

$current_ids=$id_query;
	//display the Filter!!!
	//try this as ajax-request???
	//echo $filter_query;


//identify selected filters! create array for that!
//structure: $key['products_extra_fields_id'] => value['products_extra_fields_value']
$filter_query_raw = $_GET['filter'];
$selected_filters=array();

if(strlen($filter_query_raw)>0){
	$filter_parts= explode(' ',$filter_query_raw);
	foreach($filter_parts as $fp){
		$tempw=explode('=',$fp);
		if(count($tempw)>1){	
			$keys[]=$tempw[0];
			$vals[]=str_replace('_',' ', $tempw[1]);
		}else{
			$keys[]=$tempw[0];
			$vals[]="1";
		}
	}

	$selected_filters=array_combine($keys,$vals);
}



	$fq=push_db_query($filter_query);
	$anz= push_db_num_rows($fq);

		if($anz > 0){
$ausgabe .= '
		<ul id="filterbar" style="margin-top:20px; margin-bottom:20px;">
		<h3 class="selected">Ergebnis filtern</h3>';
		//here (AT FIRST!) the manufacturers filter
$ausgabe .='
		<h3 style="border:none !important;margin-top:5px;">Hersteller</h3>
			<ul id="hersteller">';
//manufacturer is selectet
			if(isset($selected_filters['manufacturer']) || isset($_GET['manufacturers_id']) ){
				$add_class='';
				if( isset($_GET['manufacturers_id']) ){
					$man_id=$_GET['manufacturers_id'];
				}else{
					$man_id=$selected_filters['manufacturer'];
				}
	
	//get all manufacturers of current category
	
				$manufacturers_query = push_db_query("SELECT m.manufacturers_name, count(p.products_id) as anzahl  FROM products p JOIN manufacturers m ON (p.manufacturers_id=m.manufacturers_id)WHERE products_id IN( " . $current_ids . " ) and p.manufacturers_id='" . mysql_escape_string($man_id) . "' GROUP BY p.manufacturers_id" );
				$manr=push_db_fetch_array($manufacturers_query);
				$ausgabe .= '<li class="choice selected"> ' . $manr['manufacturers_name'] . ' ' . ( (isset($selected_filters['manufacturer']))? '<a class="remover"  href="' . push_href_link(FILENAME_DEFAULT , push_get_all_get_params(array("filter","page")) . get_filter_q('manufacturer' ) )   . '"><img src="' . push_href_link('images/newbkr/close-x.png') . '"></a>': '') . ' </li>';
			}else{
//no manufacturer selected
				$manufacturers_query = push_db_query("SELECT p.manufacturers_id, m.manufacturers_name, count(p.products_id) as anzahl  FROM products p JOIN manufacturers m ON (p.manufacturers_id=m.manufacturers_id)WHERE p.products_id IN( " . $current_ids . " )  GROUP BY m.manufacturers_name ORDER BY m.manufacturers_name " );
				
				$i=0;
				$class="";
				while($manr=push_db_fetch_array($manufacturers_query)){
					$i++;
					if($i > 12){
						if(!isset($once)){
						$add_class	=	'class="drop"';
						$once		=	true;
						}
					}
					$ausgabe .= '<a href="' . push_href_link(FILENAME_DEFAULT , push_get_all_get_params(array("filter","page")) . 'filter=manufacturer=' . $manr['manufacturers_id']  .  $add_filter_to )   . '">' . '<li ' . $add_class . '> ' . $manr['manufacturers_name'] . " <span>(" . $manr['anzahl'] . ")</span></li></a>";
					
				}
				if(isset($once))
					{
					$ausgabe .= '<li class="link"> <span >mehr Auswahl &raquo;</span>';
					$ausgabe .= '<span style="display:none;">&laquo; weniger Auswahl</span> </li>';
					unset($once);
				}
			}
$ausgabe .= '
		</ul>';
		
		//here the filter based on products_extra_fields
				while($fu=push_db_fetch_array($fq)){
					$pexfq=push_db_query("SELECT * FROM products_extra_fields WHERE products_extra_fields_id='" . $fu['products_extra_fields_id'] . "' AND products_extra_fields_id  IN (" . $active_filters .  ")  ;");
					while($pexfr=push_db_fetch_array($pexfq)){
		

		/* 
		 *	create form elements to select  texts
		 *	and more is coming to it too
		 */
					if($pexfr['products_extra_fields_type']=='t'){
					//multiple criteria
					//use the available text to choose filter criterium
						if(isset($selected_filters[$pexfr['products_extra_fields_id'] ]) && ($sf=push_db_fetch_array(push_db_query("SELECT * FROM products_to_products_extra_fields WHERE products_extra_fields_value LIKE '" . mysql_escape_string($selected_filters[$pexfr['products_extra_fields_id'] ]) ."%'"))) ){
							$a_f  =  "<ul><h3>" . $pexfr['products_extra_fields_name']. "</h3>". "\n";
							$a_f .=	 "	<li class='selected'>" . $sf['products_extra_fields_value'] . "\n";
							$a_f .=  '		<a class="remover"  href="' . push_href_link(FILENAME_DEFAULT , push_get_all_get_params(array("filter","page")) . get_filter_q($pexfr['products_extra_fields_id'] ) )   . '"><img src="' . push_href_link('images/newbkr/close-x.png') . '"></a>  </li>'. "\n";
							$a_f .=  "</ul>". "\n";
						}else{
							//criterion not yet selected								
							$a_f  =  "<ul><h3>" . $pexfr['products_extra_fields_name']. "</h3>". "\n";
							$filter_textq=push_db_query("SELECT products_extra_fields_value, count(products_id) nfields FROM products_to_products_extra_fields WHERE products_id IN ( " . $current_ids . " ) AND products_extra_fields_id='" . $pexfr['products_extra_fields_id'] . "' GROUP BY products_extra_fields_value");
							$i=0;
							$add_class='';
							while($textl=push_db_fetch_array($filter_textq)){
								$i++;
								if($i > 12){
									if(!isset($once)){
						//				$a_f .= "\n".'<div style="display:none"><!-- START-->';
										$once=true;
										$add_class=' class="drop"';

									}
										
								}
								$option_name=str_replace(" ", "_", $textl['products_extra_fields_value']);
								$url_criteria= str_replace(array(" ","%"), array("_",""),$option_name);
									$a_f .= '<a href="' . push_href_link(FILENAME_DEFAULT , push_get_all_get_params(array("filter","page")) . 'filter='.str_replace(array(" ","%"), array("_",""),$pexfr['products_extra_fields_id']) . '=' . $url_criteria  .  $add_filter_to  )   . '">' .'<li ' . $add_class . '>' . $textl['products_extra_fields_value']  . " <span>(" . $textl['nfields'] . ')</span></li></a>';
								}
							if(isset($once)){	
							//	$a_f .= '<!-- END--></div>';
								$a_f .= '<li class="link"> <span >mehr Auswahl &raquo;</span>';
								$a_f .= '<span style="display:none;">&laquo; weniger Auswahl</span> </li>';
								unset($once);
								}
							$a_f .= "</ul>";
							}	
						}else{
						$i=0;
						$a_f = '';
						if(!isset($eigenschaftenheader)){
						$a_f= "<h3>Eigenschaften</h3>";
						$eigenschaftenheader=true;
						}
						if(isset($selected_filters[$pexfr['products_extra_fields_id'] ])){
							$a_f .= '<li class="selected ">';
							$a_f .= $pexfr['products_extra_fields_name'] .' <span>(' .$fu['anzahl'] .")</span>";
							$a_f .= '<a class="remover"  href="' . push_href_link(FILENAME_DEFAULT , push_get_all_get_params(array("filter","page")) . get_filter_q( $pexfr['products_extra_fields_id'] ) )   . '"><img src="' . push_href_link('images/newbkr/close-x.png') . '"></a>  </li>';
							
							}else{
							
						//create Link:
							$a_f .= '<a href="' . push_href_link(FILENAME_DEFAULT , push_get_all_get_params(array("filter","page")) . 'filter='.str_replace(array(" ","%"), array("_",""),$pexfr['products_extra_fields_id']). $add_filter_to  ) . '" class="filter"><li>';
							$a_f .= $pexfr['products_extra_fields_name'] .' <span>(' .$fu['anzahl'] .")</span></li></a>";
							}
						}
					$ausgabe .= $a_f;
					}
				}
		
			}
		$ausgabe .='	
		<li class="unfilter_all"><a href="'. push_href_link(FILENAME_DEFAULT , push_get_all_get_params(array("filter","page")) ). '" > ';
		$ausgabe .= '<img src="images/pixel_trans.gif" class="BKR btn_grau_filter-aufheben" >';
		$ausgabe .= '</a></li>
		</ul>
<script type="text/javascript">
$(document).ready(function(){
	$("li.link").click(function(e){
		$(this).parent().find(".drop").toggle();
		$(this).children().toggle();
	});

})
</script>';
if(substr_count($ausgabe, '<li ') > 2)
{
	echo $ausgabe;
}
include('includes/end.php');
?>