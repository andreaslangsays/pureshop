<?php 
/**
 * creäte a general headline for the checkout process
 */
//the link is before the current page
$precurrent=true;
$current_site=basename( $_SERVER['PHP_SELF']);
if($current_site==FILENAME_CHECKOUT_SUCCESS){
	$nolink=true;
}else{
	$nolink=false;
}

if($current_site==FILENAME_SHOPPING_CART)
{
$ca_add="bg_blue";
$co_add="bg_grey";
$su_add="bg_grey";
}
elseif($current_site==FILENAME_CHECKOUT_CONFIRMATION)
{
$ca_add="bg_green";
$co_add="bg_blue";
$su_add="bg_grey";
}
elseif($current_site==FILENAME_CHECKOUT_SUCCESS)
{
$ca_add="bg_green";
$co_add="bg_green";
$su_add="bg_green";
}
?>
<div class="cart_head bbox <?=$ca_add?>"> Warenkorb <img src="<?php echo DIR_WS_IMAGES?>assets/white_thing_separator.png" class="separation"/></div>
<div class="confirmation_head bbox <?=$co_add?>">Zusammenfassung<img src="<?php echo DIR_WS_IMAGES?>assets/white_thing_separator.png" class="separation"/></div>
<div class="success_head bbox <?=$su_add?>">Fertig</div>
<div class="clearfix" ></div>
<div class="grid_16" style="height:45px;">&nbsp;</div>
<div class="clearfix"></div>
<?php 
/*

css

*/
?>