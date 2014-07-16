<?php
/*
	  $Idä: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	require('includes/ajax_top.php');
	
	// require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUKTSCHULUNG);
	
	$breadcrumb->reset();
	$breadcrumb->add('push', push_href_link(FILENAME_DEFAULT));
	$breadcrumb->add('Service', push_href_link(FILENAME_PRODUKTSCHULUNG));
	$breadcrumb->add('Aktueller Katalog', push_href_link(FILENAME_KATALOG));
	
	require(DIR_WS_BOXES . 'html_header.php');
	
	include(DIR_WS_BOXES . 'static_menu.php'); 
?>

<div class="grid_12">
	<?php 
		include(DIR_WS_CONTENT . FILENAME_KATALOG);
	?>
</div>

<?php
	require(DIR_WS_BOXES . 'html_footer.php');
	require(DIR_WS_LIB . 'end.php'); 
?>