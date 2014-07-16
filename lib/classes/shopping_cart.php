<?php
/*
  $Id: shöpping_cart.php,v 1.35 2003/06/25 21:14:33 hpdl Exp $
  adapted for Separate Pricing Per Customer v4.0 2005/01/27
  adapted for Separate Pricing Per Customer v4 and Price Break 1.11.3 2005/03/12
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class shoppingCart {
    var $contents, $total, $weight, $cartID, $content_type,$freegift; // added free gift line here

    function shoppingCart() {
      $this->reset();
    }

// Start Free Gift Functions
	function set_freegift($gift_id) {
		$this->freegift = $gift_id;
	}
	
	function get_freegift() {
		return $this->freegift;
	}
// End Free Gift Functions

	function restore_contents() 
	{
		//CCGV Replace Line
		global $gv_id, $REMOTE_ADDR;

		if (!push_session_is_registered('customer_id'))
		{ 
			return false;
		}
		// insert current cart contents in database
		if (is_array($this->contents))
		{
			reset($this->contents);
			while (list($products_id, ) = each($this->contents)) 
			{
				$qty = $this->contents[$products_id]['qty'];
				$include_discount = $this->contents[$products_id]['include_discount'];
				$product_query = push_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($products_id) . "'");
				if (!$product_query)
				{
					push_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, include_discount, customers_basket_date_added) values ('" . (int)$_SESSION['customer_id'] . "', '" . push_db_input($products_id) . "', '" . $qty . "', '" . $include_discount . "','" . date('Ymd') . "')");
					if (isset($this->contents[$products_id]['attributes']))
					{
						reset($this->contents[$products_id]['attributes']);
						while (list($option, $value) = each($this->contents[$products_id]['attributes'])) 
						{
							push_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$_SESSION['customer_id'] . "', '" . push_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "')");
						}
					}
				}
				else
				{
					push_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $qty . "', include_discount = '" . $include_discount . "'  where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($products_id) . "'");
				}
			}
			//ADDED FOR CCGV - START
			if (push_session_is_registered('gv_id')) {
			$gv_query = push_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $gv_id . "', '" . (int)$_SESSION['customer_id'] . "', now(),'" . $REMOTE_ADDR . "')");
			$gv_update = push_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $gv_id . "'");
			push_gv_account_update($_SESSION['customer_id'], $gv_id);
			push_session_unregister('gv_id');
			}
			//ADDED FOR CCGV - END
		}
		// reset per-session cart contents, but not the database contents
		$this->reset(false);
		$products_query = push_db_query("select products_id, customers_basket_quantity, include_discount from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
		while ($products = push_db_fetch_array($products_query))
		{
			$this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity']);
			// attributes
			$attributes_query = push_db_query("select products_options_id, products_options_value_id from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($products['products_id']) . "'");
			while ($attributes = push_db_fetch_array($attributes_query))
			{
				$this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
				$r=push_db_fetch_array(push_db_query("SELECT products_options_values_name FROM  products_options_values WHERE products_options_values_id = '" . $attributes['products_options_value_id'] ."'"));
				$this->contents[$products['products_id']]['attributes']['multiplier'] = (int)$r['products_options_values_name'];
			}
		}

		$this->cleanup();
	}

	function reset($reset_database = false) {

		$this->contents = array();
		$this->total = 0;
		$this->weight = 0;
		$this->content_type = false;

		if (push_session_is_registered('customer_id') && ($reset_database == true)) 
		{
			push_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
			push_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
		}

		unset($this->cartID);
		if (push_session_is_registered('cartID'))
		{
			push_session_unregister('cartID');
		}
	}

	function add_cart($products_id, $qty = '1', $attributes = '', $notify = true) {
		global $new_products_id_in_cart, $languages_id;
		$p = new product;
		$p->load_product($products_id);
		$products_id_string = push_get_uprid($products_id, $attributes);
		$products_id = push_get_prid($products_id_string);
		$attributes_pass_check = true;
	
		if (is_array($attributes)) 
		{
			reset($attributes);
			while (list($option, $value) = each($attributes))
			{
				if (!is_numeric($option) || !is_numeric($value))
				{
					$attributes_pass_check = false;
					break;
				}
			}
		}
	
		if (is_numeric($p->products_id) && is_numeric($qty) && ($attributes_pass_check == true))
		{
			if ($p->products_status == 1)
			{
				$new_products_id_in_cart = $products_id;
				push_session_register('new_products_id_in_cart');
				//used for notification 
				if ($this->in_cart($products_id_string))
				{
					$this->update_quantity($products_id_string, $qty, $attributes);
				}
				else
				{
					$this->contents[$products_id_string] = array('qty' => $qty);
					$this->contents[$products_id_string]['include_discount'] = $include_discount;
					// insert into database
					if (push_session_is_registered('customer_id')) push_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, include_discount, customers_basket_date_added) values ('" . (int)$_SESSION['customer_id'] . "', '" . push_db_input($products_id_string) . "', '" . (int)$qty . "', '" . $include_discount . "','" . date('Ymd') . "')");
					if (is_array($attributes))
					{
						reset($attributes);
						while (list($option, $value) = each($attributes))
						{
							$this->contents[$products_id_string]['attributes'][$option] = $value;
							// insert into database
							if (push_session_is_registered('customer_id'))
							{
								push_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$_SESSION['customer_id'] . "', '" . push_db_input($products_id_string) . "', '" . (int)$option . "', '" . (int)$value . "')");
							}
						}
					}
				}
				$this->cleanup();
				// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
				$this->cartID = $this->generate_cart_id();
			}
		}
	}

	function update_quantity($products_id, $quantity = '', $attributes = '') {

		$products_id_string = push_get_uprid($products_id, $attributes);
		$products_id = push_get_prid($products_id_string);
		$attributes_pass_check = true;

		if (is_array($attributes))
		{
			reset($attributes);
			while (list($option, $value) = each($attributes))
			{
				if (!is_numeric($option) || !is_numeric($value))
				{
					$attributes_pass_check = false;
					break;
				}
			}
		}
		
		if (is_numeric($products_id) && isset($this->contents[$products_id_string]) && is_numeric($quantity) && ($attributes_pass_check == true))
		{			
			// move updated product to the end of the contents array, so that it appears in cart_box as last added product 
			unset($this->contents[$products_id_string]);
			$this->contents[$products_id_string] = array('qty' =>  $quantity);
			
			if (push_session_is_registered('customer_id'))
			{
				push_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . (int)$quantity . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($products_id_string) . "'");
			}
			if (is_array($attributes))
			{
				reset($attributes);
				while (list($option, $value) = each($attributes))
				{
					$this->contents[$products_id_string]['attributes'][$option] = $value;
					// update database
					if (push_session_is_registered('customer_id'))
					{
						push_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($products_id_string) . "' and products_options_id = '" . (int)$option . "'");
					}
				}
			}
		}
	}

	function cleanup() {
		reset($this->contents);

		while (list($key,) = each($this->contents))
		{
			if ($this->contents[$key]['qty'] < 1)
			{
				unset($this->contents[$key]);
				if (push_session_is_registered('customer_id'))
				{
					push_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($key) . "'");
					push_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($key) . "'");
				}
			}
		}
	}

	function count_contents() {  // get total number of items in cart
		$total_items = 0;

		if (is_array($this->contents))
		{
			reset($this->contents);
			while (list($products_id, ) = each($this->contents))
			{
				$total_items += $this->get_quantity($products_id);
			}
		}
		return $total_items;
	}

	function get_quantity($products_id, $ve=false) {
		//Treat this by mixing with attributes!!
		$p = new product;
		$quantity=0;
		$p->load_product($products_id);
		$the_id = push_get_prid($products_id);
		if (isset($this->contents[$products_id]) && !$p->ve_loaded )
		{
			return $this->contents[$products_id]['qty'];
		}
		if($ve)
		{
			foreach($this->contents as $key => $value)
			{
				if( (substr($key, 0, strlen($the_id)) == $the_id) )
				{
					if(isset($value['attributes']['multiplier']))
					{
						$multi = $value['attributes']['multiplier'];
					}
					else
					{
						$multi = 1;
					}
					$quantity +=$value['qty'] * $multi;
				}
			}
		}
		return $quantity;
	}

	function in_cart($products_id) {
		if (isset($this->contents[$products_id]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function remove($products_id) {


		unset($this->contents[$products_id]);

		// remove from database
		if (push_session_is_registered('customer_id'))
		{
			push_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($products_id) . "'");
			push_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . push_db_input($products_id) . "'");
		}
// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
		$this->cartID = $this->generate_cart_id();
	}

	function remove_all() {
		$this->reset();
	}

    function get_product_id_list() {
		$product_id_list = '';

		if (is_array($this->contents))
		{
			reset($this->contents);
			while (list($products_id, ) = each($this->contents))
			{
				$product_id_list .= ', ' . $products_id;
			}
		}

		return substr($product_id_list, 2);
    }


    function get_product_id_count() {
		$product_id_count = 0;

		if (is_array($this->contents) && count($this->contents)>0)
		{
			reset($this->contents);
			while (list($products_id, ) = each($this->contents))
			{
				$product_id_count++;
			}
		}

		return $product_id_count;
    }

	function calculate() {

		$this->total_virtual = 0; // CCGV
		$this->total = 0;
		$this->weight = 0;
		$this->discount_price = 0;
		$this->no_discount_price = 0;
		if (!is_array($this->contents))
		{
		return 0;
		}
		$p = new product;
		reset($this->contents);
		while (list($products_id, ) = each($this->contents)) 
		{
			$qty = $this->contents[$products_id]['qty'];
			$p->load_product($products_id);
			$no_count = 1;
			if (ereg('^GIFT', $p->products_model ))
			{
				$no_count = 0;
			}
			//if $no_count 1 it means count it! (for rSPPC)
			// CCGV ADDED - END
			$prid = $p->products_id;
			$products_tax = push_get_tax_rate($p->tax_class_id);
			
			if($p->ve_loaded)
			{
				$products_weight = $p->products_weight * $p->ve_multiplier;
				$products_price = $p->ve_end_price;
			}
			else
			{
				$products_weight = $p->products_weight;
				$products_price = $p->compute_price($qty);
			}
			$this->total_virtual += push_add_tax($products_price, $products_tax) * $qty * $no_count;// CCGV
			$this->weight_virtual += ($qty * $products_weight) * $no_count;// CCGV
			$this->total += push_add_tax($products_price, $products_tax) * $qty;
			$this->weight += ($qty * $products_weight);
			if( ($p->discount()) && ($no_count == 1)){
				$this->discount_price += push_add_tax($products_price, $products_tax) * $qty;
			}
			elseif($no_count == 1)
			{
				$this->no_discount_price += push_add_tax($products_price, $products_tax) * $qty;
			}
		}
    }

	function get_products($sort_by_id = true) {
		global $languages_id;
		if (!is_array($this->contents))
		{
			return false;
		}
		$products_array = array();
		reset($this->contents);
		while (list($products_id, ) = each($this->contents))
		{
			//$p->load_product($products_id);
			//$products_price = $p->compute_price( $this->contents[$products_id]['qty']);
			$products_array[] = array(	'id' => $products_id,	'quantity' => $this->contents[$products_id]['qty']);
		}
 		if ($sort_by_id) {
			array_multisort($products_array);
		}
		return $products_array;
    }

	function show_total() {
		$this->calculate();
		return $this->total;
	}

	function show_weight() {
		$this->calculate();
		return $this->weight;
	}

	function generate_cart_id($length = 5) {
		return push_create_random_value($length, 'digits');
	}

	function get_content_type() {
		$this->content_type = false;

		if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) 
		{
			reset($this->contents);
			while (list($products_id, ) = each($this->contents))
			{
				if (isset($this->contents[$products_id]['attributes']))
				{
					reset($this->contents[$products_id]['attributes']);
					while (list(, $value) = each($this->contents[$products_id]['attributes']))
					{
						$virtual_check_query = push_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . (int)$products_id . "' and pa.options_values_id = '" . (int)$value . "' and pa.products_attributes_id = pad.products_attributes_id");
						$virtual_check = push_db_fetch_array($virtual_check_query);
						//CCGV Edited begin
						if ($virtual_check['total'] > 0)
						{
							switch ($this->content_type) 
							{

								case 'physical':
								$this->content_type = 'mixed';
								return $this->content_type;
								break;

								default:
								$this->content_type = 'virtual';
								break;
							}
						}
						else
						{
							if ($this->show_weight() == 0)
							{
								$wvirtual_check_query = push_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
								$wvirtual_check = push_db_fetch_array($wvirtual_check_query);

								if ($wvirtual_check['products_weight'] == 0) {
									switch ($this->content_type) 
									{
										case 'physical':
										$this->content_type = 'mixed';
										return $this->content_type;
										break;

										default:
										$this->content_type = 'virtual_weight';
										break;
									}
								}
								else 
								{
									switch ($this->content_type)
									{
										case 'virtual':
										$this->content_type = 'mixed';
										return $this->content_type;
										break;

										default:
										$this->content_type = 'physical';
										break;
									}
								}
							}
						}
					}

				}
				elseif ($this->show_weight() == 0) 
				{
					reset($this->contents);
					while (list($products_id, ) = each($this->contents))
					{
						$virtual_check_query = push_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
						$virtual_check = push_db_fetch_array($virtual_check_query);
						if ($virtual_check['products_weight'] == 0)
						{
							switch ($this->content_type)
							{
								case 'physical':
								$this->content_type = 'mixed';
								return $this->content_type;
								break;

								default:
								$this->content_type = 'virtual_weight';
								break;
							}
						}
						else
						{
							switch ($this->content_type)
							{
								case 'virtual':
								$this->content_type = 'mixed';
								return $this->content_type;
								break;

								default:
								$this->content_type = 'physical';
								break;
							}
						}
					}
					// CCGV ADDED - END
				}
				else
				{
					switch ($this->content_type)
					{
						case 'virtual':
						$this->content_type = 'mixed';
						return $this->content_type;
						break;

						default:
						$this->content_type = 'physical';
						break;
					}
				}
			}
		}
		else
		{
			$this->content_type = 'physical';
		}
		return $this->content_type;
	}

	function unserialize($broken) {
		for(reset($broken);$kv=each($broken);)
		{
			$key=$kv['key'];
			if (gettype($this->$key)!="user function")
			{
				$this->$key=$kv['value'];
			}
		}
	}

	function count_contents_virtual() {  // get total number of items in cart disregard gift vouchers
		$total_items = 0;

		if (is_array($this->contents))
		{
			reset($this->contents);
			while (list($products_id, ) = each($this->contents)) 
			{
				$no_count = false;
				$gv_query = push_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
				$gv_result = push_db_fetch_array($gv_query);
				if (ereg('^GIFT', $gv_result['products_model']))
				{
					$no_count=true;
				}
				if (NO_COUNT_ZERO_WEIGHT == 1)
				{
					$gv_query = push_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . push_get_prid($products_id) . "'");
					$gv_result=push_db_fetch_array($gv_query);
					if ($gv_result['products_weight']<=MINIMUM_WEIGHT)
					{
						$no_count=true;
					}
				}
				if (!$no_count)
				{ 
				$total_items += $this->get_quantity($products_id);
				}
			}
		}
		return $total_items;
	}

// Begin Change: Cart Cookie V1.3  
	function get_contents() 
	{
		return array();//return serialize($this->contents);
	}

	function set_contents($contents_string)
	{
		//$arrData = unserialize(stripslashes($contents_string));
		//$this->contents = $arrData;
	}
// End Change: Cart Cookie V1.3


}
?>
