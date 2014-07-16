<div id="servicebox">
	<div id="topservice" class="gradientgrey">
		<img src="./images/push/icons/ico_contact_phone.png" style="position: absolute; top: 12px; left: 7px" />
		<span class="tx_13_20">Kostenlos bestellen per Telefon</span>
	<?php if($is_phone)
		{
			?><a class="tx_16_20 tx_blue" href="tel:08004324835">0800 4324835</a><?php 
		}
		else 
		{
			?><a class="tx_16_20 tx_blue">0800 4324835</a><?php 
		}?>
	</div>
	<div id="serviceshow" class="gradientgrey"><span class="selectarrow" style="position:absolute;top:22px;right:22px;">&nbsp;&nbsp;&nbsp;</span></div>
	<div id="serviceinformation" style="position:absolute;top:50px;">
		<div class="tx_13_15 bottom_border teltimes">
			<span class="tx_13_15">Telefonische Bestellannahme</span>
			<span class="tx_12_15 tx_blue">Montags bis Freitags 8:00 bis 18:00 CET</span>
		</div>
		<div class="tx_16_20 bottom_border email">
			<span class="tx_13_20">Bestellen per Email</span>
			<span><a class="tx_16_20 tx_blue" href="mailto:orders@if-bi.com" title="orders@if-bi.com">orders@if-bi.com</a></span>
		</div>
		<div class="tx_13_50 bottom_border fax">
			<span class="tx_13_20">Bestellen per Fax</span>
			<span class="tx_16_20 tx_blue">+49 (0)30 28 47 00&ndash;77</span>
		</div>
        <div class="tx_13_50 bottom_border rueckruf">
			<span class="tx_13_20">Sie haben Fragen? Nutzen Sie unseren</span>
			<span><a class="tx_16_20 tx_blue" href="<?= push_href_link(FILENAME_RUECKRUFSERVICE); ?>">Rückrufservice</a></span>
		</div>
		<div class="tx_13_15 bottom_border questions">
			<span class="tx_13_20">Fragen &amp; Anregungen</span>
			<span class="tx_16_20 tx_blue">+49 (0)30 28 47 00&ndash;0</span>
		</div>
		<div class="tx_13_15 cmail">
			<span class="tx_13_15">Postanschrift</span>
			<span class="tx_12_15 tx_blue">push &ndash;</span>
			<span class="tx_12_15 tx_blue">Int'l Food &amp; Beverage Import GmbH</span>
			<span class="tx_12_15 tx_blue">Mainstr. 171/172</span>
			<span class="tx_12_15 tx_blue">C-56873 Bruessels</span>
		</div>
	</div>
</div>

