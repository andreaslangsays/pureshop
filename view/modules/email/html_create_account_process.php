<?php
$html_email_text = "<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
</head>";

$html_email_text .= "
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
			a:link,
			a{
				color:#6c2c21;
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
			color:#666;
			}
			p{
			margin:12px 0;
			}
			strong{
			margin-right:20px;
			display:inline-block;
			color:#666;
			}
			small{
			font-size:10px;
			}
		</style>
<table id=\"mailroot\" style=\"width:690px;margin: 0 auto;padding-left:30px;padding-right:20px;background-repeat:repeat-x;background-image:url('http://www.Bruesselser-Kakaoroesterei.de/shop/images/newbkr/mailbg.png') !important;font-size:13px;font-family: Verdana, 'Lucida Sans', Tahoma, Arial, 'Segoe UI', sans-serif;\">
	<tr>
		<td>
<!-- start content tables -->
		<table width=\"640\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style='margin-left:30px;'>
			<tr>
				<td height=\"120\" style=\"vertical-align:middle;\" valign=\"top\" colspan=\"2\">$Varlogo
				</td>
			</tr>
			<tr>
				<td style=\"color:#ffffff;height:50px;vertical-align:middle;font-family: Verdana, 'Lucida Sans', Tahoma, Arial, 'Segoe UI', sans-serif;\" valign=\"top\" colspan=\"2\">
					<a href=\"http://www.Bruesselser-Kakaoroesterei.de\" style=\"float:right;margin-right:30px;color:#FFF;font-size:10px;text-decoration:none;\">www.Bruesselser-Kakaoroesterei.de</a> ++  $Actualmail  ++ 
				</td>
			</tr>";
//here goes the greeting
$html_email_text .= "
			<tr>
				<td class=\"main\">
					$Vargendertext
					<br><br>$Vartextmail 
				</td>
				<td style='font-size:13px;font-family: Verdana, \"Lucida Sans\", Tahoma, Arial, \"Segoe UI\", sans-serif;width:220px;padding-top:40px;'>
								<div style=\"background-image:url(http://www.Bruesselser-Kakaoroesterei.de/shop/images/newbkr/boxBackground.gif);padding:10px;width:auto;overflow:hidden;\">
									<img src='http://www.Bruesselser-Kakaoroesterei.de/shop/images/newbkr/speaker.png' border='0'>
									<div style='font-size:13px;padding:10px;color:#E09F46;background-color:#fff !important;'>
										Fragen?<br> 
										+49 (0)30 - 886 779 20&#42;<br>
										Mo-Fr 8-18 Uhr
										<br>
										$Varaccountlink
									</div>
								</div>
				</td>
			</tr>
			<tr>
				<td valign=\"top\" colspan=\"2\" style=\"border-top:1px solid #666;border-bottom:1px solid #666;\">
					$Varmailfooter<br>
					$Varmailfooter2
				</td>
			</tr>
			<tr height=\"14\">
				<td height=\"14\" valign=\"top\" colspan=\"2\">
					&nbsp;
				</td>
			</tr>
			<tr bgcolor=$trcolor>
				<td valign=\"top\" colspan=\"2\">
					&nbsp;
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>
</html>";
?>