<?php
/*
  $Id: checkout_process.php,v 1.29 2003/07/11 09:04:22 jan0815 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// ################### added Send order Html mail ##############
require(DIR_WS_LANGUAGES . $language . '/' . 'add_checkout_process.php');
// ################### End added Send order Html mail ##############

define('EMAIL_TEXT_SUBJECT', 'Bestellung');
//Package Tracking Plus BEGIN
//define('EMAIL_TEXT_SUBJECT', 'Order Process');
define('EMAIL_TEXT_GREETING', 'Wir bedanken uns bei Ihnen für ihre Bestellung bei ' . STORE_NAME . '! . If necessary, you may update your order directly by following the link below (login required). If you have questions or comments, please reply to this email.' . "\n\n" . 'With warm regards from your friends at ' . STORE_NAME . "\n");
define('EMAIL_TEXT_SUBJECT_1', ' Bestellung');
define('EMAIL_TEXT_SUBJECT_2', 'wurde aufgenommen.');
//Package Tracking Plus END
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailierte Bestellübersicht:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestelldatum:');
define('EMAIL_TEXT_PRODUCTS', 'Artikel');
define('EMAIL_TEXT_SUBTOTAL', 'Zwischensumme:');
define('EMAIL_TEXT_TAX', 'MwSt.');
define('EMAIL_TEXT_SHIPPING', 'Versandkosten:');
define('EMAIL_TEXT_TOTAL', 'Summe:        ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Lieferanschrift');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Rechnungsanschrift');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Zahlungsweise');

//define('EMAIL_SEPARATOR', '------------------------------------------------------');
//define('TEXT_EMAIL_VIA', 'durch');

//Package Tracking Plus BEGIN
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_INVOICE', ' Invoice');
define('TEXT_EMAIL_VIA', 'durch');
//Package Tracking Plus END


?>
