<?php if(isset($_GET['usermode'])){
	//die('HOT! Now OFFLINE!');
	//$_SESSION['customer_id']=36767;
	$_SESSION['customer_id']=$_GET['uid'];
	include('includes/ajax_top.php');
	//include('includes/classes/currencies.php');
	
	//error_reporting(E_ALL | E_STRICT);
	$currencies= new $currencies;
	
	include(DIR_WS_CLASSES ."xml_order.php");
	$xo = new xml_order($_GET['oid']);
	$xo->generate_xml();
	$xo->send_xml();
	header('Location: https://www.if-bi.com/shop/xml_test.php');
}
else{
	echo "Closed by Admin! <br>No Way!";	
}
?>