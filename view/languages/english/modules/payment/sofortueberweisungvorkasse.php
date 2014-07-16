<?php
/**
 *
 *
 * @version Sofortüberweisung 1.9  27.06.2007
 * @author Henri Schmidhuber  info@in-solution.de
 * @copyright 2006 - 2007 Henri Schmidhuber
 * @link http://www.in-solution.de
 * @link http://www.oscommerce.com
 * @link http://www.sofort-ueberweisung.de
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 *
 ***********************************************************************************
 * this file contains code based on:
 * (c) 2000 - 2001 The Exchange Project
 * (c) 2001 - 2006 osCommerce, Open Source E-Commerce Solutions
 * Released under the GNU General Public License
 ***********************************************************************************
 *
 */

  // Not Installed and Admin?
  if (!defined('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_STATUS') && function_exists('push_catalog_href_link')) {  // we are in admin and module not installed -> show autoinstaller
    define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_DESCRIPTION', '<div align="center"><a href=' . push_href_link('sofortueberweisung_install.php', 'install=sofortueberweisungvorkasse', 'SSL') . '>' . push_image(DIR_WS_IMAGES . 'icons/sofortueberweisung_autoinstaller.gif', 'Autoinstaller (empfohlen)') . '</a><br><b>Vorkasse / Überweisung mit Option Sofortüberweisung</b><br>Dieses Modul ersetzt bzw. ergänzt die einfache Vorkasse. Der Kunde bekommt nach Abschluss der Bestellung Ihre Bankverbindung sowie ein Link zum sofortigen Bezahlung mit Sofortüberweisung angezeigt. Er kann an dieser Stelle nach bereits abgeschlossener Bestellung auch noch für Sofortüberweisung entscheiden</div>');
  } else {
    define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_DESCRIPTION', '<div align="center"><b>Vorkasse / Überweisung mit Option Sofortüberweisung</b><br>Dieses Modul ersetzt bzw. ergänzt die einfache Vorkasse. Der Kunde bekommt nach Abschluss der Bestellung Ihre Bankverbindung sowie ein Link zum sofortigen Bezahlung mit Sofortüberweisung angezeigt. Er kann an dieser Stelle nach bereits abgeschlossener Bestellung auch noch für Sofortüberweisung entscheiden</div>');
  }
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_TITLE', 'Vorkasse / Überweisung mit Option Sofortüberweisung');

  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_DESCRIPTION_CHECKOUT_PAYMENT', '');
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_DESCRIPTION_CHECKOUT_CONFIRMATION', "Bitte überweisen Sie den Betrag nach Abschluss der Bestellung an:<br>Kontoinhaber: " .  MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_OWNER . "<br>Kontonummer: " . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NUMBER . "<br>BLZ:" . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_BLZ . "<br>Bank:" . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NAME);

 // im Verwendungszweck werden folgende Platzhalter ersetzt:
 // {{orderid}}  durch Bestellnummer (nicht bei directes bezahlen)
 // {{order_date}} Bestelldatum
 // {{customer_id}} durch Kundennummer
 // {{customer_name}}  Kundenname
 // {{customer_company}}  Kundenfirma
 // {{customer_email}} Email des Kunden
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_V_ZWECK_1', 'Bestellung bei ' . STORE_NAME);  // max 27 Zeichen
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_V_ZWECK_2', 'Nr: {{orderid}} Kd-Nr. {{customer_id}}'); // max 27 Zeichen
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_EMAIL_FOOTER', "Bitte überweisen Sie den Betrag an:\nKontoinhaber: " .  MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_OWNER . "\nKontonummer: " . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NUMBER . "\nBLZ:" . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_BLZ . "\nBank:" . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NAME  . "\n\nAdressat:\n" . STORE_NAME_ADDRESS . "\n\n" . 'Ihre Bestellung wird nicht versandt, bis wir das Geld erhalten haben!');

  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_SUCCESS_HEADING', 'Wer mit Sofortüberweisung bezahlt, erhält die Ware auch sofort!');
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASSE_TEXT_SUCCESS_INFORMATION', 'Sie haben jetzt die Möglichkeit, Ihre Bestellung mit Sofortüberweisung zu bezahlen.');

?>