<?php
/*
  $Id: mzmt.php,v 1.000 2004-10-29 Josh Dechant Exp $

  Copyright (c) 2004 Josh Dechant

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*//*
  Create text & icons for geo zones and their tables following template below where
    $n = geo zone number (in the shipping module) and
    $j = table number

  MODULE_SHIPPING_MZMT_GEOZONE_$n_TEXT_TITLE
  MODULE_SHIPPING_MZMT_GEOZONE_$n_ICON
  MODULE_SHIPPING_MZMT_GEOZONE_$n_TABLE_$j_TEXT_WAY

  Sample is setup for a 3x3 table (3 Geo Zones with 3 Tables each)
*/

define('MODULE_SHIPPING_MZMT_TEXT_TITLE', 'MultiGeoZone MultiTable');
define('MODULE_SHIPPING_MZMT_TEXT_DESCRIPTION', 'Multiple geo zone shipping with multiple tables to each geo zone.');

define('MODULE_SHIPPING_MZMT_GEOZONE_1_TEXT_TITLE', 'Standard-Versand');
define('MODULE_SHIPPING_MZMT_GEOZONE_1_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_1_TEXT_WAY', '<b>Standard-Versand: **</b><br>Versand innerhalb Deutschlands mit DHL');
define('MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_2_TEXT_WAY', '<b>gek&uuml;hlter Versand innerhalb Deutschlands: **</b><br>Diese Versandart' .
		' empfehlen wir besonders im Sommer f&uuml;r w&auml;rmeempfindliche Artikel wie Schokoladen. Wenn Sie diese Option nicht nutzen, m&uuml;ssen ' .
		' Sie bei hohen Au&szlig;entemperaturen gegebenenfalls mit Lieferverz&ouml;gerungen rechnen.');
define('MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_3_TEXT_WAY', '<b>Teillieferung: **</b> <br>Falls momentan nicht alle Artikel lieferbar sind erhalten Sie 2 Lieferungen:<br>eine Lieferung mit '.
															'den momentan verf&uuml;gbaren Artikeln und sp&auml;ter eine Lieferung mit den &uuml;brigen Artikeln');

define('MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_4_TEXT_WAY', '<b>Teillieferung + Kühlversand: **</b> <br>Falls momentan nicht alle Artikel lieferbar sind erhalten Sie 2 Lieferungen:<br>eine Lieferung mit '.						'den momentan verf&uuml;gbaren Artikeln und sp&auml;ter eine Lieferung mit den &uuml;brigen Artikeln.<br> Diese Versandoption schließt Kühlversand mit ein! <br>
															<small>(Diese Versandart' .
		' empfehlen wir besonders im Sommer f&uuml;r w&auml;rmeempfindliche Artikel wie Schokoladen. Wenn Sie diese Option nicht nutzen, m&uuml;ssen ' .
		' Sie bei hohen Au&szlig;entemperaturen gegebenenfalls mit Lieferverz&ouml;gerungen rechnen.)</small>');

define('MODULE_SHIPPING_MZMT_GEOZONE_2_TEXT_TITLE', 'Versand Zone 2');
define('MODULE_SHIPPING_MZMT_GEOZONE_2_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_1_TEXT_WAY', 'Belgium');
define('MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_3_TEXT_TITLE', 'Versand Zone 3');
define('MODULE_SHIPPING_MZMT_GEOZONE_3_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_1_TEXT_WAY', 'Czech Republic');
define('MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_4_TEXT_TITLE', 'Versand Zone 4');
define('MODULE_SHIPPING_MZMT_GEOZONE_4_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_4_TABLE_1_TEXT_WAY', 'Liechtenstein');
define('MODULE_SHIPPING_MZMT_GEOZONE_4_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_4_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_5_TEXT_TITLE', 'Versand Zone 5');
define('MODULE_SHIPPING_MZMT_GEOZONE_5_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_5_TABLE_1_TEXT_WAY', 'Denmark, France, Luxembourg, Netherlands, Austria, Switzerland');
define('MODULE_SHIPPING_MZMT_GEOZONE_5_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_5_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_6_TEXT_TITLE', 'Versand Zone 6');
define('MODULE_SHIPPING_MZMT_GEOZONE_6_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_6_TABLE_1_TEXT_WAY', 'Slovakia');
define('MODULE_SHIPPING_MZMT_GEOZONE_6_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_6_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_7_TEXT_TITLE', 'Versand Zone 7');
define('MODULE_SHIPPING_MZMT_GEOZONE_7_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_7_TABLE_1_TEXT_WAY', 'PL');
define('MODULE_SHIPPING_MZMT_GEOZONE_7_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_7_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_8_TEXT_TITLE', 'Versand Zone 8');
define('MODULE_SHIPPING_MZMT_GEOZONE_8_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_8_TABLE_1_TEXT_WAY', 'United Kingdom, Italy');
define('MODULE_SHIPPING_MZMT_GEOZONE_8_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_8_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_9_TEXT_TITLE', 'Versand Zone 9');
define('MODULE_SHIPPING_MZMT_GEOZONE_9_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_9_TABLE_1_TEXT_WAY', 'Slovenia, Hungary');
define('MODULE_SHIPPING_MZMT_GEOZONE_9_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_9_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_10_TEXT_TITLE', 'Versand Zone 10');
define('MODULE_SHIPPING_MZMT_GEOZONE_10_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_10_TABLE_1_TEXT_WAY', 'Portugal, Spain');
define('MODULE_SHIPPING_MZMT_GEOZONE_10_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_10_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_11_TEXT_TITLE', 'Versand Zone 11');
define('MODULE_SHIPPING_MZMT_GEOZONE_11_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_11_TABLE_1_TEXT_WAY', 'Sweden');
define('MODULE_SHIPPING_MZMT_GEOZONE_11_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_11_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_12_TEXT_TITLE', 'Versand Zone 12');
define('MODULE_SHIPPING_MZMT_GEOZONE_12_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_12_TABLE_1_TEXT_WAY', 'Finland, Norway');
define('MODULE_SHIPPING_MZMT_GEOZONE_12_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_12_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_13_TEXT_TITLE', 'Versand Zone 13');
define('MODULE_SHIPPING_MZMT_GEOZONE_13_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_13_TABLE_1_TEXT_WAY', 'Estonia, Latvia, Lithuania');
define('MODULE_SHIPPING_MZMT_GEOZONE_13_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_13_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_14_TEXT_TITLE', 'Versand Zone 14');
define('MODULE_SHIPPING_MZMT_GEOZONE_14_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_14_TABLE_1_TEXT_WAY', 'Ireland');
define('MODULE_SHIPPING_MZMT_GEOZONE_14_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_14_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_15_TEXT_TITLE', 'Versand Zone 15');
define('MODULE_SHIPPING_MZMT_GEOZONE_15_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_15_TABLE_1_TEXT_WAY', 'Greece');
define('MODULE_SHIPPING_MZMT_GEOZONE_15_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_15_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_16_TEXT_TITLE', 'Versand Zone 16');
define('MODULE_SHIPPING_MZMT_GEOZONE_16_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_16_TABLE_1_TEXT_WAY', 'Turkey');
define('MODULE_SHIPPING_MZMT_GEOZONE_16_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_16_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_17_TEXT_TITLE', 'Wir senden Ihnen ein Angebot für Versandkosten');
define('MODULE_SHIPPING_MZMT_GEOZONE_17_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_17_TABLE_1_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_17_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_17_TABLE_3_TEXT_WAY', '');

?>
