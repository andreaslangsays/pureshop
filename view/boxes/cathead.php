	<?php
	if(isset($_GET['specials'])){
	
	if($_GET['specials'] == 'mhd'){
	$headline='ANGEBOTE MIT KURZEM MHD';
	$headertext ='Nachfolgend finden Sie eine Auswahl an im Preis reduzierten Artikeln deren Mindesthaltbarkeitsdatum in naher Zukunft liegt. Das Angebot endet jeweils mit Abverkauf bzw. dem Ablauf des Mindesthaltbarkeitsdatums. 
';
	}else{
	$headline='ANGEBOTE DER WOCHE';
	$headertext = 'Wöchentlich wählen wir Produkte aus unserem reichhaltigen Angebot aus, die wir Ihnen zu Sonderpreisen anbieten.<br>
	Unsere Angebote hier gelten ganztägig von Montag  ' . date('d.m.Y', strtotime('last Monday')) . ' bis Sonntag ' . date('d.m.Y', strtotime('next Sunday')) . '. 
	';
	}
	?>
<div id="specialshead" class="maxihead" >
	<div id="inner-canvas">
		
		<h1><?= $headline ?></h1>
		<p><?= $headertext ?></p>
	</div>
</div>
<?php
}elseif (isset($_GET['manufacturers_id'])){

//Headline Hersteller/Kategorie Name
$nameq=push_db_query("SELECT m.manufacturers_name, m.manufacturers_image, md.manufacturers_description FROM manufacturers m JOIN manufacturers_info md on ( m.manufacturers_id = md.manufacturers_id ) WHERE m.manufacturers_id='" . mysql_real_escape_string($_GET['manufacturers_id']). "';");
$txt=push_db_fetch_array($nameq);
if ($txt['manufacturers_name'] <>''){
	$headline=strtoupper(utf8_encode(utf8_decode(html_entity_decode($txt['manufacturers_name']))));

	$serch=array('ä','ö', 'ü', 'ß','á','à','è','é');
	$replece=array('Ä','Ö', 'Ü', 'SS', 'Á','À','È','É');
	$headline = str_replace($serch, $replece, $headline);

	$herstellername=$txt['manufacturers_name'];
	$headertext = strip_tags($txt['manufacturers_description'], '<br>');
	if($txt['manufacturers_image'] <>''){
		$headerimage = 'images/hersteller/'.trim($txt['manufacturers_image']);
	}
	$categoriesq=push_db_query("SELECT DISTINCT cd.categories_id, cd.categories_name,
			COUNT(p.products_id) AS produkte 
			FROM  products p JOIN products_to_categories p2c
			ON 
			(p.products_id=p2c.products_id)
			JOIN
			categories_description cd
			ON
			(p2c.categories_id = cd.categories_id)
			WHERE  p.manufacturers_id='" . mysql_real_escape_string($_GET['manufacturers_id']). "' and p.products_status=1
			GROUP BY p.products_id 
			 ORDER BY produkte DESC;");
	$categorie_links='';
	$i=0;
	$anzahl=7;
	$eidies=array();
	while(($ctxt=push_db_fetch_array($categoriesq)) && ($i<$anzahl ) ){
		if(!in_array($ctxt['categories_id'],$eidies)){
			$eidies[]=$ctxt['categories_id'];
			$categorie_links .= "<a href='" . push_href_link(FILENAME_DEFAULT, 'cPath=' .$ctxt['categories_id']) . "'  style='white-space:nowrap'>" . $ctxt['categories_name'] . "</a>";
			if($i+1 < $anzahl ){
				$categorie_links .= ", ";
			}
			$i++;
		}
	}

	if($i+1 < $anzahl){
				$categorie_links = substr($categorie_links, 0, -2);
	}
?>
<div id="specialshead" class="maxihead" >
	<div id="inner-canvas" style="position:relative">
<?php 

	if($txt['manufacturers_description'] <>'') {  //Link nur anzeigen wenn mehr Informationen vorhanden sind
?>
		<div style="float:right;text-align:center;height:100%; width:140px; margin-left:20px;">
		<?php 
		if(push_not_null(push_browser_detect('MSIE'))){

		echo (isset($headerimage))?"<img src=\"images/pixel_trans.gif\" height=70 width=140 style='filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(
src=\"" . $headerimage . "\",sizingMethod=\"scale\")' alt><br>":'<img src="images/pixel_trans.gif" height="30" width="140"><br>';
}else{
		echo (isset($headerimage))?"<img src=\"images/pixel_trans.gif\" height=70 width=140 style='background-size:contain;background-repeat:no-repeat;background-image:url(" . $headerimage . ");background-position:center;' alt><br>":'<img src="images/pixel_trans.gif" height="30" width="140"><br>';

}
 ?>
		<a href="<?php echo push_href_link( FILENAME_MANUFACTURERS, 'manufacturers_id=' . $_GET['manufacturers_id'])?>" title="<?php echo push_short_string($headertext, 68); ?>" style="font-size:10px;line-height:10px;text-align:right;/position:absolute;right:10px;bottom:10px;*/">mehr über <?= $headline ?> &raquo;</a>
		</div>
<?php
	}
?>
		<h1><?= $headline ?></h1>
		<p style="height:64px;overflow:hidden;">Produkte von <?=$herstellername?> finden Sie in folgenden Kategorien: <br /><?=$categorie_links?> 
	</div>
</div>
<?php
}

	}elseif ( isset($_GET['cPath']) && !isset($_GET['filter']) && !isset($_GET['keywords']) ){

	//Hersteller abfrage
		$manufacturers_qu = push_db_query("SELECT 
											m.manufacturers_id, 
											m.manufacturers_name,
											SUM(pd.products_viewed)
												AS viewes
										FROM
											manufacturers m
											JOIN
											products p
											ON 
											(m.manufacturers_id = p.manufacturers_id)
											JOIN
											products_description pd
											ON
											(p.products_id = pd.products_id)
											JOIN
											products_to_categories p2c
											ON
											(p.products_id = p2c.products_id)
										WHERE 
											p2c.categories_id = '" . mysql_escape_string($current_category_id) . "'
											AND
											p.products_model NOT LIKE  'ccb_%'
											AND
											p.products_status = 1
										GROUP BY
											m.manufacturers_name
										ORDER BY
											viewes
											DESC" );
		$categorie_links='';
		$i=0;
		$anzahl=6;
		$eidies=array();
		$menge=push_db_num_rows($manufacturers_qu);
		if(($menge <= $anzahl)&&($menge > 3)){
			$anzahl= $menge-2;
		}elseif($menge <= 3){
		$anzahl=2;
		}
		while(($ctxt=push_db_fetch_array($manufacturers_qu)) && ($i<$anzahl ) ){
			if(!in_array($ctxt['manufacturers_id'],$eidies)){
				$eidies[]=$ctxt['manufacturers_id'];
				
				//push_href_link(FILENAME_DEFAULT , push_get_all_get_params(array("filter","page")) . 'filter=manufacturer=' . $manr['manufacturers_id']  .  $add_filter_to )
				
				$categorie_links .= "<a title='" . ((DIR_WS_HTTP_CATALOG =='/testsystem/')?$ctxt['viewes']:'') . "' href='" . push_href_link(FILENAME_DEFAULT, push_get_all_get_params(array("filter","page")) . 'filter=manufacturer=' . $ctxt['manufacturers_id'] ) . "'  style='white-space:nowrap'>" . $ctxt['manufacturers_name'] . "</a>";
				if($i+1 < $anzahl ){
					$categorie_links .= ", ";
				}
				$i++;
			}
		}
	
		if($i+1 < $anzahl){
					$categorie_links = substr($categorie_links, 0, -2);
		}
	//hier neue hersteller in kategorie wenn es mehr als x Hersteller gibt
		if($menge > ($anzahl - 3)){
			$manufacturers_qu = push_db_query("SELECT p.manufacturers_id, m.manufacturers_name, count(p.products_id) as anzahl  FROM products p JOIN manufacturers m ON (p.manufacturers_id=m.manufacturers_id) JOIN products_to_categories p2c ON (p.products_id = p2c.products_id) WHERE p.products_status=1 AND p2c.categories_id = '" . mysql_escape_string($current_category_id) . "'
											AND
											p.products_model NOT LIKE  'ccb_%' GROUP BY m.manufacturers_name ORDER BY m.date_added DESC " );
			$i=0;
			$anzahl=2;
				while(($ctxt=push_db_fetch_array($manufacturers_qu)) && ($i<$anzahl ) ){
		
					if(!in_array($ctxt['manufacturers_id'],$eidies)){
						$eidies[]=$ctxt['manufacturers_id'];
						if($i==0){
								$categorie_links .= '<br> Neue Hersteller in dieser Kategorie: ';
						}				
						$categorie_links .= "<a href='" . push_href_link(FILENAME_DEFAULT, push_get_all_get_params(array("filter","page")) . 'filter=manufacturer=' . $ctxt['manufacturers_id']) . "'  style='white-space:nowrap'>" . $ctxt['manufacturers_name'] . "</a>";
						$categorie_links .= ", ";
						$i++;
					}
				}
			
				if($i > 0){
							$categorie_links = substr($categorie_links, 0, -2);
				}
		}
	
		$headline=strtoupper($categories['categories_name']);
		$serch=array('ä','ö', 'ü', 'ß','á','à','è','é');
		$replece=array('Ä','Ö', 'Ü', 'SS', 'Á','À','È','É');
		$headline = str_replace($serch, $replece, $headline);
	if($menge>1){
		?>
		<div id="specialshead" class="maxihead" >
			<div id="inner-canvas" style="position:relative">
		<?php 
			if(isset($cPath_array)) {  //Kategoriebild anzeigen
			$tiq=push_db_fetch_array(push_db_query("SELECT categories_image FROM categories_preview_images WHERE categories_id IN (" . implode(',', $cPath_array) . ")"));
		//	echo $tiq;
		?>
				<div style="float:left;text-align:center;height:100%; width:140px; margin-right:20px;"><?php echo "<img src=\"images/pixel_trans.gif\" height=120 width=140 style='background-size:contain;background-repeat:no-repeat;background-image:url( images/small/bilder/" . $tiq['categories_image'] . ");background-position:center;' alt><br>" ?>
				</div>
		<?php
			}
		?>
				<h1><?= $headline ?></h1>
				<p style="height:64px;overflow:hidden;">Beliebte Hersteller in dieser Kategorie: <?=$categorie_links?> 
			</div>
		</div>
		<?php
		}
	//}
}
?>