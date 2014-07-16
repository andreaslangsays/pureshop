<?php
/*
  $Id: forum.php, v 1 2002/11/19 01:48:08 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Forum');
define('NAVBAR_TITLE', 'Forum');
define('FORUM_TITLE', 'Forum: Diskutieren, austauschen, fragen, ...');

define('TABLE_FORUM_POST', 'forum_post');
define('TABLE_FORUM_TOPIC', 'forum_topic');
define('TABLE_FORUM_POST_TEXT', 'forum_postext');

define('FORUM_POSTED', 'Autor');
define('FORUM_TOPIC', 'Thema');
define('FORUM_VIEWS', 'Aufrufe');
define('FORUM_REPLIES', 'Antworten');
define('FORUM_LAST_REPLY', 'Letzte Antwort');

define('FORUM_TOTAL_TOPICS', 'Themen: ');
define('FORUM_TOTAL_POSTS', 'Beitr&auml;ge: ');
define('FORUM_NO_POSTS', 'Keine Beitr&auml;ge gefunden.');
define('FORUM_THREAD_VIEWS', 'Aufrufe: ');
define('FORUM_TOPIC_REPLIES', 'Antworten: ');
define('TEXT_DISPLAY_NUMBER_OF_POSTS', 'Beitrag <b>%d</b> bis <b>%d</b> [%d Beitr&auml;ge]');
define('TEXT_DISPLAY_NUMBER_OF_TOPICS', '<b>%d</b> bis <b>%d</b> (von <b>%d</b> Themen)');
define('FORUM_NEW_THREAD_HEADING', 'Neues Thema anlegen');
define('FORUM_NEW_REPLY_HEADING', 'Antworten');
define('FORUM_NEW_CONTACT_HEADING', 'Schreiben Sie eine Nachricht');

define('FORUM_NEW_THREAD_NAME','* Ihr Name');
define('FORUM_NEW_THREAD_EMAIL','Ihre eMail Adresse');
define('FORUM_EMAIL_DISCLAIMER',' (<i>Wird nicht angezeigt</i>)');
define('FORUM_NEW_THREAD_HOMEPAGE','Ihre HomePage');
define('FORUM_NEW_THREAD_SUBJECT','* Thema');
define('FORUM_NEW_THREAD_TEXT','* Nachricht');
define('FORUM_NEW_THREAD_REQUIRED','Markierte (*) Felder sind Pflichtfelder.');

define('FORUM_BUTTON_POSTMESSAGE', 'Diese Nachricht senden.');
define('FORUM_LINK', 'Zur&uuml;ck zum Forum');
define('FORUM_NEW_TOPIC', 'Neues Thema');
define('FORUM_BUTTON_REPLY', 'Antwort zu diesem Thema');

define('FORUM_DISCLAIMER', 'Die Ansichten und Meinungen in diesem Forum sind nicht notwendigerweise die der ' . STORE_NAME . '.');

define('TEXT_SUCCESS', 'Ihre eMail wurde erfolgreich gesendet!');
define('EMAIL_SUBJECT_1', 'Re: "');
define('EMAIL_SUBJECT_2', '" ' . STORE_NAME . ' (Forum)');
define('EMAIL_TO', $_GET["cont"] . " <i>[Address hidden]</i>" );

define('CONTACT_NAME', 'Nachricht an: ');

define('ENTRY_TO', 'An');
define('ENTRY_SUBJECT', 'Betreff');
define('ENTRY_NAME', 'Ihr Name');
define('ENTRY_EMAIL', 'Ihre&nbsp;eMail&nbsp;Adresse');
define('ENTRY_ENQUIRY', 'Nachricht');
define('ENTRY_EMAIL_CONTENT_CHECK_ERROR', 'Ihre Nachricht muss mehr als 50 Zeichen enthalten.');

define('CONTACT_DISCLAIMER', 'Alle eMails und IP-Adressen werden geloggt um Missbrauch zu verhindern.<br>Nur registrierte Kunden k&ouml;nnen eMails senden.');
?>
