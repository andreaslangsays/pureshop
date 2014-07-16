<?php
/*
  $Id: create_account_success.php,v 1.30 2003/06/05 23:27:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');

  	//BOF ccb-modul customers-coffee-blend
	//here we take all the blends the user made before and connect them to his account
	if((isset($_SESSION['ccb_key']))&&(substr($_SESSION['ccb_key'],0,1)=='g')){
		//alle DB-Einträge updaten und anschließend die $_SESSION['ccb-key'] anpassen
		$us="UPDATE ccb SET creator='u_".$_SESSION['customer_id']."' WHERE creator='".$_SESSION['ccb_key']."';";
		if(push_db_query($us)){
			$_SESSION['ccb_key']="u_".$_SESSION['customer_id'];
		}

	}
	//EOF ccb

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1, push_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') );
  $breadcrumb->add(NAVBAR_TITLE_2);

  if (sizeof($navigation->snapshot) > 0) {
    $origin_href = push_href_link($navigation->snapshot['page'], push_array_to_string($navigation->snapshot['get'], array(push_session_name())), $navigation->snapshot['mode']);
    $navigation->clear_snapshot();
  } else {
    $origin_href = push_href_link(FILENAME_DEFAULT);
  }

require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'bkr_static_menu.php'); 
?>
<!-- /#left-column --> 
</div>
<div id="center-column">

	<h1><?php echo HEADING_TITLE; ?></h1><br />
    <p class="success"><?php echo HEADING_ACCOUNT_CREATED; ?></p>
    <p class="w520"><?php echo TEXT_ACCOUNT_CREATED; ?></p><br />
    <?php echo '<a class="btnOrange" href="' . $origin_href . '">' . BUTTON_GOTO_SHOP . '</a>'; ?>
    
<!-- body_text_eof //-->
<!-- body_eof //-->
</div>
</div>
<?php
require(DIR_WS_BOXES . 'html_footer.php');
require(DIR_WS_LIB . 'end.php'); ?>