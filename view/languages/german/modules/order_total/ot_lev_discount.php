<?php
/*
  $Id: ot_lev_discount.php,v 1.1 2002/08/30 21:05:04 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_LEV_DISCOUNT_TITLE',       'Mitarbeiter-Rabatt');
  define('MODULE_LEV_DISCOUNT_DESCRIPTION', 'Mitarbeiter-Rabatt' . '<br><br>' .
                                            '<B><U>WICHTIG!:</U></B>' . '<br>' .
                                            'Wenn die Steuer neu berechnet werden soll, ' .
                                            'm&uuml;ssen die Optionen ' .
                                            '<I>Include Shipping</I> und <I>Include Tax</I> auf <B>false</B> ' .
                                            'und ' .
                                            '<I>Calculate Tax</I> auf <B>true</B> gesetzt werden. ' . '<br>' .
                                            'Zudem muss die Sortierung dieses Modules ' .
                                            'so gesetzt werden, dass es vor der Bestellsumme ' .
                                            'angezeigt wird.' . '<br><br>' .
                                            '<B>Discount Percentage</B> muss so definiert werden:' . '<br>' .
                                            'Beispiel: 100:7.5,250:10' . '<br>' .
                                            'Bestellungen &uuml;ber 100.- erhalten 7.5% Rabatt, ' . '<br>' .
                                            'Bestellungen &uuml;ber 250.- erhalten 10% Rabatt.');

  define('SHIPPING_NOT_INCLUDED',           ' [Lieferung nicht inbegriffen]');
  define('TAX_NOT_INCLUDED',                ' [Steuer nicht inbegriffen]');

?>
