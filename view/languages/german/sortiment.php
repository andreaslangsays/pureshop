<?php
/*
  $Id: wishlist.php,v 3.0  2005/04/20 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/
define('WISHLISTNAME' , "Sortiment");
define('NAVBAR_TITLE_WISHLIST', WISHLISTNAME);
define('HEADING_TITLE', 'Mein ' . WISHLISTNAME);
define('HEADING_TITLE2', '\s ' . WISHLISTNAME . ' enth&auml;lt:');
define('BOX_TEXT_PRICE', 'Preis je St&uuml;ck');
define('BOX_TEXT_PRODUCT_AND_SHIPPING', 'Artikel und Lieferzeit');
define('BOX_TEXT_PRODUCT', 'Produkt');
define('BOX_TEXT_IMAGE', 'Bild');
define('BOX_TEXT_SELECT', 'Auswahl');

define('BOX_TEXT_VIEW', 'Zeigen');
define('BOX_TEXT_HELP', 'Hilfe');
define('BOX_WISHLIST_EMPTY', '0 Eintrag');
define('BOX_TEXT_NO_ITEMS', 'Es sind keine Produkte in Ihrem ' . WISHLISTNAME);

define('TEXT_NAME', 'Name: ');
define('TEXT_EMAIL', 'Email: ');
define('TEXT_YOUR_NAME', 'Ihr Name: ');
define('TEXT_YOUR_EMAIL', 'Ihre Email: ');
define('TEXT_MESSAGE', 'nachricht: ');
define('TEXT_ITEM_IN_CART', 'Bereits im Warenkorb');
define('TEXT_ITEM_NOT_AVAILABLE', 'Der Artikel ist nicht mehr erh&auml;ltlich');
define('TEXT_DISPLAY_NUMBER_OF_WISHLIST', '<b>%d</b> bis <b>%d</b> (von <b>%d</b> Eintr&auml;gen auf Ihrem ' . WISHLISTNAME . '.)');
define('WISHLIST_EMAIL_TEXT', 'If you would like to email your wishlist to multiple friends or family, just enter their name\'s and email\'s in each row.  You don\'t have to fill every box up, you can just fill in for however many people you want to email your wishlist link too.  Then fill out a short message you would like to include in with your email in the text box provided.  This message will be added to all the emails you send.');
define('WISHLIST_EMAIL_TEXT_GUEST', 'If you would like to email your wishlist to multiple friends or family, please enter your name and email address.  Then enter their name\'s and email\'s in each row.  You don\'t have to fill every box up, you can just fill in for however many people you want to email your wishlist link too.  Then fill out a short message you would like to include in with your email in the text box provided.  This message will be added to all the emails you send.');
define('WISHLIST_EMAIL_SUBJECT', 'hat Ihnen einen ' . WISHLISTNAME . ' aus der ' . STORE_NAME);  //Customers name will be automatically added to the beginning of this.
define('WISHLIST_SENT', 'Ihr ' . WISHLISTNAME . ' wurde gesendet.');
define('WISHLIST_EMAIL_LINK', '

$from_name\'s &ouml;ffentliches ' . WISHLISTNAME . ' befindet sich hier:
$link

Vielen Dank,
' . STORE_NAME); //$from_name = Customers name  $link = public wishlist link

define('WISHLIST_EMAIL_GUEST', 'Vielen Dank,
' . STORE_NAME);

define('ERROR_YOUR_NAME' , 'Bitte geben Sie Ihren Namen an.');
define('ERROR_YOUR_EMAIL' , 'Bitte geben Sie Ihre e-Mail-Adresse an.');
define('ERROR_VALID_EMAIL' , 'Bitte geben Sie eine g&uuml;ltige e-Mail-Adresse an.');
define('ERROR_ONE_EMAIL' , 'Sie m&uuml;ssen mindestens einen Namen und eine e-Mail-Adresse angeben');
define('ERROR_ENTER_EMAIL' , 'Bitte geben Sie eine e-Mail-Adresse an.');
define('ERROR_ENTER_NAME' , 'Bitte geben Sie einen Namen zu dem e-Mail-Empf&auml;nger an.');
define('ERROR_MESSAGE', 'Bitte verfassen Sie auch eine kurze Nachricht.');
?>
