<?php
/*
  $Id: price_schedule.php,v 1.0 2004/08/23 22:50:52 rmh Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/*
    price_schedule.php - module to support customer classes with quantity pricing
    Originally Created 2003, Beezle Software based on some code mods by WasaLab Oy (Thanks!)
    Modified by Ryan Hobbs (hobbzilla)
*/

class PriceFormatter {
  var $hiPrice;
  var $lowPrice;
  var $quantity;
  var $hasQuantityPrice;
  var $hasSpecialPrice;
  var $qtyPriceBreaks;

  function PriceFormatter($prices=NULL) {
    $this->productsID = -1;

    $this->hasQuantityPrice=false;
    $this->hasSpecialPrice=false;

    $this->hiPrice=-1;
    $this->lowPrice=-1;
    $this->thePrice = -1;
    $this->specialPrice = -1;
    $this->qtyBlocks = 1;
    $this->qtyPriceBreaks = 0;

    if($prices) {
      $this->parse($prices);
    }
  }

  function encode() {
    $str = $this->productsID . ":"
           . (($this->hasQuantityPrice == true) ? "1" : "0") . ":"
           . (($this->hasSpecialPrice == true) ? "1" : "0") . ":"
           . $this->quantity[1] . ":"
           . $this->quantity[2] . ":"
           . $this->quantity[3] . ":"
           . $this->quantity[4] . ":"
           . $this->price[1] . ":"
           . $this->price[2] . ":"
           . $this->price[3] . ":"
           . $this->price[4] . ":"
           . $this->thePrice . ":"
           . $this->specialPrice . ":"
           . $this->qtyBlocks . ":"
           . $this->taxClass;
    return $str;
  }

  function decode($str) {
    list($this->productsID,
         $this->hasQuantityPrice,
         $this->hasSpecialPrice,
         $this->quantity[1],
         $this->quantity[2],
         $this->quantity[3],
         $this->quantity[4],
         $this->price[1],
         $this->price[2],
         $this->price[3],
         $this->price[4],
         $this->thePrice,
         $this->specialPrice,
         $this->qtyBlocks,
         $this->taxClass) = explode(":", $str);

    $this->hasQuantityPrice = (($this->hasQuantityPrice == 1) ? true : false);
    $this->hasSpecialPrice = (($this->hasSpecialPrice == 1) ? true : false);
  }

  function parse($prices) {
    global $customer_group_id, $customer_group_type, $customer_group_discount;

    if (!push_not_null($customer_group_id)) $customer_group_id = VISITOR_PRICING_GROUP;

    $this->productsID = $prices['products_id'];
    $this->hasQuantityPrice=false;
    $this->hasSpecialPrice=false;

    if ($customer_group_type != '1') {
         $price_schedule_query = push_db_query("select products_groupush_price, products_groupush_price_qty FROM " . TABLE_PRODUCTS_PRICE_SCHEDULES . " WHERE products_id = '" . $prices['products_id'] . "' and customers_groupush_id = '" . (int)$customer_group_id . "' and stores_id = '" . STORES_ID . "'");
         $this->qtyPriceBreaks = push_db_num_rows($price_schedule_query);
         $this->thePrice = $prices['products_price'];
         $this->specialPrice = $prices['specials_new_products_price'];
    } else {
         $this->qtyPriceBreaks = 0;
         $customer_group_discount < 0 ? $this->thePrice = $prices['products_price'] * (100 - $customer_group_discount)/100 : $this->thePrice = $prices['products_price'];
         $customer_group_discount < 0 ? $this->specialPrice = $prices['specials_new_products_price'] : $this->specialPrice = $this->thePrice * (100 - $customer_group_discount)/100;
         if ($prices['specials_new_products_price'] < $this->specialPrice) $this->specialPrice = $prices['specials_new_products_price'];
    }

    $this->hiPrice = $this->thePrice;
    $this->lowPrice = $this->thePrice;
    $this->hasSpecialPrice = push_not_null($this->specialPrice);

    $this->qtyBlocks = $prices['products_qty_blocks'];
    $this->taxClass=$prices['products_tax_class_id'];
    $n = 0;

    if ($this->qtyPriceBreaks > 0 ) {
      while ($price_schedule = push_db_fetch_array($price_schedule_query)) {
        $this->price[$n]=$price_schedule['products_groupush_price'];
        $this->quantity[$n]=$price_schedule['products_groupush_price_qty'];
        if ($this->quantity[$n] == '1') {
          $this->thePrice = $this->price[$n];
          $this->hiPrice = $this->thePrice;
          $this->lowPrice = $this->thePrice;
        } else {
          $this->hasQuantityPrice = true;
        }
        $n += 1;
      }
    }

    for($i=0; $i<$this->qtyPriceBreaks; $i++) {
      if ($this->hasSpecialPrice == true) {
        $this->hiPrice = $this->specialPrice;
        if ($this->price[$i] > $this->specialPrice) {
          $this->price[$i] = $this->specialPrice;
        }
      }

      if ($this->hasQuantityPrice == true) {
        if ($this->price[$i] > $this->hiPrice) {
          $this->hiPrice = $this->price[$i];
        }
        if ($this->price[$i] < $this->lowPrice) {
          $this->lowPrice = $this->price[$i];
        }
      }
    }
  }

  function loadProduct($product_id, $language_id=1)
  {
    $sql="select pd.products_name, p.products_model, p.products_image, p.products_id, p.manufacturers_id, p.products_price, p.products_weight, p.products_qty_blocks, p.products_tax_class_id, p.distributors_id, IF(s.status = '1' AND s.stores_id = '" . STORES_ID . "', s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status = '1' AND s.stores_id = '" . STORES_ID . "', s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, ((" . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id and s.stores_id = '" . STORES_ID . "') INNER JOIN " . TABLE_PRODUCTS_TO_STORES . " p2s ON p.products_id = p2s.products_id where p2s.stores_id = '" . STORES_ID . "' AND p.products_status = '1' and p.products_id = '" . (int)$product_id . "' and pd.products_id = '" . (int)$product_id . "' and pd.language_id = '". (int)$language_id ."'";

    $product_info_query = push_db_query($sql);
    $product_info = push_db_fetch_array($product_info_query);
    $this->parse($product_info);

    return $product_info;
  }

  function computePrice($qty)  {

    $qty = $this->adjustQty($qty);
    $price = $this->thePrice;

    if ($this->hasSpecialPrice == true) {
        $price = $this->specialPrice;
    }

    if ($this->hasQuantityPrice == true) {
      for ($i=0; $i<$this->qtyPriceBreaks; $i++) {
        if (($this->quantity[$i] > 0) && ($qty >= $this->quantity[$i])) {
          $price = $this->price[$i];
        }
      }
    }
    return $price;
  }

// Force QTY_BLOCKS granularity
  function adjustQty($qty) {

    $qb = $this->getQtyBlocks();
    if ($qty < 1) {
      $qty = 1;
    }

    if ($qb >= 1) {
      if ($qty < $qb) {
        $qty = $qb;
      }

      if (($qty % $qb) != 0) {
        $qty += ($qb - ($qty % $qb));
      }
    }
    return $qty;
  }

  function getQtyBlocks() {
    return $this->qtyBlocks;
  }

  function getPrice() {
    return $this->thePrice;
  }

  function getLowPrice() {
    return $this->lowPrice;
  }

  function getHiPrice() {
    return $this->hiPrice;
  }

  function hasSpecialPrice() {
    return $this->hasSpecialPrice;
  }

  function hasQuantityPrice() {
    return $this->hasQuantityPrice;
  }

  function getPriceString($style='productPriceInBox') {
    global $currencies;

// If you want to change the format of the price/quantity table
// displayed on the product information page, here is where you do it.

    if (($this->hasSpecialPrice == true) && ($this->hasQuantityPrice == false)) {
      $lc_text = '&nbsp;<s>' . $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass)) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($this->specialPrice, push_get_tax_rate($this->taxClass)) . '</span>&nbsp;';
    }

    if($this->hasQuantityPrice == true) {
      $lc_text = '<table align="top" border="1" cellspacing="0" cellpadding="0"><tr><td align="center" class=' . $style. ' colspan="2">' . ($this->hasSpecialPrice == true ? '<s>' . $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass)) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($this->specialPrice, push_get_tax_rate($this->taxClass)) . '</span>&nbsp;' : $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass)) ) . '</td></tr>';

      for($i=0; $i<=$this->qtyPriceBreaks; $i++) {
        if(($this->quantity[$i] > 0) && ($this->price[$i] > $this->specialPrice)) {
          $lc_text .= '<tr><td class='.$style.'>' . $this->quantity[$i] . '+&nbsp;</td><td class='.$style.'>' . $currencies->display_price($this->price[$i], push_get_tax_rate($this->taxClass)) . '</td></tr>';
        }
      }
      $lc_text .= '</table>';
    } else {
      if ($this->hasSpecialPrice == true) {
        $lc_text = '&nbsp;<s>' . $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass)) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($this->specialPrice, push_get_tax_rate($this->taxClass)) . '</span>&nbsp;';
      } else {
        $lc_text = '&nbsp;' . $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass)) . '&nbsp;';
      }
    }
    if (VISITOR_PRICING_GROUP < '0' && !push_session_is_registered('customer_id')) {
      return '';
    } else {
      return $lc_text;
    }
  }

  function getPriceStringShort() {
    global $currencies;

    if (($this->hasSpecialPrice == true) && ($this->hasQuantityPrice == false)) {
      $lc_text = '&nbsp;<s>' . $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass)) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($this->specialPrice, push_get_tax_rate($this->taxClass)) . '</span>&nbsp;';
    } elseif ($this->hasQuantityPrice == true) {
      $lc_text = '&nbsp;' . $currencies->display_price($this->lowPrice, push_get_tax_rate($this->taxClass)) . ' - ' . $currencies->display_price($this->hiPrice, push_get_tax_rate($this->taxClass)) . '&nbsp;';
    } else {
      $lc_text = '&nbsp;' . $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass)) . '&nbsp;';
    }

    if (VISITOR_PRICING_GROUP < '0' && !push_session_is_registered('customer_id')) {
      return TEXT_LOGIN_TO_SEE_PRICES;
    } else {
      return $lc_text;
    }
  }
}
?>