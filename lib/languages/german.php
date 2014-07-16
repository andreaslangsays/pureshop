<?php
/*
  $Id: german.php,v 1.124 2003/07/11 09:03:49 jan0815 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'de_DE'
// on FreeBSD try 'de_DE.ISO_8859-1'
// on Windows try 'de' or 'German'
@setlocale(LC_TIME, 'de_DE.UTF-8');

define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_MIDDLE', '%A, %d.%m.%Y'); // this is used for strftime()
define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd.m.Y');  // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

//// 
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function push_date_raw($date, $reverse = false)
{
	if ($reverse)
		return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
	else
		return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="de-DE"');

// charset for web pages and emails
define('CHARSET', 'UTF-8');

// page title
define('TITLE', 'push GmbH');

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Neues Konto');
define('HEADER_TITLE_MY_ACCOUNT', 'Mein Konto');
define('HEADER_TITLE_CART_CONTENTS', 'Warenkorb');
define('HEADER_TITLE_CHECKOUT', 'Kasse');
define('HEADER_TITLE_TOP', 'Startseite');
define('HEADER_TITLE_CATALOG', 'Shop');
define('HEADER_TITLE_LOGOFF', 'Abmelden');
define('HEADER_TITLE_LOGIN', 'Anmelden');
//redesign
define('HEADER_CREATE_ACCOUNT', 'Registrieren');
define('TEXT_GREETING_NEW', '<span class="nobr" id="greet_new" style="white-space:nowrap;">Hallo! Kennen wir uns?</span>');
define('TEXT_GREETING_LOGOFF', '<span class="nobr" id="greet_new" style="white-space:nowrap;">Auf Wiedersehen!</span>');

define('TEXT_HI', "Hallo ");


// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'Zugriffe seit');

// text for gender
define('MALE', 'Herr');
define('FEMALE', 'Frau');
define('MALE_ADDRESS', 'Herr');
define('FEMALE_ADDRESS', 'Frau');

// text for date of birth example
define('DOB_FORMAT_STRING', 'TT.MM.JJJJ');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Kategorien');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Hersteller');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Neue Produkte');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Schnellsuche');
define('BOX_SEARCH_TEXT', 'Verwenden Sie Stichworte, um ein Produkt zu finden.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'erweiterte Suche');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Angebote');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Bewertungen');
define('BOX_REVIEWS_WRITE_REVIEW', 'Bewerten Sie dieses Produkt.');
define('BOX_REVIEWS_NO_REVIEWS', 'Es liegen noch keine Bewertungen vor.');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s von 5 Sternen.');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Warenkorb');
define('BOX_SHOPPING_CART_EMPTY', '0 Produkte');
// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', 'Bestell&uuml;bersicht');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', 'Bestseller');
define('BOX_HEADING_BESTSELLERS_IN', 'Bestseller<br>&nbsp;');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', 'Benachrichtigungen');
define('BOX_NOTIFICATIONS_NOTIFY', 'Benachrichtigen Sie mich &uuml;ber Aktuelles zu diesem Artikel <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Benachrichtigen Sie mich nicht mehr zu diesem Artikel <b>%s</b>');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', 'Hersteller Info');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', 'mehr &uuml;ber %s');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Weitere Produkte anzeigen');

// languages box test in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Sprachen');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'W&auml;hrungen');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Informationen');
define('BOX_INFORMATION_PRIVACY', 'Privatsph&auml;re und Datenschutz');
define('BOX_INFORMATION_CONDITIONS', 'Unsere AGB');
define('BOX_INFORMATION_SHIPPING', 'Liefer- und Versandkosten');
define('BOX_INFORMATION_CONTACT', 'Kontakt');
define('BOX_HEADING_IMPRESSUM', 'Impressum');
define('BOX_HEADING_PAYMENT', 'Zahlungsarten');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'Weiterempfehlen');
define('BOX_TELL_A_FRIEND_TEXT', 'Empfehlen Sie diesen Artikel einfach per eMail weiter.');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Versandinformationen');
define('CHECKOUT_BAR_PAYMENT', 'Zahlungsweise');
define('CHECKOUT_BAR_CONFIRMATION', 'Best&auml;tigung');
define('CHECKOUT_BAR_FINISHED', 'Fertig!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Bitte w&auml;hlen.');
define('TYPE_BELOW', 'Bitte unten eingeben.');

// javascript messages
define('JS_ERROR', 'Notwendige Angaben fehlen!<br>Bitte richtig ausf&uuml;llen.');

define('JS_REVIEW_TEXT', '* Der Text muss mindestens aus ' . REVIEW_TEXT_MIN_LENGTH . ' Buchstaben bestehen.');
define('JS_REVIEW_RATING', '* Geben Sie Ihre Bewertung ein.');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Bitte w&auml;hlen Sie eine Zahlungsweise f&uuml;r Ihre Bestellung.');

define('JS_ERROR_SUBMITTED', 'Diese Seite wurde bereits best&auml;tigt. Best&auml;tigen Sie bitte mit OK und warten Sie bis der Prozess durchgef&uuml;hrt wurde.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Bitte w&auml;hlen Sie eine Zahlungsweise f&uuml;r Ihre Bestellung.');

define('CATEGORY_COMPANY', 'Firmendaten');
define('CATEGORY_PERSONAL', 'Pers&ouml;nliche Daten');
define('CATEGORY_ADDRESS', 'Adresse');
define('CATEGORY_CONTACT', 'Kontaktinformationen');
define('CATEGORY_OPTIONS', 'Optionen');
define('CATEGORY_PASSWORD', 'Ihr Passwort');

define('ENTRY_COMPANY', 'Firmenname');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');

// BOF Separate Pricing Per Customer
define('ENTRY_COMPANY_TAX_ID', 'Steuernummer des Unternehmens');
define('ENTRY_COMPANY_TAX_ID_ERROR', '');
define('ENTRY_COMPANY_TAX_ID_TEXT', '');
// EOF Separate Pricing Per Custome

define('ENTRY_GENDER', 'Anrede');
define('ENTRY_GENDER_ERROR', 'Bitte das Geschlecht angeben.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Vorname');
define('ENTRY_FIRST_NAME_ERROR', 'Der Vorname sollte mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Nachname');
define('ENTRY_LAST_NAME_ERROR', 'Der Nachname sollte mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Geburtsdatum');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Bitte geben Sie Ihr Geburtsdatum in folgendem Format ein: TT.MM.JJJJ (z.B. 21.05.1970).');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (z.B. 21.05.1970)');
define('ENTRY_EMAIL_ADDRESS', 'eMail-Adresse');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Die eMail Adresse sollte mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Die eMail Adresse scheint nicht g&uuml;ltig zu sein - bitte korrigieren.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Die eMail Adresse ist bereits gespeichert - bitte melden Sie sich mit dieser Adresse an oder er&ouml;ffnen Sie ein neues Konto mit einer anderen Adresse.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Stra&szlig;e und Hausnr.');
define('ENTRY_STREET_ADDRESS_ERROR', 'Die Stra&szlig;enadresse sollte mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Stadtteil:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Postleitzahl');
define('ENTRY_POST_CODE_ERROR', 'Die Postleitzahl sollte mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Ort');
define('ENTRY_CITY_ERROR', 'Die Stadt sollte mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Bundesland');
define('ENTRY_STATE_ERROR', 'Das Bundesland sollte mindestens ' . ENTRY_STATE_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_STATE_ERROR_SELECT', 'Bitte w&auml;hlen Sie ein Bundesland aus der Liste.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Land');
define('ENTRY_COUNTRY_ERROR', 'Bitte w&auml;hlen Sie ein Land aus der Liste.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telefonnummer');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Die Telefonnummer sollte mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Telefaxnummer');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'abonniert');
define('ENTRY_NEWSLETTER_NO', 'nicht abonniert');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Passwort');
define('ENTRY_PASSWORD_ERROR', 'Das Passwort sollte mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Tippfehler! Die beiden eingegebenen Passw&ouml;rter m&uuml;ssen identisch sein.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Best&auml;tigung');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Passwort:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Das Passwort sollte mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_PASSWORD_NEW', 'Neues Passwort:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Das neue Passwort sollte mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Die Passwort-Best&auml;tigung muss mit Ihrem neuen Passwort &Uuml;bereinstimmen.');
define('PASSWORD_HIDDEN', '--VERSTECKT--');

define('FORM_REQUIRED_INFORMATION', '* Notwendige Eingabe');

// constants for use in push_prev_next_display function
define('TEXT_RESULT_PAGE', 'Seite %d von %d');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '<strong>%d</strong> Artikel');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '<strong>%d</strong> Bestellungen');
define('TEXT_DISPLAY_NUMBER_OF_TOPICS', '<b class="none">%d</b> <b class="none">%d</b> %d Themen | ');
define('TEXT_DISPLAY_NUMBER_OF_POSTS', '<b class="none">%d</b> <b class="none">%d</b> %d Beiträge | ');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'angezeigte Meinungen: <b>%d</b> bis <b>%d</b><br>(von <b>%d</b> insgesamt)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'angezeigte neue Produkte: <b>%d</b> bis <b>%d</b><br>(von <b>%d</b> insgesamt)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'angezeigte Angebote <b>%d</b> bis <b>%d</b><br>(von <b>%d</b> insgesamt)');
define('TEXT_GO_TO_PAGE','Gehe zu Seite:');
define('TEXT_PRODUCTS_PER_PAGE','Artikel pro Seite:');
define('PREVNEXT_TITLE_FIRST_PAGE', 'erste Seite');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'vorherige Seite');
define('PREVNEXT_TITLE_NEXT_PAGE', 'n&auml;chste Seite');
define('PREVNEXT_TITLE_LAST_PAGE', 'letzte Seite');
define('PREVNEXT_TITLE_PAGE_NO', 'Seite %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'vorhergehende %d Seiten');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'n&auml;chste %d Seiten');
define('PREVNEXT_BUTTON_FIRST', '&laquo;&nbsp;Erste');
define('PREVNEXT_BUTTON_PREV', '&nbsp;');//'&laquo;');
define('PREVNEXT_BUTTON_NEXT', '&nbsp;');// '&raquo;');
define('PREVNEXT_BUTTON_LAST', 'Letzte&nbsp;&raquo;');
define('PIECES','St&uuml;ck');
define('INSTEADOF','Statt bisher');
define('IMAGE_BUTTON_ADD_ADDRESS', 'Adresse hinzuf&uuml;gen');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Adressbuch');
define('IMAGE_BUTTON_BACK', 'Zur&uuml;ck');
define('IMAGE_BUTTON_BUY_NOW', 'Jetzt kaufen');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Adresse &auml;ndern');
define('IMAGE_BUTTON_CHECKOUT', 'Kasse');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Bestellung best&auml;tigen');
define('IMAGE_BUTTON_CONTINUE', 'weiter');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Einkauf fortsetzen');
define('IMAGE_BUTTON_DELETE', 'l&ouml;schen');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Daten ändern');
define('IMAGE_BUTTON_EDIT', 'bearbeiten');
define('IMAGE_BUTTON_CHANGE', '&auml;ndern');
define('IMAGE_BUTTON_HISTORY', 'Bestellübersicht');
define('IMAGE_BUTTON_LOGIN', 'Anmelden');
define('IMAGE_BUTTON_IN_CART', 'In den Warenkorb');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Benachrichtigungen');
define('IMAGE_BUTTON_QUICK_FIND', 'Schnellsuche');
define('IMAGE_BUTTON_READY', 'fertig');
define('IMAGE_BUTTON_SAVE', 'speichern');
define('IMAGE_BUTTON_CANCEL', 'abbrechen');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Benachrichtigungen löschen');
define('IMAGE_BUTTON_REVIEWS', 'Bewertungen');
define('IMAGE_BUTTON_SEARCH', 'Suchen');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Versandoptionen');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Weiterempfehlen');
define('IMAGE_BUTTON_UPDATE', 'Aktualisieren');
define('IMAGE_BUTTON_UPDATE_CART', 'Warenkorb aktualisieren');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Bewertung schreiben');

define('SMALL_IMAGE_BUTTON_DELETE', 'Delete');
define('SMALL_IMAGE_BUTTON_EDIT', 'Edit');
define('SMALL_IMAGE_BUTTON_VIEW', 'View');

define('ICON_ARROW_RIGHT', 'Zeige mehr');
define('ICON_CART', 'In den Warenkorb');
define('ICON_ERROR', 'Fehler');
define('ICON_SUCCESS', 'Success');
define('ICON_WARNING', 'Warnung');

define('TEXT_GREETING_PERSONAL', 'Sch&ouml;n, dass Sie wieder da sind, <b>%s</b>!<br>M&ouml;chten Sie sich unsere <a href="%s">neuen Produkte</a> ansehen?');
define('TEXT_GREETING_PERSONAL_RELOGON', '(Wenn Sie nicht %s sind, melden Sie sich bitte <a href="%s">hier</a> mit Ihrem Kundenkonto an.');
define('TEXT_GREETING_GUEST', 'Herzlich Willkommen.<br>M&ouml;chten Sie sich <a href="%s">anmelden</a> oder ein <a href="%s">Kundenkonto</a> er&ouml;ffnen?');
define('TEXT_LAST_SEEN_PRODUCTS', 'Zuletzt angesehene Produkte');
define('TEXT_SORT_PRODUCTS', 'Sortierung der Artikel ist ');
define('TEXT_DESCENDINGLY', 'absteigend');
define('TEXT_ASCENDINGLY', 'aufsteigend');
define('TEXT_AVAILABILITY','Verf&uuml;gbarkeit');
define('TEXT_CONTENT', 'Inhalt');
define('TEXT_BY', ' nach ');
define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Artikel-Nr.');
define('TABLE_HEADING_PRODUCTS', 'Artikel');
define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
define('TABLE_HEADING_BESTELLHAUFIGKEIT', 'Bestellhäufigkeit');
define('TEXT_ART_NR', 'Art. Nr.');
define('TABLE_HEADING_QUANTITY', 'Anzahl');
define('TABLE_HEADING_PRICE', 'Preis/ St.');
define('TABLE_HEADING_ORDER_PRICE', 'Bestellwert ');
define('TABLE_HEADING_ORDER_DATE','Bestelldatum ');
define('TABLE_HEADING_POPULARITY', 'Beliebteste');
define('TABLE_HEADING_NEWEST', 'Neueste');
define('TABLE_HEADING_NEWEST2', 'Neuester');
define('TABLE_HEADING_OLDEST2', 'Ältester');
define('TABLE_HEADING_WEIGHT', 'Gewicht');
define('TABLE_HEADING_BUY_NOW', 'Bestellen');
define('TABLE_HEADING_PRICE_PER_UNIT', 'Preis/ Einheit');
define('TABLE_HEADING_UNIT' , 'Einheit');
define('ORDER_DATE', 'Datum');
define('ORDER_NUMBER', 'Bestell-Nr.');
define('ORDER_PRODUCTS', 'Anzahl Artikel');
define('ORDER_COST', 'Wert');
define('ORDER_SHIPPED_TO', 'Lieferung an');
define('FORUM_TOPIC', ' Beitrag');
define('FORUM_HITS', 'Hits');
define('FORUM_REPLIES', 'Antworten');
//REDESIGN

define('TEXT_PRODUCT_AVAILABLE', '<div class="product-available-icon"></div> Auf Lager');
define('TEXT_PRODUCT_LIMITED_QUANTITY', '<div class="product-limited-quantity-icon"></div> Noch %d St. verfügbar');
define('TEXT_PRODUCT_SOLD_OUT', '<div class="product-sold-out-icon"></div> Zurzeit ausverkauft');

define('TEXT_CHANGE_ADDRESS', '&auml;ndern');
define('HEADING_CART','Mein Warenkorb');
define('HEADING_SHIPPING','Versand');
define('HEADING_PAYMENT','Zahlungsweise');
define('PAYMENT_ADRESS_TITLE', 'Rechnungsadresse &auml;ndern');
define('HEADING_CONFIRMATION','Best&auml;tigung');
define('HEADING_SUCCESS','Fertig'); 
define('TEXT_NO_PRODUCTS', 'Es wurden keine Artikel gefunden, die den Suchkriterien entsprechen.');  
define('TEXT_PRODUCTS', 'Artikel');
define('TEXT_HEADING_WISHLIST','Merkliste');
define('TEXT_ADD_TO_WISHLIST','Auf die Merkliste');
define('BUTTON_TO_SHOPPING_CART', 'Warenkorb &ouml;ffnen');
define('TEXT_NEW_PRODUCT_ADDED_TO_CART', 'Produkt wurde dem Warenkorb hinzugefügt');
define('TEXT_MWST_AND_SHIPPING', 'Alle Preise inkl. gesetzl. MwSt. zzgl. <a href="shipping.php">Versandkosten</a>');
define('VIEW', 'Ansicht');
define('TEXT_REVIEW_BY', 'von: %s ');
define('TEXT_FROM' , 'aus');
define('TEXT_REVIEW_WORD_COUNT', '%s Worte');
define('TEXT_REVIEW_RATING', 'Bewertung: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Datum hinzugef&uuml;gt: %s');
define('TEXT_NO_REVIEWS', 'Zu diesem Produkt gibt es noch keine Bewertungen.');
define('TEXT_REVIEW_SENT', 'Sie haben dieses Produkt bereits bewertet. <br>Die Freigabe erfolgt nach sorgfältiger Überprüfung.');
define('TEXT_REVIEW_NOT_YET_APPROVED', 'Sie haben dieses Produkt bereits bewertet. <br>Die Freigabe erfolgt nach sorgfältiger Überprüfung.');
define('TEXT_NO_NEW_PRODUCTS', 'Zur Zeit gibt es keine neuen Produkte.');
define('TEXT_REVIEW_WRITE', 'Bewertung schreiben');
define('TEXT_UNKNOWN_TAX_RATE', 'Unbekannter Steuersatz');
define('TEXT_SEND', 'Absenden');
define('TEXT_CANCEL', 'Abbrechen');
define('TEXT_REQUIRED', '<span class="errorText">erforderlich</span>');
define('TEXT_NEW_REGISTER','Noch kein Konto?'); 
define('TEXT_NEW_REGISTER_NOW','Jetzt registrieren');
define('TEXT_RETURNING_USER','Bereits registriert? Hier einloggen:');
define('ERROR_push_MAIL', '<b style="color: red;">Fehler: Die eMail kann nicht &uuml;ber den angegebenen SMTP-Server verschickt werden. Bitte kontrollieren Sie die Einstellungen in der php.ini Datei und f&uuml;hren Sie notwendige Korrekturen durch!</b>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warnung: Das Installationverzeichnis ist noch vorhanden auf: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. Bitte l&ouml;schen Sie das Verzeichnis aus Gr&uuml;nden der Sicherheit!');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Warnung: osC kann in die Konfigurationsdatei schreiben: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. Das stellt ein m&ouml;gliches Sicherheitsrisiko dar - bitte korrigieren Sie die Benutzerberechtigungen zu dieser Datei!');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warnung: Das Verzeichnis f&uuml;r die Sessions existiert nicht: ' . push_session_save_path() . '. Die Sessions werden nicht funktionieren bis das Verzeichnis erstellt wurde!');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warnung: osC kann nicht in das Sessions Verzeichnis schreiben: ' . push_session_save_path() . '. Die Sessions werden nicht funktionieren bis die richtigen Benutzerberechtigungen gesetzt wurden!');
define('WARNING_SESSION_AUTO_START', 'Warnung: session.auto_start ist enabled - Bitte disablen Sie dieses PHP Feature in der php.ini und starten Sie den WEB-Server neu!');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warnung: Das Verzeichnis f�r den Artikel Download existiert nicht: ' . DIR_FS_DOWNLOAD . '. Diese Funktion wird nicht funktionieren bis das Verzeichnis erstellt wurde!');
define('TEXT_CCVAL_ERROR_INVALID_DATE', 'Das "G&uuml;ltig bis" Datum ist ung&uuml;ltig.<br>Bitte korrigieren Sie Ihre Angaben.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'Die "KreditkarteNummer", die Sie angegeben haben, ist ung&uuml;ltig. Bitte korrigieren Sie Ihre Angaben.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Die ersten 4 Ziffern Ihrer Kreditkarte sind: %s. Wenn diese Angaben stimmen, wird dieser Kartentyp leider nicht akzeptiert. Bitte korrigieren Sie Ihre Angaben gegebenfalls.');

define('IMAGE_BUTTON_PRINT_ORDER', 'Bestellung ausdrucken');
/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/

define('FOOTER_TEXT_BODY', 'Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');

//ABOC
define('BOX_HEADING_ABO_CART', 'Abonnement');
define('BOX_ABO_CART_EMPTY', 'Sie abonnieren keine Artikel.');

//BOF Featured Products
define('BOX_CATALOG_FEATURED_PRODUCTS', 'Highlights');
define('BOX_HEADING_FEATURED', 'Highlights');
//EOF Featured Products

//BOF Base Price
define('TEXT_PRODUCTS_BASE_PRICE', 'Grundpreis:&nbsp;');
define('TEXT_INFO_TAX', '[inkl. %s%% MwSt zzgl. <a href="%s" class="tax_info">Versandkosten</a>]');
//EOF Base Price

//BOF extra questions
require(DIR_WS_LANGUAGES . 'questions_german.php');
//EOF extra questions

//BOF CCGV
require(DIR_WS_LANGUAGES . 'add_ccgvdc_german.php');
//EOF CCGV
define('AVG_GIVEN_STARS', 'Durchschnittliche K&auml;uferbewertung');

//BOF Pollboth
// Poll Box Text 
define('_RESULTS', 'Trend'); 
define('_POLLS','Abstimmungen'); 
define('_VOTE', 'Abstimmen'); 
define('_VOTES', 'abgegebene Stimmen'); 
define('_NOPOLLS','Derzeit keine Abstimmung m&ouml;glich'); 
define('_NOPOLLSCONTENT','Derzeit ist keine Abstimmung aktiv, schm&ouml;kern Sie durch unser Voting-Atchiv<br><br><a href="pollbooth.php">['._POLLS.']');  
//EOF Pollboth

// necessary for breadcrumbs
define('CONTACT_US', 'Wir &Uuml;ber uns');
define('CAFE_LADEN', 'Caf&eacute; und Ladengesch&auml;ft');
define('BKR', 'push');
define('TEXT_MORE_INFORMATION','&raquo; Details');
define('TEXT_CONTINUE_SHOPPING', '&laquo Einkauf fortsetzen');
define('TEXT_CONTINUE', 'Weiter');
define('TEXT_ACTUALIZE', 'aktualisieren');
define('TEXT_GENERAL_SORT', 'Sortieren: ');
define('TEXT_SORT_ASC', ' aufsteigend');
define('TEXT_SORT_DESC', ' absteigend');
define('TEXT_SORT_ASC_AZ', ' A-Z');
define('TEXT_SORT_DESC_ZA', ' Z-A');
define('PRICE_SORT_ASC', ' aufsteigend');
define('PRICE_SORT_DESC', ' absteigend');
define('POP_SORT_ASC', ' zuletzt');
define('POP_SORT_DESC', ' zuerst');
// Belz: gift_products
define('TEXT_GRATIS_PRODUKT_INTRO', '<p> Unser kleines Dankesch&ouml;n!<br>Je nach Warenwert bieten wir Ihnen folgende Artikel als Geschenk an:</p>');
define('TEXT_GRATIS_PRODUKT_AB', 'ab ');
define('TEXT_GRATIS_PRODUKT_EXISTS', 'Es befindt sich bereits ein Geschenk in Ihrem Warenkorb.');
define('TEXT_GRATIS_PRODUKT_MISSING_1', 'Es fehlen Ihnen noch <strong>');
define('TEXT_GRATIS_PRODUKT_MISSING_2', ' </strong> f&uuml;r unser kleines Geschenk.');
define('TEXT_GRATIS_PRODUKT_ALLOWED', '<p>M&ouml;chten Sie Ihr Geschenk Ihrem Warenkorb hinzuf&uuml;gen? </p>');
define('TEXT_GRATIS_PRODUKT_ALLOWED_LINK', '<p> >>> Ja, ich m&ouml;chte mein Geschenk mitbestellen <<< </p>');
define('TEXT_GRATIS_PRODUKT_NEUE_AUSWAHL', '<p>Sie k&ouml;nnten ein gr&ouml;&szlig;eres Geschenk ausw&auml;hlen!<br>Hierzu entfernen Sie bitte das aktuelle Geschenk aus Ihrem Warenkorb.</p>');
define('TEXT_GRATIS_PRODUKT_AGB', '<SPAN class="newItemInCart"><p>Abweichende Angaben in Ihrer Bestellung k&ouml;nnen wir leider nicht ber&uuml;cksichtigen!</p></SPAN>');
define('TEXT_GRATIS_PRODUKT_CHEATER_1', '<SPAN class="newItemInCart"><p><strong>Es befindet sich ein Geschenk in ihrem Warenkorb, welches erst ab einem Warenwert von ');
define('TEXT_GRATIS_PRODUKT_CHEATER_2', ' erlaubt ist!<br>Ihrem Warenkorb fehlen also ');
define('TEXT_GRATIS_PRODUKT_CHEATER_3', ' <br>Bitte passen Sie Ihre Bestellung an oder entfernen Sie unser Geschenk aus Ihrem Warenkorb!</strong></p></SPAN>');
// wishlist box text in includes/boxes/wishlist.php
define('BOX_HEADING_CUSTOMER_WISHLIST', 'Merkzettel');
define('TEXT_WISHLIST_COUNT', 'Sie haben %s Artikel auf Ihrem Merkzettel.');


define('STANDARD_MWST', 'Preis inkl. MwSt.');

/**
 * Coffee-Strings
 */
define('TEXT_REGION','Region');
define('TEXT_PLANTAGE', 'Plantage');
define('TEXT_VARIETAET','Variet&auml;t');
define('TEXT_ANBAUHOEHE','Anbauh&ouml;he');
define('TEXT_BODEN','Boden');
define('TEXT_AUFBEREITUNG','Aufbereitung');
define('TEXT_SCHATTENBAUME','Schattenb&auml;ume');
define('TEXT_QUALITY','Qualit&auml;t');
define('TEXT_ROESTGRAD','R&ouml;stgrad');
define('TEXT_MISCHUNGSBESTANDTEILE','Mischungsbestandteile');
define('TEXT_ZERTIFIZIERUNGEN','Zertifizierungen');
define('TEXT_GEEIGNETFUER','Geeignet f&uuml;r');

//No javascript
define('TEXT_NO_JAVASCRIPT', 'Oh nein! Sie haben Javascript in Ihrem Browser deaktiviert.<br /><br />
Bitte <strong class="tx_red">aktivieren Sie Javascript</strong>, damit diese Webseite vollständig angezeigt wird und Sie alle unsere Funktionen in vollem Umfang nutzen können. Die Aktivierung finden Sie in den Einstellungen Ihres Browsers. Vielen Dank und nun viel Spass auf unserer Webseite.
');

?>
