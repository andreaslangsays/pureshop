<?php
/*
  $Idä: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');

if( (!isset($_GET['redirected'])) && ($_GET['redirected'] <>'true')){
	// Permanent redirection
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . push_href_link(FILENAME_PRODUCT_INFO, push_get_all_get_params(array('action'))) . "");
	exit();
}
//error_reporting(E_ALL | E_STRICT);

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

require(DIR_WS_MODULES . "product_navi.php");
require(DIR_WS_BOXES . 'html_header.php');
?>
<div class="container_16">
<?php
if(isset($a)){
?>
<div id="mininavi" class="grid_16" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc;padding: 0;margin-bottom:10px;padding: 6px 0">
<span  class="tx_left tx_12_20 tx_light_gray" style="display:inline-block;margin-top:8px;font-size:12px;"><strong class="tx_light_gray"><?=$a?></strong> in <?= $actualtitle?></span>
<span class="tx_right" style="position:relative; bottom:-3px;">
<?php
if(isset($before))
echo '<span class="before" style="display_inline-block;margin-right:8px;"><a href="' . push_href_link(FILENAME_PRODUCT_INFO, $current_category_query . 'products_id=' . $before . "&t=" . $rv) . '" ><img src="' . push_href_link(DIR_WS_IMAGES . 'push/pageselector-prev-bg.png') . '"></a></span>';
if(isset($after))
echo '<span class="after"><a href="' . push_href_link(FILENAME_PRODUCT_INFO, $current_category_query . 'products_id=' . $after  . "&t=" . $rv) . '" ><img src="' . push_href_link(DIR_WS_IMAGES . 'push/pageselector-next-bg.png') . '"></a></span>';
?></span>
<span  class="tx_right tx_12_20 tx_light_gray" style="display:inline-block;margin:8px 8px 0 8px;font-size:12px;">Artikel <?=$x?>/<?=$a?></span>
<a class="tx_right button gradientlight tx_12_15 w90" style="margin-top: 3px; padding-right: 10px; border-color: #cccccc" href="<?= push_href_link(FILENAME_DEFAULT, $current_category_query.$pagestring) ?>" ><img src="images/push/icons/ico_arrow-rw_S-double.png" style="position: absolute; left: 11px; top: 11px">Zur Übersicht</a>
</div>
<?php
	}
	?>
<div class="clearfix"></div>
<?php
$p->load_product($_GET['products_id']);
if (!$p->product_exists)
{
/**
 * Product not Found
 */
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'static_menu.php'); ?>
<!-- /#left-column --> 
</div>
        <div style="padding-left:80px;overflow:hidden;width:500px;">
		  <?php echo  '<h1>' . TEXT_PRODUCT_NOT_FOUND . '</h1>';
		  //try to get the Products name:
		  $nfr=push_db_query("SELECT p.manufacturers_id, pd.products_name, m.manufacturers_name FROM products p JOIN products_description pd ON p.products_id=pd.products_id JOIN manufacturers m ON p.manufacturers_id=m.manufacturers_id WHERE p.products_id='" . mysql_escape_string($_GET['products_id']) . "'");
		  if($pr=push_db_fetch_array($nfr)){
			 echo "<p >Leider haben wir den Artikel  <span style=\"display:block;color:#ED7F00\">&raquo;" . $pr['products_name']  . "&laquo;</span>kurzfristig aus dem Sortiment genommen.</p>";
			 echo "<h2 style=\"margin-top:0px;padding-top:12px;\"><a href='" . push_href_link(FILENAME_DEFAULT, 'manufacturers_id='. $pr['manufacturers_id']) ."' >Hier finden Sie andere Artikel des Herstellers  " . $pr['manufacturers_name'] . "</a><h2>"; 
 		  }else{
			 echo "<p>Leider konnte das Produkt nicht gefunden werden. Wahrscheinlich haben wir es kürzlich aus dem Sortiment entfernt.</p>";
			 echo '<p><a href="' . push_href_link(FILENAME_DEFAULT) . '">' .'Klicken Sie hier um zur Startseite zurückzukehren.</a></p>'; 
		  }
		 ?>
     </div></div>
	 </div>
<?php
}
else
{
	$p->add_to_seen_products();
/**
 * add this impression of the product
 */
push_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . $p->products_id . "' and language_id = '" . (int)$languages_id . "'");
// in sortiment?
$sortimentStar = '';
if ($customer->login) {
	if ($wishList->in_wishlist($p->products_id)) {
		$sortimentStar .= '<div style="width: 35px; height: 32px; display: inline-block; position: relative; top: -6px; left: 10px">';
		$sortimentStar .= '<form class="del-from-sortiment-form" name="wishlist_form" method="post" >';
		$sortimentStar .= '	<input type="hidden" value="' . $p->products_id . '" name="add_wishprod[]">';
		$sortimentStar .= '	<input style="display: block" class="del-from-sortiment-star" type="submit" name="delete_prod_x" value="">';
		$sortimentStar .= '	<div class="sprechblase-red tx_12_15 tx_white">Artikel aus Mein Sortiment entfernen?</div>';
		$sortimentStar .= '</form>';
		$sortimentStar .= '</div>';
	} else {
		$sortimentStar .= '<a class="add-to-sortiment-star" href="' . push_href_link(FILENAME_PRODUCT_INFO, push_get_all_get_params() . "products_id=" . $p->products_id . "&sortiment=sortiment" ) . '" style="position: relative; top: 4px; left: 0; width: 32px; display: inline-block"></a>';
		$sortimentStar .= '<div class="sprechblase-green tx_12_15 tx_white" style="top: -60px; left: -128px">Artikel in Mein Sortiment aufnehmen</div>';
	}
}
?>
<div   itemscope itemtype="http://data-vocabulary.org/Product">
<h1  itemprop="name" class="grid_16 ProductHeading" style="position: relative; margin-bottom:10px;"><?= $sortimentStar ?><?php if ($p->is_new()) echo '<span class="newProdH1">Neu</span>'; ?> <?php echo $p->products_name; ?></h1>
<div class="clearfix"></div>
<!-- products image start -->
<div id="imagebox" class=" bbox grid_6" >
	<div class="  bbox productImage"  style="position:relative;">
		<div class="  bbox wraptocenter" style="text-align:center;">
<?php
echo '		<span></span><a href="' . push_href_link(DIR_WS_IMAGES . 'fullsize/' . $p->products_image) . '" class="imgpop"><img itemprop="image" src="' .DIR_WS_IMAGES .$p->get_image('detail',320) . '" alt="' . $p->products_name . ' Produktbild" title="' . $p->products_name . '"/></a>';
?>

		</div>
	<?php echo $p->get_infographics();?>
	</div>
<!--Image end-->
</div>

<div id="descriptionbox" class="grid_4">
	<p class="description">
	<?php 
		echo stripslashes(stripslashes(nl2br($p->products_short_description)));
		//echo stripslashes(stripslashes(nl2br($p->products_description)))
	
	?>
	</p>
<?php if($customer->kunde)
{
?>
	<span class="tx_12_15 tx_light_gray">
	<?php echo $p->availability_txt ?>
	</span>
<?php
}

?>
</div>
<?php
if($p->products_quantity > 0 && $customer->login)
{
?>
	<div id="pricebox" class="bbox grid_6 unselectable" style="margin-bottom:10px;border:1px solid #ccc;padding:10px;">
<?php
if($p->has_ve)
	{
	##############################################################################
		$ve_price = $currencies->format_clean($p->ve_end_price);
		$ve_price_string =  $currencies->format($p->ve_end_price) . " <span class='tx_15_30' style='bottom:1px;display:inline-block;position:relative;'>/</span> VE";
		$ve_info  = "1 VE = <span class='tx_17_30'>" .  $p->ve_multiplier . "</span> Stück";
	
		echo '			<form name="buy_now_' . $p->products_id . '"  action="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id . '&action=fast_buy') . '" method="post" style="width:300px;overflow:visible;">';
		echo '			<span class="tx_25_30"> ' . $ve_price_string . ' | </span><span class="tx_13_30">' . $ve_info . '</span><br>'; 
		echo '			<span class="tx_12_15">(' . $currencies->format($p->ve_single_price) . ' / St.)</span>';
		echo '			' . push_draw_hidden_field('products_id', $p->products_id);
		echo '			' . push_draw_hidden_field('ve_id', $p->ve_id);
		echo '			' . push_draw_hidden_field('id[' . $p->ve_o_1 . ']', $p->ve_o_2);
		/**
		 * Einkaufsfunktionalität
		 */
		if($p->ve_left > 0)
		{
		?>	<br />
			<b>-</b><input type="text" name="cart_quantity" value="1" maxlength="3" data-max="<?= $p->ve_left ?>" size="2"><b>+</b> <div class="buysubmit-cont"><img src="images/push/icons/ico_cart-ve_white.png" onclick="$('form[name=buy_now_<?= $p->products_id ?>]').submit()" /><?php echo '<input type="submit"  value="In den Warenkorb"  name="' . $p->products_name  . '" class="darkblue buysubmit" style="">' . "\n"; ?></div><br />
			<span class="tx_13_15 tx_pink">Sie sparen <?php echo $currencies->format( $p->ve_difference) ?> /St. </span> <span class="tx_12_15">gegenüber dem Einzelkauf</span><br /><br />
	<?php
		}
		else
		{
		//no VE left!!
			echo '	
			<div style="height: 80px; text-align: center">
			<img src="./images/push/product-not-available.png"><br>
			<div class="tx_light_gray tx_12_15">VE nicht verfügbar</div>
			</div>
			<br>';
		}
		?>
	</form>
	<div class="clearfix" style="border-bottom:1px dotted #ccc;height:0px;width:300px;margin-bottom:20px;"></div>
<?php
	}

	if((!($p->has_ve && $_SESSION['customer_only_ve'] != 0 )) || ($_SESSION['customer_only_ve']==0))
	{
	
	?>
	<form id="prodConfForm"  action="<?php echo push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id . '&action=fast_buy') ?>" method="post" name="cart_quantity"  style="width:300px;">
		<span class='tx_25_30' ><?php echo $currencies->display_price($p->final_price); ?></span> 
		<span class='tx_15_30' style='bottom:1px;display:inline-block;position:relative;'>/</span>
		<span class='tx_25_30' >Stück</span>
		<?php
			echo '			' . push_draw_hidden_field('products_id', $p->products_id);
		
		if ( ($p->final_price > 0) && (!in_array($p->products_availability_id , $ired)) )
		{
			if($p->has_ve && ($p->ve_multiplier < $p->display_quantity))
			{
				$max = ($p->ve_multiplier -1);
			}
			else
			{
				$max = intval($p->display_quantity) ;
			}
			
			if($max >0){
				echo '			<br>
							<b>-</b><input type="text" name="cart_quantity" value="1" maxlength="3" size="2" data-max="' . $max .'"   /><b>+</b> '."\n";
				echo '			<div class="buysubmit-cont"><img src="images/push/icons/ico_cart-pcs_white.png" onclick="$(\'form[name=cart_quantity]\').submit()" /><input type="submit" name="' . $product_info['products_name']  . '" value="In den Warenkorb" class="darkblue buysubmit"></div>' . "\n";
			//					echo  push_image_submit('button-buy-now.png',  $product_info['products_name'] ,'class="buysubmit"') ."\n";//'push_draw_input_field('buynow', TEXT_NOW, 'class="buttontest"','submit') ;
				if($p->has_ve)
				{				
					echo ' 			<br><span class="tx_13_20">Ab <span class="tx_17_20">' . $p->ve_multiplier . "</span> Stück bitte <span class='tx_17_20'>VE</span> bestellen</span>";
				}
			}
			else
			{
				echo '	
						<div style="height: 80px; text-align: center">
						<img src="./images/push/product-not-available.png"><br>
						<div class="tx_light_gray tx_12_15">Artikel nicht mehr verfügbar</div>
						</div>
						<br>';
			}
	//echo ' <img id="pricesumImg" src="images/pixel_trans.gif" style="margin-left:2px;" class="BKR pfeil_orange_indikator"><span id="pricesum" style="padding-left:10px;font-weight:bold;">' . number_format($pbarr[1], 2, ',', ' ') . '</span>&nbsp;<span style="font-weight:bold;" id="pricesumEur">EUR</span>';
		}
echo "</form>";
	}
?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#pricebox b').addClass('gradientlight');
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
</div>
<?php
}
?>
<div class="clearfix"></div>
<div class="grid_16" style="height:20px;">&nbsp;</div>
<div class="clearfix"></div>
<?php 
/*******************************
 * Product detail starts here! *
 *******************************/
?>
<div class="grid_12 bbox" id="detailblock" style="border:1px solid #ccc;min-height:230px;">

<div style="margin:20px;">
<div class="tx_13_20" style="border-bottom:1px dotted #ccc;padding-bottom:10px;margin-bottom:10px;">
<h4 class="tx_15_30"style="margin-bottom:0;">Produktbeschreibung</h4>
<span itemprop="description">
<?php
	echo stripslashes(stripslashes(nl2br($p->products_description)));
	//echo $p->products_short_description;
?></span>
</div>
<div class="tx_13_20" style="border-bottom:1px dotted #ccc;padding-bottom:10px;margin-bottom:10px;">
<h4 class="tx_15_30"style="margin-bottom:0;">Verpackungsinformationen</h4>
<?php
	echo $p->products_package_info;
?>
</div>
<?php 
if($p->products_usage_info <> '')
{
	?>
<div class="tx_13_20" style="border-bottom:1px dotted #ccc;padding-bottom:10px;margin-bottom:10px;">
<h4 class="tx_15_30"style="margin-bottom:0;">Verwendung</h4>
<?php
	echo $p->products_usage_info;
?>
</div>
<?php
}
	/**
	 * package
	 */
	 if(false){
?>
			<p><?=TEXT_CONTENT?>:</b> <span id="package"><?php echo $p->get_package();?>&nbsp;</span>
<?php
		/**
		 * abtropfgewicht
		 */
		 if( $pef->get_abtropfgewicht() ){
	?>
			<b>Abtropfgewicht:</b> <span id="package"><?php echo $p->get_abtropfgewicht();?>&nbsp;</span>
	<?php
		}
	}
	/**
	 * Manufacturer (just a query more!)
	 */
	
	if($p->manufacturers_name <>''){
		?>
		<div class="tx_13_20" style="border-bottom:1px dotted #ccc;padding-bottom:10px;margin-bottom:10px;">
<h4 class="tx_15_30"style="margin-bottom:0;"> <?= TABLE_HEADING_MANUFACTURER?> </h4>
<a  itemprop="brand" href="<?php echo ((false) ? push_href_link(FILENAME_MANUFACTURERS, 'manufacturers_id=' . $p->manufacturers_id) : '' ) ;?>" ><?= $p->manufacturers_name ?></a>
</div>
<?php
	}
	
	/**
	 * Artikelnummer
	 */
	 ?>
<div class="tx_13_20" style="border-bottom:none;padding-bottom:10px;margin-bottom:10px;">
<h4 class="tx_15_30"style="margin-bottom:0;"><?=TEXT_ART_NR?></h4>
<?=$p->products_model?>
</div>
<?php 	/**
	 * TODO: Bio -> Informationen einfuegen...
	 */

if($p->is_bio()){
?>
		<b>Bio:</b> <span>ja</span>
<?php
}
?>

<?php
/**
 * special Gültigkeit
 */
if($p->special && ($p->expires_date > 0)){
?>
<b>Angebot gilt bis:</b><span><?= date('d.m.Y',strtotime($p->expires_date)) ?></span>
<?php
	}
?>
<?php
	/**
	 * Product Added...
	 */

?>
<strong><?php
/* 
if($p->products_date_added != "0000-00-00 00:00:00"){
echo sprintf(TEXT_DATE_ADDED, push_date_short($p->products_date_added)); 
}else{
echo sprintf(TEXT_DATE_ADDED, push_date_short("2009-11-25 00:00:00")); 
}*/
?></strong>

	</div>
</div>
<?php 
if(false){
?>
	<div class=" grid_4" id="fblikebox" style="display:none;position:relative;padding-top:19px;width:200px;float:right;margin-right:10px">
	<?php
	/*********************************
	* wishlist and social activities *
	**********************************/
	?>
	
	<div style="position:relative"><a href="" id="socialwidget" title="Bookmark setzen" id="socialwidget"><img src="images/pixel_trans.gif" class="BKR btn_sharing_bookmark"></a><div id="thewidget" >
	<img src="images/newbkr/popcorner.png" style="position:absolute;z-index:800;left:-7px;top:100px;">
	<!-- DELICIOUS -->
	<a href="http://www.delicious.com/save" onClick="window.open('http://www.delicious.com/save?v=5&noui&jump=close&url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;"><img src="images/pixel_trans.gif" class="BKR  ico_deilicous_16"> delicious</a><br>
	<!-- FOLKD -->
	<a href="http://www.folkd.com/submit/<?php echo CANONICALURL;?>" target="_blank" ><img src="images/pixel_trans.gif" class="BKR  ico_folkd_16"> Folkd</a><br>
	<!-- GOOGLE -->
	<a href="https://www.google.com/bookmarks/mark?op=add&bkmk=<?php echo urlencode(CANONICALURL);?>&title=<?php echo urlencode(TITLEADD);?>&annotation=<?php echo DESCR; ?>" target="_blank"><img src="images/pixel_trans.gif" class="BKR  ico_google_16"> Google</a><br>
	<!-- LINKARENA -->
	<a href="http://linkarena.com/bookmarks/addlink/?url=<?php echo urlencode(CANONICALURL);?>&title=<?php echo urlencode(TITLEADD);?>&desc=<?php echo DESCR; ?>" target="_blank"><img src="images/pixel_trans.gif" class="BKR  ico_linkarena_16"> Linkarena</a><br>
	<!-- LINKEDIN -->
	<a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(CANONICALURL);?>&title=<?php echo urlencode(TITLEADD);?>&summary=<?php echo urlencode(DESCR);?>" target="_blank"><img src="images/pixel_trans.gif" class="BKR  ico_linkedin_16"> LinkedIn</a><br>
	<!-- LINKSILO -->
	<a href="http://www.linksilo.de" onClick="javascript:location.href='http://www.linksilo.de/index.php?area=bookmarks&func=bookmark_new&addurl='+encodeURIComponent(location.href)+'&addtitle='+encodeURIComponent(document.title);return false" target="_top"><img src="images/pixel_trans.gif" class="BKR  ico_linksilo_16"> Linksilo</a><br>
	<!-- MR WONG -->
	<a href="http://www.mister-wong.de/add_url/" onclick="location.href=&quot;http://www.mister-wong.de/index.php?action=addurl&amp;bm_url=&quot;+encodeURIComponent(location.href)+&quot;&amp;bm_description=&quot;+encodeURIComponent(document.title);return false" title="Diese Seite zu Mister Wong hinzufügen" target="_top">
	<img src="images/pixel_trans.gif" class="BKR  ico_mrwong_16"> Mr. Wong</a><br>
	<!-- ONEVIEW -->
	<a href="javascript: location.href='http://oneview.com/link/quickadd/?URL=' + encodeURIComponent(location.href) + '&title=' + encodeURIComponent(document.title)">
	<img src="images/pixel_trans.gif" class="BKR  ico_oneview_16"> OneView</a><br>
	<!-- XING -->
	<a rel="nofollow" target="_blank" href="http://www.xing.com/app/user?op=share;url=<?php echo urlencode(CANONICALURL);?>" title="bei XING empfehlen!" target="_blank">
	<img src="images/pixel_trans.gif" class="BKR  ico_xing_16"> Xing</a><br>
	</div></div>
	<div id="socialshareprivacy"></div>
	</div> 
<?php 
}
?>
 
<div class="clearfix"></div>
<div id="xsellblock" style="display:none;"></div>
<div id="alsoblock" style="display:none;"></div>

<?php 
//if(!$js_enabled){
//include(DIR_WS_MODULES .'seenproducts.php');
//}else{
//<div id="seenblock" style="display:none;"></div>

//}
//include(DIR_WS_BOXES.'languages.php');
if(false && isset($a)){
	echo '<div id="bottomnavi"> ';
	echo '<span class="xofy"><!--' .$x . " von " .$a . ' | --><a href="' . push_href_link(FILENAME_DEFAULT, $current_category_query  . '&d=' . $rv) . '" > &#9632; zur &Uuml;bersicht</a>' ;
	echo '<a href="#" class="totop">&#9650; nach oben</a>';
	if(isset($before))
	 echo '<a href="' .generate_url_rewritten_string(push_href_link(FILENAME_PRODUCT_INFO, $current_category_query . 'products_id='.$before . '&t=' . $rv)) . '" class="before">&#9664; Zur&uuml;ck</a> ';
	if(isset($after))
	 echo '<a href="' .generate_url_rewritten_string(push_href_link(FILENAME_PRODUCT_INFO, $current_category_query . 'products_id='.$after . '&t=' . $rv)) . '" ' . ((isset($before))? 'class="after"': 'style="margin-left:351px;"').'>Weiter &#9654;</a></span>';
	echo "</div>";
	
/*	$imgq=push_db_query("SELECT products_image FROM products WHERE products_id IN ('" . $before ."','" . $after ."')");
	while($preima=push_db_fetch_array($imgq)){
		echo '<img src="' .DIR_WS_IMAGES .'full_size/' . $preima['products_image'] . '" class="preload"/> ';
	}
*/	
	}
?>
</div>
<?php } ?>
    </div>
	<!-- body_text_eof //-->
</div>
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<?php require(DIR_WS_LIB . 'end.php'); ?>
