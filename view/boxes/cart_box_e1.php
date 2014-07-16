<?php
// cärt_böx
?>
<div id="shopping_cart">
	<img src="images/push/icons/ico_cart_empty.png"  alt=""/>
	 <span class="tx_16_20 tx_blue">Warenkorb:</span> <span class="tx_13_20"><?= $cart->get_product_id_count()?> Artikel<?= sizeof($products) > 0 ? ' - ' . $currencies->display_price($cart->show_total(), 0) : '' ?></span>
	<span class="selectarrow" style="position:absolute;top:22px;right:15px;">&nbsp;&nbsp;&nbsp;</span>
</div>
<div id="cart_info">
<?php if ($customer->login) { ?>
		<div id="cart_preview"<?= sizeof($products) > 0 ? ' class="bottom_separator"' : '' ?>>
		<?php
		$navigation->set_snapshot();
		$any_out_of_stock = 0;
		$products = $cart->get_products(false);		// false: don't sort products by id, get insertion order
		if(sizeof($products) ==0)
		{
		?>
			<div class="tx_13_20 bottom_border" style="margin-bottom: 20px; padding-bottom: 15px; padding-top: 15px">
				Befüllen Sie Ihren Warenkorb, z.B. mit verlockenden <a class="tx_blue tx_12_15" href="http://if-bi.com/shop/Sweets-&-Snacks,c,cPath=105.html">Sweets & Snacks</a>, wohltuendem <a class="tx_blue tx_12_15" href="http://if-bi.com/shop/Tea,c,cPath=101.html">Tea</a>, guter <a class="tx_blue tx_12_15" href="http://if-bi.com/shop/Chocolate,c,cPath=102.html">Chocolate</a>, erfrischenden <a class="tx_blue tx_12_15" href="http://if-bi.com/shop/Ice-Cold,c,cPath=104.html">Ice Cold</a> Drinks, hilfreichen <a class="tx_blue tx_12_15" href="http://if-bi.com/shop/Tools,c,cPath=107.html">Tools</a> oder praktischem <a class="tx_blue tx_12_15" href="http://if-bi.com/shop/Equipment,c,cPath=108.html">Equipment</a> für Ihren Betrieb.
			</div>
			<a class="button gradientgrey tx_12_15" href="<?= push_href_link(FILENAME_SHOPPING_CART,'','SSL') ?>">Warenkorb öffnen<img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double.png"></a>
		<?php
		//	echo '<div id="cart_empty" class="tinycartlist" style="height:6px;margin-top:0px;padding:0;border-bottom:1px solid #ccc;padding-bottom:8px;margin-bottom:5px;">&nbsp;</div>';
		}
		else
		{
		//show only the last 3 Entries
		?>
		<div class="tx_15_20" style="margin-bottom: 17px;">Zuletzt hinzugefügte Artikel</div>
		<?php
			$restrict_lenght=3;
			for ($i=sizeof($products) - 1; $i> sizeof($products) - (1 +$restrict_lenght) ; $i--)
			{
				if($i < 0)
				{
					break;
				}
				$p->load_product($products[$i]['id']);
				if (($new_products_id_in_cart == $products[$i]['id'])&& ( basename( $_SERVER['PHP_SELF'] ) <> "shopping_cart.php"))
				{ ?>
					<h3 class="tx_13_15"><?=TEXT_NEW_PRODUCT_ADDED_TO_CART?></h3>
			<?php	push_session_unregister("new_products_id_in_cart");
					echo '<div id="cart_' . $i . '" class="top_item cart_list tx_13_15" style="border-bottom:1px solid #ccc;padding-bottom:5px;margin-bottom:5px;">';
				}
				else
				{
					echo '<div id="cart_' . $i . '" class="cart_list">';
				}
		
				if (STOCK_CHECK == 'true')
				{
					$stock_check = push_check_stock($p->products_id, $cart->get_quantity($p->products_id, true));
					if (push_not_null($stock_check)) 
					{
						$any_out_of_stock = 1;
					}
				}
				$img = $p->get_image('cartbox',30);
				$quantity = 0;
				$displayprice= ($p->ve_loaded) ? $p->ve_end_price : $p->final_price;
				echo '
						<div class="cart_t1 tx_13_20">
						<a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id) . '" >' . push_image(DIR_WS_IMAGES . $img, $p->products_name) . '</a>
						</div><div class="cart_t2 tx_13_15">' . $products[$i]['quantity'] .  'x</div> 
						<div class="cart_t3 tx_13_15"><a class="tx_13_15 tx_blue" href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id) . '" >' . $p->products_name . " " . (($p->ve_loaded)?'VE':'') .'</a></div>
						<div class="cart_t4 tx_13_20 tx_right">'. $currencies->display_price(  $displayprice, push_get_tax_rate($p->tax_class_id), $products[$i]['quantity'] ) . '</div>
						</div>';
			}
			?>
			</div>
			<?php
			if($discount->overall_amount > 0)
			{
			?>
			<div id="cart_preview_total" class="tx_13_15">
			<span class="bx_left tx_13_20">Gesamtwarenwert</span><span class="bx_right tx_13_20"><?= $currencies->display_price($discount->overall_amount,1)?></span>
			<div class="clearfix"></div>
			<span class="bx_left tx_13_20">Versandkosten</span><span class="bx_right tx_13_20"><?=  $currencies->format($discount->shipping_cost,1)?></span>
			<div class="clearfix"></div>
			<?php
			if($discount->discount_reached=true)
			{
			?>
				<span class="tx_pink tx_13_20 tx_strong">Bereits gespart:</span><br />
			<?php
				if($discount->shipping_savings > 0)
				{
			?>
					<span class="bx_left tx_pink tx_13_20"><img style="vertical-align: middle; margin-right: 5px" src="images/push/icons/ico_discount_true-s.png">Versandkosten </span><span class="bx_right tx_pink tx_13_20"><?php //echo $currencies->format($discount->shipping_savings,0) ?></span>
					<div class="clearfix"></div>
			<?php
				}			
				if($discount->general_savings > 0)
				{
			?>
					<span class="bx_left tx_pink tx_13_20"><img style="vertical-align: middle; margin-right: 5px" src="images/push/icons/ico_discount_true-s.png"><?php echo $discount->actual_discount_procent; ?>% Rabatt auf <?php echo $currencies->format($discount->discount_amount,1); ?> </span><span class="bx_right tx_pink tx_13_20"><?php echo $currencies->format( (-1 * $discount->general_savings), 1) ?></span>
					<div class="clearfix"></div>
			<?php
				}
								if($discount->online_rabatt > 0)
				{
			?>
					<span class="bx_left tx_pink tx_13_20"><img style="vertical-align: middle; margin-right: 5px" src="images/push/icons/ico_discount_true-s.png"><?php echo ONLINE_RABATT_PROCENT; ?>% Onlinerabatt </span><span class="bx_right tx_pink tx_13_20"><?php echo $currencies->format( (-1 * $discount->online_rabatt), 1) ?></span>
					<div class="clearfix"></div>
			<?php
				}
			}
			?>
			<div class="clearfix"></div>
			<span class="bx_left tx_13_20 tx_strong">Gesamtbetrag</span><span class="bx_right tx_13_20 tx_strong"><?= $currencies->display_price($discount->actual_discount_price + $discount->shipping_cost , 0)?></span>
		
			<br />
			<div class="clearfix"></div>
			<div class="bottom_separator" style="margin: 6px 0 5px 0"></div>
			<?php
			if($discount->need_more){
			$discount->get_next_discount_string();
			?>
			<span class="tx_pink tx_13_20">Fehlender Einkaufswert</span><br />
			<span class="bx_left tx_pink tx_13_20 cart-box-discount" style="position: relative">
            	bis <?php echo $discount->next_spush_identifier; ?>:
            	<div class="cart-box-discount-popup">
                	<div class="tx_15_20" style="width: 270px; height: 45px; padding: 5px 0 0 10px; margin-bottom: 7px; background: url('./images/push/pink-grid-bg-small.png')">
                    	<?= $discount->next_spush_popup_header ?>
                    </div>
                	<?= $discount->next_spush_popup_description ?>
				</div>
            </span>
            <em class="bx_right tx_pink tx_13_20"><?php echo $discount->next_difference_string; ?></em>
			<div class="clearfix"></div>
			<?php
			}
			}
		}
		?>
			</div>
		
		
		<div id="cart_box_bottom" style="margin-top:20px;overflow:hidden;">
		<?php
		/*
			if($i > -1){
				$left=$i+1;
				echo '<a href="' . push_href_link(FILENAME_SHOPPING_CART) . '" style="font-size:13px;margin-left:45px;padding-top:4px;vertical-align:bottom;display:inline-block;">+ ' . $left . ' ' .TEXT_PRODUCTS .'</a>';
			}
		/**/
		if(sizeof($products) > 0)
		{
			?>
			<a id="continueshopping" href="#" class="gradientblack button tx_12_15 tx_white" style="float: left; width: 100px; padding-left: 30px">
            	<img style="position: absolute; left: 11px; top: 11px" src="images/push/icons/ico_arrow-rw_S-double_white.png">
                Weiter einkaufen
            </a> 
		<?php
		}
		if($customer->login && ( $cart->get_product_id_count() > 0))
		{
		?>
			<a id="tocart" href="<?=push_href_link(FILENAME_SHOPPING_CART,'','SSL')?>" class="button darkblue tx_white tx_12_15" style="float: right; border: 1px solid #4195D5; width: 115px">
            	Zum Warenkorb
                <img src="images/push/icons/ico_arrow-fw_S-double_white.png" style="position: absolute; right: 11px; top: 11px">
            </a> 
		<?php
		}
		?>
		</div>
<?php } else { ?>
		<div class="tx_13_20" style="margin: 10px 0 15px 0">
			Keine Preise und Kaufen-Buttons zu sehen? <br />
			- Bitte melden Sie sich an.
		</div>
		<a class="button w110 darkblue tx_white tx_12_15" style="border: 1px solid #4195D5" title="Anmelden" href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>">Anmelden <img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
		<div class="tx_13_20 top_border" style="margin-top: 20px; padding-top: 15px">
			Noch kein Konto? <a class="tx_12_15 tx_blue" title="Gleich registrieren!" href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>">Gleich registrieren!</a>
		</div>
<?php } ?>
</div>
<?php if(isset($_SESSION['customer_id']) ){ ?>
	<div id="rabatt_info" class="tx_pink tx_13_15" <?php echo ($_COOKIE['displayhint']== 'yes')? ' style="display:block;"':' style="display:none;"';?> >
	<?php
		echo $discount->get_next_discount_string();
	?>
	 <img class="btn_close" alt="" src="images/push/btn_schliessen30.png"/>
	</div>
	<?php
	/**
	 * Rabatt Information je nach Warenkorbwert.
	 */
	if($sc_total < 1000)
	{
		?>
		<div id="display_rabatt" <?php echo ($_COOKIE['displayhint']== 'yes')?' style="display:none;"':' style="display:block;"';?>>
		<img src="images/push/display_rabatt.png" alt=""/>
		</div>
		<?php
	}
}
?>
