<?php
/*
        discus24.de
        eCommerce application
        version 1.6.3   2006/10/04

        First based on:
        osCommerce, Open Source E-Commerce Solutions
        http://www.oscommerce.com

        /includes/languages/german/modules/payment/germanlastschrift.php

        Copyright (c) 2003 osCommerce
        Copyright (c) 2004 - 2005 fmce.de
        Copyright (c) 2004 - 2005 discus24.de
        Copyright (c) 2004 - 2005 Frank Maroke

        Maintainer: Frank Maroke <frank@fmce.de>
        Extensioncode: Marcel Bossert-Schwab <info@opensourcecommerce.de> (mbs)
*/

  define('TABLE_GERMANRT', 'banktransfer');
  define('TABLE_GERMANRT_BLZ', 'banktransfer_blz');

  define('MODULE_PAYMENT_GERMANRT_TEXT_TITLE', 'Lastschriftverfahren f&uuml;r Wiederverk&auml;ufer');
  define('MODULE_PAYMENT_GERMANRT_TEXT_DESCRIPTION', 'Lastschriftverfahren SSL');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK', 'Bankeinzug');
  define('MODULE_PAYMENT_GERMANRT_TEXT_EMAIL_FOOTER', 'Hinweis: Sie k&ouml;nnen sich unser Faxformular unter ' . HTTP_SERVER . DIR_WS_CATALOG . MODULE_PAYMENT_GERMANRT_URL_NOTE . ' herunterladen und es ausgef&uuml;llt an uns zur&uuml;cksenden.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_INFO', 'Bitte beachten Sie, dass das Lastschriftverfahren <b>nur</b> von einem <b>deutschen Girokonto</b> aus m&ouml;glich ist. <br><br>');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_OWNER', 'Kontoinhaber:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_NUMBER', 'Kontonummer:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_BLZ', 'Bankleitzahl:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_NAME', 'Bank:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_FAX', 'Einzugserm&auml;chtigung wird per Fax best&auml;tigt');

  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR', 'FEHLER: ');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_1', 'Kontonummer und Bankleitzahl stimmen nicht &uuml;berein, bitte korrigieren Sie Ihre Angabe.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_2', 'Diese Kontonummer ist nicht pr&uuml;fbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_3', 'Diese Kontonummer ist nicht pr&uuml;fbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_4', 'Diese Kontonummer ist nicht pr&uuml;fbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_5', 'Diese Bankleitzahl existiert nicht, bitte korrigieren Sie Ihre Angabe.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_8', 'Sie haben keine korrekte Bankleitzahl eingegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_9', 'Sie haben keine korrekte Kontonummer eingegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_10', 'Sie haben keinen Kontoinhaber angegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_88', 'Leider kann eine Transaktion mit diesem Girokonto nicht ausgef&uuml;hrt werden.');

  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_1', 'Kombination aus Kontonummer und Bankleitzahl abgelehnt.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_2', 'Kontonummer ohne g&uuml;ltige Berechnungsmethode');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_3', 'Unbekannte Berechnungsmethode.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_4', 'Kontonummer technisch nicht pr&uuml;fbar.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_5', 'Bankleitzahl nicht in der Datenbank vorhanden.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_8', 'Keine oder ung&uuml;ltige Bankleitzahl &uuml;bergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_9', 'Keine oder ung&uuml;ltige Kontonummer &uuml;bergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_10', 'Kein oder ung&uuml;ltiger Kontoinhaber &uuml;bergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_88', 'Keine Bonit&auml;t - Transaktion abgelehnt.');

  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE', 'Hinweis:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE2', 'Wenn Sie aus Sicherheitsbedenken keine Bankdaten über das Internet &uuml;bertragen wollen, können Sie sich unser ');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE3', 'Faxformular (hier)');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE4', ' herunterladen und uns ausgef&uuml;llt zusenden. Bitte best&auml;tigen Sie uns das mit der folgenden Checkbox:');

  define('JS_GERMANRT_BLZ', 'Bitte geben Sie die Bankleitzahl Ihrer Bank ein.\n');
  define('JS_GERMANRT_NAME', 'Bitte geben Sie den Namen Ihrer Bank ein.\n');
  define('JS_GERMANRT_NUMBER', 'Bitte geben Sie Ihre Kontonummer ein.\n');
  define('JS_GERMANRT_OWNER', 'Bitte geben Sie den Namen des Kontoinhabers ein.\n');
?>