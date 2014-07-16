<?php
/**
 * @version sofortueberweisung.de 2.3.0 - $Date: 2010-06-25 14:34:05 +0200 (Fr, 25 Jun 2010) $
 * @author Payment Network AG (integration@payment-network.com)
 * @link http://www.payment-network.com/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 - 2007 Henri Schmidhuber (http://www.in-solution.de)
  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
  
  $Id: pn_sofortueberweisung.php 226 2010-06-25 12:34:05Z thoma $
  
*/

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_TITLE', 'sofortueberweisung.de');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_PUBLIC_TITLE', 'sofort&uuml;berweisung.de');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ALLOWED_TITLE', 'Erlaubte Zonen');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ALLOWED_DESC', 'Geben Sie <b>einzeln</b> die Zonen an, welche f&uuml;r dieses Modul erlaubt sein sollen. (z.B. AT,DE (wenn leer, werden alle Zonen erlaubt))');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS_TITLE', 'sofort&uuml;berweisung.de Modul aktivieren');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS_DESC', 'M&ouml;chten Sie Zahlungen per sofort&uuml;berweisung.de akzeptieren?');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID_TITLE', 'Kundennummer');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID_DESC', 'Ihre Kundennummer bei der sofort&uuml;berweisung.de');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID_TITLE', 'Projektnummer');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID_DESC', 'Die verantwortliche Projektnummer bei der sofort&uuml;berweisung.de, zu der die Zahlung geh&ouml;rt');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD_TITLE', 'Projekt-Passwort:');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD_DESC', 'Das Projekt-Passwort (unter Erweiterte Einstellungen / Passw&ouml;rter und Hashfunktionen)');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD_TITLE', 'Benachrichtigungspasswort:');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD_DESC', 'Das Benachrichtigungspasswort (unter Erweiterte Einstellungen / Passw&ouml;rter und Hashfunktionen)');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM_TITLE', 'Hash-Algorithmus:');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM_DESC', 'Der Hash-Algorithmus (unter Erweiterte Einstellungen / Passw&ouml;rter und Hashfunktionen)');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE_TITLE', 'Hash-Algorithmus: MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM<br /><br />Zahlungszone');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_CURRENCY_TITLE', 'Transaktionsw&auml;hrung');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_CURRENCY_DESC', 'Empfangende W&auml;hrung laut sofort&uuml;berweisung.de Projekteinstellung');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID_TITLE', 'best&auml;tigter Bestellstatus');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID_DESC', 'Order Status nach Eingang einer Bestellung, bei der von sofort&uuml;berweisung.de eine erfolgreiche Zahlungsbest&auml;tigung &uuml;bermittelt wurde');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID_TITLE', 'Tempor&auml;rer Bestellstatus');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID_DESC', 'Bestellstatus f&uuml;r noch nicht abgeschlossene Transaktionen');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_NAME', 'sofort&uuml;berweisung Vorbereitung');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_UNC_STATUS_ID_TITLE', 'Zu &uuml;berpr&uuml;fender Bestellstatus');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_UNC_STATUS_ID_DESC', 'Order Status nach Eingang einer Bestellung bei der eine fehlerhafte Zahlungsbest&auml;tigung &uuml;bermittelt wurde');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1_TITLE', 'Verwendungszweck Zeile 1');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1_DESC', 'Im Verwendungszweck (maximal 27 Zeichen) kann nur die Bestellnummer und Kundennummer stehen. Die Werte m&uuml;ssen eindeutig sein');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2_TITLE', 'Verwendungszweck Zeile 2');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2_DESC', 'Im Verwendungszweck (maximal 27 Zeichen) werden folgende Platzhalter ersetzt:<br /> {{order_id}}<br />{{order_date}}<br />{{customer_id}}<br />{{customer_name}}<br />{{customer_company}}<br />{{customer_email}}');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE_TITLE', 'Zahlungsauswahl Grafik / Text');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE_DESC', 'Angezeigte Grafik / Text bei der Auswahl Zahlungsoptionen');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_TEXT', 'Online-&Uuml;berweisung mit T&Uuml;V gepr&uuml;ftem Datenschutz ohne Registrierung. Bitte halten Sie Ihre Online-Banking-Daten (PIN/TAN) bereit.  Dienstleistungen/Waren werden bei Verf&uuml;gbarkeit SOFORT geliefert bzw. versendet!');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGEALT', 'sofort&uuml;berweisung.de ist der kostenlose, T&Uuml;V-zertifizierte Zahlungsdienst der Payment Network AG. Ihre Vorteile: keine zus&auml;tzliche Registrierung, automatische Abbuchung von Ihrem Online-Bankkonto, h&ouml;chste Sicherheitsstandards und sofortiger Versand von Lagerware. F&uuml;r die Bezahlung mit sofort&uuml;berweisung.de ben&ouml;tigen Sie Ihre eBanking Zugangsdaten, d.h. Bankverbindung, Kontonummer, PIN und TAN.');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_ERROR_HEADING', 'Folgender Fehler wurde von sofort&uuml;berweisung.de w&auml;hrend des Prozesses gemeldet:');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_ERROR_MESSAGE', 'Zahlung via sofort&uuml;berweisung.de ist leider nicht m&ouml;glich, oder wurde auf Kundenwunsch abgebrochen. Bitte w&auml;hlen sie ein andere Zahlungsweise.');
  
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION', (MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS != 'True' ? 
	'<form action="'.push_href_link(FILENAME_MODULES, '', 'SSL').'" method="get"><input type="hidden" name="set" value="payment">
	<input type="hidden" name="module" value="pn_sofortueberweisung"><input type="hidden" name="action" value="install">
	<input type="hidden" name="autoinstall" value="1"><input type="submit" value="Autoinstaller (empfohlen)" /></form><br />' : '').'<br />
	<b>sofort&uuml;berweisung.de</b><br>Sobald der Kunde sofort&uuml;berweisung.de ausgew&auml;hlt hat und auf Bestellen klickt, wird eine tempor&auml;re Bestellung angelegt. 
	Ist die Zahlung erfolgreich, wird die Bestellung fest in die Datenbank eingetragen. Bei Abbruch wird die Bestellung r&uuml;ckg&auml;ngig gemacht und die Bestellnummer 
	verworfen, so dass bei der n&auml;chsten Bestellung die Bestellnummer um eins erh&ouml;ht wird. Dadurch sollten die Bestellnummern nicht als Rechnungsnummern 
	verwendet werden, da diese nicht fortlaufend sind.');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGE', '
    <!--<a href="https://www.sofortueberweisung.de/funktionsweise" target="_blank">{{image}}</a>-->
    %s');

  define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_CONFIRMATION', '
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="main"><p>Nach Best&auml;tigung der Bestellung werden Sie zum Zahlungssytem von Sofort&uuml;berweisung weitergeleitet und k&ouml;nnen dort eine Online-Überweisung duchf&uuml;hren.</p><p>Hierf&uuml;r ben&ouml;tigen Sie Ihre eBanking Zugangsdaten, d.h. Bankverbindung, Kontonummer, PIN und TAN. Mehr Informationen finden Sie hier: <a href="https://www.sofortueberweisung.de/funktionsweise" target="_blank">sofort&uuml;berweisung.de</a>.</p></td>
      </tr>
    </table>');

//see also /includes/languages/german/checkout_process.php
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_EMAIL_HTML_TEXT', '  <table style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small" width="100%"><tr><td>
    <p>Vielen Dank f&uuml;r Ihre Bestellung</p>
    <table style="border-top:1px solid; border-bottom:1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small" width="100%" border="0">
      <tr bgcolor="#F1F1F1">
        <td width="50%">
            <strong>{{EMAIL_TEXT_DELIVERY_ADDRESS}}:</strong>
        </td>
        <td>
            <strong>{{EMAIL_TEXT_BILLING_ADDRESS}}:</strong>
        </td>
      </tr>
      <tr>
        <td>
          {{DELIVERY_ADRESS}}
        </td>
        <td>
          {{BILLING_ADRESS}}
        </td>
      </tr>
    </table>
	<p>
		{{EMAIL_TEXT_ORDER_NUMBER}} <strong>{{ORDER_ID}}</strong>
	</p>
	<p>
	{{EMAIL_TEXT_DATE_ORDERED}} <strong>{{DATE_ORDERED}}</strong>
	</p>
	<p>
	Kommentar: <strong>{{CUSTOMER_COMMENT}}</strong>
	</p>
	<p>
		{{EMAIL_TEXT_INVOICE_URL}} 
	  <strong><a href="{{INVOICE_URL}}">{{INVOICE_URL}}</a></strong>
	</p>
    <table style="border-bottom:1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small" width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#F1F1F1">
			<td class="c3" align="left">
			  <div style="text-align: left">
				<strong>{{EMAIL_TEXT_PRODUCTS}}:</strong>
			  </div>
			</td>
		</tr>
		<tr>
			<td>
			  {{Item_List}}
			</td>
		</tr>
    </table>
    <div style="text-align: right">
      {{List_Total}}
    </div><br /><br />
    <p>{{EMAIL_TEXT_PAYMENT_METHOD}}: {{Payment_Modul_Text}}</p>
    {{Payment_Modul_Text_Footer}}
</td></tr></table>');

//see also /includes/languages/german/checkout_process.php
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_EMAIL_TEXT', '{{STORE_NAME}}
{{EMAIL_SEPARATOR}}
{{EMAIL_TEXT_ORDER_NUMBER}} {{ORDER_ID}}
{{EMAIL_TEXT_INVOICE_URL}} {{INVOICE_URL}}
{{EMAIL_TEXT_DATE_ORDERED}} {{DATE_ORDERED}}

{{EMAIL_TEXT_PRODUCTS}}:
{{EMAIL_SEPARATOR}}
{{Item_List}}
{{EMAIL_SEPARATOR}}
{{List_Total}}

{{EMAIL_TEXT_BILLING_ADDRESS}}:
{{EMAIL_SEPARATOR}}
{{BILLING_ADRESS}}

{{EMAIL_TEXT_DELIVERY_ADDRESS}}:
{{EMAIL_SEPARATOR}}
{{DELIVERY_ADRESS}}

Kommentar:
{{EMAIL_SEPARATOR}}
{{CUSTOMER_COMMENT}}

{{EMAIL_TEXT_PAYMENT_METHOD}}
{{EMAIL_SEPARATOR}}
{{Payment_Modul_Text}}

{{Payment_Modul_Text_Footer}}'); 
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_EMAIL_FOOTER', 'Ihre Zahlung mit sofortueberweisung.de wurde entgegengenommen.');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_EMAIL_SUBJECT', '{{EMAIL_TEXT_SUBJECT}}: {{ORDER_ID}}');

  
?>