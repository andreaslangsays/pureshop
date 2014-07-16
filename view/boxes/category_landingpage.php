<?php
$catq=push_db_query("SELECT * FROM categories_header WHERE categories_id = '" .  (int)$current_category_id . "';");
if($catr = push_db_fetch_array($catq))
{
	$c_header = $catr['categories_header'];
	$c_description = $catr['categories_description'];
	
	$c_id = ($catr['categories_redirect']<>'') ? $catr['categories_redirect'] : (int)$current_category_id;
?>
	<div class="grid_12" style="position:relative;margin:0;margin-bottom:20px;height:340px;border:1px solid #ccc;background-image:url('images/categories/<?=$c_header?>');background-repeat:no-repeat;">
	<div id="pre" class="ch_<?=$cPath?>"></div><div class="tx_13_20 cathead ch_<?=$cPath?>"><h1 style="margin-bottom:20px;"><?= $actualtitle?></h1><?=$c_description?></div>
	</div>
	<div style="margin-left:-10px;margin-right:-10px;">
<?php
	$scqs = "SELECT c.categories_id, cd.categories_name, cpi.categories_image, cpi.categories_tag FROM categories c JOIN categories_description cd ON c.categories_id = cd.categories_id , categories_preview_images cpi WHERE c.parent_id='" . (int)$c_id . "' AND cpi.categories_id = c.categories_id ORDER BY cd.categories_name";
	//echo "<div class=\"grid_12\">" . $scqs . "</div>";
	$scatq = push_db_query($scqs);
	$catarr = array();
	while($scatr = push_db_fetch_array($scatq))
	{
		//search in subcategories recursive: 
		if($garbage=push_db_fetch_array(push_db_query("SELECT p.products_id FROM products p JOIN products_to_categories p2c ON (p2c.products_id = p.products_id) WHERE (p2c.categories_id ='" . $scatr['categories_id'] . "' OR p2c.categories_id IN (SELECT c.categories_id FROM categories c WHERE c.parent_id ='" . $scatr['categories_id'] . "' )) AND products_status ='1' ")))
		{
			$catarr[] = $scatr['categories_id'];
			?>
			<a class="grid_4 ch_img tx_13_20" href="<?=push_href_link(FILENAME_DEFAULT, "cPath=" . $c_id . "_" . $scatr['categories_id'] )?>">
			<img src="images/categories/<?=$scatr['categories_image']?>">
			<span style="text-transform: uppercase;"><?= $scatr['categories_name'] ?><br /></span>
			
			<?=$scatr['categories_tag']?>
			</a>
			<?php
//	echo "<h1> " . $scatr['categories_name'] . "</h1>";
		}
	}
	

	?>
	</div>
	<h3 class="alpha grid_12 omega seph3" style="text-align:center;">Beliebte Produkte in dieser Kategorie</h3>
	<?php
	$prodarr = array();
	$max=0;
	while( (count($prodarr) < 8 ) && $max < 100)
	{
		$max++;
		foreach($catarr as $ccat)
		{
			if($fq=push_db_fetch_array(push_db_query("SELECT p.products_id FROM products p JOIN products_to_categories p2c ON (p2c.products_id = p.products_id) WHERE p.products_id NOT IN (0" . ((implode(',', $prodarr) <>'')?','.implode(',', $prodarr):'') . ") AND (p2c.categories_id ='" . $ccat . "' OR p2c.categories_id IN (SELECT c.categories_id FROM categories c WHERE c.parent_id ='" . $ccat . "' )) AND products_status ='1' ORDER BY products_ordered DESC")))
			{
				$product->load_product($fq['products_id']);
				if($product->available && $product->image_present())
				{
					$prodarr[] = $fq['products_id'];
				}
				else
				{
					if($fq=push_db_fetch_array(push_db_query("SELECT p.products_id FROM products p JOIN products_to_categories p2c ON (p2c.products_id = p.products_id) WHERE p.products_id NOT IN (0, " . $fq['products_id'] . ((implode(',', $prodarr) <>'')?','.implode(',', $prodarr):'') . ") AND (p2c.categories_id ='" . $ccat . "' OR p2c.categories_id IN (SELECT c.categories_id FROM categories c WHERE c.parent_id ='" . $ccat . "' )) AND products_status ='1' ORDER BY products_ordered DESC")))	
					{
						$product->load_product($fq['products_id']);
						if($product->available && $product->image_present())
						{
							$prodarr[] = $fq['products_id'];
						}
					}
				}
			}
		}
	}
	$i=0;
	echo '<div style="margin-left:-10px;margin-right:-20px;">';
	foreach($prodarr as $produkt)
	{
		$i++;
		if($i<9)
		{
			$product->load_product($produkt);
			?>
			<div class='bgallery catprod grid_3' style='text-align:center; position:relative;margin-top:20px;margin-bottom:20px;'>
		<?php
			push_product_link_opener($product->products_id, "tx_13_15 tx_blue");
			echo $product->get_infographics();
		?>
			<div style="height:140px;line-height:140px;margin-bottom:10px">
				<image src="<?php echo DIR_WS_IMAGES . $product->get_image('gallery',140)?>" alt="" style="vertical-align: middle;"/>
			</div>
			<div style="height:45px !important;">
		<?php
			$add_neu = "";
			if ($product->is_new())
			{
				$add_neu ='<span class="tx_13_15" style="display:inline;">Neu! </span>';
			}
			$pname = (str_replace($product->manufacturers_name,'',$product->products_name));
			if(trim($product->manufacturers_name)<>'')
			{
				echo '<span class="tx_13_15 tx_left" style="line-height:15px;display:block;width:100%;height:15px;margin-bottom:10px;">' . $product->manufacturers_name . '</span>';
			}
			else
			{
				echo '<span class="tx_13_15 tx_left" style="line-height:15px;display:block;width:100%;height:15px;margin-bottom:10px;">&nbsp;</span>';	
			}
			echo '<span class="tx_blue tx_13_15 tx_left">' . $add_neu . osc_trunc_string( $product->products_name, 66,' &hellip;') . '</span></a>';
		?>
			</div>
		</div>
			<?php

		}
		else
		{
			break;
		}
	}

	echo '</div>';
}
else
{
//
echo "ERROR!";
}
?>
<div class="clearfix" style="border-bottom:1px solid #ccc"></div>