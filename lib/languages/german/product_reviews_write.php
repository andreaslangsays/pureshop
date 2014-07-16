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
<p>Wir interessieren uns f�r Ihre Erfahrungen und ihre Ideen zu diesem Artikel. Teilen Sie uns und anderen Kunden mit, wie Sie das Produkt finden, ob es ihren Erwartungen entsprach oder welche Tipps Sie diesbez�glich haben.</p>
<p style="margin-top:10px;">Bitte benutzen Sie dazu das Kommentar-Feld unten. Schreiben Sie bitte <b>mindestens 50 und h�chstens 300 W�rter</b>. </p>
<p style="margin-top:10px;">Vermeiden Sie pauschale Wertungen und erkl�ren Sie n�her warum Sie es m�gen oder nicht m�gen. Sie k�nnen auch auf alternative Produkte in unserem Shop oder allgemein hinweisen.</p>
<p style="margin-top:10px;">Bitte haben Sie Verst�ndnis daf�r, dass geh�ssige Bemerkungen oder Obsz�nit�ten, die Angabe alternativer Bestellm�glichkeiten, Webadressen, e-Mailadressen sowie Telefonnummern in der Produktbewertung nicht erw�nscht sind.</p>
<p>Wir freuen uns auf Ihre Bewertungen!</p>
<p style="font-size:x-small;margin-top:20px;">Wir behalten uns das Recht vor, Ihren Kommentar zu akzeptieren, abzulehnen oder zu bearbeiten, daher wird Ihr Beitrag m�glicherweise nicht sofort angezeigt.</p>
<hr />
');
//*** </Reviews Mod>


define('TEXT_NO_HTML', '<small><font color="#ff0000"><b>ACHTUNG:</b></font></small>&nbsp;HTML wird nicht unterst&uuml;tzt!');
define('TEXT_BAD', '<small><font color="#ff0000"><b>SCHLECHT</b></font></small>');
define('TEXT_GOOD', '<small><font color="#ff0000"><b>SEHR GUT</b></font></small>');

define('TEXT_CLICK_TO_ENLARGE', 'F&uuml;r eine gr&ouml;ssere Darstellung<br>klicken Sie auf das Bild.');
?>