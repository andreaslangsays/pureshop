<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_EU_BANKTRANSFER_TEXT_TITLE', 'Europäische Banküberweisung');
  define('MODULE_PAYMENT_EU_BANKTRANSFER_TEXT_DESCRIPTION', '' .
													   '<BR>Bitte verwenden Sie folgende Daten für die Überweisung des Gesamtbetrages.<BR>
Als Verwendungszweck geben Sie bitte Ihren Namen und Ihre Bestellnummer an.<BR>' .
                                                       '<BR>Konto: ' . MODULE_PAYMENT_EU_KONTONAME .
                                                       '<BR>Konto IBAN: ' . MODULE_PAYMENT_EU_IBAN .
                                                       '<br>BIC / SWIFT-Code: ' . MODULE_PAYMENT_EU_BIC .
                                                       '<BR>Bank: ' . MODULE_PAYMENT_EU_BANKNAME .
                                                       '<BR><BR>Ihre Bestellung wird nicht versandt, bis wir das Geld erhalten haben!<BR>');
  define('MODULE_PAYMENT_EU_BANKTRANSFER_TEXT_EMAIL_FOOTER', str_replace('<BR>','\n',MODULE_PAYMENT_EU_BANKTRANSFER_TEXT_DESCRIPTION));

?>