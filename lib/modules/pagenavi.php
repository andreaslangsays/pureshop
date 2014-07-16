<?php
//set the pärameters as GET-Variables 
$parameters='';
$sess_name= push_session_name();
if(isset($_GET[$sess_name])){
	$parameters .= $sess_name . '=' . $_GET[$sess_name] . '&' ;
} 

if(isset($_GET['cPath'])){
	$parameters .='cPath=' . $_GET['cPath'];
}elseif(isset($_GET['manufacturers_id'])){
	$parameters .='manufacturers_id=' . $_GET['manufacturers_id'];
}elseif(isset($_GET['newproducts'])){
	$parameters .='newproducts=all';
}elseif(isset($_GET['specials'])){
	$parameters .='specials=all';
}elseif(isset($_GET['categories_id'])){
	if(isset($_GET['keywords']))
	$parameters .='keywords='.$_GET['keywords'].'&';
	$parameters .='categories_id='.$_GET['categories_id'];
	if(isset($_GET['search_in_description']))
		$parameters .= '&search_in_description=1'; 
} else if (isset($_GET['action']) && $_GET['action'] == 'viewthread') {
	$parameters .= 'action=viewthread&id=' . $_GET['id'];
}
if(isset($_GET['page'])){
	$parameters .= '&page=' . (int)$_GET['page'] ;
}
if(isset($_GET['filter'])){
	$parameters .= '&filter=' . $_GET['filter'] ;
} 

if(defined('ONSH')){
	if($epp>12)$epp=12;
}else{ 
	if($epp<20)$epp=20;
}

$orders_listing = false;
$sortiment_listing = false;
if ((basename($_SERVER['PHP_SELF']) == FILENAME_ACCOUNT_HISTORY) ||(basename($_SERVER['PHP_SELF']) == 'account_torten_history.php')){
	$orders_listing = true; 
} else if (basename($_SERVER['PHP_SELF']) == FILENAME_SORTIMENT) {
	$sortiment_listing = true;
}

$max_rows = $epp;

if ($orders_listing || $sortiment_listing) {
	$count_key = '*';
} else {
	$count_key = 'p.products_id';
}

// infinite products scrolling
if ($epp == 1000 && !isset($_GET['_escaped_fragment_'])) {		
	// 1000 = show all products
	// _escaped_fragment_ for google crawler -> show all products. For more info see https://support.google.com/webmasters/answer/174992

	if (!push_session_is_registered('infinite_products_query')) {
		push_session_register('infinite_products_query');
	}
	$infinite_products_query = $listing_sql;
	if (!push_session_is_registered('infinite_products_query_count_key')) {
		push_session_register('infinite_products_query_count_key');
	}
	$infinite_products_query_count_key = $count_key;
	$max_rows = 20;
	?>
		<script>
			var ajax_load_more_products = true;
			var ajax_products_page = 2;
		</script>
	<?php
}

if ($orders_listing) {
	if(basename($_SERVER['PHP_SELF']) == 'account_torten_history.php')
	$listing_split = new splitPageResults($listing_sql, $max_rows, '*', 'page', 'dbt');
	else
	$listing_split = new splitPageResults($listing_sql, $max_rows);
} else {
	$listing_split = new splitPageResults($listing_sql, $max_rows, $count_key);
}

// sorting options
if ($orders_listing) {
	$selectedOption = $ordersSortOrder;
	$sortingOptions = array(
				"1d" => TABLE_HEADING_ORDER_PRICE . PRICE_SORT_ASC,
				"1a" => TABLE_HEADING_ORDER_PRICE . PRICE_SORT_DESC,
				"2a" => TABLE_HEADING_ORDER_DATE . ' ' . TEXT_DESCENDINGLY,
				"2d" => TABLE_HEADING_ORDER_DATE . ' ' . TEXT_ASCENDINGLY);
} else if ($sortiment_listing) {
	$selectedOption = $sortimentSortOrder;
	$sortingOptions = array(
				"1a" => TABLE_HEADING_PRICE . PRICE_SORT_ASC,
				"1d" => TABLE_HEADING_PRICE . PRICE_SORT_DESC,
				"2a" => TABLE_HEADING_PRODUCTS . TEXT_SORT_ASC_AZ,
				"2d" => TABLE_HEADING_PRODUCTS . TEXT_SORT_DESC_ZA,
				"3a" => TABLE_HEADING_MANUFACTURER . TEXT_SORT_ASC_AZ,
				"3d" => TABLE_HEADING_MANUFACTURER . TEXT_SORT_DESC_ZA,
				"4a" => TABLE_HEADING_BESTELLHAUFIGKEIT . PRICE_SORT_ASC,
				"4d" => TABLE_HEADING_BESTELLHAUFIGKEIT . PRICE_SORT_DESC);
} else {
	$selectedOption = $sortorder;
	$sortingOptions = array(
				"1d" => TABLE_HEADING_POPULARITY . POP_SORT_DESC,
				"5a" => TABLE_HEADING_NEWEST . POP_SORT_ASC,
				"5d" => TABLE_HEADING_NEWEST . POP_SORT_DESC,
				"2a" => TABLE_HEADING_PRICE . PRICE_SORT_ASC,
				"2d" => TABLE_HEADING_PRICE . PRICE_SORT_DESC,
				"4a" => TABLE_HEADING_PRODUCTS . TEXT_SORT_ASC_AZ,
				"4d" => TABLE_HEADING_PRODUCTS . TEXT_SORT_DESC_ZA);
	// sorting by manufacturer only in main categories possible 
	if (isset($cPath_array) && sizeof($cPath_array) > 1) {
		if ($selectedOption == "3a" || $selectedOption == "3d") {
			$selectedOption = "1d";	
		}
	} else {
		$sortingOptions["3a"] = TABLE_HEADING_MANUFACTURER . TEXT_SORT_ASC_AZ;
		$sortingOptions["3d"] = TABLE_HEADING_MANUFACTURER . TEXT_SORT_DESC_ZA; 
	}
}


///////////////////////////////////////////////////////////////////////////////////////START NAV
if ($listing_split->number_of_rows > 0) {
	
	$no_products=false;
	echo push_draw_form('sortieren', push_href_link(basename($_SERVER['SCRIPT_NAME']), $parameters), 'post' , 'id="sortandorder" class="product-listing-navi grid_12"');	
?>
	
	<div class="grid_12 alpha omega bottom_separator tx_12_15" style="padding-bottom: 7px">
		<?php 
			// number of items 
		?>
		<?php
			if ($orders_listing) {
				echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS);
			} else if ($sortiment_listing) {
				echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS);
			} else {
				echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS);
			}			
		?>
	</div>
	<div class="clearfix"></div>
	<div class="grid_12 alpha omega bottom_separator tx_12_15" style="padding: 7px 0 7px 0; border-color: #dddddd">
		<div class="grid_4 alpha" style="position: relative">
			<?php 
				// sorting 
			?>
			<span id="sort-toggle" class="cselection gradientlight">
				<span id="sort-name" style="padding-left: 10px"><?= $sortingOptions[$selectedOption] ?></span>
				<span class="selectarrow" style="position:absolute;right:-5px;top:10px;"></span>
			</span>
			<ul id="sort-list" style="display: none;">
				<?php		
					foreach ($sortingOptions as $key => $value) {
						echo '<li data-sel="' . $key . '">' . $value . '</li>';
					}
				?>
			</ul>
			<input id="selectedsorting" type="hidden" value="<?= $selectedOption ?>" name="<?php 
							if ($orders_listing) {
								echo 'ordersSort';
							} else if ($sortiment_listing) {
								echo 'sortimentSort';
							} else {
								echo 'sort'; 
							}
							?>" />
		</div>
		
		<div class="grid_4">
			<?php 
				// Ansicht 
				if ($orders_listing) {
						echo '&nbsp;';	
				}
				else
				{
					if ($customer->login) {
						?>
							<div style="padding: 6px 3px 0 0; float: left">Ansicht:</div> 
						<?php				
							if ($view == 'list') { 
						?>					
								<div class="select-view-active tx_12_15" title="Liste">Liste</div>
								<input type="submit" class="select-view-inactive tx_blue tx_12_15" name="gallery" value="Galerie" title="Galerie">
						<?php 
							} else if ($view == 'gallery') { 
						?>
								<input type="submit" class="select-view-inactive tx_blue tx_12_15" name="list" value="Liste" title="Liste">
								<div class="select-view-active tx_12_15" style="padding-left: 5px; width: 43px" title="Galerie">Galerie</div>
						<?php 
							} 
					} else {
						echo '&nbsp;';	
					}
				}
			?>
		</div>
		
		<?php
			if ($listing_split->number_of_pages > 1) {
		?>
				<div class="grid_4 omega" style="position: relative"> 
					<?php  
						// page select 
					?>
					<div style="padding-left: 30px; line-height: 30px">
						Seite 
						<input class="pageselector" type="text" name="psel" value="<?= $listing_split->current_page_number ?>" width="3" maxlength="3" /> 
						von <?= ($epp == 1000) ? '1' : $listing_split->number_of_pages ?>
					</div>
					<?php
						if ($epp != 1000) echo $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, push_get_all_get_params(array('page', 'info', 'x', 'y'))); 
					?>
				</div>
		<?php
			}
		?>
	</div>
	
	<?php	
		if(isset($_GET['cPath'])){
			echo push_draw_hidden_field('cPath',$_GET['cPath']);
		}elseif(isset($_GET['manufacturers_id'])){
			echo push_draw_hidden_field('manufacturers_id',$_GET['manufacturers_id']);
		}elseif(isset($_GET['categories_id'])){
			echo push_draw_hidden_field('categories_id',$_GET['categories_id']);
		}
	
		if(isset($_GET['page'])){
			echo push_draw_hidden_field('page',$_GET['page']);
		}elseif(isset($_POST['page'])){
			echo push_draw_hidden_field('page',$_POST['page']);
		}

	?>
	</form><!--EOF sortandorder -->
	
<?php
} else {
	$no_products=true;
}
?>