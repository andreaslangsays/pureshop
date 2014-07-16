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

  define('MODULE_PAYMENT_GERMANRT_TEXT_TITLE', 'Lastschriftverfahren für Wiederverkäufer');
  define('MODULE_PAYMENT_GERMANRT_TEXT_DESCRIPTION', 'Lastschriftverfahren SSL');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK', 'Bankeinzug');
  define('MODULE_PAYMENT_GERMANRT_TEXT_EMAIL_FOOTER', 'Hinweis: Sie können sich unser Faxformular unter ' . HTTP_SERVER . DIR_WS_CATALOG . MODULE_PAYMENT_GERMANRT_URL_NOTE . ' herunterladen und es ausgefüllt an uns zurücksenden.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_INFO', 'Bitte beachten Sie, dass das Lastschriftverfahren <b>nur</b> von einem <b>deutschen Girokonto</b> aus möglich ist');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_OWNER', 'Kontoinhaber:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_NUMBER', 'Kontonummer:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_BLZ', 'Bankleitzahl:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_NAME', 'Bank:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_FAX', 'Einzugsermächtigung wird per Fax bestätigt');

  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR', 'FEHLER: ');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_1', 'Kontonummer und Bankleitzahl stimmen nicht überein, bitte korrigieren Sie Ihre Angabe.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_2', 'Diese Kontonummer ist nicht prüfbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_3', 'Diese Kontonummer ist nicht prüfbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_4', 'Diese Kontonummer ist nicht prüfbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_5', 'Diese Bankleitzahl existiert nicht, bitte korrigieren Sie Ihre Angabe.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_8', 'Sie haben keine korrekte Bankleitzahl eingegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_9', 'Sie haben keine korrekte Kontonummer eingegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_10', 'Sie haben keinen Kontoinhaber angegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_88', 'Leider kann eine Transaktion mit diesem Girokonto nicht ausgeführt werden.');

  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_1', 'Kombination aus Kontonummer und Bankleitzahl abgelehnt.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_2', 'Kontonummer ohne gueltige Berechnungsmethode');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_3', 'Unbekannte Berechnungsmethode.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_4', 'Kontonummer technisch nicht pruefbar.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_5', 'Bankleitzahl nicht in der Datenbank vorhanden.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_8', 'Keine oder ungueltige Bankleitzahl übergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_9', 'Keine oder ungueltige Kontonummer übergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_10', 'Kein oder ungueltiger Kontoinhaber übergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_88', 'Keine Bonität - Transaktion abgelehnt.');

  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE', 'Hinweis:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE2', 'Wenn Sie aus Sicherheitsbedenken keine Bankdaten über das Internet übertragen wollen, können Sie sich unser ');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE3', 'Faxformular (hier)');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE4', ' herunterladen und uns ausgefüllt zusenden. Bitte bestätigen Sie uns das mit der folgenden Checkbox:');

  define('JS_GERMANRT_BLZ', 'Bitte geben Sie die Bankleitzahl Ihrer Bank ein.\n');
  define('JS_GERMANRT_NAME', 'Bitte geben Sie den Namen Ihrer Bank ein.\n');
  define('JS_GERMANRT_NUMBER', 'Bitte geben Sie Ihre Kontonummer ein.\n');
  define('JS_GERMANRT_OWNER', 'Bitte geben Sie den Namen des Kontoinhabers ein.\n');
?>
