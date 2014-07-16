<?php
/*
  $Id: address_book.php,v 1.58 2003/06/09 23:03:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');

  if (!push_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK);

	$breadcrumb->reset();
	$breadcrumb->add('Pers&ouml;nlicher Bereich', FILENAME_ACCOUNT);
	$breadcrumb->add('Mein Konto', push_href_link(FILENAME_ACCOUNT_INFO, '', 'SSL'));
	$breadcrumb->add(NAVBAR_TITLE_2, push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'bkr_static_menu.php'); ?>
<!-- /#left-column --> 
</div>
<div id="center-column">
<!-- body_text //-->
<div class="maincontent">

<script type="text/javascript">
    function changeAddressAction(action) {
       $("#act").attr("value", action);
		//get ids from php
		if(action =="changeCA") {
		 var tue=<?=$customer_default_address_id?>;
		 }
		if(action =="changeSA"){ 
		 var tue=<?=$customer_shipping_address_id?>;
		 }
		if(action =="changeBA"){
		 var tue=<?=$customer_bill_address_id?>;
		}
		
		$('input:radio[value="' + tue +  '"]').attr('checked', true);
		$("#question").toggle();
    }
</script>
	<h1><?php echo HEADING_TITLE; ?></h1><br />
<?php
	if ($messageStack->size('addressbook') > 0) {
		echo $messageStack->output('addressbook');
	}
?>
	<br /><div class="address">
    	<div class="c1">
        	<b><?php echo CONTACT_ADDRESS_TITLE; ?></b><br />
            <span><?php echo CONTACT_ADDRESS_INFO; ?></span>
        </div>
        <div class="c2">
        	<?php echo push_address_label($_SESSION['customer_id'], $customer_default_address_id, true, ' ', '<br />'); ?>
        </div>
        <div class="c3">
			<div class="abChangeIcon">
                <a onClick="changeAddressAction('changeCA');"><img src="images/newbkr/change_icon.jpg" /></a>
                <a onClick="changeAddressAction('changeCA');"><?php echo IMAGE_BUTTON_CHANGE; ?></a>
            </div>
        </div>
    </div>
    <div class="address">
    	<div class="c1">
        	<b><?php echo BILL_ADDRESS_TITLE; ?></b><br />
            <span><?php echo BILL_ADDRESS_INFO; ?></span>
        </div>
        <div class="c2">
        	<?php echo push_address_label($_SESSION['customer_id'], $customer_bill_address_id, true, ' ', '<br />'); ?>
        </div>
        <div class="c3">
        	<div class="abChangeIcon">
                <a onClick="changeAddressAction('changeBA');"><img src="images/newbkr/change_icon.jpg" /></a>
                <a onClick="changeAddressAction('changeBA');"><?php echo IMAGE_BUTTON_CHANGE; ?></a>
            </div>
        </div>
    </div>
    <div class="address">
    	<div class="c1">
        	<b><?php echo SHIPPING_ADDRESS_TITLE; ?></b><br />
            <span><?php echo SHIPPING_ADDRESS_INFO; ?></span>
        </div>
        <div class="c2">
        	<?php echo push_address_label($_SESSION['customer_id'], $customer_shipping_address_id, true, ' ', '<br />'); ?>
        </div>
        <div class="c3">
        	<div class="abChangeIcon">
                <a onClick="changeAddressAction('changeSA');"><img src="images/newbkr/change_icon.jpg" /></a>
                <a onClick="changeAddressAction('changeSA');"><?php echo IMAGE_BUTTON_CHANGE; ?></a>
            </div>
        </div>
    </div>
    
    <div class="addressSeparator700"></div>
    
    <b><?php echo ADDRESS_BOOK_TITLE; ?></b><br /><br />
    <?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?><br /><br />
    
<?php
	$addresses_query = push_db_query("SELECT address_book_id, 
											entry_firstname as firstname, 
											entry_lastname as lastname, 
											entry_company as company, 
											entry_street_address as street_address, 
											entry_suburb as suburb, 
											entry_city as city, 
											entry_postcode as postcode, 
											entry_state as state, 
											entry_zone_id as zone_id, 
											entry_country_id as country_id 
									FROM	" . TABLE_ADDRESS_BOOK . " 
									WHERE 	customers_id = '" . (int)$_SESSION['customer_id'] . "'");
							
	$a = 1;								
	while ($addresses = push_db_fetch_array($addresses_query)) {
	$format_id = push_get_address_format_id($addresses['country_id']);
?>
        <div class="address">
            <div class="c1">
                <b><?php echo $a . '.'; ?></b><br />
                <span class="orange">
					<?php 	if ($addresses['address_book_id'] == $customer_default_address_id) echo IS_CONTACT_ADDRESS . '<br />';
							if ($addresses['address_book_id'] == $customer_bill_address_id) echo IS_BILL_ADDRESS . '<br />'; 
							if ($addresses['address_book_id'] == $customer_shipping_address_id) echo IS_SHIPPING_ADDRESS; ?>
                </span>
            </div>
            <div class="c2">
                <?php echo push_address_format($format_id, $addresses, true, ' ', '<br />'); ?>
            </div>
            <div class="c3">
            	<div class="abEditIcon">
                    <a href="<?php echo push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL'); ?>"><img src="images/newbkr/edit_icon.jpg" /></a>
                    <a href="<?php echo push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL'); ?>"><?php echo IMAGE_BUTTON_EDIT; ?></a>
                </div>
                <div class="abDeleteIcon">
                    <a href="<?php echo push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL'); ?>"><img src="images/newbkr/delete_icon.jpg" /></a>
                    <a href="<?php echo push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL'); ?>"><?php echo IMAGE_BUTTON_DELETE; ?></a>
                </div>
            </div>
        </div>
	<?php 	if ($a < MAX_ADDRESS_BOOK_ENTRIES) {
				echo '<div class="addressSeparator"></div>'; 
			}
			$a++;
  }
  
	if ($a <= MAX_ADDRESS_BOOK_ENTRIES) {
?>
		<div class="address">
            <div class="c1">
                <b class="lightGrey"><?php echo $a . '.'; ?></b>
            </div>
            <div class="c2">
                <?php echo '<a id="addAddress" class="btnGrey" href="' . push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') . '">' . IMAGE_BUTTON_ADD_ADDRESS . '</a>'; ?>
            </div>
            <div class="c3"></div>
        </div>
<?php
	}
?>     
	</div>
<!-- body_text_eof //-->
</div>
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>