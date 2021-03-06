<?php
/*
  $Id: paypal_ipn.php,v 2.1.0.0 13/01/2007 16:30:45 Edith Karnitsch Exp $

  Copyright (c) 2004 osCommerce
  Released under the GNU General Public License

  Original Authors: Harald Ponce de Leon, Mark Evans
  Updates by PandA.nl, Navyhost, Zoeticlight, David, gravyface, AlexStudio, windfjf and Terra

*/

  define('MODULE_PAYMENT_PAYPAL_IPN_TEXT_TITLE', 'PayPal');
  define('MODULE_PAYMENT_PAYPAL_IPN_TEXT_DESCRIPTION', 'Wir empfehlen Ihnen <b>paypal</b> als sichere Zahlungsart.<br> <b>Hinweis:</b> Wir bitten Sie nach Abschluss der Transaktion bei Paypal auf unsere Site zur&uuml;ckzukehren, damit die Bestellung vollendet und der Warenkorb geleert wird.' .
  		'<!--<p style="margin:auto"><a href="#" onclick="javascript:window.open(\'https://www.paypal.com/de/cgi-bin/webscr?cmd=xpt/popup/OLCWhatIsPayPal-outside\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=400, height=450\');">' .
  		'<img  src="https://www.paypal.com/de_DE/i/btn/btn_xpressCheckout.gif" border="0" alt="PayPal"></a>' .
  		'</p>-->');

  // Sets the text for the "continue" button on the PayPal Payment Complete Page
  // Maximum of 60 characters!
  define('CONFIRMATION_BUTTON_TEXT', 'Complete your Order Confirmation');

define('EMAIL_PAYPAL_PENDING_NOTICE', 'Your payment is currently pending. We will send you a copy of your order once the payment has cleared.');

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

define('PAYPAL_ADDRESS', 'Customer PayPal address');

/* If you want to include a message with the order email, enter text here: */
/* Use \n for line breaks */
define('MODULE_PAYMENT_PAYPAL_IPN_TEXT_EMAIL_FOOTER', '');

?>
