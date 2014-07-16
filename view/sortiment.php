<?php
/*
  $Idä: wishlist.php,v 3.0  2005/04/20 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/
// This version, removes the product when adding to a cart now, replaced all of the  HTTP_POST_VARS for $_POST

  require('includes/ajax_top.php');
  
	if (!$customer->login) { 
		$navigation->set_snapshot();
		push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
	}
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST);

  	$igreen = array('1','4');
	$iyellow = array('5','6','7','8','9','13','15');
	$ired = array('10','11','12','16');

/*******************************************************************
******* ADD PRODUCT TO WISHLIST IF PRODUCT ID IS REGISTERED ********
*******************************************************************/

  if(push_session_is_registered('wishlist_id')) {
 	unset($wishList->wishID['0']); 
//	$wishList->add_wishlist($_SESSION['wishlist_id'], $attributes_id);
/*******************************************************************
******* CREATES COOKIE TO STORE WISHLIST ON LOCAL COMPUTER  ********
********************************************************************
******* TO CHANGE THE LENGTH OF TIME THE COOKIE IS STORED:  ********
*******                                                     ********
******* EDIT THE "DAYS" VARIABLE BELOW.  THIS VARIABLE IS   ********
******* THE NUMBER OF DAYS THE COOKIE IS STORED.            ********
*******************************************************************/	
		$days = 1460;																															
		$time = time() + (3600 * 24 * $days);
	 	$cook_id = serialize($wishList->wishID);
		push_setcookie('wish', $cook_id , $time);
/***********************END CHANGE*********************************/

	if( (WISHLIST_REDIRECT == 'Yes') && ($_GET['redirect']==1) ) {
			push_session_unregister('wishlist_id');
			push_session_register('wishlistsuccess');
		if(basename($_SERVER['HTTP_REFERER'])<>''){
		//die(basename($_SERVER['HTTP_REFERER']));
		push_redirect(basename($_SERVER['HTTP_REFERER']));
		}else{
		push_redirect(push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_SESSION['wishlist_id']));
		}
	} else {
		push_session_unregister('wishlist_id');
	}
  }


/*******************************************************************
****************** ADD PRODUCT TO SHOPPING CART ********************
*******************************************************************/

  if (isset($_POST['add_wishprod'])) {
	if(isset($_POST['add_prod_x'])) {
		foreach ($_POST['add_wishprod'] as $value) {
			$product_id = push_get_prid($value);
			$cart->add_cart($product_id, $cart->get_quantity(push_get_uprid($product_id, $_POST['id'][$value]))+1, $_POST['id'][$value]);
			$wishList->remove($value);
			}
		}
	}
  


/*******************************************************************
****************** DELETE PRODUCT FROM WISHLIST ********************
*******************************************************************/

  if (isset($_POST['add_wishprod'])) {
	if(isset($_POST['delete_prod_x'])) {
		foreach ($_POST['add_wishprod'] as $value) {
			$wishList->remove($value);
		}
	}
  }
	$breadcrumb->reset();
	$breadcrumb->add('Mein Sortiment', push_href_link(FILENAME_SORTIMENT, '', 'SSL'));

//HEAD

require(DIR_WS_BOXES . 'html_header.php');

include(DIR_WS_BOXES . 'static_menu.php'); ?>

<!-- body_text //-->
<div class="grid_12 alpha">

<?php 
/*	if ($messageStack->size('wishlist') > 0) {			not working?!
		echo $messageStack->output('wishlist');
	}
*/
if (is_array($wishList->wishID) && !empty($wishList->wishID) && $wishList->count_wishlist() > 0) {		// own function count_wishlist(), because sometimes there are empty products in the wishlist
	reset($wishList->wishID);
?>
	<div class="grid_12 blue-grid-bg" style="width: 680px; padding: 10px; margin-bottom: 20px">
		<div class="tx_13_20" style="padding: 10px; width: 660px; background-color: #ffffff">
			<h4>Was ist "Mein Sortiment"?</h4>
			Wenn Sie einmal keine Zeit haben, sich durch unseren Onlineshop zu klicken, tätigen Sie Ihre Bestellung am besten über Ihr Sortiment. <br /><br />
			Sie können Ihrem Sortiment jedes Produkt  über einen <strong>Klick auf den Stern</strong> hinzufügen und es auf die gleiche Weise auch wieder löschen.
		</div>
	</div>
	<?php
		// products ids as list
		$productsStr = '(';
		foreach ($wishList->wishID as $k => $v) {
			if (!empty($k))
			{
				$productsStr .= "$k,";
			}
		}
		$productsStr = trim($productsStr, ",");
		$productsStr .= ')';	
	
		// sort sortiment
		if (isset($_POST['sortimentSort']))
			$sortimentSortOrder = $_POST['sortimentSort'];
		$sortimentSort_col = substr($sortimentSortOrder, 0 , 1);
		$sortimentSort_order = substr($sortimentSortOrder, 1);
		$sortimentSortBy = ' ORDER BY ';
		switch ($sortimentSort_col) {
			case '1':
				$sortimentSortBy .= "products_price" . ($sortimentSort_order == 'd' ? ' DESC' : '');
				$listing_sql = "select products_id from " . TABLE_PRODUCTS . " where products_id in " . $productsStr . $sortimentSortBy;				
				break;
			case '2':
				$sortimentSortBy .= "products_name" . ($sortimentSort_order == 'd' ? ' DESC' : '');
				$listing_sql = "select products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in " . $productsStr . $sortimentSortBy;				
				break;
			case '3':
				$sortimentSortBy .= "m.manufacturers_name" . ($sortimentSort_order == 'd' ? ' DESC' : '');
				$listing_sql = "select p.products_id from " . TABLE_PRODUCTS . " p, manufacturers m where p.manufacturers_id = m.manufacturers_id and p.products_id in " . $productsStr . $sortimentSortBy;
				break;
			case '4':
				$sortimentSortBy .= "counth" . ($sortimentSort_order == 'd' ? ' DESC' : '') . ', pd.products_name';
				$listing_sql = "select pd.products_id, (SELECT count(*) FROM orders o, orders_products op WHERE pd.products_id = op.products_id and o.orders_id = op.orders_id AND o.customers_id = " . $customer->customers_id . ") as counth from products_description pd where products_id in " . $productsStr . $sortimentSortBy;
				break;
			default :
				$sortimentSortBy .= "products_name" . ($sortimentSort_order == 'd' ? ' DESC' : '');
				$listing_sql = "select products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in " . $productsStr . $sortimentSortBy;
				break;
		}
			
		include(DIR_WS_MODULES . "pagenavi.php");

		$sortimentQ = push_db_query($listing_split->sql_query);
		$c = 0;
		
		echo '<div id="productswrapper" class="grid_12 alpha omega">';
		
		while ($productId = push_db_fetch_array($sortimentQ)) {		
			$product = new product();
			$product->load_product($productId['products_id']);
			$col=($product->special)?' tx_redpr':'';
			
			if ($view == 'list') {
				// list view
	?>
				<div class="grid_12 omega gray-border" style="width: 668px; padding: 15px; margin-top: 25px">
					<div class="grid_2 alpha omega" style="width: 109px; min-height: 157px">
						<div style="width: 80px; height: 80px; position: relative; margin-top: 10px">
							<img src="images/<?= $product->get_image("sortimentListe", 80) ?>" />
								<?php
									echo push_draw_form('wishlist_form', push_href_link(FILENAME_WISHLIST), 'post', ' class="sort-form"');
								?>
										<input type="hidden" value="<?= $product->products_id ?>" name="add_wishprod[]">
										<input class="sortiment-list-green-star" type="submit" name="delete_prod_x" value="" title="Artikel aus Mein Sortiment entfernen">
										<div class="sprechblase-red tx_12_15 tx_white">Artikel aus Mein Sortiment entfernen?</div>
									</form>
						</div>
						<div style="padding-top: 25px; height: 20px">
							<?php 
								$green = "#99CC00";
								$gray = "#E6E6E6";
								
								$inCart = $cart->get_quantity($product->products_id);
								if ($product->has_ve) {
									$veInCart = $cart->in_cart($product->ve_id) ? $cart->contents[$product->ve_id]['qty'] : 0;														
								}
							?>						
							<div class="tx_17_20 tx_strong" style="float: right; width: 50px; color: <?= $inCart ? $green : $gray ?>" title="<?= $inCart ?> St. in Warenkorb">
								<?= $inCart ?> <img style="position: relative; top: 4px" src="images/push/<?= $inCart ? 'in-cart-green-boxes.png' : 'not-in-cart-gray-boxes.png' ?>" />
							</div> 						
							<div class="tx_17_20 tx_strong" style="float: right; width: 45px; color: <?= $veInCart ? $green : $gray ?>"<?= $product->has_ve ? ' title="' . $veInCart . ' VE in Warenkorb"' : '' ?>>
								<?php
									if ($product->has_ve) {
								?>
										<?= $veInCart ?> <div style="width: 20px; height: 20px; display: inline-block; position: relative; top: 4px; background-color: <?= $veInCart ? $green : $gray ?>"></div>
								<?php
									} else {
										echo '&nbsp;';
									}
								?>
							</div>
						</div>
					</div>
					<div class="grid_9 alpha omega left_separator" style="width: 535px; padding-left: 20px">
						<div class="grid_9 alpha omega bottom_separator" style="width: 535px; padding-bottom: 10px; margin-bottom: 10px">
							<div class="grid_5 alpha omega" style="width: 300px">
								<a class="tx_15_20 tx_blue" href="<?= push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product->products_id) ?>" title="<?= $product->products_name ?>"><?= $product->products_name ?></a>
							</div>
							<div class="tx_12_15 tx_light_gray grid_3 alpha omega" style="width: 134px; text-align: right">
								<?= $product->availability_txt ?>
							</div>
							<div class="tx_13_15 grid_3 omega left_separator" style="width: 80px; padding-left: 10px">
								Art.Nr. <?= $product->products_model ?>
							</div>
						</div>
						<div id="pricebox" class="grid_9 alpha omega" style="width: 535px">
							<?php
								if ($product->has_ve) {
	
										$ve_price = $currencies->format_clean($product->ve_end_price);
										$ve_price_string =  $currencies->format($product->ve_end_price) . " <span class='tx_15_30$col' style='bottom:1px;display:inline-block;position:relative;'>/</span> VE";
										$ve_info  = "1 VE = <span class='tx_17_30'>" .  $product->ve_multiplier . "</span> Stück";
									
										echo '			<form  name="buy_now_' . $product->products_id . '" method="post" action="?action=fast_buy" style="position:relative;width:267px;overflow:visible" class="grid_4 alpha omega right_border">';
										$product->get_ve_special_tag('s');
										if($product->special)
										{
											echo "<span class='tx_stroke tx_13_20'>" . $currencies->display_price($product->ve_old_end_price) . " / VE</span><br />";
										}
										echo '			<div style="height: 55px">';
										echo '				<span class="tx_17_30' . $col . '"> ' . $ve_price_string . '</span><span class="tx_17_30"> | </span><span class="tx_13_30">' . $ve_info . '</span><br>'; 
										echo '				<span class="tx_12_15 tx_light_gray">(' . $currencies->format($product->ve_single_price) . ' / St.)</span>';
										echo '				' . push_draw_hidden_field('products_id', $product->products_id);
										echo '				' . push_draw_hidden_field('ve_id', $product->ve_id);
										echo '				' . push_draw_hidden_field('id[' . $product->ve_o_1 . ']', $product->ve_o_2);
										echo '			</div>';
										/**
										 * Einkaufsfunktionalität
										 */
										echo '<div>';
										if($product->ve_left > 0)
										{
										?>
											<b class="gradientlight">-</b><input type="text" name="cart_quantity" value="1" maxlength="3" data-max="<?= $product->ve_left ?>" size="2"><b class="gradientlight">+</b> <div class="buysubmit-cont"><img src="images/push/icons/ico_cart-ve_white.png" onclick="$('form[name=buy_now_<?= $product->products_id ?>]').submit()" /><?php echo '<input type="submit"  value="Warenkorb"  name="' . $product->products_name  . '" class="darkblue buysubmit" style="width: 120px">' . "\n"; ?></div><br />
											<div style="margin-top: 10px">
												<span class="tx_13_15 tx_pink">Sie sparen <?php echo $currencies->format( $product->ve_difference) ?> / St. </span> <span class="tx_12_15 tx_light_gray"> ggü. Einzelkauf</span>
											</div>
									<?php
										}
										else
										{
											//no VE left!!
									?>
											<div style="height: 80px; text-align: center">
												<img src="./images/push/product-not-available.png" /><br />
												<div class="tx_light_gray tx_12_15">VE momentan nicht verfügbar</div>
											</div>
									<?php
										}
										?>
											</div>
										</form>
								<?php
									} else {
								?>
										<div style="width: 267px; height: 100px; padding-top: 15px" class="grid_4 alpha omega right_border">
											<div style="height: 80px; text-align: center">
												<img src="./images/push/product-not-available.png" /><br />
												<div class="tx_light_gray tx_12_15">VE nicht verfügbar</div>
											</div>
										</div>							
								<?php
									}
									
									if((!($product->has_ve && $_SESSION['customer_only_ve'] != 0 )) || ($_SESSION['customer_only_ve']==0))
									{
									
									?>
									<form id="prodConfForm<?= $product->products_id ?>" method="post" name="cart_quantity" action="?action=fast_buy" style="width:247px; padding-left: 20px" class="grid_4 alpha omega">
	<?php
		$product->get_special_tag('s');
		if($product->special)
		{
		echo "<span class='tx_stroke tx_13_20'>" . $currencies->display_price($product->price) . " / Stück</span><br />";
		}
		?>
									<div style="height: 55px">
										<span class='tx_17_30<?=$col?>' ><?php echo $currencies->display_price($product->final_price); ?></span> 
										<span class='tx_17_30<?=$col?>' style='bottom:1px;display:inline-block;position:relative;'>/</span>
										<span class='tx_17_30<?=$col?>' >Stück</span>
									<?php
										echo '			' . push_draw_hidden_field('products_id', $product->products_id);
									echo '</div>';
									
									if ( ($product->final_price > 0) && ($product->products_quantity > 0) )
									{
										if($product->has_ve)
										{
											$max = $product->ve_multiplier - 1;
										}
										else
										{
											$max = $product->display_quantity ;
										}
										echo '<div>';
										echo '	
													<b class="gradientlight">-</b><input type="text" name="cart_quantity" value="1" maxlength="3" size="2" data-max="' . $max .'"   /><b class="gradientlight">+</b> '."\n";
										?>
											<div class="buysubmit-cont"><img src="images/push/icons/ico_cart-pcs_white.png" onclick="$('#prodConfForm<?= $product->products_id ?>').submit()" /><input type="submit" name="' . $product_info['products_name']  . '" value="Warenkorb" class="darkblue buysubmit" style="width: 120px"></div> 
										<?php
									//					echo  push_image_submit('button-buy-now.png',  $product_info['products_name'] ,'class="buysubmit"') ."\n";//'push_draw_input_field('buynow', TEXT_NOW, 'class="buttontest"','submit') ;
										echo '</div>';
										if($product->has_ve)
										{			
											echo '<div class=" tx_13_20" style="margin-top: 10px">'	;
											echo ' 			Ab <span class="tx_17_20">' . $product->ve_multiplier . "</span> Stück bitte <span class='tx_17_20'>VE</span> bestellen";
											echo '</div>';
										}
								//echo ' <img id="pricesumImg" src="images/pixel_trans.gif" style="margin-left:2px;" class="BKR pfeil_orange_indikator"><span id="pricesum" style="padding-left:10px;font-weight:bold;">' . number_format($pbarr[1], 2, ',', ' ') . '</span>&nbsp;<span style="font-weight:bold;" id="pricesumEur">EUR</span>';
										}
									echo "</form>";
								}
							?>
						</div>
					</div>				
				</div>	
	<?php		
			} else {
				// gallery view
				$c++;
				$classadd = '';
				if ($c == 4) {
					$classadd = "omega ";
					$c = 0;
				}
				if ($c == 1) {
					$classadd = "alpha ";
				}
				?>
				<div class="gallery gallery-sortiment grid_3 <?= $classadd ?>">
					<form class="del-from-sortiment-form" name="wishlist_form" method="post">
						<input type="hidden" value="<?= $product->products_id ?>" name="add_wishprod[]">
						<input class="del-from-sortiment-star" type="submit" name="delete_prod_x" value="">
						<div class="sprechblase-red tx_12_15 tx_white">Artikel aus Mein Sortiment entfernen?</div>
					</form>
				<?php
					push_product_link_opener($product->products_id, "tx_15_20 tx_blue");
					echo $product->get_infographics();
				?>
					<div class="image">
						<div class="inner">
							<image src="<?php echo DIR_WS_IMAGES . $product->get_image('gallery', 140) ?>" alt="" />
						</div>				
					</div>
				</a>
					<div class="name">
				<?php if ($product->is_new()) { ?>
						<span class="newProd tx_13_15">Neu!</span>
				<?php }
				
					push_product_link_opener($product->products_id);
					$pname = (str_replace($product->manufacturers_name,'',$product->products_name));
					echo '<span class="tx_blue tx_13_15">' .osc_trunc_string( $product->products_name, 66,' &hellip;') . '</span></a>';
				?>
					</div>
				<div class="pricebox sortiment-pricebox">	
					<div style="padding-top: 25px; height: 20px; width: 140px">
							<?php 
								$green = "#99CC00";
								$gray = "#E6E6E6";
								
								$inCart = $cart->get_quantity($product->products_id);
								if ($product->has_ve) {
									$veInCart = $cart->in_cart($product->ve_id) ? $cart->contents[$product->ve_id]['qty'] : 0;														
								}
							?>						
							<div class="tx_17_20 tx_strong" style="text-align: right; float: right; width: 50px; color: <?= $inCart ? $green : $gray ?>" title="<?= $inCart ?> St. in Warenkorb">
								<?= $inCart ?> <img style="position: relative; top: 4px" src="images/push/<?= $inCart ? 'in-cart-green-boxes.png' : 'not-in-cart-gray-boxes.png' ?>" />
							</div>						
							<div class="tx_17_20 tx_strong" style="text-align: right; float: right; width: 45px; color: <?= $veInCart ? $green : $gray ?>"<?= $product->has_ve ? ' title="' . $veInCart . ' VE in Warenkorb"' : '' ?>>
								<?php
									if ($product->has_ve) {
								?>
										<?= $veInCart ?> <div style="width: 20px; height: 20px; display: inline-block; position: relative; top: 4px; background-color: <?= $veInCart ? $green : $gray ?>"></div>
								<?php
									} else {
										echo '&nbsp;';
									}
								?>
							</div>
						</div>
				
				<?php
						if($product->available == false)
						{
							$product->get_notify_button();
						}
				?>
					</div>	
				<?php
				
						if($product->products_quantity > 0)
						{
						?>
							<a class="fastbuy fast_buy_button gradientgrey sortiment_fast_buy" data-pid="<?=$product->products_id?>"><span></span>Schnellbestellung</a>
				<?php
						}
				?>
					
				</div>	
		<?php
			}
		}
	?>
</div>
<?php
	include(DIR_WS_MODULES . "bottomnavi.php");
?>	
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#pricebox b').click(function(ev){
				var inpt = $(this).parent().children("input[type='text']");
				console.log(inpt.attr("data-max"));
				if($(this).text() == '+'  && (parseInt(inpt.val()) < inpt.attr("data-max")))
				{
					inpt.val( parseInt(inpt.val()) + 1)
				}
				if($(this).text() == '-'  && parseInt(inpt.val()) >1)
				{
					inpt.val(parseInt(inpt.val())-1)
				}
			});
	
		$("#pricebox input[type='text']").focusout(function(ev){
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
	});			
</script>

<?php

} else { // Nothing in the customers wishlist
?>
	<h2 style="padding: 20px 0 15px 80px; background: url('./images/push/green-star-sad-smile.png') no-repeat">Ihr Sortiment ist leer.</h2>
	<div class="grid_8 alpha blue-grid-bg" style="padding: 10px">
		<div class="tx_13_20" style="padding: 10px; width: 440px; background-color: #ffffff">
			<h4>Was ist "Mein Sortiment"?</h4>
			<strong>Klicken Sie auf die Sterne an den Produktfotos</strong>, wenn Sie einen Artikel interessant finden - so stellen Sie Ihr individuelles Sortiment Stück für Stück zusammen. <br />
			Rufen Sie Ihr Sortiment auf und legen Sie von dort aus Produkte in ihren Warenkorb. Insbesondere, wenn Sie es einmal eilig haben, können Sie über Ihr Sortiment <strong>ganz schnell eine Bestellung generieren</strong>. <br /><br />
			In Ihrem Sortiment sind Ihre Standardprodukte <strong>jederzeit schnell verfügbar und bestellbar</strong>. Sie können es ständig erweitern oder reduzieren.
		</div>
	</div>
<?php }
?>
<!-- customer_wishlist_eof //-->
<?php

//FOOT

?>
	</div>
</div>
<!-- body_text_eof //-->

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>