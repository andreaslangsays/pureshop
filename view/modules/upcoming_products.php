<?php
/*
  $Id: upcoming_products.php,v 1.24 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//rmh M-S_multi-stores edited next line
  $expected_query = push_db_query("select p.products_id, pd.products_name, products_date_available as date_expected from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_STORES . " p2s where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and p.products_id = p2s.products_id and p2s.stores_id = '" . STORES_ID . "' and pd.language_id = '" . (int)$languages_id . "' order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . " limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);  if (push_db_num_rows($expected_query) > 0) {
?>
<!-- upcoming_products //-->
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_UPCOMING_PRODUCTS; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_DATE_EXPECTED; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><?php echo push_draw_separator(); ?></td>
              </tr>
              <tr>
<?php
    $row = 0;
    while ($expected = push_db_fetch_array($expected_query)) {
      $row++;
      if (($row / 2) == floor($row / 2)) {
        echo '              <tr class="upcomingProducts-even">' . "\n";
      } else {
        echo '              <tr class="upcomingProducts-odd">' . "\n";
      }

      echo '                <td class="smallText">&nbsp;<a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected['products_id']) . '">' . $expected['products_name'] . '</a>&nbsp;</td>' . "\n" .
           '                <td align="right" class="smallText">&nbsp;' . push_date_short($expected['date_expected']) . '&nbsp;</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
              <tr>
                <td colspan="2"><?php echo push_draw_separator(); ?></td>
              </tr>
            </table></td>
          </tr>
<!-- upcoming_products_eof //-->
<?php
  }
?>