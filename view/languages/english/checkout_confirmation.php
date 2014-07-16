<?php
/*
  $Id: checkout_confirmation.php,v 1.24 2003/02/06 17:38:16 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Confirmation');

define('HEADING_TITLE', 'Order Confirmation');

define('HEADING_DELIVERY_ADDRESS', 'Delivery Address');
define('HEADING_SHIPPING_METHOD', 'Shipping way');
define('HEADING_PRODUCTS', 'Products');
define('HEADING_TAX', 'Tax');
define('HEADING_TOTAL', 'Total');
define('HEADING_BILLING_INFORMATION', 'Billing Information');
define('HEADING_BILLING_ADDRESS', 'Billing Address');
define('HEADING_PAYMENT_METHOD', 'Payment Method');
define('HEADING_PAYMENT_INFORMATION', 'Payment Information');
define('HEADING_ORDER_COMMENTS', 'Comments About Your Order');
define('TEXT_TOTAL_VALUE','Subtotal');

define('TEXT_EDIT', 'Edit');
define('HEADING_GIFTWRAP_METHOD', 'GiftWrap');
define('CONDITION_AGREEMENT', '
<h3>Informationen zur Bestellung</h3>
<p>In der Zusammenfassung �ber diesem Text sehen Sie die vorher beschriebenen Waren und/oder Dienstleistungen, die Gegenst�nde des folgenden Vertrages sind, mit ihren jeweiligen Preisen. Die Kosten f�r die Lieferung und die Zahlungsweise sind ebenfalls in der Zusammenfassung enthalten. Um vor dem Abschluss Ihrer Bestellung noch �nderungen vorzunehmen, klicken Sie bitte auf "Bearbeiten" im entsprechenden Teil der Zusammenfassung.</p>
<p>Mit dem Absenden Ihrer Bestellung durch einen Klick auf die "Bestellen"-Schaltfl�che unten rechts auf dieser Seite kommt ein Vertrag zwischen Ihnen und ' . STORE_NAME . ' zustande.</p>
<p>Ihre Bestellung wird nach Abschluss in unserer Datenbank gespeichert. Sie k�nnen den Inhalt dieser Bestellung dann in Ihrem Konto (Account) einsehen.</p>
<p>F�r diesen Vertrag steht Ihnen ein Widerrufsrecht zu, �ber das Sie im folgenden informiert werden.</p>

<h3>Widerrufsbelehrung</h3>
<p>Sie k�nnen Ihre Vertragserkl�rung innerhalb von zwei Wochen ohne Angabe von Gr�nden in Textform (z. B. Brief, Fax, eMail) oder durch R�cksendung der Sache widerrufen. Die Frist beginnt fr�hestens mit Erhalt dieser Belehrung. Zur Wahrung der Widerrufsfrist gen�gt die rechtzeitige Absendung des Widerrufs oder der Sache.</p>
<p>Ein Widerruf ist ausgeschlossen bei:</p>
<ul style="margin-left: 10px;">
	<li>Waren, die nach Kundenspezifikation angefertigt werden</li>
	<li>Waren, die eindeutig auf die pers�nlichen Bed�rfnisse zugeschnitten sind</li>
	<li>Waren, die auf Grund ihrer Beschaffenheit nicht f�r eine R�cksendung geeignet sind</li>
	<li>Waren, die schnell verderben k�nnen oder deren Verfalldatum �berschritten w�rde</li>
	<li>Audio- und Videoaufzeichnungen und Software, die auf Datentr�gern versiegelt geliefert werden</li>
	<li>Waren und Dienstleistungen, die aufgrund ihrer Beschaffenheit gar nicht zur�ckgegeben werden k�nnen</li>
	<li>Zeitungen, Zeitschriften und Illustrierten</li>
</ul>

<h3>Widerrufsfolgen</h3>
<p>Im Falle eines wirksamen Widerrufs sind die beiderseits empfangenen Leistungen zur�ckzugew�hren und ggf. gezogene Nutzungen (z. B. Zinsen) herauszugeben. K�nnen Sie uns die empfangene Leistung ganz oder teilweise nicht oder nur in verschlechtertem Zustand zur�ckgew�hren, m�ssen Sie uns insoweit ggf. Wertersatz leisten. Bei der �berlassung von Sachen gilt dies nicht, wenn die Verschlechterung der Sache ausschlie�lich auf deren Pr�fung - wie sie Ihnen etwa im Ladengesch�ft m�glich gewesen w�re - zur�ckzuf�hren ist. Im �brigen k�nnen Sie die Wertersatzpflicht vermeiden, indem Sie die Sache nicht wie ein Eigent�mer in Gebrauch nehmen und alles unterlassen, was deren Wert beeintr�chtigt. Bei einer R�cksendung aus einer Warenlieferung, deren Bestellwert insgesamt bis zu 40 Euro betr�gt, haben Sie die Kosten der R�cksendung zu tragen, wenn die gelieferte Ware der bestellten entspricht. Anderenfalls ist die R�cksendung f�r Sie kostenfrei zur�ckzusenden. Nicht paketversandf�hige Sachen werden bei Ihnen abgeholt.</p>
<p>Ihr Widerrufsrecht erlischt vorzeitig, wenn Ihr Vertragspartner mit der Ausf�hrung der Dienstleistung mit Ihrer ausdr�cklichen Zustimmung vor Ende der Widerrufsfrist begonnen hat oder Sie diese selbst veranlasst haben.</p>
<p><br><strong>Ich habe die Informationen zur Bestellung und meinem Widerrufsrecht gelesen und verstanden: </strong></p>');

define('CONDITION_AGREEMENT_ERROR', 'Bitte best�tigen Sie durch Anklicken der Checkbox, dass Sie den Text �ber Ihr Widerrufs- und R�ckgaberecht gelesen und verstanden haben.');
define('DELIVERY_ADDRESS','Deliver to');
?>
