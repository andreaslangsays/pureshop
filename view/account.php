<?php
/*
  $Id: account.php,v 1.61 2003/06/09 23:03:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');
//	push_session_unregister('cart_TELECASH_CONNECT_ID');
//	push_session_unregister('cart_PayPal_IPN_ID');

  if (!push_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);

	$breadcrumb->reset();
	$breadcrumb->add('Pers&ouml;nlicher Bereich', FILENAME_ACCOUNT);
	$breadcrumb->add('Start', FILENAME_ACCOUNT);
require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'static_menu.php'); ?>
<!-- /#left-column --> 
</div>
<div id="center-column">
<!-- body_text //-->
<div class="maincontent">
<?php 
	include(DIR_WS_CLASSES.'pref.php');
if(isset($_GET['order_id'])){
//here the message for completed Orders:
?>

<div class="border" style="overflow:hidden;padding:8px;border:0px double #9c3;margin-bottom:20px;background-image: url('images/newbkr/boxBackground.gif')">
	<div class="side" style="overflow:hidden;padding:10px;margin:0px;background-color:#fff;">

<h1>Vielen Dank für Ihre Bestellung!</h1>

<p style="color:#690;">Ihre Bestellung ist ausgeführt worden.</p>


</div>
</div>
<?php
}


	// show boxes
	$boxes = array(	DIR_WS_BOXES . 'bkr_userHistory.php',
					DIR_WS_BOXES . 'bkr_userLastReview.php',
					DIR_WS_BOXES . 'bkr_user5StarsProducts.php',
					DIR_WS_BOXES . 'bkr_userSpecials.php');
					
	$box = 0;	// you have to increase $box value manually in each box file ($box++)
	foreach ($boxes as $i => $value) {
		include($boxes[$i]);
		if ($box % 2) 
			echo '<div class="boxSeparator"></div>';
	}			
?> 

<?php 
//push_session_unregister('products_visited');
if(push_session_is_registered('products_visited')){

$products_visited = array_values($products_visited)
?>
<div style="clear:both"></div>
<div id="seenblock">
<h2><?=TEXT_LAST_SEEN_PRODUCTS?></h2>
<?php 

$limes=count($products_visited);
if($limes>70)
	$limes=70;
$w = ceil($limes/7)  * 630;
$add=( ceil($limes/7) * 7 ) - $limes;
?>
<div class="counter"><?php echo "Seite <span>1</span> von " . ceil($limes/7) ?></div>
<div class="wrapbox">
<img src="images/pixel_trans.gif" class="prev"  width="20" height="80">
<img src="images/pixel_trans.gif" class="next" width="20" height="80">
<div id="seen" style="width:630px;overflow:hidden;">
<div  style="width:<?=$w?>px;min-width:<?=$w?>px"> 
<?php
	for($cou=0;$cou < $limes; $cou++){ 
		$seen_query=push_db_query( "SELECT p.products_id, p.products_image,pd.products_description, pd.products_name FROM products p, products_description pd WHERE p.products_id=pd.products_id AND p.products_id=" . (int)$products_visited[$cou] . " ;");
		$orders = push_db_fetch_array($seen_query) ;
		echo '<div class="xsellboxcontent" >';
//display the image
			$pef = new pref($orders['products_id']);
			echo $pef->display_toolbox_images($orders['products_image'],$orders['products_name']);
		echo '<div class="anchor" ><a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></div></div>';
	 }
	 if( $add > 0){
	 	echo "<div style='min-width:" . $add * 90 . "px;'>&nbsp;</div>";
	 }
	 ?>
</div>
</div>
</div>
<script type="text/javascript">
	var spos=0;
	var sweite= <?=$w?>;
	var sdefw=630;
	var scounter=1;
	$(document).ready(function() {
		if( (spos < (sweite-sdefw)) && (sweite>sdefw) ){
			$('#seenblock .next').addClass('npointer');
		}
		$('#seenblock .next').click(function(){ 
			$('#seen').scrollTo('+=630');
			if( (spos + (sdefw)) < (sweite) ){
				spos+=sdefw;
				scounter++;
				$('#seenblock .counter span').text(scounter)
				}
			if(spos==sdefw)
				$('#seenblock .prev').addClass('ppointer');
			if(spos == (sweite - sdefw) )//if there is no more space to scroll remove pointer
				$('#seenblock .next').removeClass('npointer');
			//uncomment to log: console.log(" zu " + spos + " verscrollt ");			

		});
		$('#seenblock .prev').click(function(){ 
			$('#seen').scrollTo('-=630');
			if(spos > 0){
				spos-=sdefw;
				scounter--;
				$('#seenblock .counter span').text(scounter)
			}
			if(spos == 0)
				$('#seenblock .prev').removeClass('ppointer');
			if(spos == (sweite - sdefw - sdefw) )
				$('#seenblock .next').addClass('npointer');
			//uncomment to log: console.log("  zu " + apos + " verscrollt ");			

		});
	});
</script>
</div>
<?php
}
?>
    
	</div>
<!-- body_text_eof //-->
</div>
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>