<?php
	require('includes/ajax_top.php');	
	require(DIR_WS_BOXES . 'html_header.php');
	
	$categories = array(
			array("wohltuend", "tea", "tea", array(
					"11009" => "DR_EU_14-oz_Tiger.png",
					"11187" => "SCH_schmuckbox-offen.png",
					"11226" => "DMC_peppermint-chai_250g.png")),
			array("schokoladig", "chocolate", "chocolate", array(
					"11095" => "BLOEM_gd_cocoa.png",
					"11100" => "BLOEM_CD_02-ital-choc.png",
					"11102" => "BLOEM_CD_04-milk-drops.png")),
			array("vielfältig", "syrup", "syrup & sauces", array(
					"10806" => "TO_caramel-sauce_2012.png",
					"11124" => "CD_karamellbatzen.png",
					"10053" => "TO_hazelnut_750.png")),
			array("erfrischend", "ice", "ice cold", array(
					"11052" => "CD_frappe_wc.png",
					"11045" => "CD_smoothie_mango.png",
					"11054" => "CD_frappe_chai.png")),
			array("verlockend", "sweets", "sweets & snacks", array(
					"11547" => "PAN_panettoncino-classic-80g.png",
					"11546" => "GB_cheddar_pouch.png")),
			array("schützend", "cups", "cups & packaging", array(
					"11564" => "CK_becher_300ml.png",
					"11265" => "BIONAT_clearcup_smoothie_300ml_mit_domdeckel.png")),
			array("hilfreich", "tools", "tools", array(
					"11341" => "tamper_espro_stehend.png",
					"11476" => "HARIO_karaffe_360ml.png",
					"11418" => "milchkanne_350ml.png")),
			array("praktisch", "equipment", "equipment", array(
					"11452" => "becherspender_edelstahl_300ml.png",
					"11434" => "serviettenspender.png",
					"11455" => "spenderhalter_stahl.png")),
			array("stark", "machinery", "machinery", array(
					"11599" => "hotdrink_gold_2A.png",
					"11500" => "VITA_mixer_barboss_advance_2011.png",
					"11507" => "NOSCH_spindelmixer_single_M98_PE.png"))
	);
?>

<div class="container_18 ie_fix_2" style="position:relative; width: 978px;top:-1px;">
	<div class="grid_18 tx_15_20 tx_blue" style="text-align: center">    
	    <img src="images/push/push-logo.png" style="margin: 20px 0 5px 0;" alt="IF-BI"><br />
        TASTE IT. LOVE IT. SHOP IT.
	</div>
    <div id="home-banner" class="gradientgrey">
        <h1 style="font-size: 50px; margin: 40px 0; font-weight: 300; color: #666666">KOFFEIN FÜR IHR GESCHÄFT</h1>
        <h2 class="tx_30_40 tx_blue" style="font-weight: 300">PRODUKTE UND IDEEN FÜR GASTRONOMEN, <br />KAUFLEUTE UND GEWERBLICHE GASTGEBER</h2>
        <a class="gradientblack button tx_25_30 tx_white" style="display: inline-block; width: 158px; height: 40px; font-weight: 300; padding: 8px 0 0 20px; -moz-border-radius: 5px; -webkit-border-radius: 5px;	border-radius: 5px; text-align: left" href="<?= push_href_link(FILENAME_DEFAULT) ?>">
	        Zum Shop<img src="images/push/icons/ico_arrow-fw_L_white.png" style="position: absolute; right: 20px; top: 15px">
        </a>
    </div>  
   	<div id="home-categories" class="grid_18">  
		<?php foreach ($categories as $c) { 
			$product_id = array_rand($c[3]);
		?>
			<a id="home-<?= $c[1] ?>" class="grid_2 alpha omega home-category" href="<?= generate_url_rewritten_string(push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_id)) ?>">
            	<div class="home-category-description"><?= $c[0] ?></div>
                <div class="home-category-img">
                	<img src="images/push/home/<?= $c[3][$product_id] ?>" />
                </div>
                <div class="home-box1"></div>
                <div class="home-box2"></div>
                <div class="home-category-name"><?= $c[2] ?></div>
            </a>
		<?php } ?>         
	</div>
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); ?>
