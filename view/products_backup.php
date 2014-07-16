<?php
/*
  $Idä: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $

  ösCommerce, Öpen Söürce E-Cömmerce Sölütiöns
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/ajax_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MANUFACTURERS);
require(DIR_WS_BOXES . 'html_header.php');
?>
<?php	include(DIR_WS_BOXES . 'static_menu.php'); ?>
<!-- /#left-column --> 
<!-- body_text //-->
<div class="grid_12">
<?php //echo push_draw_form('cart_quantity', push_href_link(FILENAME_PRODUCT_INFO, push_get_all_get_params(array('action')) . 'action=add_product')); 
	/**
	 * Navi X of Y back - last -  - next
	 * how to do? get last_query -> count Products, Get Product before and After (if available)
	 * go back to the last View .> needs to be more specific (mark that the Query is already here... ( maybe the settings have remained in the Session...))
	 */
	?>
<div id="cathead" class="maxihead" >
	<div id="inner-canvas" >
		<h1>HERSTELLERVERZEICHNIS</h1>
        <ul>
		<?php
		$mq=push_db_query("SELECT m.manufacturers_id,  m.manufacturers_image, m.manufacturers_name, COUNT(p.products_id) AS anzahl FROM manufacturers m JOIN products p ON m.manufacturers_id = p.manufacturers_id   WHERE p.products_status='1' GROUP BY m.manufacturers_name ORDER BY m.manufacturers_name ASC");
		while($m=push_db_fetch_array($mq)){
			
			echo '	<li><img src="' . push_href_link(DIR_WS_IMAGES .'brands/'. $m['manufacturers_image']) . '"<a href="'.push_href_link(FILENAME_MANUFACTURERS, 'manufacturers_id='.$m['manufacturers_id']).'" title="' . $m['anzahl'] . ' Artikel ">' . $m['manufacturers_name'] . ' - ' . $m['anzahl'] . '</a></li>' . "\n";


		}
		?>
		</ul>
        </div>
</div>
	
<?php
	
	 $a=0;
	 $lqr=push_db_query("SELECT m.manufacturers_id, m.manufacturers_image, m.manufacturers_name, COUNT(p.products_id) AS anzahl FROM manufacturers m JOIN products p ON m.manufacturers_id = p.manufacturers_id   WHERE p.products_status='1' GROUP BY m.manufacturers_name ORDER BY m.manufacturers_name ASC");
	 while($lq=push_db_fetch_array($lqr)){
	 		$a++;
			if($lq['manufacturers_id'] == (int)$_GET['manufacturers_id']){
//				echo $lq['manufacturers_name'];
				$current=$lq['manufacturers_id'];
				$x=$a;
			}
	 		if(!isset($current)){
	 			$before=$lq['manufacturers_id'];
			}elseif($a == $x+1){
				$after=$lq['manufacturers_id'];
			}		
	 }
/*	 if($current_category_query<>''){
		$current_category_query .= "page=". (1+(floor($x/$epp))) . "&";
	 }
*/
	 echo '<div id="mnavi"> ';
	echo " <span>" .$a . ' Hersteller</span> | <span class="xofy">' .$x . " von " .$a . '</span>' ;
	if(isset($before))
	 echo '<span class="before"><a href="' .push_href_link(FILENAME_MANUFACTURERS, $current_category_query . 'manufacturers_id='.$before) . '" >&#9664;  vorheriger</a></span> ';
	if(isset($after))
		echo '<span class="after"><a href="' .push_href_link(FILENAME_MANUFACTURERS, $current_category_query . 'manufacturers_id='.$after) . '" >n&auml;chster &#9654;</a></span>';
	echo "</div>";
	?>
		<div id="details">
		
        <?php 		
		$manufacturer_query = push_db_query("SELECT m.manufacturers_name, m.manufacturers_image, mi.manufacturers_country, mi.manufacturers_region, mi.manufacturers_description FROM " . TABLE_MANUFACTURERS . " AS m LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " AS mi USING (manufacturers_id) WHERE m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' AND languages_id = '" . (int)$languages_id . "'");
	$manu = push_db_fetch_array($manufacturer_query);
	if(push_not_null($manu['manufacturers_image'] )){
		?>
        <div id="imageboxm">
			<div class="productImage wraptocenter">
				<div class="inner">
        			<span></span><a href="<?php echo 'images/hersteller/' . $manu['manufacturers_image']; ?>"  class="bkrpop"><img src="<?php echo 'images/hersteller/' . $manu['manufacturers_image']; ?>"></a>
        		</div>
        	</div>
			<div class="imagelupe"><img src="images/newbkr/imagelupe.png"> zur Gro&szlig;ansicht Bild anklicken</div>
        </div>
        <div id="textpart" style="margin-left:240px;">

		<?php
		}else{
		?>
        <div id="textpart">
		<?php
		}if(push_not_null($manu['manufacturers_country'])){
		?>
        	<h2><?=$manu['manufacturers_country']?><?php echo ($manu['manufacturers_region']<>'')? " :: ".$manu['manufacturers_region']:'';?></h2>
			
		<?php }
		?>
        	<h1><?=$manu['manufacturers_name']?></h1>
			<p>
				<?=$manu['manufacturers_description']?>
            </p>
        
        </div>
        </div>
        <div class="container_16" style="height:20px;padding:10px;">&nbsp;</div>
        <div id="seenblock">
		<h2>Produkte von <?=$manu['manufacturers_name']?></h2>
		<div class="wrapbox" >
        	<?php
			$ci=0;
            $mpq=push_db_query("SELECT DISTINCT p.products_id, p.products_image, pd.products_name FROM products p JOIN products_description pd ON pd.products_id = p.products_id WHERE manufacturers_id='" . (int)$_GET['manufacturers_id'] . "' AND p.products_status='1' AND p.products_model NOT LIKE 'ccb_%' ORDER BY pd.products_viewed ASC  LIMIT 0,7");
			while($orders = push_db_fetch_array($mpq) ){
			$ci++;
			if($ci < 7){
			echo '<div class="xsellboxcontent" >';
//display the image
			$pef = new pref($orders['products_id']);
			echo $pef->display_toolbox_images($orders['products_image'],$orders['products_name']);
		echo '<div class="anchor" ><a href="' . push_href_link(FILENAME_PRODUCT_INFO,  'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></div></div>';
				}
			}
			?>
            <?php if( $ci > 1 ){ ?>
            <div class="xsellboxcontent"><a href="<?php echo push_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id']  )?>">
	            <div class="BKR btn_grau_hersteller-anzeigen">
<!--	           		<div class="inner"><a href="<?php echo push_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id']  )?>" class="anchor"  style="text-align:left;padding:0;font-size:10px;line-height:14px !important;text-decoration:underline; color:#6C2D22;min-height:60px;">
		            	Alle Artikel dieses Herstellers ansehen</a>
    	        	</div>-->
                </div></a>
        	</div>
            <?php } ?>
		</div></div>

<?php 
include(DIR_WS_MODULES .'seenproducts.php');


	 echo '<div id="mnavi" class="xofy"> ';
	echo " <span>" .$a . ' Hersteller</span> | <span class="xofy">' .$x . " von " .$a . '</span>' ;
	echo '<a href="#" style="margin-left:90px;" class="totop">&#9650; zum Seitenanfang</a>';
	if(isset($before))
	 echo '<span class="before" style="margin-left:100px;"><a href="' .push_href_link(FILENAME_MANUFACTURERS, $current_category_query . 'manufacturers_id='.$before) . '" >&#9664; vorheriger</a></span> ';
	if(isset($after))
		echo '<span class="after"><a href="' .push_href_link(FILENAME_MANUFACTURERS, $current_category_query . 'manufacturers_id='.$after) . '" >n&auml;chster &#9654;</a></span>';
	echo "</div>";
	 ?>
   
    </div>
	
	
	</div>
<!-- body_text_eof //-->
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>