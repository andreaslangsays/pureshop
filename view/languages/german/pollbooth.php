<?php
/*
  $Id: pollbooth.php,v 1.1.1.1 2003/04/01 20:19:05 wilt Exp $
  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org
  Copyright (c) 2000,2001 The Exchange Project
  Released under the GNU General Public License
*/
if (!isset($_GET['op'])) {
	$_GET['op']="list";
	}
if ($_GET['op']=='results') {  define('TOP_BAR_TITLE', 'Abstimmung');  define('HEADING_TITLE', 'Sehen Sie den derzeitigen Trend:');  define('SUB_BAR_TITLE', 'AbstimmungsErgebnis');}if ($_GET['op']=='comment') {  define('TOP_BAR_TITLE', 'Abstimmung');  define('HEADING_TITLE', 'Ihr Kommentar zur Abstimmung:');  define('SUB_BAR_TITLE', 'Kommentar');}
if ($_GET['op']=='list') {
  define('TOP_BAR_TITLE', 'Abstimmung');
  define('HEADING_TITLE', 'Unsere bereits erfolgten Abtimmungen');
  define('SUB_BAR_TITLE', 'Fr&uuml;here Abstimmungen');
}
if ($_GET['op']=='vote') {
  define('TOP_BAR_TITLE', 'Abstimmung');
  define('HEADING_TITLE', 'derzeitig aktive Abstimmung');
  define('SUB_BAR_TITLE', 'Stimmen Sie mit ab');
}
define('_WARNING', 'Achtung : ');
define('_ALREADY_VOTED', 'Sie haben bereits f&uuml;r diesen Trend gestimmt.');
define('_NO_VOTE_SELECTED', 'Leider wurde keine Auswahl getroffen.');
define('_TOTALVOTES', 'bereits abgegebene Stimmen');
define('_OTHERPOLLS', 'weitere Abstimmungen');
define('NAVBAR_TITLE_1', 'Abstimmung');
define('_POLLRESULTS', 'zum aktuellen Zwischenstand');
define('_VOTING', 'Jetzt Abstimmen');
define('_RESULTS', 'Zwischenstand');
define('_VOTES', 'Trend');
define('_VOTE', 'Abstimmen');
define('_PUBLIC','&ouml;ffentlich');
define('_PRIVATE','nicht &ouml;ffentlich');
define('_POLLOPEN', 'aktiv');
define('_POLLCLOSED', 'beendet');
define('_POLLPRIVATE','Abstimmung nur f&uuml;r Kunden, die eingeloggt sind!');
define('_ADD_COMMENTS', 'Kommentar hinzuf&uuml;gen');
define('_COMMENTS', 'Kommentare');
define('TEXT_DISPLAY_NUMBER_OF_COMMENTS', '<b>%d</b> bis <b>%d</b> (von <b>%d</b> Kommentaren) werden angezeigt');define('_YOURNAME', 'Ihr Name');define('_YOURCOMMENT', 'Ihr Kommentar');define('_COMMENTS_BY', '');define('_COMMENTS_ON', ' sagte am ');define('_COMMENTS_POSTED', 'Abgegebene Kommentare');?>
