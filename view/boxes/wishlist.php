<?php
/*
  $Idä: wishlist.php,v 3.0  2005/04/20 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

/*******************************************************************
****** QUERY THE DATABASE FOR THE CUSTOMERS WISHLIST PRODUCTS ******
*******************************************************************/
  require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST);


?>
<!-- wishlist //-->
          <tr>
            <td>

<?php
	unset($wishList->wishID[0]);
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_HEADING_CUSTOMER_WISHLIST
                                );
    new infoBoxHeading($info_box_contents, false, false, push_href_link(FILENAME_WISHLIST, '','NONSSL'));

    $info_box_contents = array();

	if (is_array($wishList->wishID) && !empty($wishList->wishID)) {
	reset($wishList->wishID);

	if (count($wishList->wishID) < MAX_DISPLAY_WISHLIST_BOX) {

		$wishlist_box = '<table>';
		$counter = 1;

/*******************************************************************
*** LOOP THROUGH EACH PRODUCT ID TO DISPLAY IN THE WISHLIST BOX ****
*******************************************************************/

	    while (list($_SESSION['wishlist_id'], ) = each($wishList->wishID)) {
		$_SESSION['wishlist_id'] = push_get_prid($_SESSION['wishlist_id']);
		
    	$p->load_product($_SESSION['wishlist_id']);

		$wishlist_box .= '<tr><td class="infoBoxContents" valign="top">0' . $counter . '.</td>';
		$wishlist_box .= '<td class="infoBoxContents"><a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p->products_id, 'NONSSL') . '"><img src="' . DIR_WS_IMAGES . $p->get_image('sortiment',30) . '" alt ="' . $p->products_name . '" /></a></td></tr>';
		
		$counter++;
		}

	$wishlist_box .= '</table>';

	} else {

	$wishlist_box = '<div class="infoBoxContents">' . sprintf(TEXT_WISHLIST_COUNT, count($wishList->wishID)) . '</div>';

	}

  } else {

	$wishlist_box = '<div class="infoBoxContents">' . BOX_WISHLIST_EMPTY . '</div>';

  }

    $info_box_contents[] = array('align' => 'left',
                                 'text'  => $wishlist_box);

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- wishlist_eof //-->