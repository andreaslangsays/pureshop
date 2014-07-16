<?php
/* LÖL */

$best_sellers_query = push_db_query("SELECT distinct p.products_id, p.products_image, pd.products_name 
									FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
									WHERE p.products_status = '1' 
										AND p.products_id = pd.products_id 
										AND pd.language_id = '" . (int)$languages_id . "' 
									ORDER BY p.products_ordered desc, pd.products_name 
									LIMIT 8");

if (push_db_num_rows($best_sellers_query) == 8) {
?>
	<!-- topseller -->
	<div class="grid_16 alpha omega">
		<div class="grid_16 tx_15_20" style="height: 35px; padding-top: 15px; border : 1px solid #cccccc; background-color: #f5f5f5; text-align: center; vertical-align: middle; margin-bottom: 20px; margin-top: 20px">
			Topseller
		</div>
		<div id="topseller" class="grid_16 alpha omega" style="width: 960px">
			<?php while ($best_seller = push_db_fetch_array($best_sellers_query)) { ?>
						<div class="grid_2">
							<?php	
								if(!is_object($p)){
									$p = new product;
								}
								$p->load_product($best_seller['products_id']);
								push_product_link_opener($p->products_id, "");
							?>
								<div class="image2">
									<div class="inner2">
										<image src="<?= DIR_WS_IMAGES . $p->get_image('sortimentListe', 80) ?>" alt="" />
									</div>
									<?php 
										echo $p->get_infographics();							
									?>
								</div>
							</a>
							<div class="tx_13_20"><?= $p->manufacturers_name ?></div>
							<?php 
								echo push_product_link_opener($p->products_id, "tx_13_15 tx_blue"); 
								echo $p->products_name;
							?>								
							</a>
						</div>			
			<?php } ?>
		</div>
	</div>
<!-- best_sellers_eof -->
<?php
}
?>
