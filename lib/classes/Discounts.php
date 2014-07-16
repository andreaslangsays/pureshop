<?php
/**
 * 05. 2013 added Support for multiple discounts/coupons
 * class for customer discount änd complete discount calculations
 * 2013 by BKrö / AL
 */
 
 class discounts{
	var	$discounts,
		$customer_table,
		$table_cost,
		$add_to_all,
		$add_to_customers_product,
		$add_to_overall_discount,
		$shipping_table,
		$shipping_cost,
		$free_shipping_amount,
		$actual_general_discount_step,
		$actual_general_discount_procent,
		$next_general_discount_step,
		$next_general_discount_procent,
		$actual_customer_discount_step,
		$actual_customer_discount_procent,
		$next_customer_discount_step,
		$next_customer_discount_procent,
		$next_spush_identifier,
		$next_difference_string,
		$next_spush_popup_header,
		$next_spush_popup_description,
		$discount_amount,
		$discount_procent,
		$no_discount_amount,
		$overall_amount,
		$need_more,
		$discount_reached,
		$customer_online_rabatt,
		$tempq,
		$free_product_ids,
		$product_discounts,
		$shipping_price		;
	
 	function discounts()
	{
		global $cart,$customer, $discountable_products, $free_ones;
		$discountable_products = array();
		$free_ones = array();
		$this->product_discounts = array();
		$this->debugger_on = false;
		$this->get_shipping_discount();
		#get all necessary values!
		$re=0;
		if(is_object($cart))
		{
			$cart->calculate();
			$this->discount_amount = $cart->discount_price;
			$this->no_discount_amount = $cart->no_discount_price;
			$this->overall_amount = $this->no_discount_amount + $this->discount_amount;
		}
		if(is_object($customer))
		{
			$this->customer_id = $customer->customers_id;
			if($customer->customer_discount_table > 0)
			{
				$this->custom_discount = true;
				$this->customer_has_rabatt = true;
			}
			else
			{
				$this->custom_discount = false;
				$this->customer_has_rabatt = false;
			}
		}
		
		//PUBLIC-DISCOUNTS:
		$pdq=push_db_query("SELECT * FROM discounts WHERE discount_active='1' ORDER BY discount_value DESC");
		while($d=push_db_fetch_array($pdq)){
			$this->debug("<br>".$d['ID']."..");
			$this->discounts[$d['ID']]['id']=$d['ID'];
			$tmp_discountable_products = array();
			$this->discounts[$d['ID']]['name']=$d['discount_name'];
			$this->discounts[$d['ID']]['group']=$d['discount_group'];
			$this->discounts[$d['ID']]['base']=$d['discount_base'];	
			$this->discounts[$d['ID']]['type']=$d['discount_type'];
			$this->discounts[$d['ID']]['value']=$d['discount_value'];
//			$this->discounts[$d['ID']]['name']=$d['discount_name'];
			$valid=true;
			$dcq=push_db_query("SELECT  * FROM discounts_conditions WHERE discount_id='" . $d['ID'] . "' AND condition_active='1'");
			while($c=push_db_fetch_array($dcq))
			{
				//here goes the mechanics!
				switch($c['condition_type'])
				{
					case 'customer':
						$valid = $this->evaluate($c['condition_operator'], $customer->$c['condition_key'], $c['condition_value'] );
						$this->debug("{" . $c['condition_key'] . $c['condition_operator'] . $c['condition_value'] .' :: ' . $customer->$c['condition_key']."}<br>");
					break;
					case 'cart':
						$valid = $this->evaluate($c['condition_operator'], $cart->$c['condition_key'], $c['condition_value']);
						$difference = abs($cart->$c['condition_key'] - $c['condition_value']);
						$this->debug("{" . $c['condition_key'] .$c['condition_operator'] . $c['condition_value'].' :: ' .$cart->$c['condition_key']."}<br>");
					break;
					case 'date':
						$valid = $this->evaluate($c['condition_operator'], time() , strtotime($c['condition_value']));
						$this->debug( "{" . time() . $c['condition_operator'] . strtotime($c['condition_value']) . "}<br>");
						$difference = date('d. m. Y',  strtotime($c['condition_value']));
					break;
// NEW CASES - soley for coupons!
					case 'coupon_code':
						//coupon VALUE MUST MATCH EXACT!!
						$cmq=push_db_query("SELECT * FROM 
								discounts_track 
								WHERE 
								track_redeemed = 0 
								AND discount_id='" . $d['ID'] . "' 
								AND coupon_code='" . $c['condition_value'] . "' 
								AND customer_id='" . $customer->customers_id . "'");
						if($cm=push_db_fetch_array($cmq))
						{
							$valid=true;
						}
						else
						{
							$valid=false;	
						}
					break;
					case 'coupon_general_uses':
						$cmq=push_db_query("SELECT count(ID) AS zahl FROM 
								discounts_track 
								WHERE 
								track_redeemed = 1 
								AND discount_id='" . $d['ID'] . "' 
								AND coupon_code='" . $c['condition_value'] . "'");
						if($cm=push_db_fetch_array($cmq))
						{
							$valid=$this->evaluate('<', $cm['zahl'], $c['condition_value']);
						}
					break;
					case 'coupon_user_uses': 
						$cmq=push_db_query("SELECT count(ID) AS zahl FROM 
								discounts_track 
								WHERE 
								track_redeemed = 1 
								AND discount_id='" . $d['ID'] . "' 
								AND coupon_code='" . $c['condition_value'] . "'
								AND customer_id='" . $customer->customers_id . "'");
						if($cm=push_db_fetch_array($cmq))
						{
							$valid=$this->evaluate('<', $cm['zahl'], $c['condition_value']);
						}					
					break;
					case 'products_in_cart':
						$valid=false;
						if($c['condition_value']<>'')
						{
							//extract all conditions from condition value
							foreach(preg_split("/((\r?\n)|(\r\n?))/", $c['condition_value']) as $line)
							{
								$arg=explode('|',$line);
								$pic_c[$arg[0]] = $arg[1];
							}
							//now get min and array of pids
							$min =(isset($pic_c['min']))?$pic_c['min']:1;
							$only_ve=(isset($pic_c['onlyve']))?$pic_c['onlyve']:false;//Var to keep a look if VE is in cart
							$pic_ids= explode(',', $pic_c['ids']);
							$inids=0;
							foreach($pic_ids as $pic_id)
							{
								$discountable_products[]=$pic_id;
								$tmp_discountable_products[]=$pic_id;
								if($cart->in_cart($pic_id,$only_ve))
								{
									$inids++;
								}
							}
							if($inids >= $min)
							{
								$valid=true;
							}
							else
							{
								$valid=false;
								if(isset($_SESSION['bonusoff']) && is_array($_SESSION['bonusoff']))
								{
									$bosa = array();
									foreach($_SESSION['bonusoff'] as $bos)
									{
										if($bos <> $d['discount_value'])
											$bosa[] = $bos; 
									}
									$_SESSION['bonusoff'] = $bosa;
								}
							}
							$discountable_products[]=$d['discount_value'];
							$free_ones[]=$d['discount_value'];
							//insert to disable discount
							if(isset($_SESSION['bonusoff']) && is_array($_SESSION['bonusoff']))
							{
								if(in_array($d['discount_value'],$_SESSION['bonusoff']))
								{
									$valid = false;	
								}
							}
							
							$this->product_discounts[$d['id']]['enabled'] = $tmp_discountable_products;
							$this->product_discounts[$d['id']]['product'] = $d['discount_value'];
							$this->product_discounts[$d['id']]['min'] = $min;
							$this->product_discounts[$d['id']]['valid'] = $valid;
							$this->product_discounts[$d['id']]['discount_id'] = $d['id'];
							$this->product_discounts[$d['id']]['description'] = $d['discount_description'];

							
						}
					break;
					case 'restrict_to_categories':
					//TODO look if we have products related to certain category there
					break;
					case 'restrict_to_customers':
					//TODO : 
					// create array from List and look if userID is in it
					break;
					default:
					break;
				}
				if(!$valid )
				{
					if($c['condition_failure_reason'] <> '')
					{
						if($d['discount_group']=='public' && $c['condition_type'] == 'cart')
						{
						$this->need_more =true;
							
						}
						$missing = $difference;
						$this->reason[$re]['text']=str_replace('%V%', $missing, $c['condition_failure_reason']);
						$this->reason[$re]['type'] = $c['condition_type'];
						$this->reason[$re]['value'] = $missing;
						$this->reason[$re]['name']=$d['discount_name'];
						$this->reason[$re]['ID']=$d['ID'];
						$this->reason[$re]['step']=$c['condition_value'];
						
						$re++;
					}
					break;
				}
			}
			$this->discounts[$d['ID']]['applies']=$valid;
		}
	}
//Discount berechnen
	function calculate(){
		global $cart, $customer;
	// apply discount
		if(is_object($cart))
		{
			$cart->calculate();
			$this->discount_amount = $cart->discount_price;
			$this->no_discount_amount = $cart->no_discount_price;
			$this->free_product_ids= array();
			$this->overall_amount = $this->no_discount_amount + $this->discount_amount;
			$this->actual_general_discount_step = array();
			$this->actual_general_discount_procent = array();
			$this->next_general_discount_step = array();
			$this->next_general_discount_procent = array();
			$this->actual_customer_discount_step = array();
			$this->actual_general_discount_procent = array();
			$this->next_customer_discount_step = array();
			$this->next_customer_discount_procent = array();
			$this->shipping_savings = 0;
			$this->general_savings = 0;
			$this->online_rabatt = 0;
			$this->online_rabatt_procent = 0;
			$this->discount_procent=0;
			$this->coupon['is']=false;
			$general_procent = 0;
			$customer_procent = 0;
			$this->shipping_cost =$this->shipping_price;
//shipping discount
			foreach($this->discounts as $entries)
			{
				if($entries['applies'])
				{
					switch($entries['base'])
					{
						case 'shipping_cost':
							$shipping = $this->shipping_price;
							$this->shipping_savings =  $shipping;
							$this->shipping_cost = $entries['value'];
							$this->savings[$entries['id']]['id'] = $entries['id'];
							$this->savings[$entries['id']]['value'] = 'free_shipping';
							
						break;	
						case 'discount_amount':
							$this->savings[$entries['id']]['id'] = $entries['id'];
							$this->savings[$entries['id']]['value'] = $this->reduction($entries['type'], $entries['value'] ,$this->discount_amount);
							$this->discount_amount = $this->discount_amount - $this->savings[$entries['id']]['value'];
							$this->overall_amount = $this->no_discount_amount + $this->discount_amount;
							if($entries['group'] == 'coupon' && $this->savings[$entries['id']]['value'] > 0)
							{
								if($entries['type'] =='%')
								{
									$add='%';
								}
								elseif($entries['type'] =='F')
								{
									$add = ' EUR';	
								}
								$this->coupon['is']=true;
								$this->coupon['value'] = $entries['value'] . $add;
								$this->coupon['name'] = $entries['name'];
								$this->coupon['reduction'] = $this->savings[$entries['id']]['value'];
							}
							//compatibility vars
							if($entries['group'] == 'public')
							{
								$this->actual_discount_procent = $entries['value'];
								$this->discount_procent = $entries['value'];
								$this->general_savings = $this->savings[$entries['id']]['value']	;
							}
						break;
						case 'overall_amount':
							$tmp_total =  $this->no_discount_amount + $this->discount_amount;
							$tmp_saving = $this->reduction($entries['type'], $entries['value'] ,$this->discount_amount);
							$this->discount_amount = $this->discount_amount - $tmp_saving;
							$tmp_saving2 = $this->reduction($entries['type'], $entries['value'] ,$this->no_discount_amount);
							$this->no_discount_amount = $this->no_discount_amount - $tmp_saving2;
							$this->savings[$entries['id']]['id'] = $entries['id'];
							$this->savings[$entries['id']]['value'] = $tmp_saving + $tmp_saving2;
							$this->overall_amount = $this->overall_amount - $this->savings[$entries['id']]['value'];
							if($entries['group'] == 'private')
							{
								
								$this->private_rabatt_amount = $tmp_total;
								$this->private_rabatt_saving = $tmp_saving + $tmp_saving2;
								$this->private_rabatt_procent = $entries['value'];
								
							}
							if($entries['group'] == 'coupon' && $this->savings[$entries['id']]['value'] > 0)
							{
								if($entries['type'] =='%')
								{
									$add='%';
								}
								elseif($entries['type'] =='F')
								{
									$add = ' EUR';	
								}
								$this->coupon['is']=true;
								$this->coupon['value'] = $entries['value'] . $add;//. " von " . $tmp_total ." EUR";
								$this->coupon['name'] = $entries['name'];
								$this->coupon['reduction'] = $this->savings[$entries['id']]['value'];
							}
							if($entries['id']==1)
							{
								$this->online_rabatt = $tmp_saving + $tmp_saving2;
								$this->online_rabatt_procent = $entries['value'];
							}
						break;
						case 'add_free_product':
							$this->savings[$entries['id']]['id'] = 'id_'.$entries['value'];
							$this->free_product_ids[]=$entries['value'];
						break;
						default:
						break;
					}
					
				}
				
			}
			$this->actual_discount_price = $this->overall_amount;// + $this->shipping_cost;
			$this->discount_amount = $cart->discount_price;
			$this->overall_amount= $cart->total;
		}
		else
		{
			return 0;
		}
	}
//
	function get_products_discounts_product($pid)
	{
		//Steps:
		//returns an array of products and free one
		foreach($this->product_discounts as $key => $bar)
		{
			if(in_array($pid , $bar['enabled']))
			{
				return $bar;	
			}
		}
		return false;

	}
//
	function insert_coupon($code)
	{
		global $cart, $customer;
		//THIS HAS TO BE DONE FIRST
		
		//Straight ahead:
		//Look for matching code
		//write discount_id and customer_id to discounts_track - that's all! 
		$lookout = push_db_query("SELECT * FROM discounts_conditions WHERE 
										condition_key='code'
										AND	condition_operator='='
										AND condition_value='" . $code . "'	
										AND condition_active=1;");
		
		if($c=push_db_fetch_array($lookout))
		{
			if(!$q=push_db_fetch_array(push_db_query("SELECT discount_id FROM discounts_track WHERE discount_id='" . $c['discount_id'] . "' AND customer_id='" . $_SESSION['customer_id'] . "' AND coupon_code='" . $code . "' AND track_redeemed = 0")))
			{
				push_db_query("INSERT INTO discounts_track SET
									discount_id='" . $c['discount_id'] . "',
									customer_id='" . $_SESSION['customer_id'] . "',
									coupon_code='" . $code . "',
									track_type='coupon'
									");
			}
		}
		
	}

/* COMPATIBILITY Layer	*/
	function get_discount_steps(){
		$this->calculate();
		return true;
	}
//
	function fix_and_redeem($orderid)
	{
		global $customer, $REMOTE_ADDR;

		foreach($this->discounts as $writer)
		{
			if($writer['applies'] && ($writer['group']<>'coupon'))
			{
				if($writer['type'] == 'product')
				{
					push_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity -1 where products_id = '" . $writer['value'] . "'");
				}
				push_db_query("INSERT INTO discounts_track SET
									track_redeemed = 1,
									track_order = '" . $orderid . "',
									discount_id = '" . $writer['id'] . "',
									customer_id = '" . $customer->customers_id . "',
									track_value = '" . $this->savings[$writer['id']]['value'] . "',
									track_ip ='" . $_SERVER["REMOTE_ADDR"] . "',
									track_type='" . $writer['group'] . "'");
			}
			elseif($writer['applies'] && ($writer['group']=='coupon'))
			{	
				$couponsq = push_db_query("SELECT ID, discount_id FROM discounts_track WHERE customer_id='" . $customer->customers_id . "' AND track_type='coupon' AND discount_id='" . $writer['id'] . "' AND track_redeemed = 0");
				if($q=push_db_fetch_array($couponsq))
				{
					push_db_query("UPDATE discounts_track SET
										track_redeemed = 1,
										track_order = '" . $orderid . "',
										track_value = '" . $this->savings[$writer['id']]['value'] . "',
										track_ip ='" . $_SERVER["REMOTE_ADDR"] . "'
									WHERE
										track_type='coupon'
									AND customer_id = '" .$customer->customers_id ."'
									AND ID ='" . $q['ID'] . "'
										");
				}
			}
		}
		
			
	}
//
	function reduction($type, $value, $source)
	{
		if($type == '%')
		{
			$tmp = $source / 100;
			return  (round($tmp * $value * 100)/100);	
		}
		elseif($type == 'F')
		{
			$tmp = $source - $value;
			if( $tmp < 0 )
			{
				return $value - $tmp;	
			}
			else
			{
				return $value;	
			}
		}
		elseif($type == "=")
		{
			return $source;	
		}
		
		return 0;
		
	}
//
	function evaluate($operator, $okey , $ovalue)
	{
		if($operator=='=')
		{
			if( $ovalue == $okey)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		elseif($operator=='>')
		{
			//echo "$okey > $ovalue ? <br>";
			if( $okey > $ovalue)
			{
				return true;
			}
			else
			{
				return false;
			}
			
		}
		elseif($operator=='<')
		{
			//echo "$okey < $ovalue<br>";
			if(  $okey < $ovalue)
			{
				return true;
			}
			else
			{
				return false;
			}
			
		}
		elseif($operator=='IN')
		{
			//echo "$okey IN $ovalue<br>";
			$arr=explode(',',$ovalue);
			if( in_array($okey,$arr) )
			{
				return true;
			}
			else
			{
				return false;
			}
			
		}
	}

	function get_shipping_discount()
	{

		if(isset($_SESSION['customer_id']) && ( (int)$_SESSION['customer_id'] > 0))
		{		
        //we have a customer:
			$q=push_db_fetch_array(push_db_query("SELECT  entry_country_id FROM customers c JOIN address_book ab ON (c.customers_shipping_address_id = ab.address_book_id) WHERE c.customers_id='" . $_SESSION['customer_id'] . "'"));
			$r=push_db_fetch_array(push_db_query("SELECT geo_zone_id FROM zones_to_geo_zones WHERE zone_country_id = '" . $q['entry_country_id'] . "'"));
			$this->shipping_table=false;
			$j=0;
			$ti=push_db_fetch_array(push_db_query("SELECT configuration_key FROM  `configuration` WHERE  `configuration_key` LIKE  'MODULE_SHIPPING_MZMT_GEOZONE_%_ID' AND `configuration_value`= '" .  $r['geo_zone_id'] . "'"));
			if($ti)
			{
				$cons=substr($ti['configuration_key'], 0 , -2).'TABLE_';
			}
			else
			{
				$cons='MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_';
			}

			while (!$this->shipping_table) {
				$con= $cons . $j;
				if (defined($con))
				{				
					$this->shipping_table = split("[:,]" , constant($con));
				}
				if(sizeof($this->shipping_table) < 2 )
				{
					$this->shipping_table= false;
				}
				else
				{
					break;
				}
				$j++;
				if($j>4)
				{
					break;
				}
			}

		}
		
      		for ($i = 0; $i < count($this->shipping_table); $i+=2)
			{
				if ($this->shipping_table[$i+1] > 0) //shipping cost
				{
			//		$this->shipping_savings = $this->shipping_cost;
					$this->shipping_price = $this->shipping_table[$i+1];
				}
				else
				{
					$this->free_shipping_amount= $this->shipping_table[$i];
				}
			}
	}
	
	function get_next_discount_string(){
		global $currencies;
		$low=1110;
		foreach($this->reason AS $grund){
			if($grund['type']=='cart' && $grund['value'] < $low)
			{
				$this->next_discount_step = $grund['step'];
				$this->next_discount_procent = $this->discounts[$grund['ID']]['value'];
				$ausgabe = $grund['text'];	
				//echo "!!!";
				$low = $grund['value'];
			}
		}
//		return $ausgabe;
		
		if($this->need_more)
		{
			if($this->overall_amount < $this->free_shipping_amount)
			{
				$this->next_difference_string = $currencies->format( ( $this->free_shipping_amount - $this->overall_amount) );
				
				$this->next_spush_identifier = '<span class="tx_pink tx_13_15 tx_underline">kostenfreier Versand</span>';
				
				$this->next_spush_popup_header = 'Bestellwert ab ' . $this->free_shipping_amount . ' EUR = <br /><b>versandkostenfrei</b>';
				$this->next_spush_popup_description = '<div>Wir schenken Ihnen ab ' . $this->free_shipping_amount . ' EUR Bestellwert den Versandkostenanteil in Höhe von 9,95 EUR</div><a class="tx_12_20 tx_blue" href="' . push_href_link(FILENAME_SHIPPING) . '">Versandkosten</a><br /><a class="tx_12_20 tx_blue" href="' . push_href_link(FILENAME_RABATTE) . '">Rabattstaffel</a>';
			}
			elseif( (!$this->custom_discount)  && $this->next_discount_step)
			{
				$this->next_difference_string = $currencies->format( $low );
				$this->next_spush_identifier = '<span class="tx_pink tx_13_15 tx_underline"> Rabattstufe ' . $currencies->format($this->next_discount_step,0) ."</span> ";
				$this->next_spush_popup_header = 'Nächste Rabattstufe: <br />ab ' . $currencies->format($this->next_discount_step, 0) . ' = <b>' . $this->next_discount_procent . '% Rabatt</b>';

				$this->next_spush_popup_description = '<div>Ab einem Bestellwert von ' . $this->next_discount_step . ' EUR sparen Sie sich die Versandkosten und erhalten ' . $this->next_discount_procent . '% Rabatt auf den gesamten Bestellwert. </div><a class="tx_12_20 tx_blue" href="' . push_href_link(FILENAME_RABATTE) . '">Rabattstaffel</a>';
			}
			if(($this->overall_amount < $this->free_shipping_amount) || !$this->custom_discount )
			return '<span class="tx_pink tx_13_15">Noch ' . $this->next_difference_string . '</span> bis ' . $this->next_spush_identifier;
		}
		else
		{
			return "";
		}
	 }
	
	function debug($txt)
	{
		if($this->debugger_on)
		{
			echo $txt;
		}
	}
}
 ?>