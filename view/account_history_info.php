<?php
/*
  $Idä: account_history_info.php,v 1.100 2003/06/09 23:03:52 hpdl Exp $

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

  if (!isset($_GET['order_id']) || (isset($_GET['order_id']) && !is_numeric($_GET['order_id']))) {
    push_redirect(push_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }

  $customer_info_query = push_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". (int)$_GET['order_id'] . "'");
  $customer_info = push_db_fetch_array($customer_info_query);
  if (($customer_info['customers_id'] != $_SESSION['customer_id'] ) and ($customer_info['customers_id'] != $customer->selectline_customers_id )) {
    push_redirect(push_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
  
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

  
	$history_res = push_db_query($history_query = "	SELECT 	o.orders_id 
													FROM 	" . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot
													WHERE 	(o.customers_id = '" . (int)$_SESSION['customer_id'] . "' or o.customers_id = '" . (int)$customer->selectline_customers_id . "' )
															AND o.orders_id = ot.orders_id 
															AND ot.class = 'push_summe' 
															" . $ordersSortBy);
	
	$historySize = mysql_num_rows($history_res); 
	
	$o = 1;
	while ($history = push_db_fetch_array($history_res)) {
		if (isset($currentOrder) && !isset($nextOrder)) {
			$nextOrder = $history['orders_id'];
			break;
		}
		if ($_GET['order_id'] == $history['orders_id']) {
			$currentOrder = $history['orders_id'];
			$orderNr = $o;
		}
		if (!isset($currentOrder))
			$prevOrder = $history['orders_id'];
		$o++;
	}

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

	$breadcrumb->reset();
//	$breadcrumb->add('Pers&ouml;nlicher Bereich', FILENAME_ACCOUNT);
	$breadcrumb->add('Meine Bestellungen', push_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
	$breadcrumb->add(sprintf(NAVBAR_TITLE_3, $_GET['order_id']), push_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_GET['order_id'], 'SSL'));

require(DIR_WS_CLASSES . 'order.php');
$order = new order($_GET['order_id']);
require(DIR_WS_BOXES . 'html_header.php');
?>
<!-- body_text //-->
<?php
	echo '<div id="mininavi" class="grid_16" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc;padding: 0;margin-bottom:10px;">';
	echo '<span  class="tx_left tx_12_20" style="display:inline-block;margin-top:8px;font-size:12px;">' . $orderNr ." von ". $historySize . ' Bestellungen <a href="' . push_href_link(FILENAME_ACCOUNT_HISTORY) . '" >&#9632 zur &Uuml;bersicht</a></span><span class="tx_right" style="position:relative; bottom:-3px;">' ;
	if(isset($prevOrder))
		echo '<span class="before" style="display_inline-block;margin-right:20px;"><a href="' .push_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $prevOrder) . '" ><img src="' . push_href_link(DIR_WS_IMAGES . 'push/pageselector-prev-bg.png') . '"></a></span> ';
	if(isset($nextOrder))
		echo '<span class="after"><a href="' .push_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $nextOrder) . '" ><img src="' . push_href_link(DIR_WS_IMAGES . 'push/pageselector-next-bg.png') . '"></a></span>';
	echo "</span></div>";
?>

    <h1 class="tx_30_40"><?php echo 'Bestellung ' . $_GET['order_id'] . ' vom ' . strftime(DATE_FORMAT_SHORT, strtotime($order->info['date_purchased'])); ?></h1>
	<div class="grid_10 tx_13_20">
	<h2 class=" grid_10 alpha omega tx_25_30">Überblick</h2>
		<div class="alpha grid_3"><b><?php echo HEADING_ORDER_NUMBER; ?></b></div><div class="grid_7 omega"><?php echo $_GET['order_id']; ?></div>
		<div class="alpha grid_3"><b><?php echo  HEADING_ORDER_TOTAL; ?></b></div><div class="grid_7 omega"><?php echo $currencies->format($order->info['push_summe']); ?></div>
		<div class="alpha grid_3"><b><?php echo HEADING_PRODUCTS; ?></b></div><div class="grid_7 omega"><?php echo sizeof($order->products); ?></div>
<h2 class="grid_10 alpha omega tx_25_30" style="margin-top:20px;">Lieferadresse</h2>
	<div class="grid_10 alpha omega"><?php echo push_address_format(6, $order->delivery, 1, ' ', '<br />'); ?><?=$order->delivery['country']?></div>
<?php
/*
		$statuses_query = push_db_query("SELECT 	os.orders_status_name, 
												osh.date_added, 
												osh.comments 
										FROM 	" . TABLE_ORDERS_STATUS . " os, " . 
												TABLE_ORDERS_STATUS_HISTORY . " osh 
										WHERE 	osh.orders_id = '" . (int)$_GET['order_id'] . "' 
												AND osh.orders_status_id = os.orders_status_id 
												AND os.language_id = '" . (int)$languages_id . "' 
										ORDER BY osh.date_added");
										
		while ($statuses = push_db_fetch_array($statuses_query)) {
			echo '<span>' . push_date_short($statuses['date_added']) . "</span>";
			echo '<div class="ahiDiv">' . $statuses['orders_status_name'] . '<br />';
			echo (empty($statuses['comments']) ? '' : HEADING_COMMENT . nl2br(push_output_string_protected($statuses['comments']))) . '</div><br />';
		}
		echo '&nbsp;';
*/

 
?>
<h2 class="grid_10 alpha omega tx_25_30" style="margin-top:20px;">Warenkorb</h2>
</div><div class="grid_6" >
<div style="border:1px solid #88BDE5;background-color:#e7f2fa;padding:10px;">
<h3 class="tx_bold tx_12_15 tx_light_gray">Fragen zu Ihrer Bestellung? Kontaktieren Sie uns:</h3><br />
<a href="mailto:service@if-bi.com" class="tx_blue tx_12_20"> service@if-bi.com</a><br />
<span class="tx_12_20 tx_light_gray">+49 (0)30 28 47 00–0</span><br /><br />
<span class="tx_12_15 tx_bold tx_light_gray">Nicht zufrieden mit Ihrer Lieferung? Schade. </span><br />
<span class="tx_12_15 tx_bold tx_light_gray">— Bitte füllen Sie unser Reklamationsformular aus.</span><br />
<a href="<?php echo push_href_link(FILENAME_ACCOUNT_COMPLAINT, 'oID=' . $_GET['order_id'] );?>" class="button gradientgrey tx_12_15" style="width:125px;margin-top:20px;" >Zur Reklamation <span style="display:block;float:right;margin-right:6px;margin-top:10px;width:0px;height:0;border:5px solid transparent;border-left:5px solid #333;">&nbsp;</span></a>

</div>
</div>
<div class="clearfix"></div>
	<div class="grid_6 tx_12_15"><?php echo TABLE_HEADING_PRODUCT; ?></div>
	<div class="grid_3 tx_12_15"><?php echo TABLE_HEADING_PRICE_PER_UNIT; ?></div>
	<div class="grid_2 tx_12_15"><?php echo TABLE_HEADING_QUANTITY; ?></div>
	<div class="grid_2 tx_12_15"><?php echo TABLE_HEADING_UNIT; ?></div>
	<div class="grid_3 tx_12_15"><?php echo TABLE_HEADING_TOTAL; ?></div>
	<div class="grid_16" style="height:10px;border-bottom:solid 1px #ccc;margin-bottom:20px;">&nbsp;</div>
<?php
for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
		$p->load_product($order->products[$i]['id']);

?>
	
	<div class="grid_1">
	<?php 
	
	echo '<img src="' .DIR_WS_IMAGES  . $p->get_image('orderimg', 40) . '" />';

	?>
	</div>

	<div class="grid_5">
	<?php 
		$produktname= $p->products_name;
		if(substr($order->products[$i]['name'],-2) == 'VE')
		{
			$is_ve = true;
		}
		else
		{
			$is_ve = false;
		}
				if ($order->products[$i]['products_status'] == 1) {
					echo '<a href="' . push_href_link(FILENAME_PRODUCT_INFO, "products_id=" . $order->products[$i]['id']) . '" class="tx_blue tx_15_20">' . $produktname . '</a><br />'; 
				} else {
					echo $produktname . '<br />'; 
				}
				if($p->manufacturers_name <>'')
				{
				?>				
				<span class="tx_12_15"><?= $p->manufacturers_name?></span>
				<br />
				<?php
				}
				
				?>
				<span class="tx_12_15">Artikel-Nr. <?= $p->products_model?></span>
				<br />
			</div>
			<div class="grid_3 tx_13_20">
				<?php echo $currencies->format(push_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']));
				if($is_ve)
				{
					echo "/  VE";
				}
				else
				{
					echo "/ Stück";
				}
				 ?>
			</div>
			<div class="grid_2 tx_13_20">
			<?php
				echo $order->products[$i]['qty'];
			?>
			</div>
			<div class="grid_2 tx_13_20">
				<?php 
				if($is_ve)
				{
					echo "VE";
				}
				else
				{
					echo "Stück";
				}
				//echo $currencies->format(push_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);
			 ?>
			</div>
			<div class="grid_3 tx_15_20 tx_right tx_blue">
			<?php echo $currencies->format(push_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']); ?>
			</div>
	<div class="grid_16" style="height:10px;border-bottom:1px dotted #ccc;margin-bottom:10px;">&nbsp;</div>
<?php
	}

$it=push_db_fetch_array(push_db_query("SELECT value FROM orders_total WHERE orders_id='" . $_GET['order_id'] . "' AND class='push_pre_netto';"));
?>
<div class="prefix_6 grid_7">Gesamtbestellwert netto</div><div class="grid_3 tx_right tx_blue"><?php echo $currencies->format($it['value'])?></div>
<div class="clearfix"></div>
<?php
$it=push_db_fetch_array(push_db_query("SELECT value FROM orders_total WHERE orders_id='" . $_GET['order_id'] . "' AND class='push_shipping';"));
?>
<div class="prefix_6 grid_7 tx_13_20">Versandkosten</div><div class="grid_3 tx_right tx_blue tx_13_20"><?php echo $currencies->format($it['value'])?></div>
<div class="clearfix"></div>
<div class="prefix_6 grid_10 tx_pink tx_13_20">Ersparnisse:</div>
<div class="clearfix"></div>
<?php 
if(intval($it['value']) == 0)
{
?>
<div class="prefix_6 grid_10 tx_pink tx_13_20">Versandkosten <span class="tx_pink tx_13_20"><?= $currencies->format(9.95)?></span></div>
<div class="clearfix"></div>
<?php
}
$it=push_db_fetch_array(push_db_query("SELECT value FROM orders_total WHERE orders_id='" . $_GET['order_id'] . "' AND class='push_onlinerabatt';"));
if($it['value'] > 0)
{
?>
<div class="prefix_6 grid_7 tx_pink tx_13_20">1% Onlinerabatt</div><div class="grid_3 tx_right  tx_pink tx_13_20"><?php echo $currencies->format(-1*$it['value'])?></div>
<div class="clearfix"></div>
<?php
}
$it=push_db_fetch_array(push_db_query("SELECT value FROM orders_total WHERE orders_id='" . $_GET['order_id'] . "' AND class LIKE ('push_discount%');"));
if($it['value'] > 0)
{
?>
<div class="prefix_6 grid_7 tx_pink tx_13_20">Staffelrabatt</div><div class="grid_3 tx_right  tx_pink tx_13_20"><?php echo $currencies->format(-1 * $it['value'])?></div>
<div class="clearfix"></div>
<?php
}
//@TODO: Gutschein!!
$it=push_db_fetch_array(push_db_query("SELECT value FROM orders_total WHERE orders_id='" . $_GET['order_id'] . "' AND class='push_summe';"));
?>
<div class="prefix_6 grid_5 tx_bold tx_13_15">Gesamtbetrag netto</div><div class="tx_right grid_5 tx_30_40 tx_blue"><?php echo $currencies->format($it['value'])?></div>
<div class="clearfix"></div>
<?php
$tq=push_db_query("SELECT text, value FROM orders_total WHERE orders_id='" . $_GET['order_id'] . "' AND class='push_tax';");
while($it=push_db_fetch_array($tq))
{
?>
<div class="prefix_6 grid_10 tx_13_15"><?= $it['text']?> <span class="tx_13_15"><?php echo $currencies->format($it['value'])?></span></div>
<div class="clearfix"></div>
<?php
}

?>
<?php
/*
	echo '<div id="bottomnavi"> ';
	echo '<span class="xofy"><!--' . $orderNr . " von " . $historySize . ' | --><a href="' . push_href_link(FILENAME_ACCOUNT_HISTORY) . '" > &#9632; zur &Uuml;bersicht</a>' ;
	echo '<a href="#" class="totop">&#9650; nach oben</a>';
	if(isset($prevOrder))
		echo '<a href="' .push_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $prevOrder) . '" class="before">&#9664; Zur&uuml;ck</a> ';
	if(isset($nextOrder))
		echo '<a href="' .push_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $nextOrder) . '" ' . ((isset($prevOrder))? 'class="after"': 'style="margin-left:351px;"').'>Weiter &#9654;</a></span>';
	echo "</div>";
/**/

?>
<!-- body_eof //-->
</div>


<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>