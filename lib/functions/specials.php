<?php
/*
  $Id: specials.php,v 1.6 2003/06/09 21:25:32 hpdl Exp $

  ösCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// Sets the status of a special product
  function push_set_specials_status($specials_id, $status) {
    return push_db_query("update " . TABLE_SPECIALS . " set status = '" . $status . "', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
  }

////
// Auto expire products on special
  function push_expire_specials() {
    $specials_query = push_db_query("select specials_id from " . TABLE_SPECIALS . " where status = '1' and now() >= expires_date and expires_date > 0");
    if (push_db_num_rows($specials_query)) {
      while ($specials = push_db_fetch_array($specials_query)) {
        push_set_specials_status($specials['specials_id'], '0');
      }
    }
  }
  
	////
// Sets the status of a special on 1
  function push_start_special($specials_id) {
  	return push_db_query("update " . TABLE_SPECIALS . " set status = '1', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
  }
  
////
// Auto start products on special
  function push_start_specials() {
    $specials_query = push_db_query("select specials_id from " . TABLE_SPECIALS . " where status = '0' and now() >= starts_date and starts_date > 0");
    if (push_db_num_rows($specials_query)) {
      while ($specials = push_db_fetch_array($specials_query)) {
        push_start_special($specials['specials_id']);
      }
    }
  }
?>