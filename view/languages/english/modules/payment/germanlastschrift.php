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

  define('MODULE_PAYMENT_GERMANRT_TEXT_TITLE', 'Lastschriftverfahren f�r Wiederverk�ufer');
  define('MODULE_PAYMENT_GERMANRT_TEXT_DESCRIPTION', 'Lastschriftverfahren SSL');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK', 'Bankeinzug');
  define('MODULE_PAYMENT_GERMANRT_TEXT_EMAIL_FOOTER', 'Hinweis: Sie k�nnen sich unser Faxformular unter ' . HTTP_SERVER . DIR_WS_CATALOG . MODULE_PAYMENT_GERMANRT_URL_NOTE . ' herunterladen und es ausgef�llt an uns zur�cksenden.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_INFO', 'Bitte beachten Sie, dass das Lastschriftverfahren <b>nur</b> von einem <b>deutschen Girokonto</b> aus m�glich ist');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_OWNER', 'Kontoinhaber:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_NUMBER', 'Kontonummer:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_BLZ', 'Bankleitzahl:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_NAME', 'Bank:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_FAX', 'Einzugserm�chtigung wird per Fax best�tigt');

  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR', 'FEHLER: ');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_1', 'Kontonummer und Bankleitzahl stimmen nicht �berein, bitte korrigieren Sie Ihre Angabe.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_2', 'Diese Kontonummer ist nicht pr�fbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_3', 'Diese Kontonummer ist nicht pr�fbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_4', 'Diese Kontonummer ist nicht pr�fbar, bitte kontrollieren zur Sicherheit Sie Ihre Eingabe nochmals.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_5', 'Diese Bankleitzahl existiert nicht, bitte korrigieren Sie Ihre Angabe.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_8', 'Sie haben keine korrekte Bankleitzahl eingegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_9', 'Sie haben keine korrekte Kontonummer eingegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_10', 'Sie haben keinen Kontoinhaber angegeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_88', 'Leider kann eine Transaktion mit diesem Girokonto nicht ausgef�hrt werden.');

  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_1', 'Kombination aus Kontonummer und Bankleitzahl abgelehnt.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_2', 'Kontonummer ohne gueltige Berechnungsmethode');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_3', 'Unbekannte Berechnungsmethode.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_4', 'Kontonummer technisch nicht pruefbar.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_5', 'Bankleitzahl nicht in der Datenbank vorhanden.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_8', 'Keine oder ungueltige Bankleitzahl �bergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_9', 'Keine oder ungueltige Kontonummer �bergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_10', 'Kein oder ungueltiger Kontoinhaber �bergeben.');
  define('MODULE_PAYMENT_GERMANRT_TEXT_BANK_ERROR_INTERNAL_88', 'Keine Bonit�t - Transaktion abgelehnt.');

  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE', 'Hinweis:');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE2', 'Wenn Sie aus Sicherheitsbedenken keine Bankdaten �ber das Internet �bertragen wollen, k�nnen Sie sich unser ');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE3', 'Faxformular (hier)');
  define('MODULE_PAYMENT_GERMANRT_TEXT_NOTE4', ' herunterladen und uns ausgef�llt zusenden. Bitte best�tigen Sie uns das mit der folgenden Checkbox:');

  define('JS_GERMANRT_BLZ', 'Bitte geben Sie die Bankleitzahl Ihrer Bank ein.\n');
  define('JS_GERMANRT_NAME', 'Bitte geben Sie den Namen Ihrer Bank ein.\n');
  define('JS_GERMANRT_NUMBER', 'Bitte geben Sie Ihre Kontonummer ein.\n');
  define('JS_GERMANRT_OWNER', 'Bitte geben Sie den Namen des Kontoinhabers ein.\n');
?>
