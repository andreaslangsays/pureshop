<?php
/*
  $Id: new_products.php,v 1.34 2003/06/09 22:49:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- new_products //-->
<?php
//rmh M-S_fixes don't creat the info_box unless it meets minimum quantity
  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
//rmh M-S_multi-stores edited next line
    $new_products_query = push_db_query("select p.products_id, p.products_image, p.products_tax_class_id, IF(s.status = '1' AND s.stores_id = '" . STORES_ID . "', s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id and s.stores_id = '" . STORES_ID . "' INNER JOIN " . TABLE_PRODUCTS_TO_STORES . " p2s ON p.products_id = p2s.products_id where p2s.stores_id = '" . STORES_ID . "' and products_status = '1' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
//rmh M-S_multi-stores edited next line
    $new_products_query = push_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, IF(s.status = '1' AND s.stores_id = '" . STORES_ID . "', s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id and s.stores_id = '" . STORES_ID . "' INNER JOIN " . TABLE_PRODUCTS_TO_STORES . " p2s ON p.products_id = p2s.products_id where p2s.stores_id = '" . STORES_ID . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }

  if (push_db_num_rows($new_products_query) >= MIN_DISPLAY_NEWPRODUCTS) { //rmh M-S_fixes
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));

  new contentBoxHeading($info_box_contents);

  $row = 0;
  $col = 0;
  $info_box_contents = array();
  while ($new_products = push_db_fetch_array($new_products_query)) {
    $pf->parse($new_products); //rmh M-S_pricing
    $new_price = $pf->getPriceStringShort(); //rmh M-S_pricing
    $new_products['products_name'] = push_get_products_name($new_products['products_id']);
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . push_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a><br>' . $new_price); //rmh M-S_pricing

    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

  new contentBox($info_box_contents);
  } //rmh M-S_fixes
?>
<!-- new_products_eof //-->
