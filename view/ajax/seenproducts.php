<?php
/*
 *
 * module to display seen products of user...
 *
 */
chdir('../../../');
require('includes/ajax_top.php');
include(DIR_WS_CLASSES.'pref.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO); 

//push_session_unregister('products_visited');
if(push_session_is_registered('products_visited')){
	if ($key = array_search( $_GET['products_id'], $products_visited ) ){
		unset($products_visited[$key]);
	}
$products_visited = array_values($products_visited);

?>

<h2><?=TEXT_LAST_SEEN_PRODUCTS?></h2>
<?php 
$limes=count($products_visited);
if(push_session_is_registered('torten_visited')){
	$torten_visited=array_values($torten_visited);
	$limes +=count($torten_visited);
}
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
	foreach($products_visited as $tovis){ 
		$seen_query=push_db_query( "SELECT p.products_id, p.products_image,pd.products_description, pd.products_name FROM products p, products_description pd WHERE p.products_id=pd.products_id AND p.products_id=" . (int)$tovis . " ;");
		$orders = push_db_fetch_array($seen_query) ;
		echo '<div class="xsellboxcontent" >';
//display the image
			
			$pef = new pref($orders['products_id']);
			echo $pef->display_toolbox_images($orders['products_image'],$orders['products_name']);
			echo '<div class="anchor" >' . ($pef->is_new() ? '<span class="newProdSmall">Neu</span>' : '') . '<a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></div></div>';
	 }
if(push_session_is_registered('products_visited')){	
	foreach($torten_visited as $provis){ 
			$seen_query=push_db_query( "SELECT p.products_id, p.products_image,pd.products_description, pd.products_name FROM products p, products_description pd WHERE p.products_id=pd.products_id AND p.products_id=" . (int)$provis . " ;", 'dbt');
			$orders = push_db_fetch_array($seen_query) ;
			echo '<div class="xsellboxcontent" >';
	//display the image
				$pef = new pref($orders['products_id'],'dbt');
				echo $pef->display_toolbox_images($orders['products_image'],$orders['products_name'], TOSH);
				echo '<div class="anchor" >' . ($pef->is_new() ? '<span class="newProdSmall">Neu</span>' : '') . '<a href="' . push_href_link(TOSH . FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></div></div>';
		 }
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
<?php
	if(!in_array($_GET['products_id'],$products_visited) ){
		array_unshift($products_visited ,(int) $_GET['products_id'] );
	}
}else{
  $products_visited = array($_GET['products_id']);
  push_session_register('products_visited');
}
