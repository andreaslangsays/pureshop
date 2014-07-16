<?php
chdir('../../../');
require('includes/ajax_top.php');
$product->load_product($_GET['aid']);
$is_bonus_product=false;
foreach($discount->product_discounts as $test){
	if($test['product'] == $_GET['aid'])
	{
		$is_bonus_product=true;
		$arbeit=$test;
	}
}
if(!$is_bonus_product)
{
	$arbeit=$discount->get_products_discounts_product($product->products_id);	
}
if(!isset($_GET['list'])&& !isset($_GET['match']))
{
	if(!$is_bonus_product)
	{
		if($arbeit['valid'])
		{
	?>
	
			<div class="bbox grid_6 unselectable" style="margin-bottom:-1px;border:1px solid #ccc;padding:10px;position:relative;">
			<div style="margin-bottom:10px;width: 300px; height: 25px; padding: 5px 0 0 15px; background: url('images/push/pink-grid-bg-small.png') no-repeat"><b>Glückwunsch, Bonus gesichert!</b> <img src="images/assets/ico_bonus-present_black_L.png" style="margin-top:-6px;display:block;float:right;"></div>
			<div>
			<?php
			$bonus = new product;
			$bonus->load_product($arbeit['product']);
			$bbild = $bonus->get_image('bonus',60);
			//var_dump($arbeit);
			?>
			<img src="images/<?=$bbild?>" style="float:left;margin-right:10px;">
			<span class="tx_pink tx_13_20">Kostenlos für Sie</span><br />
			<img src="images/assets/ico_discount_true-s.png"> <a href="<?=push_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$bonus->products_id)?>" class="tx_blue"><?=$bonus->products_name?></a>
			</div>
			<div style="clear:both;margin-top:30px;">
			<a id="plist" class="button gradientgrey tx_12_15" style="width:120px;">Infos anzeigen <img src="images/assets/ico_arrow-fw_S_.png" style="margin-top:10px;padding-right:10px;float:right;"></a>
			</div>
			
			</div>
	<?php
		}
		else
		{
	?>
	
			<div class="bbox grid_6 unselectable" style="margin-bottom:-1px;border:1px solid #ccc;padding:10px;position:relative;">
			<div style="margin-bottom:10px;width: 300px; height: 25px; padding: 5px 0 0 15px; background: url('images/push/pink-grid-bg-small.png') no-repeat"><b>Bonus</b> erhältlich!<img src="images/assets/ico_bonus-present_black_L.png" style="margin-top:-6px;display:block;float:right;"></div>
			<div>
			<?php
			$bonus = new product;
			$bonus->load_product($arbeit['product']);
			$bbild = $bonus->get_image('bonus',60);
			//var_dump($arbeit);
			?>
			<img src="images/<?=$bbild?>" style="float:left;margin-right:10px;">
			<span class="tx_pink tx_13_20">Kostenlos für Sie</span><br />
			<a href="<?=push_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$bonus->products_id)?>" class="tx_blue"><?=$bonus->products_name?></a>
			</div>
			<?php
			if(isset($_SESSION['bonusoff']) && is_array($_SESSION['bonusoff']))
			{
				if(in_array($arbeit['product'],$_SESSION['bonusoff']))
				{
					?>
					<div style="clear:both;margin-top:30px;">
					Der Bonus ist bereits für Sie verfügbar.
					<a class="button gradientgrey tx_12_15" href="<?= push_href_link(FILENAME_PRODUCT_INFO , 'products_id=' . $_GET['aid'] . "&bonuson=true" ) ?>" style="color:#ff00ff;border-color:#ff00ff;width:120px;margin-top:10px;"> Bonus abholen </a>
					</div>	
					<?php
				}
			}
			?>
			<div style="clear:both;margin-top:30px;">
			<a id="plist" class="button gradientgrey tx_12_15" style="color:#ff00ff;border-color:#ff00ff;width:120px;">Infos anzeigen <img src="images/assets/ico_arrow_S_pink.png" style="margin-top:10px;padding-right:10px;float:right;"></a>
			</div>
			
			
			</div>
	<?php
			
		}
	}
	else
	{
		if($arbeit['valid'])
		{
		?>
			<div class="bbox grid_6 unselectable" style="margin-bottom:-1px;border:1px solid #ccc;padding:10px;position:relative;">
				<div style="margin-bottom:10px;width: 300px; height: 25px; padding: 5px 0 0 15px; background: url('images/push/pink-grid-bg-small.png') no-repeat"> <b>Glückwunsch, gratis gesichert!</b><img src="images/assets/ico_bonus-present_black_L.png" style="margin-top:-6px;display:block;float:right;"></div>
				<div style="clear:both;margin-top:10px;">
				<a id="plist" class="button gradientgrey tx_12_15" style="width:120px;">Infos anzeigen <img src="images/assets/ico_arrow-fw_S_.png" style="margin-top:10px;padding-right:10px;float:right;"></a>
				</div>
			</div>
		<?php
		}
		else
		{
		?>
			<div class="bbox grid_6 unselectable" style="margin-bottom:-1px;border:1px solid #ccc;padding:10px;position:relative;">
				<div style="margin-bottom:10px;width: 300px; height: 25px; padding: 5px 0 0 15px; background: url('images/push/pink-grid-bg-small.png') no-repeat">Dieser Artikel ist <b>gratis erhältlich!</b><img src="images/assets/ico_bonus-present_black_L.png" style="margin-top:-6px;display:block;float:right;"></div>
				<?php
				if(isset($_SESSION['bonusoff']) && is_array($_SESSION['bonusoff']))
				{
					if(in_array($_GET['aid'],$_SESSION['bonusoff']))
					{
						?>
						<div style="clear:both;margin-top:10px;">
						Der Bonus ist bereits für Sie verfügbar.
						<a class="button gradientgrey tx_12_15" href="<?= push_href_link(FILENAME_PRODUCT_INFO , 'products_id=' . $_GET['aid'] . "&bonuson=true" ) ?>" style="color:#ff00ff;border-color:#ff00ff;width:120px;margin-top:10px;"> Bonus abholen </a>
						</div>	
						<?php
					}
				}
				?>
				<div style="clear:both;margin-top:10px;">
				<a id="plist" class="button gradientgrey tx_12_15" style="color:#ff00ff;border-color:#ff00ff;width:120px;">Infos anzeigen <img src="images/assets/ico_arrow_S_pink.png" style="margin-top:10px;padding-right:10px;float:right;"></a>
				</div>
			</div>
		<?php
		}
	}
}
elseif(isset($_GET['list']))
{
	?>
	<div class="bbox grid_6 unselectable" style="margin-bottom:-1px;border:1px solid #ff00ff;padding:10px;position:absolute;background:#ffffff;z-index:12;padding-bottom:40px;">
		<div style="width: 300px; height: 25px; padding: 5px 0 0 15px; background: url('images/push/pink-grid-bg-small.png') no-repeat"><?php 
		if($arbeit['valid'])
		{
			?><b>Glückwunsch, Bonus gesichert!</b><?php 
		}
		else
		{
			?><b>Bonus!</b> So geht's:<?php 
		}
		?><img src="images/assets/ico_bonus-present_black_L.png" style="margin-top:-6px;display:block;float:right;"></div>
		<div class="tx_15_20" style="margin: 10px 0;"><?=$arbeit['description']?></div>
		<div>
		<?php
			$bonus = new product;
			$bonus->load_product($arbeit['product']);
			$bbild = $bonus->get_image('bonusbig',80);
		?>
		<img src="images/<?=$bbild?>" style="float:left;margin-right:10px;">
		<a href="<?=push_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$bonus->products_id)?>" class="tx_blue tx_15_20"><?=$bonus->products_name?></a>
		<br><?php if($arbeit['valid']){?><img src="images/assets/ico_discount_true-L.png"><?php } ?><br>
		</div>
		<h2 class="tx_25_30" style="clear:both;margin:50px 0 20px 0;">Bestellen Sie mindestens <span class="tx_25_30 tx_blue"><?=$arbeit['min']?></span> dieser <?=(int)count($arbeit['enabled'])?> Artikel:</h2>
		<div id="text">
		<?php
		$anz=count($arbeit['enabled']);
		$pi=1;
		foreach($arbeit['enabled'] as $bpr)
		{
			$product->load_product($bpr);
			$mimage = $product->products_image;
			
		?>
		<div style="float:left;">
		<div class="bbox" style="margin-top:4px;text-align:center;width:60px; height:60px;<?=( $y>1 && 5/$y == round(5/$y) )? '':'margin-right:10px;' ?>border:1px solid #ccc;line-height:60px;margin-bottom:10px;overflow:hidden;">
			<a href="<?php echo push_href_link(DIR_WS_IMAGES . 'fullsize/' . $mimage); ?>" class="imgbop"  style="line-height:60px;"><img src="imagethumb.php?s=images/fullsize/<?=$mimage?>&h=50" style="vertical-align:middle"></a>
		</div>
		
		<?php
		if($customer->login)
		{
			
			if($cart->in_cart($bpr))
			{
				?><div class=" tx_green tx_17_20" style="color: #99CC00;font-weight:bolder;margin-bottom:10px;margin-top:-5px;margin-left:5px;"><?=$cart->get_quantity($bpr)?> <div style="width: 20px; height: 20px; display: inline-block; position: relative; top: 4px; background-color: #99CC00"></div></div><?php	
			}
		}
			?>
		</div>
		<div style="float:right;width:220px;margin-bottom:20px;">
			<a href="<?=push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product->products_id)?>" class="tx_15_20 tx_blue"><?=$product->products_name?></a>
		<?php
		if($customer->login)
		{
		?>
			<a class="fast_buy_button gradientgrey" data-pid="<?=$product->products_id?>" style="margin-top:10px;"><span></span> Schnellbestellung</a>
		<?php
		}
		?>
				</div>
			<?php
			if($pi<$anz)
			{
			?>
				<div class="clearfix"></div>
				<div style="clear:both;border-bottom:dotted 1px #bbbbbb;margin:20px 0;"></div>
			<?php
			}
			$pi++;
		}
		?>
		</div>
		<a id="clist" class="button gradientgrey tx_12_15" style="border-color:#ff00ff;color:#ff00ff;position:absolute;bottom:-1px;left:-1px;width:320px;text-align:center;">
			Schliessen <img src="images/assets/ico_pink_x_S.png" style="float:right;margin-top:11px;margin-right:5px;">
		</a>
		
	</div>
	<?php }
?>
<script type="text/javascript">
$(document).ready(function(e) {

					$("a.imgbop").fancybox({
						'titlePosition'	: 'over',
						'transitionIn'	: 'elastic',
						'hideOnContentClick' : true,
						'transitionOut'	: 'elastic'
					});
});
</script>