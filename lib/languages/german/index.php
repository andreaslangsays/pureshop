<?php
/*
  $Idä: index.php,v 1.2 2003/07/11 09:04:22 jan0815 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// BOF PRODUCT LISTING WITH ATTRIBUTES
define('TABLE_HEADING_MULTIPLE', 'Anzahl');
// EOF PRODUCT LISTING WITH ATTRIBUTES

define('TEXT_MAIN', '');
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Produkte im %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Wann ist was verfügbar?');
define('TABLE_HEADING_DATE_EXPECTED', 'Datum');

//BOF Featured Products
define('TABLE_HEADING_FEATURED_PRODUCTS', "Neue Produkte und Highlight's");
define('TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY', "Neue Produkte und Hightlight's in %s");
//EOF Featured Products

//define('STANDARD_MWST', 'Preis');
define('STEUERFREI_OHNE_MWST', 'Preis zzgl. MwSt.');
define('OHNE_MWST', 'Nettopreis');
if ( ($category_depth == 'products') || (isset($_GET['manufacturers_id'])) ) {
  define('HEADING_TITLE', '');

//  define('TEXT_NO_PRODUCTS', 'Es gibt keine Produkte in dieser Kategorie.');
  define('TEXT_NO_PRODUCTS2', 'Es gibt kein Produkt, das von diesem Hersteller stammt.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Artikel: ');
  define('TEXT_SHOW', '<b>Suche weiter eingrenzen:</b>');
  define('TEXT_BUY', '1x \'');
  define('TEXT_NOW', '\' bestellen!');
  define('TEXT_ALL_CATEGORIES', 'Alle Kategorien');
  define('TEXT_ALL_MANUFACTURERS', 'Alle Hersteller');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'Onlineshop der Bruesselser Kakaor&ouml;sterei');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Kategorien');
}
?>
