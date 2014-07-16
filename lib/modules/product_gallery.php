<?php
/*
  $Idä: product_listing.php,v 1.44 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$igreen= array('1','4');
$iyellow= array('5','6','7','8','9','13','15');
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
	<!-- Begin Listing -->
    
<div id="productswrapper" class="grid_12 alpha omega">
    
<?php
$c=0;
while ($listing = push_db_fetch_array($listing_query)) {
$c++;
$classadd ='';
if($c == 4){
	$classadd="omega ";
	?>
	<?php
	$c=0;
}
if($c==1)
{
	$classadd="alpha ";
}
?>
<div class="gallery grid_3 <?=$classadd?>" >
	<?php 
		if ($customer->login) {
			if ($wishList->in_wishlist($listing['products_id'])) {
		?>
				<form class="del-from-sortiment-form" name="wishlist_form" method="post">
					<input type="hidden" value="<?= $listing['products_id'] ?>" name="add_wishprod[]">
					<input class="del-from-sortiment-star" type="submit" name="delete_prod_x" value="">
					<div class="sprechblase-red tx_12_15 tx_white">Artikel aus Mein Sortiment entfernen?</div>
				</form>
		<?php 
			} else {
		?>
				<a class="add-to-sortiment-star" href="<?= push_href_link(FILENAME_DEFAULT, push_get_all_get_params()."products_id=" . $listing['products_id'] . "&sortiment=sortiment" ) ?>" style="display: none"></a>
				<div class="sprechblase-green tx_12_15 tx_white">Artikel in Mein Sortiment aufnehmen</div>
		<?php 
			}
		}
	?>
<?php
//echo $listing['products_id'];
if(!is_object($p)){
	$p = new product;
}
	$product->load_product($listing['products_id']);
	echo $product->get_infographics();
	push_product_link_opener($product->products_id, "tx_15_20 tx_blue");
	?>
	<div class="image">
		<div class="inner">
			<image src="<?php echo DIR_WS_IMAGES . $product->get_image('gallery',140)?>" alt="" />
		</div>

	</div>
</a>
	<div class="name">
<?php 
	$add_neu = "";
	if ($product->is_new())
	{ 
		$add_neu ='<span class="tx_13_15" style="display:inline;">Neu! </span>';
	}

	push_product_link_opener($listing['products_id']);
	$pname = (str_replace($product->manufacturers_name,'',$product->products_name));
	echo '<span class="tx_blue tx_13_15">' . $add_neu . osc_trunc_string( $product->products_name, 66,' &hellip;') . '</span></a>';
?>
	</div>
	<div class="pricebox" <?php 
	if($product->products_quantity < 1)
		{
			echo ' style="color:#ccc;"';
		}
	?>> <span class="tx_bold tx_17_20 <?= ($product->special)? 'tx_special':'' ?>" <?php 
	if($product->products_quantity < 1)
		{
			echo ' style="color:#ccc;"';
		}
	?>>
<?php
	if($customer->login)
	{
		echo 'Ab ';
		if($product->has_ve && $ve_left > 0)
		{
			echo  $currencies->format($product->ve_single_price);
		}
		else
		{
			echo  $currencies->format($product->final_price);
		}
?></span><span style="font-size: 10px; position: relative; top: -2px<?= $product->products_quantity < 1 ? '; color: #ccc' : '' ?>"> /</span><span class="tx_13_20"<?= $product->products_quantity < 1 ? ' style="color: #ccc"' : '' ?>> St.</span>
	<span class="tx_12_15 tx_light_gray prod-avail-cont" style="display:block; margin: 2px 0 3px 0; height: 15px">
		<?php if($customer->kunde) { ?>
			<span class="prod-avail"><?php echo $product->availability_txt ?></span>
				<?php if($product->available == false)
					{
							$product->get_notify_button();
					}

		} ?> &nbsp;
	</span>
</div>

<?php
		if($product->products_quantity > 0)
		{
		?>
			<a class="fastbuy fast_buy_button gradientgrey" data-pid="<?=$product->products_id?>"><span></span>Schnellbestellung</a>
<?php
		}

	}
	else
	{
?>
</div>
<?php
	}
?>

</div>	

<?php
	
	} //for ($x = 0; $x < $no_of_listings; $x++)
?>
</div>
<div class="grid_12 alpha omega" style="text-align: center; height: 10px">
	<img id="ajax-load-more-products" src="images/push/ajax-loader.gif" style="display: none" />
</div>

<script>
	var prod_view = "gallery";
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
