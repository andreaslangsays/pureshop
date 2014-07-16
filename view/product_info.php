<?php
/*
  $Idä: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/ajax_top.php');
if($_SESSION['current_page']<>CANONICALURL)
{
	$_SESSION['last_page'] = $_SESSION['current_page'];
	$_SESSION['current_page']= CANONICALURL;
}

if ($_SERVER['SERVER_PORT']==443) header("Location: " . CANONICALURL);

if( (!isset($_GET['redirected'])) && ($_GET['redirected'] <>'true')){
	// Permanent redirection
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . push_href_link(FILENAME_PRODUCT_INFO, push_get_all_get_params(array('action'))) . "");
	exit();
}
//error_reporting(E_ALL | E_STRICT);

if(isset($_GET['bonuson'])&&$_GET['bonuson']== true)
{
	$_SESSION['bonusoff']=array();
	push_redirect(push_href_link(FILENAME_PRODUCT_INFO,'products_id='.$_GET['products_id']));
}

$product->load_product($_GET['products_id']);

if (isset($_POST['submit-email']) && isset($_POST['name']) && isset($_POST['email'])) {
	
	$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
				<HTML>
					<HEAD>
						<META content="text/html; charset=utf-8" http-equiv=Content-Type>
						<META name=GENERATOR content="MSHTML 9.00.8112.16437">
					</HEAD>
				<BODY>
					<Div style="width:620px">
						<DIV style="margin-bottom:20px; margin-top:20px">
							<IMG src="http://if-bi.com/shop/images/push/push-logo.png"><span>&nbsp;&nbsp;&nbsp;</span>
							<FONT color=#1179cb face="Myriad Pro, Arial, Helvetica, sans-serif">TASTE IT. LOVE IT. SHOP IT.</FONT>
						</DIV>
					<!-- Start MSG-->
						<DIV style="border:solid #ccc 1px; overflow:hidden; padding:20px; font-size:13px; line-height:1.5">
							<DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif"><b>Ein Freund möchte Ihnen ein Produkt empfehlen</b></FONT></DIV>
						  <DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
						  <DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">' . $_POST['name'] . ' schreibt:</FONT></DIV>
							<P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">' . $_POST['email-text'] . '</FONT></P>
							<P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">&nbsp;</FONT></P>
					
					<!--Start Box-->      
						<div style="position:relative; overflow:hidden; font-size:13px; line-height:1.5; border:solid 1px #CCC; background-color:#f5f5f5; padding:5px">
							<table border="0" cellpadding="0" cellspacing="0" width="560" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; font-size:13px; color:#333; line-height:1.5">
								<tr>
									<td width="170"><a href="' . CANONICALURL . '" style="color:#1179bc; text-decoration:none; max-height:150; max-width:150; margin-right:20; text-align:left; vertical-align:top" target="_blank"><DIV style="width: 140px; height: 140px; border:1px solid; border-color:#ccc; background-color:#fff; padding:10px; margin: 0 10px 0 0; vertical-align: middle; text-align: center;"><IMG src="http://www.if-bi.com/shop/images/' . $product->get_image('gallery',140) . '"></DIV></a>
									</td>
									<td style="vertical-align:top">
										<p><a href="http://www.if-bi.com" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; color:#1179cb; text-decoration:none" target="_blank"><b>www.if-bi.com</b></a>
										</p>
										<p>' . $product->products_name . ' - ' . $product->products_short_description . '
										</p>
										<p><a href="' . CANONICALURL . '" target="_blank" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; color:#fff; text-decoration:underline" target="_blank"><IMG src="http://www.if-bi.com/shop/images/push/produkt_ansehen_btn.png" style="border:0px;"></a>
										</p>
									</td>
								</tr>
							</table>
						</div>
					<!--End Box-->
					
						</DIV>  
					<!--EOM-->
					
					
					<!-- Start Contact  
						<DIV>
						  <DIV style="border:solid 1px #88bde5; background-color:#e7f2fa; padding:10px; margin-top:20px; margin-bottom:20px; font-size:11px; line-height:1.5">
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">push Int’l Food &amp; Beverage Import GmbH<BR>
							  Mainstraße 171/172, C-56873 Bruessels<BR>
							  <a href="http://www.if-bi.com" style="color:#1179bc; text-decoration:none" target="_blank">www.if-bi.com</a></FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Telefon: +49 (0)30 28 47 00-0, 
							  Telefax: +49 (0)30 28 47 00-77, E-Mail: kontakt@if-bi.com</FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Handelsregister: Amtsgericht 
							  Charlottenburg, Handelsreg.-Nr.: HRB 94602B<BR>
							  VAT-ID: 
							  DE239223851<BR>
							  Geschäftsführer: Matthias Gladiatory, Andreas Heroe, Stefan 
							  Richter</FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Bankverbindung: 
							  Commerzbank<BR>
							  Bankleitzahl: 100 400 00, Kontonummer: 51 63 05 000 <BR>
							  BIC: 
							  COBADEFF, IBAN: DE87 1004 0000 0516 3050 00</FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Öko-Kontrollstelle: 
							  DE-ÖKO-044</FONT></DIV>
						  </DIV>
						</DIV>
					<!-- End Contact
					
					<!-- Start Disclaimer
						<div style="font-size:11px; line-height:1.5; padding-left:10px; padding-right:10px; margin-bottom:20px">
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Achtung!</FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Diese email kann Betriebs- und 
							  Geschäftsgeheimnisse, dem Anwaltsgeheimnis unterliegende oder sonstige 
							  vertrauliche Informationen enthalten. Sollten Sie diese email irrtümlich 
							  erhalten haben, ist Ihnen eine Kenntnisnahme des Inhalts, eine Vervielfältigung 
							  oder Weitergabe der email ausdrücklich untersagt. Bitte benachrichtigen Sie uns 
							  und vernichten Sie die email. <BR>
							  Der Absender hat alle erdenklichen 
							  Vorsichtsmaßnahmen getroffen, dass die Anlagen dieser eMail frei von 
							  Computerviren o.ä. sind. Gleichwohl schließen wir die Haftung für jeden Schaden 
							  aus, der durch Computerviren o.ä. verursacht wurde, soweit wir nicht vorsätzlich 
							  oder grob fahrlässig gehandelt haben. Wir raten Ihnen, dass Sie in jedem Fall 
							  Ihre eigene Virenprüfung vornehmen, bevor Sie die Anlagen öffnen. Vielen Dank </FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT face="Myriad Pro, Arial, Helvetica, sans-serif"><FONT color="#666">Important!! <BR>
							  The information contained in this email message may be confidential 
							  information, and may also be the subject of legal professional privilege. If you 
							  are not the intended recipient, any use, interference with, disclosure or 
							  copying of this material is unauthorised and prohibited. Please inform us 
							  immediately and destroy the email. <BR>
							  We have taken every reasonable precaution 
							  to ensure that any attachment to this eMail has been swept for viruses. However, 
							  we cannot accept liability for any damage sustained as a result of software 
							  viruses and would advice that you carry out your own virus checks before opening 
							  any attachment. Thank you for your cooperation</FONT> </FONT></DIV>
						</div>
					<!-- End Disclaimer-->
					</DIV>
					<?php echo EMAIL_FOOTER_OFFICIAL; ?>
				</BODY>
			</HTML>';
				
	$emails = explode(",", $_POST['email']);
	foreach ($emails as $email) {	
		push_mail('', trim($email), 'Ein Freund möchte Ihnen ein Produkt empfehlen', $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);					
	}
	
	$friend_email_sent = true;
}

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
$product->load_product($_GET['products_id']);
if (!$product->product_exists)
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
	$product->add_to_seen_products();
/**
 * add this impression of the product
 */
push_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . $product->products_id . "' and language_id = '" . (int)$languages_id . "'");
// in sortiment?
$sortimentStar = '';
if ($customer->login) {
	if ($wishList->in_wishlist($product->products_id)) {
		$sortimentStar .= '<div style="width: 35px; height: 32px; display: inline-block; position: relative; top: -6px; left: 10px">';
		$sortimentStar .= '<form class="del-from-sortiment-form" name="wishlist_form" method="post" >';
		$sortimentStar .= '	<input type="hidden" value="' . $product->products_id . '" name="add_wishprod[]">';
		$sortimentStar .= '	<input style="display: block" class="del-from-sortiment-star" type="submit" name="delete_prod_x" value="">';
		$sortimentStar .= '	<div class="sprechblase-red tx_12_15 tx_white">Artikel aus Mein Sortiment entfernen?</div>';
		$sortimentStar .= '</form>';
		$sortimentStar .= '</div>';
	} else {
		$sortimentStar .= '<a class="add-to-sortiment-star" href="' . push_href_link(FILENAME_PRODUCT_INFO, push_get_all_get_params() . "products_id=" . $product->products_id . "&sortiment=sortiment" ) . '" style="position: relative; top: 4px; left: 0; width: 32px; display: inline-block"></a>';
		$sortimentStar .= '<div class="sprechblase-green tx_12_15 tx_white" style="top: -60px; left: -128px">Artikel in Mein Sortiment aufnehmen</div>';
	}
}
?>
<div   itemscope itemtype="http://data-vocabulary.org/Product">
<h1  itemprop="name" class="grid_16 ProductHeading" style="position: relative; margin-bottom:10px;"><?= $sortimentStar ?><?php if ($product->is_new()) echo '<span class="newProdH1">Neu!</span>'; ?> <?php echo $product->products_name; ?></h1>
<div class="clearfix"></div>
<!-- products image start -->
<div id="imagebox" class=" bbox grid_6" >
	<div class="  bbox productImage"  style="position:relative;">
		<div class="  bbox wraptocenter" style="text-align:center;">
<?php
echo '		<span></span><a href="' . push_href_link(DIR_WS_IMAGES . 'fullsize/' . $product->products_image) . '" class="imgpop" rel="gallery"><img itemprop="image" src="' .DIR_WS_IMAGES .$product->get_image('detail',320) . '" alt="' . $product->products_name . ' Produktbild" title="' . $product->products_name . '"/></a>';
?>

		</div>
	<?php echo $product->get_infographics('n');?>
	</div>
<?php
if(count($product->products_image_array)>0 /*&& $customer->customers_id == 36767*/)
{
	//multiple images here!
	$y=0;
	if($product->discountable)
	{
		$bp=$discount->get_products_discounts_product($product->products_id);
		$bonus = new product();
		$bonus->load_product($bp['product']);
		$product->products_image_array[] = $bonus->products_image;
	}
	foreach($product->products_image_array as $mimage)
	{
		if(trim($mimage)<>'')
		{
			$y++;
		?>
			<div class="bbox" style="margin-top:-10px;text-align:center;float:left;width:60px; height:60px;<?=( $y>1 && 5/$y == round(5/$y) )? '':'margin-right:10px;' ?>border:1px solid #ccc;line-height:60px;margin-bottom:20px;overflow:hidden;">
			<a href="<?php echo push_href_link(DIR_WS_IMAGES . 'fullsize/' . $mimage); ?>" class="imgpop" rel="gallery" style="line-height:60px;"><img src="imagethumb.php?s=images/fullsize/<?=$mimage?>&w=50" style="vertical-align:middle"></a></div>
		<?php
		}
	}

}
?>
<!--Image end-->
</div>
<div id="descriptionbox" class="grid_4">
	<p class="description">
	<?php 
		echo stripslashes(stripslashes(nl2br($product->products_short_description)));
		//echo stripslashes(stripslashes(nl2br($product->products_description)))
	?>
	</p>
<?php if($customer->kunde)
{
	
?>
	<span class="tx_12_15 tx_light_gray">
	<?php echo $product->availability_txt ?>
	</span>
<?php
}

?>
</div>
<div id="doright" class="bbox grid_6 alpha omega" >
<?php

if($product->discountable)
{
?>
	<div id="bonusbox" style="position:relative"></div>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#bonusbox').load('./includes/modules/ajax/ajax_bonus.php?aid=<?=$product->products_id?>');
		$('#doright').on('click','#plist', function(e){
			$('#bonusbox').load('./includes/modules/ajax/ajax_bonus.php?list=1&aid=<?=$product->products_id?>');		
		})
		$('#doright').on('click','#clist', function(e){
			$('#bonusbox').load('./includes/modules/ajax/ajax_bonus.php?aid=<?=$product->products_id?>');		
		})

		$('#doright').on('click', 'a.fast_buy_button', function(event){
			prodID= $(this).attr('data-pid');
			//$('#prodToCart').html('<div id="loadingIcon" style="margin: 40px 0 0 145px"><img src="images/assets/ajax-loader.gif" /></div>');
			$('#prodToCart').load('./includes/modules/ajax/ajax-tocart.php?products_id=' + prodID, function() {
				var str= window.location.href;
				var action = str.replace('.html', '&action=fast_buy.html');
				if (str.indexOf("sortiment.php") > -1) {
					action += "&action=fast_buy&ref=sortiment";
				}
				$("#prodToCart form").attr("action", action);
				//TODO redirect to current page!
			//	console.log(action)
			});
			$('#semitransparent').show();
			$("#prodToCart").show();
		});
	});
	</script>
<?php	
}
if( $customer->login)
{
	$col='';
	if($product->special)
	{
		$col=' tx_red';
	}
	$tcol="";

?>
	<div id="pricebox" class="bbox grid_6 unselectable" style="margin-bottom:10px;border:1px solid #ccc;padding:10px;position:relative;">
<?php

if($product->has_ve)
	{
	##############################################################################
		if($product->ve_left < 1)
		{
			$tcol=$col;
			$col=" tx_grey";
		}
		$ve_price = $currencies->format_clean($product->ve_end_price);
		$ve_price_string =  $currencies->format($product->ve_end_price) . " <span class='tx_15_30" . $col . "' style='bottom:1px;display:inline-block;position:relative;'>/</span> VE";
		$ve_info  = "1 VE = <span class='tx_17_30'>" .  $product->ve_multiplier . "</span> Stück";
	
		echo '			<form name="buy_now_' . $product->products_id . '"  action="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product->products_id . '&action=fast_buy') . '" method="post" style="width:300px;overflow:visible;">';
		if($product->special)
		{
			echo "<span class='tx_stroke tx_13_20'>" . $currencies->display_price($product->ve_old_end_price) . " / VE</span><br />";
		}

		echo '			<span class="tx_25_30' . $col . '"> ' . $ve_price_string . '</span><span class="tx_25_30"> | </span><span class="tx_13_30">' . $ve_info . '</span><br>'; 
		echo '			<span class="tx_12_15">(' . $currencies->format($product->ve_single_price) . ' / St.)</span>';
		echo '			' . push_draw_hidden_field('products_id', $product->products_id);
		echo '			' . push_draw_hidden_field('ve_id', $product->ve_id);
		echo '			' . push_draw_hidden_field('id[' . $product->ve_o_1 . ']', $product->ve_o_2);
		/**
		 * Einkaufsfunktionalität
		 */
		if($product->ve_left > 0)
		{
			$product->get_ve_special_tag();
		?>	<br />
			<b>-</b><input type="text" name="cart_quantity" value="1" maxlength="3" data-max="<?= $product->ve_left ?>" size="2"><b>+</b> <div class="buysubmit-cont"><img src="images/push/icons/ico_cart-ve_white.png" onclick="$('form[name=buy_now_<?= $product->products_id ?>]').submit()" /><?php echo '<input type="submit"  value="In den Warenkorb"  name="' . $product->products_name  . '" class="darkblue buysubmit" style="">' . "\n"; ?></div><br />
			<span class="tx_13_15 tx_pink">Sie sparen <?php echo $currencies->format( $product->ve_difference) ?> /St. </span> <span class="tx_12_15">gegenüber dem Einzelkauf</span><br /><br />
	<?php
		}
		else
		{
		//no VE left!!
			echo '	
			<div style="height: 25px; text-align: center">
			<!--<img src="./images/push/product-not-available.png"><br>-->
			<div class="tx_light_gray tx_12_15">VE zur Zeit ausverkauft</div>
			</div>
			<br>';
		}
		?>
	</form>
	<div class="clearfix" style="border-bottom:1px dotted #ccc;height:0px;width:300px;margin-bottom:20px;"></div>
<?php
	}
		
		if($product->available == false)
		{
			$col=" tx_grey";	
		}
		else
		{
			$col=$tcol;
		}
	if((!($product->has_ve && $_SESSION['customer_only_ve'] != 0 )) || ($_SESSION['customer_only_ve']==0))
	{
	
	?>
	<form id="prodConfForm"  action="<?php echo push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product->products_id . '&action=fast_buy') ?>" method="post" name="cart_quantity"  style="width:300px; position:relative;">
	<?php
		$product->get_special_tag();
		if($product->special)
		{
			echo "<span class='tx_stroke tx_13_20'>" . $currencies->display_price($product->price) . " / Stück</span><br />";
			$col .= ' tx_special';
		}
	?>
		<span class='tx_25_30<?= $col ?>' ><?php echo $currencies->display_price($product->final_price); ?></span> 
		<span class='tx_15_30<?= $col ?>' style='bottom:1px;display:inline-block;position:relative;'>/</span>
		<span class='tx_25_30<?= $col ?>' >Stück</span>
		<?php
			echo '			' . push_draw_hidden_field('products_id', $product->products_id);
		
		if (!in_array($product->products_availability_id , $ired)) 
		{
			if($product->has_ve && ($product->ve_multiplier < $product->display_quantity))
			{
				$max = ($product->ve_multiplier -1);
			}
			else
			{
				$max = intval($product->display_quantity) ;
			}
			
			if($max >0){
				echo '			<br>
							<b>-</b><input type="text" name="cart_quantity" value="1" maxlength="3" size="2" data-max="' . $max .'"   /><b>+</b> '."\n";
				echo '			<div class="buysubmit-cont"><img src="images/push/icons/ico_cart-pcs_white.png" onclick="$(\'form[name=cart_quantity]\').submit()" /><input type="submit" name="' . $product_info['products_name']  . '" value="In den Warenkorb" class="darkblue buysubmit"></div>' . "\n";
			//					echo  push_image_submit('button-buy-now.png',  $product_info['products_name'] ,'class="buysubmit"') ."\n";//'push_draw_input_field('buynow', TEXT_NOW, 'class="buttontest"','submit') ;
				if($product->has_ve)
				{				
					echo ' 			<br><span class="tx_13_20">Ab <span class="tx_17_20">' . $product->ve_multiplier . "</span> Stück bitte <span class='tx_17_20'>VE</span> bestellen</span>";
				}
			}
			else
			{
				echo '	
						<div style="height: 25px; text-align: center">
						<!--<img src="./images/push/product-not-available.png"><br>-->
						<div class="tx_light_gray tx_12_15">Stück zur Zeit ausverkauft</div>
						</div>
						<br>';
			}
	//echo ' <img id="pricesumImg" src="images/pixel_trans.gif" style="margin-left:2px;" class="BKR pfeil_orange_indikator"><span id="pricesum" style="padding-left:10px;font-weight:bold;">' . number_format($pbarr[1], 2, ',', ' ') . '</span>&nbsp;<span style="font-weight:bold;" id="pricesumEur">EUR</span>';
		}
		if($product->available == false)
		{
			?>
				<div class="clearfix" style="border-bottom:1px dotted #ccc;height:0px;width:300px;margin-bottom:20px;"></div>
			<div class="detail" style="height:80px;position:relative;">
				<div class="tx_green tx_strong tx_13_15 mail-notification-header"<?= $product->is_notification_active() ? '' : ' style="display: none"' ?>>Sie erhalten eine E-Mail, sobald der Artikel wieder im Shop erhältlich ist.</div>
				<div class="tx_strong tx_13_15 mail-notification-header"<?= $product->is_notification_active() ? ' style="display: none"' : '' ?>>Lassen Sie sich per E-Mail benachrichtigen, sobald der Artikel wieder erhältlich ist:</div>
				<?php $product->get_notify_button(); ?>
			</div>
			<?php
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
			$('body').on('click', 'span.mail_notification', function(e) {
				$('.mail-notification-header').toggle();
			});
		});
</script>
</div>

<?php
} else {
	// customer not logged in
?>
	<div id="login-box" class="bbox grid_6 unselectable tx_13_20" style="margin-bottom: 10px; border: 1px solid #ccc; padding: 10px; position: relative">
		<div style="margin-left: 10px">
			<p>Keine Preise und Kaufen-Buttons zu sehen? <br />
			- Bitte melden Sie sich an.</p>
			<a class="button darkblue tx_white tx_12_15 w110" style="border: 1px solid #4195D5" href="<?php echo push_href_link(FILENAME_LOGIN); ?>">Anmelden <img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
			<p class="top_border" style="padding-top: 16px">Noch kein Konto? <a class="tx_12_15 tx_blue" href="<?php echo push_href_link(FILENAME_LOGIN); ?>">Hier registrieren!</a></p><br /><br />
		</div>
	</div>
<?php
}
?>
</div>
<?php
if(time() < strtotime($product->products_date_available))
{
	echo "<div style='float:right;margin-right:10px;color:#ff00ff;'>Verfügbar ab " . date("d.m Y", strtotime($product->products_date_available)) . " </div>";
}
?>
<div class="clearfix"></div>
<div class="grid_16" style="height:20px;">&nbsp;</div>
<div class="clearfix"></div>
<?php 
/*******************************
 * Product detail starts here! *
 *******************************/
include(DIR_WS_BOXES."product_tabs.php");
?>
<ul class="grid_3 bbox product_tab">
<li class="tx_13_20 active" id="overview" style="cursor:pointer;">Überblick</li>
<?php
$q=push_db_fetch_array(push_db_query("SELECT * FROM downloads WHERE ref_id ='" . $product->products_model . "'  AND ref_type='product' AND active='1'"));
if($q)
{
?>
<li class="tx_13_20" id="download" style="cursor:pointer;">Downloads</li>
<?php
	
}
?>
<li class="tx_13_20" id="question" style="cursor:pointer;">Frage zum Produkt?</li>
</ul>
<div class="grid_9 bbox" id="detailblock" style="border:1px solid #ccc;min-height:230px;">

	<div style="margin:20px;" class="ggler" id="overview-box">
	<?php
		product_info($product->id);
	?>
	</div>
	<div style="margin:20px;" class="ggler" style="display:none;" id="download-box">
	<?php
		product_datasheet($product->id);
	?>
	</div>
	<div style="margin:20px;" class="ggler" style="display:none;" id="question-box">
	<?php
		product_question($product->id);
	?>
	</div>
	
	
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#detailblock > div').hide();
		$('#detailblock > div:first-child').show();
		$('.product_tab li').on('click',function(quell){
			var rec = $(this);
			$('ul.product_tab li').removeClass('active');
			$(this).addClass('active');
			console.log('ID-' + rec[0]['id']);
			var name='#detailblock div#'+rec[0]['id']+"-box";
			$('.ggler').hide();
			$(name).show();
		})
	})
</script>

<?php
	/********************
	* social activities *
	********************/
?>
<div class="bbox grid_4 unselectable tx_13_20 gray-box" style="margin-bottom: 10px; position: relative">	
	<div class="gray-box-head" style="font-size: 15px; line-height: 40px">
		Empfehlen & Teilen
	</div>
	<div class="gray-box-body">
			
		<!-- Email -->
		<div style="margin-bottom: 15px" id="socialemail">
			<img src="images/push/icons/email_share_btn.png" onClick="$('.email-share-popup').show()" style="cursor: pointer" /><div class="tx_12_15 tx_light_gray" style="display: inline-block; width: 130px; position: relative; left: 7px; top: -3px">Artikel einem Freund per E-Mail empfehlen</div>
		</div>	
			
		<!-- Facebook -->
		<div style="margin-bottom: 8px" >
			<div style="width: 46px; overflow: hidden; display: inline-block" >
				<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode(CANONICALURL) ?>&amp;width=90&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;locale=en_US" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true" id="socialfb"></iframe>
			</div>
			<span class="tx_12_15 tx_light_gray" style="position: relative; left: 3px; top: -11px">Auf Facebook teilen</span>
		</div>
		
		<!-- Pinterest -->
		<div style="margin-bottom: 15px" id="socialpin">
			<a href="//www.pinterest.com/pin/create/button/?url=<?php echo urlencode(CANONICALURL) ?>&media=<?php echo urlencode(push_href_link(DIR_WS_IMAGES . 'fullsize/' . $product->products_image)) ?>&description=<?php echo urlencode($product->products_name) ?>" data-pin-do="buttonPin" data-pin-config="none"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a> <span class="tx_12_15 tx_light_gray" style="position: relative; left: 3px; top: -4px">Auf Pinterest teilen</span>
		</div>
		
		<!-- Google+ -->
		<div style="margin-bottom: 15px"  id="socialgp">
			<!-- Place this tag where you want the +1 button to render. -->
			<div class="g-plusone" data-size="medium" data-annotation="none" data-href="<?= CANONICALURL ?>"></div>			
			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
			  window.___gcfg = {lang: 'de'};			
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
			<span class="tx_12_15 tx_light_gray" style="position: relative; left: 3px; top: -5px">Auf Google+ teilen</span>
		</div>
		
		<!-- Xing -->
		<div style="margin-bottom: 15px" id="socialx">
			<div data-counter="no_count" data-type="XING/Share" data-url="<?= CANONICALURL ?>" data-lang="de"></div>
			<script>
			  ;(function (d, s) {
				var x = d.createElement(s),
				  s = d.getElementsByTagName(s)[0];
				  x.src = "https://www.xing-share.com/js/external/share.js";
				  s.parentNode.insertBefore(x, s);
			  })(document, "script");
			</script>
			<span class="tx_12_15 tx_light_gray" style="position: relative; left: 3px; top: -5px">Auf Xing teilen</span>
		</div>
		
		<!-- LinkedIn -->
		<div id="sociallin">
			<script src="//platform.linkedin.com/in.js" type="text/javascript">
			 lang: en_US
			</script>
			<script type="IN/Share" data-url="<?= CANONICALURL ?>"></script>
			<span class="tx_12_15 tx_light_gray" style="position: relative; left: 3px; top: -9px">Auf LinkedIn teilen</span>
		</div>
	</div>
</div>
 
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

<div id="shareProduct" class="email-share-popup" style="display: none">
	<div style="width: 380px; margin: 20px; background-color: #ffffff; padding: 15px 20px; display: relative">
		<img class="close-info-popup" src="images/push/close-info-popup.gif" style="top: 10px; right: 10px" onclick="$('.email-share-popup').hide()" />
		<h2 class="bottom_separator" style="width: 380px; padding-bottom: 20px; margin-bottom: 20px">Senden Sie Ihren Freunden per E-Mail einen Link auf dieses Produkt</h2>
		<div id="email-share-error" class="tx_red tx_strong tx_13_15" style="margin-bottom: 15px"></div>
		<form id="email-share" class="tx_13_15" method="post">
			<label for="name">Ihr Name</label><span class="tx_red">*</span><br />
			<input type="text" name="name" value="<?= $customer->customers_firstname . ' ' . $customer->customers_lastname ?>" style="width: 358px; border: 1px solid #999999; margin: 5px 0 15px; padding: 0 10px; height: 28px" />
			<label for="email">E-Mail-Adresse des Empfängers</label><span class="tx_red">*</span><br />
			<input type="text" name="email" style="width: 358px; border: 1px solid #999999; margin: 5px 0 0 0; padding: 0 10px; height: 28px" />
			<span class="tx_light_gray tx_12_15">Mehrere E-Mail-Adressen bitte mit Komma abtrennen.</span><br /><br />
			<span class="tx_blue" style="cursor: pointer" onclick="$('#email-text').slideDown()">Persönliche Nachricht hinzufügen?</span><br />
			<textarea id="email-text" name="email-text" style="display: none; margin: 5px 0 10px 0; border: 1px solid #999999; padding: 10px; width: 358px; height: 100px "></textarea><br />
			<div class="tx_right tx_light_gray tx_12_15"><span class="tx_red">*</span>Pflichtfelder</div>
			<input class="submitBtn w130 darkblue tx_12_15" type="submit" name="submit-email" value="E-Mail senden" style="margin-top: 20px">
		</form>
	</div>
</div>

<?php if (isset($friend_email_sent)) { ?>
	<div id="shareProduct" class="share-email-popup-success" style="display: block; top: 60%">
		<div style="width: 380px; margin: 20px; background-color: #ffffff; padding: 15px 20px; display: relative">
			<img class="close-info-popup" src="images/push/close-info-popup.gif" style="top: 10px; right: 10px" onclick="$('.share-email-popup-success').hide()" />
			<img style="vertical-align: middle; margin-right: 5px" src="images/push/icons/ico_true_S-green.png" />
			<strong class="tx_15_20" style="color: #99cc00">Ihre Nachricht wurde versendet.</strong>
		</div>
	</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(e) {
	
	$("a.imgpop").fancybox({
		'titlePosition'	: 'over',
		'transitionIn'	: 'elastic',
		'hideOnContentClick' : true,
		'transitionOut'	: 'elastic'
	});
	
	$('#email-share').submit(function() {
		if ( this.name.value == '' || this.email.value == '') {
			$('#email-share-error').text('Bitte alle Pflichtfelder ausfüllen.');
			return false;
		}
		return true;
	});
});
</script>
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<?php require(DIR_WS_LIB . 'end.php'); ?>
