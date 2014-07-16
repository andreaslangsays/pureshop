<?php //Hier weiter
chdir('../../../');
include('includes/ajax_top.php');
$action = $_GET['action'];
$product_id = $_GET['pid'];
$customer_id = $_GET['cid'];
$customer = new Customer($customer_id);
if($action=='add')
{
	$q=push_db_query("SELECT customers_ids FROM products_notifications WHERE products_id ='" . (int)$product_id . "';");
	if($qr = push_db_fetch_array($q))
	{
		$cids = $qr['customers_ids'];
		if($cids<>'')
		{
			$cida = explode(',',$cids);
		}
		else
		{
			$cida=array();
		}
		$cida[]='|'.(int)$_SESSION['customer_id'].'|';
		$cida = array_unique($cida);
		$cids = implode(',',$cida);
		push_db_query("UPDATE products_notifications SET customers_ids ='" . $cids . "' WHERE products_id='" . (int)$product_id . "'");
	}
	else
	{
		push_db_query("INSERT INTO products_notifications SET customers_ids ='" . '|' . (int)$_SESSION['customer_id'] . '|' . "', products_id = '" . (int)$product_id . "' ");	
	}
}
if($action=='remove')
{
	$q=push_db_query("SELECT customers_ids FROM products_notifications WHERE products_id ='" . (int)$product_id . "';");
	if($qr = push_db_fetch_array($q))
	{
		$cids = $qr['customers_ids'];
		$cida = explode(',',$cids);
		$cidb = array();
		foreach($cida as $c)
		{
			if($c <> "|" . $customer_id . "|")
			{
				$cidb[]	= $c;
			}
		}
		$cida = array_unique($cidb);
		$cids = implode(',',$cida);
		if($cits<>'')
		{
			push_db_query("UPDATE products_notifications SET customers_ids ='" . $cids . "' WHERE products_id='" . (int)$product_id . "'");
		}
		else
		{
			push_db_query("DELETE FROM products_notifications WHERE products_id='" . (int)$product_id . "'");
		}
	}
}
	$product = new product;
	$product->load_product($product_id);
	echo $product->get_notify_button();
	//echo "<a href='www.google.com/ig'>KLIQUEZ ICI!</a>";
?>