<?php
/*
 * just a try to create a paypal - class, to simplify ordering via paypal
 *
 * Abstract:
 *
 * - declare a container for any articles OR(!) a cart amount
 * - set the recording post-parameters
 * - create the post-query and send it to paypal
 * - be able to use the sandbox
 * - be able to choose the right localisation of paypal (if necessary)
 * - set an option for free donations etc
 *
 *
 *
 */

class al_paypal{
#########
		#
		#
		# Class Members
		#
	var $sandbox = false;		# mode of the object live(default)/sandbox
	#
	var $donate = false;		# user may /may not (default) set the amount of money to send via paypal
	#
	var $whole_cart = false;
	#
	var $company = "";
	#
	var $transID = "";
	#
	var $amount = 0;
	#
	var $sb_url = "https://www.sandbox.paypal.com";
	#
	var $live_url = "https://www.paypal.com";
	#
	var $pp_url;							# url of paypal site
	#
	var $forward_address = false;
	#
	#####################################################################################################################
	##
	## BOF PAYPAL-VARIABLES
	##
	var $pp_notify_url;			# IPN-URL url-encoded
	## required for shopping carts / optional for donations
	var $pp_item_name;			# description of the transactin if not set the customer may define its own description
	##
	var $pp_item_number;		# number of the transactin something like an ID
	##
	var $pp_quantity;			# total number of items in the transactin
	##
	var $pp_undefined_quantity;	# buyer can change quantity
	##
	var $pp_on0;				# optional parameter 0: name
	##
	var $pp_on1;				# optional parameter 1: name
	##
	var $pp_os0;				# optional parameter 0: value
	##
	var $pp_os1;				# optional parameter 1: value
	##
	var $pp_add = "1";			# required value for shopping-carts adds an item to the paypal-cart
	##
	var $pp_cancel_return;		# address to jump if the customer doesn't finish the payment
	##
	var $pp_cbt;				# text on the payment-finished-page of PP, requires $pp_return
	##
	var $pp_cn;					# description - text to display above the textfield for customers annotations
	##
	var $pp_cpp_header_image;	# src of an image as a header at the upper left corner of payment-page	(max 90*750px) maybe a logo...
	##
	var $pp_cpp_headerback_color; 	# bgcolor for the header; in hex-format
	##
	var $pp_cpp_headerborder_color;	# bordercolor for the headline; will be 2px in width, a hex-formatted color
	##
	var $pp_cpp_payflow_color;		# bgcolor of payment-content on the payment-website; some colors will be ignored, hex-format
	##
	var $pp_cs;						# bgcolor of the whole payment-sites; '0' (default) -> white | '1' -> black ... no other choices
	##
	var $pp_display;			# shows the content of the paypal-shoppping-cart, has priority to add
	##
	var $pp_image_url;			# address of an image to be displayed in the left upper corner of paypal-pages, PP recommends a https-connection
								# default behavior: PP shows your company-name or your email-address
	##
	var $pp_no_note;			# trigger for displaying a textfield for user-comments: '0' (default) displays it | '1' doesn't
	##
	var $pp_no_shipping;		# trigger for delivery-address: '0' (default) customer may add his delivery-address | '1' he may not give his address
	##
	var $pp_page_style;			# TODO: posibility to set a style via css? how does it work?
	##
	var $pp_return;				# redirection: address of your own payment-success-page - default: paypal shows a "thank you for using paypal"-page or so
	##
	var $pp_rm;					# return method: '0' (default) -> GET | '1' -> GET | '2' ->POST
	##
	var $pp_address_override;	# TODO: I don't understand, customer may not change the address if set to '1'
	##
	var $pp_currency_code;		# sets the currency for all transactions DEFAULT: USD!
	##
	var $pp_custom;				# this value will be redirected directly to your web-application (maybe for security-reasons?)
	##
	var $pp_handling;			# sets a handling fee - independent from customers basket
	##
	var $pp_invoice;			# transactions ID of your web application will be redirected
	##
	var $pp_redirect_cmd;		# TODO: understand that!
	##
	var $pp_shipping;			# shipping fee, overall (if shipping2 is unset)
	##
	var $pp_shipping2;			# shipping2: sets a seperate shipping fee for each item
	##
	var $pp_tax;				# sets the tax fees for all items - - - but how???
	##
	var $pp_tax_cart;			# overall-tax, item-based taxes will be ignored
	##
	## some required values
	##
	var $pp_amount;				# price of item or the overall price of shopping cart
	##
	var $pp_business;			# email-address of your PP-Account
	##
	var $pp_item_name;			# name of item or cart
	##
	var $pp_upload = 1;			# load the contents of shopping-cart to PP ||| set to one!
	##
	var $pp_amount_x = array();			# price of item x (sort of an array...)
	##
	var $pp_item_name_x = array();		# name of item x (sort of an array...)
	##
	## not required but useful :-)
	##
	var $pp_item_number_x = array();	# article-ID of item x (sort of an array...)
	##
	var $pp_on0_x = array();			# optional-value-name0 of item x (sort of an array...)
	##
	var $pp_on1_x = array();			# optional-value-name1 of item x (sort of an array...)
	##
	var $pp_os0_x = array();			# optional-value0 of item x (sort of an array...)
	##
	var $pp_os1_x = array();			# optional-value1 of item x (sort of an array...)
	##
	var $pp_quantity_x = array();		# quantity of item x (sort of an array...)
	##
	var $pp_shipping_x = array();		# shipping cost of item x (sort of an array...)
	##
	var $pp_shipping2_x = array();		# shipping cost of item x if( quantity > 2) (sort of an array...)
	##
	var $pp_tax_x = array();			# tax of item x (sort of an array...)
	##
	var $pp_handling_cart;		# handling fee for cart (overall)
	##
	## auto-fill-data formular at PP-Site
	##
	var $pp_address1;			# street
	##
	var $pp_address2;			# street
	##
	var $pp_city;				# city
	##
	var $pp_zip;				# zip code of location in german: PLZ
	##
	var $pp_email;				# email address of customer
	##
	var $pp_first_name;
	##
	var $pp_last_name;
	##
	var $pp_lc;					# language-code (eg DE)
	##
	var $pp_night_phone_a;		# US-phone thing A???
	##
	var $pp_night_phone_b;		# US-phone thing B???
	##
	var $pp_night_phone_c;		# US-phone thing C???
	##
	var $pp_state;				# for addresses in US or Canada
	##
	## EOF PAYPAL - VARIABLES
	##
	###########################################################################################################
		#
#########

#########
	function al_paypal($company, $notify_url,$currency="EUR", $sb=false){
		#
		# Constructor
		#
		$this->pp_currency_code=$currency;
		#
		$this->pp_notify_url = $notify_url;
		#
		$this->company = $company;
		#
		$this->pp_url = ($sb)? $this->sb_url : $this->live_url;
		#
	}	# EOF al_paypal($company, $notify_url, $sb=false)
#########

#########
	function set_amount($amo){
		#
		# set the whole amount for paypal
		#
		 if($amo>"0"){
		 	$this->whole_cart = true;
		 	$this->pp_amount = $amo;
		 }
		#
	}	# EOF set_amount()
#########

#########
	function pay(){
		#
		# create a Form and send it via POST
		#


	}	# EOF pay()
#########

#########
	function customer_address($first_name,$last_name,$street1,$street2,$city,$zip,$email,$phone){
		#
		# set the address of customer to be forwarded onto paypal
		#
		$this->forward_address = true;
		$this->pp_first_name = $first_name;
		$this->pp_last_name = $last_name;
		$this->pp_address1 = $street1;
		$this->pp_address2 = $street2;
		$this->pp_city = $city;
		$this->pp_zip = $zip;
		$this->pp_email = $email;
		$this->pp_night_phone_a = $phone;
	}	# EOF customer_address($first_name,$last_name,$street1,$street2,$city,$zip,$email,$phone)
#########

#########
}		#EOF class al_paypal
#########
?>