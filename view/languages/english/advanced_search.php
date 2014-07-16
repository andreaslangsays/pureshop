<?php
/*
  $Id: advanced_search.php,v 1.21 2003/07/11 09:04:22 jan0815 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Ricerca Avanzata');
define('HEADING_TITLE_1', 'Ricerca Avanzata');
define('NAVBAR_TITLE_2', 'Risultati della ricerca');
define('HEADING_TITLE_2', 'Prodotti secondo i criteri di ricerca');

define('HEADING_SEARCH_CRITERIA', 'Criteri di Ricerca');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Ricerca nella Descrizione del Prodotto');
define('ENTRY_CATEGORIES', 'Categorie:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Includi Sottocategorie');
define('ENTRY_MANUFACTURERS', 'Produttori:');
define('ENTRY_PRICE_FROM', 'Prezzo da:');
define('ENTRY_PRICE_TO', 'Prezzo a:');
define('ENTRY_DATE_FROM', 'Data da:');
define('ENTRY_DATE_TO', 'Data a:');

define('TEXT_SEARCH_HELP_LINK', '<u>Aiuto Ricerca</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Tutte le Categorie');
define('TEXT_ALL_MANUFACTURERS', 'Tutti Produttori');

define('HEADING_SEARCH_HELP', 'Aiuto Ricerca');
define('TEXT_SEARCH_HELP', 'Le parole chiave possono essere separate dagli operatori AND e/o OR per ottenere risultati piu\' precisi.<br><br>Per esempio, <u>orologio AND polso</u> ricerchera\' i prodotti che contengono entrambe le parole. Mentre, per <u>orologio OR radio</u>, il risultato conterra\' una o l\'altra o entrambe le parole.<br><br>Un risultato esatto puo\' essere ricercato tramite parole chiave tra virgolette.<br><br>Per esempio, <u>"orologio da polso"</u> ricerchera\' i prodotti che contengono precisamente queste parole.<br><br>Le parentesi possono essere usate per specificre ulteriori criteri di ricerca.<br><br>Per esempio, <u>orologio AND (polso OR cronometro OR "radio sveglia")</u>.');
define('TEXT_CLOSE_WINDOW', '<u>Chiudi Finestra</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Modello');
define('TABLE_HEADING_PRODUCTS', 'Nome Prodotto');
define('TABLE_HEADING_MANUFACTURER', 'Produttore');
define('TABLE_HEADING_QUANTITY', 'Quantita\'');
define('TABLE_HEADING_PRICE', 'Prezzo');
define('TABLE_HEADING_WEIGHT', 'Peso');
define('TABLE_HEADING_BUY_NOW', 'Acquista ora');
define('TEXT_NO_PRODUCTS', 'Non riusciamo a trovare prodotti che soddisfano i criteri di ricerca.');

define('ERROR_AT_LEAST_ONE_INPUT', 'Almeno un campo dei criteri di ricerca deve essere compilato.');
define('ERROR_INVALID_FROM_DATE', 'Dato invalido.');
define('ERROR_INVALID_TO_DATE', 'Inserimento invalido.');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'La data deve essere uguale o superiore.');
define('ERROR_PRICE_FROM_MUST_BE_NUM', 'Il prezzo deve essere un numero.');
define('ERROR_PRICE_TO_MUST_BE_NUM', 'Il prezzo deve essere un numero.');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', 'Il prezzo deve essere uguale o superiore.');
define('ERROR_INVALID_KEYWORDS', 'Parola chiave non valida.');
// ###Begin Fehlertolerante Suche

define ('NO_FOUND1','Non riusciamo a trovare quello che cerchi, prova a cambiare');
define ('NO_FOUND2','Non riusciamo a trovare quello che cerchi, puoi provare a cambiare i termini che stai cercando o cercare nelle descrizioni.<br>(Non è detto che nelle descrizioni vi siano tutte le parole chiave che cerchi)<br><A href="http://www.weinkontor-luebeck.de/shop/deutsch/catalog/advanced_search.php/?keywords=');

define ('NO_FOUND3','&search_in_description=1"><FONT color=#003399><FONT size=2><b>Click qui per cercare nelle descrizioni dei prodotti</b></FONT></A>');
define ('NO_FOUND4','<br>Non riusciamo a trovare ciò che cerchi?, prova a cercare nella descrizione dei prodotti.<br>(Non è detto che nelle descrizioni vi siano tutte le parole chiave che cerchi)<br><A href="http://www.weinkontor-luebeck.de/shop/deutsch/catalog/advanced_search.php/?keywords=');

define ('NO_FOUND5','&search_in_description=1"><FONT color=#003399><FONT size=2><b>Click qui per cercare nelle descrizioni dei prodotti</b></b></FONT></A>');

define ('NO_FOUND6','<br>Non riusciamo a trovare ciò che cerchi?, prova a cercare usando altre parole chiave');

define ('SUCHWORT_FALSE1','Per il termine di ricerca <font color="#FF0000"> ');
define ('SUCHWORT_FALSE2','</font> purtroppo non riusciamo a trovare niente<br>Termine di ricerca <font color="#FF0000"> ');
define ('SUCHWORT_FALSE3','</font> cambiato in: <font color="#FF0000"> ');
// ##End Fehlertolerante Suche
define('TEXT_REPLACEMENT_SUGGESTION1', 'Ti consigliamo anche i seguenti termini ');
define('TEXT_REPLACEMENT_SUGGESTION2', ' per migliorare la tua ricerca');
define('TEXT_REPLACEMENT_SEARCH_RESULTS', 'I risultati della tua ricerca per: ');

?>
