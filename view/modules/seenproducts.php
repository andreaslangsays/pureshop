<?php
/*
 *
 * module to display seen products of user...
 *
 */

if( (isset($_GET['clear_visited'])) && ($_GET['clear_visited']=='true')){	//Debug Feature to reset History
	push_session_unregister('products_visited');
}

$limes = 0;
if(isset($products_visited)){
	$products_visited = array_values($products_visited);
	$limes = count($products_visited);
}

global $products_visited;

if (push_session_is_registered('products_visited') && sizeof($products_visited) > 0) {
?>
    <div id="seenblock" class="xsell-box">
	    <div class="xsell-box-header">
            <h2 class="tx_15_20 grid_7">Zuletzt angesehen</h2>
            <div class="counter tx_12_15 tx_light_gray" style="float: right"><?php echo "Seite <span class='tx_light_gray'>1</span> von " . ceil($limes/7) ?></div>
		</div>
        <?php 
            $w = ceil($limes/7)  * 840; //width of div
            $add=( ceil($limes/7) * 7 ) - $limes; //space to be added 	
        ?>        
        <div class="wrapbox">
            <div class="btn_bild-zurueck prev gradientgrey"><img src="images/push/icons/ico_arrow-rw_L.png" /></div>
            <div class="btn_bild-vor next gradientgrey"><img src="images/push/icons/ico_arrow-fw_L.png" /></div>
            <div id="seen" style="width:840px;overflow:hidden; position: relative">
                <div id="seen-content" style="width:<?=$w?>px;min-width:<?=$w?>px; position: relative"> 
                    <?php
                        include_once(DIR_WS_CLASSES . 'pref.php'); 
    
                        foreach ($products_visited as $tovis){
                            if ((int)$tovis <> '') {
                                
								$p->load_product($tovis);
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
            var transt =300;//transition time - animation 
            var spos=0;
            var sweite= <?=$w?>;
            var sdefw=840;
            var scounter=1;
            $(document).ready(function() {
                if( (spos < (sweite-sdefw)) && (sweite > sdefw) ){
                    $('#seenblock .next').fadeIn(transt);
                }
                $('#seenblock .next').click(function(){ 
                    // $('#seen').scrollTo('+=840');
					// $('#seen-content').css({left: ($('#seen-content').position().left - 840)});
					$('#seen-content').animate({left: ($('#seen-content').position().left - 840)}, transt);
                    if( (spos + (sdefw)) < (sweite) ){
                        spos+=sdefw;
                        scounter++;
                        $('#seenblock .counter span').text(scounter)
                        }
                    if(spos==sdefw)
                        $('#seenblock .prev').fadeIn(transt);
                    if(spos == (sweite - sdefw) )//if there is no more space to scroll remove pointer
                        $('#seenblock .next').fadeOut(transt);
                    //uncomment to log: console.log(" zu " + spos + " verscrollt ");			
        
                });
                $('#seenblock .prev').click(function(){ 
                    // $('#seen').scrollTo('-=840');
					// $('#seen-content').css({left: ($('#seen-content').position().left + 840)});
					$('#seen-content').animate({left: ($('#seen-content').position().left + 840)}, transt);
                    if(spos > 0){
                        spos-=sdefw;
                        scounter--;
                        $('#seenblock .counter span').text(scounter)
                    }
                    if(spos == 0)
                        $('#seenblock .prev').fadeOut(transt);
                    if(spos == (sweite - sdefw - sdefw) )
                        $('#seenblock .next').fadeIn(transt);
                    //uncomment to log: console.log("  zu " + apos + " verscrollt ");			
        
                });
            });
        </script>
    </div>
<?php
}
?>