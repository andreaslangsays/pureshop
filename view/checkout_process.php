<?php
/*
  $Idä: checkout_process.php,v 1.128 2003/05/28 18:00:29 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

function n_f($inpt)
{
	return number_format($inpt,4,'.','');
}
include('includes/ajax_top.php');
//User must confirm conditions
//error_reporting(E_ALL | E_STRICT);
if(!isset($_POST['agree'])) {
	//    push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'alert=agb', 'SSL'));
}


// if the customer is not logged on, redirect them to the login page
  if (!push_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_CONFIRMATION));
    push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  
	if (!isset($_POST['agree'])) {
		push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'err=agree', 'SSL'));
	}

  if (!push_session_is_registered('sendto')) {
  //  push_redirect(push_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }


  if ( (push_not_null(MODULE_PAYMENT_INSTALLED)) && (!push_session_is_registered('payment')) ) {
   // push_redirect(push_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
 }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && push_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
    }
  }

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// load selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  if ($credit_covers) $payment=''; // CCGV
  $payment_modules = new payment($payment);
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

if(sizeof($order->products) < 1)
{
	//prevent reordering as of going back in browser
	//and empty orders
     push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
}
// load the selected shipping module
//  require(DIR_WS_CLASSES . 'shipping.php');
// $shipping_modules = new shipping($shipping);


// check order total minimum
//  if ($order->info['subtotal'] < MIN_ORDER_AMOUNT) {
//   push_redirect(push_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
// }
/*
	if (isset($_POST['redeem'])) {
		push_session_register('couponCode');
		$couponCode = $_POST['gv_redeem_code'];      	
		push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
	} 
*/
require(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;
$land=$order->delivery['country']['title'];
//$customer= new Customer($_SESSION['customer_id']);
$shippingAddress = $customer->get_address_by_id($_POST['shippingAddress']);
$country = 'DE';
$countryarray =  push_get_countries($shippingAddress['country_id']);
$country = $countryarray['countries_name'];

include(DIR_WS_CLASSES . 'Splitorders.php');
$sord=new splitorder();
for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
{
	$product->load_product($order->products[$i]['id']);	
	if($product->products_drop_shipment == 1)
	{
		$sord->add_to_cart($order->products[$i]['id'], $order->products[$i]['qty'], 'drop');
	}
	else
	{
		$sord->add_to_cart($order->products[$i]['id'], $order->products[$i]['qty'], 'nodrop');
	}
}


$sord->straighten_discounts();
$carts = $sord->names;

/* * /
foreach($carts as $cartname)
{
	echo "<pre>";
	var_dump($sord->tax);
	echo"</pre>##############################################################################################################";
	
	echo "<pre>{{!! $cartname ";
	foreach($sord->cart[$cartname]['p'] as $c_product) 
	{
		var_dump($c_product);	
	}
	foreach($sord->cart[$cartname]['staffel_tax'] as $vtax => $ttax)
	{
		echo "$vtax --->" . $ttax  . " ? <br>";
		echo "$vtax --->" . floatval($ttax) . "<br> " ;
	}
	
	echo " PRESUM : " . $sord->cart[$cartname]['summe'] ." ??<br>";
	echo "ENDSUM : " . (($sord->cart[$cartname]['summe'] + $discount->shipping_cost) -  $sord->tmp_endsum[$cartname])	 . " ?? <br>";
	echo "!!!}}</pre>";
}
die("[X!!$%$%$!!X]");
/**/
$anzahl_cart_loops=0;
$order_totals = $order_total_modules->process();
if($payment_modules->before_process() == "decline")
{
	push_redirect(push_href_link(FILENAME_CHECKOUT_PAYMENT, 'CC-Error', 'SSL'));
}
foreach($carts as $cartname)
{
	$oID=push_generate_order_id('S');
	$sql_data_array = array('orders_id' => $oID,
							'customers_id' => $customer->selectline_customers_id,
							'customers_name' => $customer->customers_firstname, 
							'customers_lastname' =>  $customer->customers_lastname,
							'customers_company' => $order->customer['company'],
							'customers_street_address' => $order->customer['street_address'],
							'customers_suburb' => $order->customer['suburb'],
							'customers_city' => $order->customer['city'],
							'customers_postcode' => $order->customer['postcode'],
							'customers_state' => $order->customer['state'],
							'customers_country' => $order->customer['country']['title'],
							'customers_telephone' => $order->customer['telephone'],
							'customers_email_address' => $customer->customers_email_address,
							'customers_address_format_id' => $_SESSION['customer_default_address_id'],
							'delivery_name' => $shippingAddress['firstname'] . ' ' . $order->delivery['lastname'],
							'delivery_company' => $shippingAddress['company'],
							'delivery_street_address' => $shippingAddress['street_address'],
							'delivery_suburb' => $shippingAddress['suburb'],
							'delivery_city' => $shippingAddress['city'],
							'delivery_postcode' => $shippingAddress['postcode'],
							'delivery_state' => $shippingAddress['state'],
							'delivery_country' => $country,
							'delivery_address_format_id' => $shippingAddress['id'],
							'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
							'billing_company' => $order->billing['company'],
							'billing_street_address' => $order->billing['street_address'],
							'billing_suburb' => $order->billing['suburb'],
							'billing_city' => $order->billing['city'],
							'billing_postcode' => $order->billing['postcode'],
							'billing_state' => $order->billing['state'],
							'billing_country' => $order->billing['country']['title'],
							'billing_address_format_id' => $order->billing['format_id'],
							'payment_method' => $order->info['payment_method'],
							'payment_info' => $GLOBALS['payment_info'],
							'cc_type' => $order->info['cc_type'],
							'cc_owner' => $order->info['cc_owner'],
							'cc_number' => $order->info['cc_number'],
							'cc_orderid' => $order->info['cc_orderid'],
							'cc_full' => $order->info['cc_response'],
							'cc_expires' => $order->info['cc_expires'],
							'date_purchased' => 'now()',
							'orders_status' => $order->info['order_status'],
							'currency' => $order->info['currency'],
							'currency_value' => $order->info['currency_value']);
	push_db_perform(TABLE_ORDERS, $sql_data_array);
	$insert_id = $oID;

//hard written push vars
#ZWISCHENSUMME
	$sql_data_array = array(
			'orders_id' => $insert_id,
			'title' => 'Zwischensumme netto',
			'text' => $sord->cart[$cartname]['summe'],
			'value' => n_f($sord->cart[$cartname]['summe']),
			'class' => 'push_pre_netto',
			'sort_order' => 1);
	push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
#VERSANDKOSTEN
	$sql_data_array = array(
			'orders_id' => $insert_id,
			'title' => 'Versandkosten',
			'text' => $discount->shipping_cost,
			'value' => n_f($discount->shipping_cost),
			'class' => 'push_shipping', 
			'sort_order' => 2);
	push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
#ONLINERABATT
	$sql_data_array = array(
			'orders_id' => $insert_id,
			'title' => 'Onlinerabatt',
			'text' => $sord->temp_online[$cartname],
			'value' => n_f($sord->temp_online[$cartname]),
			'class' => 'push_onlinerabatt', 
			'sort_order' => 3);
	push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
#STAFFELRABATT
	//DISTINCTION !
#PERSONAL/STAFFEL RABATT
	if($sord->personal_discount > 0)
	{
		$sql_data_array = array('orders_id' => $insert_id,
							'title' => 'Kundenspezifischer Rabatt',
							'text' => $sord->temp_personal[$cartname],
							'value' => n_f($sord->temp_personal[$cartname]),
							'class' => 'push_discount_C' ,
							'sort_order' => 4);
		push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	}
	else
	{
		$sql_data_array = array('orders_id' => $insert_id,
							'title' => 'Staffelrabatt',
							'text' => $sord->temp_staffel[$cartname],
							'value' => n_f($sord->temp_staffel[$cartname]),
							'class' => 'push_discount_'.intval($sord->staffel) ,
							'sort_order' => 4);
		push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	}
#COUPON
	if($discount->coupon['is'])
	{
		$sql_data_array = array('orders_id' => $insert_id,
							'title' => $discount->coupon['name'],
							'text' => $sord->temp_coupon[$cartname],
							'value' => n_f($sord->temp_coupon[$cartname]),
							'class' => 'push_discount_S' ,
							'sort_order' => 5);
		push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);

	}
#NETTOSUMME $this->tmp_endsum[$name]
	$nettosumme = 	($sord->cart[$cartname]['summe'] + $discount->shipping_cost) -  $sord->tmp_endsum[$cartname];
	$sql_data_array = array('orders_id' => $insert_id,
			'title' => 'Nettosumme',
			'text' =>  $nettosumme,
			'value' => n_f($nettosumme),
			'class' => 'push_summe',
			'sort_order' => 6);
	push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
#TAX
	foreach( $sord->tax[$cartname] as $v_tax => $t_tax)
	{
		$sql_data_array = array('orders_id' => $insert_id,
								'title' => "TAX ". $v_tax,
								'text' => $t_tax,
								'value' => n_f($t_tax),
								'class' => 'push_tax_'.$v_tax,
								'sort_order' => 6 + $i);
		push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	}
	unset($v_tax);
	unset($t_tax);
#OSCCOUPON MODULE OFF?
/*	$gv = new ot_coupon;
	$gv->process();
	foreach($gv->output as $nclass)
	{
		$sql_data_array = array('orders_id' => $insert_id,'title' => 'Gutschein','text' => $nclass['title'],
			'value' => n_f($nclass['value']),
			'class' => 'push_coupon', 'sort_order' => 6 + $i);
		$i++;
		push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	}

/*	$j=$i+12;
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => n_f($order_totals[$i]['value']),
                            'class' => $order_totals[$i]['code'],
                            'sort_order' => $j + $order_totals[$i]['sort_order']);
   //-
   push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }
*/
  $sql_data_array = array('orders_id' => $insert_id,
                          'orders_status_id' => $order->info['order_status'],
                          'date_added' => 'now()',
                          'customer_notified' => 1,
                          'comments' => $order->info['comments']);
 //-
  push_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

// initialized for the email confirmation
	$products_ordered = '';
	$subtotal = 0;
	$total_tax = 0;
	$array_of_products = array();
	$product=new product();
	foreach($sord->cart[$cartname]['p'] as $c_product) 
	{
		$product->load_product($c_product['id']);
		$qty = ($product->ve_loaded) ? $c_product['qty'] * $product->ve_multiplier : $c_product['qty'];
		if($product->products_drop_shipment == 0)
		{
			$stock_values = $product->products_db_quantity;
			$stock_left = $stock_values - $qty;
			if($stock_left < 0)
			{
				$stock_left = 0;
			}
			push_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . $product->products_id . "'");
			if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') )
			{
				//push_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . push_get_prid($c_product['id']) . "'");
			}
		}
		
		// Update products_ordered (for bestsellers list)
		push_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " .  $qty . " where products_id = '" . $product->id  . "'");
		
		$sql_data_array = array('orders_id' => $insert_id,
								'products_id' => $product->products_id,
								'products_model' => $product->products_model,
								'products_name' => $product->products_name . (($product->ve_loaded)?'VE':''),
								'products_price' => n_f($c_product['price']),
								'final_price' => n_f($c_product['final_price']),
								'products_tax' => $c_product['tax'],
								'products_quantity' => $c_product['qty']);
		push_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
		$order_products_id = push_db_insert_id();
		//$order_total_modules->update_credit_account($i);// CCG
		$total_weight += ($qty * $product->products_weight);
		$total_tax += $c_product['tax'];
		$total_cost += $c_product['final_price'];
		// start email modification:
	
	}
	if(count($discount->free_product_ids)>0)
	{
		$gp = new product;
		foreach($discount->free_product_ids as $gratis)
		{
			$gp->load_product($gratis);
			$sql_data_array = array('orders_id' => $insert_id,
									'products_id' => $gp->products_id,
									'products_model' => $gp->products_model,
									'products_name' => $gp->products_name . (($gp->ve_loaded)?'VE':''),
									'products_price' => 0,
									'final_price' => 0,
									'products_tax' => 0,
									'products_quantity' => 1);
			push_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
		}
	}
	
	require(DIR_WS_MODULES . 'email/html_checkout_process_order.php');
	$email_order = $html_email_order ;

	push_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . ' ' . $insert_id , $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

	if (SEND_EXTRA_ORDER_EMAILS_TO != '')
	{
		$email_order = $html_email_order ;
		if (EMAIL_USE_HTML == 'true')
		{
			$email_order;
		}

		push_mail('', SEND_EXTRA_ORDER_EMAILS_TO,  'push ' . EMAIL_TEXT_SUBJECT_1 . ' ' . $insert_id , $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		//mail('andreas.lang@Bruesselser-Kakaoroesterei.de', utf8_decode(STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . ' ' . $insert_id . ' ' .EMAIL_TEXT_SUBJECT_2) , $email_order_text,"From: Onlineshop <onlineshop@Bruesselser-Kakaoroesterei.de>");

	}
	require_once(DIR_WS_CLASSES ."xml_order.php");
	$xo = new xml_order($insert_id);
	$xo->generate_xml();
	$xo->send_xml();
	$anzahl_cart_loops++;
}

$uc=push_db_query("UPDATE customers SET customer_credit_amount =  customer_credit_amount - " . n_f($discount->actual_discount_price + $discount->shipping_cost,2,'.','') . " WHERE customers_id = '" . $_SESSION['customer_id'] .  "';" );
//REDEEM
$discount->fix_and_redeem($oID);
$payment_modules->after_process();

		$adminmail='transmitted POSTVARS: ' . "\n";
		$i=0;
		foreach($_POST as $post => $value)
		{
		$i++;
		$adminmail .= $i.'{' . $post . ':'  . $value .'}' . "\n";
		}
		$adminmail.='transmitted GETVARS: ' . "\n";
		$i=0;
		foreach($_GET as $post => $value)
		{
		$i++;
		$adminmail .= $i.'{' . $post . ':'  . $value .'}' . "\n";
		}
		$adminmail.='transmitted SESSIONVARS: ' . "\n";
		$i=0;
		foreach($_SESSION as $post => $value)
		{
		$i++;
		if(!is_object($value))
		{
		$adminmail .= $i.'{' . $post . ':'  . $value .'}' . "\n";
		}
		}
		$adminmail.='SERVER: ' . "\n";
		$i=0;
		foreach($_SERVER as $post => $value)
		{
		$i++;
		$adminmail .= $i.'{' . $post . ':'  . $value .'}' . "\n";
		}
		mail('rootifyme@gmail.com', utf8_decode(STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . ' ' . $insert_id . ' ' .EMAIL_TEXT_SUBJECT_2) , $adminmail  , "From: Onlineshop <onlineshop@bif-bi.com>");

$cart->reset(true);
// Free Gift Mod
$cart->set_freegift(0);
// End Free Gift Mod
push_session_unregister('sendto');
push_session_unregister('billto');
push_session_unregister('shipping');
push_session_unregister('payment');
push_session_unregister('comments');



 push_redirect(push_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));

  require(DIR_WS_LIB . 'end.php');
?>
