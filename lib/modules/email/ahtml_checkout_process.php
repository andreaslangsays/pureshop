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
<style type=\"text/css\"> 
			*{	
				margin:0;
				padding:0;
				background: transparent;
				font-family: Verdana, 'Lucida Sans', Tahoma, Arial, 'Segoe UI', sans-serif;
				font-size:13px;
				color: #4C4C4C;
				line-height: 20px;
				text-decoration: none;
			}
			
			body{
			background-color:#FFF;
			padding:0;
			margin:0;
			}
			
			table{
			font-size:13px;
			font-family: Verdana, 'Lucida Sans', Tahoma, Arial, 'Segoe UI', sans-serif;
			}
			
			table tr td h2{
			color:#666;
			font-weight:500;
			font-size:24px;
			}
			
			.infoBoxContents{
			padding: 0 10px;
			}
			
			#mailroot td{
			padding:0;
			vertical-align:top;
			}
			
			.orderdetails h2{
			padding-top:40px;
			padding-bottom:20px;
			}
			
			tr.infoBoxContents{
			height:26px;
			}
			
			table tr td b {
			width:100px;
			margin-right:20px;
			display:inline-block;
			color:#666;
			}
			
			strong{
			margin-right:20px;
			display:inline-block;
			color:#666;
			}
		</style>
<table id=\"mailroot\" style=\"width:690px;margin: 0 auto;padding-left:30px;padding-right:20px;background-repeat:repeat-x;background-image:url('http://www.Bruesselser-Kakaoroesterei.de/shop/images/newbkr/mailbg.png') !important;font-size:13px;font-family: Verdana, 'Lucida Sans', Tahoma, Arial, 'Segoe UI', sans-serif;\">
	<tr>
		<td>
<!-- start content tables -->
		<table width=\"640\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style='margin-left:30px;'>
			<tr>
				<td height=\"120\" style=\"vertical-align:middle;\">$Varlogo
				</td>
			</tr>
			<tr>
				<td style=\"color:#ffffff;height:50px;vertical-align:middle;font-family: Verdana, 'Lucida Sans', Tahoma, Arial, 'Segoe UI', sans-serif;\">
					<a href=\"http://www.Bruesselser-Kakaoroesterei.de\" style=\"float:right;margin-right:30px;color:#FFF;font-size:10px;text-decoration:none;\">www.Bruesselser-Kakaoroesterei.de</a> ++  $Actualmail  ++ 
				</td>
			</tr>";

//here goes the greeting bla
$html_email_order .= "
			<tr>
				<td style=\"padding-top:40px;font-size:13px;font-family: Verdana, 'Lucida Sans', Tahoma, Arial, 'Segoe UI', sans-serif;\">
					<table border=\"0\">
						<tr>
							<td style='width:440px;overflow:hidden;padding-right:40px'>
								<h2>$Varhead1</h2>
								<h3>$Vartext1</h3>
								<p style='font-size:13px;color:#666;'>$varmessage</p>
							</td>
							<td style='font-size:13px;font-family: Verdana, \"Lucida Sans\", Tahoma, Arial, \"Segoe UI\", sans-serif;width:220px;'>
								<div style=\"background-image:url(http://www.Bruesselser-Kakaoroesterei.de/shop/images/newbkr/boxBackground.gif);padding:10px;width:auto;overflow:hidden;\">
									<img src='http://www.Bruesselser-Kakaoroesterei.de/shop/images/newbkr/speaker.png' border='0'>
									<div style='font-size:13px;padding:10px;color:#E09F46;background-color:#fff !important;'>
										Fragen?<br> 
										+49 (0)30 - 886 779 20&#42;<br>
										Mo-Fr 8-18 Uhr
									</div>
								</div>
							</td>
						</tr>
					</table>
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
					" .push_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>',$Vardetail) ."
				</td>
				<td colspan=\"3\" width=\"300\" valign=\"top\" align=\"right\" class=\"infoBoxContents\">
					$Vartaxe 
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