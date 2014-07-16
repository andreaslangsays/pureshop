<?php
/*
  $Id: reviews.php,v 1.37 2003/06/09 22:20:28 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reviews //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_REVIEWS);

  new infoBoxHeading($info_box_contents, false, false, push_href_link(FILENAME_REVIEWS));

//rmh M-S_multi-stores edited next line
  $random_select = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_STORES . " p2s where p.products_status = '1' and p.products_id = r.products_id and p.products_id = p2s.products_id and p2s.stores_id = '" . STORES_ID . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'";
  if (isset($HTTP_GET_VARS['products_id'])) {
    $random_select .= " and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'";
  }
  $random_select .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
  $random_product = push_random_select($random_select);
  $random = push_db_query($random_select); //rmh M-S_multi-stores
  $num_rows = push_db_num_rows($random); //rmh M-S_multi-stores
  $info_box_contents = array();

  if ($random_product) {
// display random review box
    $rand_review_query = push_db_query("select substring(reviews_text, 1, 60) as reviews_text from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$random_product['reviews_id'] . "' and languages_id = '" . (int)$languages_id . "'");
    $rand_review = push_db_fetch_array($rand_review_query);

    $rand_review_text = push_break_string(push_output_string_protected($rand_review['reviews_text']), 15, '-<br>');

    $info_box_contents[] = array('text' => '<div align="center"><a href="' . push_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . push_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><a href="' . push_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . $rand_review_text . ' ..</a><br><div align="center">' . push_image(DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product['reviews_rating'])) . '</div>');
  } elseif (isset($HTTP_GET_VARS['products_id']) && ($num_rows > 0)) { //rmh M-S_multi-stores
// display 'write a review' box
    $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a href="' . push_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . push_image(DIR_WS_IMAGES . 'box_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a></td><td class="infoBoxContents"><a href="' . push_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a></td></tr></table>');
  } else {
// display 'no reviews' box
    $info_box_contents[] = array('text' => BOX_REVIEWS_NO_REVIEWS);
  }

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- reviews_eof //-->