<?php
//Überraschung!
?><ul id="mainnavi" class="grid_9"><?php 
	//
	// onlineshop-navi
	// 
	/*
	// BOF Separate Price Per Customer
	if(!push_session_is_registered('sppc_customer_group_id')) {
		$customer_group_id = '0';
	} else {
		$customer_group_id = $sppc_customer_group_id;
	}
	// EOF Separate Price Per Customer
	*/
?><a class="mmenu_parent"  title="Shop" href="<?php echo push_href_link(FILENAME_DEFAULT, '' , 'NONSSL'); ?>"><li class="<?php is_selected('shop');?>" id="shop" style="width: 44px"  >SHOP
	
	<?php
		//<!--<div class="mainnavisub"> check if ther is an active product of the day
	/*	
		$special_q = push_db_query("SELECT products_id FROM products_of_the_day WHERE time_from <= NOW() AND time_to > NOW() LIMIT 1;");
		$specialSize = push_db_num_rows($special_q);
		
		if ($specialSize > 0 && $customer_group_id == '0') {
			$special = push_db_fetch_array($special_q);
			
?>
			<a title="" href="<?php echo push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $special['products_id']); ?>">Produkt des Tages</a>
<?php 
		
		
		// check if there are some specials
		
		$specials_q = push_db_query("SELECT p.products_id
									FROM specials AS s, products AS p
									WHERE 	s.products_id = p.products_id AND p.products_status = 1 AND s.status = 1 AND s.product_of_the_day = 0 AND (s.customers_id = " . (int)$_SESSION['customer_id'] ." OR s.customers_id = 0) order by s.customers_id DESC";
		$num_specials = push_db_num_rows($specials_q);
		
		if ($num_specials > 0 ) {
?>
			<a title="" href="<?php echo push_href_link(FILENAME_DEFAULT, 'specials=all'); ?>">Aktuelle Angebote</a>
<?php 
		}
	}
?>
			<a title="" href="<?php echo push_href_link(FILENAME_DEFAULT, 'newproducts=all'); ?>">Neue Produkte</a>
			<a title="" href="<?php echo push_href_link(FILENAME_MANUFACTURERS); ?>">Hersteller</a>	
			

					<?php
		$categories_q = push_db_query('SELECT c.categories_id, cd.categories_name FROM ' . TABLE_CATEGORIES . ' c, ' . TABLE_CATEGORIES_DESCRIPTION . ' cd WHERE c.categories_id = cd.categories_id AND cd.language_id = 2 AND c.parent_id = 0 ORDER BY c.sort_order, cd.categories_name;');
		
		$c = 0;	
		while ($cat = push_db_fetch_array($categories_q)) {
			if (!($c==0 && (!isset($cPath_array)|| $id <> 'fehlt' ))) {					// first category is empty
				if (!push_count_products_in_category($row['categories_id']) == 0) {		// 0 products
					echo '<a title="" href="' . push_href_link(FILENAME_DEFAULT, 'cPath=' . $cat['categories_id']) . '">' . $cat['categories_name'] . '</a>';
				}
			}
			$c++;
		}
		/*</div>-->*/
	?>
						
</li></a><?php 
?><a title="PRODUKTE" href="<?php echo push_href_link(FILENAME_MANUFACTURERS , '' , 'NONSSL'); ?>"><li class="<?php is_selected('produkte');?>"  style="width: 84px;position:relative;" id="produkte" >
	PRODUKTE</li></a><?php

?><a title="SERVICE"  href="<?php echo push_href_link(FILENAME_PRODUKTSCHULUNG, '' , 'NONSSL'); ?>"><li class="<?php is_selected('service');?>"  style="width: 74px" id="service" >
	SERVICE
	</li></a><?php

?><a title="INFORMATIONEN" href="<?php echo push_href_link(FILENAME_NEUKUNDENINFORMATION, '' , 'NONSSL'); ?>"><li  class="<?php is_selected('informationen');?>"  style="width: 137px" id="informationen">
	INFORMATIONEN</li></a>

</ul>
<?php 
	if(true)//$customer->customers_id == 36767)
	{
	?><ul class="navisubi subproducts">
	<?php 
		$amq=push_db_query("SELECT manufacturers_name, manufacturers_id FROM manufacturers WHERE manufacturers_active = '1' AND manufacturers_id <> 1040201 ORDER BY manufacturers_name");
		while($amr = push_db_fetch_array($amq))
		{
			//Count brands!
			$totalnumberofbrands++;

			if($amr['manufacturers_id'] == $cmid){
				$currentbrandposition = $totalnumberofbrands; //	
			}
			if($currentbrandposition ==0)
			{
				$prevbrandlink = push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL');
			}
			if($totalnumberofbrands == ($currentbrandposition +1))
			{
				$nextbrandlink = push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL');		
			}
			
			?>
			<!-- <?=$amr['manufacturers_name']?> --><a title="" href="<?php echo push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL'); ?>"><li><?=$amr['manufacturers_name']?></li></a>
		<?php
		}?>
	</ul>
	<?php 
	//Service
	?><ul class="navisubi subservice">

			<!-- Produktschulung --><a title="" href="<?php echo push_href_link(FILENAME_PRODUKTSCHULUNG,'','NONSSL'); ?>"><li>Produktschulung</li></a>
			<!-- Existenzgründung --><a title="" href="<?php echo push_href_link(FILENAME_EXISTENZGRUENDUNG,'','NONSSL'); ?>"><li>Existenzgründung</li></a>
			<!-- Kalender --><a title="" href="<?php echo push_href_link(FILENAME_CALENDAR,'','NONSSL'); ?>"><li>Kalender</li></a>
			<!-- Saisonkarte --><a title="" href="<?php echo push_href_link(FILENAME_SAISONKARTE,'','NONSSL'); ?>"><li>Saisonkarte</li></a>
			<!-- Saisonkarte --><a title="" href="<?php echo push_href_link(FILENAME_HOTCONTENT,'','NONSSL'); ?>"><li>Hot Content</li></a>
			<!-- Aktueller Katalog --><a title="" href="<?php echo push_href_link(FILENAME_KATALOG,'','NONSSL'); ?>"><li>Aktueller Katalog</li></a>
	</ul>
	<?php 
	//Information
	?><ul class="navisubi subinformation">
		<!-- Neukundeninformationen --><a title="" href="<?php echo push_href_link(FILENAME_NEUKUNDENINFORMATION,'','NONSSL'); ?>"><li>Neukundeninformationen</li></a>
		<!-- Rabatte --><a title="" href="<?php echo push_href_link(FILENAME_RABATTE,'','NONSSL'); ?>"><li>Rabatte</li></a>
		<!-- Versandkosten --><a title="" href="<?php echo push_href_link(FILENAME_SHIPPING,'','NONSSL'); ?>"><li>Versandkosten</li></a>
		<!-- Downloads --><a title="" href="<?php echo push_href_link(FILENAME_DOWNLOADCENTER,'','NONSSL'); ?>"><li>Downloads</li></a>
		<!-- FAQ --><a title="" href="<?php echo push_href_link(FILENAME_FAQ,'','NONSSL'); ?>"><li>FAQ</li></a>
		<?php
			if (false && in_array($currentPage, $faq)) {
		?>
				<!-- Über push --><a title="" href="<?php echo push_href_link(FILENAME_FAQ,'','NONSSL'); ?>"><li>Über push</li></a>
				<!-- Unsere Produkte --><a title="" href="<?php echo push_href_link(FILENAME_FAQ_PRODUKTE,'','NONSSL'); ?>"><li>Unsere Produkte</li></a>
				<!-- Lieferung --><a title="" href="<?php echo push_href_link(FILENAME_FAQ_LIEFERUNG,'','NONSSL'); ?>"><li>Lieferung</li></a>
				<!-- Rechnungslegung --><a title="" href="<?php echo push_href_link(FILENAME_FAQ_RECHNUNGSLEGUNG,'','NONSSL'); ?>"><li>Rechnungslegung</li></a>
		<?php
			}
		?>		
		<!-- Kontakt --><a  title="" href="<?php echo push_href_link(FILENAME_CONTACT_US,'','NONSSL'); ?>"><li>Kontakt</li></a>
		<!-- Rückrufservice --><a title="" href="<?php echo push_href_link(FILENAME_RUECKRUFSERVICE,'','NONSSL'); ?>"><li>Rückrufservice</li></a>
		<!-- Feedback --><a  title="" href="<?php echo push_href_link(FILENAME_FEEDBACK,'','NONSSL'); ?>"><li>Feedback</li></a>
		<!-- Impressum --><a title="" href="<?php echo push_href_link(FILENAME_IMPRESSUM,'','NONSSL'); ?>"><li>Impressum</li></a>
		<!-- Datenschutz --><a title="" href="<?php echo push_href_link(FILENAME_PRIVACY,'','NONSSL'); ?>"><li>Datenschutz</li></a>
		<!-- Über uns --><a title="" href="<?php echo push_href_link(FILENAME_UEBER_UNS,'','NONSSL'); ?>"><li>Über uns</li></a>
		<!-- Jobs --><a title="" href="<?php echo push_href_link(FILENAME_JOBS,'','NONSSL'); ?>"><li>Jobs</li></a>
		<!-- Partner --><a title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'','NONSSL'); ?>"><li>Partner</li></a>
		<?php
		if(false && $currentPage == FILENAME_PARTNERS){
?>			<!-- Orderbird --><a title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'partner=orderbird','NONSSL'); ?>"><li>Orderbird</li></a>
			<!-- Coffeekids --><a title="" href="<?php echo push_href_link(FILENAME_PARTNERS,'partner=coffeekids','NONSSL'); ?>"><li>Coffeekids</li></a>
		<?php
	}
	?>

	</ul>
	<script type="text/javascript">
	$(document).ready(function(){
		var uppa = 50;
		//Products
		$("a[title='PRODUKTE']").on('hover',function(){
			if(!$('.subproducts').hasClass('toggleBox') ){$('.toggleBox').hide(0).removeClass('togglebox')};
				$('.subproducts').slideDown(200).addClass('toggleBox');
			})
		$("a[title='PRODUKTE']").on('mouseleave',function(e){
			console.log("Y:" + e.pageY+ "|X:" +e.pageX);
			if(e.pageY < 253 )
			{
					$('.subproducts').slideUp(uppa).removeClass('toggleBox');
			}
			})
		$(".subproducts").on('mouseleave',function(e){
				if(e.pageY > 253 )
				{
						$('.subproducts').slideUp(uppa).removeClass('toggleBox');
				}
			})
		//Service
			$("a[title='SERVICE']").on('hover',function(){
				if(!$('.subservice').hasClass('toggleBox') ){$('.toggleBox').hide(0).removeClass('togglebox')};
				$('.subservice').slideDown(200).addClass('toggleBox');
			})
		$("a[title='SERVICE']").on('mouseleave',function(e){
				if(e.pageY < 253 )
				{
					$('.subservice').slideUp(uppa).removeClass('toggleBox');
				}
			})
		$(".subservice").on('mouseleave',function(e){
				if(e.pageY > 253 )
				{
					$('.subservice').slideUp(uppa).removeClass('toggleBox');
				}
			})
		//Information
			$("a[title='INFORMATIONEN']").on('hover',function(){
				if(!$('.subinformation').hasClass('toggleBox') ){$('.toggleBox').hide(0).removeClass('togglebox')};
				$('.subinformation').slideDown(200).addClass('toggleBox');
			})
		$("a[title='INFORMATIONEN']").on('mouseleave',function(e){
				if(e.pageY < 253 )
				{
					$('.subinformation').slideUp(uppa).removeClass('toggleBox');
				}
			})
		$(".subinformation").on('mouseleave',function(e){
				if(e.pageY > 253 )
				{
					$('.subinformation').slideUp(uppa).removeClass('toggleBox');
				}
			})


		});
	</script>
	<?php 
	}
	?>