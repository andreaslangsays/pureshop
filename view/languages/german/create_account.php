<?php
/*
  $Id: create_account.php,v 1.13 2003/05/19 20:17:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// ################ Send Order Html Mail ###############"
require(DIR_WS_LANGUAGES . $language . '/' . 'add_create_account_process.php');
// ################ End Send Order Html Mail ###############"


define('NAVBAR_TITLE', 'Konto erstellen');

define('HEADING_TITLE', 'Herzlich willkommen bei der Bruesselser Kakaor&ouml;sterei!');
define('HEADING_TEXT1', 'Wenn Sie sich f&uuml;r unseren Shop ein Kundenkonto einrichten, haben Sie immer eine &Uuml;bersicht &uuml;ber Ihre aktuellen und bisherigen Bestellungen, k&ouml;nnen mehrere Versandadressen verwalten und bestehendes Kontoguthaben an Freunde verschenken. Das Kundenkonto ist kostenfrei.');
define('HEADING_TEXT2', '<b>Registrierung</b><br />In nur einem Schritt zu Ihrem Kundenkonto - F&uuml;llen Sie bitte die folgenden Felder aus.');

define('NEWSLETTER_TEXT', 'Ja, ich m&ouml;chte mit dem Newsletter &uuml;ber Neuigkeiten und Angebote informiert werden.');

define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>ACHTUNG:</b></font></small> Wenn Sie bereits ein Konto besitzen, so melden Sie sich bitte <a href="%s"><u><b>hier</b></u></a> an.');

define('EMAIL_SUBJECT', 'Willkommen in der ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Sehr geehrter Herr ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Sehr geehrte Frau ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Sehr geehrte ' . stripslashes($_POST['firstname']) . ',' . "\n\n");
define('EMAIL_WELCOME', 'Willkommen in der <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Sie können jetzt unseren <b>Online-Service</b> nutzen. Der Service bietet unter anderem:' . "\n\n" . '<li><b>Kundenwarenkorb</b> - Jeder Artikel bleibt registriert bis Sie zur Kasse gehen, oder die Produkte aus dem Warenkorb entfernen.' . "\n" . '<li><b>Adressbuch</b> - Wir können jetzt die Produkte zu der von Ihnen ausgesuchten Adresse senden. Der perfekte Weg ein Geburtstagsgeschenk zu versenden.' . "\n" . '<li><b>Vorherige Bestellungen</b> - Sie können jederzeit Ihre vorherigen Bestellungen überprüfen.' . "\n" . '<li><b>Meinungen über Produkte</b> - Teilen Sie Ihre Meinung zu unseren Produkten mit anderen Kunden.' . "\n\n");
define('EMAIL_CONTACT', 'Falls Sie Fragen zu unserem Kunden-Service haben, wenden Sie sich bitte an den Vertrieb: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Achtung:</b> Diese eMail-Adresse wurde uns von einem Kunden bekannt gegeben. Falls Sie sich nicht angemeldet haben, senden Sie bitte eine eMail an ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");

/* CCGV ADDED - BEGIN */
define('EMAIL_GV_INCENTIVE_HEADER', 'Als kleines Willkommensgeschenk senden wir Ihnen einen Gutschein über %s');
define('EMAIL_GV_REDEEM', 'Ihr persönlicher Gutscheincode lautet %s. Sie können diese Gutschrift entweder während dem Bestellvorgang verbuchen');
define('EMAIL_GV_LINK', 'oder direkt über diesen Link: ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Herzlich willkommen in unserem Webshop. Für Ihren ersten Einkauf verfügen Sie über einen kleinen Einkaufsgutschein,' . "\n" .
                                        ' alle notwendigen Informationen diesbezüglich finden Sie hier:' . "\n\n");
define('EMAIL_COUPON_REDEEM', 'Geben Sie einfach Ihren persönlichen Code %s während des Bezahlvorganges ' . "\n" . 'ein');
/* CCGV ADDED - END */

?>
