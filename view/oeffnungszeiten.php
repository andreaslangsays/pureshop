<?php
	require('includes/ajax_top.php');

	include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CAFE);

	$breadcrumb->reset();
	$breadcrumb->add(CAFE_LADEN, FILENAME_CAFE);
	$breadcrumb->add('Anfahrt & Öffnungszeiten', 'oeffnungszeiten.php');


	require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'bkr_static_menu.php'); ?>
<!-- /#left-column --> 
</div>
<div id="center-column">
<?php include(DIR_WS_CONTENT . 'oeffnungszeiten.php.html') ?>
</div>
</div>
<?php
require(DIR_WS_BOXES . 'html_footer.php');
require(DIR_WS_LIB . 'end.php'); ?>
