<?php
/*
  $Idä: product_listing.php,v 1.44 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
include(DIR_WS_CLASSES.'pref.php');
$ired= array('10','11','12','16');

//BOF filter add on
if( (isset($add_filter_to)) && (strlen($add_filter_to)>0) ){
	$add_filter_to= '&filter='.$_GET['filter'];
}else{
	$add_filter_to=''; 
}
//EOF filter add on

// EOF Products Description Hack
if ($listing_split->number_of_rows > 0)
{
	$rows = 0;
	$listing_query = push_db_query($listing_split->sql_query);
	$no_of_listings = push_db_num_rows($listing_query);

/*
 ************************************
 * HERE starts the Products Listing	*
 ************************************
 */
?>
<div style="clear:both;height:20px;" class="clearfix"></div>

<div id="productswrapper" class="grid_12 alpha omega">

<?php
$iterator = 1;
while ($listing = push_db_fetch_array($listing_query)) {
?>
	<div  id="list_<?= $listing['products_id'] ?>" class="productListing grid_12 alpha omega"<?= $iterator == $no_of_listings ? ' style="border-bottom: none"' : '' ?>>
	
	<?php
		if ($customer->login) {
			if ($wishList->in_wishlist($listing['products_id'])) {
	?>
				<form class="del-from-sortiment-form" name="wishlist_form" method="post">
					<input type="hidden" value="<?= $listing['products_id'] ?>" name="add_wishprod[]">
					<input style="left: 120px" class="del-from-sortiment-star" type="submit" name="delete_prod_x" value="">
					<div class="sprechblase-red tx_12_15 tx_white">Artikel aus Mein Sortiment entfernen?</div>
				</form>
		<?php 
			} else {
		?>
				<a class="add-to-sortiment-star" href="<?= push_href_link(FILENAME_DEFAULT, push_get_all_get_params()."products_id=" . $listing['products_id'] . "&sortiment=sortiment" ) ?>" style="display: none; left: 120px"></a>
				<div class="sprechblase-green tx_12_15 tx_white">Artikel in Mein Sortiment aufnehmen</div>
		<?php 
			}
		}
	?>
	
<?php
if(!is_object($p)){
	$p = new product;
}
	$p->load_product($listing['products_id']);

?>
<?php 
	echo $p->get_infographics();
	push_product_link_opener($p->products_id, "tx_15_20 tx_blue");
?>
	<div class="image">
		<div class="inner">
			<image src="<?php echo DIR_WS_IMAGES . $p->get_image('listing',140)?>" alt="" />
		</div>
		
	</div>
</a>
	<div class="description">
		<h2 class="tx_15_20 tx_blue"><?php
	push_product_link_opener($p->products_id, "tx_15_20 tx_blue");	
	echo $p->products_name . '</a></h2>' . "\n";
	echo '			<p class="tx_13_20">'.osc_trunc_string(strip_tags(stripslashes($p->products_short_description), ''), PRODUCT_LIST_DESCRIPTION_LENGTH) . "</p>";
		if($p->manufacturers_name <> ''){
			echo  '				<span class="tx_12_15">Ein Produkt von </span><a class="tx_12_15 tx_blue" href="' . push_href_link(FILENAME_BRANDS, 'mid=' . $p->manufacturers_id) . '" ;">' . $p->manufacturers_name . '</a>'. "\n";
		}
?>
</div>
<div class="pricebox" >

<?php
if($customer->login )
{
if($customer->kunde)
{
?>
	<span class="tx_12_15 tx_light_gray">
	<?php echo $p->availability_txt ?>
	</span>
<?php } ?>
<div class="clearfix"></div>
<?php
	if($p->has_ve)
	{
		if($p->ve_left > 0)
		{
			echo '			<span class="tx_left tx_13_20">VE-Preis:</span><span class="tx_right tx_17_20 tx_bold ' . (($p->special)? 'tx_special':'') . '">' . $currencies->format($p->ve_single_price) . '<span class="tx_13_20 tx_normal"> / St.</span></span><br>';
		}
		else
		{
			echo '			<span class="tx_left tx_13_20">VE-Preis:</span><span class="tx_right tx_17_20 tx_bold " style="color:#ccc;">' . $currencies->format($p->ve_single_price) . '<span style="color:#ccc;" class="tx_13_20 tx_normal"> / St.</span></span><br>';
		}
		if($p->products_quantity > 0)
		{
			echo '			<span class="tx_left tx_13_20">Stückpreis:</span><span class="tx_right  tx_17_20 tx_bold ' . (($p->special)? 'tx_special':'') . '">' . $currencies->format($p->final_price) . '<span class="tx_13_20 tx_normal"> / St.</span></span><br>';
		}
		else
		{
			echo '			<span class="tx_left tx_13_20">Stückpreis:</span><span class="tx_right  tx_17_20 tx_bold ' . (($p->special)? 'tx_special':'') . '" style="color:#ccc;">' . $currencies->format($p->final_price) . '<span class="tx_13_20 tx_normal" style="color:#ccc;"> / St.</span></span><br>';
		}
	}
	elseif($p->products_quantity > 0)
	{
		echo '			<span class="tx_left tx_13_20">Stückpreis:</span><span class="tx_right  tx_17_20 tx_bold ' . (($p->special)? 'tx_special':'') . '">' . $currencies->format($p->final_price) . '<span class="tx_13_20 tx_normal"> / St.</span></span><br>';
	}
	else
	{
		echo '			<span class="tx_left tx_13_20">Stückpreis:</span><span class="tx_right  tx_17_20 tx_bold ' . (($p->special)? 'tx_special':'') . '" style="color:#ccc;">' . $currencies->format($p->final_price) . '<span class="tx_13_20 tx_normal" style="color:#ccc;"> / St.</span></span><br>';
	}	
	if($p->products_quantity > 0)
	{
?>
	<a class="fast_buy_button gradientgrey" data-pid="<?=$p->products_id?>"><span></span> Schnellbestellung</a>
<?php
	}
	
	if($p->available == false)
	{
		
			$p->get_notify_button();		
	}
	
}
?>
	</div>
<?php
	
?>
</div>
<?php
if(isset($x) && $x < ($no_of_listings-1))
echo '<div style="clear:both;height:20px;" class="clearfix"></div>';

$iterator++;
    } //for ($x = 0; $x < $no_of_listings; $x++)
?>
</div>
<div class="grid_12 alpha omega" style="text-align: center; height: 10px">
	<img id="ajax-load-more-products" src="images/push/ajax-loader.gif" style="display: none" />
</div>

<script>
	var prod_view = "listing";
</script>

<?php
}
else
{
	?><div style="clear:both"></div>
	<div class="grid_12 tx_13_20">
	<?php
		echo TEXT_NO_PRODUCTS;
	?>
	</div>
	<?php
}
