<?php
// set the level of error reporting
// error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL | E_STRICT);
// error_reporting(0);

/*
 *
 * SECTION 0 INITIALIZATION
 *
 */
 
// Set the local configuration parameters - mainly for developers
if (file_exists('lib/local/configure.php'))
{
include('lib/local/configure.php');
}
else
{
	require('lib/configure.php');
}
// define the project version
define('PROJECT_VERSION', 'PureShop 0.1');
// set the type of request (secure or not)
$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';
if ($request_type == 'NONSSL') 
{
	define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
}
else
{
	define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
}
header("Content-Type: text/html; charset=UTF-8");
// include the list of project filenames
require(DIR_WS_LIB . 'filenames.php');
// include the list of project database tables
require(DIR_WS_LIB . 'database_tables.php');
// include the database functions
require(DIR_WS_FUNCTIONS . 'database.php');
// make a connection to the database... now
push_db_connect() or die('Unable to connect to database server!');
$PHP_SELF = $_SERVER['REQUEST_URI'];
/** /
$configuration_query = push_db_query('select distinct configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
while ($configuration = push_db_fetch_array($configuration_query))
{
	if(!defined($configuration['cfgKey']))
	{
		echo "define('" .$configuration['cfgKey']."','" .$configuration['cfgValue']."');<br>";
		define($configuration['cfgKey'], $configuration['cfgValue']);
	}
}
/**/

include(DIR_WS_LIB .'definitions.php');

?>