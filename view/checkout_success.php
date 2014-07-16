<?php
/*
  $Id: checkout_success.php,v 1.49 2003/06/09 23:03:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');
//TELECASH CONNECT
if(isset($_POST['response_hash'])){
	$hstring = MODULE_PAYMENT_TELECASH_CONNECT_EWP_CERT_ID . $_POST['approval_code']. $_POST['chargetotal'] .  $_POST['currency'] . urldecode($_POST['zeit']) . MODULE_PAYMENT_TELECASH_CONNECT_ID ;
	$hhex = bin2hex($hstring);
	$hash = sha1($hhex);
	if ($_POST['response_hash'] == $hash){
//finish checkout
		$cart->reset(true);
// Free Gift Mod
		$cart->set_freegift(0);
// End Free Gift Mod
// unregister session variables used during checkout 
		push_session_unregister('sendto');
		push_session_unregister('billto');
		push_session_unregister('shipping');
		push_session_unregister('payment');
		push_session_unregister('comments');
		if(push_session_is_registered('credit_covers')) push_session_unregister('credit_covers');// CCGV
		push_redirect(push_href_link(FILENAME_CHECKOUT_SUCCESS));
	}else{
		push_redirect(push_href_link(FILENAME_CHECKOUT_PAYMENT));
	}
}
// if the customer is not logged on, redirect them to the shopping cart page
  if (!push_session_is_registered('customer_id')) {
    push_redirect(push_href_link(FILENAME_SHOPPING_CART));
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'update')) {
    $notify_string = 'action=notify&';

    //extra questions start
if (QUESTION_LOCATION == 'after_purchase') require('extra_questions_upload_result_box.php');
//extra questions end
//extra questions start
if (QUESTION_LOCATION == 'after_purchase')  require('extra_question_db_upload_box.php');
//extra questions end

    $notify = $_POST['notify'];
    if (!is_array($notify)) $notify = array($notify);
    for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
      $notify_string .= 'notify[]=' . $notify[$i] . '&';
    }
    if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);

    push_redirect(push_href_link(FILENAME_DEFAULT, $notify_string));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  $global_query = push_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
  $global = push_db_fetch_array($global_query);

  if ($global['global_product_notifications'] != '1') {
    $orders_query = push_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' order by date_purchased desc limit 1");
    $orders = push_db_fetch_array($orders_query);

    $products_array = array();
    $products_query = push_db_query("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
    while ($products = push_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name']);
    }
  }

$ordernr=false;
if(isset($_GET['order_id'])){
	$ordernr=$_GET['order_id'];
}elseif (isset($_POST['invoicenumber'])){
	$ordernr=$_POST['invoicenumber'];
}
if($ordernr){
	//push_redirect(push_href_link(FILENAME_ACCOUNT, 'order_id='. $ordernr,'SSL'));
}

require(DIR_WS_BOXES . 'html_header.php');

?>
<!-- body_text //-->
<div id="container_16">
	<?php
	require(DIR_WS_BOXES . 'checkout_head.php');
	?>
	<div class="grid_16" style="margin-bottom: 50px">
  		<div class="grid_2 alpha omega">
			<img src="images/push/green-light.png" />
		</div>
		<div class="grid_8 alpha omaga">
			<div class="tx_30_40">Vielen Dank!</div><br />
			<div class="tx_15_20">Nun packen und versenden wir für Sie. Wir beeilen uns!</div>
            <br /><br />
			<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 150px" href="<?= push_href_link(FILENAME_DEFAULT) ?>">Weiter zum Shop<img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
		</div>
	</div>
    <div class="grid_16">
    	<?php include(DIR_WS_MODULES . 'also_purchased_products.php'); ?>
	    <?php include(DIR_WS_MODULES . 'interesting_products.php'); ?>
	    <?php include(DIR_WS_MODULES . 'seenproducts.php'); ?>
	</div> 
<!-- body_text_eof //-->
</div>
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<?php require(DIR_WS_LIB . 'end.php'); ?>