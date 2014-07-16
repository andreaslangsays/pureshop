<br />
</div></div>
<div id="footer">
	<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #ffffff;margin-top:20px; margin-bottom: 20px"></div>
	<footer class="container_16">
            	<div class="footerBox grid_5 tx_13_20">
					<div class="footer-info" id="footerphonehint">
	                	Kostenlos anrufen und bestellen<br />
    	                  <?php if($is_phone)
								{
									?><a class="tx_16_20 tx_blue" href="tel:08004324835">0800 4324835</a><?php 
								}
								else 
								{
									?><a class="tx_16_20 tx_blue">0800 4324835</a><?php 
								}?>

					</div>
					<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #ffffff"></div>
					<div class="footer-info" id="footercontacttimeshint">
						Telefonische Bestellannahme<br />
						Montags bis Freitags 8:00-18:00 CET
					</div>
					<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #ffffff"></div>
					<div class="footer-info" id="footeremailhint" >
						Bestellen per Email<br />
						<a class="tx_blue tx_16_20" title="Emailbestellung" href="mailto:orders@if-bi.com">orders@if-bi.com</a>
					</div>
					<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #ffffff"></div>
					<div class="footer-info" id="footerqahint" >
						Fragen & Anregungen<br />
			<?php	if($is_phone)
					{
					?>
						<a class="tx_blue tx_16_20" title="qatelefon" href="tel:+49302847000">+49 (0)30 28 47 00-0</a>
					<?php 
					}
					else
					{ 
					?>
						<a class="tx_blue tx_16_20" title="qatelefon">+49 (0)30 28 47 00-0</a>
					<?php 
					} 
					?>
					</div>
					<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #ffffff"></div>
                </div>
                <div class="footerBox grid_5 tx_12_15">
                	<div class="tx_15_20" id="footershippinghint">Versandkostenfrei* ab <?php
						if($cusomer->login)
						{
							echo $currencies->format($discount->free_shipping_amount );
						}
						else
						{
							echo $currencies->format(100 );
						}
						?>
					</div>
                    <p>
						<a href="<?php echo push_href_link('shipping.php','','NONSSL') ?>" class="tx_blue tx_12_15">Weitere Informationen zum Versand</a>
					</p>
					<p class="tx_12_15 tx_light_gray">* Diese Versandkostenfreigrenze gilt nur bei Einkauf in unserem Onlineshop und Lieferung innerhalb Deutschlands. Bei Bestellungen per Fax, E-Mail oder Telefon versenden wir ab 150 EUR kostenfrei innerhalb Deutschlands.
					</p>
					<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #ffffff"></div>
                </div>
                <div class="footerBox prefix_1 grid_2 tx_12_20">
                	<a href="<?php echo push_href_link('sortiment.php','','SSL') ?>" class="tx_blue tx_12_20">Sortiment</a><br />
                    <a href="<?php echo push_href_link('calendar.php','','NONSSL') ?>" class="tx_blue tx_12_20">Kalender</a><br />
                    <a href="<?php echo push_href_link('produktschulung.php','','NONSSL') ?>" class="tx_blue tx_12_20">Produktschulung</a><br />
                </div>
                <div class="footerBox grid_2 prefix_1 tx_12_20">
                	<a title="Über uns" href="<?php echo push_href_link('ueber_uns.php','','NONSSL'); ?>" class="tx_blue tx_12_20">Über uns</a><br />
					<a title="Jobs" href="<?php echo push_href_link(FILENAME_JOBS,'','NONSSL'); ?>" class="tx_blue tx_12_20">Jobs</a><br />
                    <a title="Impressum" href="<?php echo push_href_link(FILENAME_IMPRESSUM,'','NONSSL'); ?>" class="tx_blue tx_12_20">Impressum</a><br />
					<a title="Datenschutz" href="<?php echo push_href_link('datenschutz.php','','NONSSL'); ?>" class="tx_blue tx_12_20">Datenschutz</a><br />
                    <a title="AGB" href="<?php echo push_href_link('agb.php','','NONSSL'); ?>" class="tx_blue tx_12_20">AGB</a>
					<br><br>
					<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #ffffff"></div>
					<a title="find us on facebook" href="https://www.facebook.com/pages/push-Gmbh/376947199082200" target="_blank" style="display:block;padding-top:10px;padding-bottom:10px;">
					<img src="<?=(CANONICALURL == 'http://www.if-bi.com/shop//')?'shop/':''?>images/push/fb_like_aqua.jpg" alt=""><br><span class="tx_13_20 tx_nobr">push auf Facebook</span></a> 
                	<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #ffffff"></div>
                </div>
				<div class="clearfix"></div>
                <div id="images grid_16" style="width: 940px; padding: 30px 20px 20px 20px; margin-top: 20px; border:1px solid #ccc; background-color:#F5F5F5; position: relative; left: -10px">
					<a id="footer-tea" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=101','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Tea
					</a>
					<a id="footer-chocolate" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=102','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Chocolate
					</a>
					<a id="footer-syrup" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=103','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Syrup & Sauces
					</a>
					<a id="footer-ice" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=104','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Ice <br />Cold
					</a>
					<a id="footer-sweets" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=105','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Sweets & Snacks
					</a>
					<a id="footer-cups" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=106','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Cups & Packaging
					</a>
					<a id="footer-tools" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=107','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Tools
					</a>
					<a id="footer-equipment" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=108','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Equipment
					</a>
					<a id="footer-machinery" class="footer-cat" href="<?= push_href_link(FILENAME_DEFAULT, 'cPath=109','NONSSL') ?>">
						<div class="footer-ico"></div><div class="footer-box1"></div><div class="footer-box2"></div>Machinery
					</a>
 				</div>
              
			</footer>
		<!-- /#footer -->
	</div>

<?php include_once(DIR_WS_BOXES . "popups.php");?>
	<!-- JQuery -->
	<script type="text/javascript" src="javascript/jquery.autocomplete.pack.js" defer></script>
	<script type="text/javascript" src="javascript/jquery.mousewheel-3.0.4.pack.js" defer></script>
	<!--<script type="text/javascript" src="javascript/jquery.fancybox-1.3.4.pack.js" defer></script>-->
	<script type="text/javascript" src="javascript/jquery.jcarousel.min.js" defer></script>
	<script type="text/javascript" src="javascript/jquery.blockUI.js" defer></script>
	<script type="text/javascript" src="javascript/jquery.outside-events.min.js" defer></script>
	<script type="text/javascript" src="javascript/jquery.fancybox-1.3.4.pack.js" defer></script>
	<?php if (basename($_SERVER['PHP_SELF']) == 'pimp_my_cup.php') { ?>
		<script type="text/javascript" src="javascript/pimp_my_cup.js" defer></script>
	<?php } ?>

    <?php if(basename($_SERVER['PHP_SELF']) == FILENAME_PRODUCT_INFO){ ?> 
		<script type="text/javascript" src="javascript/jquery.socialshareprivacy.js" defer></script>
		<script type="text/javascript" src="javascript/jquery.rating.pack.js" defer></script>
		
	<script type="text/javascript">
	  jQuery(document).ready(function($){
		if($('#socialshareprivacy').length > 0){
		  $('#socialshareprivacy').socialSharePrivacy({
		   services : {
        facebook : {
            'perma_option'  : 'off'
        }, 
        twitter : {
            'perma_option' : 'off'
        },
        gplus : {
            'perma_option' : 'off'
        }
    }
		  }); 
		}
	  });
	</script>
    <?php } ?>

	<!--<script type="text/javascript" src="javascript/jquery.scrollto.min.js" defer></script>-->
	<script type="text/javascript" src="javascript/general.js?v12"></script>
		<script type="text/javascript" src="javascript/al.js?v12"></script>
<?php if(basename($_SERVER['PHP_SELF']) == FILENAME_ADVANCED_SEARCH){ ?>
	<script type="text/javascript">

	function check_form() {
	var error_message = "<?php echo JS_ERROR; ?>";
	var error_found = false;
	var error_field;
	var keywords = document.advanced_search.keywords.value;
	var dfrom = document.advanced_search.dfrom.value;
	var dto = document.advanced_search.dto.value;
	var pfrom = document.advanced_search.pfrom.value;
	var pto = document.advanced_search.pto.value;
	var pfrom_float;
	var pto_float;

	if ( ((keywords == '') || (keywords.length < 1)) && ((dfrom == '') || (dfrom == '<?php echo DOB_FORMAT_STRING; ?>') || (dfrom.length < 1)) && ((dto == '') || (dto == '<?php echo DOB_FORMAT_STRING; ?>') || (dto.length < 1)) && ((pfrom == '') || (pfrom.length < 1)) && ((pto == '') || (pto.length < 1)) ) {
    error_message = error_message + "* <?php echo ERROR_AT_LEAST_ONE_INPUT; ?>\n";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }

  if ((dfrom.length > 0) && (dfrom != '<?php echo DOB_FORMAT_STRING; ?>')) {
    if (!IsValidDate(dfrom, '<?php echo DOB_FORMAT_STRING; ?>')) {
      error_message = error_message + "* <?php echo ERROR_INVALID_FROM_DATE; ?>\n";
      error_field = document.advanced_search.dfrom;
      error_found = true;
    }
  }

  if ((dto.length > 0) && (dto != '<?php echo DOB_FORMAT_STRING; ?>')) {
    if (!IsValidDate(dto, '<?php echo DOB_FORMAT_STRING; ?>')) {
      error_message = error_message + "* <?php echo ERROR_INVALID_TO_DATE; ?>\n";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }

  if ((dfrom.length > 0) && (dfrom != '<?php echo DOB_FORMAT_STRING; ?>') && (IsValidDate(dfrom, '<?php echo DOB_FORMAT_STRING; ?>')) && (dto.length > 0) && (dto != '<?php echo DOB_FORMAT_STRING; ?>') && (IsValidDate(dto, '<?php echo DOB_FORMAT_STRING; ?>'))) {
    if (!CheckDateRange(document.advanced_search.dfrom, document.advanced_search.dto)) {
      error_message = error_message + "* <?php echo ERROR_TO_DATE_LESS_THAN_FROM_DATE; ?>\n";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }

  if (pfrom.length > 0) {
    pfrom_float = parseFloat(pfrom);
    if (isNaN(pfrom_float)) {
      error_message = error_message + "* <?php echo ERROR_PRICE_FROM_MUST_BE_NUM; ?>\n";
      error_field = document.advanced_search.pfrom;
      error_found = true;
    }
  } else {
    pfrom_float = 0;
  }

  if (pto.length > 0) {
    pto_float = parseFloat(pto);
    if (isNaN(pto_float)) {
      error_message = error_message + "* <?php echo ERROR_PRICE_TO_MUST_BE_NUM; ?>\n";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  } else {
    pto_float = 0;
  }

  if ( (pfrom.length > 0) && (pto.length > 0) ) {
    if ( (!isNaN(pfrom_float)) && (!isNaN(pto_float)) && (pto_float < pfrom_float) ) {
      error_message = error_message + "* <?php echo ERROR_PRICE_TO_LESS_THAN_PRICE_FROM; ?>\n";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  }

  if (error_found == true) {
    alert(error_message);
    error_field.focus();
    return false;
  } else {
    RemoveFormatString(document.advanced_search.dfrom, "<?php echo DOB_FORMAT_STRING; ?>");
    RemoveFormatString(document.advanced_search.dto, "<?php echo DOB_FORMAT_STRING; ?>");
    return true;
  }
}
</script>
    <?php } ?>    
        <script type="text/javascript">

            function cFormat(amount){
				var i = parseFloat(amount);
				if(isNaN(i)) { i = 0.00; }
				var minus = '';
				if(i < 0) { minus = '-'; }
				i = Math.abs(i);
				i = parseInt((i + .005) * 100);
				i = i / 100;
				s = new String(i);
				if(s.indexOf('.') < 0) { 
					s += '.00'; 
				}
				if(s.indexOf('.') == (s.length - 2))
				{ 
					s += '0'; 
				}
				var st= s.split(".");
				st = new String(st[0]+','+st[1]);
				st = minus + st;	
				return st;
			}

 
            function incre(ele){
                var sel = document.getElementById(ele);
                sel.value = parseInt(sel.value) + 1;
				$(sel).trigger('keyup');
            }         
            
			function decre(ele){
                var sel = document.getElementById(ele);
                if(parseInt(sel.value) > 1)
                    sel.value = parseInt(sel.value) - 1;
				$(sel).trigger('keyup');
            }
            
            function showthemall(){
                $("#allofthem").slideToggle('fast');
                $(".showAllCategories").toggleClass('frontpage');
				$(".showAllCategories").toggleClass('offpage');
            }

        </script>
        <script language="javascript">
                function session_win() {
                window.open("<?php echo push_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
                }
     
        </script>
		<!--[if lt IE 8]>
			<script type="text/javascript" src="javascript/bgiframe.js"></script>	
			<script language="javascript">
				$('.mainnavisub').bgiframe();
			</script> 
		<![endif]-->
<div id="semitransparent"></div>
<div id="prodToCart"></div>
<!-- from index.php-->
        <script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
--></script>
	</body>
</html>
