<?php
/*
	pureshop 2014
*/
/*	var_dump($_POST);
	var_dump($_GET);
	die("[Iam to young to DIE!!!]");
	*/
require('lib/app.php');
	
  $category_depth = 'top';
  if (isset($cPath) && push_not_null($cPath)) {
	if($current_category_id < 1000)
	{
		$category_depth = 'nested'; // navigate through the categories
	}
	else
	{
		$categories_products_query = push_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
		$cateqories_products = push_db_fetch_array($categories_products_query);
		if ($cateqories_products['total'] > 0) {
		  $category_depth = 'products'; // display products
		} else {
		  $category_parent_query = push_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
		  $category_parent = push_db_fetch_array($category_parent_query);
		  if ($category_parent['total'] > 0) {
				$category_depth = 'products'; // navigate through the categories
		  } else {
			$category_depth = 'products'; // category has no products, but display the 'no products' message
		  }
		}
	}
  }
 //complete cPath querylink 
//
$cPathq=$current_category_id;
$cq=push_db_query("SELECT parent_id FROM " . TABLE_CATEGORIES . " WHERE categories_id=" . (int)$current_category_id . ";");

while($f=push_db_fetch_array($cq)){
  if( (int)$f['parent_id'] > 0 ){
  //there is a parent-category
  	$cq=push_db_query("SELECT parent_id FROM " . TABLE_CATEGORIES . " WHERE categories_id=" . (int)$f['parent_id'] . ";");
	$cPathq= $f['parent_id'] . "_" . $cPathq;
  }
}
if(isset($_GET['cPath']))
{	$_GET['cPath']=$cPathq;
	$cPath=$cPathq;
  $cPath_array = push_parse_category_path($cPath);
}


if(isset($_GET['keywords']))
{
	require(DIR_WS_CLASSES . "search.php");
	$search = new oscsearch();
	$search->set_keyword($_GET['keywords']);
	$search->set_subcategories(1);
	$listing_sql = $search->search();

	$search->register_user_keywords();
	$keywordstr = $search->get_url_string($search->original);
	$breadcrumbkey = $search->original;
	$key_categories_id = $search->category;
}
if(isset($_GET['categories_id'])){
	   $breadcrumb->add('Suche',push_href_link(FILENAME_DEFAULT, 'categories_id='.$_GET['categories_id']."&keywords=".$_GET['keywords'] ));
	   $breadcrumb->add( $_GET['keywords'] ,push_href_link(FILENAME_DEFAULT, 'categories_id='.$_GET['categories_id']."&keywords=".$_GET['keywords'] ));
}

	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
	require(DIR_WS_BOXES . 'html_header.php');

	$special_without_duplicates = "SELECT MIN(specials_new_products_price) AS specials_new_products_price FROM specials WHERE products_id = p.products_id AND specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND status = 1 AND customers_id='" . $_SESSION['customer_id'] . "'GROUP BY products_id";
	$specials_without_duplicates_exists = "SELECT COUNT(status) FROM specials WHERE products_id = p.products_id AND specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND status = 1 GROUP BY products_id";

if ($category_depth == 'products' || isset($_GET['manufacturers_id']) || isset($_GET['specials'])|| isset($_GET['categories_id']) || isset($_GET['newproducts'])) {
// create column list

   /* this will build the table with specials prices for the retail group or update it if needed
    this function should have been added to includes/functions/database.php
  if ($customer_group_id == '0') {
  push_db_check_age_specials_retail_table();
  }*/
   $status_product_prices_table = false;
   $status_need_to_get_prices = false;
	$select_column_list = '';
//	$select_column_list .= 'p.products_model, ';
/*	$select_column_list .= 'pd.products_name, ';
	$select_column_list .= 'pd.products_description, ';
	$select_column_list .= 'p.products_image, ';
	$select_column_list .= 'm.manufacturers_name, ';
	$select_column_list .= 'p.manufacturers_id, ';
	$select_column_list .= 'p.products_availability_id, ';
//	$select_column_list .= 'p.products_tax_class_id, ';
	
//	$select_column_list .= 'p.products_quantity, ';
*/

//perform a simple search
    if(isset($_GET['keywords'])){
	if(strtolower(trim($search->original)) <> strtolower(trim($search->keyphrase))){
		//echo "<!-- |" . $search->original . "| <> |" . $search->keyphrase . "| -->";
		echo "<span class=\"info-warning prefix_4 grid_12\">Ihre Suche nach &ldquo;" . $search->original . "&rdquo; ergab leider keine Produkttreffer. </span>\n<br>";
		if ($search->shortest == 0) {
			//	echo "<span class=\"info-statement\"> Meinten Sie: " . $search->keyphrase . "\n</span>";
		} else {
			//echo "<span class=\"info-statement\">Meinten Sie: " . $search->keyphrase . "?\n </span>";
		}
	}
	echo $search->javascript;
// show the products of a specified manufacturer
	}elseif (isset($_GET['manufacturers_id'])) {
######################################################
	//Manufacturers Query
      if (isset($_GET['filter_id']) && push_not_null($_GET['filter_id'])) {
// We are asked to show only a specific category
	$listing_sql = "select distinct " . $select_column_list . " p.products_id,  (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from (" . TABLE_PRODUCTS . " p ), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "' ";
      } else {
// We show them all
		$listing_sql = "select distinct " . $select_column_list . " p.products_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from (" . TABLE_PRODUCTS . " p ), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
      }
    } else {
// show the products in a given categorie
      if (isset($_GET['filter_id']) && push_not_null($_GET['filter_id'])) {
// We are asked to show only specific catgeory
// BOF Separate Pricing Per Customer
		$listing_sql = "select distinct " . $select_column_list . " p.products_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from ( " . TABLE_PRODUCTS . " p ), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
// EOF Separate Pricing Per Customer
      } else {
// We show them all
	if(isset($_GET['specials'])){
	//specials
		if($_GET['specials']=='mhd'){
			$special_where=" AND products_mhd NOT LIKE '' ";
		}else{
			$special_where=" AND products_mhd = '' ";
		}
		$specials_without_duplicates = "SELECT products_id, status, customers_id, specials_date_added, MIN(specials_new_products_price) AS specials_new_products_price,  product_of_the_day FROM specials WHERE specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND product_of_the_day=0 and (customers_id=" . (int)$_SESSION['customer_id'] . " or customers_id=0)" . $special_where . " GROUP BY products_id";
		$listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, pd.products_name, pd.products_description, p.products_price, p.products_tax_class_id, p.products_availability_id, p.products_image, s.specials_new_products_price as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, (" . $specials_without_duplicates . ") s , " . TABLE_PRODUCTS . " p  left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id  where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' and (s.customers_id = '". (int)$_SESSION['customer_id']."' or s.customers_id = 0) ";
        }elseif(isset($_GET['newproducts'])){
		//newproducts
		$listing_sql = "select distinct " . $select_column_list . " p.products_id,  (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from ((" . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p ) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id ), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.products_id = p2c.products_id and p.products_price> 0 and pd.products_id = p2c.products_id and DATE_SUB(CURDATE(),INTERVAL " . INTERVAL_NEW_PRODUCTS . " DAY) <= p.products_date_added  and pd.language_id = '" . (int)$languages_id . "'";
        } else { // either retail or no need to get correct special prices
####################################	
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
		$listing_sql = "select distinct " . $select_column_list . " p.products_id, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (" . $specials_without_duplicates_exists . ") > 0 THEN (" . $special_without_duplicates . ") ELSE p.products_price END) as final_price from ((" . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p ) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id ), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and " . $cat_Search . "";// "' and 'p2c.categories_id = '" . (int)$current_category_id . "'";
        }
      }
    }
	
	if(isset($_GET['filter']) && isset($filter_sub_query) ){
	$listing_sql .= " and " . $filter_sub_query; 
	
	//echo $filter_sub_query;
	}

		$sort_col = substr($_SESSION['sortorder'], 0 , 1);
		$sort_order = substr($_SESSION['sortorder'], 1);
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
			default:
			$listing_sql .= "p.products_ordered " . ($sort_order == 'd' ? 'desc' : '');
			break;
		}
// optional Product List Filter
$put_off = "Troublesome trucks";
    if (PRODUCT_LIST_FILTER == $put_off) { //ausgeschaltet!!! == $put_off durch > 0 ersetzen um den Filter zu reaktivieren
      if (isset($_GET['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' order by cd.categories_name";
      } else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      }
      $filterlist_query = push_db_query($filterlist_sql);
      if (push_db_num_rows($filterlist_query) > 1) {
        echo '          ' . push_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';
        if (isset($_GET['manufacturers_id'])) {
          echo push_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
          echo push_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }
        echo push_draw_hidden_field('sort', $_POST['sort']);
        while ($filterlist = push_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo push_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo '</form>' . "\n";
		}
 	}

include(DIR_WS_BOXES . 'static_menu.php'); ?>

<!-- body_text //-->
<div class="grid_12">
<?php
		if(!(isset($_GET['page'])) ){
			//include (DIR_WS_BOXES . "cathead.php");
		}
	
	if (isset($pw_mispell)){ ##Begin fehlertolerante Suche mit Search enhancement mod
		$pw_string=str_replace('keywords','?keywords=',$pw_string);
		$pw_string=str_replace('search_in_description','+&search_in_description=1',$pw_string);
		 $pw_string; 
	} // ##End fehlertolerante Suche mit Search enhancement mod

		if(isset($_GET['AB_TEST_GROUP'])){
			echo $ab_test_group;
			echo $client_ip;
			}
//######################################
	/*		echo "---";
			var_dump( $cPath_array );
			echo "---";
/**/#######################################

	include(DIR_WS_MODULES . "pagenavi.php");
	if(isset($_SESSION['customer_id']) && ($_SESSION['customer_id'] <>0) && $view=='list'){
	
		if($_SESSION['customer_id']== 36767)
		{
		include(DIR_WS_MODULES . "product_listing.php");//FILENAME_PRODUCT_LISTING);		
		}
		else
		{
		include(DIR_WS_MODULES . "product_listing.php");//FILENAME_PRODUCT_LISTING);
		}
	}else{
		include(DIR_WS_MODULES . "product_gallery.php");//FILENAME_PRODUCT_LISTING);
	} 
	include(DIR_WS_MODULES . "bottomnavi.php");
	?>
	</div>
<?php
  	}elseif(isset($_GET['cookie'])&&($_GET['cookie']=='force_test_ambient_variables_from_server_and_user')) { 
	var_dump($_COOKIE);

	echo "<br>#############################<br><br>";
	echo push_count_products_in_category(1);
	echo "---";
	var_dump( $cPath_array );
	
	echo "---";
	var_dump($_POST);
	echo "---";
	var_dump($_GET);
	echo "---";
	
	/*<br>DISCOUNT CLASS OUTPUT:<br>";
	//	echo $discount->apply_discount() . " .. <br>";
		echo    $discount->get_next_discount_step() . " <br>
		[[---";
		echo 	$discount->get_next_discount_string() .  "---]] ";
		echo ' :: ---> ' . $discount->free_shipping_amount;/**/
	echo "<br>#############################<br>";	var_dump($_SESSION);
	echo "<br>#############################<br>+++++++++++++++++++++++++++<br>";
	var_dump($cart);
	echo "<br>#############################<br>";
	var_dump( $discount->get_shipping_discount()) ;
	echo "<br>#############################<br>";
	var_dump($_SESSION); 
	}
elseif($category_depth == 'nested')
{
	
	include(DIR_WS_BOXES . 'static_menu.php'); ?>

	<!-- body_text //-->
	<div class="alpha grid_12 omega" <?=($epp > 100)?' id="listing" ':''?> >
	<?php
		include(DIR_WS_BOXES . "category_landingpage.php");
	?>
	</div>
	<?php
}
else
{// default page
	
?>
<!-- body_text //-->
<?php //include(DIR_WS_BOXES . "banner_maple.php");

include(DIR_WS_BOXES . "banner_david_rio.php");
?>
<?php
include(DIR_WS_BOXES . "banner_dynamix.php");
?>
<div></div>
<?php if(false)
{
?>
<div class="grid_4 bbox" style="position: relative; height:460px; background: url('./images/push/start/ad_to_mojito-mint.jpg') no-repeat">
	<a href="http://www.if-bi.com/shop/Mojito-Mint-750-ml,pd,cPath=103_10302&products_id=11629.html" style="padding-top: 0; padding-bottom: 0; bottom: 19px; right: 20px" class="bannerdario-link gradientblack button w170 tx_12_15 tx_white">Torani Mojito Mint <img src="images/push/icons/ico_arrow-fw_S_white.png" /></a>
</div>

<?php
// include(DIR_WS_BOXES . "banner_green_dream.php");	// wird zurzeit nicht benutzt
if(true){
	include(DIR_WS_BOXES . "banner_barflavors.php");
}
else
{ 	//To be reactivated in fall
	include(DIR_WS_BOXES . "banner_schluerf.php");
}
?>

<div class="grid_8 bbox" style="height:458px;margin-top:20px;">
	
	<?php
	
	if(isset($_SESSION['bannerchange']) && $_SESSION['bannerchange']==true)
	{
		include(DIR_WS_BOXES . "banner_produktschulung.php");
		$_SESSION['bannerchange'] = false;
	}
	else
	{
		include(DIR_WS_BOXES . "banner_3_gewinnt.php");
		$_SESSION['bannerchange'] = true;		
	}
	 ?>
	<?php //include(DIR_WS_BOXES . "right_advertising_box.php");?>
</div>
<?php  } 

?>
<?php include(DIR_WS_BOXES . "banner_topseller.php") ?>
<?php include(DIR_WS_BOXES . "banner_topmarken.php") ?>

		<?php //include(DIR_WS_MODULES . FILENAME_FEATURED); /* DIR_WS_MODULES . FILENAME_NEW_PRODUCTS // changed due to Featured Products */ ?>
		<?php //include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS); 

		?>
<?php
  }
  
  	/*	$cv=  get_class_vars(get_class($p));
		var_dump($cv);*/
?>
<!-- body_text_eof //-->
</div>



<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>
