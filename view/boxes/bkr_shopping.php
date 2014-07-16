<div id="cartBox">
	<!--
	<?php 

	?>
	-->
	<div style="color:#ffffff;padding-left:25px;background-image:url('images/newbkr/wishlist.png');background-repeat:no-repeat;background-position:0px 4px">
	<?php if($wishList->count_wishlist() > 0){?>
	<a style="font-weight:bold;color:#ffffff;"  href="<?= push_href_link(FILENAME_WISHLIST)?>"><?=TEXT_HEADING_WISHLIST?></a><br>
	<?php }else{?>
	<span style="font-weight:bold;color:#ffffff;"><?=TEXT_HEADING_WISHLIST?></span><br>
	<?php }?>
	<?=$wishList->count_wishlist()?> <?=TEXT_PRODUCTS?><br>
	</div>
	<?php  ?>
	<div style="color:#ffffff;padding-left:25px;background-image:url('images/newbkr/cartlist.png');background-repeat:no-repeat;background-position:0px 4px">
	<?php
	?>
	<?php
	$count=0;
	if($cart->count_contents() > -1){
	 $count = $cart->get_products();
	 $count =sizeof($count);
		if(( basename( $_SERVER['PHP_SELF'] ) <> "shopping_cart.php")){
			?><a style="font-weight:bold;color:#ffffff;" href="<?= push_href_link(FILENAME_SHOPPING_CART)?>" id="shoppingcart"><?= BOX_HEADING_SHOPPING_CART ?></a> <?php
		}else{
			?><span style="font-weight:bold;color:#ffffff;" href="<?= push_href_link(FILENAME_SHOPPING_CART)?>"><?= BOX_HEADING_SHOPPING_CART ?></span> <?php
		}
	}
	else
	{
	?><span style="font-weight:bold;color:#ffffff;" href="<?= push_href_link(FILENAME_SHOPPING_CART)?>"><?= BOX_HEADING_SHOPPING_CART ?></span> <?php
	}
	?>	<div id="cartstring" style="color:#ffffff;">
	<?=$count?> <?=TEXT_PRODUCTS?><br>
	<?php 
	echo $currencies->display_price($cart->show_total()) ;
//	else
//	echo $currencies->display_price(0). '<!--B-->';
	?>
		</div>
	</div>
</div>
<?php 
  if ($cart->count_contents() > -1) {
?>

<div id="cart_preview" <?php
if (isset($new_products_id_in_cart) && ( basename( $_SERVER['PHP_SELF'] ) <> "shopping_cart.php")) {
echo ' style="display:block"';
}
?>>
 <?php
    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $attributes = push_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
									   and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
									   and poval.language_id = '" . (int)$languages_id . "'");

          $attributes_values = push_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }
if(sizeof($products) ==0){
?>
	   <h3 style="color:#669900;margin-top:5px;margin-bottom:0px;">Ihr Warenkorb enthält noch keine Produkte</h3>
<?php
	echo '<div id="cart_empty" class="tinycartlist" style="height:6px;margin-top:0px;padding:0;border-bottom:1px solid #ccc;padding-bottom:8px;margin-bottom:5px;">&nbsp;</div>';

}
//show only the last 4 Entries
$restrict_lenght=3;
    for ($i=sizeof($products) - 1; $i> sizeof($products) - (1 +$restrict_lenght) ; $i--) {
	if($i < 0)
		break;
	if (($new_products_id_in_cart == $products[$i]['id'])&& ( basename( $_SERVER['PHP_SELF'] ) <> "shopping_cart.php")) {
       ?>
	   <h3 style="color:#669900;margin-top:5px;margin-bottom:10px;"><?=TEXT_NEW_PRODUCT_ADDED_TO_CART?></h3>
<?php 	echo '<div id="cart_' . $i . '" class="tinycartlist" style="border-bottom:1px solid #ccc;padding-bottom:5px;margin-bottom:5px;">';

     }else{ 	echo '<div id="cart_' . $i . '" class="tinycartlist">';

	 }
	 if(($products[$i]['image'] =='') || ($products[$i]['image'] =='bilder/noch_kein_bild.png')){
		$image='bilder/' . get_categories_default_image($products[$i]['id']);
	}else{
		$image = $products[$i]['image'] ;
	}
	//generate smaller copies of small files 
  		if(!file_exists(DIR_WS_IMAGES .'tiny/'. $image)){
			push_copy_image(DIR_WS_IMAGES . 'small/' . $image, DIR_WS_IMAGES .'tiny/'. $image, 32, 32);
		}
		
		$isCcbMix = false;
		if (strpos($products[$i]['model'], "ccb_") !== false) {
			$isCcbMix = true;
			$prodID = (stripos($products[$i]['id'], '{') === false ? $products[$i]['id'] : substr($products[$i]['id'], 0, stripos($products[$i]['id'], '{')));
		}
		
      $products_name = '' .
                       ' 	<div class="tinyimage"><a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '" >' . push_image(DIR_WS_IMAGES .'tiny/' . $image, $products[$i]['name']) . '</a></div>' .
                       '    <a href="' . ($isCcbMix ? (push_href_link(FILENAME_CCB, 'show=' . $prodID) . '#userBlends') : (push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']))) . '" style="font-size:11px;display:inline-block;height:30px;width:180px;line-height:15px;vertical-align:top;margin-top:4px;margin-left:4px;">' . $products[$i]['name'] . '</a>';

      if (STOCK_CHECK == 'true') {
        $stock_check = push_check_stock($products[$i]['id'], $products[$i]['quantity']);
        if (push_not_null($stock_check)) {
          $any_out_of_stock = 1;

         // $products_name .= $stock_check;
        }
      }

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
       //   $products_name .= '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }

      $products_name .= '  ';

      echo  $products_name;

	  //echo  push_draw_hidden_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') .$products[$i]['quantity']. push_draw_hidden_field('products_id[]', $products[$i]['id']);


      echo '<span id="price" style="display:inline-block;font-size:11px;height:30px;width:90px;line-height:15px;vertical-align:top;margin-top:4px;text-align:right;">'. $currencies->display_price($products[$i]['final_price'], push_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</span>';
	  echo '</div>';
	  }
?>
<script type="text/javascript">
	
</script>

<?php
if($i > -1){
	$left=$i+1;
	echo '<a href="' . push_href_link(FILENAME_SHOPPING_CART) . '" style="font-size:13px;margin-left:45px;padding-top:4px;vertical-align:bottom;display:inline-block;">+ ' . $left . ' ' .TEXT_PRODUCTS .'</a>';
}
?>

<a id="tocart" href="<?=push_href_link(FILENAME_SHOPPING_CART)?>" style="display:block;border:1px solid #ccc;background:url('images/newbkr/tinybutton.png'); background-repeat:repeat-x;padding:2px 10px 2px 10px;float:right;white-space:nowrap;"><?=BUTTON_TO_SHOPPING_CART?></a> 
<?php
echo '</div>';
}
if (isset($new_products_id_in_cart)&& ( basename( $_SERVER['PHP_SELF'] ) <> "shopping_cart.php")) {
?>
<script type="text/javascript">
$(document).ready(function(){
	//$('#cart_preview').slideToggle();
	carttimeout=setTimeout(function(){$('#cart_preview').slideToggle(800);},2000 );
	
});
	   </script>
	<?php
	      push_session_unregister('new_products_id_in_cart');
		  }	
?>
