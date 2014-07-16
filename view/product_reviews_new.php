<?php
/*
  $Id: product_reviews.php,v 1.50 2003/06/09 23:03:55 hpdl Exp $
  adapted for Separate Pricing Per Customer v4.0 2005/01/27

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

 // require('includes/application_top.php');
 ?>
<h2><?=TEXT_REVIEWS?></h2>
 <div id="explainreviews">
 <img src="images/pixel_trans.gif" class="BKR gesicht_M2" border="0" id="speaker">
 <img src="images/newbkr/speakhead.png" border="0" id="head" width="180" />
 <p>
<strong>Bitte beachten Sie bei Ihrer Bewertung:</strong><br><br> 
 Bitte haben Sie Verst&auml;ndnis daf&uuml;r, dass geh&auml;ssige Bemerkungen oder Obsz&ouml;nit&auml;ten, die Angabe alternativer Bestellm&ouml;glichkeiten, Webadressen, eMailadressen sowie Telefonnummern in der Produktbewertung nicht erw&uuml;nscht sind.
<br><br>
Ihre Bewertung wird von uns vor Ver&ouml;ffentlichung bzgl. o.a. Kriterien &uuml;berpr&uuml;ft und danach ver&ouml;ffentlicht.
 </p>
 </div>
 <div class="wrapbox">
 <?php
  $product_info_query = push_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");

    $product_info = push_db_fetch_array($product_info_query);
//Intro with stars
?>
<h3><?=AVG_GIVEN_STARS?><span style="font-weight:normal;"> <?php  push_get_stars_count($stars['stars']);?> <?=$stars['stars']?> Stern<?php echo ($stars['stars']>1)?'e':''; ?> (<?=$stars['anzahl']?> Bewertung<?php echo ($stars['anzahl']==1)?'':'en';?>)</span></h3>
<?php
/**
 * TODO: ob Kunde den Artikel noch nicht bewertet hat!!!
 * TODO: angemeldete Kunden dürfen alles bewerten!
 */
 if($stars['anzahl'] == 0){
 ?><h3><?=TEXT_NO_REVIEWS?></h3><?php
 }
if(isset($_SESSION['customer_first_name'])){
//ask if customer has ordered
$hasordered = push_db_query("SELECT date_purchased FROM orders o JOIN orders_products op ON (o.orders_id=op.orders_id) WHERE o.customers_id=" . $_SESSION['customer_id'] . " AND op.products_id='" . $product_info['products_id'] . "' AND op.no_reviews=0 ORDER BY date_purchased DESC ");
$ho=push_db_fetch_array($hasordered);

//next question: if the the customer has voted yet
$hasvoted=push_db_query("SELECT approved FROM reviews WHERE approved='0' AND products_id='" . $product_info['products_id'] . "'  AND customers_id='" . $_SESSION['customer_id'] . "' AND  TIMESTAMPDIFF(HOUR,date_added,NOW()) < 1 ;");
//echo "SELECT * FROM reviews WHERE products_id='" . $product_info['products_id'] . "'  AND customers_id='" . $_SESSION['customer_id'] . "' AND date_added > '" . $ho['date_purchased'] . "';";
	if($hv=push_db_fetch_array($hasvoted)){
?>
		<p class="main green bold"><?=TEXT_REVIEW_SENT?></p>
<?php		
		}//customer has already sent
	}	
?>		<!--<script type="text/javascript">
			function offandhide() {
				$('#question').block({ 
                message: '<img src="images/newbkr/loading.gif" style="margin:10px;">', 
                css: { height : '50px',
						width : '50px',
						 } 
            });
				$.get('skipreview.php',{
				'products_id': '<?=$product_info['products_id']?>' ,
				'customers_id':'<?=$_SESSION['customer_id']?>'
				},
				function(){$('#voting').hide('slow')});
			}	
		</script>-->
<?php
//
$hasvoted=push_db_query("SELECT * FROM reviews WHERE products_id='" . $product_info['products_id'] . "'  AND customers_id='" . $_SESSION['customer_id'] . "';");
if($na=push_db_fetch_array($hasvoted)){
//Kunde hat bereits bewertet
	echo "Vielen Dank, dass Sie dieses Produkt bewertet haben.";
}else{
?>
		<div id="voting"><h4 style="color:#3399ff"><img src="images/pixel_trans.gif" class="BKR icon_stern_10x11" title="Bewerten Sie diesen Artikel"> <?php echo (isset($_SESSION['customer_first_name']))? $_SESSION['customer_first_name'].', wie':'Wie'; ?> gef&auml;llt Ihnen dieses Produkt?</h4><?php
		
		if($ho <> false){ 
		?>
		<p>Sie haben dieses Produkt am <?=date('d.m.Y',strtotime($ho['date_purchased']))?> gekauft. </p>
		<?php
		}
		?>
		<?php 
		/**
		 * here comes the distinction 
		 */
		 if(isset($_SESSION['customer_first_name'])){
		?>
        <p ><span  onclick="writeit()" class="BKR btn_grau_bewertungschreiben">
		<?php }else{ ?>
		<p ><span id="loginforreview" title="Login to write a review"  class="BKR btn_grau_bewertungschreiben">
		<?php }?> </span> <!--<img src="images/newbkr/checkbox.png" onclick="$('#question').show()"> Ich m&ouml;chte diesen Artikel nicht bewerten--></p>
        </div>
		<?php
		/**
		 * here comes the distinction 
		 */
		 if(isset($_SESSION['customer_first_name'])){
		 ?>
        <div id="write_review" style="display:none">    
        <form id="reviewform" action="skipreview.php" method="post" enctype="text/plain">
 		<div class="starbox" style="overflow:hidden;">
        <label>Wertung</label>
			<input name="star" value="1" type="radio" class="star">
			<input name="star" value="2" type="radio" class="star"/> 
			<input name="star" value="3" type="radio" class="star"/>
			<input name="star" value="4" type="radio" class="star"/>
			<input name="star" value="5" type="radio" class="star"/> <span class="hint" style="display:none">Sie haben noch keine Sterne vergeben!</span></div>
		<div class="spacer"></div>
         <label>Auf den Punkt</label>
         	<input type="text" name="header" value="" maxlength="40" size="30" placeholder="optional" />
		<div class="spacer"></div>
         <label>Ausf&uuml;hrliche Meinung</label>
         	<textarea name="review" placeholder="optional"></textarea>
      	<div class="spacer"></div>
        <label>Autor</label>
        <?php
		//Stadt Query
		 $t=push_db_fetch_array(push_db_query("SELECT a.entry_city AS stadt FROM address_book a JOIN customers c ON (a.address_book_id=c.customers_default_address_id) WHERE c.customers_id='" . $_SESSION['customer_id'] . "'"));
		 $stadt=$t['stadt'];
		echo push_draw_hidden_field('stadt', $stadt);
		echo push_draw_hidden_field('customer_id', $_SESSION['customer_id']);
		echo push_draw_hidden_field('product_id',  $product_info['products_id'] );
		?>
			<input name="name" value="full" type="radio"/> <?php echo $_SESSION['customer_first_name'];?> aus <?=$stadt?>
			<input name="name" value="anonym" type="radio"/> Anonym aus <?=$stadt?>
			 <div class="spacer"></div>
			<label></label>
       		<input type="image" src="images/pixel_trans.gif" name="vote" value="Absenden" class="BKR btn_orange_absenden"/>
        </form>
        
        </div>
 
<?php		
			}else{
			?>
		<div>Melden Sie sich an oder erstellen Sie einen Account um eine Bewertung zu schreiben.</div>
			<?php
			}
		}
		//}//
	//}
//}
// BOF Separate Pricing Per Customer
// global variable (session) $sppc_customer_group_id -> local variable customer_group_id

  if(!push_session_is_registered('sppc_customer_group_id')) {
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }

     if ($customer_group_id !='0') {
	$customer_group_price_query = push_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "'");
	  if ($customer_group_price = push_db_fetch_array($customer_group_price_query)) {
	    $product_info['products_price'] = $customer_group_price['customers_group_price'];
	  }
     }
// EOF Separate Pricing Per Customer

  if (push_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);

//*** <Reviews Mod>
  $reviews_query_raw = "select r.reviews_id, r.city, rd.reviews_text, rd.reviews_header, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.approved = '1' order by r.reviews_id desc";
//*** </Reviews Mod>
//  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);
//wird nicht mehr gesplittet!!

    $reviews_query = push_db_query($reviews_query_raw);
    while ($reviews = push_db_fetch_array($reviews_query)) {
?>
                    <div class="main" style="border-top:1px solid #c4c4c4;">
					<p><?php echo '' . sprintf(TEXT_REVIEW_BY, push_output_string_protected($reviews['customers_name'])) . TEXT_FROM . " " .$reviews['city'] ; ?>
                    <span class="datesmall"><?php echo  push_date_short($reviews['date_added']); ?></span></p>
                    <?php 
					push_get_stars_count($reviews['reviews_rating'] );
					echo '<h3>' . stripslashes( push_output_string_protected($reviews['reviews_header'])) . '</h3>';
					echo stripslashes( push_output_string_protected($reviews['reviews_text'])) ; 
					?>
                    </div>
 <?php
    }
?>
</div>