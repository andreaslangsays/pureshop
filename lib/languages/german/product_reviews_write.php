<?php
/*
  $Id: product_reviews_write.php,v 1.10 2003/06/05 23:23:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Meinungen');

define('SUB_TITLE_FROM', 'Autor:');
define('SUB_TITLE_REVIEW', 'Ihre Meinung:');
define('SUB_TITLE_RATING', 'Bewertung:');

//*** <Reviews Mod>
define('ADMIN_EMAIL_SUBJECT', 'Product Review - Approval Required');
define('ADMIN_EMAIL_MESSAGE', 'There is a new product review awaiting approval from your online store, please click this link to view this review: <a href="' . push_href_link(FILENAME_PRODUCT_REVIEW_EMAIL) . '">' . push_href_link(FILENAME_PRODUCT_REVIEW_EMAIL) . '</a>');
define('ADMIN_EMAIL_FROM_NAME', 'Product Reviews');
define('SUB_TITLE_EXPLAIN', '
<hr />
<h2>Bewertungsrichtlinien</h2>
<h3>Ihre Meinung interessiert uns!</h3>
<p>Wir interessieren uns für Ihre Erfahrungen und ihre Ideen zu diesem Artikel. Teilen Sie uns und anderen Kunden mit, wie Sie das Produkt finden, ob es ihren Erwartungen entsprach oder welche Tipps Sie diesbezüglich haben.</p>
<p style="margin-top:10px;">Bitte benutzen Sie dazu das Kommentar-Feld unten. Schreiben Sie bitte <b>mindestens 50 und höchstens 300 Wörter</b>. </p>
<p style="margin-top:10px;">Vermeiden Sie pauschale Wertungen und erklären Sie näher warum Sie es mögen oder nicht mögen. Sie können auch auf alternative Produkte in unserem Shop oder allgemein hinweisen.</p>
<p style="margin-top:10px;">Bitte haben Sie Verständnis dafür, dass gehässige Bemerkungen oder Obszönitäten, die Angabe alternativer Bestellmöglichkeiten, Webadressen, e-Mailadressen sowie Telefonnummern in der Produktbewertung nicht erwünscht sind.</p>
<p>Wir freuen uns auf Ihre Bewertungen!</p>
<p style="font-size:x-small;margin-top:20px;">Wir behalten uns das Recht vor, Ihren Kommentar zu akzeptieren, abzulehnen oder zu bearbeiten, daher wird Ihr Beitrag möglicherweise nicht sofort angezeigt.</p>
<hr />
');
//*** </Reviews Mod>


define('TEXT_NO_HTML', '<small><font color="#ff0000"><b>ACHTUNG:</b></font></small>&nbsp;HTML wird nicht unterst&uuml;tzt!');
define('TEXT_BAD', '<small><font color="#ff0000"><b>SCHLECHT</b></font></small>');
define('TEXT_GOOD', '<small><font color="#ff0000"><b>SEHR GUT</b></font></small>');

define('TEXT_CLICK_TO_ENLARGE', 'F&uuml;r eine gr&ouml;ssere Darstellung<br>klicken Sie auf das Bild.');
?>