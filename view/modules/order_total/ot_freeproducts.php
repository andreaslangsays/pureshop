<?php
/*
  $Id: ot_freeproducts.php,v 1.37.3 2004/01/01 12:52:59 Strider Exp $
  $Id: ot_freeproducts.php,v 1.4.2.12 2003/05/14 22:52:59 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class ot_freeproducts {
    var $title, $output,$products, $customer_codes;

    function ot_freeproducts() {
		global $order;
		$this->customer_codes = array();
		if(isset($_SESSION['customer_id']) && $_SESSION['customer_id'] > '')
		{
			$this->get_customers_fpc();
		}
		if(is_object($order))
		{
			foreach($order->products AS $prod)
			{
				if(!isset($this->products[(int)$prod['id']]) )
				{
					$this->products[(int)$prod['id']]['price'] = 0;
				}
				$this->products[(int)$prod['id']]['price'] += $prod['final_price'] * $prod['qty'];
				$this->products[(int)$prod['id']]['qty'] += $prod['qty'];
				
				
			}
		}
		$this->code = 'ot_freeproducts';
		$this->title = MODULE_ORDER_TOTAL_FREEPRODUCTS_TITLE;
		$this->header = MODULE_ORDER_TOTAL_FREEPRODUCTS_HEADER;
		$this->description = MODULE_ORDER_TOTAL_FREEPRODUCTS_DESCRIPTION;
		$this->user_prompt = MODULE_ORDER_TOTAL_FREEPRODUCTS_USER_PROMPT;
		$this->enabled = MODULE_ORDER_TOTAL_FREEPRODUCTS_STATUS;
		$this->sort_order = MODULE_ORDER_TOTAL_FREEPRODUCTS_SORT_ORDER;
		$this->include_shipping = MODULE_ORDER_TOTAL_FREEPRODUCTS_INC_SHIPPING;
		$this->include_tax = MODULE_ORDER_TOTAL_FREEPRODUCTS_INC_TAX;
		$this->calculate_tax = MODULE_ORDER_TOTAL_FREEPRODUCTS_CALC_TAX;
		$this->credit_tax = MODULE_ORDER_TOTAL_FREEPRODUCTS_CREDIT_TAX;
		$this->tax_class  = MODULE_ORDER_TOTAL_FREEPRODUCTS_TAX_CLASS;
		$this->show_redeem_box = MODULE_ORDER_TOTAL_FREEPRODUCTS_REDEEM_BOX;
		$this->credit_class = true;
		$this->checkbox = '<input type="checkbox" style="vertical-align:middle;" onClick="submitFunction()" name="' . 'c' . $this->code . '">' .  $this->user_prompt;
		$this->output = array();
    }

    function process() {
      global $order;
	  
	  $this->get_customers_fpc();
      if ( sizeof($this->customer_codes) > 0) {
          $this->output[] = array('title' => $this->title . ':',
                           'text' => '<b>' . $this->get_customers_fpc() . '</b>',
                           'value' =>  '');
        }
    }

	function selection_test() {


	}

	function pre_confirmation_check($order_total) {
		global $cot_freeproducts, $order, $cart;
	}


    function credit_selection() {
		global  $order, $currencies, $language, $cart;
		//alle Produkte finden die ersetzt/hinzugefügt werden dürfen
		$getp2p="SELECT * 
			FROM  `freeproductstoproducts` 
			JOIN  `freeproductsentry` ON (  `fp2p_id` =  `fpe_id` ) 
			WHERE ( fpe_start < NOW( ) )
			AND ( NOW( ) < fpe_end	)
			AND ( fpe_active =  'true'	)
			ORDER BY fpe_id
			";
		$qu=push_db_query($getp2p);
		while($p = push_db_fetch_array($qu))
		{
			$fpc[$p['fpe_name']][]['product']=$p['fp2p_products_id'];
			$fpc[$p['fpe_name']][]['replacement']=$p['fp2p_replacement_id'];
			$fpc[$p['fpe_name']][]['insert']=$p['fp2p_insert_id'];	
			$fpc[$p['fpe_name']][]['value']=$p['fp2p_min_value'];	
		}
		$flag=false;

		//refresh customer_codes
		$this->get_customers_fpc($_SESSION['customer_id']);

		//Step1 look if there are products with possible codes
		foreach($fpc AS $key => $value)
		{
			foreach($value AS $entry)
			{
				//look if corresponding Product in Cart
				if(array_key_exists($entry['product'],  $this->products) )
				{
					if($this->products[$entry['product']]['price'] > $entry['value'])
					{
						$flag=true;
					}
				}
				
			}
		
		}
		
		//herausfinden, ob Produkt, das in Gutschein angegeben ist ( bereits im Warenkorb ist bzw in der richtigen Anzahl oder ob es herausgenommen wurde)
		$sq=push_db_query("SELECT fpcc_products_id, COUNT(fpcc_products_id) AS quantity FROM freeproductscustomers WHERE fpcc_customers_id = '" . $_SESSION['customer_id'] . "' AND fpcc_status = 'pending' GROUP BY fpcc_products_id  ");
		
		while($cc = push_db_fetch_array($sq))
		{
			if(array_key_exists($cc['fpcc_products_id'],  $this->products) )
			{
				if($this->products[$cc['fpcc_products_id']]['qty'] > $cc['quantity'] )
				{
					//correct the number of free products in cart!
					$cart->update_quantity($cc['fpcc_products_id'], $cc['quantity']);
				}
				elseif($this->products[$cc['fpcc_products_id']]['qty'] < $cc['quantity'] )
				{
					$i=$cc['quantity'] - $this->products[$cc['fpcc_products_id']]['qty'];
					for($i; $i > 1; $i--){
						push_db_query("DELETE FROM freeproductscustomers WHERE fpcc_products_id='" . $cc['fpcc_products_id'] . "' AND fpcc_customers_id = '" . $_SESSION['customer_id'] . "' AND fpcc_status='pending' LIMIT 1;");
					}
				}
			}
			else
			{
				push_db_query("DELETE FROM freeproductscustomers WHERE fpcc_products_id='" . $cc['fpcc_products_id'] . "' AND fpcc_customers_id = '" . $_SESSION['customer_id'] . "' AND fpcc_status='pending';");
			}
 		}


		if($flag)
		{
			//var_dump($this->products);
			//die('[ging nicht anders - ist gestorben]');
			return '<div class="grid_9 prefix_3">' .  TEXT_HINT_FREEPRODUCTS . '<br><br></div><div class="clearfix"></div>';
		}
		else
		{
			return "";
		}
    }



	function get_customers_fpc(){

		$gutscheine='';
		$cq="SELECT * FROM freeproductscustomers WHERE fpcc_customers_id='" . (int)$_SESSION['customer_id'] . "' AND fpcc_status='pending'";
		$cqr=push_db_query($cq);
		while($c=push_db_fetch_array($cqr)){
			$this->customer_codes[]=$c['fpcc_code'];
			$gutscheine .="Gutschein: " . $c['fpcc_code'] . " <br>\n";
		}
		return $gutscheine;
	}

/**
 * hier werden alle Gutscheine als eingeloest markiert!
 */
	function update_credit_account() {
		global $order, $insert_id, $REMOTE_ADDR, $cart;
		$this->get_customers_fpc();
		foreach($this->customer_codes as $the_code)
		{
		//findout if product still inside order
			$cc= push_db_fetch_array(push_db_query("SELECT * FROM freeproductscustomers WHERE  fpcc_customers_id  = '" . $_SESSION['customer_id'] . "' AND BINARY fpcc_code = '" . $the_code . "'; "));
			if(array_key_exists($cc['fpcc_products_id'],  $this->products) )
			{
				//1st inside the customers table:
				push_db_query("UPDATE freeproductscustomers SET fpcc_status = 'redeemed' ,  fpcc_redeem_date = NOW()  WHERE fpcc_code = '" . $the_code . "' AND fpcc_customers_id   = '" . $_SESSION['customer_id'] . "';");
				//2nd at freeproductscode
				$uq=push_db_query("SELECT fpc_code FROM freeproductscode WHERE BINARY fpc_code = '" . $the_code . "' AND fpc_redeem= 'false'");
				if($t=push_db_fetch_array($uq))
				{
					push_db_query("UPDATE freeproductscode SET fpc_redeem = 'true',  fpc_redeem_ip= '" . $REMOTE_ADDR . "', fpc_redeem_customers_id= '" . $_SESSION['customer_id'] . "', fpc_redeem_date = NOW() WHERE BINARY fpc_code = '" . $the_code . "'");
				}
			}
		}
	/**/
	}
	
	function apply_credit() {
		global $order, $coupon_no, $cot_freeproducts, $cart;
		/* TEST * /
		return $gv_payment_amount;
		/**/
	}


//FIRST CALLED METHOD in confirmation
	function collect_posts() {
		global $currencies, $_POST,  $coupon_no, $REMOTE_ADDR, $cart, $code_invalid, $order;
		if(isset($_POST['gv_redeem_code'])){
			$holdc=explode(",", $_POST['gv_redeem_code']);
			$count=0;
			foreach($holdc as $co){	//jeden Code einzeln durchgehen
				$replaced=false; //or inserted??
				$co=trim($co);	 //$co haelt Code
				$test="SELECT DISTINCT * FROM freeproductscode JOIN freeproductsentry ON
					(fpc_id=fpe_id) WHERE
					BINARY fpc_code='" . mysql_real_escape_string($co). "' 
					AND (fpc_redeem='false' OR fpc_redeem='always')
					AND (fpc_redeem_customers_id='' OR fpc_redeem_customers_id='" . $_SESSION['customer_id'] . "')
					AND fpe_active='true' 
					AND fpe_start < NOW()
					AND NOW() < fpe_end";
				$tqr=push_db_query($test);
				if($ee=push_db_fetch_array($tqr)){	//code ist valid -> get the fpc_id

					$fpe_id=$ee['fpc_id'];
					$rpq="SELECT * 
							FROM  `freeproductstoproducts` 
							WHERE `fp2p_id`='" . $fpe_id . "'; ";
					//get all valid product-ids for the coupon
					$qr=push_db_query($rpq);
					while($tt=push_db_fetch_array($qr)){
						//loop for products (one after one)
						if(array_key_exists($tt['fp2p_products_id'],  $this->products) )
						{

							if($this->products[$tt['fp2p_products_id']]['price'] > $tt['fp2p_min_value'])
							{
								if(trim($tt['fp2p_replacement_id']) <>'')
								{//Produkt im Warenkorb gefunden, einmal !! entfernen
								 //product located in cart  remove one(!) piece of it
									if($cart->get_quantity($tt['fp2p_products_id'])==1)
									{
										$cart->remove($tt['fp2p_products_id']);
									}else{
										$cart->update_quantity($tt['fp2p_products_id'],(($cart->get_quantity($tt['fp2p_products_id'])) -1));		
									}	
									if(!$cart->in_cart($tt['fp2p_replacement_id']))
									{
										$cart->add_cart($tt['fp2p_replacement_id'], '1', '', false);
									}else{
										$cart->update_quantity($tt['fp2p_replacement_id'] , ($cart->get_quantity($tt['fp2p_replacement_id']) + 1),'');
									}
									$newprod= $tt['fp2p_replacement_id'];
								}
								elseif($tt['fp2p_insert_id'] <>'')
								{
									if(! array_key_exists($tt['fp2p_insert_id'],  $this->products) )
									{
										$cart->add_cart($tt['fp2p_insert_id']);//works only once!
										$newprod= $tt['fp2p_insert_id'];
									}
								}
							}
							//write product to the current CODE
							//insert the user ID to the valid coupon
							$uq="UPDATE freeproductscode SET fpc_redeem_customers_id='" . $_SESSION['customer_id'] .  "', fpc_redeem_product='" . $tt['fp2p_products_id'] . "' WHERE fpc_redeem='false' AND BINARY fpc_code='" . mysql_real_escape_string($co). "' ";
							push_db_query($uq);
							$uq="INSERT INTO freeproductscustomers (fpcc_customers_id, fpcc_entry_id, fpcc_code, fpcc_products_id, fpcc_status) VALUES ('" . $_SESSION['customer_id'] . "', '" . $fpe_id . "', '" . $co . "', '" . $newprod . "', 'pending' )";
							push_db_query($uq);
							$replaced=true;

							$code_invalid = false;
							break;
						}
					}
				}
				else
				{
					//wenn code nicht korrekt war?
					$count++;
					$cgcache[]=$co;
					//kann auch ein anderer Code gemeint worden sein...
				}
			}
			//alle abgearbeitet
			if(($count > 0)&&(is_array($cgcache)))
			{
				if(count($cgcache)==1)
					$ts=$cgcache[0];
				else
					$ts=implode(",",$cgcache);
				
				$_POST['gv_redeem_code']=$ts;	
			}
			elseif($count==0)
			{
				unset($_POST['gv_redeem_code']);			
			}
		}
		
	 $order->cart();
   }

   function use_credit_amount() {
		global $cot_freeproducts, $currencies, $cart, $order,  $insert_id, $REMOTE_ADDR;
/* TEST * /
//      $_SESSION['cot_freeproducts'] = false;     // old code - Strider
      $cot_freeproducts = false;
      if ($this->selection_test()) {
			$gv_query = push_db_query("select amount from " . TABLE_COUPON_FREEPRODUCTS_CUSTOMER . " where customer_id = '" . $_SESSION['customer_id'] . "'");
			$gv_result = push_db_fetch_array($gv_query);
			$am=$currencies->format($gv_result['amount']);
        $output_string .=  '    <b class="grid_3">' . TEXT_APPLY_GUTHABEN . '</b>';
        $output_string .= '		<div class="grid_13" style="margin-bottom:20px;">' . TEXT_QUESTION_GUTHABEN  . ' <span class="gv_orange">' . $am . '</span><br><br>' . $this->checkbox . '<br><small style="padding-left:20px;">' . TEXT_EXPLAIN_APPLY . '</small></div>' . "\n";
      }
      return $output_string;/**/
    }


    function calculate_credit($amount) {
      global  $order,$cart;
	  /* TEST * /
      $gv_query=push_db_query("select amount from " . TABLE_COUPON_FREEPRODUCTS_CUSTOMER . " where customer_id = '" . $_SESSION['customer_id'] . "'");
      $gv_result=push_db_fetch_array($gv_query);
      $gv_payment_amount = $gv_result['amount'];
      $gv_amount = $gv_payment_amount;
      $save_total_cost = $amount;
      $full_cost = $save_total_cost - $gv_payment_amount;
      if ($full_cost <= 0) {
        $full_cost = 0;
        $gv_payment_amount = $save_total_cost;
      }
      return push_round($gv_payment_amount,2);/**/
    }

    function calculate_tax_deduction($amount, $od_amount, $method) {
      global $order,$cart;
/* TEST * /
      switch ($method) {
        case 'Standard':
        $ratio1 = push_round($od_amount / $amount,2);
        $tod_amount = 0;
        reset($order->info['tax_groups']);
        while (list($key, $value) = each($order->info['tax_groups'])) {
          $tax_rate = push_get_tax_rate_from_desc($key);
          $total_net += $tax_rate * $order->info['tax_groups'][$key];
        }
        if ($od_amount > $total_net) $od_amount = $total_net;
        reset($order->info['tax_groups']);
        while (list($key, $value) = each($order->info['tax_groups'])) {
          $tax_rate = push_get_tax_rate_from_desc($key);
          $net = $tax_rate * $order->info['tax_groups'][$key];
          if ($net > 0) {
            $god_amount = $order->info['tax_groups'][$key] * $ratio1;
            $tod_amount += $god_amount;
            $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
          }
        }
        $order->info['tax'] -= $tod_amount;
        $order->info['total'] -= $tod_amount;
        break;
        case 'Credit Note':
          $tax_rate = push_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tax_desc = push_get_tax_description($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tod_amount = $this->deduction / (100 + $tax_rate)* $tax_rate;
          $order->info['tax_groups'][$tax_desc] -= $tod_amount;
//          $order->info['total'] -= $tod_amount;   //// ????? Strider
        break;
        default:
      }
      return $tod_amount;/**/
    }

    function user_has_gv_account($c_id) {
	/* TEST * /
      $gv_query = push_db_query("select amount from " . TABLE_COUPON_FREEPRODUCTS_CUSTOMER . " where customer_id = '" . $c_id . "'");
      if ($gv_result = push_db_fetch_array($gv_query)) {
        if ($gv_result['amount']>0) {
          return true;
        }
      }
      return false;
	  /**/
    }

    function get_order_total() {
      global $order;
      $order_total = $order->info['total'];
      if ($this->include_tax == 'false') $order_total = $order_total - $order->info['tax'];
      if ($this->include_shipping == 'false') $order_total = $order_total - $order->info['shipping_cost'];

      return $order_total;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_FREEPRODUCTS_STATUS'");
        $this->check = push_db_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_FREEPRODUCTS_STATUS', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_SORT_ORDER', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_QUEUE', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_INC_SHIPPING', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_INC_TAX', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_CALC_TAX', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_TAX_CLASS', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_CREDIT_TAX');
    }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Total', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_STATUS', 'true', 'Do you want to display the Gift Voucher value?', '6', '1','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_SORT_ORDER', '740', 'Sort order of display.', '6', '2', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Queue Purchases', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_QUEUE', 'true', 'Do you want to queue purchases of the Gift Voucher?', '6', '3','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Shipping', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_INC_SHIPPING', 'true', 'Include Shipping in calculation', '6', '5', 'push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_INC_TAX', 'true', 'Include Tax in calculation.', '6', '6','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Re-calculate Tax', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_CALC_TAX', 'None', 'Re-Calculate Tax', '6', '7','push_cfg_select_option(array(\'None\', \'Standard\', \'Credit Note\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_TAX_CLASS', '0', 'Use the following tax class when treating Gift Voucher as Credit Note.', '6', '0', 'push_get_tax_class_title', 'push_cfg_pull_down_tax_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Credit including Tax', 'MODULE_ORDER_TOTAL_FREEPRODUCTS_CREDIT_TAX', 'false', 'Add tax to purchased Gift Voucher when crediting to Account', '6', '8','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
