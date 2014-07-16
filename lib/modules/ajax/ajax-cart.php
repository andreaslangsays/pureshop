<?php
/**
 *
 * ajax-cart.php 
 * Überlegt
 */
//@TODO: insert a noticication Value for free-products-code
//@TODO ALSO: use this for other notifications
//redirect to shop if cart empty 
chdir('../../../');
require('includes/ajax_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
$notice="";
//freeproductscode (GROUPON)
	if(isset($fpd)){
	$notice =" Folgende Gutscheincodes wurden freigegeben: ". $fpd;
 		push_session_unregister('fpd');
 		unset($fpd);
}      //EOF freeproductscode
$error=0;
if(defined('ONSH')){
####################	TORTENSHOP
if(isset($_POST['remove'])){ //REMOVE
		$tcart->remove($_POST['products_id']);
		if($tcart->count_contents() > 0){
			if(($tcart->show_total() > MIN_ORDER_AMOUNT +20 )&&($tcart->show_total() < 100) ){
				$notice ='Wenn Sie noch Waren im Wert von ' . $currencies->format(100 - $tcart->show_total()) . ' bestellen sparen Sie die Versandgeb&uuml;hren in H&ouml;he von 10.00 EUR.';
				$error=0;
			}elseif ($tcart->show_total() < MIN_ORDER_AMOUNT ) {
				$notice = sprintf(TEXT_ORDER_UNDER_MIN_AMOUNT, $currencies->format(MIN_ORDER_AMOUNT)) ;
				$error = 1;
			}else{
				$error=0;
				$notice='';
			}
			//$notice=strip_tags($notice);
			$count = $tcart->get_products();
			$count =sizeof($count);
			
			echo  '{"row": "'. $_POST['row'] . '", ';
			echo ' "notice": "' .  $notice . '", ';
			echo ' "totalvalue": "' .  $currencies->format($tcart->show_total()) . '", ';
			echo ' "cartstring": "'. $count .' Artikel <br>' . $currencies->format($tcart->show_total()) . '", ';
			echo ' "notice": "' .  $notice . '", ';
			
			echo '"error": "' . $error . '" }';
		}else{
			echo '{"row":"'. $_POST['row'] . '",';
			echo ' "notice": "' .  $notice . '", ';
			echo '"totalvalue":"0 EUR", "cartstring":"0 Artikel<br>0,00 EUR", "error": "noproducts"}';
		}
	}elseif(isset($_POST['update'])){ //UPDATE
//IDENTIFY Number
			$i=0;
			$products=$tcart->get_products();
		do{
				if( (int)$_POST['products_id'] == $products[$i]['id'])
					break;
				$i++; 
			}while($i<$n=sizeof($products));

		$attr='';
			$p=$_POST['products_id'];
			while(strpos($p, "{")){
				$p = substr($p, strpos($p, "{") +1); 
				$key = substr($p,0,strpos($p,"}"));
				$p = substr($p, strpos($p, "}") + 1);
				if(strpos($p,"{")){
					$value=substr($p,0,strpos($p,"{"));
				}else{
					$value=$p;
				}
				$attr[$key]=$value;
			}
//SET NEW Quantity
			$tcart->update_quantity($_POST['products_id'], $_POST['amount'], $attr);
						$i=0;
			$products=$tcart->get_products();
	       	do{
				if($_POST['products_id']==$products[$i]['id'])
					break;
				$i++; 
			}while($i<$n=sizeof($products));
//GET NEW Price
		    $newprice = $currencies->display_price($products[$i]['final_price'], push_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']);
		if($tcart->count_contents() > 0){
			if(($tcart->show_total() > MIN_ORDER_AMOUNT +20)&&($tcart->show_total() < 100) ){
				$notice ='Wenn Sie noch Waren im Wert von ' . $currencies->format(100 - $tcart->show_total()) . ' bestellen sparen Sie die Versandgeb&uuml;hren in H&ouml;he von min. 10.00 EUR.';
				$error=0;
			}elseif ($tcart->show_total() < MIN_ORDER_AMOUNT ) {
				$notice = sprintf(TEXT_ORDER_UNDER_MIN_AMOUNT, $currencies->format(MIN_ORDER_AMOUNT)) ;
				$error = 1;
			}else{
				$error=0;
				$notice='';
			}
			$count = $tcart->get_products();
			$count =sizeof($count);
			echo '{"row": "'. $_POST['row'] . '", ';
			echo '"newprice" : "' . $newprice . '","';
			echo 'newquantity" : "' . $tcart->get_quantity($_POST['products_id']) . '",';
			echo '"totalvalue": "' . $currencies->format($tcart->show_total()) . '", ';
			echo '"attributes": "';
			echo $attr;
			echo '", ';
			echo ' "notice": "' . $notice . '", ';
			echo '"cartstring": "'. $count .' Artikel <br>' . $currencies->format($tcart->show_total()) . '", ';
			echo '"error": "' . $error . '" }';
	}
}
}else{
########################################################################
#############	NORMAL SHOP
if(isset($_POST['remove'])){ // REMOVE
	
		$cart->remove($_POST['products_id']);
		if($cart->count_contents() > 0){
			$count = $cart->get_products();
			$count =sizeof($count);
			echo  '{"row": "'. $_POST['row'] . '", ';
			echo ' "notice": "' .  $notice . '", ';
			echo ' "totalvalue": "' .  $currencies->format($cart->show_total()) . '", ';
			echo ' "cartstring": "'. $count .' Artikel <br>' . $currencies->format($cart->show_total()) . '", ';
			echo '"error": "0" }';
		}else{
			echo '{"row":"'. $_POST['row'] . '",';
			echo ' "notice": "' .  $notice . '", ';
			echo '"totalvalue":"0 EUR", "cartstring":"0 Artikel<br>0,00 EUR", "error": "noproducts"}';
		}
	}elseif(isset($_POST['update'])){ //UPDATE############################################################################
//IDENTIFY Number
			$i=0;
			$products=$cart->get_products();
		do{
				if( (int)$_POST['products_id'] == $products[$i]['id'])
					break;
				$i++; 
			}while($i<$n=sizeof($products));

//TRY TO SAVE Attributes
/*
			if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
				while (list($option, $value) = each($products[$i]['attributes'])) {
				  //echo push_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
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

			if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
			reset($products[$i]['attributes']);
			$attr='';			
			while (list($option, $value) = each($products[$i]['attributes'])) {
				  $attr .= $products[$i][$option]['products_options_name'] . ': ' . $products[$i][$option]['products_options_values_name'] . '<br>';
			}
*/
			$attr='';
			$p=$_POST['products_id'];
			while(strpos($p, "{")){
				$p = substr($p, strpos($p, "{") +1); 
				$key = substr($p,0,strpos($p,"}"));
				$p = substr($p, strpos($p, "}") + 1);
				if(strpos($p,"{")){
					$value=substr($p,0,strpos($p,"{"));
				}else{
					$value=$p;
				}
				$attr[$key]=$value;
			}
//SET NEW Quantity
			$cart->update_quantity($_POST['products_id'], $_POST['amount'], $attr);
						$i=0;
			$products=$cart->get_products();
	       	do{
				if($_POST['products_id']==$products[$i]['id'])
					break;
				$i++; 
			}while($i<$n=sizeof($products));
//GET NEW Price
		    $newprice = $currencies->display_price($products[$i]['final_price'], push_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']);
			if(($cart->show_total() > 40)&&($cart->show_total() < 49) )
				$notice='Wenn Sie noch Waren im Wert von ' . $currencies->format(49 - $cart->show_total()) . ' bestellen sparen Sie sich die Versandgeb&uuml;hren in H&ouml;he von 3.95 EUR.' ;
			else
				$notice='';
			
			$count = $cart->get_products();
			$count =sizeof($count);
			echo '{"row": "'. $_POST['row'] . '", ';
			echo '"newprice" : "' . $newprice . '","';
			echo 'newquantity" : "' . $cart->get_quantity($_POST['products_id']) . '",';
			echo '"totalvalue": "' . $currencies->format($cart->show_total()) . '", ';
			echo '"attributes": "';
			echo $attr;
			echo '", ';
			echo ' "notice": "' . $notice . '", ';
			echo '"cartstring": "'. $count .' Artikel <br>' . $currencies->format($cart->show_total()) . '", ';
			echo '"error": "0" }';
	}

}
?>