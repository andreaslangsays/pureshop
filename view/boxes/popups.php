<?php 
/*
 * Krös
 */

/**************************
info popups
**************************/

	if (basename($_SERVER['PHP_SELF']) == FILENAME_DOWNLOADCENTER || basename($_SERVER['PHP_SELF']) == FILENAME_ACCOUNT_EDIT) {
?>
		<div id="kundendatenblatt-info-popup" class="info-popup tx_12_15">
			<img class="close-info-popup" src="images/push/close-info-popup.gif" />
			<h5>Kundendatenblatt</h5>
			Nutzen Sie dieses Formular als <strong>Neukunde</strong> oder als <strong>Bestandskunde</strong> zur Änderung ihrer hinterlegten Daten. <br /><br />
			<strong>Für Neukunden:</strong><br /><br />
			Bitte <strong>drucken Sie unser Kundendatenblatt aus</strong> und senden es vollständig ausgefüllt an die angegebene Anschrift. Nach Bearbeitung Ihrer Registrierung richten wir dann sofort Ihr Nutzerkonto und - falls gewünscht - auch Ihren Online-Zugang ein.<br /><br />
			<strong>Wichtig:</strong> Wenn Sie online bestellen möchten, markieren Sie im Formular die Option <strong>"Online-Konto einrichten" mit "JA"</strong>. <br /><br /><br />
			<strong>Für Bestandskunden:</strong><br /><br />
			Bitte <strong>tragen Sie nur Ihre Änderungen ein</strong>, wie z.B. neue<br /><br />
			<ul>
				<li class="tx_12_15" style="list-style: disc inside">Firmenangaben</li>
				<li class="tx_12_15" style="list-style: disc inside">Anschrift</li>
				<li class="tx_12_15" style="list-style: disc inside">Ansprechpartner</li>
			</ul>
			etc.<br /><br />
			<a class="button w170 gradientgrey tx_12_15 tx_center" title="Kundendatenblatt herunterladen" href="download/push_kundendatenblatt_DE_2013-03_form.pdf" target="_blank">Formular herunterladen</a>
		</div>
		
		<div id="bildnutzungsvereinbarung-info-popup" class="info-popup tx_12_15">
			<img class="close-info-popup" src="images/push/close-info-popup.gif" />
			<h5>Bildnutzungsvereinbarung</h5>
			Falls Sie unser <strong>BIldmaterial für Werbezwecke</strong> o.ä. nutzen möchten, benötigen wir Ihre Zustimmung zu dieser Vereinbarung.<br /><br />
			Bis auf wenige Ausnahmen können wir Ihnen alle Bilder, die Sie auf unseren Publikationen finden, zur Verfügung stellen. Diese können Sie entweder auf den jeweiligen Produktseiten <strong>selbst herunterladen oder</strong> bei unserem Kundenservice <strong>anfordern</strong>.<br /><br />
			<?php if(push_session_is_registered('customer_id') && $customer->customer_checkout_enabled == 1) { 	// only customers allowed ?>
				<strong>Sie können der Bildnutzungsvereinbarung als bereits registrierter Kunde</strong> <a href="<?= push_href_link(FILENAME_BILDNUTZUNGSVEREINBARUNG) ?>" class="tx_blue tx_12_15" title="Bildnutzungsvereinbarung online zustimmen">auch online zustimmen</a>. <br /><br />
				<a class="button w170 darkblue tx_12_15 tx_white tx_center" style="margin-bottom: 10px; border-color: #4195D5" title="Bildnutzungsvereinbarung online zustimmen" href="<?= push_href_link(FILENAME_BILDNUTZUNGSVEREINBARUNG) ?>">Jetzt online zustimmen</a>
			<?php } ?>
			<a class="button w170 gradientgrey tx_12_15 tx_center" title="Bildnutzungsvereinbarung herunterladen" href="download/push_bildnutzungsvereinbarung_03-2013_form.pdf" target="_blank">Formular herunterladen</a>
		</div>
		
		<div id="bestellung-info-popup" class="info-popup tx_12_15">
			<img class="close-info-popup" src="images/push/close-info-popup.gif" />
			<h5>Bestellung</h5>
			Falls Sie per <strong>Fax oder Email</strong> bestellen möchten, nutzen Sie bitte das Bestellformular und senden es an:<br /><br />
			<span class="tx_blue">+49.30.284 700-71</span> oder <br /><br />
			<a href="mailto:orders@if-bi.com" class="tx_blue tx_12_15">orders@if-bi.com</a><br /><br />
			<a class="button w170 gradientgrey tx_12_15 tx_center" title="Bestellformular herunterladen" href="download/push_bestellung_DE_2013-03_form.pdf" target="_blank">Formular herunterladen</a>
		</div>
		<?php /*
		<div id="pushkatalog-info-popup" class="info-popup tx_12_15">
			<img class="close-info-popup" src="images/push/close-info-popup.gif" />
			<h5>push Katalog</h5>
			Sie benötigen ein <strong>Passwort</strong> für dieses Dokument. <br />
			Kein Problem: Kontaktieren Sie uns unter Angabe ihrer <strong>Kundennummer</strong> unter:<br /><br />
			<span class="tx_blue">+49.30.284 700-0</span> oder <br /><br />
			<a href="mailto:kontakt@if-bi.com" class="tx_blue tx_12_15">kontakt@if-bi.com</a><br /><br />
			<a class="button w170 gradientgrey tx_12_15 tx_center" title="push Katalog herunterladen" href="download/push_bildnutzungsvereinbarung_03-2013_form.pdf">Katalog herunterladen</a>
		</div>  */ ?>
		
		<script type="text/javascript">
			var info_box_width = '190px';
			var button_width = '40px';
			var button_height = '12px';
			var animation_speed = 500;
			<?php // setting popup height to '100%' or 'auto' after animation didn't work, so have to store heights in a map ?>
			var popup_heights = {};

			$(document).ready(function() {
				 $(".info-popup").each(function() {
				 	var id = $(this).attr('id');
					var button_id = id.replace('-popup', '-button');
					popup_heights[id] = $(this).height();
					if ($("#" + button_id).length > 0) {	// checks if object exists
						$("#" + id).css({
							width: button_width,
							height: button_height,
							top: $("#" + button_id).offset().top + "px",
							left: ($("#" + button_id).offset().left) + "px"
						})
					}
				});				
			});
			
			$(".close-info-popup").click(function(e) {
				var popup_id = $(this).parent().attr('id');
				$("#" + popup_id).animate({
					width: button_width,
					height: button_height
				}, animation_speed, function() {$("#" + popup_id).hide()});
			});
			
			$(".show-info-popup").click(function() {
				var popup_id = $(this).attr('id').replace('-button', '-popup');
				$("#" + popup_id).show();
				$("#" + popup_id).animate({
					width: info_box_width,
					height: popup_heights[popup_id] + "px"
				}, animation_speed); 
				$(this).on("clickoutside", function(e){
						$("#" + popup_id).animate({
							width: button_width,
							height: button_height
						}, animation_speed, function() {$("#" + popup_id).hide()});
						$(this).unbind("clickoutside");
				});
			});
		</script>
<?php	
	} 
?>