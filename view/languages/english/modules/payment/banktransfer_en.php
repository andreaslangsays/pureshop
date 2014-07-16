<?php
/*
  $Id: banktransfer.php 86 2003-08-13 18:32:56Z dogu $

  OSC German Banktransfer
  (http://www.oscommerce.com/community/contributions,826)

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 - 2003 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_TITLE', 'Direct debiting');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_DESCRIPTION', 'Direct debiting');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK', 'Direct debit');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_EMAIL_FOOTER', 'Note: You can download our fax form at ' . HTTP_SERVER . DIR_WS_CATALOG . MODULE_PAYMENT_BANKTRANSFER_URL_NOTE . ' and return it to us.');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_INFO', 'Please note that direct debiting can <b>only</b> be effected from a <b>German bank account.</b>');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_OWNER', 'Account holder:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_NUMBER', 'Account number:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_BLZ', 'Bank code number:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_NAME', 'Bank:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_FAX', 'Direct debit authorization will be confirmed by fax');

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR', 'E:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_1', 'Account number and bank code number do not agree! Please check again.');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_2', 'No check-digit calculation method available for this account number!');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_3', 'Account number cannot be verified!');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_4', 'Account number cannot be verified! Please check again.');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_5', 'Bank code number not found! Please check again.');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_8', 'Incorrect bank code number or no bank code number indicated!');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_9', 'No account number indicated!');

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_NOTE', 'Note:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_NOTE2', 'If for safety reasons you do not wish to provide the bank details via the Internet, you may download our');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_NOTE3', 'fax form');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_NOTE4', ' and return the completed form to us.');

  define('JS_BANK_BLZ', 'Please enter the code number of your bank!\n');
  define('JS_BANK_NAME', 'Please enter the name of your bank!\n');
  define('JS_BANK_NUMBER', 'Please enter your account number!\n');
  define('JS_BANK_OWNER', 'Please enter the account holders name!\n');
?>