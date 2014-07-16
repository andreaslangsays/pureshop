<?php
/*
  $Id: lögöff.php,v 1.13 2003/06/05 23:28:24 hpdl Exp $
  adapted for Separate Pricing Per Customer 2005/02/06

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');
  // BEGIN LOGOFF BACK BUTTON SECURITY FIX
  // Do not let the customer use back button or refresh to go back after logoff
    if (push_session_is_registered('customer_id')) {
  //$navigation->set_snapshot();
        push_session_destroy(); // disabled above line and changed to destroy so cannot hit back button and see potentially private info
        push_redirect(push_href_link(FILENAME_LOGOFF, '', 'SSL')); // changed to FILENAME_LOGOFF instead of FILENAME_DEFAULT ... lock in loop
        }
  // END LOGOFF BACK BUTTON SECURITY FIX
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

	push_session_unregister('customer');
  push_session_unregister('customer_id');
  push_session_unregister('customer_default_address_id');
  push_session_unregister('customer_first_name');
  // BOF Separate Pricing per Customer
  push_session_unregister('sppc_customer_group_id');
  push_session_unregister('sppc_customer_group_show_tax');
  push_session_unregister('sppc_customer_group_tax_exempt');
  // EOF Separate Pricing per Customer

  push_session_unregister('customer_country_id');
  push_session_unregister('customer_zone_id');
  push_session_unregister('comments');
  push_session_unregister('gv_id');// CCGV
  push_session_unregister('cc_id');// CCGV

	$cart->reset();
	$wishList->reset();
// re-write cart cookie after member cart is cleared
  include('includes/write_cart_to_cookie.php');
// Begin Change: Cart Cookie V1.3
	function no_strpos($target){
		$account = array('account', 'address_book', 'address_book_process.php', 'gv_faq.php', 'gv_info.php', 'gv_send.php', 'wishlist.php', 'checkout');
			foreach($account as $privat){
				if(strpos($target,$privat)){
				return false;
				}
			}
		return true;
	}
	
	$logoff=true;
	push_session_register('logoff');
 	
 
	if( no_strpos($_SERVER['HTTP_REFERER'])){
	push_redirect($_SERVER['HTTP_REFERER']);
	}else{
	push_redirect(FILENAME_DEFAULT);	
	}
?>