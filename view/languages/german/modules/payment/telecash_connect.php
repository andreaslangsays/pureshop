<?php
/*
  $Id: paypal_ipn.php,v 2.1.0.0 13/01/2007 16:30:45 Edith Karnitsch Exp $

  Copyright (c) 2004 osCommerce
  Released under the GNU General Public License

  Original Authors: Harald Ponce de Leon, Mark Evans
  Updates by PandA.nl, Navyhost, Zoeticlight, David, gravyface, AlexStudio, windfjf and Terra

*/

  define('MODULE_PAYMENT_TELECASH_CONNECT_TEXT_TITLE', 'Kreditkarte');
  define('MODULE_PAYMENT_TELECASH_CONNECT_TEXT_DESCRIPTION', 'Beim Bestellabschluss mit Kreditkarte zahlen.<br>Manche Anbieter benutzen das <b>3D-Secure</b> Verfahren um Kreditkartenzahlungen über das Internet zu bestätigen. Bitte halten Sie das entsprechende Passwort bereit.');

  // Sets the text for the "continue" button on the PayPal Payment Complete Page
  // Maximum of 60 characters!
  define('CONFIRMATION_BUTTON_TEXT', 'Complete your Order Confirmation');

define('EMAIL_TELECASH_PENDING_NOTICE', 'Your payment is currently pending. We will send you a copy of your order once the payment has cleared.');

define('EMAIL_TEXT_SUBJECT', 'Bestellung');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailierte Bestell&uuml;bersicht:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestelldatum:');
define('EMAIL_TEXT_PRODUCTS', 'Artikel');
define('EMAIL_TEXT_SUBTOTAL', 'Zwischensumme:');
define('EMAIL_TEXT_TAX', 'MwSt.');
define('EMAIL_TEXT_SHIPPING', 'Versandkosten:');
define('EMAIL_TEXT_TOTAL', 'Summe:        ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Lieferanschrift');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Rechnungsanschrift');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Zahlungsweise');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('TEXT_EMAIL_VIA', 'durch');

define('PAYPAL_ADDRESS', 'Telecash Kreditkarte');

/* If you want to include a message with the order email, enter text here: */
/* Use \n for line breaks */
define('MODULE_PAYMENT_TELECASH_CONNECT_TEXT_EMAIL_FOOTER', '');

?>
