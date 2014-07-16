<?php
/**
 * Krös: set needed vars fpr cookie exchange to subdomain:
 */
$q=push_db_fetch_array(push_db_query("SELECT count(products_id) as anz FROM products WHERE  products_status=1 AND products_quantity = 0 and products_drop_shipment = 0;"));
$tanz=push_db_fetch_array(push_db_query("SELECT count(products_id) as tanz FROM products WHERE products_status=1;"));
$prz=round( floatval(100/$tanz['tanz']) * ($tanz['tanz']-$q['anz'])*10) / 10;

if($prz < 90)
{
	$prz = 90.1;
}
if(push_session_is_registered('customer_id')) 
{
	$cookiename =  $_SESSION['customer_first_name'] . ' ' . $_SESSION['customer_last_name'];
?>