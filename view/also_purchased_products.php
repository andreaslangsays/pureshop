<?php
/*
  $Id: also_purchased_products.php,v 1.21 2003/02/12 23:55:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
include('includes/ajax_top.php');
include(DIR_WS_CLASSES.'pref.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
  if (isset($_GET['products_id'])) {
    $orders_query = push_db_query("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' and p.products_price > '0' group by p.products_id order by o.date_purchased desc limit 35");
    $num_products_ordered = push_db_num_rows($orders_query);
    if ($num_products_ordered >= 2) {
?>
<!-- also_purchased_products //-->
<h2><?=TEXT_ALSO_PURCHASED_PRODUCTS?></h2>
<?php $limes = $num_products_ordered;
$w = ceil($limes/7)  * 630;
$add=( ceil($limes/7) * 7 ) - $limes;
?>
<div class="counter"><?php echo "Seite <span>1</span> von " . ceil($limes/7) ?></div>
<div class="wrapbox">
<img src="images/pixel_trans.gif" class="BKR btn_bild-zurueck prev">
<img src="images/pixel_trans.gif" class="BKR btn_bild-vor next">
<div id="also" style="width:630px;overflow:hidden;">
<div  style="width:<?=$w?>px"> 
<?php
    while ($orders = push_db_fetch_array($orders_query)) {
       $orders['products_name'] = push_get_products_name($orders['products_id']);
       ?>
	  <div class="xsellboxcontent">
		<?php //display the image
			$pef = new pref($orders['products_id']);
			echo $pef->display_toolbox_images($orders['products_image'],$orders['products_name']);
		?>
			<div class="anchor">
			<?php if ($pef->is_new()) { ?>
					<span class="newProd">Neu</span>
			<?php } ?>
			<a href="<?php echo generate_url_rewritten_string(push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id'])) ?>"><?= $orders['products_name'] ?></a>
		</div>
	</div>
<?php
      }
	  if( $add > 0){
	 	echo "<div style='min-width:" . $add * 90 . "px;'>&nbsp;</div>";
	 }
?>
</div>
</div>
<script type="text/javascript">
	var transt =200;//transition time - animation 
	var apos=0;
	var aweite= <?=$w?>;
	var adefw=630;
	var acounter=1;
	$(document).ready(function() {
		if( (apos < (aweite-adefw)) && (aweite>adefw) ){
			$('#alsoblock .next').fadeIn(transt);
		}

		$('#alsoblock .next').click(function(){ 
			$('#also').scrollTo('+=630');
			if( (apos + (adefw)) < (aweite) ){
				apos+=adefw;
				acounter++;
				$('#alsoblock .counter span').text(acounter);	
			}
			if(acounter>1)
				$('#alsoblock .prev').fadeIn(transt);
			if(apos == (aweite - adefw) )//if there is no more space to scroll remove pointer
				$('#alsoblock .next').fadeOut(transt);
			//uncomment to log: console.log(" zu " + apos + " verscrollt ");			
		});
		$('#alsoblock .prev').click(function(){ 
			$('#also').scrollTo('-=630');
			if(apos > 0){
				apos-=adefw;
				acounter--;
				$('#alsoblock .counter span').text(acounter);	
			}
			if(apos == 0)
				$('#alsoblock .prev').fadeOut(transt);
			if(apos == (aweite - adefw - adefw) )
				$('#alsoblock .next').fadeIn(transt);
			//uncomment to log: console.log("  zu " + apos + " verscrollt ");			
		});
	});
</script>

</div>
<?php
	  }
  }
include('includes/ajax_bottom.php');
?>
<!-- also_purchased_products //-->
