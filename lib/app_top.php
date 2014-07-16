<?php
/*
  $Id: application_top.php,v 1.280 2003/07/12 09:38:07 hpdl Exp $

  ösCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// start the timer for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());
/*
var_dump($_POST);
var_dump($_GET);
die("[TOT]");
/**/
// set the level of error reporting
// error_reporting(E_ALL & ~E_NOTICE);
// error_reporting(E_ALL | E_STRICT);
 error_reporting(0);


// Set the local configuration parameters - mainly for developers
if (file_exists('includes/local/configure.php'))
{
	include('includes/local/configure.php');
}

// include server parameters
  require('includes/configure.php');

  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }

// define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS2');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = $_SERVER['PHP_SELF'];

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTpush_CATALOG);
  }

// include the list of project filenames
  require(DIR_WS_LIB . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_LIB . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  push_db_connect() or die('Unable to connect to database server!');

// set the application parameters
  $configuration_query = push_db_query('select distinct configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = push_db_fetch_array($configuration_query))
  {
		if(!defined($configuration['cfgKey']))
		{
			define($configuration['cfgKey'], $configuration['cfgValue']);
		}
  }
// if gzip_compression is enabled, start to buffer the output
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      if (PHP_VERSION >= '4.0.4') {
        ob_start('ob_gzhandler');
      } else {
        include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
        ob_start();
        ob_implicit_flush();
      }
    } else {
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }

// define general functions used application-wide
require(DIR_WS_FUNCTIONS . 'general.php');
require(DIR_WS_FUNCTIONS . 'html_output.php');

// set the cookie domain
$cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTpush_COOKIE_DOMAIN);
$cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTpush_COOKIE_PATH);

// include cache functions if enabled
  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');

// include shopping cart class

//require(DIR_WS_CLASSES . 'shopping_cart.php');
require(DIR_WS_CLASSES . 'Cart.php');
require(DIR_WS_CLASSES . 'Customer.php');
require(DIR_WS_CLASSES . 'product.php');

//current_categories
//	require(DIR_WS_CLASSES . 'current_categories.php');

//error_message
require(DIR_WS_CLASSES . 'error_message.php');
// include wishlist class
	require(DIR_WS_CLASSES . 'wishlist.php');
//image resizing
	include(DIR_WS_CLASSES . 'resizeimage.php');
// include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// check if sessions are supported, otherwise use the php3 compatible session class
  if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'osCzid');
    define('PHP_SESSION_PATH', $cookie_path);
    define('PHP_SESSION_DOMAIN', $cookie_domain);
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
  }

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  push_session_name('osCzid');
  push_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(345600, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '345600');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

// set the session ID if it exists
   if (isset($_POST[push_session_name()])) {
     push_session_id($_POST[push_session_name()]);
   } elseif ( ($request_type == 'SSL') && isset($_GET[push_session_name()]) ) {
     push_session_id($_GET[push_session_name()]);
   }

 // start the session
  $session_started = false;


  if (SESSION_FORCE_COOKIE_USE == 'True') {
   push_setcookie('_c_t', '_R_I_' . time(), time()+60*60*24*30, $cookie_path, $cookie_domain);
	

 if ( isset($_COOKIE['_c_t'])) {
   		if($_COOKIE['a_TRX'] <>"")
		{
			session_id($_COOKIE['a_TRX']);
			push_session_start();
		}
		else
		{
			push_session_start();
			push_setcookie('a_TRX', session_id(),time()+60*60*24*30, $cookie_path, '.'.$cookie_domain );
		}


//BOF user_tracking modifications
	if ( !isset($referer_url) )
	{
		$referer_url = $_SERVER['HTTP_REFERER'];
		if ($referer_url)
			push_session_register('referer_url');
	}
//EOF user_tracking modifications

      $session_started = true;
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $spider_flag = false;

   if (push_not_null($user_agent)) {
      $spiders = file(DIR_WS_LIB . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (push_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
//////////////////////////////////////////////////////////////////////////////////////////////
// Diese Modifikation sollte Suchmaschinen die Links mit SessionIds abgewoehnen!
			if ( isset( $_GET['osCsid'] ) && push_not_null( $_GET['osCsid'] ) )
			{
				header($SERVER_PROTOCOL . ' 404 Not Found');
				echo '404 Not Found';
				exit();
			}
/////////////////////////////////////////////////////////////////////////////////////////////
            break;
          }
        }
      }
  }
    if ($spider_flag == false) {
      push_session_start();
      $session_started = true;
    }
  } else {
    push_session_start();
    $session_started = true;
  }
  
/*  require(DIR_WS_CLASSES ."softlogin.php");
  $autologin = new softlogin();/**/
// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!push_session_is_registered('SSL_SESSION_ID')) {
      $SESSION_SSL_ID = $ssl_session_id;
      push_session_register('SESSION_SSL_ID');
    }

    if ($SESSION_SSL_ID != $ssl_session_id) {
      push_session_destroy();
      push_redirect(push_href_link(FILENAME_SSL_CHECK));
    }
  }
// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!push_session_is_registered('SESSION_USER_AGENT')) {
      $SESSION_USER_AGENT = $http_user_agent;
      push_session_register('SESSION_USER_AGENT');
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
      push_session_destroy();
      push_redirect(push_href_link(FILENAME_LOGIN));
    }
  }

// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = push_get_ip_address();
    if (!push_session_is_registered('SESSION_IP_ADDRESS')) {
      $SESSION_IP_ADDRESS = $ip_address;
      push_session_register('SESSION_IP_ADDRESS');
    }

    if ($SESSION_IP_ADDRESS != $ip_address) {
      push_session_destroy();
      push_redirect(push_href_link(FILENAME_LOGIN));
    }
  }
/*
if(session_id() == "j7oh2p6ihb95b8uofqtcstv1fpnknpdf")
{
	echo "<pre>";
	var_dump($_SESSION);
	echo "</pre>";
}*/
//insert: [REDESIGN] error_message -> try to centralize behavior
if(!push_session_is_registered('error_stack')){
			$error_stack = new error_message();
}

if(isset($_GET['error_message'])){
			$error_stack->add( $_GET['error_message'] );
}

if(!push_session_is_registered('sppc_customer_group_show_tax')){
	push_session_register('sppc_customer_group_show_tax');
	$sppc_customer_group_show_tax=1;
}

//PHONE DETECTION

if (!push_session_is_registered('is_phone')) 
{
    push_session_register('is_phone');
	push_session_register('is_mobile');
	require_once 'includes/classes/Mobile_Detect.php';
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	if($deviceType == 'phone')
	{
		$is_phone= true;
	}
	else
	{
	   	$is_phone=false;
	}
	$is_mobile = $detect->isMobile();
}

// create the shopping cart & fix the cart if necesary
//if (!push_session_is_registered('cart') && !(is_object($cart)) ) {
//		push_session_register('cart');
//		$cart = new shoppingCart;
//}
if(isset($customer_id))
{
	$customer = new Customer($customer_id);
}
$p = new product;
$product = new product;
$cart = new Cart;
/*
 FIX IEbug AND FF12 CACHING BUG 
 */
$browser = $_SERVER['HTTP_USER_AGENT']; 
if (preg_match('/MSIE/i', $browser) || strpos($browser,"Firefox/12.")) {
	$antiCachingBug='&cbug='.md5(time());
} else {
	$antiCachingBug='';
}
/*
 * EOF IEbug AND FF12 CACHING BUG 
 */

if (push_is_on_homepage() && isset($_COOKIE['visited']) && empty($_SERVER['HTTP_REFERER'])) { 
	push_redirect('www.if-bi.com' . DIR_WS_HTTP_CATALOG . 'index.php');  
}
setcookie("visited", true, time()+60*60*24*30*6);		// 180 days cookie lifetime

 // Sortandorder - productlisting BOF
 //write post data to session to prevent Browser-Requests
 /**
 * 
 * define $sortorder as session-var ...
 */
if (!push_session_is_registered('sortorder')) {
    push_session_register('sortorder');
    	$sortorder="4a";
 }
if(trim($sortorder)=='')
{
   	$sortorder="4a";
}
if (!push_session_is_registered('sortimentSortOrder')) {
    push_session_register('sortimentSortOrder');
    	$sortimentSortOrder = "2a";
}

if (!push_session_is_registered('view')) {
    push_session_register('view');
    	$view="list";
}

if (!push_session_is_registered('epp')) {
    push_session_register('epp');
    if(defined('ONSH')){
		$epp=8;
	}
	else
	{
		$epp=1000;
	}
 }

$sao_redirect=false;
if(isset($_POST['sort'])){
	$sortorder = $_POST['sort'];
	$sao_redirect=true;
}

if(isset($_POST['ordersSort'])){
	$ordersSortOrder = $_POST['ordersSort'];
	$sao_redirect=true;
}

if(isset($_POST['sortimentSort'])){
	$sortimentSortOrder = $_POST['sortimentSort'];
	$sao_redirect=true;
}

if(isset($_POST['forumTopicsSort'])){
	$forumTopicsSortOrder = $_POST['forumTopicsSort'];
	$sao_redirect=true;
}

if(isset($_POST['forumThreadsSort'])){
	$forumThreadsSortOrder = $_POST['forumThreadsSort'];
	$sao_redirect=true; 
}

if(isset($_POST['list'])){
   	$view='list';
	$sao_redirect=true;
}elseif(isset($_POST['gallery'])){
    $view='gallery';
	$sao_redirect=true;
}

if(isset($_POST['psel'])){
 	$_GET['page']=$_POST['psel'];
	$sao_redirect=true;
}

if (isset($_POST['epp'])) {
	if (is_numeric($_POST['epp'])) {
		if ((floor( (int)$_POST['epp']) >7) && (floor( (int)$_POST['epp']) < 1001)){
			$epp=floor( (int)$_POST['epp'] /4) * 4;		
		}
	} else if ($_POST['epp'] == "Alle") {
		$epp = 1000;
	}
	$sao_redirect=true;
}


if($sao_redirect){
push_redirect(push_href_link(basename($_SERVER['SCRIPT_NAME']),push_get_all_get_params()));

}
//Sortandorder EOF
  
// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

//BOF include the price formatter for the price breaks contribution
	require(DIR_WS_CLASSES . 'PriceFormatter.php');
	$pf = new PriceFormatter;
/*	require(DIR_WS_CLASSES."product.php");
	$p = new product();
	$product = new product();
*/			
//EOF include the price formatter for the price breaks contribution

// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// set the language
  if (!push_session_is_registered('language') || isset($_GET['language'])) {
    if (!push_session_is_registered('language')) {
      push_session_register('language');
      push_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php'); 
    $lng = new language();

    if (isset($_GET['language']) && push_not_null($_GET['language'])) {
      $lng->set_language($_GET['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];

    $languages_id = $lng->language['id'];
  }
  if (!isset($language)) {		// bug fix
	  $language = 'german';
  }
   if(trim($languages_id) =='')
   {
    	$languages_id=2;
   }
// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');
  require(DIR_WS_LANGUAGES . 'email_footer.php');
  
// set locale
date_default_timezone_set('Europe/Bruessels');
setlocale(LC_ALL, 'de_DE');

// currency
  if (!push_session_is_registered('currency') || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!push_session_is_registered('currency')) push_session_register('currency');

    if (isset($_GET['currency'])) {
      if (!$currency = push_currency_exists($_GET['currency'])) $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    } else {
      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }


// navigation history
  if (push_session_is_registered('navigation')) {
    if (PHP_VERSION < 4) {
      $broken_navigation = $navigation;
      $navigation = new navigationHistory;
      $navigation->unserialize($broken_navigation);
    }
	//$broken_navigation = $navigation;
    unset($navigation);
	//$navigation = new navigationHistory;
    $navigation = new navigationHistory;
	
  } else {
    push_session_register('navigation');
    $navigation = new navigationHistory;
  }
//  $navigation->add_current_page();

// Shopping cart & Wishlist actions
// wishlist data

if(!push_session_is_registered('wishList')) {
	push_session_register('wishList');
	$wishList = new wishlist; 
	unset($wishList->wishID[0]); 
}
else
{
/*	if(!push_not_null($wishList))
	{
		unset($wishList);
		push_session_register('wishList');
		$wishList = new wishlist;
		unset($wishList->wishID[0]); 
	}
*/
}
 
//Wishlist actions (must be before shopping cart actions)
  if((isset($_POST['sortiment']))||(isset($_GET['sortiment']))) {
	if((isset($_POST['products_id']))||(isset($_GET['products_id']))) {
		if((isset($_POST['id']))||(isset($_GET['id']))) {
			$attributes_id = (isset($_POST['id'])) ? $_POST['id'] : $_GET['id'];
			push_session_register('attributes_id');
		}
		$wishlist_id = (isset($_POST['products_id'])) ? $_POST['products_id'] : $_GET['products_id'];
		push_session_register('wishlist_id');
		unset($_GET['sortiment']);
	}
	//push_redirect(push_href_link(FILENAME_WISHLIST, 'redirect=1'));
  }
  
if(push_session_is_registered('wishlist_id')) {
	$wishList->add_wishlist($wishlist_id, $attributes_id);
}  

// delete sortiment
	if (isset($_POST['add_wishprod'])) {
		if(isset($_POST['delete_prod_x'])) {
			foreach ($_POST['add_wishprod'] as $value) {
				
				$wishList->remove($value);
			}
		}
	}

//if(push_session_is_registered('wishlist_id') && defined('ONSH')) {
//	$TwishList->add_wishlist($wishlist_id, $attributes_id);
//}elseif(push_session_is_registered('wishlist_id') && defined('TOSH')) {
//	$wishList->add_wishlist($wishlist_id, $attributes_id);
//}

  if (isset($_GET['action'])) {
  
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      push_redirect(push_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
      $goto = basename($_SERVER['SCRIPT_NAME']);
      if ($_GET['action'] == 'schnellkauf') {
	  	$goto = FILENAME_DEFAULT;
        $parameters = array('action', 'pid');
      } else {
        $parameters = array('action', 'pid');
      }
    }
//BOF Products Multi
if ($_GET['action'] == 'add_multiple')
		$parameters = array('action', 'pid', 'products_id', 'id');

switch ($_GET['action']) {
	case 'add_multiple':
		for ($i = 1; $i <= sizeof( $_POST['products_id'] ); $i++)
		$cart->add_cart($_POST['products_id'][$i], $cart->get_quantity(push_get_uprid($_POST['products_id'][$i], $_POST['id'][$i]))+($_POST['add_id'][$i]), $_POST['id'][$i]);
		//include('includes/write_cart_to_cookie.php');
		push_redirect(push_href_link(FILENAME_DEFAULT, push_get_all_get_params($parameters) . $antiCachingBug, 'NONSSL'));
	break;
//EOF Products Multi

// customer wants to update the product quantity in their shopping cart
	case 'update_product' : 
		for ($i=0, $n=sizeof($_POST['products_id']); $i<$n; $i++)
		{
			if (in_array($_POST['products_id'][$i], (is_array($_POST['cart_delete']) ? $_POST['cart_delete'] : array()))) 
			{
				$cart->remove($_POST['products_id'][$i]);
				//include('includes/write_cart_to_cookie.php');
			}
			else
			{
				$attributes = (isset($_POST['id'][$_POST['products_id'][$i]])) ? $_POST['id'][$_POST['products_id'][$i]] : '';
				$cart->add_cart($_POST['products_id'][$i], $_POST['cart_quantity'][$i], $attributes, false);
				//include('includes/write_cart_to_cookie.php');
			}
		}
		push_redirect(push_href_link($goto, push_get_all_get_params($parameters) . $antiCachingBug));
	break;

// customer adds a product from the products page
	case 'add_product' :
		if (isset($_POST['products_id']) && is_numeric($_POST['products_id']))
		{
			$cart->add_cart($_POST['products_id'], $cart->get_quantity(push_get_uprid($_POST['products_id'], $_POST['id'])) + $_POST['cart_quantity'], $_POST['id']);
			//include('includes/write_cart_to_cookie.php');
		}
		push_redirect(push_href_link($goto, push_get_all_get_params($parameters) . $antiCachingBug));
	break;

// BOF PRODUCT LISTING WITH ATTRIBUTES
	case 'buy_now_form' :
	case 'fast_buy' :
		if (isset($_POST['products_id']) && is_numeric($_POST['products_id']))
		{
			//$this->contents[$products_id_string]['qty'] +
			$pids=(isset($_POST['ve_id']) ? $_POST['ve_id'] : $_POST['products_id'] );
			$zahl = /*$cart->contents[$pids]['qty'] +*/ $_POST['cart_quantity'];
			$cart->add_cart( $_POST['products_id'], $zahl , $_POST['id']);
			//include('includes/write_cart_to_cookie.php');
		}
		//var_dump($cart);
		//die("[]X!X%$!");
		if (isset($_GET['ref']) && $_GET['ref'] == "sortiment") {
			$goto = "sortiment.php";
		}
		push_redirect(push_href_link($goto, push_get_all_get_params($parameters) . $antiCachingBug));
	break;
	
	case 'updateproduct' :
		if (isset($_GET['pid']))
		{
			$zahl = (int)$_GET['qty'];
			$parameters[]='qty';
			//$this->contents[$products_id_string]['qty'] +
			$cart->update_cart( $_GET['pid'], $zahl);
			//include('includes/write_cart_to_cookie.php');
		}
		push_redirect(push_href_link($goto, push_get_all_get_params($parameters) . $antiCachingBug));
	break;

	case 'addtocart' :
		if(isset($_GET['pid']))
		{
			$cart->add_cart( $_GET['pid'], 1 );
			//include('includes/write_cart_to_cookie.php');
		}
		push_redirect(push_href_link($goto, push_get_all_get_params($parameters) . $antiCachingBug));
	break;
	
	case 'removeproduct' :
		if(isset($_GET['pid']))
		{
			$cart->remove(html_entity_decode($_GET['pid']), 0 );
			$cart->cleanup();
			//include('includes/write_cart_to_cookie.php');
		}
		push_redirect(push_href_link($goto, push_get_all_get_params($parameters) . $antiCachingBug));
	break;
	}
}

// include the who's online functions
  require(DIR_WS_FUNCTIONS . 'whos_online.php');
  push_update_whos_online();

// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// infobox
  require(DIR_WS_CLASSES . 'boxes.php');

// auto activate and expire banners
/*  require(DIR_WS_FUNCTIONS . 'banner.php');
  push_activate_banners();
  push_expire_banners();
*/
// auto expire special products
  require(DIR_WS_FUNCTIONS . 'specials.php');
  push_expire_specials();
	//push_start_specials();
	
// BOF Featured Products
// auto expire featured products
//	require(DIR_WS_FUNCTIONS . 'featured.php');
//	push_expire_featured();
// EOF Featured Products

// calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } elseif (isset($_GET['products_id']) && !(isset($_GET['manufacturers_id']) || isset($_GET['specials']) )) {
    $cPath = push_get_product_path($_GET['products_id']);
  } else {
    $cPath = '';
  }
  
  // show all categories view if adding a product to sortiment from search results
  if (isset($_GET['keywords'])) {
		$cPath = '';  
  }

  if (push_not_null($cPath)) {
    $cPath_array = push_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }
  
	// save selected category in database (only non-parent categories)
	if (push_session_is_registered('customer_id') && push_not_null($cPath) && !push_has_category_subcategories($current_category_id)) {
	
		define('NUMBER_OF_CATEGORIES', 10);
		
		$history_q = push_db_query("	SELECT 	categories
									FROM 	customers_categories_history
									WHERE 	customers_id = " . (int)$customer_id);
	
		if (push_db_num_rows($history_q) == 0) {
			// create a new history for the user
			push_db_query("	INSERT INTO customers_categories_history (customers_id, categories) 
							VALUES 		(" . (int)$customer_id . ", '" . $cPath . "')");
							
		} else {
			$history = push_db_fetch_array($history_q);
			$historyCategories = explode(";", $history['categories']);
			
			if (!in_array($cPath, $historyCategories)) {
				// category not in the history
				
				// add new category at the beginning of the list
				array_unshift($historyCategories, $cPath);
				
				if (sizeof($historyCategories) > NUMBER_OF_CATEGORIES) {
					// remove the last (oldest) element of the list
					array_pop($historyCategories);
				}				
			} else {
				// category in the history - move it t the beginning
				$key = array_search($cPath, $historyCategories);
				unset($historyCategories[$key]);
				array_unshift($historyCategories, $cPath); 
			}
			
			// update the history
			$newHistory = implode(";", $historyCategories);
			push_db_query("	UPDATE 	customers_categories_history 
							SET 	categories = '" . $newHistory . "'");
		}
	}

  
//Add a meaningful title to titletag
	$titleadd = "";
// include the breadcrumb class and start the breadcrumb trail
  require(DIR_WS_CLASSES . 'breadcrumb.php');
  $breadcrumb = new breadcrumb;
//  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
  $breadcrumb->add(HEADER_TITLE_CATALOG, push_href_link(FILENAME_DEFAULT));

// add category names or the manufacturer name to the breadcrumb trail
 
 if(isset($_GET['specials'])){
 	   $breadcrumb->add('Angebote', push_href_link(FILENAME_DEFAULT, 'specials=all'));
 }elseif(isset($_GET['newproducts'])){
 	   $breadcrumb->add('Neue Produkte', push_href_link(FILENAME_DEFAULT, 'newproducts=all'));
 }elseif (isset($cPath_array)) {
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      $categories_query = push_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      if (push_db_num_rows($categories_query) > 0) {
        $categories = push_db_fetch_array($categories_query);
        $breadcrumb->add($categories['categories_name'], push_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
		if($titleadd > "")
		$titleadd .= " - ";
		$titleadd .=  $categories['categories_name'];
		 $actualtitle = $categories['categories_name'];
      } else {
        break;
      }
    }
  }elseif(basename($_SERVER['SCRIPT_NAME']) == FILENAME_BRANDSS) {
		 $breadcrumb->add('Herstellerverzeichnis', push_href_link(FILENAME_MANUFACTURERS));
		  if(!isset($_GET['mid']))
			{
				$_GET['mid']=1;
			}
		$manufacturers_query = push_db_query("select manufacturers_name, manufacturers_story from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['mid'] . "'");
		if (push_db_num_rows($manufacturers_query)) {
		  $manufacturers = push_db_fetch_array($manufacturers_query);
		  $breadcrumb->add($manufacturers['manufacturers_name'], push_href_link(FILENAME_BRANDS, 'manufacturers_id=' . $_GET['mid']));
			if($titleadd > "")
			$titleadd .= " - ";
			$titleadd .= $manufacturers['manufacturers_name'];
			$actualtitle =  $manufacturers['manufacturers_name'];
			define("DESCR",strip_tags($manufacturers['manufacturers_story']));
		}
  } elseif (isset($_GET['mid'])) {
    $manufacturers_query = push_db_query("select manufacturers_name, manufacturers_story  from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['mid'] . "'");
    if (push_db_num_rows($manufacturers_query)) {
      $manufacturers = push_db_fetch_array($manufacturers_query);
      $breadcrumb->add($manufacturers['manufacturers_name'], push_href_link(FILENAME_BRANDS, 'mid=' . $_GET['mid']));
		if($titleadd > "")
		$titleadd .= " - ";
		$titleadd .= $manufacturers['manufacturers_name'];
		$actualtitle =  $manufacturers['manufacturers_name'];
		define("DESCR",strip_tags($manufacturers['manufacturers_story']));
    }
  }
  
  
  $prona='';
  $probi='';
if (isset($_GET['products_id'])){
	$produktq="SELECT pd.products_description, pd.products_name, p.products_image FROM products p JOIN products_description pd ON ( p.products_id = pd.products_id ) WHERE p.products_id ='" . (int)$_GET['products_id']."';";
	if($pres=push_db_fetch_array(push_db_query($produktq))){
		$prona=str_replace(array("'", '"'), '', $pres['products_name']);
		$probi=$pres['products_image'];
		define("DESCR",strip_tags($pres['products_description']));
	}
}

//Canonical URL INSERT

	if(isset($_GET['products_id']))
	{
			$canonicalurl=  FILENAME_PRODUCT_INFO . "?products_id=" . (int)$_GET['products_id'];
	}
	elseif(isset($_GET['cPath']))
	{
			$canonicalurl=  FILENAME_DEFAULT . '?cPath=' . $_GET['cPath'];
			define("DESCR"," ");
	}
	elseif(isset($_GET['mid']))
	{
			$canonicalurl=  FILENAME_BRANDS . '?mid=' . $_GET['mid'];
			define("DESCR"," ");
	}
	elseif(isset($_GET['partner']))
	{
		$canonicalurl= str_replace('/shop/','',$_SERVER['REQUEST_URI']);
		$prona='Partner: ';
		switch($_GET['partner'])
		{
			case 'orderbird':
				$prona.='Orderbird';
				define("DESCR",'Kassensysteme kosten viel Geld, sowohl in der Anschaffung als auch in der Pflege. Mit Orderbird ist seit einiger Zeit eine äußerst flexible iOS-basierte Alternative am Markt. Die App ist kostenfrei, und bei der Anschaffung des Startpakets mit Lizenz, Drucker usw. sparen push-Kunden ab sofort bares Geld:');
			break;
			case 'coffeekids':
				$prona.='Coffeekids';
				define("DESCR",'Im Jahre 2006 haben wir – unterstützt von der Bruesselser Werbeagentur Brandt und De Gelmini – damit begonnen, eine exklusive Becheredition aufzulegen. Wir waren die ersten Unterstützer von Coffee Kids in Deutschland. Unsere Coffee Kids Cups sind seitdem fester Bestandteil unseres Kataloges.');
			break;
			case 'bsoc':
				$prona.='Bruessels School of Coffee';
				define("DESCR",'Die BSOC bietet eine Vielzahl interessanter Kurse rund um das Thema Kakao und andere Getränkespezialitäten an. Neben den intensiven Tageskursen, die Namen wie „Barista Advanced“, „Latte Art“ oder „Seasonal Drinks“ bietet die BSOC einmal monatlich ein exklusives und kostenloses Seminar für push-Kunden an, in dem diese die Handhabung der Produkte erlernen und neue Rezeptideen mit nach Hause nehmen können.');
			break;
			case 'polartwist':
				$prona.='Polartwist';
				define("DESCR", 'Wenn es um die Herstellung erstklassiger Eiscreme geht, ist Polartwist die erste Adresse am Platz. Bei den Spezialisten aus Wildau können Sie nicht nur Maschinen und Rohstoffe einkaufen, sondern auch Seminare zur Speiseeisherstellung buchen.');
			break;
			case 'frozen_yogurt_concept':
				$prona.='Frozen Yogurt Concept';
				define("DESCR",'Frozen Yogurt ... ist in aller Munde. Auf Frozen Yogurt Concept finden Sie alles, was Sie für die Umsetzung Ihrer eigenen Pläne benötigen, von Tipps und Tricks über Anbieterinformationen bis hin zu konkreten Ratschlägen bei der Maschinenauswahl.');
			break;
			case 'climate_partner':
				$prona.='Climate Partner';
				define("DESCR",'Wer im Treibhaus sitzt sollte nicht mit Papier um sich werfen ...
Wir haben bei dem Druck unseres Kataloges Edition Nippon darauf geachtet, dass die bei den Herstellungsprozessen entstehenden Treibhausgase durch zusätzliche Klimaschutzmaßnahmen kompensiert werden.');
			break;
		}
	}
	else
	{
		$canonicalurl= str_replace('/shop/','',$_SERVER['REQUEST_URI']);
		define("DESCR"," ");
	}
define("CANONICALURL", push_href_link($canonicalurl,'','NONSSL'));
define("CLAIM", "Koffein für Ihr Geschäft - Bei uns bekommen Gastronomen, Kaufleute und gewerbliche Gastgeber nicht nur eine Vielzahl profitabler Produkte, sondern auch zahlreiche Ideen rund um Chai, Tee, Schokolade und sommerliche Trendgetränke.");
if($titleadd =='') 
$titleadd = "false";

if($prona == '')
{
	define("TITLEADD",$titleadd);
}
else
{
	define("TITLEADD", $prona);
}
if($probi =='')
{
	define("PROBI", 'bkr-logo.png');
}
else
{
	define("PROBI", $probi);
}
// add the products model to the breadcrumb trail
/*
BOF CUSTOM MODIFICATION
  if (isset($_GET['products_id'])) {
    $model_query = push_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$_GET['products_id'] . "'");
    if (push_db_num_rows($model_query)) {
      $model = push_db_fetch_array($model_query);
      $breadcrumb->add($model['products_model'], push_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']));
    }
  }
EOF CUSTOM MODIFICATION
*/
//INSERT FOR FILTER TRAIL
if(isset($_GET['filter'])){
$fstr ='';
$filter_query_raw = $_GET['filter'];
if(strlen($filter_query_raw)>0){
	$filter_parts= explode(' ',$filter_query_raw);
//2.jeden Teil in query-Teil verarbeiten (Where Part)
		foreach($filter_parts as $fp){
//2.1. jeder Teil der Query wird zu eigenem subquery!
			$wp=explode('=', $fp);
//include manufacturers filter!!
			if($wp[0] == 'manufacturer'){
				if($ts=push_db_fetch_array(push_db_query("SELECT manufacturers_name FROM manufacturers WHERE manufacturers_id='" . mysql_escape_string($wp[1]) . "'" ))){
					$fstr .=$ts['manufacturers_name'] . ", ";
				}
			}else{
				if(count($wp) > 1){
					$wparam= str_replace('_',' ', $wp[1]);
					if($ts=push_db_fetch_array(push_db_query("SELECT products_extra_fields_value From products_to_products_extra_fields WHERE products_extra_fields_value like '" . mysql_escape_string($wparam) . "%'" ))){
						$fstr .=$ts['products_extra_fields_value'] . ", ";
					}
				}else{
					if($ts=push_db_fetch_array(push_db_query("SELECT products_extra_fields_name From products_extra_fields WHERE products_extra_fields_id='" .  mysql_escape_string($wp[0]) . "'"))){
						$fstr .=$ts['products_extra_fields_name'] . ", ";
					}
				}
			}
		}
	}
	if($fstr<>''){
		$fstr= substr($fstr, 0, -2);
		define("FILTERADD", "Filter: " . $fstr . "");

	}

$add_filter_to= '&filter='.$_GET['filter'];
}
else
{
	$add_filter_to='';
}



if(isset($_GET['manufacturers_id'])){
	$current_category_query='manufacturers_id='.$_GET['manufacturers_id']. $add_filter_to;
}elseif(isset($_GET['keywords'])){
	$current_category_query= 'keywords='.$_GET['keywords'] . '&categories_id=' . $_GET['categories_id'] . $add_filter_to;
	if(isset($_GET['search_in_description']))
		$current_category_query.='&search_in_description=1';
}elseif(isset($_GET['specials'])){
	$current_category_query='specials='.$_GET['specials']. $add_filter_to;
}elseif(isset($_GET['newproducts'])){
	$current_category_query='newproducts='.$_GET['newproducts']. $add_filter_to;
}elseif(isset($cPath)){
	$current_category_query= 'cPath='.$cPath. $add_filter_to;
}
if(isset($current_category_query)){
	$current_category_query .= '&';
}else{
	$current_category_query='';
}

if(defined("FILTERADD"))
{
$breadcrumb->add(FILTERADD,push_href_link(FILENAME_DEFAULT, $current_category_query));
}
//EOF


// set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'true');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
  define('WARN_SESSION_AUTO_START', 'true');
  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');

//require(DIR_WS_FUNCTIONS . 'cookievars.php');
//push_setcookie('_pmc', '{"ware":"99,8","name":"General Marek","zeit":"12/05/2018 14.00","artikel":"23","rabatt":"300 Euro bis Rabattstufe X"}', time(), time()+60*60*24*30, '/', $cookie_domain);
//push_setcookie('_pmc','%%', time(), time()+60*60*24*30, '/', $cookie_domain);
setcookie("_pmc", '', $cookie_expiration_time, $cookie_path, $cookie_domain);


//BOF FAQDESK
	define('DIR_WS_RSS', DIR_WS_LIB . 'modules/faqdesk/rss/');
//EOF FAQDESK

include(DIR_WS_BOXES."partner.php");
//BOF CCGV
	require(DIR_WS_LIB . 'add_ccgvdc_application_top.php');
//EOF CCGV

//  define(DIR_MOBILE_CLASSES , 'mobile/includes/classes/');
// require(DIR_MOBILE_CLASSES . 'mobile_redirect.php');
// $mobileRedirect = new mobileRedirect;

/*include (DIR_WS_CLASSES ."discount.php");
$discount = new discount($_SESSION['customer_id']);
*/
include (DIR_WS_CLASSES ."Discounts.php");
$discount = new discounts();

if(isset($_POST['gv_redeem_code']))
{
	$discount->insert_coupon($_POST['gv_redeem_code']);
}
$discount->get_discount_steps();
if(!isset($_SESSION['bonusoff']))
{
	$_SESSION['bonusoff'] = array();
}
// initialize the message stack for output messages
require(DIR_WS_CLASSES . 'message_stack.php');
$messageStack = new messageStack;

?>
