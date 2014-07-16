<?php
/** Ähem
***/

if (isset($_POST['submit'])) {
	if (isset($_POST['webseite-allgemein']) || isset($_POST['eigensch-navi']) || isset($_POST['eigensch-uebersichtligkeit'])
			|| isset($_POST['eigensch-zahlungsarten']) || isset($_POST['eigensch-sonderangebote']) || isset($_POST['eigensch-produktauswahl'])
			|| isset($_POST['eigensch-produktqualitaet']) || isset($_POST['eigensch-produktpreise']) || isset($_POST['eigensch-produkte'])
			|| isset($_POST['eigensch-geschwindigkeit']) || isset($_POST['eigensch-darstellung']) || isset($_POST['eigensch-suche'])
			|| isset($_POST['eigensch-kundenservice']) || isset($_POST['vergleich-allgemein']) || isset($_POST['vergleich-navi']) 
			|| isset($_POST['vergleich-uebersichtligkeit']) || isset($_POST['vergleich-zahlungsarten']) || isset($_POST['vergleich-sonderangebote']) 
			|| isset($_POST['vergleich-produktauswahl'])	|| isset($_POST['vergleich-produktqualitaet']) || isset($_POST['vergleich-produktpreise']) 
			|| isset($_POST['vergleich-produkte']) || isset($_POST['vergleich-geschwindigkeit']) || isset($_POST['vergleich-darstellung']) 
			|| isset($_POST['vergleich-suche']) || isset($_POST['vergleich-kundenservice']) || isset($_POST['gesucht-gefunden'])
			|| !empty($_POST['wuensche']) || isset($_POST['probleme']) || !empty($_POST['probleme-text'])
			|| !empty($_POST['i-like-it']) || isset($_POST['empfehlen']) || isset($_POST['haeufigkeit'])) {
		
		$message = '<html>
						<head>
							<title>Kundenfeedback</title>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						</head>
						<body>
							Kunde: ' . $customer->customers_firstname . ' ' . $customer->customers_lastname . ' (' . $customer->customers_id . ')<br><br>
							<table border="1" cellpadding="3" cellspacing="1">
								<tr>
									<td>Wie zufrieden sind Sie mit unserer Webseite im Allgemeinen?</td>
									<td>' . $_POST['webseite-allgemein'] . '</td>
								</tr>
								<tr>
									<td colspan="2">Welche Eigenschaften sind Ihnen beim Besuch von Webseiten wichtig?</td>
								</tr>
								<tr>
									<td>Einfache Navigation</td>
									<td>' . $_POST['eigensch-navi'] . '</td>
								</tr>
								<tr>
									<td>Übersichtligkeit</td>
									<td>' . $_POST['eigensch-uebersichtligkeit'] . '</td>
								</tr>
								<tr>
									<td>Auswahl verschiedener Zahlungsarten</td>
									<td>' . $_POST['eigensch-zahlungsarten'] . '</td>
								</tr>
								<tr>
									<td>Sonderangebote</td>
									<td>' . $_POST['eigensch-sonderangebote'] . '</td>
								</tr>
								<tr>
									<td>Produktauswahl</td>
									<td>' . $_POST['eigensch-produktauswahl'] . '</td>
								</tr>
								<tr>
									<td>Produktqualität</td>
									<td>' . $_POST['eigensch-produktqualitaet'] . '</td>
								</tr>
								<tr>
									<td>Produktpreise</td>
									<td>' . $_POST['eigensch-produktpreise'] . '</td>
								</tr>
								<tr>
									<td>Informationen zu Produkten</td>
									<td>' . $_POST['eigensch-produkte'] . '</td>
								</tr>
								<tr>
									<td>Geschwindigkeit</td>
									<td>' . $_POST['eigensch-geschwindigkeit'] . '</td>
								</tr>
								<tr>
									<td>Grafische Darstellung</td>
									<td>' . $_POST['eigensch-darstellung'] . '</td>
								</tr>
								<tr>
									<td>Suche</td>
									<td>' . $_POST['eigensch-suche'] . '</td>
								</tr>
								<tr>
									<td>Kundenservice</td>
									<td>' . $_POST['eigensch-kundenservice'] . '</td>
								</tr>
								<tr>
									<td colspan="2">Wie bewerten Sie unsere Webseite im Vergleich zu anderen Webseiten, auf denen Sie schon bestellt haben?</td>
								</tr>
								<tr>
									<td>Einfache Navigation</td>
									<td>' . $_POST['vergleich-navi'] . '</td>
								</tr>
								<tr>
									<td>Übersichtligkeit</td>
									<td>' . $_POST['vergleich-uebersichtligkeit'] . '</td>
								</tr>
								<tr>
									<td>Auswahl verschiedener Zahlungsarten</td>
									<td>' . $_POST['vergleich-zahlungsarten'] . '</td>
								</tr>
								<tr>
									<td>Sonderangebote</td>
									<td>' . $_POST['vergleich-sonderangebote'] . '</td>
								</tr>
								<tr>
									<td>Produktauswahl</td>
									<td>' . $_POST['vergleich-produktauswahl'] . '</td>
								</tr>
								<tr>
									<td>Produktqualität</td>
									<td>' . $_POST['vergleich-produktqualitaet'] . '</td>
								</tr>
								<tr>
									<td>Produktpreise</td>
									<td>' . $_POST['vergleich-produktpreise'] . '</td>
								</tr>
								<tr>
									<td>Informationen zu Produkten</td>
									<td>' . $_POST['vergleich-produkte'] . '</td>
								</tr>
								<tr>
									<td>Geschwindigkeit</td>
									<td>' . $_POST['vergleich-geschwindigkeit'] . '</td>
								</tr>
								<tr>
									<td>Grafische Darstellung</td>
									<td>' . $_POST['vergleich-darstellung'] . '</td>
								</tr>
								<tr>
									<td>Suche</td>
									<td>' . $_POST['vergleich-suche'] . '</td>
								</tr>
								<tr>
									<td>Kundenservice</td>
									<td>' . $_POST['vergleich-kundenservice'] . '</td>
								</tr>
								<tr>
									<td>Haben Sie alle Informationen, die Sie gesucht haben, gefunden?</td>
									<td>' . $_POST['gesucht-gefunden'] . '</td>
								</tr>
								<tr>
									<td>Welche zusätzlichen Inhalte oder Funktionen wünschen Sie sich auf dieser Webseite?</td>
									<td>' . $_POST['wuensche'] . '</td>
								</tr>
								<tr>
									<td>Traten Probleme während Ihres Besuchs auf www.if-bi.com auf?</td>
									<td>' . $_POST['probleme'] . '</td>
								</tr>
								<tr>
									<td></td>
									<td>' . $_POST['probleme-text'] . '</td>
								</tr>
								<tr>
									<td>Was gefällt Ihnen an unserer Internetseite gut?</td>
									<td>' . $_POST['i-like-it'] . '</td>
								</tr>
								<tr>
									<td>Würden Sie unser Webseite weiter empfehlen?</td>
									<td>' . $_POST['empfehlen'] . '</td>
								</tr>
								<tr>
									<td>Wie häufig besuchen Sie www.if-bi.com?</td>
									<td>' . $_POST['haeufigkeit'] . '</td>
								</tr>
								<tr>
									<td>Bitte geben Sie Ihr Geschlecht an</td>
									<td>' . $_POST['geschlecht'] . '</td>
								</tr>
								<tr>
									<td>Bitte geben Sie Ihr Alter an</td>
									<td>' . $_POST['alter'] . '</td>
								</tr>
							</table>
						</body>
					</html>';		
		
		push_mail('push', 'kontakt@if-bi.com', 'Kundenfeedback', $message,  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		push_mail('push', 'mk@if-bi.com', 'Kundenfeedback', $message,  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);			
		push_mail('push', 'loominpn@gmail.com', 'Kundenfeedback', $message,  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	
		
		$required_fields_set = true;			
	} else {
		$required_fields_set = false;
	}				
}

if (isset($_POST['submit-email']) && isset($_POST['name']) && isset($_POST['email'])) {
	
	$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
				<HTML>
					<HEAD>
						<META content="text/html; charset=utf-8" http-equiv=Content-Type>
						<META name=GENERATOR content="MSHTML 9.00.8112.16437">
					</HEAD>
				<BODY>
					<Div style="width:620px">
						<DIV style="margin-bottom:20px; margin-top:20px">
							<IMG src="http://if-bi.com/shop/images/push/push-logo.png"><span>&nbsp;&nbsp;&nbsp;</span>
							<FONT color=#1179cb face="Myriad Pro, Arial, Helvetica, sans-serif">TASTE IT. LOVE IT. SHOP IT.</FONT>
						</DIV>
					<!-- Start MSG-->
						<DIV style="border:solid #ccc 1px; overflow:hidden; padding:20px; font-size:13px; line-height:1.5">
							<DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif"><b>Ein Freund möchte Ihnen eine Webseite zeigen</b></FONT></DIV>
						  <DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
						  <DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">' . $_POST['name'] . ' schreibt:</FONT></DIV>
							<P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">' . $_POST['email-text'] . '</FONT></P>
							<P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">&nbsp;</FONT></P>
					
					<!--Start Box-->      
						<div style="position:relative; overflow:hidden; font-size:13px; line-height:1.5; border:solid 1px #CCC; background-color:#f5f5f5; padding:5px">
							<table border="0" cellpadding="0" cellspacing="0" width="560" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; font-size:13px; color:#333; line-height:1.5">
								<tr>
									<td width="170"><a href="http://www.if-bi.com" style="color:#1179bc; text-decoration:none; max-height:150; max-width:150; margin-right:20; text-align:left; vertical-align:top" target="_blank"><IMG src="http://www.if-bi.com/shop/images/push/push_startseite.jpg" style="border:1px solid; border-color:#ccc; background-color:#fff; padding:10px; margin-right: 10px"></a>
									</td>
									<td style="vertical-align:top">
										<p><a href="http://www.if-bi.com" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; color:#1179cb; text-decoration:none" target="_blank"><b>www.if-bi.com</b></a>
										</p>
										<p>KOFFEIN FÜR IHR GESCHÄFT - Bei uns bekommen Gastronomen, Kaufleute und gewerbliche Gastgeber nicht nur eine Vielzahl profitabler Produkte, sondern auch zahlreiche Ideen rund um Chai, Tee, Schokolade und sommerliche Trendgetränke. Besuchen Sie unseren Shop!
										</p>
										<p><a href="http://www.if-bi.com" target="_blank" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; color:#fff; text-decoration:underline" target="_blank"><IMG src="http://www.if-bi.com/shop/images/push/zum_push_shop_btn.png" style="border:0px;"></a>
										</p>
									</td>
								</tr>
							</table>
						</div>
					<!--End Box-->
					
						</DIV>  
					<!--EOM-->
					
					
					<!-- Start Contact  
						<DIV>
						  <DIV style="border:solid 1px #88bde5; background-color:#e7f2fa; padding:10px; margin-top:20px; margin-bottom:20px; font-size:11px; line-height:1.5">
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">push Int’l Food &amp; Beverage Import GmbH<BR>
							  Mainstraße 171/172, C-56873 Bruessels<BR>
							  <a href="http://www.if-bi.com" style="color:#1179bc; text-decoration:none" target="_blank">www.if-bi.com</a></FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Telefon: +49 (0)30 28 47 00-0, 
							  Telefax: +49 (0)30 28 47 00-77, E-Mail: kontakt@if-bi.com</FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Handelsregister: Amtsgericht 
							  Charlottenburg, Handelsreg.-Nr.: HRB 94602B<BR>
							  VAT-ID: 
							  DE239223851<BR>
							  Geschäftsführer: Matthias Gladiatory, Andreas Heroe, Stefan 
							  Richter</FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Bankverbindung: 
							  Commerzbank<BR>
							  Bankleitzahl: 100 400 00, Kontonummer: 51 63 05 000 <BR>
							  BIC: 
							  COBADEFF, IBAN: DE87 1004 0000 0516 3050 00</FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Öko-Kontrollstelle: 
							  DE-ÖKO-044</FONT></DIV>
						  </DIV>
						</DIV>
					<!-- End Contact
					
					<!-- Start Disclaimer
						<div style="font-size:11px; line-height:1.5; padding-left:10px; padding-right:10px; margin-bottom:20px">
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Achtung!</FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Diese email kann Betriebs- und 
							  Geschäftsgeheimnisse, dem Anwaltsgeheimnis unterliegende oder sonstige 
							  vertrauliche Informationen enthalten. Sollten Sie diese email irrtümlich 
							  erhalten haben, ist Ihnen eine Kenntnisnahme des Inhalts, eine Vervielfältigung 
							  oder Weitergabe der email ausdrücklich untersagt. Bitte benachrichtigen Sie uns 
							  und vernichten Sie die email. <BR>
							  Der Absender hat alle erdenklichen 
							  Vorsichtsmaßnahmen getroffen, dass die Anlagen dieser eMail frei von 
							  Computerviren o.ä. sind. Gleichwohl schließen wir die Haftung für jeden Schaden 
							  aus, der durch Computerviren o.ä. verursacht wurde, soweit wir nicht vorsätzlich 
							  oder grob fahrlässig gehandelt haben. Wir raten Ihnen, dass Sie in jedem Fall 
							  Ihre eigene Virenprüfung vornehmen, bevor Sie die Anlagen öffnen. Vielen Dank </FONT></DIV>
							<DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
							<DIV><FONT face="Myriad Pro, Arial, Helvetica, sans-serif"><FONT color="#666">Important!! <BR>
							  The information contained in this email message may be confidential 
							  information, and may also be the subject of legal professional privilege. If you 
							  are not the intended recipient, any use, interference with, disclosure or 
							  copying of this material is unauthorised and prohibited. Please inform us 
							  immediately and destroy the email. <BR>
							  We have taken every reasonable precaution 
							  to ensure that any attachment to this eMail has been swept for viruses. However, 
							  we cannot accept liability for any damage sustained as a result of software 
							  viruses and would advice that you carry out your own virus checks before opening 
							  any attachment. Thank you for your cooperation</FONT> </FONT></DIV>
						</div>
					<!-- End Disclaimer-->
					</DIV>
					<?php echo EMAIL_FOOTER_OFFICIAL; ?>
				</BODY>
			</HTML>';
				
	$emails = explode(",", $_POST['email']);
	foreach ($emails as $email) {	
		push_mail('', trim($email), 'Ein Freund möchte Ihnen eine Webseite zeigen', $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);					
	}
	
	$friend_email_sent = true;
}
?>

<?php if (isset($required_fields_set)) { ?>
	<?php if ($required_fields_set) { ?>
		<div class="grid_8 alpha omega tx_13_15" style="border: 1px solid #99cc00; background-color: #fbffea; padding: 10px">
			<img style="vertical-align: middle; margin-right: 5px" src="images/push/icons/ico_true_S-green.png" />
			<strong style="color: #99cc00">Herzlichen Dank für Ihr Feedback!</strong>
			<?php if (isset($_POST['empfehlen']) && $_POST['empfehlen'] == "Ja") { ?>
				<br /><br />
				<strong>Möchten Sie uns weiter empfehlen?</strong><br />
				Schreiben Sie Ihren Freunden und Bekannten eine E-Mail oder zeigen Sie auf Facebook, dass Sie uns mögen.<br /><br />
				<div style="display: inline-block; margin-right: 5px">
					<img style="vertical-align: middle; margin-right: 3px; cursor: pointer" src="images/push/icons/email-button.png" onClick="$('.feedback-email-popup').show()" />
				</div>	
				<div class="fb-like" data-href="http://www.if-bi.com" data-width="The pixel width of the plugin" data-height="20" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="true" style="top: 8px"></div>
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
			<?php } ?>
		</div>
		<div class="clearfix"></div><br />
	<?php } else { ?>
		<div class="grid_8 alpha omega tx_12_15 tx_red red-border" style="background-color: #FFEAEA; padding: 10px">
			<img style="vertical-align: middle; margin-right: 5px" src="images/push/icons/ico_false_S-red.png" />
			Bitte beantworten Sie mindestens eine Frage. Über einen komplett ausgefüllten Fragebogen freuen wir uns natürlich am meisten. Vielen dank.
		</div>
		<div class="clearfix"></div><br />
	<?php } ?>
<?php } ?>
 
<h1>Ihre Meinung ist uns wichtig</h1>

<div class="grid_8 alpha tx_13_20">
	<strong>Mit Ihrem Feedback helfen Sie uns, unseren Onlineservice zu verbessern.</strong> Wir würden uns freuen, wenn Sie sich kurz Zeit nehmen würden und unten stehenden Feedbackbogen ausfüllen. Ihre Daten und Angaben werden selbstverständlich vertraulich behandelt. Vielen Dank!
</div>
<div class="clearfix"></div>

<p class="grid_12 alpha omega tx_15_20 bottom_border" style="padding: 16px 0">Und los gehts:</p>

<form method="post">
	<p class="tx_15_20">Wie zufrieden sind Sie mit unserer Webseite im Allgemeinen?</p>
	<div class="feedback-radio-list tx_13_15 feedback-answers">
		<input type="radio" name="webseite-allgemein" value="Sehr zufrieden" /> Sehr zufrieden<br />
		<input type="radio" name="webseite-allgemein" value="Zufrieden" /> Zufrieden<br />
		<input type="radio" name="webseite-allgemein" value="Unzufrieden" /> Unzufrieden<br />
		<input type="radio" name="webseite-allgemein" value="Sehr unzufrieden" /> Sehr unzufrieden<br />
		<input type="radio" name="webseite-allgemein" value="Weiß nicht" /> Weiß nicht
	</div>
	
	<p class="tx_15_20">Welche Eigenschaften sind Ihnen beim Besuch von Webseiten wichtig?</p>
	<div class="feedback-matrix tx_13_15 feedback-answers">
		<div class="row">
			<div>&nbsp;</div>
			<div>Sehr wichtig</div>
			<div>Wichtig</div>
			<div>Mittel</div>
			<div>Unwichtig</div>
		</div>
		<div class="row">
			<div>Einfache Navigation</div>
			<div><input type="radio" name="eigensch-navi" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-navi" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-navi" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-navi" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Übersichtligkeit</div>
			<div><input type="radio" name="eigensch-uebersichtligkeit" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-uebersichtligkeit" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-uebersichtligkeit" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-uebersichtligkeit" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Auswahl verschiedener Zahlungsarten</div>
			<div><input type="radio" name="eigensch-zahlungsarten" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-zahlungsarten" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-zahlungsarten" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-zahlungsarten" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Sonderangebote</div>
			<div><input type="radio" name="eigensch-sonderangebote" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-sonderangebote" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-sonderangebote" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-sonderangebote" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Produktauswahl</div>
			<div><input type="radio" name="eigensch-produktauswahl" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-produktauswahl" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-produktauswahl" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-produktauswahl" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Produktqualität</div>
			<div><input type="radio" name="eigensch-produktqualitaet" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-produktqualitaet" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-produktqualitaet" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-produktqualitaet" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Produktpreise</div>
			<div><input type="radio" name="eigensch-produktpreise" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-produktpreise" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-produktpreise" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-produktpreise" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Informationen zu Produkten</div>
			<div><input type="radio" name="eigensch-produkte" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-produkte" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-produkte" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-produkte" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Geschwindigkeit</div>
			<div><input type="radio" name="eigensch-geschwindigkeit" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-geschwindigkeit" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-geschwindigkeit" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-geschwindigkeit" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Grafische Darstellung</div>
			<div><input type="radio" name="eigensch-darstellung" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-darstellung" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-darstellung" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-darstellung" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Suche</div>
			<div><input type="radio" name="eigensch-suche" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-suche" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-suche" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-suche" value="Unwichtig" /></div>
		</div>
		<div class="row">
			<div>Kundenservice</div>
			<div><input type="radio" name="eigensch-kundenservice" value="Sehr wichtig" /></div>
			<div><input type="radio" name="eigensch-kundenservice" value="Wichtig" /></div>
			<div><input type="radio" name="eigensch-kundenservice" value="Mittel" /></div>
			<div><input type="radio" name="eigensch-kundenservice" value="Unwichtig" /></div>
		</div>
	</div>
	
	<p class="tx_15_20">Wie bewerten Sie unsere Webseite im Vergleich zu anderen Webseiten, auf denen Sie schon bestellt haben?</p>
	<div class="feedback-matrix tx_13_15 feedback-answers">
		<div class="row">
			<div>&nbsp;</div>
			<div>Viel besser</div>
			<div>Besser</div>
			<div>Ähnlich</div>
			<div>Schlechter</div>
			<div>Viel schlechter</div>
			<div>Keine Erfahrung</div>
		</div>
		<div class="row">
			<div>Einfache Navigation</div>
			<div><input type="radio" name="vergleich-navi" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-navi" value="Besser" /></div>
			<div><input type="radio" name="vergleich-navi" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-navi" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-navi" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-navi" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Übersichtligkeit</div>
			<div><input type="radio" name="vergleich-uebersichtligkeit" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-uebersichtligkeit" value="Besser" /></div>
			<div><input type="radio" name="vergleich-uebersichtligkeit" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-uebersichtligkeit" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-uebersichtligkeit" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-uebersichtligkeit" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Auswahl verschiedener Zahlungsarten</div>
			<div><input type="radio" name="vergleich-zahlungsarten" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-zahlungsarten" value="Besser" /></div>
			<div><input type="radio" name="vergleich-zahlungsarten" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-zahlungsarten" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-zahlungsarten" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-zahlungsarten" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Sonderangebote</div>
			<div><input type="radio" name="vergleich-sonderangebote" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-sonderangebote" value="Besser" /></div>
			<div><input type="radio" name="vergleich-sonderangebote" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-sonderangebote" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-sonderangebote" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-sonderangebote" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Produktauswahl</div>
			<div><input type="radio" name="vergleich-produktauswahl" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-produktauswahl" value="Besser" /></div>
			<div><input type="radio" name="vergleich-produktauswahl" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-produktauswahl" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-produktauswahl" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-produktauswahl" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Produktqualität</div>
			<div><input type="radio" name="vergleich-produktqualitaet" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-produktqualitaet" value="Besser" /></div>
			<div><input type="radio" name="vergleich-produktqualitaet" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-produktqualitaet" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-produktqualitaet" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-produktqualitaet" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Produktpreise</div>
			<div><input type="radio" name="vergleich-produktpreise" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-produktpreise" value="Besser" /></div>
			<div><input type="radio" name="vergleich-produktpreise" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-produktpreise" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-produktpreise" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-produktpreise" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Informationen zu Produkten</div>
			<div><input type="radio" name="vergleich-produkte" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-produkte" value="Besser" /></div>
			<div><input type="radio" name="vergleich-produkte" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-produkte" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-produkte" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-produkte" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Geschwindigkeit</div>
			<div><input type="radio" name="vergleich-geschwindigkeit" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-geschwindigkeit" value="Besser" /></div>
			<div><input type="radio" name="vergleich-geschwindigkeit" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-geschwindigkeit" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-geschwindigkeit" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-geschwindigkeit" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Grafische Darstellung</div>
			<div><input type="radio" name="vergleich-darstellung" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-darstellung" value="Besser" /></div>
			<div><input type="radio" name="vergleich-darstellung" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-darstellung" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-darstellung" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-darstellung" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Suche</div>
			<div><input type="radio" name="vergleich-suche" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-suche" value="Besser" /></div>
			<div><input type="radio" name="vergleich-suche" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-suche" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-suche" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-suche" value="Keine Erfahrung" /></div>
		</div>
		<div class="row">
			<div>Kundenservice</div>
			<div><input type="radio" name="vergleich-kundenservice" value="Viel besser" /></div>
			<div><input type="radio" name="vergleich-kundenservice" value="Besser" /></div>
			<div><input type="radio" name="vergleich-kundenservice" value="Ähnlich" /></div>
			<div><input type="radio" name="vergleich-kundenservice" value="Schlechter" /></div>
			<div><input type="radio" name="vergleich-kundenservice" value="Viel schlechter" /></div>
			<div><input type="radio" name="vergleich-kundenservice" value="Keine Erfahrung" /></div>
		</div>
	</div>
	
	<p class="tx_15_20">Haben Sie alle Informationen, die Sie gesucht haben, gefunden?</p>
	<div class="feedback-radio-list tx_13_15 feedback-answers">
		<input type="radio" name="gesucht-gefunden" value="Ja, alle" /> Ja, alle<br />
		<input type="radio" name="gesucht-gefunden" value="Fast alle" /> Fast alle<br />
		<input type="radio" name="gesucht-gefunden" value="Die meisten" /> Nur manche<br />
		<input type="radio" name="gesucht-gefunden" value="Nur manche" /> Nur manche<br />
		<input type="radio" name="gesucht-gefunden" value="Die wenigsten" /> Die wenigsten
	</div>
	
	<p class="tx_15_20">Welche zusätzlichen Inhalte oder Funktionen wünschen Sie sich auf dieser Webseite?</p>
	<div class="feedback-answers tx_13_15">
		<textarea name="wuensche" /></textarea>
	</div>
	
	<p class="tx_15_20">Traten Probleme während Ihres Besuchs auf www.if-bi.com auf?</p>
	<div class="feedback-radio-list tx_13_15 feedback-answers">
		<input type="radio" name="probleme" value="Nein" /> Nein<br />
		<input type="radio" name="probleme" value="Ja, folgende" /> Ja, folgende:<br /><br />
		<textarea name="probleme-text" /></textarea>
	</div>
	
	<p class="tx_15_20">Was gefällt Ihnen an unserer Internetseite gut?</p>
	<div class="feedback-answers tx_13_15">
		<textarea name="i-like-it" /></textarea>
	</div>
	
	<p class="tx_15_20">Würden Sie unser Webseite weiter empfehlen?</p>
	<div class="feedback-radio-list tx_13_15 feedback-answers">
		<input type="radio" name="empfehlen" value="Ja" /> Ja<br />
		<input type="radio" name="empfehlen" value="Nein" /> Nein
	</div>
	
	<p class="tx_15_20">Wie häufig besuchen Sie www.if-bi.com?</p>
	<div class="feedback-radio-list tx_13_15 feedback-answers">
		<input type="radio" name="haeufigkeit" value="Täglich" /> Täglich<br />
		<input type="radio" name="haeufigkeit" value="Wöchentlich" /> Wöchentlich<br />
		<input type="radio" name="haeufigkeit" value="Monatlich" /> Monatlich<br />
		<input type="radio" name="haeufigkeit" value="Quartalsweise oder seltener" /> Quartalsweise oder seltener
	</div>
	
	<p class="tx_15_20">Bitte geben Sie Ihr Geschlecht an</p>
	<div class="feedback-radio-list tx_13_15 feedback-answers">
		<input type="radio" name="geschlecht" value="Männlich" /> Männlich<br />
		<input type="radio" name="geschlecht" value="Weiblich" /> Weiblich<br />
		<input type="radio" name="geschlecht" value="Andere" /> Andere
	</div>
	
	<p class="tx_15_20">Bitte geben Sie Ihr Alter an</p>
	<div class="feedback-radio-list tx_13_15 feedback-answers">
		<input type="radio" name="alter" value="<20 Jahre" /> <20 Jahre<br />
		<input type="radio" name="alter" value="21-30 Jahre" /> 21-30 Jahre<br />
		<input type="radio" name="alter" value="31-40 Jahre" /> 31-40 Jahre<br />
		<input type="radio" name="alter" value="41-50 Jahre" /> 41-50 Jahre<br />
		<input type="radio" name="alter" value="51-60 Jahre" /> 51-60 Jahre<br />
		<input type="radio" name="alter" value=">60 Jahre" /> >60 Jahre
	</div>
	<br />
	
	<input class="submitBtn w130 darkblue tx_12_15" type="submit" name="submit" value="Feedback senden">
</form>

<?php if (isset($required_fields_set) && $required_fields_set == true) { ?>
<div id="prodToCart" class="feedback-email-popup" style="display: none">
	<div style="width: 380px; margin: 20px; background-color: #ffffff; padding: 15px 20px; display: relative">
		<img class="close-info-popup" src="images/push/close-info-popup.gif" style="top: 10px; right: 10px" onclick="$('.feedback-email-popup').hide()" />
		<h2 class="bottom_separator" style="width: 380px; padding-bottom: 20px; margin-bottom: 20px">Senden Sie Ihren Freunden per E-Mail einen Link auf unsere Webseite</h2>
		<form class="tx_13_15" method="post">
			<label for="name">Ihr Name</label><span class="tx_red">*</span><br />
			<input type="text" name="name" value="<?= $customer->customers_firstname . ' ' . $customer->customers_lastname ?>" style="width: 358px; border: 1px solid #999999; margin: 5px 0 15px; padding: 0 10px; height: 28px" />
			<label for="email">E-Mail-Adresse des Empfängers</label><span class="tx_red">*</span><br />
			<input type="text" name="email" style="width: 358px; border: 1px solid #999999; margin: 5px 0 0 0; padding: 0 10px; height: 28px" />
			<span class="tx_light_gray tx_12_15">Mehrere E-Mail-Adressen bitte mit Komma abtrennen.</span><br /><br />
			<span class="tx_blue" style="cursor: pointer" onclick="$('#email-text').slideDown()">Persönliche Nachricht hinzufügen?</span><br />
			<textarea id="email-text" name="email-text" style="display: none; margin: 5px 0 10px 0; border: 1px solid #999999; padding: 10px; width: 358px; height: 100px "></textarea><br />
			<div class="tx_right tx_light_gray tx_12_15"><span class="tx_red">*</span>Pflichtfelder</div>
			<input class="submitBtn w130 darkblue tx_12_15" type="submit" name="submit-email" value="E-Mail senden" style="margin-top: 20px">
		</form>
	</div>
</div>
<?php } ?>

<?php if (isset($friend_email_sent)) { ?>
	<div id="prodToCart" class="feedback-email-popup-success" style="display: block; top: 60%">
		<div style="width: 380px; margin: 20px; background-color: #ffffff; padding: 15px 20px; display: relative">
			<img class="close-info-popup" src="images/push/close-info-popup.gif" style="top: 10px; right: 10px" onclick="$('.feedback-email-popup-success').hide()" />
			<img style="vertical-align: middle; margin-right: 5px" src="images/push/icons/ico_true_S-green.png" />
			<strong class="tx_15_20" style="color: #99cc00">Ihre Nachricht wurde versendet.</strong>
		</div>
	</div>
<?php } ?>