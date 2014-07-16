<?php
// BEGIN CÖOKIE_CART
			//error_log("[" . date("Y/m/d h:i:s", mktime()) . "] " ."cart_cookie.php::ENTER STORE_CART_COOKIE = ".STORE_CART_COOKIE."\r\n", 3, "cookielog.txt");

if (false&&STORE_CART_COOKIE == 'true')
{
 	 //error_log("[" . date("Y/m/d h:i:s", mktime()) . "] " ."cart_cookie.php::Check if cookie was sent ( ".$HTTP_COOKIE_VARS['cart'].") \r\n", 3, "cookielog.txt");
	  // Check if the cookie was sent by the client
		if (isset($_COOKIE['_CXT']))
		{
			// Retrieve the contents of the cart from the cookie
			//error_log("[" . date("Y/m/d h:i:s", mktime()) . "] " ."cart_cookie.php::COOKIE CONTENTS --- > \r\n".$HTTP_COOKIE_VARS['cart']."\r\n", 3, "cookielog.txt");
			$cart_contents_str = $_COOKIE['_CXT'];
			$item_lines = explode(':', $cart_contents_str);
			//error_log("[" . date("Y/m/d h:i:s", mktime()) . "] " ."cart_cookie.php::No. of Items in Cart = ".sizeof($item_lines)."\r\n", 3, "cookielog.txt");
			for ($i=0, $n=sizeof($item_lines); $i<$n; $i++) {
			 	//error_log("Item #".$i." is ".$item_lines[$i], 3, "cookielog.txt");
			 	parse_str($item_lines[$i]);
			 	//$cart->add_cart($PID, $QTY);
			}

	//		$cart->set_contents($cart_contents_str);
			//error_log("cart_cookie.php Set Cart Contents = ".$cart_contents_str."\r\n", 3, "cookielog.txt");
		}
		else
		{
		 		//error_log("[" . date("Y/m/d h:i:s", mktime()) . "] " ."cart_cookie.php::Cart Cookie NOT sent by Client = \r\n", 3, "cookielog.txt");
		}
}
else
{
		if (push_session_is_registered('cart'))
		{
		 		//error_log("[" . date("Y/m/d h:i:s", mktime()) . "] " ."cart_cookie.php::FEATURE DISABLED->cart is REGISTERED \r\n", 3, "cookielog.txt");
		}
		else
		{
				//error_log("[" . date("Y/m/d h:i:s", mktime()) . "] " ."cart_cookie.php::FEATURE DISABLED->cart is NOT REGISTERED \r\n", 3, "cookielog.txt");
		}
}
?>