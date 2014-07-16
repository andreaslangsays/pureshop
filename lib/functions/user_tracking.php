<?php
/*
  $Idä$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Modified by Andrew Edmond <edmond@aravia.com>
  September 14th, 2002
*/
// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled

  function push_update_user_tracking() 
  {
    global $referer_url, $languages_id, $_GET;
	
    $skip_tracking[CONFIG_USER_TRACKING_EXCLUDED] = 1;

    if (isset($_SESSION['customer_id'])) 
    {
      $wo_customer_id = $_SESSION['customer_id'];

      $customer_query = push_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $_SESSION['customer_id'] . "'");
      $customer = push_db_fetch_array($customer_query);

      $wo_full_name = addslashes($customer['customers_firstname'] . ' ' . $customer['customers_lastname']);
    } 
    else 
    {
      $wo_customer_id = '';
      $wo_full_name = 'Guest';
    }
    
    $wo_session_id = push_session_id();
    $wo_ip_address = getenv('REMOTE_ADDR');
    $wo_last_page_url = addslashes(getenv('REQUEST_URI'));

if ($_GET['products_id']) {
    $page_desc_query = push_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $_GET['products_id'] . "' and language_id = '" . $languages_id . "'");
    $page_desc_values = push_db_fetch_array($page_desc_query);
	$page_desc = addslashes($page_desc_values['products_name']);
} elseif ($_GET['cPath']) {
$cPath = $_GET['cPath'];
    $cPath_array = push_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
    $page_desc_query = push_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $current_category_id . "' and language_id = '" . $languages_id . "'");
    $page_desc_values = push_db_fetch_array($page_desc_query);
	$page_desc = addslashes($page_desc_values['categories_name']);
} else {
	$page_desc = addslashes(HEADING_TITLE);
}

    $current_time = time();
    if ($skip_tracking[$wo_ip_address] != 1)
      push_db_query("insert into " . TABLE_USER_TRACKING . " (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url, referer_url, page_desc) values ('" . $wo_customer_id . "', '" . $wo_full_name . "', '" . $wo_session_id . "', '" . $wo_ip_address . "', '" . $current_time . "', '" . $current_time . "', '" . $wo_last_page_url . "', '" . $referer_url . "', '" . $page_desc . "')");
  }

?>