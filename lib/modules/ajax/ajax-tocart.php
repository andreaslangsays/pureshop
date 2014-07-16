<?php
/**
 * KRös / AL
 *
 */
chdir('../../../');
include('includes/ajax_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO); 
if(isset($_GET['products_id'])) {
	$products_id=(int)$_GET['products_id'];
	$p->load_product($products_id);
?>
	<div style="width: 420px; margin: 20px; background-color:#fff;overflow:hidden;">
<?php
$cart->calculate();
//$product_info['products_quantity'] = $p->products_quantity - $cart->get_quantity($products_id,true) ;
//echo "$". $product_info['products_quantity']  . "§";
?>
<div id="ajax-pricebox" style=" margin: 20px 20px 0 0 ;">
<img class="close-info-popup" src="images/push/close-info-popup.gif" />
	<h2 class="ProductHeading" style="padding-top: 10px;margin-left:20px;"><?php echo $p->products_name ?></h2>

		<div style="position:absolute;right:100px;border-left:1px solid #ccc;height:500px;"></div>
	<div class="formularbox unselectable" style="padding:20px; border-top:1px #ccc solid;">

<?php
	if($p->has_ve)
	{
	##############################################################################
		$ve_price = $currencies->format_clean($p->ve_end_price);
		$ve_price_string =  $currencies->format($p->ve_end_price) . " <span class='tx_15_30' style='bottom:1px;display:inline-block;position:relative;'>/</span> VE";
		$ve_info  = "1 VE = <span class='tx_17_30'>" .  $p->ve_multiplier . "</span> Stück";
	
		echo '			<form name="buy_now_' . $p->products_id . '" method="post" style="width:300px;overflow:visible; position: relative">';
		echo '				<img src="images/push/icons/cart-ve.png" style="position: absolute; top: 55px; left: 333px" />';
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
			if($p->ve_left > 99)
			{
				$max = 99;
			}
			else
			{
				$max = $p->ve_left;
			}
		?>	<br />
			<b>-</b><input type="text" name="cart_quantity" value="1" maxlength="3" data-max="<?= $max ?>" size="2"><b>+</b> <div class="buysubmit-cont"><img src="images/push/icons/ico_cart-ve_white.png" onclick="$('form[name=buy_now_<?= $p->products_id ?>]').submit()" /><?php echo '<input type="submit"  value="In den Warenkorb"  name="' . $p->products_name  . '" class="darkblue buysubmit" style="">' . "\n"; ?></div><br />
			<span class="tx_13_15 tx_pink">Sie sparen <?php echo $currencies->format( $p->ve_difference) ?> /St. </span> <span class="tx_12_15">gegenüber dem Einzelkauf</span><br /><br />
	<?php
		}
		else
		{
			//no VE left!!
		echo '<div style="height: 80px; text-align: center">
											<img src="./images/push/product-not-available.png"><br>
											<div class="tx_light_gray tx_12_15">VE nicht verfügbar</div>
										</div><br>';
		}
		?>
		</form>
		<div class="clearfix" style="border-bottom:1px dotted #ccc;height:0px;width:300px;margin-bottom:20px;"></div>
<?php
	}

	if((!($p->has_ve && $_SESSION['customer_only_ve'] != 0 )) || ($_SESSION['customer_only_ve']==0))
	{
	
	?>
	<form id="prodConfForm" method="post" name="cart_quantity"  style="width:300px; position: relative">
		<img src="images/push/icons/cart-pcs.png" style="position: absolute; top: 30px; left: 333px" />
	<span class='tx_25_30' ><?php echo $currencies->display_price($p->final_price); ?></span> 
	<span class='tx_15_30' style='bottom:1px;display:inline-block;position:relative;'>/</span>
	<span class='tx_25_30' >Stück</span>
	<?php
		echo '			' . push_draw_hidden_field('products_id', $p->products_id);
	
	if ( $p->products_quantity > 0 )
	{


		if($p->has_ve && ($p->ve_multiplier < $p->display_quantity))
		{
			$max = ($p->ve_multiplier -1);
		}
		else
		{
			$max = intval($p->display_quantity) ;
		}
		if($max > 0)
		{
			echo '			<br>
						<b>-</b><input type="text" name="cart_quantity" value="1" maxlength="3" size="2" data-max="' . $max .'"   /><b>+</b> '."\n";
			echo '			<div class="buysubmit-cont"><img src="images/push/icons/ico_cart-pcs_white.png" onclick="$(\'form[name=cart_quantity]\').submit()" /><input type="submit" name="' . $product_info['products_name']  . '" value="In den Warenkorb" class="darkblue buysubmit"></div>' . "\n";
		//					echo  push_image_submit('button-buy-now.png',  $product_info['products_name'] ,'class="buysubmit"') ."\n";//'push_draw_input_field('buynow', TEXT_NOW, 'class="buttontest"','submit') ;
			if($p->has_ve)
			{				
				echo ' 			<br><span class="tx_13_20">Ab <span class="tx_17_20">' . $p->ve_multiplier . "</span> Stück bitte <span class='tx_17_20'>VE</span> bestellen</span>";
			}
		}
//echo ' <img id="pricesumImg" src="images/pixel_trans.gif" style="margin-left:2px;" class="BKR pfeil_orange_indikator"><span id="pricesum" style="padding-left:10px;font-weight:bold;">' . number_format($pbarr[1], 2, ',', ' ') . '</span>&nbsp;<span style="font-weight:bold;" id="pricesumEur">EUR</span>';
		}
echo "</form>";
	}
}
?>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#ajax-pricebox b').addClass('gradientlight');
	$('#ajax-pricebox b').click(function(ev){
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

	$("#ajax-pricebox input[type='text']").focusout(function(ev){
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
	$('#semitransparent').click(function(ev){
		$('#ajax-pricebox').parent().parent().hide();
		$('#semitransparent').hide();
	});
	$('#ajax-pricebox .close-info-popup').click(function(ev){
		$('#ajax-pricebox').parent().parent().hide();
		$('#semitransparent').hide();
	});
});

</script>


<?php

include('includes/ajax_bottom.php');
?>
