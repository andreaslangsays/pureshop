<?php
/*
  $Id: shöpping_cart.php,v 1.18 2003/02/10 22:31:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	// BEGIN CUSTOM MODIFICATION - show cart only if not empty (to retrieve some space)
	if ($cart->count_contents() > 0)
	{
?>
<!-- shopping_cart //-->
          <tr>
            <td>
<?php
	$info_box_contents = array();
	$info_box_contents[] = array('text' => BOX_HEADING_SHOPPING_CART);

	new infoBoxHeading($info_box_contents, false, false, push_href_link(FILENAME_SHOPPING_CART));
  
    $scart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $scart_contents_string .= '<tr><td align="right" valign="top" class="infoBoxContents">';

      if ((push_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $scart_contents_string .= '<span class="newItemInCart">';
      } else {
        $scart_contents_string .= '<span class="infoBoxContents">';
      }

      $scart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span></td><td valign="top" class="infoBoxContents"><a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';

      if ((push_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $scart_contents_string .= '<span class="newItemInCart">';
      } else {
        $scart_contents_string .= '<span class="infoBoxContents">';
      }

      $scart_contents_string .= $products[$i]['name'] . '</span></a></td></tr>';

      if ((push_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        push_session_unregister('new_products_id_in_cart');
      }
    }
    $scart_contents_string .= '</table>';

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $scart_contents_string);

  if ($cart->count_contents() > 0) {
    $info_box_contents[] = array('text' => push_draw_separator());
    $info_box_contents[] = array('align' => 'right',
                                 'text' => $currencies->format($cart->show_total()));
  }

//BOF CCGV
  if (push_session_is_registered('customer_id')) {
    $gv_query = push_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $_SESSION['customer_id'] . "'");
    $gv_result = push_db_fetch_array($gv_query);
    if ($gv_result['amount'] > 0 ) {
      $info_box_contents[] = array('align' => 'left','text' => push_draw_separator());
      $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_BALANCE . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($gv_result['amount']) . '</td></tr></table>');
      $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext"><a href="'. push_href_link(FILENAME_GV_SEND) . '">' . BOX_SEND_TO_FRIEND . '</a></td></tr></table>');
    }
  }
  if (push_session_is_registered('gv_id')) {
    $gv_query = push_db_query("select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $gv_id . "'");
    $coupon = push_db_fetch_array($gv_query);
    $info_box_contents[] = array('align' => 'left','text' => push_draw_separator());
    $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_REDEEMED . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($coupon['coupon_amount']) . '</td></tr></table>');

  }
//EOF CCGV

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- shopping_cart_eof //-->

<?php
	}
?>