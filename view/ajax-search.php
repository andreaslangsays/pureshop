<?php 

function html_replace($snippet){
	$snippet = utf8_encode($snippet);				
//	$snippet = mb_convert_encoding($snippet, 'ISO_8859-1', 'UTF-8');
	return $snippet;	
}
include("includes/ajax_top.php");
$qu=mysql_real_escape_string($_GET["q"]);
if(!$qu)return;

$sq="SELECT manufacturers_name FROM manufacturers WHERE manufacturers_name LIKE '$qu%'";
$tag="manufacturers_name";
$qsq=push_db_query($sq);
while($txt = push_db_fetch_array($qsq)){
	
	// echo html_replace($txt[$tag])."\n";			// we don't need it, text is already encoded in utf8
	echo $txt[$tag] . "\n";
	
}

$sq="SELECT pd.products_name FROM products_description pd left join products p on pd.products_id=p.products_id WHERE p.products_status=1 AND p.products_model NOT LIKE 'ccb%' AND products_name LIKE '%$qu%';";
$tag='products_name';

$qsq=push_db_query($sq);
while($txt = push_db_fetch_array($qsq)){
	
	// echo html_replace($txt[$tag])."\n";			// we don't need it, text is already encoded in utf8
	echo $txt[$tag] . "\n";
	
}
include('includes/ajax_bottom.php');
?>