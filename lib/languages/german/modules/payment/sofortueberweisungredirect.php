<?php
/**
 *
 *
 * @version Sofort�berweisung 1.9  27.06.2007
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





  if (!defined('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_STATUS') && function_exists('push_catalog_href_link')) {  // we are in admin and module not installed -> show autoinstaller
    define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_DESCRIPTION', '<div align="center"><a href=' . push_href_link('sofortueberweisung_install.php', 'install=sofortueberweisungredirect', 'SSL') . '>' . push_image(DIR_WS_IMAGES . 'icons/sofortueberweisung_autoinstaller.gif', 'Autoinstaller (empfohlen)') . '</a><br><b>Direktes Bezahlen mit Sofort�berweisung.</b><br><br><small>Der Kunde wird vor Abschluss des Bestellvorgangs zur Sofort�berweisungseite geleitet. Mit Abschluss der Zahlung wird die Bestellung in die Shopdatenbank geschrieben. Bricht der Kunde ab kommt er zur�ck zur Zahlungsausswahlseite des Shops.<br><b>Hinweis zu diesem Modul:</b><br>Schliest der Kunde bei Sofort�berweisung den Browser, bzw. scheitert der R�cksprung wird keine Bestellung im Shop aufgenommen.</small><br><b>Bei gleichzeitiger Verwendung mit einem der anderen Sofort�berweisungsmodule mu� ein eigenes Projekt bei Sofort�berweisung angelegt werden.</b></div>');
  } else {
    define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_DESCRIPTION', '<b>Direktes Bezahlen mit Sofort�berweisung.</b><br><br><small>Der Kunde wird vor Abschluss des Bestellvorgangs zur Sofort�berweisungseite geleitet. Mit Abschluss der Zahlung wird die Bestellung in die Shopdatenbank geschrieben. Bricht der Kunde ab kommt er zur�ck zur Zahlungsausswahlseite des Shops.<br><b>Hinweis zu diesem Modul:</b><br>Schliest der Kunde bei Sofort�berweisung den Browser, bzw. scheitert der R�cksprung wird keine Bestellung im Shop aufgenommen.</small><br><b>Bei gleichzeitiger Verwendung mit einem der anderen Sofort�berweisungsmodule mu� ein eigenes Projekt bei Sofort�berweisung angelegt werden.</b>');
  }

  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_TITLE', 'Direktes Bezahlen mit Sofort�berweisung (empfohlen)');

  // checkout_payment Informationen via Bild
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_DESCRIPTION_CHECKOUT_PAYMENT', '
    <!--<a href="#" onclick="window.open(\'https://www.sofort-ueberweisung.de/paynetag/anbieter/download/informationen.html\', \'Popup\',\'toolbar=yes,status=no,menubar=no,scrollbars=yes,width=690,height=500\'); return false;">' . push_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . 'sofortueberweisung.gif', 'Sofort�berweisung ist der kostenlose, T�V-zertifizierte Zahlungsdienst der Payment Network AG. Ihre Vorteile: keine zus�tzliche Registrierung, automatische Abbuchung von Ihrem Online-Bankkonto, h�chste Sicherheitsstandards und sofortiger Versand von Lagerware. F�r die Bezahlung mit Sofort�berweisung ben�tigen Sie Ihre eBanking Zugangsdaten, d.h. Bankverbindung, Kontonummer, PIN und TAN.') . '</a>-->');

  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_DESCRIPTION_CHECKOUT_CONFIRMATION', '
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="main">Sofort�berweisung ist der kostenlose, <a href="#" onclick="window.open(\'https://www.sofortueberweisung.de/cms/index.php?plink=tuev-zertifikat&alink=sicherheit&fs=&l=0\', \'Popup\',\'toolbar=yes,status=no,menubar=no,scrollbars=yes,width=690,height=500\'); return false;">T�V-zertifizierte</a> Zahlungsdienst der Payment Network AG. Ihre Vorteile: keine zus�tzliche Registrierung, automatische Abbuchung von Ihrem Online-Bankkonto, h�chste Sicherheitsstandards und sofortiger Versand von Lagerware. F�r die Bezahlung mit Sofort�berweisung ben�tigen Sie Ihre eBanking Zugangsdaten, d.h. Bankverbindung, Kontonummer, PIN und TAN. Mehr Informationen finden Sie hier: <a href="#" onclick="window.open(\'https://www.sofort-ueberweisung.de/paynetag/anbieter/download/informationen.html\', \'Popup\',\'toolbar=yes,status=no,menubar=no,scrollbars=yes,width=690,height=500\'); return false;">www.sofortueberweisung.de</a>.</td>
      </tr>
    </table>');


 // im Verwendungszweck werden folgende Platzhalter ersetzt:
 // {{order_date}} durch Bestelldatum
 // {{customer_id}} durch Kundennummer der Datenbank
 // {{customer_name}}  durch Kundenname
 // {{customer_company}} durch Kundenfirma
 // {{customer_email}} durch Email des Kunden

  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_V_ZWECK_1', 'Bestellung bei ' . STORE_NAME);  // max 27 Zeichen
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_V_ZWECK_2', 'Kd-Nr. {{customer_id}} {{customer_name}}'); // max 27 Zeichen
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_EMAIL_FOOTER', '');
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_REDIRECT', 'Sie werden nun zu Sofortueberweisung.de weitergeleitet. Sollte dies nicht geschehen bitte den Button dr�cken');

  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_ERROR_HEADING', 'Folgender Fehler wurde von Sofort�berweisung w�hrend des Prozesses gemeldet:');
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_ERROR_MESSAGE', 'Zahlung via Sofort�berweisung ist leider nicht m�glich, oder wurde auf Kundenwunsch abgebrochen. Bitte w�hlen sie ein andere Zahlungsweise.');
  define('MODULE_PAYMENT_SOFORTUEBERWEISUNGREDIRECT_TEXT_CHECK_ERROR', 'Sofort�berweisungs Transaktionscheck fehlgeschlagen. Bitte manuell �berpr�fen');

?>