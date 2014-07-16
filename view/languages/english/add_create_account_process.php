<?php
/*
  $Id: send_html_mail, v 5.0 2003/06/29 22:50:52 Gyakutsuki Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('STORE_LOGO', 'pixel_trans.gif');      //IMAGE DU LOGO..Remplacez pixel_trans.gif par l'image de votre logo, cette image doit se situer dans votre repertoire catalog/images, si vous ne voulez pas de logo laisser le gif transparent.
define('BG_TOP_EMAIL', 'pixel_trans.gif');    //Image de votre fond background pour le entete de email. Remplacez pixel_trans.gif par votre image de fond...cette image doit se situer dans votre repertoire catalog/images, si vous en avez pas ce sera alors la couleur de fond cidessous qui apparaitra.
define('COLOR_TOP_EMAIL', '#FFCC99');         //couleur de fond du cadre comprenant le logo et votre image de fond du cadre logo si vous en avez une
define('COLOR_TABLE', '#FFCC99');         //couleur de fond du email
     
define('EMAILTECH', '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">Technischer Service</a><br>');  //votre email de liaison 1
define('EMAILCOM', '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">Kundenservice</a><br>');       //votre email de liaison 2


define('EMAILGREET_MR', '<b>Sehr geehrter Herr ' . stripslashes($_POST['lastname'].'</b>') . ',<br><br>' . "\n");
define('EMAILGREET_MS', '<b>Sehr geehrte Frau  ' . stripslashes($_POST['lastname'].'</b>') . ',<br><br>' . "\n");
define('EMAILGREET_NONE', '<b>Hallo ' . stripslashes($_POST['lastname'].'</b>') . ',<br><br>' . "\n");
define('EMAILWELCOME', 'Herzlich Willkommen in der ' . STORE_NAME . '<br><br> '. "\n\n\n");  // texte d'intro du mail
define('EMAILTEXT', 'Ihr Account ist eingerichtet.<br> Damit kommen Sie nun in den Genuß einiger Vorteile:<br>' . "\n" . '<blockquote>Sie brauchen die persönlichen Daten inkl. Rechnungs- und Lieferadresse nur bei der ersten Bestellung eingeben. Bei späteren Bestellungen sind diese Daten dann bereits voreingetragen.
[Die Zahlungsdaten (Kreditkarten- oder Bankkontonummern) werden nicht gespeichert und müssen bei jeder Bestellung neu eingegeben werden. Zur Eingabe dieser sensiblen Daten wird eine sichere Verbindung aufgebaut.]
</blockquote>' ."\n". '<blockquote>Ihre alten Bestellungen können Sie jederzeit noch einmal einsehen und so interessante Produkte wieder heraussuchen.<br>Alle von Ihnen bestellten Produkte können Sie nun auch bewerten.</blockquote><br>' . "\n" . '<blockquote>Wir wünschen Ihnen viel Freude beim Einkauf in unserem Onlineshop.</blockquote><br><br>');
define('EMAILCONTACT', 'Bei Fragen oder Anregungen kontaktieren Sie uns einfach: ' . EMAILTECH .  "\n");  //contenu info du mail suite
define('EMAILWARNING', '<font size="-2">Wenn Sie sich nicht auf unserer Website registriert haben, senden Sie uns bitte eine eMail an: ' . EMAILCOM . '<br>Teilen Sie uns bitte mit, dass jemand Ihre Identität missbraucht hat. Vielen Dank!</font><br><br>' . "\n"); //contenu info du mail fin
define('EMAIL_SEPARATOR', '----------------------------------------------------------------------------------- ' . "\n");
define('EMAIL_TEXT_FOOTER', '');
define('EMAIL_TEXT_FOOTERR', ''); 

define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="'. HTTP_SERVER . DIR_WS_CATALOG . 'mail_html_catalog.css">');   //lien vers votre nouveau css de email
define('VARLOGO', ' <a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> ');
define('VARTABLE1', '<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="' . COLOR_TOP_EMAIL . '" background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/' . BG_TOP_EMAIL . '" > ');
define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" bgcolor="' . COLOR_TABLE . '">' ) ;

?>