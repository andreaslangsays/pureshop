<?php

//class to split the order to various parts

class splitorder{

	function splitorder()
	{
		global $discount;
		$this->debugger = false;
		$this->names=array();
		//do something strange initially
		$this->staffel = $discount->discount_procent;
		$this->staffel_value = $discount->discount_amount;
		$this->staffel_reduction = $discount->general_savings;
		$this->personal_discount = $discount->private_rabatt_procent;
		$this->personal_discount_reduction = $discount->private_rabatt_saving;
		if($discount->coupon['is'] == true)
		{
			$this->coupon = intval($discount->coupon['value']);
			$this->coupon_reduction = $discount->coupon['reduction'];
		}
		$this->online_rabatt = $discount->online_rabatt_procent;
		$this->online_rabatt_reduction = $discount->online_rabatt;
	}

	function prepare_cart($name)
	{
		$this->cart[$name]['staffel'] = 0;
		$this->cart[$name]['summe'] = 0;
		$this->temp_staffel[$name] = 0;
		$this->temp_online[$name] = 0;
		$this->temp_personal[$name] = 0;
		$this->temp_coupon[$name] = 0;
		$this->prepared[$name]=true;
	}

	function add_to_cart($item_id, $item_qty, $name)
	{
		global $product, $discount, $customer;
		$this->cart_exists($name);
		$this->cart[$name]['p'][$item_id]['id'] = $item_id;
		$this->cart[$name]['p'][$item_id]['qty'] = $item_qty;
		$product->load_product($item_id);
		$this->cart[$name]['p'][$item_id]['preis'] = ($product->ve_loaded)? $product->ve_end_price : $product->final_price ;
		$this->cart[$name]['p'][$item_id]['price'] = $this->cart[$name]['p'][$item_id]['preis'];
		$this->cart[$name]['p'][$item_id]['final_price'] = $this->cart[$name]['p'][$item_id]['price'];
		$this->cart[$name]['p'][$item_id]['summe'] = floatval($this->cart[$name]['p'][$item_id]['preis'] * $this->cart[$name]['p'][$item_id]['qty']);
		$this->debug( "{ $item_id x $item_qty : " . $this->cart[$name]['p'][$item_id]['summe'] ." }... <br>");
			$t_tax = strval($product->tax);
		if($product->include_discount == 1)
		{
			$this->cart[$name]['staffel'] += $this->cart[$name]['p'][$item_id]['summe'];
			$this->cart[$name]['staffel_tax'][$t_tax] += ($this->cart[$name]['p'][$item_id]['summe']/100) * $t_tax;
		
		}
		else
		{
				$this->cart[$name]['general_tax'][$t_tax] += ($this->cart[$name]['p'][$item_id]['summe']/100 * $t_tax);
		}
		$this->cart[$name]['summe'] += $this->cart[$name]['p'][$item_id]['summe'];
		
		$this->cart[$name]['p'][$item_id]['tax'] = $t_tax;
	}

	function straighten_discounts()
	{
		foreach($this->cart as $name => $tcart)
		{
			$this->debug(" $name Summe" . $tcart['summe'] . "<br>");
			$tsumme = $tcart['summe'];
			if($tcart['staffel']>0 && $this->staffel > 0 )
			{
				$this->temp_staffel[$name] = $this->reduction($tcart['staffel'], $this->staffel); //
				$this->reduce_tax($name, $this->staffel,true);
				$tsumme =$tsumme - $this->temp_staffel[$name];
			}
			if($this->personal_discount > 0)
			{
				$this->temp_personal[$name] = $this->reduction($tsumme,$this->personal_discount);
				$this->reduce_tax($name, $this->personal_discount);
				$tsumme =$tsumme - $this->temp_personal[$name];
			}
			if($this->coupon > 0)
			{
				$this->temp_coupon[$name] =  $this->reduction($tsumme,$this->coupon);
				$this->reduce_tax($name, $this->coupon);
				$tsumme =$tsumme - $this->temp_coupon[$name] ;
			}
			$this->temp_online[$name] =   $this->reduction($tsumme, $this->online_rabatt);
			$this->reduce_tax($name, $this->online_rabatt);
			$this->debug($name . " -- " . $this->temp_online[$name] . "--- online? <br>");
			$this->names[]=$name;
			$this->tmp_endsum[$name] = $this->temp_staffel[$name] + $this->temp_personal[$name] + $this->temp_coupon[$name] + $this->temp_online[$name];
		}
		foreach($this->cart as $name => $tc)
		{
			$tx=array();
			foreach($tc['general_tax'] as $key => $val)
			{
				$tx[$key] +=$val;
			}
			foreach($tc['staffel_tax'] as $key =>$val){
				$tx[$key] +=$val;
			}
			foreach($tx as $ky => $vl)
			{
				$this->tax[$name][$ky]	= $vl;
			}
		}
		
		
		$staffel=0;
		foreach($this->temp_staffel as $summe)
		{
			$staffel +=	$summe;
		}
		$personal=0;
		foreach($this->temp_personal as $summe)
		{
			$personal +=	$summe;
		}
		$coupon=0;
		foreach($this->temp_coupon as $summe)
		{
			$coupon +=	$summe;
		}
		$online=0;
		foreach($this->temp_online as $summe)
		{
			$online +=	$summe;
		}
	}
	
	function cart_exists($name)
	{
	
		if($this->prepared[$name]){
			return true;	
		}
		else
		{
			$this->prepare_cart($name);
			return true;
		}
	}

	function debug($txt)
	{
		if($this->debugger===true)
		{
			echo $txt;
		}
	}
	
	function reduction($input, $procent)
	{
		return  (round(($input / 100) * $procent * 100)/100);
	}

	function reduce($input, $procent)
	{
		return  $input - (($input / 100) * $procent);
	}
	
	function reduce_tax($name, $procent, $staffel=false)
	{
		$i=0;
		foreach($this->cart[$name]['staffel_tax'] as $tkey => $taxes)
		{
			$st[$i]['key']=$tkey;
			$st[$i]['val']=$this->reduce($taxes,$procent);
			$i++;
		}
		foreach($st as $w)
		{
			$this->cart[$name]['staffel_tax'][$w['key']] =  $w['val'];
		}
		if($staffel)
		{
			return;
		}
		$i=0;
		foreach($this->cart[$name]['general_tax'] as $tkey => $taxes)
		{
			$gn[$i]['key']=$tkey;
			$gn[$i]['val']=$this->reduce($taxes,$procent);
			$i++;
		}
		foreach($gn as $w)
		{
			$this->cart[$name]['general_tax'][$w['key']] =  $w['val'];
		}		
		
	}
}
?>