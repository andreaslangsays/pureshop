<?php
//new html
$html_email_order  = "<!DOCTYPE HTML>
<html>
	<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"> 
	$Varhttp 
	 
	</head>";

//here goes the header!
$html_email_order .= "
<body >
<table id=\"mailroot\">
<tr>
		<td>
<!-- start content tables -->
		<table width=\"640\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style='margin-left:30px;'>
			<tr>
				<td height=\"120\" style=\"vertical-align:middle;\">$Varlogo
				</td>
			</tr>";


//here starts the order detail
$html_email_order .= "
			<tr> 
				<td class=\"orderdetails\">
					<h2>" . ORDER_DETAILS_HEADER. "</h2>
				 </td>
			</tr >
			<tr class=\"infoBoxContents\">
				<td>
					$Varcustomer
				</td>
			</tr>
			<tr class=\"infoBoxContents\">
				<td >
					$varuser
				</td>
			</tr>
			<tr class=\"infoBoxContents\">
				<td class=\"infoBoxContents\" >
					$vardatecommande 
				</td>
			</tr>
			<tr class=\"infoBoxContents\">
				<td>
					$varcomments<br>
					<blockquote> $varinfocomments </blockquote>
				</td>
			</tr>
			<tr class=\"infoBoxContents\">
				<td class=\"infoBoxContents\">
					$Vartext2<br>
				</td>
			</tr>
			<tr class=\"infoBoxContents\">
				<td class=\"infoBoxContents\">
					<div align=\"center\">
						$varimage
					</div>
				</td>
			</tr>
			<tr class=\"infoBoxContents\">
				<td class=\"infoBoxContents\">
					$vartable2
				</td>
			</tr>
		</table>";
//Here starts next table
$html_email_order .= "
<!-- start products listing -->
		<table width=\"640\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color:#ffffff;margin-left:30px;border:none;\">
			<tr>
				<td class=\"infoBoxHeading\" align=\"left\" width=\"300\" style=\"padding: 16px 0;border-bottom:1px solid #666;\">
					<strong>$varproducts</strong>
				</td>
				<td class=\"infoBoxHeading\" align=\"center\" width=\"160\" style=\"padding: 16px 0;border-bottom:1px solid #666;\">
					<strong>$varmodel</strong>
				</td>
				<td class=\"infoBoxHeading\" align=\"left\" width=\"78\" style=\"padding: 16px 0;border-bottom:1px solid #666;\">
					<strong>$varqty</strong>
				</td>
				<td class=\"infoBoxHeading\" align=\"left\" width=\"100\" style=\"padding: 16px 0;border-bottom:1px solid #666;\">
					<strong>$vartotal</strong>
				</td>
			</tr>";
$style=true;
for($j=0;$j<count($products_name);$j++){
	$style=!$style;
	
	if($style)$steil="productListing-odd";
	else $steil="productListing-even";
$html_email_order .= "
			<tr class=\"$steil\" style=\"min-height:60px\">
				";
$html_email_order .= "
				". $products_name[$j];
$html_email_order .= "
				". $products_model[$j];
$html_email_order .= "
				". $products_quantity[$j];
$html_email_order .= "
				".$products_price[$j];
$html_email_order .= "
			</tr>";

if(substr($products_model[$j],41,3)=="ccb"){
	$ccbid=trim(substr($products_model[$j],41,8));
	$html_email_order .= "
			<tr class=\"$steil\">
				<td width=\"100\" valign=\"top\" align=\"left\" class=\"infoBoxContents\" style=\"border:none;\">
					Mischung:
				</td>
				<td colspan=\"3\" width=\"300\" valign=\"top\" align=\"right\" class=\"infoBoxContents\" style=\"border:none;\">
					" . $Varccb[$ccbid] . "
				</td>
			</tr>";	
	}
}

$html_email_order .= "  
			<tr height=\"14\">
				<td height=\"14\" width=\"300\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">
					" . push_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>',$Vardetail) . "
				</td>
				<td colspan=\"3\" width=\"300\" valign=\"top\" align=\"right\" class=\"infoBoxContents\">
				" . push_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>',$Vartaxe) . " 
				</td>
			</tr>
	
		</table>";

$html_email_order .= "
		<table width=\"640\"  border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"background-color:#ffffff;margin-left:30px;border:none;\">
			<tr height=\"24\">
				<td colspan=\"2\" width=\"600\" height=\"24\" style=\"background-color:#ffffff;\">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td width=\"180\" valign=\"top\" class=\"infoBoxHeading\" align=\"left\" style=\"background-color:#ffffff;\">
					<strong>$vardelivery</strong>
				</td>
				<td width=\"460\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">
					".push_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>',$Varaddress) ."
				</td>
			</tr>
			<tr class=\"table\">
				<td width=\"180\" valign=\"top\" class=\"infoBoxHeading\" align=\"left\" style=\"background-color:#ffffff;\">
					<strong>$varbilling</strong>
				</td>
				<td width=\"460\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">
					". push_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>',$Varadpay) ."
				</td>
			</tr>
			<tr height=\"14\">
				<td colspan=\"2\" height=\"14\">
					&nbsp;
				</td>
			</tr>
		</table>";

$html_email_order .= "
		<table width=\"640\"  border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"background-color:#ffffff;margin-left:30px;border:none;\">
			<tr>
				<td  valign=\"top\" width=\"180\" align=\"left\" style=\"background-color:#ffffff;\">
					<strong>". push_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>',$varpaymethod) ."</strong>
				</td>
				<td  valign=\"top\" align=\"left\" >
					". push_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>',$Varmodpay)."<br>
				</td>
			</tr>
			<tr height=\"14\">
				<td height=\"14\">&nbsp;</td>
			</tr>
		</table>";

$html_email_order .= "
		<table width=\"640\"  border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"background-color:#ffffff;margin-left:30px;\">
			<tr height=\"14\">
				<td height=\"14\">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td class=\"infoBoxContents\">
					$Varmailfooter1<br>
					$Varmailfooter2
				</td>
			</tr>
			<tr height=\"14\">
				<td height=\"14\">
					&nbsp;
				</td>
			</tr>
		</table>";

$html_email_order .= "
		</td>
	</tr>
</table>
</body>
</html>";
?>