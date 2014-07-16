<?php 
	global $discount;
	return '<div class="cart-box-discount-popup">
			<div class="tx_15_20" style="width: 270px; height: 45px; padding: 5px 0 0 10px; margin-bottom: 7px; background: url(\'./images/push/pink-grid-bg-small.png\')">
				' . $discount->next_spush_popup_header .
			'</div>' .
			 $discount->next_spush_popup_description .
		'</div> ';
?>