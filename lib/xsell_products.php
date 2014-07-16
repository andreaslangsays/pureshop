<?php
/*
$Id: xsell_products.php, v1  2002/09/11
// adapted for Separate Pricing Per Customer v4 2005/02/24

osCommerce, Open Source E-Commerce Solutions
<http://www.oscommerce.com>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/
include('includes/ajax_top.php');
include(DIR_WS_CLASSES.'pref.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO); 
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_XSELL_PRODUCTS);

if ($_GET['products_id']) {

	$xsell_query = push_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, specials_new_products_price
	from " . TABLE_PRODUCTS_XSELL . " xp left join " . TABLE_PRODUCTS . " p on xp.xsell_id = p.products_id
	left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "'
	left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id 
	where xp.products_id = '" . $_GET['products_id'] . "'
	and p.products_status = '1'
	order by sort_order asc limit 14;");// . MAX_DISPLAY_XSELL);

$num_products_xsell = push_db_num_rows($xsell_query);
if ($num_products_xsell > 0) {
?>
<!-- xsell_products //-->
<h2><?=TEXT_XSELL_PRODUCTS?></h2>
<?php $limes = mysql_num_rows($xsell_query);
$w = ceil($limes/7)  * 630;
$add=( ceil($limes/7) * 7 ) - $limes;
?>
<div class="counter"><?php echo "Seite <span>1</span> von " . ceil($limes/7) ?></div>
<div class="wrapbox" >
<img src="images/pixel_trans.gif" class="BKR btn_bild-zurueck prev">
<img src="images/pixel_trans.gif" class="BKR btn_bild-vor next">
<div id="xsell" style="width:630px;overflow:hidden;">
<div  style="width:<?=$w?>px"> 
<?php
	$cou=0;
	while ($orders = push_db_fetch_array($xsell_query)) {
	$cou++;
	?>
	<div class="xsellboxcontent" >
	<?php
//display the image
		$pef = new pref($orders['products_id']);
		echo $pef->display_toolbox_images($orders['products_image'],$orders['products_name']);
	 ?>
	<div class="anchor"><a href="<?php echo generate_url_rewritten_string(push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id'])) ?>"><?= $orders['products_name']?></a></div></div>
	<?php		
     }
	  if( $add > 0){
	 	echo "<div style='min-width:" . $add * 90 . "px;'>&nbsp;</div>";
	 }
?>
</div>
</div>
<?php if($w>630){?>
<script type="text/javascript">
	var transt =200;//transition time - animation 
	var xpos=0;
	var weite= <?=$w?>;
	var defw=630;
	var xcounter=1;
	$(document).ready(function() {
	 //uncomment to get log: console.log(weite +" ist das Frame breit/ und " + xpos +" ist der aktuelle scrollwert" );
		if( (xpos < (weite-defw)) && (weite>defw) ){
			$('#xsellblock .next').fadeIn(transt);
		}
		$('#xsellblock #next').click(function(){ 
			$('#xsell').scrollTo('+=630');
			//after scrolling test if it goes further on=
			if( (xpos + (defw)) < (weite) ){
				xpos+=defw;//set internal scroll-pointer
				xcounter++;
				$('#xsellblock .counter span').text(xcounter);
			}
			if(xpos==defw)
				$('#xsellblock .prev').fadeIn(transt);
			if(xpos == (weite - defw) )//if there is no more space to scroll remove pointer
				$('#xsellblock .next').fadeOut(transt);
			//uncomment to log: console.log(" zu " + xpos + " verscrollt ");
		});
		$('#xsellblock #prev').click(function(){ 
			$('#xsell').scrollTo('-=630');
			if(xpos > 0){
				xpos-=defw;
				xcounter--;
				$('#xsellblock .counter span').text(xcounter);				
			}
			if(xpos == 0)
				$('#xsellblock .prev').fadeOut(transt);
			if(xpos == (weite - defw - defw) )
				$('#xsellblock .next').fadeIn(transt);
			//uncomment to log: console.log("  zu " + xpos + " verscrollt ");			
		});
	});
</script>
<?php 
}//make arrows clickable!
?>
</div>
<!-- xsell_products_eof //-->
<?php
   }
 }
 
include('includes/ajax_bottom.php');
?>