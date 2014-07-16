<?php
/**
 *
 *
 * @version Sofortüberweisung 1.9  27.06.2007
 * @author Henri Schmidhuber  info@in-solution.de
 * @copyright 2006 - 2007 Henri Schmidhuber
 * @link http://www.in-solution.de
 * @link http://www.oscommerce.com
 * @link http://www.sofort-ueberweisung.de
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 *
 ***********************************************************************************
 * this file contains code based on:
 * (c) 2000 - 2001 The Exchange Project
 * (c) 2001 - 2006 osCommerce, Open Source E-Commerce Solutions
 * Released under the GNU General Public License
 ***********************************************************************************
 *
 */

define('NAVBAR_TITLE', 'Sofortüberweisung');
define('HEADING_TITLE', 'Sofortüberweisung');

$sofortueberweisung_payto = '';
if (defined('MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NUMBER')) {
  $sofortueberweisung_payto = "Kontoinhaber: " .  MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_OWNER . "<br>Kontonummer: " . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NUMBER . "<br>BLZ:" . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_BLZ . "<br>Bank:" . MODULE_PAYMENT_SOFORTUEBERWEISUNG_PAYTO_BANK_NAME;
} elseif (defined('MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASS_PAYTO_BANK_NUMBER')) {
  $sofortueberweisung_payto = "Kontoinhaber: " .  MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASS_PAYTO_BANK_OWNER . "<br>Kontonummer: " . MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASS_PAYTO_BANK_NUMBER . "<br>BLZ:" . MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASS_PAYTO_BANK_BLZ . "<br>Bank:" . MODULE_PAYMENT_SOFORTUEBERWEISUNGVORKASS_PAYTO_BANK_NAME;
}

define('TEXT_INFORMATION', 'Leider funktioniert Sofortueberweisung.de derzeit noch nicht mit Ihrem
Kreditinstitut. Wir werden uns bemühen, dass auch Sie in Kürze diesen
Paymentdienst nutzen können.<br>
<br>
Bitte überweisen Sie uns den Rechnungsbetrag auf das folgende Konto:<br>
' . $sofortueberweisung_payto);
?>