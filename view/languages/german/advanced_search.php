<?php
/*
  $Id: advanced_search.php,v 1.21 2003/07/11 09:04:22 jan0815 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Erweiterte Suche');
define('NAVBAR_TITLE_2', 'Suchergebnisse');

define('HEADING_TITLE_1', 'Geben Sie Ihre Suchkriterien ein');
define('HEADING_TITLE_2', 'Artikel, welche den Suchkriterien entsprechen');

define('HEADING_SEARCH_CRITERIA', 'Geben Sie Ihre Stichworte ein');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Auch in den Beschreibungen suchen');
define('ENTRY_CATEGORIES', 'Kategorien:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Unterkategorien mit einbeziehen');
define('ENTRY_MANUFACTURERS', 'Hersteller:');
define('ENTRY_PRICE_FROM', 'Preis ab:');
define('ENTRY_PRICE_TO', 'Preis bis:');
define('ENTRY_DATE_FROM', 'hinzugef&uuml;gt von:');
define('ENTRY_DATE_TO', 'hinzugef&uuml;gt bis:');

define('TEXT_SEARCH_HELP_LINK', '<u>Hilfe zur erweiterten Suche</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Alle Kategorien');
define('TEXT_ALL_MANUFACTURERS', 'Alle Hersteller');

define('HEADING_SEARCH_HELP', 'Hilfe zur erweiterten Suche');
define('TEXT_SEARCH_HELP', 'Die Suchfunktion erm&ouml;glicht Ihnen die Suche in den Produktnamen, Produktbeschreibungen, Herstellern und Artikelnummern.<br><br>Sie haben die M&ouml;glichkeit logische Operatoren wie "AND" (Und) und "OR" (oder) zu verwenden.<br><br>Als Beispiel k&ouml;nnten Sie also angeben: <u>Microsoft AND Maus</u>.<br><br>Desweiteren k&ouml;nnen Sie Klammern verwenden um die Suche zu verschachteln, also z.B.:<br><br><u>Microsoft AND (Maus OR Tastatur OR "Visual Basic")</u>.<br><br>Mit Anf&uuml;hrungszeichen k&ouml;nnen Sie mehrere Worte zu einem Suchbegriff zusammenfassen.');
define('TEXT_CLOSE_WINDOW', '<u>Fenster schliessen</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Artikelnummer');
define('TABLE_HEADING_PRODUCTS', 'Bezeichnung');
define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
define('TABLE_HEADING_QUANTITY', 'Anzahl');
define('TABLE_HEADING_PRICE', 'Einzelpreis');
define('TABLE_HEADING_WEIGHT', 'Gewicht');
define('TABLE_HEADING_BUY_NOW', 'jetzt bestellen');
define('TABLE_HEADING_PRODUCT_SORT', 'Sortiere');

//define('TEXT_NO_PRODUCTS', 'Es wurden keine Artikel gefunden, die den Suchkriterien entsprechen.');

define('TEXT_NO_PRODUCTS', '<br><span style="font-size:11px;"><i>Ihre Suche ergab keine Übereinstimmungen.</i></span><br><br>Einige Vorschläge:<ol><li>Stellen Sie sicher, dass Sie die Rechtschreibung korrekt ist.</li><li>Probieren Sie andere Keywords aus</li><li>Versuchen Sie es mit weniger Begriffen</li><li>Versuchen Sie es mit mehr allgemeinen Keywords</li></ol>');


define('ERROR_AT_LEAST_ONE_INPUT', 'Wenigstens ein Feld des Suchformulars muss ausgefüllt werden.');
define('ERROR_INVALID_FROM_DATE', 'Unzul&auml;ssiges <b>von</b> Datum');
define('ERROR_INVALID_TO_DATE', 'Unzul&auml;ssiges <b>bis jetzt</b> Datum');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'Das Datum <b>von</b> muss gr&ouml;sser oder gleich dem <b>bis jetzt</b> Datum sein');
define('ERROR_PRICE_FROM_MUST_BE_NUM', '<b>Preis ab</b> muss eine Zahl sein');
define('ERROR_PRICE_TO_MUST_BE_NUM', '<b>Preis bis</b> muss eine Zahl sein');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', '<b>Preis bis</b> muss gr&ouml;&szlig;er oder gleich <b>Preis ab</b> sein.');
define('ERROR_INVALID_KEYWORDS', 'Suchbegriff unzul&aum&auml;ssig');
// ###Begin Fehlertolerante Suche

define ('NO_FOUND1','Der Suchbegriff wurde nicht gefunden, Bitte ab&auml;ndern');
define ('NO_FOUND2','Der Suchbegriff wurde nicht gefunden, sie k&ouml;nnen versuchen auch in der Produktbeschreibung zu Suchen oder Suchwort(e) ab&auml;ndern.<br>(In den &Uuml;berschriften der Produktbeschreibung stehen nicht alle relevanten Begriffe)<br><A href="'. HTTP_SERVER . DIR_WS_HTTP_CATALOG .'advanced_search.php/?keywords=');

define ('NO_FOUND3','&search_in_description=1"><FONT color=#003399><FONT size=2><b>Zu der Suche in den Produktbeschreibungen hier Klicken</b></FONT></A>');
define ('NO_FOUND4','<br>Nicht das richtige dabei ???, sie k&ouml;nnen auch versuchen in der Produktbeschreibung zu Suchen.<br>(In den &Uuml;berschriften der Produktbeschreibung stehen nicht alle relevanten Begriffe)<br><A href="'. HTTP_SERVER . DIR_WS_HTTP_CATALOG .'advanced_search.php/?keywords=');

define ('NO_FOUND5','&search_in_description=1"><FONT color=#003399><FONT size=2><b>Zu der Suche in den Produktbeschreibungen hier Klicken</b></FONT></A>');

define ('NO_FOUND6','<br>Nicht das richtige dabei ??? Versuchen sie es doch mit abge&auml;nderten Suchworten ');

define ('SUCHWORT_FALSE1','Unter dem angegeben Suchbegriff <font color="#FF0000"> ');
define ('SUCHWORT_FALSE2','</font> wurde leider nichts gefunden<br>Suchbegriff <font color="#FF0000"> ');
define ('SUCHWORT_FALSE3','</font> abge&auml;ndert auf: <font color="#FF0000"> ');
// ##End Fehlertolerante Suche
define('TEXT_REPLACEMENT_SUGGESTION1', 'Wir empfehlen auch den/die Begriffe ');
define('TEXT_REPLACEMENT_SUGGESTION2', ' zu versuchen');
define('TEXT_REPLACEMENT_SEARCH_RESULTS', 'Ihre Suchergebnisse für: ');

?>
