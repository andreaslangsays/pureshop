<table border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td colspan=2>
<p class="bigheader">Vielfalt F&uuml;r Genie&szlig;er</p>
<P class="aufmacher" style="margin-top:3px;padding-top:0px;text-align:center;"><FONT face="Verdana, Arial, Helvetica, sans-serif"><small>
Auf unseren Seiten finden Sie ein vielf&auml;ltiges Angebot an erlesenen Kakao- und Teesorten, K&ouml;stliches
aus der Welt der Konfiserie wie Kakaos und Schokoladen sowie Accessoires f&uuml;r den stilvollen Genu&szlig;
unserer frisch ger&ouml;steten Kakaos und unserer edlen Tees.
Alle Produkte k&ouml;nnen Sie direkt bestellen. Klicken Sie dazu lediglich auf das Warenkorbsymbol neben
dem jeweiligen Produkt.
Falls Sie eine kurze Einf&uuml;hrung w&uuml;nschen, halten wir f&uuml;r Sie unsere Hilfe zum Shop bereit.</small></FONT>
<br>
<?PHP
$m_breite=567;
$m_hoehe=300;
$sidow=$_GET["osCzid"];
if (strlen($sidow)< 2){
	$sidow="rokoko";
}

?>
 <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="567" height="300" id="carousel2" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="carousel2.swf?sid=<?PHP echo $sidow; ?>" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffcc99" />
<embed src="carousel2.swf?sid=<?PHP echo $sidow; ?>" quality="high" bgcolor="#ffcc99" width="567" height="300" name="carousel2" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</P>
<p style="font:Verdana Arial Helvetica sans-serif;text-align:center;width:480px;margin-left:50px;">
<a href="<?php echo  push_href_link("ccb-app.php"); ?>">
<img src="ccb/icons/ccb.gif" border="0" align="right">
<strong>Erstellen Sie online Ihre individuelle Kakaomischung!</strong><br />
Eines der streng geh&uuml;teten Geheimnisse rund um den Kakao besteht in der Mischung der unterschiedlichen Sorten.<br>
Kreieren Sie hier selbst Ihren HausKakao - stellen Sie aus 16 Kakaosorten Ihre Mischung zusammen und genie&szlig;en Sie einen ganz pers&ouml;nlichen Kakao.</a>
</p>
    </td>
  </tr>
  <tr><td colspan="2" align="center">
 <?PHP //include'tips.php'; ?>
</td>
</tr>
</table>
