<?php
	require('includes/ajax_top.php');

	$breadcrumb->reset();
	$breadcrumb->add('Shop', push_href_link(FILENAME_DEFAULT));
	$breadcrumb->add('Pimp my Cup', push_href_link(FILENAME_PIMP_MY_CUP));
	
	require(DIR_WS_BOXES . 'html_header.php');
?>

<div class="grid_16" style="background: url('./images/assets/pmc/pimp-my-cup_coming-soon-anzeige3.jpg') no-repeat; border: 1px solid #cccccc; height: 610px; -moz-border-radius: 15px; -webkit-border-radius: 15px; border-radius: 15px"></div>

<?php
	require(DIR_WS_BOXES . 'html_footer.php');
	require(DIR_WS_LIB . 'end.php'); 
?>