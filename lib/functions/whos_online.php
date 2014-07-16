<?php
/*
  $Id: whos_online.php,v 1.11 2003/06/20 00:12:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function push_update_whos_online() {

    if (push_session_is_registered('customer_id')) {
      $wo_customer_id = $_SESSION['customer_id'];

      $customer_query = push_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
      $customer = push_db_fetch_array($customer_query);

      $wo_full_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
    } else {
      $wo_customer_id = '';
      $wo_full_name = 'Guest';
    }

    $wo_session_id = push_session_id();
    $wo_ip_address = getenv('REMOTE_ADDR');
    $wo_last_page_url = getenv('REQUEST_URI');

    $current_time = time();
    $xx_mins_ago = ($current_time - 900);

// remove entries that have expired
    push_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");

    $stored_customer_query = push_db_query("select count(*) as count from " . TABLE_WHOS_ONLINE . " where session_id = '" . push_db_input($wo_session_id) . "'");
    $stored_customer = push_db_fetch_array($stored_customer_query);

    if ($stored_customer['count'] > 0) {
      push_db_query("update " . TABLE_WHOS_ONLINE . " set customer_id = '" . (int)$wo_customer_id . "', full_name = '" . push_db_input($wo_full_name) . "', ip_address = '" . push_db_input($wo_ip_address) . "', time_last_click = '" . push_db_input($current_time) . "', last_page_url = '" . push_db_input($wo_last_page_url) . "' where session_id = '" . push_db_input($wo_session_id) . "'");
    } else {
      push_db_query("insert into " . TABLE_WHOS_ONLINE . " (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('" . (int)$wo_customer_id . "', '" . push_db_input($wo_full_name) . "', '" . push_db_input($wo_session_id) . "', '" . push_db_input($wo_ip_address) . "', '" . push_db_input($current_time) . "', '" . push_db_input($current_time) . "', '" . push_db_input($wo_last_page_url) . "')");
    }
  }
?>
