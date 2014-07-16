<?php
/**
 *
 * class product by BKrö / AL 2013
 *
 */
 
 class product{
 
 	const LIMITED_QUANTITY = 10;
 
 	var 	$attr, $has_ve,$ve_id,$ve_prefix,$ve_multiplier,$ve_difference,$products_model,$products_name,$products_description,$products_image,$manufacturers_name,
			$manufacturers_id,$products_availability_id,$tax_class_id,$products_id,$final_price,$price,$specials_new_products_price,$products_quantity,$products_date_available,
			$products_date_expire,$products_weight,$include_in_discount,$products_ordered,$products_short_description,$products_package_info,$products_usage_info,$products_pdf,
			$products_image_array,$special,$tax,$discountable;
 	
	function product()
	{
		$this->attr=array();
		$this->has_ve = false;
		$this->ve_prefix = '';
		$this->ve_multiplier='';
		$this->ve_difference = '';
		$this->sr_search=array('{','}');
		$this->sr_insert=array('0');
	}

	function reset()
	{
		$this->attr=array();
		$this->has_ve = false;
		unset($this->ve_id);
		$this->ve_prefix = '';
		$this->ve_multiplier='';
		$this->ve_difference = '';
		$this->ve_single_price = '';
		$this->ve_end_price = '';
		$this->ve_loaded = false;
		$this->ve_o_1='';
		$this->ve_o_2= '';
		$this->ve_left= 0;
		$this->ve_db_left= 0;
		$this->products_model = ''; 
		$this->products_name = ''; 
		$this->products_description = '';
		$this->products_image = '';
		$this->manufacturers_name = ''; 
		$this->manufacturers_id = ''; 
		$this->products_availability_id = ''; 
		$this->tax_class_id = '';  
		$this->products_id = ''; 
		$this->final_price = '';
		$this->price = '';
		$this->specials_new_products_price = '';
		$this->products_quantity = '';
		$this->products_date_available = '';
		$this->products_date_expire = '';
		$this->products_weight = '';
		$this->products_ordered = '';
		$this->products_short_description = '';
		$this->products_package_info = '';
		$this->products_usage_info = '';
		$this->products_pdf = '';
		$this->products_image_array = '';
		$this->special = false;
		$this->tax = 0;
		$this->include_discount = 1;
		$this->discountable = false;
	}

 	function load_product($id)
	{
		global $discountable_products;
		$this->reset();
		global $languages_id, $cart, $customer;	
		if(!isset($_SESSION['customer_id']))
		{
			$cid=0;
		}
		else
		{
			$cid = $_SESSION['customer_id'];
		}

		$this->loaded_id=$id;
		$id = push_get_prid($id);
		//echo $id . "" .$this->loaded_id;
		if($this->loaded_id <> $id)
		{
			$this->ve_loaded = true;
		}
		else
		{
			$this->ve_loaded = false;
		}
		//for shopping cart actions
		$this->intid = str_replace($this->sr_search, $this->sr_insert,$this->loaded_id);

		$special_without_duplicates = "
				SELECT 
					MAX(specials_new_products_price) 
					AS specials_new_products_price 
				FROM 
					specials 
				WHERE 
					products_id = p.products_id 
				AND 
					(expires_date > NOW() 
					OR 
					(expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL))
				AND 
					status = 1 
				AND
					customers_id='0' 
				GROUP BY 
					products_id";
		$specials_without_duplicates_exists = "
			SELECT 
				COUNT(status)
			FROM 
				specials 
			WHERE 
				products_id = p.products_id
			AND 
				(expires_date > NOW() 
				OR 
				(expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL))
			AND 
				customers_id='0' 
			AND
				status = 1 
			GROUP 
				BY products_id";

		$q= "SELECT 
				p.products_model, 
				pd.products_name, 
				pd.products_description,
				pd.products_short_description,
				pd.products_package_info,
				pd.products_usage_info,
				pd.products_pdf,				
				p.products_image,
				p.products_status,
				p.products_image_array, 
				p.manufacturers_id, 
				p.products_availability_id, 
				p.products_tax_class_id,  
				p.products_id,
				p.products_quantity,
				p.products_date_expire,
				p.products_date_available,
				p.products_weight,
				p.include_in_discount,
				p.products_ordered,
				p.products_drop_shipment,
				p.products_price,
				(SELECT 
					CASE WHEN 
						(" . $specials_without_duplicates_exists . ") > 0 
					THEN 
						(" . $special_without_duplicates . ") 
					ELSE NULL END) 
				as specials_new_products_price,
				(SELECT 
					CASE WHEN 
						(" . $specials_without_duplicates_exists . ") > 0 
					THEN 
						(" . $special_without_duplicates . ") 
					ELSE  p.products_price END) 
				as final_price 
			FROM 
				" . TABLE_PRODUCTS . " p JOIN
				" . TABLE_PRODUCTS_DESCRIPTION . " pd
				ON
				(pd.products_id = p.products_id) 
			WHERE
				p.products_status = '1' 
			AND 	
				pd.language_id = '" . (int)$languages_id . "' 
			AND
				p.products_id='" . $id . "'
			";
	//echo "<pre>".$q."</pre>";
		$r=push_db_fetch_array(push_db_query($q));
		if($r)
		{
			$this->product_exists=true;
		}
		else
		{
			$this->product_exists=false;
			return false;
		}
	//load products properties
		$this->products_model = $r['products_model']; 
		$this->products_name = $r['products_name']; 
		$this->products_description = $r['products_description'];
		$this->products_image = (push_not_null($r['products_image']))? $r['products_image'] : '';
		$this->products_status = $r['products_status'];
		if(push_not_null($r['manufacturers_id']))
		{
			$mq=push_db_fetch_array(push_db_query("SELECT manufacturers_name FROM manufacturers WHERE manufacturers_id='" .  $r['manufacturers_id'] . "'"));
			$this->manufacturers_name = $mq['manufacturers_name']; 
			$this->manufacturers_id = $r['manufacturers_id']; 
		}
		else
		{
			$this->manufacturers_name = ''; 
			$this->manufacturers_id = ''; 
		}
		$this->products_availability_id = $r['products_availability_id']; 
		$this->tax_class_id = $r['products_tax_class_id'];  
		$this->tax = push_get_tax_rate($this->tax_class_id);
		$this->products_id = $r['products_id'];
		$this->final_price = $r['final_price'];
		$this->price = $r['products_price'];
		$this->specials_new_products_price = $r['specials_new_products_price'];
		if(push_not_null($this->specials_new_products_price ))
		{
			$this->special=true;
			//get special prices in separate query!
			$spq = push_db_query("SELECT  specials_new_products_price 
								FROM 
									specials 
								WHERE 
									products_id = '" . $this->products_id . "'
								AND 
									(expires_date > NOW() 
									OR 
									(expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL))
								AND 
									status = 1
								ORDER BY specials_new_products_price DESC 
									;");
			
			if($spp=push_db_fetch_array($spq))
			{
				$this->specials_new_products_price = $spp['specials_new_products_price'];
				$this->final_price = $this->specials_new_products_price;
				if($sppve=push_db_fetch_array($spq))
				{
					$this->specials_new_products_ve_price = $sppve['specials_new_products_price'];
				}
				else
				{
					$this->specials_new_products_ve_price = $this->specials_new_products_price;
				}
			}
		
		}
		$this->products_quantity = $r['products_quantity'];
		$this->products_date_available = $r['products_date_available'];
		$this->products_date_expire = $r['products_date_expire'];
		$this->products_weight = $r['products_weight'];
		$this->include_discount = $r['include_in_discount'];
		$this->products_ordered = $r['products_ordered'];
		$this->products_short_description = $r['products_short_description'];
		$this->products_package_info = $r['products_package_info'];
		$this->products_usage_info = $r['products_usage_info'];
		$this->products_pdf = $r['products_pdf'];
		$this->products_image_array = explode('|',$r['products_image_array']);
		//products_drop_shipment
		$this->products_drop_shipment = $r['products_drop_shipment'];
		if ( $cid > 0)
		{
			$customer_price_query_r =push_db_query("
				SELECT
					products_price
				FROM 
					" . TABLE_PRODUCTS_CUSTOMERS . "
				WHERE 
					products_id = '" . (int)$id. "' 
				AND 
					customers_id =  '" .  $cid. "'
				ORDER BY products_price DESC");
			$customer_price_query = push_db_fetch_array($customer_price_query_r);
			if($customer_price_query)
			{
				$this->special_customer_price= true;
				$this->final_price = $customer_price_query['products_price'];
				if($customer_price_query = push_db_fetch_array($customer_price_query_r))
				{
					$this->customer_ve = $customer_price_query['products_price'];
				}
			}
			else
			{
				$this->special_customer_price= false;
			}
			if($customer->customer_discount_combines_all == 0  || $customer->customer_discount_add_to_customers_product == 0)
			{
				//$this->include_discount = 0;
			}
		}
		
		$q=push_db_query("
			SELECT 
					pov.products_options_values_name, 
					pa.options_values_id,
					pa.options_id,
					pa.options_values_price, 
					pa.price_prefix, 
					pa.customers_id
			FROM 
					products_attributes pa, 
					products_options po, 
					products_options_values pov 
			WHERE 
				pa.products_id='" . $id . "'
			AND  
				pa.options_id = po.products_options_id
			AND 
				po.products_options_name = 'VE'
			AND
				pa.options_values_id = pov.products_options_values_id");

		while($r=push_db_fetch_array($q))
		{
			$this->has_ve = true;
			$this->ve_prefix = $r['price_prefix'];
			$this->ve_id = $id ."{".$r['options_id'] ."}". $r['options_values_id'];
			$this->ve_o_1=$r['options_id'];
			$this->ve_o_2= $r['options_values_id'];
			$this->ve_multiplier= (int)$r['products_options_values_name'];
			if($this->special_customer_price)
			{
				$this->ve_difference = $this->final_price - $this->customer_ve;
				$this->ve_old_difference = $r['options_values_price'];
			}
			elseif($this->special)
			{
				$this->ve_difference = $this->specials_new_products_price - $this->specials_new_products_ve_price;
				$this->ve_old_difference = $r['options_values_price'];
			}
			else
			{
				$this->ve_difference = $r['options_values_price'];
			}
			
			if($this->ve_prefix  == '+')
			{
				$this->ve_single_price = $this->final_price + $this->ve_difference;
			}
			else
			{
				$this->ve_single_price = $this->final_price - $this->ve_difference;
				$this->ve_old_single_price = $this->price - $this->ve_old_difference;
			}
			$this->ve_end_price = $this->ve_single_price * $this->ve_multiplier;
			$this->ve_old_end_price = $this->ve_old_single_price * $this->ve_multiplier;
			
			if($r['customers_id'] == $cid)
			{
				break;	
			}
		}


		$prefq="SELECT 
						pef.products_extra_fields_name, 
						p2pef.products_extra_fields_value 
				FROM 
						products_extra_fields pef 
					JOIN 
						products_to_products_extra_fields p2pef 
					ON 
						(pef.products_extra_fields_id=p2pef.products_extra_fields_id)
				WHERE 
						products_extra_fields_status = '1' 
					AND 
						products_id=" . $id . " 
				ORDER BY 
						pef.products_extra_fields_order;";
		
		$qr=push_db_query($prefq);
		
		while($t=push_db_fetch_array($qr))
		{
			$this->attr[$t['products_extra_fields_name']]=$t['products_extra_fields_value'];
		}

		$n=push_db_query("SELECT SQL_CACHE products_date_added FROM products WHERE products_id='" . $id . "' AND DATE_SUB(CURDATE(),INTERVAL  " . INTERVAL_NEW_PRODUCTS . "  DAY) <= products_date_added  ");
		if(push_db_fetch_array($n))
		{
			$this->attr['New']=true;
		}
		$this->products_id = $id;
		$this->id=$this->products_id;
		$cartquantity=0;
		$this->products_db_quantity = $this->products_quantity;
		if(is_object($cart))
		{
			if(isset($cart->contents[$this->products_id]['qty']))
			{
				$cartquantity += $cart->contents[$this->products_id]['qty'];
			}
			if( isset($this->ve_id) && isset($cart->contents[$this->ve_id]['qty']) )
			{
				$cartquantity += $cart->contents[$this->ve_id]['qty'] * $this->ve_multiplier; 
			}

		}
		if($this->products_drop_shipment == 1)
		{
			$this->display_quantity =99;
			$this->products_db_quantity = 99;
		}
		else
		{
			$this->display_quantity = ($this->products_quantity > 99) ? 99 : $this->products_quantity;
		}
		
		
		$this->products_quantity = $this->display_quantity - $cartquantity;
		if($this->has_ve){
			$this->ve_left = floor($this->products_quantity / $this->ve_multiplier);
			$this->ve_db_left = floor($this->products_db_quantity / $this->ve_multiplier);
		}
		
		$this->available = true;
		if ($this->products_quantity > product::LIMITED_QUANTITY || $this->products_drop_shipment == 1) {			
			$this->availability_txt = TEXT_PRODUCT_AVAILABLE;
		} else if ($this->products_quantity > 0) {
			$this->availability_txt = sprintf(TEXT_PRODUCT_LIMITED_QUANTITY, $this->products_quantity);
		} else {
			$this->available = false;
			$this->availability_txt = TEXT_PRODUCT_SOLD_OUT;
		}
		//quickfix to disable availability info below
		//remove following line to enable it
		if($cid==0)
		{
			$this->availability_txt ='';
		}
		if(is_array($discountable_products) && count($discountable_products)>1 && in_array($this->products_id,$discountable_products) )
		{
			//handle with that!
			$this->discountable = true;
		}
		//EOF quickfix
		
		/**
		 * Insert for treatment of more attributes if necessary in future
		 */
/*
// attributes price
			if (isset($this->contents[$products_id]['attributes'])) 
			{
				reset($this->contents[$products_id]['attributes']);
				while (list($option, $value) = each($this->contents[$products_id]['attributes'])) 
				{
					if($option=='multiplier')
					{
						$qty = $qty * $value;
					}
					else
					{
						//customer specific attribute price  
						$attribute_price_query = push_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "' AND customers_id = '" .  $_SESSION['customer_id'] . "' ");
						$attribute_price = push_db_fetch_array($attribute_price_query);
						if(!$attribute_price)
						{
							//no customer specific price!
							$attribute_price_query = push_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "' AND customers_id = '0' ");
							$attribute_price = push_db_fetch_array($attribute_price_query);
						}
						if ($attribute_price['price_prefix'] == '+')
						{
							$products_price =  $products_price +push_add_tax($attribute_price['options_values_price'], $products_tax);
						}
						else
						{
							$products_price =  $products_price - push_add_tax($attribute_price['options_values_price'], $products_tax);
						}
					}
				}
			}
/**/

	}

	function adjustQty($qty)
	{
		if ($qty < 1)
		{
			$qty = 1;
		}
		
		if($this->has_ve && !$this->ve_loaded)
		{
			if($qty >= $this->ve_multiplier)
			{
				$qty = $this->ve_multiplier -1;
			}
		}
		elseif($this->has_ve && $this->ve_loaded)
		{
			if($qty > $this->ve_left)
			{
				$qty = $this->ve_left;
			}
		}
/*	
		if($this->ve_loaded)
		{
			$qty = ($qty > $this->ve_db_left)? $this->ve_db_left : $qty;
			return intval($qty);
		}
		elseif($this->has_ve)
		{
			$wanted_qty = $qty;
			if ($wanted_qty > $this->ve_multiplier)
			{
				//$cut_qty = $this->ve_multiplier -1;
				$ve_correction = floor(($wanted_qty ) / $this->ve_multiplier);
				if($ve_correction > 0)
				{
					$cut_qty = ($wanted_qty ) % $this->ve_multiplier;
					
					if(isset($cart->contents[$this->ve_id]))
					{
						if ($this->ve_left >= ($cart->contents[$this->ve_id] + $ve_correction))
						{
							$cart->contents[$this->ve_id] = $cart->contents[$this->ve_id] + $ve_correction;
						}
						else
						{
							$
						
						if()
					}
				}
				else
				{
					$qty = $qty;
					
					
				}
			}
/*		else
			{
				if($left_qty >= $this->ve_multiplier)
				{
					$qty = $this->ve_multiplier - 1 ;
				}
				else
				{
					$qty = $left_qty;
				}
			}
		}
		else
		{
			$qty = ($qty > $this->products_db_quantity)? $this->products_db_quantity : $qty;
		}
		*/
		$qb=1; // quantity-blocks set to 1 by default!!
		if ($qb >= 1)
		{
			if ($qty < $qb)
				$qty = $qb;
	
			if (($qty % $qb) != 0)
				$qty += ($qb - ($qty % $qb));
		}
		return $qty;
	}
 
	function is_bio()
	{
		if(isset($this->attr['Bio']))
		{
			if($this->attr['Bio'] <> '' )
			{
				return true;
			}
		}
		return false;
 	}
	
	function get_bio_cert()
	{
		if($this->is_bio)
		{
			return $this->attr['Bio'];
		}
	}
	
	function is_new(){
		if(isset($this->attr['New']) ){
			return true;
		}else{
			return false;
		}
	}
 
 	function is_special(){
		return $this->special;
	}
	
	function discount()
	{
		if($this->include_discount == '1')
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	function model_exists($model)
	{
		$t=push_db_fetch_array(push_db_query("SELECT products_id FROM products WHERE products_model='" . $model . "' AND products_status = 1"));
		if($t)
		{
			return $t['products_id'];	
		}
		else
		{
			return false;
		}
	}

	function compute_price($qty)
	{
		$qty = $this->adjustQty($qty);
		$price = $this->final_price;
		
		//to be activated for use of quantity-prices
		/*
		for ($i=1; $i<=8; $i++)
		{
			if (($this->quantity[$i] > 0) && ($qty >= $this->quantity[$i]))
			{
				$price = $this->price[$i];
			}
		}*/
		return $price;
	}

	function get_image($place = "fullsize", $size = 800)
	{
		$place .= '/'; 
		$image = $this->products_image;
		if(($image <>'') && file_exists(DIR_WS_IMAGES . $place . $image))
		{
			return $place . $this->products_image;
		}
		
		if(($this->products_image =='') || (!file_exists(DIR_WS_IMAGES . 'fullsize/' . $image)))
		{
			$image =  get_categories_default_image($this->id);
		}
		
		if(!file_exists(DIR_WS_IMAGES . $place . $image)){
			if(!is_dir(DIR_WS_IMAGES . $place))
			{
				mkdir(DIR_WS_IMAGES . $place, 0777);
			}
			push_copy_image(DIR_WS_IMAGES  . 'fullsize/' . $image, DIR_WS_IMAGES  . $place . $image, $size, $size);
		}
		
		chmod(DIR_WS_IMAGES . $place, 0777);
		if($image<>'')
		{
			return $place . $image;
		}

	}
	
	
	
	function get_special_tag($fall='')
	{
		if($this->special)
		{
			echo "<span class='" . (($fall=='s')?'sortiment_':'') . "discount_offset'> -  " . floor(100 - ( floatval($this->specials_new_products_price) / floatval($this->price) * 100) ) . " %</span>";
		}
	
	}

	function get_ve_special_tag($fall='')
	{
		if($this->special)
		{
			echo "<span class='" . (($fall=='s')?'sortiment_':'') . "discount_offset'> -  " . floor(100 - ( floatval($this->ve_end_price) / floatval($this->ve_old_end_price) * 100) ) . " %</span>";
		}
	
	}
	
	function get_infographics($fall='')
	{
		global $customer;
		if($this->special)
		{	
			$tag = ' ';
			if($customer->login)
			{
				//echo "<!-- " . floatval($this->specials_new_products_price)  . "/ " . floatval($this->price) . " -->";
				$tag= ' - ' . floor(100 - (floatval($this->specials_new_products_price) / floatval($this->price) * 100) );
			}

			if(!($fall =='d' && $customer->login))
			{
				echo "<span class='discount_offset'>" . $tag . " %</span>";
			}
		}
		if($fall =='n' && $this->include_discount == 0)
		{
			//Auf Anfrage MP entfernt
			echo "<span class='discount_exclude'>&nbsp;<div class='discount_exclude_popup'>Auf dieses Produkt findet unsere Rabattstaffel leider keine Anwendung</div></span>";
		}
		if($fall <>'n')
		{
			$this->get_discount_tag();
		}
	}

	function get_discount_tag()
	{
		if($this->discountable)
		{
			echo "<div class='products_discount'>&nbsp;<div class='products_discount_popup tx_12_15'>Beim Kauf dieses Produktes gibt es einen <strong style=\"color:#ff00ff;font-size:12px;\">Bonus</strong>!<a href=\"" . push_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$this->products_id) . "\" class='tx_12_15 tx_blue'> Infos im Produktdetail.</a></div></div>";	
		}	
	}

	function get_notify_button()
	{
		global $customer;
		//put out code to trigger a notify-customer-script 	
		if($customer->login == true)
		{
			if ($this->is_notification_active())
			{
				//Kunde wird  Benachrichtigt
				echo '<span data-href="includes/modules/ajax/notification.php?action=remove&pid=' . $this->products_id . '&cid=' . $customer->customers_id . '" class="mail_notification removere"><span class="notification_remove">E-Mail Benachrichtigung deaktivieren?</span> Benachrichtigung aktiv </span>';
			}
			else
			{
				//Kunde kann Benachrichtigung wählen
				echo '<span data-href="includes/modules/ajax/notification.php?action=add&pid=' . $this->products_id . '&cid=' . $customer->customers_id . '" class="mail_notification tx_blue add"><span class="notification_add">Aktivieren, um einmalig eine <strong>E-Mail zu erhalten</strong>, wenn das Produkt wieder erhältlich ist.</span>Benachrichtige mich! </span>';
			}
		}
	}
	
	function is_notification_active() 
	{
		global $customer;
		
		if ($customer->login)
		{
			$teste = push_db_query("SELECT products_id FROM products_notifications WHERE products_id = '" . $this->products_id . "' AND customers_ids LIKE '%|" . $customer->customers_id . "|%'; ");
			return push_db_fetch_array($teste) ? true : false;
		}
		return false;
	}

	function image_present()
	{
		$place .= 'fullsize/'; 
		$image = $this->products_image;
		if(($image <>'') && file_exists(DIR_WS_IMAGES . $place . $image))
		{
			return true;
		}	
		else
		{
			return false;	
		}
	}

	function add_to_seen_products() {
		global $products_visited;
		
		if (!push_session_is_registered('products_visited')) {
			push_session_register('products_visited');
			$products_visited = array();
		}
		
		if ($key = array_search($this->products_id, $products_visited)) {
			unset($products_visited[$key]);
		}
		
		array_unshift($products_visited, $this->products_id);
	}

}

?>