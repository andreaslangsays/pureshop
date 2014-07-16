<?php
$html_email_order  = "<html>";

$html_email_order .= "<head>";
$html_email_order .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"> ";
$html_email_order .= "$Varhttp ";
$html_email_order .= "$Varstyle ";
$html_email_order .= "</head>";

$html_email_order .= "<body>";
$html_email_order .= "<div align=\"center\">";
$html_email_order .= "<table width=\"600\"  border=\"0\" cellpadding=\"2\" cellspacing=\"2\">";
$html_email_order .= "<tr><td><div align=\"center\"> $Varlogo </div></td></tr>";
$html_email_order .= "<tr><td>";

$html_email_order .= "<table width=\"600\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#F9F9F9\">";
$html_email_order .= "<tr> ";
$html_email_order .= "<td>";

$html_email_order .= "$vartable1";
$html_email_order .= "<tr> ";
$html_email_order .= "<td></td>";
$html_email_order .= "</tr>";
$html_email_order .= "</table>";

$html_email_order .= "</td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr>";
$html_email_order .= "  <td class=\"infoBoxContents\">$Vartext1</td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td>&nbsp;</td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td>$varmessage</td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td><br><u>$varcategory</u>";
$html_email_order .= "  </td>";
$html_email_order .= "</tr >";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td><br>$varuser";
$html_email_order .= "  </td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td>$Varcustomer</td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\">";
$html_email_order .= "  <td>$varcomments<br>";
$html_email_order .= "    <blockquote> $varinfocomments </blockquote>";
$html_email_order .= "  </td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td class=\"infoBoxContents\">$vardatecommande </td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td class=\"infoBoxContents\">$Vartext2<br></td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td class=\"infoBoxContents\"><div align=\"center\"><br>$varimage</div></td>";
$html_email_order .= "</tr>";

$html_email_order .= "<tr class=\"infoBoxContents\"> ";
$html_email_order .= "  <td class=\"infoBoxContents\">";

$html_email_order .= "$vartable2" ;
$html_email_order .= "  <tr class=\"infoBoxContents\"> ";
$html_email_order .= "    <td class=\"infoBoxContents\"></td>";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr> ";
$html_email_order .= "    <td></td>";
$html_email_order .= "  </tr>";
$html_email_order .= "</table>";

$html_email_order .= "<table width=\"600\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=white>";
$html_email_order .= "  <tr> ";
$html_email_order .= "    <td><br></td>";
$html_email_order .= "  </tr>";
$html_email_order .= "</table>";

$html_email_order .= "<table width=\"600\"  border=\"0\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"#F9F9F9\">";
$html_email_order .= "  <tr> ";
$html_email_order .= "    <td width=\"300\"><?php echo push_draw_separator('pixel_trans.gif', '300', '1'); ?></td>";
$html_email_order .= "    <td width=\"160\"><?php echo push_draw_separator('pixel_trans.gif', '160', '1'); ?></td>";
$html_email_order .= "    <td width=\"40\"><?php echo push_draw_separator('pixel_trans.gif', '40', '1'); ?></td>";
$html_email_order .= "    <td width=\"100\"><?php echo push_draw_separator('pixel_trans.gif', '100', '1'); ?></td>";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr bordercolor=\"#FFFFFF\" bgcolor=\"#000066\">";
$html_email_order .= "    <td class=\"infoBoxHeading\" align=\"center\" width=\"300\"><font color=\"#ffffff\"><strong>$varproducts</strong></font></td>";
$html_email_order .= "    <td class=\"infoBoxHeading\" align=\"center\" width=\"160\"><font color=\"#ffffff\"><strong>$varmodel</strong></font></td>";
$html_email_order .= "    <td class=\"infoBoxHeading\" align=\"center\" width=\"40\"><font color=\"#ffffff\"><strong>$varqty</strong></font></td>";
$html_email_order .= "    <td class=\"infoBoxHeading\" align=\"center\" width=\"100\"><font color=\"#ffffff\"><strong>$vartotal</strong></font></td>";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr> ";
$html_email_order .= "    <td width=\"300\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">$products_name</td>";
$html_email_order .= "    <td width=\"160\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">$products_model</td>";
$html_email_order .= "    <td width=\"40\"  valign=\"top\" align=\"center\" class=\"infoBoxContents\">$products_quantity</td>";
$html_email_order .= "    <td width=\"100\" valign=\"top\" align=\"right\" class=\"infoBoxContents\">$products_price</td>";
$html_email_order .= "  </tr>";
//BOF CCB_MOD
if($mischungen)
{
$html_email_order .= "  <tr > ";
$html_email_order .= "    <td width=\"100\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">Mischungen:</td>";
$html_email_order .= "    <td colspan=\"3\" width=\"300\" valign=\"top\" align=\"right\" class=\"infoBoxContents\">$Varccb</td>";
$html_email_order .= "  </tr>";
}
//EOF CCB_MOD
$html_email_order .= "  <tr height=\"14\"> ";
$html_email_order .= "    <td height=\"14\" width=\"300\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">$Vardetail</td>";
$html_email_order .= "    <td colspan=\"3\" width=\"300\" valign=\"top\" align=\"right\" class=\"infoBoxContents\">$Vartaxe</td>";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr height=\"14\"> ";
$html_email_order .= "    <td colspan=\"4\" width=\"600\" height=\"14\">&nbsp;</td>";
$html_email_order .= "  </tr>";
$html_email_order .= "</table>";

$html_email_order .= "<table width=\"600\"  border=\"0\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"#F9F9F9\">";
$html_email_order .= "  <tr bordercolor=\"#FFFFFF\" bgcolor=\"#000066\"> ";
$html_email_order .= "    <td width=\"300\" class=\"infoBoxHeading\" align=\"center\"> <font color=\"#FFFFFF\"><strong>$vardelivery</strong></font></td>";
$html_email_order .= "    <td width=\"300\" class=\"infoBoxHeading\" align=\"center\"> <font color=\"#FFFFFF\"><strong>$varbilling</strong></font></td>";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr class=\"table\"> ";
$html_email_order .= "    <td width=\"300\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">$Varaddress</td>";
$html_email_order .= "    <td width=\"300\" valign=\"top\" align=\"left\" class=\"infoBoxContents\">$Varadpay</td> ";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr height=\"14\"> ";
$html_email_order .= "    <td colspan=\"2\" height=\"14\">&nbsp;</td>";
$html_email_order .= "  </tr>";
$html_email_order .= "</table>";

$html_email_order .= "<table width=\"600\"  border=\"0\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=white>";
$html_email_order .= "  <tr class=\"infoBoxHeading\" bgcolor=\"#000066\">";
$html_email_order .= "    <td class=\"infoBoxHeading\" align=\"center\"><font color=\"#FFFFFF\"><strong>$varpaymethod </strong></font> </td>";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr> ";
$html_email_order .= "    <td class=\"infoBoxContents\"> ";
$html_email_order .= "      $Varmodpay ";
$html_email_order .= "    </td> ";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr height=\"14\"> ";
$html_email_order .= "    <td height=\"14\"></td>";
$html_email_order .= "  </tr>";
$html_email_order .= "</table>";

$html_email_order .= "<table width=\"600\"  border=\"0\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"#F9F9F9\">";
$html_email_order .= "  <tr height=\"14\"> ";
$html_email_order .= "    <td height=\"14\"></td>";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr> ";
$html_email_order .= "    <td class=\"infoBoxContents\">$Varmailfooter1<br>";
$html_email_order .= "       $Varmailfooter2</td> ";
$html_email_order .= "  </tr>";
$html_email_order .= "  <tr height=\"14\">";
$html_email_order .= "    <td height=\"14\">&nbsp;</td>";
$html_email_order .= "  </tr>";
$html_email_order .= "</table>";

$html_email_order .= "  </td>";
$html_email_order .= "</tr>";

$html_email_order .= "</table>";

$html_email_order .= "  </td>";
$html_email_order .= "</tr>";

$html_email_order .= "</table>";

$html_email_order .= "</div></body>";
$html_email_order .= "</html>";
?>