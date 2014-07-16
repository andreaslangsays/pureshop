<div id="ware-lager">
<?php
/*
 * Krös
 */
 
$q=push_db_fetch_array(push_db_query("SELECT count(products_id) as anz FROM products WHERE (`products_date_available` < CURDATE() OR `products_date_available` = '0000-00-00 00:00:00') AND  products_status=1 AND products_quantity = 0 and products_drop_shipment = 0;"));
$tanz=push_db_fetch_array(push_db_query("SELECT count(products_id) as tanz FROM products WHERE (`products_date_available` < CURDATE() OR `products_date_available` = '0000-00-00 00:00:00') AND  products_status=1;"));
$prz=round( floatval(100/$tanz['tanz']) * ($tanz['tanz']-$q['anz'])*10) / 10;
if(false)
{
	echo "<!-- " . $tanz['tanz'] . " " . $q['anz'] . " $prz -->"; 
}
if($prz < 90){
	$prz = 90.1;
}
?>
<span style="display:block;font-size:50px;line-height:65px;text-align:center;color:#666;"><?=number_format($prz, 1, ',', ' ');?>%</span>
<span style="display:block;font-size:17px;line-height:15px;text-align:center;color:#666;">unserer Ware auf</span>
<span style="display:block;font-size:45px;line-height:48px;text-align:center;color:#666;">LAGER</span>
</div>
