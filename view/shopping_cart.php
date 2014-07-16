<?php
/*
  $Idä: shopping_cart.php,v 1.73 2003/06/09 23:03:56 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require("includes/ajax_top.php");
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
######################################################################

if (!push_session_is_registered('continueshopping')) {
	push_session_register('continueshopping');
	$continueshopping = push_href_link(FILENAME_DEFAULT);
}
if (sizeof($navigation->snapshot) > 0
		&& ($navigation->snapshot['page'] == 'index.php'
		|| $navigation->snapshot['page'] == 'products_info.php'
		|| $navigation->snapshot['page'] == 'sortiment.php')) {	
	$continueshopping = push_href_link($navigation->snapshot['page'], push_array_to_string($navigation->snapshot['get'], array(push_session_name())), $navigation->snapshot['mode']);
}

if(!$customer->login)  
{
	push_redirect(push_href_link(FILENAME_DEFAULT));
} 
// if no shipping destination address was selected, use the customers own address as default
  if (!push_session_is_registered('sendto')) {
    push_session_register('sendto');
    $sendto = $customer_shipping_address_id;
  } else {
  if($customer_shipping_address_id<>$sendto){
  	  $sendto = $customer_shipping_address_id;
  }
  
  // verify the selected shipping address
    $check_address_query = push_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$sendto . "'");
    $check_address = push_db_fetch_array($check_address_query);

    if ($check_address['total'] != '1') {
      $sendto = $customer_default_address_id;
      if (push_session_is_registered('shipping')) push_session_unregister('shipping');
    }
  }

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  if (!push_session_is_registered('cartID')) push_session_register('cartID');
  $cartID = $cart->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
   if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') ) { // Edited for CCGV
    if (!push_session_is_registered('shipping')) push_session_register('shipping');
    $shipping = false;
    $sendto = false;
  }

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
 
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;

 if (!push_session_is_registered('shipping')) push_session_register('shipping');

    if ( (push_count_shipping_modules() > 0) || ($free_shipping == true) ) {
      if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
        $shipping = $_POST['shipping'];
        list($module, $method) = explode('_', $shipping);
        if ( is_object($$module) || ($shipping == 'free_free') ) {
          if ($shipping == 'free_free') {
            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
            $quote[0]['methods'][0]['cost'] = '0';
          } else {
            $quote = $shipping_modules->quote($method, $module);
          }
          if (isset($quote['error'])) {
            push_session_unregister('shipping');
          } else {
            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
              $shipping = array('id' => $shipping,
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);

  //            push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
            }
          }
        } else {
          push_session_unregister('shipping');
        }
      }
    } else {
    
      $shipping = false;

    //  push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
    }
if(isset($_GET['action']) && $_GET['action']== 'removebonus')
{
	
	$_SESSION['bonusoff'][]=$_GET['pid'];
	push_redirect(push_href_link(FILENAME_SHOPPING_CART,'','SSL'));
    break;
}

// get all available shipping quotes
  $quotes = $shipping_modules->quote();
// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
	if ( !push_session_is_registered('shipping') || ( push_session_is_registered('shipping') && ($shipping == false) && (push_count_shipping_modules() > 1) ) )
	{
		$shipping = $shipping_modules->cheapest();
	}
	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);


//PAYMENT SEITE

// if we have been here before and are coming back get rid of the credit covers variable
	if(push_session_is_registered('credit_covers')) push_session_unregister('credit_covers');  //CCGV

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (push_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        push_redirect(push_href_link(FILENAME_SHOPPING_CART,'','SSL'));
        break;
      }
    }
  }

// if no billing destination address was selected, use the customers own address as default
  if (!push_session_is_registered('billto')) {
    push_session_register('billto');
    $billto = $customer_bill_address_id;
  } else {
  if($billto <> $customer_bill_address_id)
  	$billto = $customer_bill_address_id;
// verify the selected billing address
    $check_address_query = push_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$billto . "'");
    $check_address = push_db_fetch_array($check_address_query);

    if ($check_address['total'] != '1') {
      $billto = $customer_default_address_id;
      if (push_session_is_registered('payment')) push_session_unregister('payment');
    }
  }
 require(DIR_WS_CLASSES . 'order_total.php');// CCGV
  $order_total_modules = new order_total;// CCGV
  
   if (!push_session_is_registered('comments'))
   {
    push_session_register('comments');
	}
	
// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

  if (!push_session_is_registered('comments')) push_session_register('comments');
  if (push_not_null($_POST['comments'])) {
    $comments = push_db_prepare_input($_POST['comments']);
  }



  $payment_modules->update_status();
  $order_total_modules->collect_posts();// CCGV
  $order_total_modules->pre_confirmation_check();//  CCGV

// Line edited for CCGV
//  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
  if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!$credit_covers) ) {
    push_redirect(push_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }


  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (push_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      push_redirect(push_href_link(FILENAME_SHOPPING_CART,'','SSL'));
    }
  }

######################################################################
$breadcrumb->add(NAVBAR_TITLE, push_href_link(FILENAME_SHOPPING_CART,'','SSL'));
require(DIR_WS_BOXES . 'html_header.php');
?>
<!-- body_text //-->
<?php //echo push_draw_form('cart_quantity', push_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?> 
<?php
// TEST MIN_ORDER_AMOUNT

$hinweis="";
$minorderamount=false;
if ((isset($tcart)) && (defined('ONSH') && $tcart->show_total() < MIN_ORDER_AMOUNT) ||((isset($cart)) && !defined('ONSH') && $cart->show_total() < MIN_ORDER_AMOUNT) ) {
	$minorderamount=true;
	$hinweis='   ' . sprintf(TEXT_ORDER_UNDER_MIN_AMOUNT, $currencies->format(MIN_ORDER_AMOUNT)) . "<br>";
}


require(DIR_WS_BOXES . 'checkout_head.php');
//freeproductscode (GROUPON)
if(isset($fpd)){
?>
	<div class="grid_16" style="color:red;padding:5px;">
 		Folgende Gutscheincodes wurden freigegeben: 
<?php 
		echo $fpd;
 		push_session_unregister('fpd');
 		unset($fpd);
?>
	</div>
<?php 
}      //EOF freeproductscode

if($hinweis<>"")
{
	echo '	<div class="grid_16"  id="hinweis"  style="margin-bottom:20px;color:#ed7f00;padding:5px;font-weight:bold">';
	echo "	<span style='font-size:16px;color:#ed7f00;font-family:Times,serif;'>&#10132;</span>  " . $hinweis;
	echo '	</div>';
}
//separate the warnings???
if (isset($error_stack) && $error_stack->message_set())
{
?>
	<div class="grid_16 alpha omega headerError" style=""><?php echo $error_stack->display(); ?></div>
<?php
}
?>

<div class="grid_1 tx_12_15 tx_nobr"><h3 class="tx_light_gray">Artikel</h3></div>
<div class="grid_5 tx_12_15"><h3 class="tx_light_gray">&nbsp;</h3></div>
<div class="grid_3 tx_12_15"><h3 class="tx_light_gray">Preis/Einheit</h3></div>
<div class="grid_3 tx_12_15"><h3 class="tx_light_gray">Anzahl</h3></div>
<div class="grid_1 tx_12_15"><h3 class="tx_light_gray">&nbsp;</h3></div>
<div class="grid_3 tx_12_15 tx_right"><h3 class="tx_light_gray">Summe</h3></div>

<div class="grid_16" style="border-bottom:1px solid #ccc;margin-bottom:10px;margin-top:5px;"></div>

<?php
$cartEmpty = !(isset($cart) && $discount->overall_amount > 0);

if ($cartEmpty) {
?>
	<div class="grid_16" style="margin-top: 10px; margin-bottom: 5px">
		<a class="button w90 darkblue tx_white tx_12_15" style="border: 1px solid #4195D5; padding-left: 30px; display: inline-block; position: relative; top: -5px" title="Zum Shop" href="<?= push_href_link(FILENAME_DEFAULT) ?>">
			<img src="images/push/icons/ico_arrow-rw_S-double_white.png" style="position: absolute; left: 11px; top: 12px" /> Zum Shop
		</a>
		<div class="tx_30_40" style="display: inline-block; margin-left: 120px">Schade. Ihr Warenkorb ist noch leer.</div>
	</div>
<?php
} else {
	// cart content

	function subval_sort($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
	}
	
	$any_out_of_stock = 0;
	$products = $cart->get_products();
	// ksort($products[]);
	 $products = subval_sort($products,'id'); 
	for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		if($i < 0)
		{
			break;
		}
		$p->load_product($products[$i]['id']);
		if($current_id <> $p->id)
		{
			echo '<div class="grid_16" id="cart_' . $intid . '" style="' . ($i > 0 ? 'border-top:1px dotted #ccc;' : '') . 'padding-bottom:15px;padding-top:15px">';
		}
		else
		{
			echo '<div class="grid_16" id="cart_' . $intid . '" style="border-bottom:none;padding-bottom:15px;padding-top:15px">';
		}
		
	$current_id = $p->id;
	echo '<div class="grid_1 alpha" ><a href="' . push_href_link(DIR_WS_IMAGES .  $p->get_image() ) . '" class="bkrpop" style="text-align:center;"><img src="' . DIR_WS_IMAGES  . $p->get_image('cart', 40) . '" ></a></div>' ;
	#### uncomment for stockcheck
	/*
	if (STOCK_CHECK == 'true') {
			$stock_check = push_check_stock($products[$i]['id'], $products[$i]['quantity']);
			if (push_not_null($stock_check)) {
				$any_out_of_stock = 1;
				echo $stock_check;
			}
		}
	*/
	####
	echo '<div class="grid_5"><h3 style="padding-bottom: 3px"><a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id) . '" class="tx_15_20 tx_blue">' . $p->products_name . '&nbsp;</a></h3><span class="tx_12_15 tx_light_gray">' . $p->manufacturers_name . '</span><br /><span class="tx_12_15 tx_light_gray">Artikel-Nr. ' . $p->products_model . '</span><br />';
	echo "<a href='" . push_href_link(basename($PHP_SELF), push_get_all_get_params(array('action')) . 'action=removeproduct&pid=' . (($p->ve_loaded)? $p->ve_id : $p->id) , 'NONSSL') . "' class='tx_12_15' style='display: block; margin-top: 7px'><img src='images/push/icons/btn-action_close_S.png' style='vertical-align: middle; margin-right: 3px' /> Aus Warenkorb löschen</a>" .'</div>'; 
		if($p->ve_loaded)
		{
			$max = $p->ve_left + $products[$i]['quantity'];
		}
		else
		{
				if($p->has_ve && ($p->ve_multiplier < $p->display_quantity))
				{
					$max = ($p->ve_multiplier -1);
				}
				else
				{
					$max = intval($p->display_quantity) ;
				}
		}
	echo '<div class="grid_3 tx_13_20">' . $currencies->format(push_add_tax(($p->ve_loaded) ? $p->ve_end_price : $p->final_price, $p->tax)) . ' / ' . ( ($p->ve_loaded)? 'VE' : 'Stück') . ' </div>';
	echo '<div class="grid_2 cartpricebox unselectable"><b>-</b><input type="text" data-max="' . $max . '" id="quantity' . $p->loaded_id . '" name="cart_quantity" maxlength="3" size="2" value ="' . $products[$i]['quantity'] . '"><b>+</b>' .  push_draw_hidden_field('products_id', $p->loaded_id) . '</div>'; 
	
	echo '<div class="grid_3">';
		if($p->has_ve && !($p->ve_loaded) && !($cart->in_cart($p->ve_id)))
		{
			echo "<a href='" . push_href_link(basename($PHP_SELF), push_get_all_get_params(array('action')) . 'action=addtocart&pid=' . $p->ve_id , 'NONSSL') . "'  style='height:17px;padding:8px 5px 5px 40px; width:115px;border-radius:2px;display:inline-block; position: relative' class='darkblue tx_12_15 tx_white'><img src='images/push/icons/ico_cart-ve_white.png' style='position: absolute; left: 10px; top: 7px' />VE hinzufügen</a>";
		}
		elseif(($p->ve_loaded) && !($cart->in_cart($p->id)))
		{
			echo "<a href='" . push_href_link(basename($PHP_SELF), push_get_all_get_params(array('action')) . 'action=addtocart&pid=' . $p->id , 'NONSSL') . "'  style='height:17px;padding:8px 5px 5px 40px; width:115px;border-radius:2px;display:inline-block;position: relative' class='darkblue tx_12_15 tx_white'><img src='images/push/icons/ico_cart-pcs_white.png' style='position: absolute; left: 10px; top: 7px' />Stück hinzufügen</a>";
		}
		else
		{
			echo "&nbsp;";
		}
	echo '</div>';
	
	$preis = $currencies->display_price(  ($p->ve_loaded)?$p->ve_end_price : $p->final_price, push_get_tax_rate($p->tax_class_id), $products[$i]['quantity'] );
	echo '<div class="grid_2 omega tx_blue tx_15_20 tx_bold tx_right"> ' . $preis . ' </div>';
	
		?>
	</div>
	<div class="clearfix"></div>
	<?php
	 }
}
?>

<?php
if(count($discount->free_product_ids) > 0)
{
	foreach($discount->free_product_ids as $fpid)
	{
			$product->load_product($fpid);
			echo '<div class="grid_16" id="cart_' . $intid . '" style="' . ($i > 0 ? 'border-top:1px dotted #ccc;' : '') . 'padding-bottom:15px;padding-top:15px">';
			
			echo '<div class="grid_1 alpha" style="text-align:center;"><a href="' 
			. push_href_link(DIR_WS_IMAGES .  $product->get_image() ) 
			. '" class="bkrpop" style="text-align:center;"><img src="' 
			. DIR_WS_IMAGES  . $product->get_image('cart', 40) . '" ></a><img src="images/assets/ico_bonus-present_pink_L.png" style=""></div>' ;
			
			echo '<div class="grid_5"><h3 style="padding-bottom: 3px"><a href="' 
			. push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product->products_id) 
			. '" class="tx_15_20 tx_blue">' 
			. $product->products_name 
			. '&nbsp;</a></h3><span class="tx_12_15 tx_light_gray">' 
			. $product->manufacturers_name 
			. '</span><br /><span class="tx_12_15 tx_light_gray">Artikel-Nr. ' 
			. $product->products_model 
			. '</span><br /><span class="tx_12_15 tx_pink">Sie erhalten diesen Artikel als Bonus gratis '
			. '</span><br />';
			
			echo "<a href='" 
			. push_href_link(basename($PHP_SELF), push_get_all_get_params(array('action')) 
			. 'action=removebonus&pid=' . (($product->ve_loaded)? $product->ve_id : $product->id) , 'NONSSL') 
			. "' class='tx_12_15' style='display: block; margin-top: 7px'><img src='images/push/icons/btn-action_close_S.png' style='vertical-align: middle; margin-right: 3px' /> Aus Warenkorb löschen</a>" .'</div>';
			
			echo '<div class="grid_3 tx_13_20">' . $currencies->format(push_add_tax(($product->ve_loaded) ? $product->ve_end_price : $product->final_price, $product->tax)) . ' / ' . ( ($product->ve_loaded)? 'VE' : 'Stück') . ' </div>';
		//	echo '<div class="grid_2 cartpricebox unselectable"><b>-</b><input type="text" data-max="' . $max . '" id="quantity' . $product->loaded_id . '" name="cart_quantity" maxlength="3" size="2" value ="' . $products[$i]['quantity'] . '"><b>+</b>' .  push_draw_hidden_field('products_id', $product->loaded_id) . '</div>'; 
			echo '<div class="grid_2 cartpricebox unselectable">&nbsp;</div>';
			echo '<div class="grid_3">';
			echo "&nbsp;";
			echo '</div>';
			echo '<div class="grid_2 omega tx_blue tx_15_20 tx_bold tx_right"> 0,00 EUR </div>';
	
		?>
	</div>
	<div class="clearfix"></div>
	<?php
		
	}
}
?>

<div class="grid_16" style="height:10px;border-bottom:1px solid #ccc;margin-bottom:15px;" ></div>
	<div class="prefix_6 grid_4 bx_left tx_13_20">Gesamtbestellwert netto</div><div class="grid_6 tx_right tx_15_20 tx_blue"><?= $currencies->display_price($discount->overall_amount,1) ?></div>
	<div class="clearfix"></div>
	
	<div class="prefix_6 grid_4 bx_left tx_13_20">Versandkosten<img src="images/push/icons/ico_lkw_S.png" style="margin-left: 5px; vertical-align: middle" /></div><div class="grid_6 tx_right tx_15_20 tx_blue"><?= $cartEmpty ? '0,00 EUR' : $currencies->format($discount->shipping_cost,1) ?></div>
	<div class="clearfix"></div>
	
	<?php
	/*
			$taxes = new ot_tax;
			$taxes->process();
			foreach($taxes->output as $nclass)
			{
				echo '<div class="prefix_6 grid_4  bx_left tx_13_20">' .$nclass['title']."</div>";
				echo '<div class="grid_2 suffix_4 bx_left tx_13_20">' .$nclass['text'] ."</div>";
				//echo $nclass['value']."|";
				echo '<div class="clearfix"></div>';
			}
			/**/
	?>
	
	<?php	 if ($cartEmpty) { ?>
				<div class="prefix_6 grid_4 tx_darkpink tx_13_20">Ersparnisse:</div><div class="grid_6 tx_left tx_13_20 tx_darkpink">0,00 EUR</div>
	<?php 	} else {
				if($discount->discount_reached=true)
				{
				?>
					<div class="prefix_6 grid_10 tx_darkpink tx_13_20">Ersparnisse</div>
					<div class="clearfix"></div>
				<?php
					if($discount->shipping_savings > 0)
					{
				?>
					<div class="prefix_6 grid_5 bx_left tx_darkpink tx_13_20"><img style="vertical-align: middle; margin-right: 5px" src="images/assets/ico_discount_true-s.png">Versandkosten </div><div class="grid_2 suffix_3 tx_darkpink bx_left tx_13_20"><?=  $currencies->format($discount->shipping_savings,1)?></div>
					<div class="clearfix"></div>
					
				<?php
					}
					if($discount->custom_discount  && $discount->private_rabatt_amount > 0 )
					{
				?>
						<div class="prefix_6 grid_4 bx_left tx_darkpink tx_13_20">
						<img style="vertical-align: middle; margin-right: 5px" src="images/assets/ico_discount_individual.png">Individueller Kundenbonus</div>
						<div class="clearfix"></div>
						<div class="prefix_6 grid_6 bx_left tx_darkpink tx_13_20"><img style="vertical-align: middle;margin-left:34px; margin-right: 5px" src="images/assets/ico_discount_true-s.png"><?php echo $discount->private_rabatt_procent; ?>% Rabatt auf <?php echo $currencies->format($discount->private_rabatt_amount,1); ?></div><div class="grid_4 tx_right tx_13_20 tx_darkpink"><?php echo $currencies->format( (-1 * $discount->private_rabatt_saving), 1) ?></div>
						<div class="clearfix"></div>
				<?php
					}
				
					if($discount->general_savings > 0) 
					{
				?>
						<div class="prefix_6 grid_4 bx_left tx_darkpink tx_13_20"><img style="vertical-align: middle; margin-right: 5px" src="images/assets/ico_discount_true-s.png"><?php echo $discount->actual_discount_procent; ?>% Rabatt auf <?php echo $currencies->format($discount->discount_amount,1); ?> </div><div class="grid_6 tx_right tx_13_20 tx_darkpink"><?php echo $currencies->format( (-1 * $discount->general_savings), 1) ?></div>
						<div class="clearfix"></div>
				<?php
					}
					//BOF DISCOUNTS
					if($discount->coupon['is']) 
					{
				?>
						<div class="prefix_6 grid_4 bx_left tx_darkpink tx_13_20"><img style="vertical-align: middle; margin-right: 5px" src="images/assets/ico_discount_true-s.png"><?php echo $discount->coupon['name']; ?> Gutschein <?= $discount->coupon['value'] ?></div><div class="grid_6 tx_right tx_13_20 tx_darkpink"><?php echo $currencies->format( (-1 * $discount->coupon['reduction']), 1) ?></div>
						<div class="clearfix"></div>
				<?php
					}
					//EOF DISCOUNTS

					if($discount->online_rabatt > 0)
					{
				?>
						<div class="prefix_6 grid_4 bx_left tx_darkpink tx_13_20"><img style="vertical-align: middle; margin-right: 5px" src="images/assets/ico_discount_true-s.png"><?php echo ONLINE_RABATT_PROCENT; ?>% Onlinerabatt </div><div class="grid_6 tx_right tx_13_20 tx_darkpink"><?php echo $currencies->format( (-1 * $discount->online_rabatt), 1) ?></div>
						<div class="clearfix"></div>
				<?php
					}
					if(count($discount->free_product_ids) > 0)
					{
						?><!--<div class="prefix_6 grid_4 bx_left tx_darkpink tx_13_20" style="margin-top:10px;font-weight:bold;">Gratis:</div><div class="clearfix"></div>--><?php
						$gp = new product;
						foreach($discount->free_product_ids as $gratis)
						{
							$gp->load_product($gratis);
							?>
							<div class="prefix_6 grid_5 bx_left tx_darkpink tx_13_20" ><img style="vertical-align: middle; margin-right: 5px" src="images/assets/ico_discount_true-s.png">1 x Bonusartikel "<?php echo $gp->products_name ?>"</div><div class="grid_2 suffix_3 tx_darkpink bx_left tx_13_20"><?=   $currencies->format(push_add_tax(($gp->ve_loaded) ? $gp->ve_end_price : $gp->final_price, $gp->tax)) ?></div>
							<div class="clearfix"></div>
							<?php
						}
					}
				}
			}
		?>
		<div class="clearfix"></div>
	<?php
		if($discount->need_more  &&(!$discount->custom_discount)){
		$discount->get_next_discount_string();
		if($discount->overall_amount < $discount->free_shipping_amount)
		{
			$savenext = $discount->shipping_cost;
		}
		else
		{
			$savenext = $discount->next_discount_step / 100 * $discount->next_discount_procent;
		}
		?>
		<div id="booster" class="grid_16" style="position: relative; margin-top: 15px; margin-bottom: 15px">
			<div class="prefix_2 grid_4 tx_25_30" style="color:#444444;margin-top:16px;">Mein<br />Rabatt-Booster</div>
			<div class="grid_6 tx_darkpink tx_13_20" style="margin-top:16px;"><img src="images/push/icons/ico_false_s-pink.png" style="margin-right: 5px; vertical-align: middle" />Noch  <?php echo $discount->next_difference_string; ?> bis <?php echo $discount->next_spush_identifier; ?><br />
				<a href = "<?php echo $continueshopping; ?>" class="black_button_gradient" style="position: relative">Weiter einkaufen und sparen <img src="images/push/icons/ico_arrow-fw_S-double_white.png" style="position: absolute; right: 11px; top: 11px" /></a>
			</div>
			<?php if (!$cartEmpty) { ?>
					<div class="grid_4  bx_right tx_13_20 alpha omega" style="margin-top:16px;color:#ccc; float:right;"><?php echo $currencies->format( (-1 * $savenext), 1) ?></div>
			<?php } ?>
			<img src="images/push/icons/btn-action_close_L.png" style="position: absolute; top: 0; left: 0" onclick="$('#booster').slideUp();" />	
		</div>
		<div class="clearfix"></div>
		<?php
		}
	?>	<div class="grid_10 bottom_border" style="margin-left:370px;margin-bottom:10px;">&nbsp;&nbsp;</div> 
		<div class="clearfix"></div>
		<div class="prefix_6 grid_4 bx_left tx_13_20 tx_bold">Gesamtbetrag netto</div><div class="grid_6 tx_right tx_15_20 tx_blue"><?= $cartEmpty ? '0,00 EUR' : $currencies->display_price($discount->actual_discount_price + $discount->shipping_cost , 0) ?></div>
		<div class="clearfix"></div>
	<?php	if (!$cartEmpty) { 
				$taxes = new ot_tax;
				$taxes->process();
				foreach($taxes->output as $nclass)
				{
					echo '<div class="prefix_6 grid_3  bx_left tx_13_20"><span class="tx_left">' .$nclass['title']. ' ' .$nclass['text'] . "</span></div>";
					echo '<div class="grid_3 suffix_4 bx_left tx_13_20">' . "</div>";
		
					echo '<div class="clearfix"></div>';
				}
			} ?>
			
	<div class="grid_16" style="height:35px;border-bottom:1px solid #ccc;margin-bottom:20px;"></div> 
	
	<?php
	if ($cartEmpty) { ?>
	
		<div class="grid_16" style="margin-top: 10px; margin-bottom: 5px">
			<a class="button w90 darkblue tx_white tx_12_15" style="border: 1px solid #4195D5; padding-left: 30px; display: inline-block; position: relative; top: -5px" title="Zum Shop" href="<?= push_href_link(FILENAME_DEFAULT) ?>">
				<img src="images/push/icons/ico_arrow-rw_S-double_white.png" style="position: absolute; left: 11px; top: 12px" /> Zum Shop
			</a>
		</div>			
<?php
	} else {
		$customer =new Customer($_SESSION['customer_id']);
		?>
		<div class="grid_6 tx_left"><a href = "<?php echo $continueshopping; ?>" class="gradientgrey button w90 tx_12_15" style="padding-left: 30px;"><img src="images/push/icons/ico_arrow-rw_S-double.png" style="position: absolute; left: 11px; top: 12px" /> Zum Shop</a></div>
		<?php
		if($customer->customer_checkout_enabled == 1 && ( ($discount->actual_discount_price + $discount->shipping_cost ) < $customer->customer_credit_amount ))
		{
		?>
		<div class="grid_10"><a href = "<?php echo push_href_link(FILENAME_CHECKOUT_CONFIRMATION,'','SSL'); ?>" class="button w170 darkblue tx_white tx_12_15" style="position: absolute; right: 10px; border: 1px solid #4195D5;"> Weiter zu Rechnung und Versand <img style="position: absolute; right: 11px; top: 12px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a></div>
		<?php
		}
		elseif($customer->customer_checkout_enabled == 1)
		{
		?>
		<div class="grid_10 omega tx_12_15 tx_red" style="border: 1px solid #cccccc; padding: 10px; width: 560px">Lieber Kunde, Ihr Kreditrahmen ist leider überschritten. <br />
			Um die Bestellung abschliessen zu können, reduzieren Sie bitte den Gesamtbetrag Ihres Warenkorbes um <?php 
		$txt = (($discount->actual_discount_price + $discount->shipping_cost ) - $customer->customer_credit_amount );
		echo $currencies->format((($discount->actual_discount_price + $discount->shipping_cost ) - $customer->customer_credit_amount ),'');
		if($_SESSION['customer_id'] <> 36767)
		{
			mail('service@if-bi.com', 'KREDITALERT' , 'Kunde ' . $_SESSION['customer_id'] . ' ---' . $customer->customers_firstname . ' ' . $customer->customers_lastname . '--- Warenkorb hat Kreditlimit um ' . $txt . " ueberschritten");
		}
		else
		{
			mail('andreas.lang@if-bi.com', 'KREDITALERT' , 'Kunde ' . $_SESSION['customer_id'] . ' ---' . $customer->customers_firstname . ' ' . $customer->customers_lastname . '--- Warenkorb hat Kreditlimit um ' . $txt . " ueberschritten");
		
		}
		?>. Vielen Dank.
		</div>
		<?php
		}
		else
		{
		?>
		<div class="grid_10 omega tx_12_15 tx_red" style="border: 1px solid #cccccc; padding: 10px; width: 560px">
			Lieber Interessent, um die Bestellung abschliessen zu können, vervollständigen Sie bitte Ihre Registrierung. Lassen Sie uns hierzu das <strong class="tx_red">Kundendatenblatt</strong> ausgefüllt zukommen. Nach Überprüfung Ihrer Angaben schalten wir Ihr Kundenkonto für den Onlinekauf frei. <br/>
			Ihr Warenkorb wird gespeichert.
			<div style="margin-left: 20px">
				<a class="tx_blue tx_12_15" target="_blank" style="display: block; margin: 20px 0; margin-right: 15px" title="Kundendatenblatt (PDF)" href="download/push_kundendatenblatt_DE_2013-03_form.pdf">
					<img style="vertical-align: middle; margin: -3px 5px 0 0" src="images/push/icons/ico_download.png">	Kundendatenblatt (PDF)
				</a>
				<a class="gradientgrey button w200 tx_12_15 tx_blue" style="disply: block; margin-bottom: 10px; height: 20px; line-height: 20px !important" href="<?= push_href_link(FILENAME_NEUKUNDENINFORMATION) ?>">
					Neukundeninformation <img style="position: absolute; right: 11px; top: 7px" src="images/push/icons/ico_arrow-fw_S-double_blue.png">
				</a>
				<a class="gradientgrey button w200 tx_12_15 tx_blue" style="height: 20px; line-height: 20px !important" href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>">
					Anmelden und Registrieren <img style="position: absolute; right: 11px; top: 7px" src="images/push/icons/ico_arrow-fw_S-double_blue.png">
				</a>
			</div>
		</div>
		<?php
		}
		
			if ($any_out_of_stock == 1) {
			  if (STOCK_ALLOW_CHECKOUT == 'true') {
		?>
				<p class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></p>
		<?php
			  } else {
		?>
				<p class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></p>
		<?php
			  }
			}
		?>
		<?php
		
			?>	</div>
		
		<script type="text/javascript">
		$(document).ready(function(){
			$('.cartpricebox b').addClass('gradientlight');
			
			$('.cartpricebox b').click(function(ev){
					var inpt = $(this).parent().children("input[type='text']");
					var hid = $(this).parent().children("input[type='hidden']");
					if($(this).text() == '+'  && (parseInt(inpt.val()) < inpt.attr("data-max")))
					{
						inpt.val( parseInt(inpt.val()) + 1)
						window.location.href="<?= push_href_link(basename($PHP_SELF), push_get_all_get_params(array('action')) . 'action=updateproduct', 'SSL')?>&pid=" + hid.val() + "&qty=" + inpt.val();
					}
					if($(this).text() == '-'  && parseInt(inpt.val()) >1)
					{
						inpt.val(parseInt(inpt.val())-1)
						window.location.href="<?= push_href_link(basename($PHP_SELF), push_get_all_get_params(array('action')) . 'action=updateproduct', 'SSL')?>&pid=" + hid.val() + "&qty=" + inpt.val();
					}
					
				});
			$('.cartpricebox b').mouseup(function(ev){
				var inpt = $(this).parent().children("input[type='text']");
			});
			
			$(".cartpricebox input[type='text']").focusout(function(ev){
				var maximum = parseInt($(this).attr("data-max"));
				if(parseInt($(this).val()) > maximum)
				{
					$(this).val(maximum);
				}
				if(isNaN(parseInt($(this).val())) )
				{
					$(this).val(1);
				}
			});
			
			$(".cartpricebox input[type='text']").keypress(function(e) {
			//13 maps to the enter key		
				var hid = $(this).parent().children("input[type='hidden']");
				if (e.keyCode == 13) {
					window.location.href="<?= push_href_link(basename($PHP_SELF), push_get_all_get_params(array('action')) . 'action=updateproduct', 'SSL')?>&pid=" + hid.val() + "&qty=" + $(this).val();
				}
			});
		
		});
		</script>
	
<?php
	}

//redirect to history - back?  --- not working!
/*        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = push_href_link($navigation->snapshot['page'], push_array_to_string($navigation->snapshot['get'], array(push_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          push_redirect($origin_href);
        } else {
			if(isset($_POST['redirectto']) && ($_POST['redirectto']<>'') ){
          	push_redirect(push_href_link($_POST['redirectto']));
			}else{
          push_redirect(push_href_link(FILENAME_DEFAULT));
		  }
        }*/
?>

        </form>
<!-- body_text_eof //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<?php require(DIR_WS_LIB . 'end.php'); ?>
