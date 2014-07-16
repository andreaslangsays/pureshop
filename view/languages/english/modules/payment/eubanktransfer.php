<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_EU_BANKTRANSFER_TEXT_TITLE', 'European Bank Transfer');
  define('MODULE_PAYMENT_EU_BANKTRANSFER_TEXT_DESCRIPTION', '' .
													   '<BR>Please transfer the total amount to the following bank account. Enter your name and your order number in the subject field.<BR>' .
                                                       '<BR>Account: ' . MODULE_PAYMENT_EU_KONTONAME .
                                                       '<BR>Account IBAN: ' . MODULE_PAYMENT_EU_IBAN .
                                                       '<br>BIC / SWIFT-Code: ' . MODULE_PAYMENT_EU_BIC .
                                                       '<BR>Bank: ' . MODULE_PAYMENT_EU_BANKNAME .
                                                       '<BR><BR>We will not ship your order until we recieve the payment!<BR>');
  define('MODULE_PAYMENT_EU_BANKTRANSFER_TEXT_EMAIL_FOOTER', str_replace('<BR>','\n',MODULE_PAYMENT_EU_BANKTRANSFER_TEXT_DESCRIPTION));

?>