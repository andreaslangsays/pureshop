<?php
switch($_GET['partner'])
{
	case 'orderbird':
		orderbird_html();
	break;
	
	case 'coffeekids':
		coffeekids_html();
	break;
	
	case 'bsoc':
		bsoc_html();
	break;

	case 'polartwist':
		polartwist_html();
	break;

	case 'frozen_yogurt_concept':
		frozen_yogurt_concept_html();
	break;

	case 'climate_partner':
		climate_partner_html();
	break;

	default:


?>
<div class="grid_8 alpha omega">
	
	<h1>Unsere Partner</h1>
	<span class="tx_13_20">Sie kennen das ja. Am liebsten würden auch wir immer alles alleine machen. Aber weil das nicht geht, und weil die richtigen Partner mit ihren Ideen zusätzlichen Schwung in unser Geschäft bringen, arbeiten wir mit einigen Unternehmen und Institutionen besonders intensiv zusammen. 
<br><br>
Klicken Sie ein Logo an um mehr zu erfahren</span><br /><br /><br />
	
	<div class="grid_12 alpha omega" style="margin-bottom: 40px">
		<div class="grid_2 alpha">
			<a class="partner-logo marke-tools" title="orderbird" href="?partner=orderbird"> 
				<img alt="Orderbird" src="images/assets/partner/orderbird_logo.png" style="max-width:80px;">
			</a>
		</div>

		<div class="grid_2 alpha">
			<a class="partner-logo marke-tools" title="Coffee Kids" href="?partner=coffeekids"> 
				<img alt="Coffee Kids" src="images/assets/partner/CK_logo-2.png" style="max-width:80px;">
			</a>
		</div>

		<?php 
		if(polartwist_html(true))
		{
		?>
		<div class="grid_2 alpha">
			<a class="partner-logo marke-tools" title="Polartwist" href="?partner=polartwist"> 
				<img alt="Coffee Kids" src="images/assets/partner/polartwist_logo.png" style="max-width:80px;">
			</a>
		</div>
		<?php
		}
		if(frozen_yogurt_concept_html(true))
		{
		?>
		<div class="grid_2 alpha">
			<a class="partner-logo marke-tools" title="Frozen Yogurt Concept" href="?partner=frozen_yogurt_concept"> 
				<img alt="Frozen Yogurt Concept" src="images/assets/partner/frozen-yogurt-concept_logo.png" style="max-width:80px;">
			</a>
		</div>
		<?php
		}
		if(bsoc_html(true))
		{
		?>
		<div class="grid_2 alpha">
			<a class="partner-logo marke-tools" title="Bruessels School of Coffee" href="?partner=bsoc"> 
				<img alt="Bruessels School of Coffee" src="images/assets/partner/BSOC_logo.png" style="max-width:80px;">
			</a>
		</div>
		<?php
		}
		if(climate_partner_html(true))
		{
		?>
		<div class="grid_2 alpha">
			<a class="partner-logo marke-tools" title="Climate Partner" href="?partner=climate_partner"> 
				<img alt="Climate Partner" src="images/assets/partner/climatePartner-logo.png" style="max-width:80px;">
			</a>
		</div>
		<?php 
		}
	 ?>
	</div>
</div>
<?php
	break; 
}
?>