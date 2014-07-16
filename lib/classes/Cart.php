<?php
/*
  $Id: shöpping_cart.php,v 1.35 2003/06/25 21:14:33 hpdl Exp $
  adapted for Separate Pricing Per Customer v4.0 2005/01/27
  adapted for Separate Pricing Per Customer v4 and Price Break 1.11.3 2005/03/12
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
	CHANGE Write Cart always to DB!! 2013
  Released under the GNU General Public License
*/

  class Cart {
    var $contents, $total, $weight, $cartID, $content_type,$freegift; // added free gift line here

    function Cart()
	{
		global $customer;
		$this->reset();
		if($customer->login)
		{
			if(!push_not_null($customer->customers_cart))
			{
				$this->cartID = $this->generate_cart_id();
				push_db_query("UPDATE customers SET customers_cart = '" . $this->cartID . "'WHERE customers_id='" . $customer->customers_id . "';");
			}
			else
			{
				$this->cartID = $customer->customers_cart;
			}
		}
		$this->restore_contents();
	}

// Start Free Gift Functions
	function set_freegift($gift_id)
	{
		$this->freegift = $gift_id;
	}
	
	function get_freegift()
	{
		return $this->freegift;
	}
// End Free Gift Functions

	function restore_contents() 
	{
		global $customer;
		$product = new product();
		if (!$customer->login)
		{ 
			return false;
		}
		$current_cart = push_db_query( "SELECT * 
										FROM  " . TABLE_CUSTOMERS_BASKET . " 
										WHERE customers_id='" . $customer->customers_id . "' 
										AND customers_cart_id='" . $this->cartID . "'
										ORDER BY products_id " );
										
		while($entry = push_db_fetch_array($current_cart))
		{
			
			$product->load_product($entry['products_id']);
			if(!$product->product_exists)
			{
				push_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " WHERE products_id = '" . $entry['products_id'] . "'");
			}
			else
			{
				$this->contents[$entry['products_id']]['products_id'] = $entry['products_id'];
				$this->contents[$entry['products_id']]['qty'] = $entry['customers_basket_quantity'];
				$this->contents[$entry['products_id']]['include_discount'] = $entry['include_discount'];
				$this->contents[$entry['products_id']]['drop_shipment'] = $product->products_drop_shipment;
				if(isset($rqty[$product->id]))//rqty array holds basket qty of db
				{
					$rqty[$product->id] += ($product->ve_loaded) ? $product->ve_multiplier * $entry['customers_basket_quantity'] : $entry['customers_basket_quantity'] ;
				}
				else
				{
					$rqty[$product->id] = ($product->ve_loaded) ? $product->ve_multiplier * $entry['customers_basket_quantity'] : $entry['customers_basket_quantity'] ;
				}
			}
		}

//COLLECT AND DIVIDE!!
		foreach($rqty as $key => $value)
		{
			
			//echo $key ."--->". $value. "<br>";
			$product->load_product($key);
			if($product->products_db_quantity < $value)
			{
				$value = $product->products_db_quantity;
			}
			//echo $key ."--->". $value. "<br>";
						
			//do something to cut the cart down!!!
			//VE BOF
			if($product->has_ve)
			{
				//tricky!!!
				$ve_count = floor($value / $product->ve_multiplier);
				$pt_count = $value - ($ve_count * $product->ve_multiplier);
				//echo $product->id ." $value St --> (VEs: $ve_count | PTs: $pt_count ) <br>";
				if($ve_count > 0)
				{
					if( isset($this->contents[$product->ve_id]) )
					{
						$this->contents[$product->ve_id]['qty'] = $ve_count;
					}
					else
					{
						$this->contents[$product->ve_id] = array(	'qty' => $ve_count,
																	'include_discount' =>$product->include_discount, 
																	'products_id' => $product->ve_id
																	);
					}
					
					$test = push_db_query("SELECT customers_basket_quantity 
											FROM " . TABLE_CUSTOMERS_BASKET . " 
											WHERE
												products_id='" . $product->ve_id . "'
											AND customers_cart_id ='" . $this->cartID . "'
											AND customers_id = '" . $customer->customers_id . "'");	
					if($t=push_db_fetch_array($test))
					{
						push_db_query("	UPDATE " . TABLE_CUSTOMERS_BASKET . " 
										SET customers_basket_quantity = '" . $ve_count . "'
										WHERE products_id='" . $product->ve_id . "'
										AND customers_cart_id ='" . $this->cartID . "'
										AND customers_id = '" . $customer->customers_id . "'");
					//					echo   $product->ve_id ." - UPDATE $q <br>";
					}
					else
					{
						push_db_query("	INSERT INTO " . TABLE_CUSTOMERS_BASKET . " 
										SET customers_basket_quantity = '" . $ve_count . "',
										products_id='" . $product->ve_id . "', 
										customers_cart_id ='" . $this->cartID . "',
										customers_id = '" . $customer->customers_id . "',
										customers_basket_date_added = '" . date('Ymd') . "'");
					//					echo   $product->ve_id ." - INSERT<br>";
					}
				}
				else
				{
						unset($this->contents[$product->ve_id]);
						push_db_query(" DELETE  FROM " . TABLE_CUSTOMERS_BASKET . " 
										WHERE products_id='" . $product->ve_id . "' 
										AND customers_cart_id ='" . $this->cartID . "'
										AND customers_id = '" . $customer->customers_id . "'
										LIMIT 1");
					
				}
				//VE EOF
				//PT BOF
				if($pt_count > 0)
				{
					if( isset($this->contents[$product->id]) )
					{
						$this->contents[$product->id]['qty'] = $pt_count;
					}
					else
					{
						$this->contents[$product->id] = array(	'qty' => $pt_count,
																'include_discount' =>$product->include_discount, 
																'products_id' => $product->id
																);
					}
					
					$test = push_db_query("SELECT customers_basket_quantity 
											FROM " . TABLE_CUSTOMERS_BASKET . " 
											WHERE products_id='" . $product->id . "'
											AND customers_cart_id ='" . $this->cartID . "'
											AND customers_id = '" . $customer->customers_id . "'");	
					if($t=push_db_fetch_array($test))
					{
						push_db_query("	UPDATE " . TABLE_CUSTOMERS_BASKET . " 
										SET customers_basket_quantity = '" . $pt_count . "'
										WHERE products_id='" . $product->id . "'
										AND customers_cart_id ='" . $this->cartID . "'
										AND customers_id = '" . $customer->customers_id . "'");
					}
					else
					{
						push_db_query("	INSERT INTO " . TABLE_CUSTOMERS_BASKET . " 
										SET customers_basket_quantity = '" . $pt_count . "',
										products_id='" . $product->id . "', 
										customers_cart_id ='" . $this->cartID . "',
										customers_id = '" . $customer->customers_id . "',
										customers_basket_date_added = '" . date('Ymd') . "'");
					}
				}
				else
				{
						unset($this->contents[$product->id]);
						push_db_query(" DELETE  FROM " . TABLE_CUSTOMERS_BASKET . " 
										WHERE products_id='" . $product->id . "' 
										AND customers_cart_id ='" . $this->cartID . "'
										AND customers_id = '" . $customer->customers_id . "'
										LIMIT 1");
					
				}//PT EOF
				
			}
			else
			{
				if($value > 0)
				{
					$this->contents[$key]['qty'] = $value;
					//update db_entry?
					push_db_query("UPDATE  " . TABLE_CUSTOMERS_BASKET . " 
									SET customers_basket_quantity ='" . $value . "' 
									WHERE products_id='" . $key . "'
									AND customers_cart_id ='" . $this->cartID . "'
									AND customers_id = '" . $customer->customers_id . "'");
				}
				else
				{
					//echo $key ." anzahl $value oder auch " .$this->contents[$key]['qty'] . " wird del.";
					unset($this->contents[$key]);
					push_db_query("	DELETE FROM " . TABLE_CUSTOMERS_BASKET . " 
									WHERE products_id='" . $key . "'
									AND customers_cart_id ='" . $this->cartID . "'
									AND customers_id = '" . $customer->customers_id . "'");
					
				}
			}
		
		
		}
	}

	function reset($reset_database = false) {
		global $customer;

		$this->contents = array();
		$this->total = 0;
		$this->weight = 0;
		$this->content_type = false;

		if ($customer->login && ($reset_database == true)) 
		{
			push_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer->customers_id . "'");
			push_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer->customers_id . "'");
			push_db_query("UPDATE customers SET  customers_cart=NULL  WHERE customers_id = '" . (int)$customer->customers_id . "'");
		}
	}

	function check_attributes($attributes)
	{
		if (is_array($attributes)) 
		{
			reset($attributes);
			while (list($option, $value) = each($attributes))
			{
				if (!is_numeric($option) || !is_numeric($value))
				{
					return false;
				}
			}
		}
		return true;
	}

	function add_cart($products_id, $qty = '1', $attributes = '', $notify = true)
	{
		global $new_products_id_in_cart, $customer, $languages_id, $product;
		$product->load_product($products_id);
		$_SESSION['customer_id'] = $customer->customers_id;
		$products_id_string = push_get_uprid($products_id, $attributes);
		$products_id = push_get_prid($products_id_string);
		$qty = intval($qty);
		if (is_numeric($product->products_id) && is_numeric($qty) && ($this->check_attributes($attributes)))
		{
			if ($product->products_status == 1)
			{
				//used for notification 
				if ($this->in_cart($products_id_string))
				{
					$this->contents[$products_id_string]['qty'] += $qty;
					if ($customer->login)
					{
						push_db_query("	UPDATE " . TABLE_CUSTOMERS_BASKET . " 
										SET customers_basket_quantity = '" . (int)$this->contents[$products_id_string]['qty'] . "'
										WHERE
											customers_id = '" . (int)$_SESSION['customer_id'] . "'
										AND 
											products_id  = '" . push_db_input($products_id_string) . "'
										AND 
											customers_cart_id ='" . $this->cartID . "';");
					}
				}
				else
				{
					if($product->ve_loaded)
					{
						$new = ( !(isset( $this->contents[$product->id] ))) ? true:false;	
					}
					elseif($product->has_ve)
					{
						$new = (!(isset( $this->contents[$product->ve_id] ))) ? true:false;	
					}
					else
					{
						$new=true;	
					}
					if($new)
					{
						$new_products_id_in_cart = $products_id;
						push_session_register('new_products_id_in_cart');
					}

					$this->contents[$products_id_string] = array(	'qty' => $qty,
																	'include_discount' =>$product->include_discount, 
																	'products_id' => $products_id_string
																	);
					if ($customer->login)
					{
						push_db_query("	INSERT INTO " . TABLE_CUSTOMERS_BASKET . " 
										SET customers_id = '" . (int)$_SESSION['customer_id'] . "', 
											products_id  = '" . push_db_input($products_id_string) . "', 
											customers_basket_quantity = '" . (int)$qty . "', 
											include_discount = '" . $include_discount . "', 
											customers_cart_id ='" . $this->cartID . "',
											customers_basket_date_added = '" . date('Ymd') . "';");
					}

				if (is_array($attributes))
				{
						reset($attributes);
						while (list($option, $value) = each($attributes))
						{
							$this->contents[$products_id_string]['attributes'][$option] = $value;
							// insert into database
							if ($customer->login)
							{
								push_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$_SESSION['customer_id'] . "', '" . push_db_input($products_id_string) . "', '" . (int)$option . "', '" . (int)$value . "')");
							}
						}
					}
				}
				//$this->cleanup();
			}
		}
	}

	function update_cart($products_id, $quantity = '', $attributes = '') {
		global $customer;
		if($quantity == 0)
		{
			unset($this->contents[$products_id]);
			push_db_query("	DELETE FROM " . TABLE_CUSTOMERS_BASKET . " 
								WHERE products_id='" . $products_id . "'
								AND customers_cart_id ='" . $this->cartID . "'
								AND customers_id = '" . $customer->customers_id . "'");
		}
		else
		{
			$this->contents[$products_id]['qty'] =  $quantity;
			push_db_query("UPDATE  " . TABLE_CUSTOMERS_BASKET . " 
								SET customers_basket_quantity ='" . $quantity . "' 
								WHERE products_id='" . $products_id . "'
								AND customers_cart_id ='" . $this->cartID . "'
								AND customers_id = '" . $customer->customers_id . "'");
		}
	}

	function cleanup() {
		return false;
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

	function count_contents()
	{  // get total number of items in cart
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

	function in_cart($products_id,$ve=true) {
		if ($ve && isset($this->contents[$products_id]))
		{
			return true;
		}
		elseif(!$ve)//look if product is in cart without respect of VE
		{
			if(isset($this->contents[$products_id]))
			{
				return true;
			}
			else
			{
				$product = new product;
				$product->load_product($products_id);
				if(isset($this->contents[$product->ve_id]))
				{
					return true;	
				}
				return false;
			}
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
		//$this->cartID = $this->generate_cart_id();
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
		global $product;
		$product_id_count = 0;

		if (is_array($this->contents))
		{
			reset($this->contents);
			$vid=0;
			while (list($products_id, ) = each($this->contents))
			{
				$product->load_product($products_id);
				if($vid <> $product->id)
				{
				$product_id_count++;
				}
				$vid=$product->id;

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

	function get_products() {
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
		// array_multisort($products_array);
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
		return;// serialize($this->contents);
	}

	function set_contents($contents_string)
	{
		return;
		
		$arrData = unserialize(stripslashes($contents_string));
		$this->contents = $arrData;
	}
// End Change: Cart Cookie V1.3


}
?>
