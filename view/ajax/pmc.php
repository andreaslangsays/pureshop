<?php
//krös
if(isset($_GET['osCzid']) && $_GET['osCzid'] <>'')
{
chdir('../../../');
require('includes/ajax_top.php');
//require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);


}
/*
elseif($_GET['osCzid'] == '')
{
	header("Location: http://if-bi.com/shop/includes/modules/ajax/pmc.php");
}
*/

else{
	chdir('../../../');
	require('includes/ajax_top.php');
	//require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
}


$kdnr = '';
$name = '';
$vorname = '';
$firma = '';
$strasse = '';
$plz = '';
$ort = '';
$mail = '';
$phone = '';
if($customer->login){
// user logged in
$kdnr = $customer->selectline_customers_id;
$name = $_SESSION['customer_last_name'];
$vorname = $_SESSION['customer_first_name'];
$addressid = $customer->customers_default_address_id;
$t=push_db_fetch_array(push_db_query("SELECT * FROM address_book WHERE address_book_id ='" . $addressid . "'"));
$firma = $t["entry_company"];
$strasse = $t["entry_street_address"];
$plz = $t["entry_postcode"];
$ort = $t["entry_city"];
$mail =  $customer->customers_email_address;
$phone = $customer->customers_telephone;


}
else
{
$name="";
}
# Header for javascript
  header("Content-Type: application/javascript");
?>
daten({	"kdnr":"<?= $kdnr ?>",
		"name":"<?= $name ?>",
		"vorname":"<?= $vorname ?>",
		"firma":"<?= $firma ?>",
		"strasse":"<?= $strasse ?>",
		"plz": "<?= $plz ?>",
		"ort":"<?= $ort ?>",
		"email":"<?= $mail ?>",
		"phone":"<?= $phone ?>"
		})