<?php
/*
  $Id: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	require('includes/ajax_top.php');
	
	// require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);
	
	$breadcrumb->reset();
	$breadcrumb->add('push', push_href_link(FILENAME_DEFAULT));
	$breadcrumb->add('Informationen', push_href_link(FILENAME_NEUKUNDENINFORMATION));
	$breadcrumb->add('FAQ', push_href_link(FILENAME_FAQ));
	$breadcrumb->add('Rechnungslegung', push_href_link(FILENAME_FAQ_RECHNUNGSLEGUNG));
	
	require(DIR_WS_BOXES . 'html_header.php');
	
	include(DIR_WS_BOXES . 'static_menu.php'); 
?>

<div class="grid_12">
	<?php 
		include(DIR_WS_CONTENT . FILENAME_FAQ_RECHNUNGSLEGUNG);
	?>
</div>

<?php
	require(DIR_WS_BOXES . 'html_footer.php');
	require(DIR_WS_LIB . 'end.php'); 
?>