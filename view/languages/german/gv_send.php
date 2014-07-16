<?php
/*
  $Id: gv_send.php,v 1.1.2.1 2003/05/15 23:04:32 wilt Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Gutschein versenden');
define('NAVBAR_TITLE', 'Gutschein versenden');
define('EMAIL_SUBJECT', 'Nachricht von ' . STORE_NAME);
define('HEADING_TEXT','<br>Bitte f&uuml;llen Sie hier Ihre pers&ouml;nlichen Angaben zum Gutschein aus, falls Sie Fragen bez&uuml;glich der Gutscheinfunktion haben, helfen wir Ihnen unter <a href="' . push_href_link(FILENAME_GV_FAQ,'','NONSSL').'">'.GV_FAQ.'.</a> gerne weiter.<br>');
define('ENTRY_NAME', 'Name des Empf&auml;ngers:');
define('ENTRY_EMAIL', 'E-Mail Adresse des Empf&auml;ngers:');
define('ENTRY_MESSAGE', 'Ihre Nachricht an den Empf&auml;nger:');
define('ENTRY_AMOUNT', 'Wert des Gutscheins (ohne W&auml;hrungsbezeichnung):');
define('ERROR_ENTRY_AMOUNT_CHECK', '&nbsp;&nbsp;<span class="errorText">Ungültiger Wert</span>');
define('ERROR_ENTRY_EMAIL_ADDRESS_CHECK', '&nbsp;&nbsp;<span class="errorText">Ungültige E-Mail Addresse</span>');
define('MAIN_MESSAGE', 'Sie m&ouml;chten einen Gutschein &uuml;ber <b style="color: #EB7F00;">%s</b> an <b>%s</b> senden <br />(E-Mail Adresse lautet <b>%s</b>). <br><br>Folgender Text erscheint in Ihrer E-Mail :<br><br>Hallo %s<br><br>
                        dies ist ein Geschenkgutschein &uuml;ber %s von %s');

define('PERSONAL_MESSAGE', '%s schreibt: ');
define('TEXT_SUCCESS', 'Gl&uuml;ckwunsch, Ihr Gutschein wurde versendet!');


define('EMAIL_SEPARATOR', '<hr style="border:1px solid #e2bf87;width:550px;">');
define('EMAIL_GV_TEXT_HEADER', 'Herzlichen Gl&uuml;ckwunsch, Sie haben einen Gutschein &uuml;ber %s erhalten !');
define('EMAIL_GV_TEXT_SUBJECT', 'Ein Geschenk von %s');
define('EMAIL_GV_FROM', 'Dieser Gutschein wurde Ihnen &uuml;bermittelt von %s');
define('EMAIL_GV_MESSAGE', "\n Mit der Nachricht: ");
define('EMAIL_GV_SEND_TO', '');
define('EMAIL_GV_REDEEM', 'Um diesen Gutschein einzul&ouml;sen, klicken Sie bitte auf den unteren Link. Bitte notieren Sie sich zur Sicherheit Ihren Gutscheincode : <b>%s</b>, so k&ouml;nnen wir Ihnen im Falle eines Problems schneller helfen. Danke..');
define('EMAIL_GV_LINK', 'Um den Gutschein einzul&ouml;sen klicken Sie bitte auf <a href="');
define('EMAIL_GV_LINK_END', '">www.Bruesselser-Kakaoroesterei.de/guschtscheineinloesen</a>');
define('EMAIL_GV_VISIT', ' oder besuchen Sie ');
define('EMAIL_GV_ENTER', ' und geben den Code am Ende Ihrer Bestellung ein. ');
define('EMAIL_GV_FIXED_FOOTER', ' Falls es mit dem obigen Link Probleme beim Einl&ouml;sen kommen sollte, ' . "\n" .
                                'k&ouml;nnen Sie den Betrag w&auml;hrend des Bestellvorganges verbuchen.' );
define('EMAIL_GV_SHOP_FOOTER', '<div style="background-color:#6c2e23;color:#fff;font-size:12px;padding:7px;text-align:center;">'.
		'<a style="color:#fff;" href="http://www.Bruesselser-Kakaoroesterei.de" target="_blank">www.Bruesselser-Kakaoroesterei.de</a> ::	<a style="color:#fff;" href="mailto:kontakt@Bruesselser-Kakaoroesterei.de">kontakt@Bruesselser-Kakaoroesterei.de</a>
		Bruesselser Kakaorösterei :: Mainstr. 173/174 :: 10719 Bruessels
		Telefon: 030 886 779-20 :: Fax: 030 886 779-22
		Internet Hotline: 030 284 700-28
		</div>');
?>
