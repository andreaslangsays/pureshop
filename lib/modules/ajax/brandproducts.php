<?php 
chdir('../../../');
include('includes/ajax_top.php');
//get gallery-images - 5 pcs - ajax?
$brand_id = $_GET['bid'];
if(isset($_GET['p']))
{
	$page=intval($_GET['p']);
}
else
{
	$page=5;
}
//Fallunterscheidung wenn Torani! //HARDCODED
if($brand_id == 10302)
{
	$zahl = push_db_fetch_array(push_db_query("SELECT COUNT(products_id) as anz FROM products WHERE (manufacturers_id ='" . $brand_id . "' OR manufacturers_id = '1040201') AND products_status = 1 "));//	1040201
}
else
{
	$zahl = push_db_fetch_array(push_db_query("SELECT COUNT(products_id) as anz FROM products WHERE manufacturers_id ='" . $brand_id . "' AND products_status = 1 "));
}
//Fallunterscheidung wenn Torani! //HARDCODED
if($brand_id == 10302)
{
	$pq = push_db_query("SELECT products_id FROM products WHERE (manufacturers_id ='" . $brand_id . "' OR manufacturers_id = '1040201')  AND products_status = 1 ORDER BY products_ordered DESC LIMIT " . ($page - 5) . ", 5;");
}
else
{
	$pq = push_db_query("SELECT products_id FROM products WHERE manufacturers_id ='" . $brand_id . "' AND products_status = 1 ORDER BY products_ordered DESC LIMIT " . ($page - 5) . ", 5;");
}
$z=push_db_num_rows($pq);
?>
<div style="position:absolute;top:-34px;right:10px;font-size:12px;">
<strong><?=$page-4?>-<?=($page > $zahl['anz']) ? $zahl['anz']: $page ?></strong> von <strong><?=$zahl['anz']?></strong> Produkten
</div>
<?php
while($pqr = push_db_fetch_array($pq))
{
	$product->load_product($pqr['products_id']);
	echo "<div class='bgallery grid_3' style='text-align:center; position:relative;'>";
	push_product_link_opener($product->products_id, "tx_13_15 tx_blue");
	echo $product->get_infographics();
?>
	<div style="line-height:140px">
		<image src="<?php echo DIR_WS_IMAGES . $product->get_image('gallery',140)?>" alt="" style="vertical-align: middle;"/>
	</div>
	<div class="name">
<?php
	$add_neu = "";
	if ($product->is_new())
	{
		$add_neu ='<span class="tx_13_15" style="display:inline;">Neu! </span>';
	}
	$pname = (str_replace($product->manufacturers_name,'',$product->products_name));
	echo '<span class="tx_blue tx_13_15 tx_left">' . $add_neu . osc_trunc_string( $product->products_name, 66,' &hellip;') . '</span></a>';
?>
	</div>
<?php
	if($customer->login)
	{
		//Preise
?>
<div class="pricebox" <?php 
	
		if($product->products_quantity < 1)
		{
			echo ' style="color:#ccc;"';
		}
		?>> <span class="tx_bold tx_17_20 <?= ($product->special)? 'tx_red':'' ?>" <?php 
		if($product->products_quantity < 1)
		{
			echo ' style="color:#ccc;"';
		}
		?>>
		<?php
		echo 'Ab ';
		if($product->has_ve && $ve_left > 0)
		{
			echo  $currencies->format($product->ve_single_price);
		}
		else
		{
			echo  $currencies->format($product->final_price);
		}
		?></span>
		<?php
		if(false)
		{
				?>
			<span style="font-size: 10px; position: relative; top: -2px<?= $product->products_quantity < 1 ? '; color: #ccc' : '' ?>"> /</span><span class="tx_13_20"<?= $product->products_quantity < 1 ? ' style="color: #ccc"' : '' ?>> St.</span>
			<span class="tx_12_15 tx_light_gray prod-avail-cont" style="display:block; margin: 2px 0 3px 0; height: 15px">
			<?php if($customer->kunde) { ?>
			<span class="prod-avail"><?php echo $product->availability_txt ?></span>
				<?php if($product->available == false)
					{
							//$product->get_notify_button();
					}
	
			}
		}?> &nbsp;
	</span>
</div>
<?php

	
	}
	echo "</div>";	
}
?>
<div class="grid_15" style="height:20px;border-bottom:1px dotted #ccc;">&nbsp;</div>
<div class="clearfix" style=""></div>
<div style="position:relative;float:right;width:130px;margin-right:10px;">
<?php
if($page >5)
{
?>
<span class="brandprev" data-page="<?= ($page - 5) ?>"><?=($page - 9)?>-<?=($page - 5)?></span>
<?php
}

if( ceil($zahl['anz']/5) >= ceil(($page+5)/5))
{
?>
<span class="brandnext"  data-page="<?= ($page + 5) ?>"><?=($page + 1)?>-<?=($zahl['anz'] < ($page+5))?$zahl['anz']:($page + 5)?> </span>
<?php	
}

?> 
</div>
