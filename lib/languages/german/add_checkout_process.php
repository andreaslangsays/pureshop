<?php
/*
  $Id: send_html_mail, v 5.0 2003/06/29 22:50:52 Gyakutsuki Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


//gestion des define
  
// define('STORE_LOGO', 'pixel_trans.gif');      //Image de votre logo. Cette image doit se situer dans votre repertoire catalog/images, si vous ne voulez pas de logo laisser le gif transparent.
 define('STORE_LOGO', '' . STORE_LOGO . ''); // logo de la boutique
//define('STORE_LOGO', 'logo.jpg'); // autre image que le logo de la boutique
define('BG_TOP_EMAIL', 'mailbg.png');    //Image de votre fond background pour le entete de email. Remplacez pixel_trans.gif par votre image de fond...cette image doit se situer dans votre repertoire catalog/images, si vous en avez pas ce sera alors la couleur de fond cidessous qui apparaitra.
define('BG_TOP_EMAIL', '#F9F9F9');    //Image de votre fond background pour le entete de email. Remplacez pixel_trans.gif par votre image de fond...cette image doit se situer dans votre repertoire catalog/images, si
//REDESIGN
define('ORDER_DETAILS_HEADER','Bestellungsdetails');
//REDESIGN

define('COLOR_TOP_EMAIL', '#FFF');         //couleur de fond du cadre comprenant le logo et votre image de fond du cadre logo si vous en avez une
define('COLOR_TABLE', '#FFCC99');         //couleur de fond du email
// define('TRCOLOR', '#879385');         //couleur de fond des lignes des titres 
define('TRCOLORR', '#F9F9F9');         //couleur de fond des lignes description produits 
define('TRCOLORRR', '#F0F0F0');

define('CATEGORY_PERSONAL', '<b><u>Ihre persönlichen Informationen</u> :</b>');
define('EMAIL_TEXT_DEAR', '<br><br>Guten Tag,');        //texte devant le nom de la personne
define('MESSAGE_SOCIETY', 'Wir danken für Ihr Interesse an unseren Produkten und das uns entgegengebrachte Vertrauen. <br>
Alle Details zu Ihrer Bestellung finden Sie in dieser Email und sind zusätzlich in Ihrem Account abrufbar.<br><br>
Sollten Sie Fragen haben, wenden Sie sich gerne per Telefon oder Email an unser Team.<br><br>
Ihre ');
define('EMAIL_TEXT_USER', '<b>Email </b>');     //texte devant le mail de la personne
define('CATEGORY_ADDRESS_IP', 'Ihre IP-Adresse :');
define('EMAIL_COMMENTS','<br><b>Kommentare :</b>');
define('DETAIL', 'Details Ihrer Bestellung');         //def en francais
define('EMAIL_NO_MODEL', '<font size="-2">Article without definite model</font>');   //def en francais
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellung Nr. ');  //def en francais
define('EMAIL_TEXT_INVOICE_URL', 'Bestellungsdetails');  //def en francais
define('EMAIL_TEXT_DATE_ORDERED', '<b>Bestelldatum</b>');  //def en francais

define('EMAIL_TEXT_PRODUCTS_QTY', 'Menge');
define('EMAIL_TEXT_PRODUCTS_MODEL', 'Artikel');

define('EMAIL_TEXT_FOOTER', 'Vielen Dank für Ihre Bestellung');     //texte fin de mail 
define('EMAIL_TEXT_FOOTERR', 'Bei Fragen und Problemen können Sie sich jederzeit an uns wenden');     //texte fin de mail apres le nom de votre boutique



//gestion des variables

define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="mail_html_catalog.css">');   //lien vers votre nouveau css de email
define('VARHTTP', '<base href="' . HTTP_SERVER . DIR_WS_CATALOG . '">');   //ne rien changer
define('VARMAILFOOTER2', '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'. HTTP_SERVER . DIR_WS_CATALOG .'</a> '. "\n" . '  <font size=-2>'.EMAIL_TEXT_FOOTERR .'</font>');   
define('VARMAILFOOTER1', '' . EMAIL_TEXT_FOOTER . ' <br><br>');   //def en francais
define('VARLOGO', '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> ');   
define('VARTABLE1', '<table width="100%" border="0" cellpadding="0" cellspacing="0"> ');
define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" >');   
define('IMG', '<IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES .'mail/detail_cde.gif" border=0>');   
define('EMAIL_TEXT_CUSTID', '<b>Kundennr.</b>');
   
?>
