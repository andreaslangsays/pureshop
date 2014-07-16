<?php
/*
  $Id: germanbanktransfer.php 157 2005-04-07 20:33:35Z dogu $

  OSC German Banktransfer
  (http://www.oscommerce.com/community/contributions,826)

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 - 2003 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_PAYMENT_GERMANBT_TEXT_TITLE', 'Lastschriftverfahren');
  define('MODULE_PAYMENT_GERMANBT_TEXT_DESCRIPTION', 'Lastschriftverfahren');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK', 'Bankeinzug');
  define('MODULE_PAYMENT_GERMANBT_TEXT_EMAIL_FOOTER', 'Hinweis: Sie können sich unser Faxformular unter ' . HTTP_SERVER . DIR_WS_CATALOG . MODULE_PAYMENT_GERMANBT_URL_NOTE . ' herunterladen und es ausgefüllt an uns zurücksenden.');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_INFO', 'Bitte beachten Sie, dass das Lastschriftverfahren <b>nur</b> von einem <b>deutschen Girokonto</b> aus möglich ist.' .
  		'<br>Bitte haben Sie Verständnis dafür, dass es nach Zahlungseingang in der Regel <u>mindestens</u> <b>2-3 Werktage</b> dauert, bis Ihre Zahlung bestätigt worden ist.<br>' . 
  		'<hr><small>Bitte beachten Sie auch, dass wir im Falle einer Rücklastschrift eine Gebühr von 15 EUR erheben müssen.<small>');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_OWNER', 'Kontoinhaber:');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_NUMBER', 'Kontonummer:');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_BLZ', 'BLZ:');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_NAME', 'Bank:');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_FAX', 'Einzugsermächtigung wird per Fax bestätigt');

  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR', 'FEHLER: ');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_1', 'Kontonummer und BLZ stimmen nicht überein! Bitte überprüfen Sie Ihre Angaben nochmals.');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_2', 'Für diese Kontonummer ist kein Prüfziffernverfahren definiert!');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_3', 'Kontonummer nicht prüfbar!');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_4', 'Kontonummer nicht prüfbar! Bitte überprüfen Sie Ihre Angaben nochmals.');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_5', 'Bankleitzahl nicht gefunden! Bitte überprüfen Sie Ihre Angaben nochmals.');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_8', 'Fehler bei der Bankleitzahl oder keine Bankleitzahl angegeben!');
  define('MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_9', 'Keine Kontonummer angegeben!');

  define('MODULE_PAYMENT_GERMANBT_TEXT_NOTE', 'Hinweise:');
  define('MODULE_PAYMENT_GERMANBT_TEXT_NOTE2', 'Wenn Sie aus Sicherheitsbedenken keine Bankdaten über das Internet übertragen wollen, können Sie sich unser ');
  define('MODULE_PAYMENT_GERMANBT_TEXT_NOTE3', 'Faxformular (hier)');
  define('MODULE_PAYMENT_GERMANBT_TEXT_NOTE4', ' herunterladen und uns ausgefüllt zusenden. Bitte bestätigen Sie uns das mit der folgenden Checkbox:');

  define('JS_GERMANBT_BLZ', 'Bitte geben Sie die BLZ Ihrer Bank ein!\n');
  define('JS_GERMANBT_NAME', 'Bitte geben Sie den Namen Ihrer Bank ein!\n');
  define('JS_GERMANBT_NUMBER', 'Bitte geben Sie Ihre Kontonummer ein!\n');
  define('JS_GERMANBT_OWNER', 'Bitte geben Sie den Namen des Kontobesitzers ein!\n');
?>
