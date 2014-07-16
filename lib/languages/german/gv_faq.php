<?php
/*
  $Id: gv_faq.php,v 1.1.1.1.2.2 2003/05/04 12:24:25 wilt Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Geschenkgutschein FAQ');
define('HEADING_TITLE', 'Geschenkgutschein FAQ');

define('TEXT_INFORMATION', '<a name="Top"></a>
  <a href="'.push_href_link(FILENAME_GV_FAQ,'faq_item=1','NONSSL').'">Geschenkgutscheine kaufen</a><br>
  <a href="'.push_href_link(FILENAME_GV_FAQ,'faq_item=2','NONSSL').'">Wie kann ich Geschenkgutscheine versenden</a><br>
  <a href="'.push_href_link(FILENAME_GV_FAQ,'faq_item=3','NONSSL').'">Kaufen mit Geschenkgutscheinen</a><br>
  <a href="'.push_href_link(FILENAME_GV_FAQ,'faq_item=4','NONSSL').'">Geschenkgutscheine einl&ouml;sen</a><br>
  <a href="'.push_href_link(FILENAME_GV_FAQ,'faq_item=5','NONSSL').'">Wenn Probleme auftauchen</a><br>
');
switch ($_GET['faq_item']) {
  case '1':
define('SUB_HEADING_TITLE','Geschenkgutscheine kaufen.');
define('SUB_HEADING_TEXT','Geschenkgutscheine werden wie andere Artikel in unserem Shop verkauft. Sie k&ouml;nnen
  sie mit unseren g&auml;ngigen Zahlungsmethoden kaufen.<br>
  Der Wert des von Ihnen gekauften Geschenkgutscheins wird Ihrem pers&ouml;nlichen
  Geschenkgutschein-Account gutgeschrieben. Wenn Sie &uuml;ber ein Guthaben in Ihrem Geschenkgutschein-Account verf&uuml;gen, werden Sie
  bemerken, dass der Wert in der Warenkorb Box angezeigt wird und einen Link anbietet, &uuml;ber den Sie den Geschenkgutschein
  per Email senden k&ouml;nnen.');
  break;
  case '2':
define('SUB_HEADING_TITLE','Wie kann ich einen Geschenkgutschein versenden.');
define('SUB_HEADING_TEXT','Um einen Geschenkgutschein zu versenden, m&uuml;ssen Sie zur Seite "Gutschein versenden" navigieren.<br> Sie finden
  den Link zu dieser Seite in der Warenkorb-Box in der rechten Spalte der Website (nachdem Sie einen Gutschein gekauft haben).<br>
  Wenn Sie einen Geschenkgutschein versenden, m&uuml;ssen Sie folgende Angaben machen:<br>
  Den Namen der Person an den Sie den Gutschein senden.<br>
  Die Emailadresse der Person an die Sie den Gutschein senden.<br>
  Den Betrag den Sie Senden wollen. (Sie m&uuml;ssen nicht den Gesamtbetrag ihres Geschenkgutschein-Accounts senden)<br>
  Eine kurze Nachricht, die in der Nachricht erscheinen wird.<br>
  Bitte versichern Sie sich, dass Sie alle Informationen korrekt eingegeben haben, obwohl Sie die M&ouml;glichkeit haben
  die Daten so zu ver&auml;ndern wie Sie wollen bis Sie die Email wirklich abgesendet haben.');
  break;
  case '3':
  define('SUB_HEADING_TITLE','Kaufen mit Geschenkgutscheinen.');
  define('SUB_HEADING_TEXT','Wenn Sie ein Guthaben in Ihrem Geschenkgutschein-Account haben, k&ouml;nnen Sie diesen Betrag nutzen
  um andere Artikel in unserem Onlineshop zu kaufen. W&auml;hrend des Bestellvorganges wird eine Checkbox erscheinen.
  Wenn Sie diese Box anklicken, wird Ihr Gutschein-Guthaben mit dem Warenkorb verrechnet.
  Bitte beachten Sie, dass Sie dennoch eine andere Zahlungsweise ausw&auml;hlen m&uuml;ssen, wenn Ihr Guthaben den Preis des Einkaufs
  nicht erreicht. Wenn Sie mehr Guthaben in Ihrem Geschenkgutschein-Account haben
  als die Gesamtkosten ihres Einkaufs, steht Ihnen der Restbetrag in Ihrem Geschenkgutschein-Account weiterhin zur Verf&uuml;gung.');
  break;
  case '4':
  define('SUB_HEADING_TITLE','Geschenkgutscheine einl&ouml;sen.');
  define('SUB_HEADING_TEXT','Wenn Sie einen Geschenkgutschein via Email erhalten,
  werden Sie sehen von wem Sie den Gutschein erhalten, vielleicht mit einer kurzen Nachricht,
  Die Email wird au&szlig;erdem einen Link zum Einl&ouml;sen des Gutscheins enthalten.
  Sie m&uuml;ssen sich einloggen oder einen Account anlegen bevor Sie den Geschenkgutschein einl&ouml;sen k&ouml;nnen.
  Es gibt verschiedene Wege, einen Gutschein einzul&ouml;sen:<br>
  1. indem Sie den Link innerhalb der Email anklicken der f&uuml;r eine schnelle Einl&ouml;sung vorgesehen ist.
  Damit gelangen Sie auf die Seite auf welcher Gutscheine eingel&ouml;st werden. Sie werden aufgefordert
  einen Account anzulegen oder sich einzuloggen bevor der Gutschein best&auml;tigt und in Ihren Geschenkgutschein-Account
  &uuml;bernommen wird. Das Guthaben steht Ihnen sofort zur Verf&uuml;gung.<br>
  2. W&auml;hrend des Bestellvorgangs gelangen Sie auf die Seite, auf welcher Sie die Zahlungsmethoden w&auml;hlen k&ouml;nnen. Dort erscheint eine Checkbox
  mit welcher Sie die ihr Guthaben auf ihren Einkauf anrechnen k&ouml;nnen.');
  break;
  case '5':
  define('SUB_HEADING_TITLE','Wenn Probleme auftauchen.');
  define('SUB_HEADING_TEXT','F&uuml;r alle Fragen bez&uuml;glich des Geschenkgutschein-Systems setzten Sie sich mit uns via eMail ' .
  		''. STORE_OWNER_EMAIL_ADDRESS . ' in Kontakt. Bitte beschreiben Sie das Problem so genau wie m&ouml;glich.');
  break;
  default:
  define('SUB_HEADING_TITLE','');
  define('SUB_HEADING_TEXT','Bitte w&auml;hlen Sie eine der Fragen oben aus.');

  }
?>