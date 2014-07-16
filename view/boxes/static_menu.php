<?php
/*
 * Krös
 */

// !!! if you change this arrays, change also the arrays in the function is_selected in functions/general.php !!! 

// Informationen

$faq = array(	FILENAME_FAQ,
				FILENAME_FAQ_PRODUKTE,
				FILENAME_FAQ_LIEFERUNG,
				FILENAME_FAQ_RECHNUNGSLEGUNG);	

$informationen = array_merge(
					array(	FILENAME_NEUKUNDENINFORMATION,
							FILENAME_RABATTE,
							FILENAME_SHIPPING, 
							FILENAME_DOWNLOADCENTER),							
					$faq,
					array(	FILENAME_CONTACT_US,
							FILENAME_RUECKRUFSERVICE,
							FILENAME_FEEDBACK,
							FILENAME_IMPRESSUM,
							FILENAME_PRIVACY,
							FILENAME_AGB,
							FILENAME_UEBER_UNS,
							FILENAME_JOBS,
							FILENAME_PARTNERS) );
							
// Service

$service = array_merge(
					array(	FILENAME_PRODUKTSCHULUNG,
							FILENAME_EXISTENZGRUENDUNG,
							FILENAME_CALENDAR,
							FILENAME_SAISONKARTE,
							FILENAME_HOTCONTENT,
							FILENAME_KATALOG
							) );
							
// Konto

$konto = array(	FILENAME_SORTIMENT,
				FILENAME_ACCOUNT_HISTORY,
				FILENAME_ACCOUNT_EDIT,
				FILENAME_ACCOUNT_INFO,
				FILENAME_ACCOUNT_PASSWORD ); 

		
?>
<!-- menu //-->
<?php
	$info_box_contents = array();
	$info_box_contents[] = array('text' => 'Men&uuml;');
	$currentPage = basename($_SERVER['PHP_SELF']);
	
	if ( in_array( $currentPage , $konto ) ) { 
		// Konto		
?>		
	<div id="staticMenu" class="infoBox grid_4 ">
		<div class="staticNavi">
		<!-- Mein Sortiment --><a class="navi-bottom-separator  <?= $currentPage == FILENAME_SORTIMENT ? 'selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_SORTIMENT,'','SSL'); ?>">Mein Sortiment</a>
		<!-- Meine Bestellungen --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_ACCOUNT_HISTORY ? 'selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_ACCOUNT_HISTORY,'','SSL'); ?>">Meine Bestellungen</a>
		<!-- Einstellungen --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_ACCOUNT_EDIT ? 'selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_ACCOUNT_EDIT,'','SSL'); ?>">Einstellungen</a>
		<!-- Persönliche Daten --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_ACCOUNT_INFO ? 'selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_ACCOUNT_INFO,'','SSL'); ?>">Persönliche Daten</a>
		<!-- Passwort ändern --><a class="navi-top-separator  <?= $currentPage == FILENAME_ACCOUNT_PASSWORD ? 'selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_ACCOUNT_PASSWORD,'','SSL'); ?>">Passwort ändern</a>
	</div>
<?php 
	} else if ( in_array( basename( $_SERVER['PHP_SELF'] ), $informationen ) ) {
		// Informationen
?>
	<div id="staticMenu" class="infoBox grid_4 ">
		<div class="staticNavi">
		<!-- Neukundeninformationen --><a class="navi-bottom-separator <?= $currentPage == FILENAME_NEUKUNDENINFORMATION ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_NEUKUNDENINFORMATION,'','NONSSL'); ?>">Neukundeninformationen</a>
		<!-- Rabatte --><a class="navi-top-separator navi-bottom-separator <?= $currentPage == FILENAME_RABATTE ? 'selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_RABATTE,'','NONSSL'); ?>">Rabatte</a>
		<!-- Versandkosten --><a class="navi-top-separator navi-bottom-separator <?= $currentPage == FILENAME_SHIPPING ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_SHIPPING,'','NONSSL'); ?>">Versandkosten</a>
		<!-- Downloads --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_DOWNLOADCENTER ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_DOWNLOADCENTER,'','NONSSL'); ?>">Downloads</a>
		<!-- FAQ --><a class="navi-top-separator navi-bottom-separator parent<?= (in_array($currentPage, $faq)) ? ' active' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_FAQ,'','NONSSL'); ?>">FAQ</a>
		<?php
			if (in_array($currentPage, $faq)) {
		?>
				<!-- Über push --><a class="navi-top-separator navi-dotted-separator lvl1<?= $currentPage == FILENAME_FAQ ? ' selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_FAQ,'','NONSSL'); ?>">Über push</a>
				<!-- Unsere Produkte --><a class="navi-top-separator navi-dotted-separator lvl1<?= $currentPage == FILENAME_FAQ_PRODUKTE ? ' selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_FAQ_PRODUKTE,'','NONSSL'); ?>">Unsere Produkte</a>
				<!-- Lieferung --><a class="navi-top-separator navi-dotted-separator lvl1<?= $currentPage == FILENAME_FAQ_LIEFERUNG ? ' selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_FAQ_LIEFERUNG,'','NONSSL'); ?>">Lieferung</a>
				<!-- Rechnungslegung --><a class="navi-top-separator navi-bottom-separator lvl1<?= $currentPage == FILENAME_FAQ_RECHNUNGSLEGUNG ? ' selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_FAQ_RECHNUNGSLEGUNG,'','NONSSL'); ?>">Rechnungslegung</a>
		<?php
			}
		?>		
		<!-- Kontakt --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_CONTACT_US ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_CONTACT_US,'','NONSSL'); ?>">Kontakt</a>
		<!-- Rückrufservice --><a class="navi-top-separator navi-bottom-separator <?= $currentPage == FILENAME_RUECKRUFSERVICE ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_RUECKRUFSERVICE,'','NONSSL'); ?>">Rückrufservice</a>
		<!-- Feedback --><a class="navi-top-separator navi-bottom-separator <?= $currentPage == FILENAME_FEEDBACK ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_FEEDBACK,'','NONSSL'); ?>">Feedback</a>
		<!-- Impressum --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_IMPRESSUM ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_IMPRESSUM,'','NONSSL'); ?>">Impressum</a>
		<!-- Datenschutz --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_PRIVACY ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_PRIVACY,'','NONSSL'); ?>">Datenschutz</a>
		<!-- Über uns --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_UEBER_UNS ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_UEBER_UNS,'','NONSSL'); ?>">Über uns</a>
		<!-- Jobs --><a class="navi-top-separator  <?= $currentPage == FILENAME_JOBS ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_JOBS,'','NONSSL'); ?>">Jobs</a>		
		<!-- Partner --><a class="navi-top-separator  <?= $currentPage == FILENAME_PARTNERS ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'','NONSSL'); ?>">Partner</a>
		<?php
		if($currentPage == FILENAME_PARTNERS){
?>			<!-- Orderbird --><a class="navi-top-separator navi-bottom-separator  <?= ($currentPage == FILENAME_PARTNERS && $_GET['partner']=='orderbird') ? 'selected ' : ''; ?> lvl1" title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'partner=orderbird','NONSSL'); ?>">Orderbird</a>
			<!-- Coffeekids --><a class="navi-top-separator navi-bottom-separator  <?= ($currentPage == FILENAME_PARTNERS && $_GET['partner']=='coffeekids') ? 'selected ' : ''; ?> lvl1" title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'partner=coffeekids','NONSSL'); ?>">Coffeekids</a>
			<?php
			if(polartwist_html(true))
			{
			?>
			<!-- Polartwist --><a class="navi-top-separator navi-bottom-separator  <?= ($currentPage == FILENAME_PARTNERS && $_GET['partner']=='polartwist') ? 'selected ' : ''; ?> lvl1" title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'partner=polartwist','NONSSL'); ?>">Polartwist</a>
			<?php
			}
			if(frozen_yogurt_concept_html(true))
			{
			?>
			<!-- Frozen Yogurt Concept --><a class="navi-top-separator navi-bottom-separator  <?= ($currentPage == FILENAME_PARTNERS && $_GET['partner']=='frozen_yogurt_concept') ? 'selected ' : ''; ?> lvl1" title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'partner=frozen_yogurt_concept','NONSSL'); ?>">Frozen Yogurt Concept</a>
			<?php
			}
			if(bsoc_html(true))
			{
			?>
			<!-- Bruessels School of Coffee --><a class="navi-top-separator navi-bottom-separator  <?= ($currentPage == FILENAME_PARTNERS && $_GET['partner']=='bsoc') ? 'selected ' : ''; ?> lvl1" title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'partner=bsoc','NONSSL'); ?>">Bruessels School of Coffee</a>
			<?php
			}
			if(climate_partner_html(true))
			{
			?>
			<!-- Climate Partner --><a class="navi-top-separator navi-bottom-separator  <?= ($currentPage == FILENAME_PARTNERS && $_GET['partner']=='climate_partner') ? 'selected ' : ''; ?> lvl1" title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'partner=climate_partner','NONSSL'); ?>">Climate Partner</a>
			<?php
			}
			?>
		<?php 	}
		 ?>	
		</div>
<?php
		
	} else if ( in_array( basename( $_SERVER['PHP_SELF'] ), $service ) ) {
		// Service
?>
	<div id="staticMenu" class="infoBox grid_4 ">
		<div class="staticNavi">
		<!-- Produktschulung --><a class="navi-bottom-separator  <?= $currentPage == FILENAME_PRODUKTSCHULUNG ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_PRODUKTSCHULUNG,'','NONSSL'); ?>">Produktschulung</a>
		<!-- Existenzgründung --><a class="navi-top-separator navi-bottom-separator  <?= $currentPage == FILENAME_EXISTENZGRUENDUNG ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_EXISTENZGRUENDUNG,'','NONSSL'); ?>">Existenzgründung</a>
		<!-- Kalender --><a class="navi-top-separator <?= $currentPage == FILENAME_CALENDAR ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_CALENDAR,'','NONSSL'); ?>">Kalender</a>
		<!-- Saisonkarte --><a class="navi-top-separator <?= $currentPage == FILENAME_SAISONKARTE ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_SAISONKARTE,'','NONSSL'); ?>">Saisonkarte</a>
		<!-- Saisonkarte --><a class="navi-top-separator <?= $currentPage == FILENAME_HOTCONTENT ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_HOTCONTENT,'','NONSSL'); ?>">Hot Content</a>
		<!-- Aktueller Katalog --><a class="navi-top-separator <?= $currentPage == FILENAME_KATALOG ? 'selected"' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_KATALOG,'','NONSSL'); ?>">Aktueller Katalog</a>
				
		</div>
<?php
	
	} else {
		// index.php menu
	echo '<div id="staticMenu" class="infoBox grid_4">';
		echo '<div class="staticNavi">';
		/*
		// BOF Separate Price Per Customer
		if(!push_session_is_registered('sppc_customer_group_id')) {
			$customer_group_id = '0';
		} else {
			$customer_group_id = $sppc_customer_group_id;
		}
		// EOF Separate Price Per Customer
		*/
		// check if ther is an active product of the day
		$special_q = push_db_query("SELECT products_id FROM products_of_the_day WHERE time_from <= NOW() AND time_to > NOW() LIMIT 1;");
		$specialSize = push_db_num_rows($special_q);
		
		if ($specialSize > 0 ) {
			$special = push_db_fetch_array($special_q);
?>
			<!-- Produkt des Tages --><a class="gradientstaticmenulight tx_13_20" title="" href="<?php echo push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $special['products_id'] ,'NONSSL'); ?>">Produkt des Tages</a>
<?php 
		}
		
		// check if there are some specials
		$specials_q = push_db_query("SELECT p.products_id 
									FROM specials AS s, products AS p
									WHERE 	s.products_id = p.products_id AND p.products_status = 1 AND s.status = 1 AND s.product_of_the_day = 0 AND (s.customers_id = " . (int)$_SESSION['customer_id'] ." OR s.customers_id = 0 ) order by s.customers_id DESC");
		$num_specials = push_db_num_rows($specials_q);
		
		if ($num_specials > 0) {
		$mhdq=push_db_fetch_array(push_db_query("SELECT products_mhd FROM specials WHERE products_mhd NOT LIKE '' "));
?>
		<!-- Aktuelle Angebote -><a title="" class=" gradientstaticmenulight tx_13_20" href="<?php echo push_href_link(FILENAME_DEFAULT, 'specials=week', 'NONSSL'); ?>">Aktuelle Angebote</a>
		<?php
			/*if (isset($_GET['specials'])) {
		
		?>
				<!-- Onlineshop --><a class="tx_13_20 child lvl1<?= (isset($_GET['specials'])&&( $_GET['specials']<> 'mhd')) ? ' selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_DEFAULT,"specials=week"); ?>">Angebote der Woche</a>
				<?php
					if($mhdq<>false){ ?>
				<!-- Tortenshop --><a class="tx_13_20 child lastchild lvl1<?= (isset($_GET['specials'])&&( $_GET['specials']== 'mhd')) ? ' selected' : ''; ?>" title="" href="<?php echo push_href_link(FILENAME_DEFAULT,"specials=mhd"); ?>">Angebote mit kurzem MHD</a>
		<?php
					}
			}*/
		?>
			
<?php 
		}
?>
		<!-- Neue Produkte -><a class="tx_13_20 tx_upper gradientstaticmenulight" title="" href="<?php echo push_href_link(FILENAME_DEFAULT, 'newproducts=all','NONSSL'); ?>" <?php echo (isset($_GET['newproducts']))? 'class="selected"':''; ?>>Neue Produkte</a>
		<!-- Hersteller <a class="tx_13_20 tx_upper gradientstaticmenulight" title="" href="<?php echo push_href_link(FILENAME_MANUFACTURERS,'','NONSSL'); ?>"  <?php echo ($currentPage == FILENAME_MANUFACTURERS)? 'class="selected"':''; ?>>Hersteller</a>-->	
</div>
<?php
include(DIR_WS_BOXES .'categories.php');

	}
?>
</div>
<!-- menu_eof //-->
