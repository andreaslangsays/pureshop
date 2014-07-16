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
<p>In der Zusammenfassung über diesem Text sehen Sie die vorher beschriebenen Waren und/oder Dienstleistungen, die Gegenstände des folgenden Vertrages sind, mit ihren jeweiligen Preisen. Die Kosten für die Lieferung und die Zahlungsweise sind ebenfalls in der Zusammenfassung enthalten. Um vor dem Abschluss Ihrer Bestellung noch Änderungen vorzunehmen, klicken Sie bitte auf "Bearbeiten" im entsprechenden Teil der Zusammenfassung.</p>
<p>Mit dem Absenden Ihrer Bestellung durch einen Klick auf die "Bestellen"-Schaltfläche unten rechts auf dieser Seite kommt ein Vertrag zwischen Ihnen und ' . STORE_NAME . ' zustande.</p>
<p>Ihre Bestellung wird nach Abschluss in unserer Datenbank gespeichert. Sie können den Inhalt dieser Bestellung dann in Ihrem Konto (Account) einsehen.</p>
<p>Für diesen Vertrag steht Ihnen ein Widerrufsrecht zu, über das Sie im folgenden informiert werden.</p>

<h3>Widerrufsbelehrung</h3>
<p>Sie können Ihre Vertragserklärung innerhalb von zwei Wochen ohne Angabe von Gründen in Textform (z. B. Brief, Fax, eMail) oder durch Rücksendung der Sache widerrufen. Die Frist beginnt frühestens mit Erhalt dieser Belehrung. Zur Wahrung der Widerrufsfrist genügt die rechtzeitige Absendung des Widerrufs oder der Sache.</p>
<p>Ein Widerruf ist ausgeschlossen bei:</p>
<ul style="margin-left: 10px;">
	<li>Waren, die nach Kundenspezifikation angefertigt werden</li>
	<li>Waren, die eindeutig auf die persönlichen Bedürfnisse zugeschnitten sind</li>
	<li>Waren, die auf Grund ihrer Beschaffenheit nicht für eine Rücksendung geeignet sind</li>
	<li>Waren, die schnell verderben können oder deren Verfalldatum überschritten würde</li>
	<li>Audio- und Videoaufzeichnungen und Software, die auf Datenträgern versiegelt geliefert werden</li>
	<li>Waren und Dienstleistungen, die aufgrund ihrer Beschaffenheit gar nicht zurückgegeben werden können</li>
	<li>Zeitungen, Zeitschriften und Illustrierten</li>
</ul>

<h3>Widerrufsfolgen</h3>
<p>Im Falle eines wirksamen Widerrufs sind die beiderseits empfangenen Leistungen zurückzugewähren und ggf. gezogene Nutzungen (z. B. Zinsen) herauszugeben. Können Sie uns die empfangene Leistung ganz oder teilweise nicht oder nur in verschlechtertem Zustand zurückgewähren, müssen Sie uns insoweit ggf. Wertersatz leisten. Bei der Überlassung von Sachen gilt dies nicht, wenn die Verschlechterung der Sache ausschließlich auf deren Prüfung - wie sie Ihnen etwa im Ladengeschäft möglich gewesen wäre - zurückzuführen ist. Im Übrigen können Sie die Wertersatzpflicht vermeiden, indem Sie die Sache nicht wie ein Eigentümer in Gebrauch nehmen und alles unterlassen, was deren Wert beeinträchtigt. Bei einer Rücksendung aus einer Warenlieferung, deren Bestellwert insgesamt bis zu 40 Euro beträgt, haben Sie die Kosten der Rücksendung zu tragen, wenn die gelieferte Ware der bestellten entspricht. Anderenfalls ist die Rücksendung für Sie kostenfrei zurückzusenden. Nicht paketversandfähige Sachen werden bei Ihnen abgeholt.</p>
<p>Ihr Widerrufsrecht erlischt vorzeitig, wenn Ihr Vertragspartner mit der Ausführung der Dienstleistung mit Ihrer ausdrücklichen Zustimmung vor Ende der Widerrufsfrist begonnen hat oder Sie diese selbst veranlasst haben.</p>
<p><br><strong>Ich habe die Informationen zur Bestellung und meinem Widerrufsrecht gelesen und verstanden: </strong></p>');

define('CONDITION_AGREEMENT_ERROR', 'Bitte bestätigen Sie durch Anklicken der Checkbox, dass Sie den Text über Ihr Widerrufs- und Rückgaberecht gelesen und verstanden haben.');
define('DELIVERY_ADDRESS','Deliver to');
?>
