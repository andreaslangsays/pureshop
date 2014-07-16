<?php
/*
  $Idä: checkout_confirmation.php,v 1.27 2003/02/16 00:42:02 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Kasse');
define('NAVBAR_TITLE_2', 'Best&auml;tigung');

define('HEADING_TITLE', 'Bestellung aufgeben:');

define('HEADING_DELIVERY_ADDRESS', 'Versandadresse');
define('HEADING_SHIPPING_METHOD', 'Versandart');
define('HEADING_PRODUCTS', 'Produkte');
define('HEADING_TAX', 'MwSt.');
define('HEADING_TOTAL', 'Summe');
define('HEADING_BILLING_INFORMATION', 'Rechnungsinformationen');
define('HEADING_BILLING_ADDRESS', 'Rechnungsadresse');
define('HEADING_PAYMENT_METHOD', 'Zahlungsweise');
define('HEADING_PAYMENT_INFORMATION', 'Zahlungsinformationen');
define('HEADING_ORDER_COMMENTS', 'Meine Anmerkung zur Bestellung');


define('TITLE_SHIPPING_ADDRESS', 'Lieferdresse');
define('TITLE_SHIPPING_ADDRESS_CHOOSE', 'Lieferdresse wählen:');
define('TITLE_PAYMENT_ADDRESS', "Rechnung senden an:");
define('TEXT_EDIT', 'Bearbeiten');
define('TEXT_TOTAL_VALUE','Warenwert');
define('CONDITIONS',"Unsere AGB");
define('HEADING_GIFTWRAP_METHOD', 'Geschenk');
define('CONDITION_AGREEMENT', '
<h3>Informationen zur Bestellung</h3>
<p>In der Zusammenfassung &uuml;ber diesem Text sehen Sie die vorher beschriebenen Waren und/oder Dienstleistungen, die Gegenst&auml;nde des folgenden Vertrages sind, mit ihren jeweiligen Preisen. Die Kosten f&uuml;r die Lieferung und die Zahlungsweise sind ebenfalls in der Zusammenfassung enthalten. Um vor dem Abschluss Ihrer Bestellung noch &auml;nderungen vorzunehmen, klicken Sie bitte auf "Bearbeiten" im entsprechenden Teil der Zusammenfassung.</p>
<p>Mit dem Absenden Ihrer Bestellung durch einen Klick auf die "Bestellen"-Schaltfl&auml;che unten rechts auf dieser Seite kommt ein Vertrag zwischen Ihnen und ' . STORE_NAME . ' zustande.</p>
<p>Ihre Bestellung wird nach Abschluss in unserer Datenbank gespeichert. Sie k&ouml;nnen den Inhalt dieser Bestellung dann in Ihrem Konto (Account) einsehen.</p>
<p>F&uuml;r diesen Vertrag steht Ihnen ein Widerrufsrecht zu, &uuml;ber das Sie im folgenden informiert werden.</p>

<h3>Widerrufsbelehrung</h3>
<p>Sie k&ouml;nnen Ihre Vertragserkl&auml;rung innerhalb von zwei Wochen ohne Angabe von Gr&uuml;nden in Textform (z. B. Brief, Fax, eMail) oder durch R&uuml;cksendung der Sache widerrufen. Die Frist beginnt fr&uuml;hestens mit Erhalt dieser Belehrung. Zur Wahrung der Widerrufsfrist gen&uuml;gt die rechtzeitige Absendung des Widerrufs oder der Sache.</p>
<p>Ein Widerruf ist ausgeschlossen bei:</p>
<ul style="margin-left: 10px;">
	<li>Waren, die nach Kundenspezifikation angefertigt werden</li>
	<li>Waren, die eindeutig auf die pers&ouml;nlichen Bed&uuml;rfnisse zugeschnitten sind</li>
	<li>Waren, die auf Grund ihrer Beschaffenheit nicht f&uuml;r eine R&uuml;cksendung geeignet sind</li>
	<li>Waren, die schnell verderben k&ouml;nnen oder deren Verfalldatum &uuml;berschritten w&uuml;rde</li>
	<li>Audio- und Videoaufzeichnungen und Software, die auf Datentr&auml;gern versiegelt geliefert werden</li>
	<li>Waren und Dienstleistungen, die aufgrund ihrer Beschaffenheit gar nicht zur&uuml;ckgegeben werden k&ouml;nnen</li>
	<li>Zeitungen, Zeitschriften und Illustrierten</li>
</ul>

<h3>Widerrufsfolgen</h3>
<p>Im Falle eines wirksamen Widerrufs sind die beiderseits empfangenen Leistungen zur&uuml;ckzugew&auml;hren und ggf. gezogene Nutzungen (z. B. Zinsen) herauszugeben. K&ouml;nnen Sie uns die empfangene Leistung ganz oder teilweise nicht oder nur in verschlechtertem Zustand zur&uuml;ckgew&auml;hren, m&uuml;ssen Sie uns insoweit ggf. Wertersatz leisten. Bei der &uuml;berlassung von Sachen gilt dies nicht, wenn die Verschlechterung der Sache ausschlie&szlig;lich auf deren Pr&uuml;fung - wie sie Ihnen etwa im Ladengesch&auml;ft m&ouml;glich gewesen w&auml;re - zur&uuml;ckzuf&uuml;hren ist. Im &uuml;brigen k&ouml;nnen Sie die Wertersatzpflicht vermeiden, indem Sie die Sache nicht wie ein Eigent&uuml;mer in Gebrauch nehmen und alles unterlassen, was deren Wert beeintr&auml;chtigt. Bei einer R&uuml;cksendung aus einer Warenlieferung, deren Bestellwert insgesamt bis zu 40 Euro betr&auml;gt, haben Sie die Kosten der R&uuml;cksendung zu tragen, wenn die gelieferte Ware der bestellten entspricht. Anderenfalls ist die R&uuml;cksendung f&uuml;r Sie kostenfrei zur&uuml;ckzusenden. Nicht paketversandf&auml;hige Sachen werden bei Ihnen abgeholt.</p>
<p>Ihr Widerrufsrecht erlischt vorzeitig, wenn Ihr Vertragspartner mit der Ausf&uuml;hrung der Dienstleistung mit Ihrer ausdr&uuml;cklichen Zustimmung vor Ende der Widerrufsfrist begonnen hat oder Sie diese selbst veranlasst haben.</p>
<p><br><strong>Ich habe die Informationen zur Bestellung und meinem Widerrufsrecht gelesen und verstanden: </strong></p>');

define('CONDITION_AGREEMENT_ERROR', 'Bitte best&auml;tigen Sie durch Anklicken der Checkbox, dass Sie den Text &uuml;ber Ihr Widerrufs- und R&uuml;ckgaberecht gelesen und verstanden haben.');
define('DELIVERY_ADDRESS','Lieferanschrift');
?>