<?php
/*
  $Id: send_html_mail, v 5.0 2003/06/29 22:50:52 Gyakutsuki Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

 
define('STORE_LOGO', 'bkr-logo.png');      //Image de votre logo. Cette image doit se situer dans votre repertoire catalog/images, si vous ne voulez pas de logo laisser le gif transparent.
define('EMAILCOM', '' . STORE_OWNER_EMAIL_ADDRESS . '');       //votre email de liaison

// Couleur du fonds et liens
define ('BODY','<BODY vLink="#006400" aLink="#006400" link="#006400" bgColor="#ffcc99">');

// Introduction
define('EMAIL_TEXT_DEAR', '<FONT face=Arial><STRONG>Sehr geehrter Kunde</STRONG> </FONT>');        //texte devant le nom de la personne
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellnummer :');  //def en francais
define('EMAIL_TEXT_DATE','Datum(d/m/Y)');
define('EMAIL_TEXT_DATE_SHIPPING', 'Tag der Versendung :'); //def en francais
define('EMAIL_TEXT_INTRO_CUSTOMERS_SERVICE','Ihr Kundenservice.<br>');

// Image
define('EMAIL_IMAGE_TITRE_ENVOI', '<IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/titre_envoi.gif">'); 
define('EMAIL_IMAGE_VARLOGO', '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .' " border=0></a> ');   
define('EMAIL_IMAGE_FOURMI','<IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/fourmi_ar.jpg"  border=0> ');
define('EMAIL_IMAGE_COMMANDE', '<IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/detail_cde.gif"  border=0>'); 
define('EMAIL_IMAGE_SVC','<A href="'. HTTP_SERVER . DIR_WS_CATALOG .'contact_us.php"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/svc_1.gif"  border=0> ');
define('EMAIL_IMAGE_ST','<IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/mail_st.gif" border=0>');

// Detail commande
define('EMAIL_TEXT_TITLE','Der Status Ihrer Bestellung auf ' . HTTP_SERVER .'');
define('EMAIL_TEXT_FOLLOW_ORDER', '<br><font color="#999999"><b>FORTSETZUNG IHRER BESTELLUNG...<br><br></b></font>');
define('EMAIL_TEXT_DATE_ORDERED', 'Datum Ihrer Bestellung : ');  //def en francais
define('EMAIL_TEXT_INVOICE_URL', '<br>Bestellung ansehen : ');  //def en francais
define('EMAIL_TEXT_STATUT','<b>Der Status Ihres Auftrages</b>');

// Tableau Contact / Mail / telephone
define('EMAIL_TEXT_CONTACT_SERVICE_CLIENT','So erreichen Sie uns, wenn Sie eine Frage zu Ihrer Bestellung haben');
define('EMAIL_TEXT_CONTACT_SERVICE_CLIENT_SHEET1','Per Telefon :');
define('EMAIL_TEXT_CONTACT_SERVICE_CLIENT_SHEET_CONTENT1', '<div align="center"> <FONT face=Arial color=darkgreen size=2><B>+49 30 28470028</B></FONT><br><br><FONT  color=#cc0000 size=1 face=Arial>&nbsp;</FONT>&nbsp; <br><B><FONT  face=Arial color=#003399 size=1>Montag bis Freitag : 8:00-20:00 Uhr <b> Samstags : 8:00 - 16:00 Uhr</FONT></div>');
define('EMAIL_TEXT_CONTACT_SERVICE_CLIENT_SHEET2','Per e-mail :');
define('EMAIL_TEXT_CONTACT_SERVICE_CLIENT_SHEET_CONTENT2','<div align="center"> <FONT face=Arial  color="#cc0000" size=2><B> '. STORE_OWNER_EMAIL_ADDRESS .'</B></FONT><br><br><FONT face=Arial color=#003399 size=1> &nbsp; </div><br>Montag bis Freitag : 8:00-20:00 Uhr <br> Samstags : 8:00 - 16:00 Uhr</FONT></div>');
define('EMAIL_TEXT_CONTACT_SERVICE_CLIENT_SHEET3','<STRONG>Per Post </STRONG> <SUP> <FONT color=white>(1) </FONT> </SUP> <STRONG>');
define('EMAIL_TEXT_CONTACT_SERVICE_CLIENT_SHEET_CONTENT3','<div align="center"><FONT face=Arial color=darkgreen size=2><B>Kundenservice</B></FONT><br><br><FONT face=Arial color=darkgreen size=1>'. STORE_NAME_ADDRESS.'</font></DIV> ');

// Contact
define('EMAIL_TEXT_CONTACT','Stellen Sie jederzeit kostenlos Fragen an unseren Kundenservice!');
define('EMAIL_TEXT_CONTACT_CONTENT','Sie erreichen uns über unseren Shop <A href="'. HTTP_SERVER . DIR_WS_CATALOG .'">'. STORE_NAME .'</a>, oder über die Seite <A href="'. HTTP_SERVER . DIR_WS_CATALOG .'/contact_us.php">Kontakt</a>, sie können uns jederzeit gerne Fragen zum Fortschritt Ihrer Bestellung stellen');
define('EMAIL_TEXT_NOTE','');
define('EMAIL_TEXT_END','Ihr Bruesselser Kakaoroesterei Team');

define('EMAIL_TEXT_NOHTML','Wenn Sie diese e-mail nicht lesen können, schauen Sie in Ihr Kundenkonto:  <A href="'. HTTP_SERVER . DIR_WS_CATALOG .'">'. STORE_NAME .'</a> um sich über den Status Ihrer Bestellung zu informieren');


// Statut = 1 la commande n\'est pas encore traitée
define('EMAIL_IMAGE_ARGO','<IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/ar_go.gif">');
define('EMAIL_TEXT_INTRO_CUSTOMERS','<b>Wir freuen uns sehr Ihnen mitteilen zu können, dass wir mit der Bearbeitung Ihrer Bestellung begonnen haben.</b><p> Unten finden Sie noch einmal eine Übersicht über die Details Ihrer Bestellung</p>Vielen Dank für Ihr Vertrauen und bis bald bei ihrer <a href="' . HTTP_SERVER . '">' .STORE_OWNER . '</a>.<br><br>Mit freundlichen Gruessen,<br>Ihr Bruesselser Kakaoroesterei Team'); //def en francais
define('EMAIL_IMAGE_POSTE', '<A href="'. HTTP_SERVER . DIR_WS_CATALOG .'account.php">STATUS'); 
define('EMAIL_TEXT_POST','<b>Um die Bearbeitung Ihres Auftrages zu verfolgen klicken Sie hier :</B>');
define('EMAIL_TEXT_DELAY','<B>Sollte es Verzögerungen geben :</B>');
define('EMAIL_TEXT_DELAY_CONTENT','Einige Informationen über den Versand. Die normale Zustellungszeit für Paketpost liegt zwischen 1 und 2 Tagen. Sollten Sie nach 5 Tagen Ihre Bestellung noch nicht erhalten haben, setzen Sie sich mit uns in Verbindung.');
define('EMAIL_TEXT_WARNING','<font face=Arial,Helvetica,Geneva,Swiss,SunSans-Regular color="#cc0000" size=2><B>Achtung !</B></FONT> <font face=Arial,Helvetica,Geneva,Swiss,SunSans-Regular color="#ffffff" size=2><B> Sollte die Sendung mit Beschädigungen bei Ihnen ankommen : </B></font>');
define('EMAIL_TEXT_WARNING_CONTENT','Bitte prüfen Sie bei eintreffen des Paketes, dieses auf eventuelle Beschädigungen. Sollte Ihre Sendng beschädigt sein, lassen Sie es sich durch Ihren Paketboten quittieren.');
define('EMAIL_TEXT_COMPOSE','Ihr Auftrag besteht aus mehreren Paketen : ');
define('EMAIL_TEXT_COMPOSE_CONTENT','Für mehr Sicherheit und Komfort, werden wir Ihre Sendung unter Umständen in mehrere Pakete aufteilen.');


// Statut = 2 la commande est en cours de traitement
define('EMAIL_IMAGE_ARGO1','<IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/ar_go1.gif">');
define('EMAIL_TEXT_INTRO_CUSTOMERS1','<b>Wir freuen uns, Ihnen mitzuteilen, dass wir nun Ihren Auftrag bearbeiten.</b><p> Unten sehen Sie noch einmal alle Punkte bezüglich Ihrer Bestellung.</p>Vielen Dank für Ihr Vertrauen, bis bald bei ihrer <a href="' . HTTP_SERVER . '">' .STORE_OWNER . '</a>.<br><br>Mit freundlichen Grüssen,<br>Ihre Team der Bruesselser Kakaorösterei'); //def en francais
define('EMAIL_IMAGE_POSTE1', '<A href="'. HTTP_SERVER . DIR_WS_CATALOG .'account.php">STATUS'); 
define('EMAIL_TEXT_POST1','<b>Um sich den Verlauf Ihrer Bestellung anzusehen klicken Sie hier :</B>');
define('EMAIL_TEXT_DELAY1','<B>Sollte es Verzögerungen geben :</B>');
define('EMAIL_TEXT_DELAY_CONTENT1','Einige Informationen über den Versand. Die normale Zustellungszeit für Paketpost liegt zwischen 1 und 2 Tagen. Sollten Sie nach 5 Tagen Ihre Bestellung noch nicht erhalten haben, setzen Sie sich mit uns in Verbindung.');
define('EMAIL_TEXT_WARNING1','<font face=Arial,Helvetica,Geneva,Swiss,SunSans-Regular color="#cc0000" size=2><B>Achtung !</B></FONT> <font face=Arial,Helvetica,Geneva,Swiss,SunSans-Regular color="#ffffff" size=2><B> Sollte Ihre Sendung beschädigt sein : </B></font>');
define('EMAIL_TEXT_WARNING_CONTENT1','Bitte prüfen Sie bei eintreffen des Paketes, dieses auf eventuelle Beschädigungen. Sollte Ihre Sendng beschädigt sein, lassen Sie es sich durch Ihren Paketboten quittieren.');
define('EMAIL_TEXT_COMPOSE1','Ihr Auftrag kann aus mehreren Paketen bestehen: ');
define('EMAIL_TEXT_COMPOSE_CONTENT1','Für mehr Sicherheit und Komfort, werden wir Ihre Sendung unter Umständen in mehrere Pakete aufteilen.');


// Statut = 3 la commande est envoyée.
define('EMAIL_IMAGE_ARGO2','<IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'mail/ar_go2.gif">');
define('EMAIL_TEXT_INTRO_CUSTOMERS2','<b>Wir freuen uns Ihnen mitzuteilen, dass Ihre Sendung unser Haus verlassen hat.</b><p> Unten sehen Sie noch einmal alle Punkte bezüglich Ihrer Bestellung.</p>Vielen Dank für Ihr Vertrauen, bis bald bei ihrer <a href="' . HTTP_SERVER . '">' .STORE_OWNER . '</a>.<br><br>Mit freundlichen Grüssen.<br>Ihre Team der Bruesselser Kakaorösterei.<br>'); //def en francais
define('EMAIL_IMAGE_POSTE2', '<A href="'. HTTP_SERVER . DIR_WS_CATALOG .'account.php">STATUS'); 
define('EMAIL_TEXT_POST2','<b>Um die Bearbeitung Ihres Auftrages zu verfolgen klicken Sie hier :');
define('EMAIL_TEXT_DELAY2','<B>Sollte es Verzögerungen geben :</B>');
define('EMAIL_TEXT_DELAY_CONTENT2','Wir haben so eben Ihre Sendung in den Versand gegeben. Die normale Zustellungszeit für Paketpost liegt zwischen 1 und 3 Tagen. Sollten Sie nach 5 Tagen Ihre Bestellung noch nicht erhalten haben, setzen Sie sich mit uns in Verbindung.');
define('EMAIL_TEXT_WARNING2','<font face=Arial,Helvetica,Geneva,Swiss,SunSans-Regular color="#cc0000" size=2><B>Achtung !</B></FONT> <font face=Arial,Helvetica,Geneva,Swiss,SunSans-Regular color="#ffffff" size=2><B> Sollte die Sendung mit Beschädigungen bei Ihnen ankommen : </B></font>');
define('EMAIL_TEXT_WARNING_CONTENT2','Bitte prüfen Sie bei eintreffen des Paketes, dieses auf eventuelle Beschädigungen. Sollte Ihre Sendng beschädigt sein, lassen Sie es sich durch Ihren Paketboten quittieren.');
define('EMAIL_TEXT_COMPOSE2','Ihr Auftrag besteht aus mehreren Paketen : ');
define('EMAIL_TEXT_COMPOSE_CONTENT2','Für mehr Sicherheit und Komfort, werden wir Ihre Sendung unter Umständen in mehrere Pakete aufteilen.');
define('EMAIL_TEXT_NOTE1','Bewahren Sie diese Best&auml;tigung auf bis Sie Ihre Bestellung erhalten haben !');

//gestion des variables
define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="' . HTTP_SERVER . DIR_WS_CATALOG .'mail_html_admin.css">');   //lien vers votre nouveau css de email
define('VARHTTP', '<base href="' . HTTP_SERVER . DIR_WS_CATALOG . '">');
   
?>
