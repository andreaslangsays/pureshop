<?php
/**
 *
 * ajax-cart.php 
 *
 */
//@TODO: insert a noticication Value for free-products-code
//@TODO ALSO: use this for other notifications
//redirect to shop if cart empty 
chdir('../../../');
require('includes/ajax_top.php');
 push_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
?>
