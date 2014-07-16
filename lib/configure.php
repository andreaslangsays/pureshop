<?php
/*
  ösCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://localhost'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://localhost'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', 'true'); // secure webserver for checkout 
  define('HTTP_COOKIE_DOMAIN', 'localhost');
  define('HTTPS_COOKIE_DOMAIN', 'localhost');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/pureshop/');
  define('DIR_WS_HTTPS_CATALOG', '/pureshop/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_LIB', 'lib/');
  define('DIR_WS_VIEW', 'view/');
  define('DIR_WS_BOXES', DIR_WS_VIEW . 'boxes/');
  define('DIR_WS_CONTENT', DIR_WS_VIEW .'content/');
  define('DIR_WS_FUNCTIONS', DIR_WS_LIB . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_LIB . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_VIEW . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_VIEW . 'languages/');
  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', 'C:\users\al\www\htdocs\pureshop');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');
  define('DEBUG_ON',true);
if(false){
// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'pureshop');
  define('USE_PCONNECT', 'true'); // use persistent connections?
  define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
}
else
{
    define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'drg_user');
  define('DB_SERVER_PASSWORD', 'boom!');
  define('DB_DATABASE', 'drg_shop');
  define('USE_PCONNECT', 'false'); // use persistent connections?
  define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
}
?>
