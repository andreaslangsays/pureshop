<?php
/*
  $Idä: PriceFormatter.php,v 1.6 2003/06/25 08:29:26 petri Exp $
  adapted for Separate Pricing Per Customer v4 2005/03/20
  including an optimization to avoid double queries for the same info

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/*
    PriceFormatter.php - module to support quantity pricing

    Created 2003, Beezle Software based on some code mods by WasaLab Oy (Thanks!)
*/

class PriceFormatter {
  var $hiPrice;
  var $lowPrice;
  var $quantity;
  var $hasQuantityPrice;
  var $baseprice;
  var $include_discount;

  function PriceFormatter($prices=NULL) {
    $this->productsID = -1;

    $this->hasQuantityPrice=false;
    $this->hasSpecialPrice=false;

    $this->hiPrice=-1;
    $this->lowPrice=-1;

    for ($i=1; $i<=8; $i++){
      $this->quantity[$i] = -1;
      $this->prices[$i] = -1;
    }
    $this->thePrice = -1;
    $this->specialPrice = -1;
    $this->qtyBlocks = 1;

    if($prices)
      $this->parse($prices);
  }

  function encode() {
	$str = $this->productsID . ":"
	       . (($this->hasQuantityPrice == true) ? "1" : "0") . ":"
	       . (($this->hasSpecialPrice == true) ? "1" : "0") . ":"
	       . $this->quantity[1] . ":"
	       . $this->quantity[2] . ":"
	       . $this->quantity[3] . ":"
	       . $this->quantity[4] . ":"
		   . $this->quantity[5] . ":"
		   . $this->quantity[6] . ":"
		   . $this->quantity[7] . ":"
	       . $this->quantity[8] . ":"
	       . $this->price[1] . ":"
	       . $this->price[2] . ":"
	       . $this->price[3] . ":"
	       . $this->price[4] . ":"
		   . $this->price[5] . ":"
		   . $this->price[6] . ":"
		   . $this->price[7] . ":"
	       . $this->price[8] . ":"
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
	     $this->quantity[5],
	     $this->quantity[6],
	     $this->quantity[7],
	     $this->quantity[8],
	     $this->price[1],
	     $this->price[2],
	     $this->price[3],
	     $this->price[4],
	     $this->price[5],
	     $this->price[6],
	     $this->price[7],
	     $this->price[8],
	     $this->thePrice,
	     $this->specialPrice,
	     $this->qtyBlocks,
	     $this->taxClass) = explode(":", $str);

	$this->hasQuantityPrice = (($this->hasQuantityPrice == 1) ? true : false);
	$this->hasSpecialPrice = (($this->hasSpecialPrice == 1) ? true : false);
  }

  function parse($prices) {
    $this->productsID = $prices['products_id'];
    $this->hasQuantityPrice=false;
    $this->hasSpecialPrice=false;

    $this->quantity[1]=$prices['products_price1_qty'];
    $this->quantity[2]=$prices['products_price2_qty'];
    $this->quantity[3]=$prices['products_price3_qty'];
    $this->quantity[4]=$prices['products_price4_qty'];
    $this->quantity[5]=$prices['products_price5_qty'];
    $this->quantity[6]=$prices['products_price6_qty'];
    $this->quantity[7]=$prices['products_price7_qty'];
    $this->quantity[8]=$prices['products_price8_qty'];

    $this->thePrice=$prices['products_price'];
    $this->specialPrice=$prices['specials_new_products_price'];
    $this->hasSpecialPrice=push_not_null($this->specialPrice);

	$this->price[1]=$prices['products_price1'];
    $this->price[2]=$prices['products_price2'];
    $this->price[3]=$prices['products_price3'];
    $this->price[4]=$prices['products_price4'];
	$this->price[5]=$prices['products_price5'];
	$this->price[6]=$prices['products_price6'];
	$this->price[7]=$prices['products_price7'];
    $this->price[8]=$prices['products_price8'];

/*
	Change support special prices
	If any price level has a price greater than the special
	price lower it to the special price
*/
	if ($this->hasSpecialPrice == true) {
		for($i=1; $i<=8; $i++) {
			if ($this->price[$i] > $this->specialPrice)
				$this->price[$i] = $this->specialPrice;
		}
	}
	//end changes to support special prices

    $this->qtyBlocks=$prices['products_qty_blocks'];

    $this->taxClass=$prices['products_tax_class_id'];

    if ($this->quantity[1] > 0) {
      $this->hasQuantityPrice = true;
      $this->hiPrice = $this->thePrice;
      $this->lowPrice = $this->thePrice;

      for($i=1; $i<=8; $i++) {
	if($this->quantity[$i] > 0) {
	  if ($this->price[$i] > $this->hiPrice) {
	    $this->hiPrice = $this->price[$i];
	  }
	  if ($this->price[$i] < $this->lowPrice) {
	    $this->lowPrice = $this->price[$i];
	  }
	}
      }
    }
  }
//
	function discount(){
		return $this->include_discount;
	}  
// function loadProductSppc is Separate Pricing Per Customer only
	function loadProductSppc($product_id, $language_id=1, $product_info)
	{
		
		
		$customer_group_price_query = push_db_query("select products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_qty_blocks from " . TABLE_PRODUCTS_CUSTOMERS . " where products_id = '" . (int)$product_id. "' and customers_id =  '" . $_SESSION['customer_id'] . "'");
		
		if ($customer_group_price = push_db_fetch_array($customer_group_price_query))
		{
			$product_info['products_price']= $customer_group_price['products_price'];
			for ($i = 1 ; $i < 9 ; $i++)
			{
				$product_info['products_price'.$i.''] = $customer_group_price['products_price'.$i.''];
				$product_info['products_price'.$i.'_qty'] = $customer_group_price['products_price'.$i.'_qty'];
			} // end if ($customer_group_price = push_db_fetch_array($customer_group_price_query))
			$product_info['products_qty_blocks'] = $customer_group_price['products_qty_blocks'];
		}
		else
		{ // there is no price for the item in products_groups: retail price breaks need to nulled
			for ($i = 1 ; $i < 9 ; $i++)
			{
				$product_info['products_price'.$i.''] = '0.0000';
				$product_info['products_price'.$i.'_qty'] = '0';
			} // end if ($customer_group_price = push_db_fetch_array($customer_group_price_query))
			$product_info['products_qty_blocks'] = '1';
		}		
		// now get the specials price for this customer_group and add it to product_info array
		$specials_without_duplicates = "SELECT products_id, status, customers_id, MIN(specials_new_products_price) AS specials_new_products_price FROM specials WHERE specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) GROUP BY products_id";
		$special_price_query = push_db_query("select s.specials_new_products_price from (" . $specials_without_duplicates . ") AS s where s.products_id = " . (int)$product_id . " and s.status ='1' and ( s.customers_id = '" . $_SESSION['customer_id'] . "' or s.customers_id = '0') order by s.customers_id DESC");
		//$special_price_query = push_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = " . (int)$product_id . " and status ='1' and customers_group_id = '" . $customer_group_id . "'");
		if ($specials_price = push_db_fetch_array($special_price_query)) {
			$product_info['specials_new_products_price'] = $specials_price['specials_new_products_price'];
		}


		$this->parse($product_info);

		return $product_info;
	}

//MAIN FUNCTON
	function loadProduct($product_id, $language_id=1)
	{

		$sql =	"select pd.products_name, p.products_model, p.products_image, p.products_id," .
				" p.products_price, p.products_weight, p.include_in_discount, " .
				" p.products_price1,p.products_price2,p.products_price3,p.products_price4, p.products_price5,p.products_price6,p.products_price7,p.products_price8," .
				" p.products_price1_qty,p.products_price2_qty,p.products_price3_qty,p.products_price4_qty, p.products_price5_qty,p.products_price6_qty,p.products_price7_qty,p.products_price8_qty," .
				" p.products_qty_blocks," .
				" p.products_tax_class_id," .
				" NULL as specials_new_products_price" .
				" from " . TABLE_PRODUCTS_DESCRIPTION . " pd," .
				"      " . TABLE_PRODUCTS . " p" .
				" where p.products_status = '1'" .
				"   and p.products_id = '" . (int)$product_id . "'" .
				"   and pd.products_id = '" . (int)$product_id . "'" .
				"   and pd.language_id = '". (int)$language_id ."'";
		
		$product_info_query = push_db_query($sql);
		$product_info = push_db_fetch_array($product_info_query);
		$this->include_discount = $product_info['include_in_discount'];
		
		$customer_group_price_query = push_db_query("select products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_qty_blocks from " . TABLE_PRODUCTS_CUSTOMERS . " where products_id = '" . (int)$product_id. "' and customers_id =  '" . $_SESSION['customer_id'] . "'");
		
			if ($customer_group_price = push_db_fetch_array($customer_group_price_query))
			{
				if($_SESSION['customer_discount_combines_all'] || $_SESSION['customer_discount_add_to_customers_product'])
				{
					$this->include_discount = $product_info['include_in_discount'];
				}
				else
				{
					$this->include_discount = 0;
				}
	
				$product_info['products_price']= $customer_group_price['products_price'];
				for ($i = 1 ; $i < 9 ; $i++)
				{
					$product_info['products_price'.$i.''] = $customer_group_price['products_price'.$i.''];
					$product_info['products_price'.$i.'_qty'] = $customer_group_price['products_price'.$i.'_qty'];
				} // end if ($customer_group_price = push_db_fetch_array($customer_group_price_query))
				$product_info['products_qty_blocks'] = $customer_group_price['products_qty_blocks'];
			}
		else
			{ // there is no price for the item in products_groups: retail price breaks need to nulled
				/*for ($i = 1 ; $i < 9 ; $i++) {
					$product_info['products_price'.$i.''] = '0.0000';
					$product_info['products_price'.$i.'_qty'] = '0';
					
				} // end if ($customer_group_price = push_db_fetch_array($customer_group_price_query))
				$product_info['products_qty_blocks'] = '1';*/
			}
/**/
		
// now get the specials price for this customer_group and add it to product_info array
		$special_price_query = push_db_query("select s.specials_new_products_price, s.customers_id, s.status from specials s where s.specials_date_added <= NOW() AND (s.expires_date > NOW() OR (s.expires_date = '0000-00-00 00:00:00' OR s.expires_date <=> NULL)) and s.products_id = " . (int)$product_id . " and s.status ='1' and  (( s.customers_id = '" . $_SESSION['customer_id'] . "' and s.status ='1') or (s.customers_id = '0' and s.status ='1'))");
		while ($specials_price = push_db_fetch_array($special_price_query))
		{
			$product_info['specials_new_products_price'] = $specials_price['specials_new_products_price'];
			if($specials_price["customers_id"] == $_SESSION['customer_id'])
			{
				break;
			}
		}
		//var_dump($product_info);
		$this->parse($product_info);
		return $product_info;
}
//
  function computePrice($qty)
  {
	$qty = $this->adjustQty($qty);

	// Compute base price, taking into account the possibility of a special
	$price = ($this->hasSpecialPrice === true) ? $this->specialPrice : $this->thePrice;

	for ($i=1; $i<=8; $i++)
	{
		if (($this->quantity[$i] > 0) && ($qty >= $this->quantity[$i]))
		{
			$price = $this->price[$i];
		}
	}
	return $price;
  }

  function adjustQty($qty) {
	// Force QTY_BLOCKS granularity
	$qb = $this->getQtyBlocks();
	if ($qty < 1)
		$qty = 1;

	if ($qb >= 1)
	{
		if ($qty < $qb)
			$qty = $qb;

		if (($qty % $qb) != 0)
			$qty += ($qb - ($qty % $qb));
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

  function getPriceStringinfo($style='productPriceInBox') {
    global $currencies;

    if ($this->hasSpecialPrice == true) {
		$lc_text = '<table border="0" rules="rows" cellspacing="2" cellpadding="2" width="160">';
		$lc_text .= '<tr><td class="priceleft">' . PIECES . '</td><td class="priceright">';
		$lc_text .= '<s>'
		. $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass))
		. '</s><span class="leftpart">' . PIECES . ':</span><span class="productSpecialPrice">'
		. $currencies->display_price($this->specialPrice, push_get_tax_rate($this->taxClass))
		. '</span>&nbsp;'
		.'</td></tr>';
    }
    else
    {
		$lc_text = '<table border="1" rules="rows" cellspacing="0" cellpadding="0">';
		$lc_text .= '<tr><td class="priceleft">' . PIECES . '</td><td class="priceright" id="cp' . $this->productsID . '">'
		. $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass))
		. '</td></tr>';
    }
    //BOF baseprice-Insert
	$lc_text .='<tr><td colspan="2" class="baseprice" id="baseprice' . $this->productsID . '">' . $this->baseprice . '</td></tr>';
 
	if(($this->hasQuantityPrice == true)&& ($this->hasSpecialPrice <> true)) {
		for($i=1; $i<=8; $i++) {
			if($this->quantity[$i] > 0) {
				$lc_text .= '<tr><td class="priceleft">ab&nbsp;'
				. $this->quantity[$i]
				.'&nbsp;St</td><td class="priceright"> '
				. $currencies->display_price($this->price[$i],
				push_get_tax_rate($this->taxClass))
				.'</td></tr>';
			}
		}

		$lc_text .= '</table>';

      }
      else {
		if ($this->hasSpecialPrice == true) {
			$lc_text =  '<span class="leftpart">' . PIECES . ':</span><span class="productSpecialPrice">'
			  . $currencies->display_price($this->specialPrice, push_get_tax_rate($this->taxClass))
			  .'</span><br>'
			  //. '</span><span class="clearfix"></span>'
			  . '<span class="leftpart">' . INSTEADOF_TXT . ':</span><span class="rightpart">'
			  . $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass))
			  . '</span>';
			  $lc_text .='<div class="baseprice" id="baseprice' . $this->productsID . '" style="width:160px;text-align:right;">' . $this->baseprice . '</div>';
		}
		else {
			$lc_text = $lc_text = '<table border="1" rules="rows" cellspacing="0" cellpadding="0">';
			$lc_text .= '<tr><td class="priceleft">' . PIECES . '</td><td class="priceright"  id="cp' . $this->productsID . '">'
		    	 . $currencies->display_price($this->thePrice,
				       push_get_tax_rate($this->taxClass))
	  		. '</td></tr>';
			$lc_text .='<tr><td colspan="2" class="baseprice" id="baseprice' . $this->productsID . '">' . $this->baseprice . '</td></tr></table>';

		}
      	}

    return $lc_text;

  }

  function getPriceString($style='productPriceInBox') {
    global $currencies;

    if ($this->hasSpecialPrice == true) {
    	$lc_text = '<table border="1" cellspacing="0" cellpadding="0">';
        $lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">';
	      $lc_text .= '<s>'
		. $currencies->display_price($this->thePrice,
				     push_get_tax_rate($this->taxClass))
		. '</s>' . PIECES . '<span class="productSpecialPrice">'
		. $currencies->display_price($this->specialPrice,
				     push_get_tax_rate($this->taxClass))
		. '</span>'
		.'</td></tr>';
    }
    else
    {
		$lc_text = '<table border="1" cellspacing="0" cellpadding="0">';
		$lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">'
		. $currencies->display_price($this->thePrice,
		push_get_tax_rate($this->taxClass))
		. '</td></tr>';
    }

 if($this->hasQuantityPrice == true) {
		for($i=1; $i<=8; $i++) {
			if($this->quantity[$i] > 0) {
				$lc_text .= '<tr><td class='.$style.'>ab&nbsp;'
				. $this->quantity[$i]
				. PIECES . '</td><td class='.$style.'>'
				. $currencies->display_price($this->price[$i],
				push_get_tax_rate($this->taxClass))
				.'</td></tr>';
			}
		}

		$lc_text .= '</table>';

      }
      else {
		if ($this->hasSpecialPrice == true) {
			$lc_text = '<s>'
			  . $currencies->display_price($this->thePrice, push_get_tax_rate($this->taxClass))
			  . '</s>' . PIECES . '
			  <span class="productSpecialPrice">'
			  . $currencies->display_price($this->specialPrice, push_get_tax_rate($this->taxClass))
			  . '</span>';
		}
		else {
			$lc_text = '&nbsp;'
	  		. $currencies->display_price($this->thePrice,
				       push_get_tax_rate($this->taxClass))
	  		. '&nbsp;';
		}
      	}

    return $lc_text;

  }

  function getPriceStringShort() {
    global $currencies;

    if ($this->hasSpecialPrice == true) {
      $lc_text = '<s>'
	. $currencies->display_price($this->thePrice,
				     push_get_tax_rate($this->taxClass))
	. '</s>' . PIECES . '<span class="productSpecialPrice">'
	. $currencies->display_price($this->specialPrice,
				     push_get_tax_rate($this->taxClass))
	. '</span>';
    }
    else {
      if($this->hasQuantityPrice == true) {
	$lc_text = '&nbsp;'
	  . $currencies->display_price($this->lowPrice,
				       push_get_tax_rate($this->taxClass))
	  . ' - '
	  . $currencies->display_price($this->hiPrice,
				       push_get_tax_rate($this->taxClass))
	  . '&nbsp;';
      }
      else {
	$lc_text = '&nbsp;'
	  . $currencies->display_price($this->thePrice,
				       push_get_tax_rate($this->taxClass))
	  . '&nbsp;';
      }
    }
    return $lc_text;
  }

    function getPriceArray() {
    global $currencies;

    if ($this->hasSpecialPrice == true) {
    	$lc_text[1]=push_add_tax($this->specialPrice,
				     push_get_tax_rate($this->taxClass));
    }
    else
    {
		$lc_text[1]=push_add_tax($this->thePrice,
		push_get_tax_rate($this->taxClass));
    }

 if($this->hasQuantityPrice == true) {
		for($i=1; $i<=8; $i++) {
			if($this->quantity[$i] > 0) {
				$lc_text[$this->quantity[$i]]=push_add_tax($this->price[$i],
				push_get_tax_rate($this->taxClass));
			}
		}

      }
      else {
		if ($this->hasSpecialPrice == true) {
			$lc_text[1]= push_add_tax($this->specialPrice, push_get_tax_rate($this->taxClass));
		}
		else {
			$lc_text[1]= push_add_tax($this->thePrice,
				       push_get_tax_rate($this->taxClass));
		}
      	}

    return $lc_text;

  }

  

}

?>
