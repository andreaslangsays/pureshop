<?php
/*
  $Idä: account_history.php,v 1.63 2003/06/09 23:03:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/ajax_top.php');
	
	if (!push_session_is_registered('customer_id')) {
		$navigation->set_snapshot();
		push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
	}

	// define $ordersSortOrder as session-var
	if (!push_session_is_registered('ordersSortOrder')) {
		push_session_register('ordersSortOrder');
		$ordersSortOrder = "1d";
	}
	if (isset($_POST['ordersSort']))
		$ordersSortOrder = $_POST['ordersSort'];

	// sort orders
	$ordersSort_col = substr($ordersSortOrder, 0 , 1);
	$ordersSort_order = substr($ordersSortOrder, 1);
	$ordersSortBy = ' ORDER BY ';
	switch ($ordersSort_col) {
		case '2':
			$ordersSortBy .= "o.date_purchased" . ($ordersSort_order == 'd' ? ' DESC' : '');
			break;
		case '1':
			$ordersSortBy .= "ot.value" . ($ordersSort_order == 'd' ? ' DESC' : '') . ", o.date_purchased DESC";
			break;
	}

	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY);
	

	$breadcrumb->reset();
	//$breadcrumb->add('Pers&ouml;nlicher Bereich', FILENAME_ACCOUNT);
	$breadcrumb->add(NAVBAR_TITLE_2, push_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
require(DIR_WS_BOXES . 'html_header.php');
?>
<?php	include(DIR_WS_BOXES . 'static_menu.php'); ?>
<!-- body_text //-->
    <h1 class="grid_12" style="display:none;"><?php echo HEADING_TITLE; ?></h1>
<?php
	$orders_total = push_count_customer_orders();

	if ($orders_total > 0) {
		$listing_sql = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.value from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot where (o.customers_id = '" . (int)$_SESSION['customer_id'] . "' or o.customers_id = '" . (int)$customer->selectline_customers_id . "' ) and o.orders_id = ot.orders_id and ot.class = 'push_summe' " . $ordersSortBy;
//echo $listing_sql;
include(DIR_WS_MODULES . "pagenavi.php");		
?>		
<div class="grid_12 prefix_4">
		<div class="grid_12 alpha omega" style="height:20px;"></div>
        <div class="alpha grid_2 tx_12_15"><?php echo TEXT_ORDER_DATE; ?></div>
        <div class="grid_2 tx_12_15"><?php echo TEXT_ORDER_NUMBER; ?></div>
        <div class="grid_2 tx_12_15"><?php echo '&nbsp'; ?></div>
        <div class="grid_3 tx_12_15"><?php echo '&nbsp'; ?></div>
        <div class="grid_3 omega tx_12_15"><?php echo TEXT_ORDER_COST; ?></div>
		<div class="grid_12 alpha omega" style="margin-bottom:10px;height:10px; border-bottom:solid 1px #ccc;"></div>
<?php
		$history_query = push_db_query($listing_split->sql_query);
		$pos = ($listing_split->current_page_number - 1) * $listing_split->number_of_rows_per_page;
		
		$zahler=0;
		while ($history = push_db_fetch_array($history_query)) {
			$products_query = push_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
			$products = push_db_fetch_array($products_query);
			$pos++;
		
//		if (push_not_null($history['delivery_name'])) {
//		$order_type = TEXT_ORDER_SHIPPED_TO;
//		$order_name = $history['delivery_name'];
//		} else {
//		$order_type = TEXT_ORDER_BILLED_TO;
//		$order_name = $history['billing_name'];
//		}
?>
			<?php echo '<a class="historyRow" href="' . push_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'order_id=' . $history['orders_id'], 'SSL') . '">'; 
			if($zahler > 0)
			{
			?>
			  <div class="grid_12 alpha omega" style="margin-bottom:10px;height:10px; border-bottom:dotted 1px #ccc;"></div>
			<?php
			}
			$zahler++;
			?>
				
            	<!--<div class="history hC1"><?php echo $pos; ?></div>-->
              	<div class="alpha grid_2 tx_blue tx_15_20"><?php echo push_date_short($history['date_purchased']); ?></div>
                <div class="grid_2 tx_15_20"><?php echo $history['orders_id']; ?></div>
                <div class="grid_2 tx_15_20">&nbsp;<?php //echo $products['count']; ?></div>
                <div class="grid_3 tx_15_20">&nbsp;</div>
                <div class="grid_3 omega tx_15_20"><?php echo $currencies->display_price($history['value'], 0); ?></div>
              
            </a>
				<div class="clearfix" style="height:10px;"></div>
<?php
		}
?>
		<div id="hBottomSep"></div>
<?php
		include(DIR_WS_MODULES . "bottomnavi.php");
		
	} else {
?>
          <div class="grid_12 tx_12_15" style="padding-bottom:10px;border-bottom:1px solid #ccc;margin-bottom_40px;">0 Bestellungen</div>
		  <h2 class="grid_12 tx_30_40"><?php echo TEXT_NO_PURCHASES; ?></h2>
<?php
	}
?>     
<!-- body_text_eof //-->
<!-- body_eof //-->
</div>
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>