<?php
/*
  $Id: whos_online.php,v 1.5 2002/03/30 15:48:55 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('EXPLAINATION', 'Dieses Werkzeug erlaubt Ihnen, das Klickverhalten der User waehrend der Sessions zu kontrollieren. Die Daten koennen viele wichtige Informationen fuer Sie enthalten.  
Sie koennen damit die Verbesserungen Ihrer Seite beschleunigen.  SIe koennen vor- und zurueckblaettern, indem Sie die Daten unten benutzen.');
define('HEADING_TITLE', 'User Tracking');

define('TABLE_HEADING_SESSION_ID', 'Session ID');
define('TABLE_HEADING_ONLINE', 'Online');
define('TABLE_HEADING_CUSTOMER_ID', 'ID');
define('TABLE_HEADING_FULL_NAME', 'Name');
define('TABLE_HEADING_IP_ADDRESS', 'IP-Addresse:');
define('TABLE_HEADING_COUNTRY', 'Land/Herkunft:');
define('TABLE_HEADING_HOST', 'Host:');
define('TABLE_HEADING_ENTRY_TIME', 'Anfangszeit:');
define('TABLE_HEADING_END_TIME', 'Endzeit:');
define('TABLE_HEADING_ACTION', 'Aktionen');
define('TABLE_HEADING_SHOPPING_CART', 'Kundenwarenkorb');
define('TEXT_SHOPPING_CART_SUBTOTAL', 'Zwischensumme');
define('TEXT_NUMBER_OF_CUSTOMERS', 'Insgesamt gab es %s Seitenansichten, in der 24-Stunden-Einheit. ');
define('TEXT_NUMBER_OF_CUSTOMERS', 'Totale anzahl besucher: ');
define('TEXT_NUMBER_OF_PAGES', 'Insgesamt gab es %s Seitenansichten, in der 24-Stunden-Einheit.');
define('TEXT_HAS_BEEN_PURGED', 'Informationen, aelter als 72 Stunden, wurden geloescht!');
define('TEXT_SELECT_VIEW', 'Ansicht waehlen');
define('TEXT_BACK_TO', 'Zurueck');
define('TEXT_FORWARD_TO', 'Weiter zu ');
define('TEXT_DISPLAY_START', 'Sie sehen die letzten ');
define('TEXT_DISPLAY_END', ' Sessions in dieser 24 Stunden-Einheit. ');

define('TEXT_PURGE_START', 'Sie koennen ebenso ');
define('TEXT_PURGE_RECORDS', 'alle Aufzeichnungen loeschen');
define('TEXT_PURGE_END', ' innerhalb der letzten 72 Stunden.');
define('TEXT_PURGE_CUSTMID_END', ' of Guest Sessions.');
define('TEXT_PURGE_ALL', ' ab JETZT. ');
define('TEXT_BEWAREDELETE', 'ACHTUNG - ALLE DATEIEN WIRDEN GELÖSCHT !!!.');
define('TEXT_USER_SHOPPING_CART', '&nbsp;Kundenwarenkorb');

define('TEXT_ORIGINATING_URL', 'Herkunfts-&nbsp;URL:');
define('TEXT_IDLE_TIME', 'Leerlaufzeit:');
define('TEXT_TOTAL_TIME', 'Gesamtzeit:');
define('TEXT_DELETE_IP', 'Alle Informationen zu der IP-Adresse loeschen ');
define('TEXT_DELETE_CUSTOMER_GUEST', 'All Guest Sessions have been Purged');
define('TEXT_DELETE_SESSION', 'Session Loeschen');
define('TEXT_VIEW_SESSION', 'Session Ansehen');

$GEOIP_COUNTRY_NAMES = array(
"", "Asien/Pazifik Region", "Europa", "Andorra", "Verein. Arab. Emirate",
"Afghanistan", "Antigua und Barbuda", "Anguilla", "Albanien", "Armenien",
"Niederlaendische Antillen", "Angola", "Antarktis", "Argentinien", "Amerikanisches Samoa",
"Oesterreich", "Australien", "Aruba", "Aserbaidschan", "Bosnien und Herzegowina",
"Barbados", "Bangladesch", "Belgien", "Burkina Faso", "Bulgarien", "Bahrain",
"Burundi", "Benin", "Bermudas", "Brunei Darussalam", "Bolivien", "Brasilien",
"Bahamas", "Bhutan", "Bouvet Inseln", "Botswana", "Weissrussland", "Belize",
"Kanada", "Kokos (Keeling) Inseln", "Demokratische Republik Kongo",
"Zentralafrikanische Republik", "Kongo", "Schweiz", "Elfenbeinkueste", "Cook
Inseln", "Chile", "Kameroon", "China", "Kolumbien", "Costa Rica", "Kuba", "Kapverdische
Inseln", "Weihnachtsinseln", "Zypern", "Tschechische Republik", "Deutschland", "Djibouti",
"Daenemark", "Dominica", "Dominikanische Republik", "Algerien", "Ekuador", "Estland",
"Aegypten", "Westliche Sahara", "Eritrea", "Spanien", "Aethopien", "Finnland", "Fiji",
"Falkland Inseln (Malvinas)", "Vereinigte Staaten von Mikronesien", "Faroer
Inseln", "Frankreich", "Franzoesiche Metropolen", "Gabun", "Grossbritannien",
"Grenada", "Georgien", "Franzoesisch Guyana", "Ghana", "Gibraltar", "Groenland",
"Gambia", "Guinea", "Guadeloupe", "Aequatorial Guinea", "Griechenland", "Suedgeorgien
und suedliche Sandwichinseln", "Guatemala", "Guam", "Guinea-Bissau",
"Guyana", "Hong Kong", "Heard Inseln und McDonald Islands", "Honduras",
"Kroatien", "Haiti", "Ungarn", "Indonesien", "Irland", "Israel", "Indien",
"Britisch-Indisch Ozeanien", "Irak", "Islamische Republik des Iran",
"Island", "Italien", "Jamaika", "Jordanien", "Japan", "Kenia", "Kirgisistan",
"Kambodscha", "Kiribati", "Komoren", "Saint Kitts und Nevis", "Demokratische 
Volksrepublik Korea", "Republik Korea", "Kuwait", "Cayman Inseln",
"Kasachstan", "Laos", "Libanon", "Saint Lucia",
"Liechtenstein", "Sri Lanka", "Liberia", "Lesotho", "Litauen", "Luxembourg",
"Lettland", "Libyen", "Marokko", "Monaco", "Republik MoldaBruessels", 
"Madagaskar", "Marshall Inseln", "Fruehere jugoslawische Republik
Mazedonien", "Mali", "Myanmar", "Mongolei", "Macau", "Noerdliche Mariane Inseln",
"Martinique", "Mauritanien", "Montserrat", "Malta", "Mauritius", "Maldiven",
"Malawi", "Mexiko", "Malaysia", "Mosambique", "Namibia", "Neukaledonien",
"Niger", "Norfolk Inseln", "Nigeria", "Nicaragua", "Niederlande", "Norwegen",
"Nepal", "Nauru", "Niue", "Neuseeland", "Oman", "Panama", "Peru", "Franzoesich
Polynesien", "Papua Neuguinea", "Philipinen", "Pakistan", "Polen", "Saint
Pierre und Miquelon", "Pitcairn", "Puerto Rico", "Palaestinagebiet,
besetzt", "Portugal", "Palau", "Paraguay", "Katar", "Reunion", "Rumaenien",
"Russische Foerderation", "Ruanda", "Saudi Arabien", "Salomonen Inseln",
"Seychellen", "Sudan", "Schweden", "Singapur", "Saint Helena", "Slowenien",
"Svalbard und Jan Mayen", "Slowakei", "Sierra Leone", "San Marino", "Senegal",
"Somalia", "Surinam", "Sao Tome und Principe", "El Salvador", "Syrische 
Republik", "Swasiland", "Turks und Caicos Inseln", "Tschad", "Franzoesische 
Suedgebiete", "Togo", "Thailand", "Tadjikistan", "Tokelau", "Turkmenistan",
"Tunesien", "Tonga", "Osttimor", "Tuerkei", "Trinidad und Tobago", "Tuvalu",
"Taiwan", "Vereinigte Staaten von Tansania", "Ukraine",
"Uganda", "Vereingte Staaten - auswaertige Inseln", "Vereinigte Staaten", "Uruguay",
"Usbekistan", "Vatikanstaat", "Saint Vincent und die 
Grenadinen", "Venezuela", "Britische Jungfraueninseln", "Amerikanische Jungfraueninseln",
"Vietnam", "Vanuatu", "Wallis und Futuna", "Samoa", "Jemen", "Mayotte",
"JugoslaBruessels", "Suedafrika", "Sambia", "Zaire", "Simbabwe"
);

?>
