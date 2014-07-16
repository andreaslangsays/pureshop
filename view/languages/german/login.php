<?php
/*
  $Id: login.php,v 1.15 2003/06/09 22:46:46 hpdl Exp $
  adapted for Separate Pricing Per Customer 2005/02/15

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Anmelden');
define('HEADING_TITLE', 'Anmelden');

define('HEADING_NEW_CUSTOMER', 'Neuer Kunde');
define('TEXT_NEW_CUSTOMER', 'Ich bin ein neuer Kunde.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Durch Ihre Anmeldung bei ' . STORE_NAME . ' sind Sie in der Lage schneller zu bestellen, kennen jederzeit den Status Ihrer Bestellungen und haben immer eine aktuelle &Uuml;bersicht &uuml;ber Ihre bisherigen Bestellungen.');

define('HEADING_RETURNING_CUSTOMER', 'Bereits Kunde');
define('TEXT_RETURNING_CUSTOMER', 'Ich bin bereits Kunde.');

define('TEXT_PASSWORD_FORGOTTEN', 'Passwort vergessen? Hier klicken.');

define('TEXT_LOGIN_ERROR', 'Unbekannte Login-Daten<br /><br /> Hinweis: Ihr Log in ist leider fehlgeschlagen. Bitte &uuml;berpr&uuml;fen Sie Ihre Angaben und versuchen Sie es erneut. Achten Sie auf Gro&szlig;- und Kleinschreibung.');

define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Achtung:</b></font> Ihre Besuchereingaben werden automatisch mit Ihrem Kundenkonto verbunden. <a href="javascript:session_win();">[Mehr Information]</a>');

// BOF Separate Pricing Per Customer
// define the email address that can change customer_group_id on login
define('SPPC_TOGGLE_LOGIN_PASSWORD', 'andreas.lang@Bruesselser-Kakaoroesterei.de');
// EOF Separate Pricing Per Customer

?>