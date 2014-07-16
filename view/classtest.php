<?php
/*
  $Idä: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
* /
  require('includes/ajax_top.php');
 


 /*SCRIPT TO REMOVE NON EXISTING Addresses 
  	echo "<html><head></head><body>WOW:<br>";
	
	$g=push_db_query("SELECT * FROM customers");
	$i=0;
	$j=0;
	while($t = push_db_fetch_array($g))
	{
		$ad=push_db_fetch_array(push_db_query("SELECT address_book_id FROM address_book WHERE customers_id ='" . $t['customers_id'] . "';"));
		
			$address=($ad['address_book_id']<>'')?$ad['address_book_id']:'9999999' ;
			
			$addarr = array('customers_default_address_id','customers_bill_address_id', 'customers_shipping_address_id');
			foreach($addarr as $arr)
			{
				if($t[$arr]< '9999999')
				$questionne= "SELECT address_book_id FROM address_book WHERE address_book_id = '" . $t[$arr] . "';";
				$aq1 = push_db_query($questionne);
				if(! push_db_fetch_array($aq1))
				{
//					if ($res)
					$i++;
					echo "<h2>" .  $t['customers_id']. "</h2>";
					echo $i . " <br> ";
					push_db_query("UPDATE customers SET " . $arr . " = '" . $address . "' WHERE customers_id ='" . $t['customers_id'] . "'" );
				}
			}
		}
		else
		{
			$j++;
			echo "$j.ter Kunde ". $t['customers_id'] . " hat keine Adresse !<br>"; 	
		}
		
	}
	*/ /*
?>
<?php
//error_reporting(E_ALL | E_STRICT);
/*
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
require(DIR_WS_BOXES . 'html_header.php');
?>
<div class="container_16">
<h1>Class Discount_new.php</h1>
<p>Results</p>
<?php 
//include(DIR_WS_CLASSES . "Discount_new.php");
//$newdiscount = new discounts();
//$newdiscount->calculate();
//var_dump($discount);
//$discount->fix_and_redeem('123412');
		
?>
<br>
#############################################################
<br><pre>
<?php
var_dump($discount);
?></pre>
</div>
    </div>
	<!-- body_text_eof //-->
</div>
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<?php require(DIR_WS_LIB . 'end.php'); ?>
*/
?>
</body></html>