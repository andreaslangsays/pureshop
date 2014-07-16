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
  if (!defined('MODULE_PAYMENT_SOFORTUEBERWEISUNG_STATUS') && function_exists('push_catalog_href_link')) {  // we are in admin and module not installed -> show autoinstaller
    define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION', '<div align="center"><a href=' . push_href_link('sofortueberweisung_install.php', 'install=sofortueberweisung', 'SSL') . '>' . push_image(DIR_WS_IMAGES . 'icons/sofortueberweisung_autoinstaller.gif', 'Autoinstaller (empfohlen)') . '</a><br><b>Sofortüberweisung nach der Bestellung</b><br>Während des Zahlungsprozesses wird der Kunde über das Zahlungssystem informiert und sofort nach Abschluss des Bestellvorgangs zum Sofortüberweisungsbezahlformular weitergeleitet. Die Bestellung ist auch im Falle des Abbruchs bereits geschrieben. Er bekommt immer zusätzlich eine Email mit Ihren Kontodaten.</div>');
  } else {
    define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION', '<div align="center"><b>Sofortüberweisung nach der Bestellung</b><br>Während des Zahlungsprozesses wird der Kunde über das Zahlungssystem informiert und sofort nach Abschluss des Bestellvorgangs zum Sofortüberweisungsbezahlformular weitergeleitet. Die Bestellung ist auch im Falle des Abbruchs bereits geschrieben. Er bekommt immer zusätzlich eine Email mit Ihren Kontodaten.</div>');
  }


  define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_TITLE', 'Sofortüberweisung nach der Bestellung');


  // checkout_payment Informationen via Bild
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT', '
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><a href="#" onclick="window.open(\'https://www.sofort-ueberweisung.de/paynetag/anbieter/download/informationen.html\', \'Popup\',\'toolbar=yes,status=no,menubar=no,scrollbars=yes,width=690,height=500\'); return false;">' . push_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . 'sofortueberweisung.gif', 'Sofortüberweisung ist der kostenlose, TÜV-zertifizierte Zahlungsdienst der Payment Network AG. Ihre Vorteile: keine zusätzliche Registrierung, automatische Abbuchung von Ihrem Online-Bankkonto, höchste Sicherheitsstandards und sofortiger Versand von Lagerware. Für die Bezahlung mit Sofortüberweisung benötigen Sie Ihre eBanking Zugangsdaten, d.h. Bankverbindung, Kontonummer, PIN und TAN.') . '</a></td>
      </tr>
    </table>');
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_CONFIRMATION', '
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="main">Sofortüberweisung ist der kostenlose, <a href="#" onclick="window.open(\'https://www.sofortueberweisung.de/cms/index.php?plink=tuev-zertifikat&alink=sicherheit&fs=&l=0\', \'Popup\',\'toolbar=yes,status=no,menubar=no,scrollbars=yes,width=690,height=500\'); return false;">TÜV-zertifizierte</a> Zahlungsdienst der Payment Network AG. Ihre Vorteile: keine zusätzliche Registrierung, automatische Abbuchung von Ihrem Online-Bankkonto, höchste Sicherheitsstandards und sofortiger Versand von Lagerware. Für die Bezahlung mit Sofortüberweisung benötigen Sie Ihre eBanking Zugangsdaten, d.h. Bankverbindung, Kontonummer, PIN und TAN. Mehr Informationen finden Sie hier: <a href="#" onclick="window.open(\'https://www.sofort-ueberweisung.de/paynetag/anbieter/download/informationen.html\', \'Popup\',\'toolbar=yes,status=no,menubar=no,scrollbars=yes,width=690,height=500\'); return false;">www.sofortueberweisung.de</a>.</td>
      </tr>
    </table>');

 // im Verwendungszweck werden folgende Platzhalter ersetzt:
 // {{orderid}}  durch Bestellnummer (nicht bei directes bezahlen)
 // {{order_date}} Bestelldatum
 // {{customer_id}} durch Kundennummer
 // {{customer_name}}  Kundenname
 // {{customer_company}}  Kundenfirma
 // {{customer_email}} Email des Kunden
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_V_ZWECK_1', 'Bestellung bei ' . STORE_NAME);  // max 27 Zeichen
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_V_ZWECK_2', 'Nr: {{orderid}} Kd-Nr. {{customer_id}}'); // max 27 Zeichen
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_EMAIL_FOOTER', "Sollten Sie den Betrag nicht via Sofortüberweisung bezahlt haben, bitten wir Sie den Betrag an:\nKontoinhaber: " .  MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_OWNER . "\nKontonummer: " . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NUMBER . "\nBLZ:" . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_BLZ . "\nBank:" . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NAME  . "\nzu überweisen.  \n\nAdressat:\n" . STORE_NAME_ADDRESS . "\n\n" . 'Ihre Bestellung wird nicht versandt, bis wir das Geld erhalten haben!');
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNG_TEXT_REDIRECT', 'Sie werden nun zu Sofortueberweisung.de weitergeleitet. Sollte dies nicht geschehen bitte den Button drücken');

?>