<?php
/*
 *
 * module to display interesting products to the user. (modified copy of seenproducts.php)
 * interesting products = products from main categories of products from last order, without products from last order - see comments below :)
 *
 */

// get products from last order
$last_order_products_q = push_db_query("	SELECT 	op.products_id
											FROM 	orders o, orders_products op
											WHERE 	o.date_purchased = (SELECT MAX(date_purchased) 
																		FROM orders 
																		WHERE customers_id = " . $customer->customers_id . ")
													AND o.customers_id = " . $customer->customers_id . " 
													AND o.orders_id = op.orders_id");

while ($product = push_db_fetch_array($last_order_products_q)) {
	$last_order_products .= $product['products_id'] . ",";
}
$last_order_products .= "0";

// get main categories from last order
$last_order_categories_q = push_db_query("	SELECT 	LEFT (ptc.categories_id, 3) AS main_category
											FROM 	orders o, orders_products op, products_to_categories ptc
											WHERE 	o.date_purchased = (SELECT MAX(date_purchased) 
																		FROM orders 
																		WHERE customers_id = " . $customer->customers_id . ")
													AND o.customers_id = " . $customer->customers_id . " 
													AND o.orders_id = op.orders_id
													AND op.products_id = ptc.products_id
											GROUP BY main_category");

$main_categories = array();
while ($main_category = push_db_fetch_array($last_order_categories_q)) {
	array_push($main_categories, $main_category['main_category']);
}
 
if (!empty($main_categories)) {

	// get 7 random products for each main category without already purchased products
	$interesting_products_query = "SELECT interesting_products.* FROM (";
	for ($c = 0; $c < sizeof($main_categories); $c++) {
		$interesting_products_query .= "(	SELECT p.products_id
											FROM products p, products_to_categories ptc
											WHERE p.products_status = '1'
												AND p.products_id = ptc.products_id
												AND categories_id LIKE '" . $main_categories[$c] . "%'
												AND p.products_id NOT IN (" . $last_order_products . ")
											ORDER BY rand()
											LIMIT 7)" . ($c < sizeof($main_categories) - 1 ? " UNION ALL " : "");
	}
	$interesting_products_query .= ") AS interesting_products ORDER BY RAND()";
	
	$interesting_products = push_db_query($interesting_products_query);
	
	if (push_db_num_rows($orders_query) > 0) {
	
		$limes = sizeof($main_categories) * 7;	// 7 products per category
		
		?>
		<div id="interestingblock" class="xsell-box">
			<div class="xsell-box-header">
				<h2 class="tx_15_20 grid_7">Das könnte Sie interessieren</h2>
				<div class="counter tx_12_15 tx_light_gray" style="float: right"><?php echo "Seite <span class='tx_light_gray'>1</span> von " . ceil($limes/7) ?></div>
			</div>
			<?php 
				$w = ceil($limes/7)  * 840; //width of div
				$add=( ceil($limes/7) * 7 ) - $limes; //space to be added 	
			?>        
			<div class="wrapbox">
				<div class="btn_bild-zurueck prev gradientgrey"><img src="images/push/icons/ico_arrow-rw_L.png" /></div>
				<div class="btn_bild-vor next gradientgrey"><img src="images/push/icons/ico_arrow-fw_L.png" /></div>
				<div id="interesting" style="width:840px;overflow:hidden; position: relative">
					<div id="interesting-content" style="width:<?=$w?>px;min-width:<?=$w?>px; position: relative"> 
						<?php
							include_once(DIR_WS_CLASSES . 'pref.php');
		
							while ($tovis = push_db_fetch_array($interesting_products)) {
								
								if ((int)$tovis['products_id'] <> '') {
									
									$p->load_product($tovis['products_id']);
									if ($p->product_exists) {
									
										$pef = new pref($p->products_id);
										
										echo '<div class="xsell-box-content" >';									
										echo '<div class="xsell-image"><a class="tx_13_20 tx_blue" href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id) . '"><img src="' . DIR_WS_IMAGES . $p->get_image('sortimentListe', 80) . '" alt="' . $p->products_name . ' Produktbild" title="' . $p->products_name . '" /></a></div>';									
										echo '<div class="anchor">' . ($pef->is_new() ? '<span class="newProdSmall">Neu</span>' : '') . '<div class="tx_13_20">' . $p->manufacturers_name . '</div>' . '<a class="tx_13_20 tx_blue" href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id) . '">' . $p->products_name . '</a></div></div>'; 
									}
								}
							}
							
							if ($add > 0) {
								echo "<div style='min-width:" . $add * 90 . "px;'>&nbsp;</div>";
							}
						?>
					</div>
				</div>
			</div>
		
			<script type="text/javascript">
				var itranst =300;//transition time - animation 
				var ipos=0;
				var iweite= <?=$w?>; 
				var idefw=840;
				var icounter=1;
				$(document).ready(function() {
					if( (ipos < (iweite-idefw)) && (iweite > idefw) ){
						$('#interestingblock .next').fadeIn(itranst);
					}
					$('#interestingblock .next').click(function(){ 
						// $('#interesting').scrollTo('+=840');
						// $('#interesting-content').css({left: ($('#interesting-content').position().left - 840)});
						$('#interesting-content').animate({left: ($('#interesting-content').position().left - 840)}, itranst);
						if( (ipos + (idefw)) < (iweite) ){
							ipos+=idefw;
							icounter++;
							$('#interestingblock .counter span').text(icounter)
							}
						if(ipos==idefw)
							$('#interestingblock .prev').fadeIn(itranst);
						if(ipos == (iweite - idefw) )//if there is no more space to scroll remove pointer
							$('#interestingblock .next').fadeOut(itranst);
						//uncomment to log: console.log(" zu " + ipos + " verscrollt ");			
			
					});
					$('#interestingblock .prev').click(function(){ 
						// $('#interesting').scrollTo('-=840');
						// $('#interesting-content').css({left: ($('#interesting-content').position().left + 840)});
						$('#interesting-content').animate({left: ($('#interesting-content').position().left + 840)}, itranst);
						if(ipos > 0){
							ipos-=idefw;
							icounter--;
							$('#interestingblock .counter span').text(icounter)
						}
						if(ipos == 0)
							$('#interestingblock .prev').fadeOut(itranst);
						if(ipos == (iweite - idefw - idefw) )
							$('#interestingblock .next').fadeIn(itranst);
						//uncomment to log: console.log("  zu " + apos + " verscrollt ");			
			
					});
				});
			</script>
		</div>
<?php 
	}
}
?>
