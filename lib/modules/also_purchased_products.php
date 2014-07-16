<?php
/*
 *
 * module to display also purchsed products. (modified copy of seenproducts.php)
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

// get also purchased products 
$orders_query = push_db_query("select p.products_id from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id IN (" . $last_order_products . ") and opa.orders_id = opb.orders_id and opb.products_id  NOT IN (" . $last_order_products . ") and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' and p.products_price > '0' group by p.products_id order by o.date_purchased desc limit 35");

$num_products_ordered = push_db_num_rows($orders_query);
$limes = $num_products_ordered;

if ($num_products_ordered >= 2) {
?>
    <div id="alsoblock" class="xsell-box">
	    <div class="xsell-box-header">
            <h2 class="tx_15_20 grid_7">Käufer dieses Artikels kauften auch</h2>
            <div class="counter tx_12_15 tx_light_gray" style="float: right"><?php echo "Seite <span class='tx_light_gray'>1</span> von " . ceil($limes/7) ?></div>
		</div>
        <?php 
            $w = ceil($limes/7)  * 840; //width of div
            $add=( ceil($limes/7) * 7 ) - $limes; //space to be added 	
        ?>        
        <div class="wrapbox">
            <div class="btn_bild-zurueck prev gradientgrey"><img src="images/push/icons/ico_arrow-rw_L.png" /></div>
            <div class="btn_bild-vor next gradientgrey"><img src="images/push/icons/ico_arrow-fw_L.png" /></div>
            <div id="also" style="width:840px;overflow:hidden; position: relative">
                <div id="also-content" style="width:<?=$w?>px;min-width:<?=$w?>px; position: relative"> 
                    <?php
                        include_once(DIR_WS_CLASSES . 'pref.php'); 
    
                        while ($tovis = push_db_fetch_array($orders_query)) {
                            if ((int)$tovis <> '') {
                                
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
            var atranst =300;//transition time - animation 
            var apos=0;
            var aweite= <?=$w?>;
            var adefw=840;
            var acounter=1;
            $(document).ready(function() {
                if( (apos < (aweite-adefw)) && (aweite > adefw) ){
                    $('#alsoblock .next').fadeIn(atranst);
                }
                $('#alsoblock .next').click(function(){ 
                    // $('#also').scrollTo('+=840');
					// $('#also-content').css({left: ($('#also-content').position().left - 840)});
					$('#also-content').animate({left: ($('#also-content').position().left - 840)}, atranst);
                    if( (apos + (adefw)) < (aweite) ){
                        apos+=adefw;
                        acounter++;
                        $('#alsoblock .counter span').text(acounter)
                        }
                    if(apos==adefw)
                        $('#alsoblock .prev').fadeIn(atranst);
                    if(apos == (aweite - adefw) )//if there is no more space to scroll remove pointer
                        $('#alsoblock .next').fadeOut(atranst);
                    //uncomment to log: console.log(" zu " + apos + " verscrollt ");			
        
                });
                $('#alsoblock .prev').click(function(){ 
                    // $('#also').scrollTo('-=840');
					// $('#also-content').css({left: ($('#also-content').position().left + 840)});
					$('#also-content').animate({left: ($('#also-content').position().left + 840)}, atranst);
                    if(apos > 0){
                        apos-=adefw;
                        acounter--;
                        $('#alsoblock .counter span').text(acounter)
                    }
                    if(apos == 0)
                        $('#alsoblock .prev').fadeOut(atranst);
                    if(apos == (aweite - adefw - adefw) )
                        $('#alsoblock .next').fadeIn(atranst);
                    //uncomment to log: console.log("  zu " + apos + " verscrollt ");			
        
                });
            });
        </script>
    </div>
<?php
}
?>