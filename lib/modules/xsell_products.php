<?php
/*
$Id: xsell_products.php, v1  2002/09/11
// adapted for Separate Pricing Per Customer v4 2005/02/24

osCommerce, Open Source E-Commerce Solutions
<http://www.oscommerce.com>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_XSELL_PRODUCTS);
if ((USE_CACHE == 'true') && empty($SID)) {
	// include currencies class and create an instance
	require_once(DIR_WS_CLASSES . 'currencies.php');
	$currencies = new currencies();
}

if ($_GET['products_id']) {

	$xsell_query = push_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, specials_new_products_price
	from " . TABLE_PRODUCTS_XSELL . " xp left join " . TABLE_PRODUCTS . " p on xp.xsell_id = p.products_id
	left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "'
	left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id 
	where xp.products_id = '" . $_GET['products_id'] . "'
	and p.products_status = '1'
	order by sort_order asc limit " . MAX_DISPLAY_XSELL);

$num_products_xsell = push_db_num_rows($xsell_query);
if ($num_products_xsell > 0) {
?>
<!-- xsell_products //-->
<?php
     $info_box_contents = array();
     $info_box_contents[] = array('align' => 'left', 'text' => TEXT_XSELL_PRODUCTS);
     new contentBoxHeading($info_box_contents);

     $row = 0;
     $col = 0;
     $info_box_contents = array();
     while ($xsell = push_db_fetch_array($xsell_query)) {
			if (push_not_null($xsell['specials_new_products_price'])) {
				$xsell_price =  '<s>' . $currencies->display_price($xsell['products_price'], push_get_tax_rate($xsell['products_tax_class_id'])) . '</s><br>';
       	$xsell_price .= '<span class="productSpecialPrice">' . $currencies->display_price($xsell['specials_new_products_price'], push_get_tax_rate($xsell['products_tax_class_id'])) . '</span>';
			} else {
				$xsell_price =  $currencies->display_price($xsell['products_price'], push_get_tax_rate($xsell['products_tax_class_id']));
			}
       $text = '<a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . push_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] .'</a><br>' . $xsell_price. '<br><a href="' . push_href_link(FILENAME_PRODUCT_INFO, push_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $xsell['products_id'], 'NONSSL') . '">' . push_image_button('button_buy_now.gif', TEXT_BUY . $xsell['products_name'] . TEXT_NOW) .'</a>';
       $info_box_contents[$row][$col] = array('align' => 'center',
                                              'params' => 'class="smallText" width="33%" valign="top"',
                                              'text' => $text) ; 
	   $col ++;
       if ($col > 2) {
         $col = 0;
         $row ++;
       }
     }
new contentBox($info_box_contents);
?>
<!-- xsell_products_eof //-->
<?php
   }
 }
?>