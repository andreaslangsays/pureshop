<?php
/*
  $Idä: checkout_confirmation.php,v 1.139 2003/06/11 17:34:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');
//error_reporting(E_ALL | E_STRICT);
// if the customer is not logged on, redirect them to the login page
  if (!push_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_CONFIRMATION));
    push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
 if ($cart->count_contents() < 1) {
  // push_redirect(push_href_link(FILENAME_SHOPPING_CART));
 }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && push_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
    }
  }
//THE BIG INSERTION!!

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
        push_redirect(push_href_link(FILENAME_SHOPPING_CART));
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
    push_redirect(push_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
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
      push_redirect(push_href_link(FILENAME_SHOPPING_CART));
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, push_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);

require(DIR_WS_BOXES . 'html_header.php');

?>
<!-- body_text //-->

<div class="container_16">
<?php
require(DIR_WS_BOXES . 'checkout_head.php');
?>
<?php
	if ($_GET['alert'] == 'agb')
	{
?>
<div class="grid_16 messageStackWarning"><?php echo CONDITION_AGREEMENT_ERROR; ?></div>
<?php
	}
	
if (isset($_GET['err']) && $_GET['err'] == 'agree') {
	echo '<div class="grid_16 tx_red tx_strong tx_13_20" style="margin-bottom: 30px">Bitte akzeptieren Sie unsere AGB.</div>';	
}

$form_action_url = FILENAME_CHECKOUT_PROCESS;
echo push_draw_form('confirmation', $form_action_url, 'post', 'onsubmit="return check_agree(this);"');
?>
	<div class="">
		<h5 class="grid_16 tx_25_30">Lieferadresse wählen:</h5><br />
		<div class="grid_10">
		<?php 		
				$addresses_query = push_db_query("SELECT address_book_id, 
													entry_firstname as firstname, 
													entry_lastname as lastname, 
													entry_company as company, 
													entry_street_address as street_address, 
													entry_suburb as suburb, 
													entry_city as city, 
													entry_postcode as postcode, 
													entry_state as state, 
													entry_zone_id as zone_id, 
													entry_country_id as country_id 
											FROM	" . TABLE_ADDRESS_BOOK . " 
											WHERE 	customers_id = '" . (int)$_SESSION['customer_id'] . "'");
									
			$a = 0;											
			while ($address = push_db_fetch_array($addresses_query)) { 
				$format_id = push_get_address_format_id($addresses['country_id']);
				if ($a % 2 == 0) {
		?>
					<div class="grid_10 alpha omega" style="margin-bottom: 20px">
		<?php 	} ?>
						<div class="grid_5 alpha omega tx_13_20"<?= $a % 2 == 1 ? ' style="width: 270px; padding-left: 9px; border-left: 1px dotted #bbbbbb"' : '' ?>>		
							<input type="radio" value="<?= $address['address_book_id'] ?>" name="shippingAddress" style="height: 20px" <?= $address['address_book_id'] == $customer->customers_shipping_address_id ? 'checked="checked"' : '' ?>/> An diese Adresse liefern: <br /><br />
							<?php echo push_address_format($format_id, $address, true, ' ', '<br />'); ?>
						</div>
		<?php 
				if ($a % 2 == 1) {
		?>
					</div>
		<?php 	}
				$a++;
			}
			
		$weekday=date(w,time());
		if( (time() < strtotime("today 16:00:00")) && !(($weekday == 0) || ($weekday == 6)) )
		{
		?>
			<div class="grid_10 alpha omega" style="margin-top: 20px">
				<div class="grid_1 alpha omega">
					<img src="images/push/icons/smilie_blue.png" />
				</div>
				<div class="grid_6 alpha omega tx_12_15 tx_light_gray">
					Sofern wir Ihre Ware mit einem <strong class="tx_12_15 tx_light_gray">Paketdienst</strong> versenden können, <strong class="tx_12_15 tx_light_gray">übergeben wir diese Bestellung noch heute</strong>.
				</div>
			</div>
		<?php
		}
		?>
		</div>

	</div>
	
		<div class="grid_6 omega blue-box tx_12_15 tx_light_gray" style="width: 318px; margin-bottom: 10px">
			<strong class="tx_light_gray">Hinweis zum Versand</strong><br />
			Je nach Art und Anzahl der von Ihnen bestellten Waren erhalten Sie von uns eine <br /><br />		
			Paketlieferung oder <br />
			Palettenlieferung<br /><br />
			Bitte haben Sie Verständnis dafür, dass wir hierzu im voraus keine verbindlichen Angaben machen können.
			Weitere Informationen zum Versand finden Sie hier:<br />
			<a class="tx_12_15 tx_blue" title="Versandkosten" href="<?= push_href_link(FILENAME_SHIPPING) ?>">Versandkosten</a>
		</div>
	<div class="grid_16 bottom_separator top_separator" style="margin: 20px 10px; padding: 20px 0 10px 0">
		<h5 class="tx_25_30"><?php echo TITLE_PAYMENT_ADDRESS; ?></h5><br />
    	<span class="tx_13_20"><?php echo push_address_label($_SESSION['customer_id'], $billto, true, ' ', '<br>'); ?></span>
	</div>
	
	<div class="grid_16 bottom_separator" style="margin: 10px 10px; padding: 10px 0 10px 0">
		<h5 class="tx_25_30">Warenkorb prüfen:</h5><br /><br />
		<div class="grid_1 alpha tx_12_15 tx_light_gray">Artikel</div>
		<div class="grid_5 tx_12_15 tx_light_gray">&nbsp;</div>
		<div class="grid_3 tx_12_15 tx_light_gray">Preis/ Einheit</div>
		<div class="grid_2 tx_12_15 tx_light_gray">Anzahl</div>
		<div class="grid_2 tx_12_15 tx_light_gray">Einheit</div>
		<div class="grid_3 omega tx_12_15 tx_light_gray tx_right">Summe</div>
	</div>
<?php
	$any_out_of_stock = 0;
	$products = $cart->get_products();
for ($i=0, $n=sizeof($products); $i<$n; $i++) {
	if($i < 0)
	{
		break;
	}
	$p->load_product($products[$i]['id']);
	
	echo '<div class="grid_16 bottom_border" style="padding: 15px 0">';
	echo '	<div class="grid_1 alpha"><a href="' . push_href_link(DIR_WS_IMAGES .  $p->get_image() ) . '" class="bkrpop" style="text-align:center;"><img src="' . DIR_WS_IMAGES  . $p->get_image('cart', 40) . '" ></a></div>' ; 
	echo '	<div class="grid_5"><a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id) . '" class="tx_15_20 tx_blue">' . $p->products_name . '</a><br /><span class="tx_12_15 tx_light_gray">' . $p->manufacturers_name . '</span><br /><span class="tx_12_15 tx_light_gray">Artikel-Nr. ' . $p->products_model . '</span></div>'; 
	echo '	<div class="grid_3 tx_13_20">' . ( ($p->ve_loaded)? $currencies->format($p->ve_end_price) : $currencies->format($p->final_price )) . ' / ' . ( ($p->ve_loaded)? 'VE' : 'Stück') . '</div>';
	echo '	<div class="grid_2 tx_13_20">' . $products[$i]['quantity'] . '</div>'; 
	echo '	<div class="grid_2 tx_13_20">' . ( ($p->ve_loaded)? 'VE' : 'Stück') . ' </div>';
	
	$preis = $currencies->display_price(  ($p->ve_loaded)?$p->ve_end_price : $p->final_price, push_get_tax_rate($p->tax_class_id), $products[$i]['quantity'] );
	echo '	<div class="grid_3 omega tx_blue tx_right"> ' . $preis .' </div>';
	echo '</div>';

  }
	?>
	<?php
if( count($discount->free_product_ids) > 0 )
{
	foreach($discount->free_product_ids as $fpid)
	{
			$product->load_product($fpid);
			echo '<div class="grid_16" id="cart_' . $intid . '" style="' . ($i > 0 ? 'border-bottom:1px dotted #ccc;' : '') . 'padding-bottom:15px;padding-top:15px">';
			
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
			. '</span></div>';
			
/*			echo "<a href='" 
			. push_href_link(basename($PHP_SELF), push_get_all_get_params(array('action')) 
			. 'action=removebonus&pid=' . (($product->ve_loaded)? $product->ve_id : $product->id) , 'NONSSL') 
			. "' class='tx_12_15' style='display: block; margin-top: 7px'><img src='images/push/icons/btn-action_close_S.png' style='vertical-align: middle; margin-right: 3px' /> Aus Warenkorb löschen</a>" .'</div>';
*/		
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

<?php
  /*/
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    echo $order_total_modules->output();
  }
  /**/
?>
<div class="clearfix" style="margin: 10px"></div>
<?php
	if($discount->overall_amount > 0)
	{
	?>
	
	<div class="prefix_6 grid_4 bx_left tx_13_20">Gesamtbestellwert netto</div><div class="grid_6 tx_right tx_15_20 tx_blue"><?= $currencies->display_price($discount->overall_amount,1)?></div>
	<div class="clearfix"></div>

<?php
if($discount->shipping_cost >= 0){
	?>
	<div class="prefix_6 grid_4 bx_left tx_13_20">Versandkosten<img src="images/push/icons/ico_lkw_S.png" style="vertical-align: middle; margin-left: 5px" /></div><div class="grid_6 tx_right tx_15_20 tx_blue"><?=  $currencies->format($discount->shipping_cost,1)?></div>
	<div class="clearfix"></div>
	<?php
	}
?>

<?php
	if($discount->discount_reached=true)
	{
	?>
		<div class="prefix_6 grid_10 tx_darkpink tx_13_20">Ersparnisse</div>
		<div class="clearfix"></div>
	<?php
		if($discount->shipping_savings > 0)
		{
	?>
		<div class="prefix_6 grid_5 bx_left tx_darkpink tx_13_20"><img src="images/push/icons/ico_discount_true-s.png" style="vertical-align: middle; margin-right: 5px" />Versandkosten</div><div class="grid_2 suffix_3 tx_darkpink bx_left tx_13_20 tx_right tx_stroke"><?=  $currencies->format($discount->shipping_savings,1)?></div>
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
			<div class="prefix_6 grid_4 bx_left tx_darkpink tx_13_20"><img src="images/push/icons/ico_discount_true-s.png" style="vertical-align: middle; margin-right: 5px" /><?php echo $discount->actual_discount_procent; ?>% Rabatt auf <?php echo $currencies->format($discount->discount_amount,1); ?> </div><div class="grid_6 tx_right tx_13_20 tx_darkpink"><?php echo $currencies->format( (-1 * $discount->general_savings), 1) ?></div>
			<div class="clearfix"></div>
	<?php
		}
		//BOF DISCOUNTS
		if(false && $discount->coupon['is']) 
		{
		?>
			<div class="prefix_6 grid_4 bx_left tx_darkpink tx_13_20"><img src="images/push/icons/ico_discount_true-s.png" style="vertical-align: middle; margin-right: 5px" /><?php echo $discount->coupon['name']; ?> Gutschein <?= $discount->coupon['value'] ?></div><div class="grid_6 tx_right tx_13_20 tx_darkpink"><?php echo $currencies->format( (-1 * $discount->coupon['reduction']), 1) ?></div>
		<div class="clearfix"></div>
		<?php
		}
		//EOF DISCOUNTS
		if($discount->online_rabatt > 0)
		{
	?>
			<div class="prefix_6 grid_4 bx_left tx_darkpink tx_13_20"><img src="images/push/icons/ico_discount_true-s.png" style="vertical-align: middle; margin-right: 5px" /><?php echo ONLINE_RABATT_PROCENT; ?>% Onlinerabatt </div><div class="grid_6 tx_right tx_13_20 tx_darkpink"><?php echo $currencies->format( (-1 * $discount->online_rabatt), 1) ?></div>
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
				<div class="prefix_6 grid_5 bx_left tx_darkpink tx_13_20" ><img style="vertical-align: middle; margin-right: 5px" src="images/assets/ico_discount_true-s.png">1 x Bonusartikel "<?php echo $gp->products_name ?>"</div><div class="grid_2 suffix_3 tx_darkpink bx_left tx_13_20 tx_right tx_stroke"><?=   $currencies->format(push_add_tax(($gp->ve_loaded) ? $gp->ve_end_price : $gp->final_price, $gp->tax)) ?></div>
				<div class="clearfix"></div>
				<?php
	
			}
		}
	}
	}
	?>
	<div class="clearfix"></div>
	<div class="prefix_6 grid_10 coupon_form" style="">
<?php
	//echo $order_total_modules->credit_selection();// CCGV
	if($discount->coupon['is'])
	{
?>
	<div style="position:relative;overflow:hidden"><span class="tx_15_20" style="display: block; margin-bottom: 5px">Gutschein einlösen</span><input class="gradientgrey" type="text" name="gv_redeem_code" value="<?=$discount->coupon['name']?>"> &nbsp;<img src="images/assets/ico_true_check_green.png" > <span class="tx_darkpink tx_13_20 tx_right"><?= $currencies->format( -1 *$discount->coupon['reduction'])?></span><br><span class="tx_lightgrey tx_12_15" >Hier Gutscheincode eingeben. Der Gegenwert wird mit Ihrer Bestellung verrechnet.<br>
(Es kann nur 1 Gutschein je Bestellung eingelöst werden)</span></div>	
<?php
	}
	else
	{
	?>
	<div style="position:relative;overflow:hidden"><span class="tx_15_20" style="display: block; margin-bottom: 5px">Gutschein einlösen</span><input type="text" name="gv_redeem_code"><input class="gradientgrey" type="submit" name="redeem" value="Einlösen" ><br><span class="tx_lightgrey tx_12_15" >Hier Gutscheincode eingeben. Der Gegenwert wird mit Ihrer Bestellung verrechnet.<br>
(Es kann nur 1 Gutschein je Bestellung eingelöst werden)</span></div>
<?php	
	}
?>
	</div>
<?php
	if(isset($_GET['error_message']))
	{
		echo '<div class="tx_red prefix_6 grid_10" style="margin-top: 5px"> ' .$_GET['error_message'] . '</div>
				<div class="clearfix"></div>';
	}
	if(isset($targett) && $discount->need_more){
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
	<div id="booster" class="grid_16 alpha omega">
		<div class="prefix_2 grid_4 tx_25_30" style="color:#444444;margin-top:16px;">Mein<br />Rabatt-Booster</div>
		<div class="grid_5 tx_darkpink tx_13_20" style="margin-top:16px;">Noch  <?php echo $discount->next_difference_string; ?> bis <?php echo $discount->next_spush_identifier; ?><br />
		<a href = "<?php echo $continueshopping; ?>" class="black_button_gradient">Weiter einkaufen und sparen</a></div>
		<div class="grid_5  bx_right tx_13_20 alpha omega" style="margin-top:16px;color:#ccc; float:right;margin-right:-10px;"><?php echo $currencies->format( (-1 * $savenext), 1) ?></div>
	</div>
	<div class="clearfix"></div>
	<?php
	}
?>	<div class="grid_10 bottom_border" style="margin-left:370px;margin-bottom:20px;">&nbsp;&nbsp;</div>
	<div class="clearfix"></div>
	<div class="prefix_6 grid_4 bx_left tx_13_20 tx_bold">Gesamtbetrag netto</div><div class="grid_6 tx_right tx_30_40 tx_blue"><?= $currencies->display_price($discount->actual_discount_price + $discount->shipping_cost , 0)?></div>
	<div class="clearfix"></div>
<?php
		$taxes = new ot_tax;
		$taxes->process();
		$pri=0;
		foreach($taxes->output as $nclass)
		{
			$_SESSION['push_tax_'][$pri]['class'] = $nclass['class'];
			$_SESSION['push_tax_'][$pri]['title'] = $nclass['title'];
			$_SESSION['push_tax_'][$pri]['text'] = $nclass['text'];
			$_SESSION['push_tax_'][$pri]['value'] = $nclass['value'];
			echo '<div class="prefix_6 grid_3  bx_left tx_13_20"><span class="tx_left">' .$nclass['title']. ' ' .$nclass['text'] . "</span></div>";
			echo '<div class="grid_3 suffix_4 bx_left tx_13_20">' . "</div>";
			//echo $nclass['value']."|";
			echo '<div class="clearfix"></div>';
			$pri ++;
		}
	echo "<!--";
	var_dump($_SESSION['push_tax_']);
	echo "-->";
	if (push_session_is_registered('couponCode')) {
		$_POST['gv_redeem_code'] = $couponCode;
		$gv = new ot_coupon;
		$gv->process();

		foreach($gv->output as $nclass)
		{
		echo '<div class="prefix_6 grid_3  bx_left tx_13_20"><span class="tx_left">' .$nclass['title']. ' ' .$nclass['text'] . "</span></div>";
			echo '<div class="clearfix"></div>';
		}
	}
?>

<div class="grid_16" style="height:20px;border-bottom:1px solid #ccc;margin-bottom:20px;"></div>
<div class="grid_16 tx_13_20" >
	<h5 class="tx_25_30">AGB</h5><br />
<?php
/*
<div class="grid_8 tx_left"><a href = "<?php echo $continueshopping; ?>" class="gradientgrey" style="padding:5px;border:1px solid #ccc;"> Zum Shop</a></div>
<div class="grid_8 tx_right"><a href = "<?php echo push_href_link(FILENAME_CHECKOUT_CONFIRMATION); ?>" class="gradientblue" style="padding:5px;border:1px solid #ccc;"> Weiter zu Rechnung und Versand </a></div>
*/
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

    ?>
<?php

 //  echo push_draw_form('confirmation', $form_action_url, 'post', 'onsubmit="return check_agree(this);" class="tx_13_20"');
?> 
      
		<?php echo push_draw_checkbox_field('agree', 'true', false, 'style="height: auto; margin-right: 5px"'); ?> Ich habe die <a class="tx_12_15 tx_blue" title="<?php echo CONDITIONS; ?>" href="<?php echo push_href_link(FILENAME_CONDITIONS); ?>" target="_blank">AGB</a> gelesen und akzeptiere sie .</p>
		<div class="grid_16 alpha" style="height:20px;border-bottom:1px solid #ccc;margin-bottom:20px;"></div>
		<div class="grid_8 alpha">
			<a class="button w90 gradientgrey tx_12_15" title="Zum Shop" href="<?= push_href_link(FILENAME_DEFAULT) ?>"><img src="images/push/icons/ico_arrow-rw_S.png" style="vertical-align: middle; margin-right: 10px" />Zum Shop</a>
		</div>
		<div class="grid_8 omega tx_right">
     	 	<?php echo push_submit("Verbindliche Bestellung auslösen", 'class="submitBtn w190 darkblue tx_12_15 tx_strong" name="submitConfirmation" title="Verbindliche Bestellung auslösen" id="einkaufAbschliessen" style=""');?>
		</div>
	</div>
</div>	
</form>
	<!-- body_text_eof //-->
</div></div>

<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<?php require(DIR_WS_LIB . 'end.php'); ?>