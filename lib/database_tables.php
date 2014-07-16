<?php
/*
  $Idä: database_tables.php,v 1.1 2003/03/14 02:10:58 hpdl Exp $

  ösCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Reläsed under the GNU General Public License
*/

// define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTER', 'counter');
  define('TABLE_COUNTER_HISTORY', 'counter_history');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');

	// declaration of custom tables (non-oscommerce tables)

	define('TABLE_FAQDESK', 'faqdesk');
	define('TABLE_FAQDESK_DESCRIPTION', 'faqdesk_description');
	define('TABLE_FAQDESK_TO_CATEGORIES', 'faqdesk_to_categories');
	define('TABLE_FAQDESK_CATEGORIES', 'faqdesk_categories');
	define('TABLE_FAQDESK_CATEGORIES_DESCRIPTION', 'faqdesk_categories_description');
	define('TABLE_FAQDESK_CONFIGURATION', 'faqdesk_configuration');
	define('TABLE_FAQDESK_CONFIGURATION_GROUP', 'faqdesk_configuration_group');
	define('TABLE_FAQDESK_REVIEWS', 'faqdesk_reviews');
	define('TABLE_FAQDESK_REVIEWS_DESCRIPTION', 'faqdesk_reviews_description');

	define('TABLE_USER_TRACKING', 'user_tracking');
	define('TABLE_PRODUCTS_AVAILABILITY', 'products_availability');

	// BOF Separate Pricing per Customer
	//MOD: 2013 create a REAL Seperate per Customer Module!
	define('TABLE_PRODUCTS_CUSTOMERS', 'products_customers');
	define('TABLE_SPECIALS_RETAIL_PRICES', 'specials_retail_prices');
	define('TABLE_PRODUCTS_CUSTOMER_PRICES', 'products_customer_prices');
	define('TABLE_CUSTOMERS_GROUPS', 'customers_groups');
	//this will define the maximum time in minutes between updates of a products_group_prices_cg_# table
	//changes in table specials will trigger an immediate update if a query needs this particular table
	define('MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE', '15');
	// EOF Separate Pricing per Customer

	// BOF Featured Products
	define('TABLE_FEATURED', 'featured');
	// EOF Featured Products

	// BOF Product Extra Fields
	define('TABLE_PRODUCTS_EXTRA_FIELDS', 'products_extra_fields');
	define('TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS', 'products_to_products_extra_fields');
	// EOF Product Extra Fields

	// EOF extra questions
	define('TABLE_QUESTIONS', 'questions');
	define('TABLE_ANSWERS', 'answers');
	// EOF extra questionst

	// Veranstaltungen
	define('BKR_VERANSTALTUNGEN','bkr_veranstaltungen');

	// BOF Package Tracking Plus
	//define('TABLE_USpush_TRACK_NUM', 'uspush_track_num');
	//define('TABLE_USpush_TRACK_NUM', 'uspush_track_num2');
	//define('TABLE_Upush_TRACK_NUM', 'upush_track_num');
	//define('TABLE_Upush_TRACK_NUM', 'upush_track_num2');
	//define('TABLE_FEDEX_TRACK_NUM', 'fedex_track_num');
	//define('TABLE_FEDEX_TRACK_NUM', 'fedex_track_num2');
	define('TABLE_DHL_TRACK_NUM', 'dhl_track_num');
	define('TABLE_DHL_TRACK_NUM2', 'dhl_track_num2');
	// EOF Package Tracking Plus

	// XSell
	define('TABLE_PRODUCTS_XSELL', 'products_xsell');  
	//Wishlist BOF
	define('TABLE_WISHLIST', 'customers_wishlist');
	define('TABLE_WISHLIST_ATTRIBUTES', 'customers_wishlist_attributes');
	//Wishlist EOF

	define('TABLE_SEARCH_QUERIES', 'search_queries');
	define('TABLE_SEARCH_QUERIES_SORTED', 'search_queries_sorted');
	define('TABLE_SEARCHWORD_SWAP', 'searchword_swap');

  // Begin German Banktransfer
  define('TABLE_GERMANBT', 'banktransfer');
  define('TABLE_GERMANBT_BLZ', 'banktransfer_blz');
  // End German Banktransfer
//rmh M-S_multi-stores
  define('TABLE_ADMINISTRATORS', 'administrators');
  define('TABLE_CATEGORIES_TO_STORES', 'categories_to_stores');
  define('TABLE_LANGUAGES_TO_STORES', 'languages_to_stores');
  define('TABLE_MANUFACTURERS_TO_STORES', 'manufacturers_to_stores');
  define('TABLE_PRODUCTS_TO_STORES', 'products_to_stores');
  define('TABLE_STORES', 'stores');

//rmh M-S_pricing
   define('TABLE_PRODUCTS_PRICE_SCHEDULES', 'products_price_schedules');
	?>