<?php if (isset($_GET['result']) && $_GET['result'] == 'success') { ?>
		<p class="tx_green tx_strong">Vielen Dank  für Ihr Interesse an unseren Produkten. Gerne senden wir Ihnen unseren aktuellen Katalog an die angegebene Adresse.</p><br />
<?php } ?>

<img src="images/push/bilder/service/push_kataloge.jpg" /> 
<br /><br />

<div class="grid_8 alpha">
	<h1 style="margin-bottom: 25px">push Katalog</h1>
	<p>Unser neuester Katalog bietet Ihnen neben vielen spannenden Neuheiten einen guten Querschnitt durch unser umfangreiches Trendsortiment für Gastronomie und Handel. <br />
		Sie können sich die Broschüre als registrierter Shopnutzer <strong>herunterladen</strong> oder sich - auch als Neukunde - natürlich gerne ein <span class="tx_12_15 tx_blue" id="bestellen-link" style="cursor: pointer">kostenloses, gedrucktes Exemplar</span> bestellen.</p>
	<p>Unser Sortiment in seiner vollen Breite finden Sie online auf unseren Shopseiten. Wir wünschen viel Freude beim Lesen und Entdecken.</p>
	<br /><br />
	
	<h2>Katalog B2B Trendsortiment 2013/2014</h2>
	<img src="images/push/bilder/service/push_katalog2013-2014_thumb.jpg" style="margin-bottom: 5px" /><br />
	<?php if ($customer->login) { ?>
		<a class="tx_blue tx_12_15" target="_blank" title="push Katalog 2013/14 (PDF)" href="download/push_dr_Katalog2013_34RZ_Web.pdf">
			<img style="vertical-align: middle; margin: -3px 5px 0 0" src="images/push/icons/ico_download.png"> push Katalog 2013/14 (PDF)		
		</a>
	<?php } else { ?>
		<img style="vertical-align: middle; margin: -3px 5px 0 0" src="images/push/icons/ico_download-inactive.png"> <span class="tx_light_gray tx_12_15">push Katalog 2013/14 (PDF)</span>
		<br />
		<div class="red-border tx_red tx_12_15" style="background-color: #FFEAEA; padding: 10px; margin-top: 20px">
			Bitte loggen Sie sich ein um den Downloadbereich zu aktivieren. <br />
			<a class="button w130 gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; margin-top: 10px" href="<?php echo push_href_link(FILENAME_LOGIN, 'rdrct=' . FILENAME_KATALOG) ?>">
				Zur Anmeldung <img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png">
			</a>
		</div>
	<?php } ?>
	<br /><br /><br />
	
	<h2 id="katalog-bestellen">Gedruckten Katalog bestellen</h2>
</div>

<div class="grid_8 alpha gray-frame">
	<form id="form-katalog" class="defaultForm label-left tx_13_20" action="./formmail.php" method="post">
		
		<noscript>
			<div class="tx_red">Bitte aktivieren Sie JavaScript in Ihrem Browser.</div>
		</noscript>
				
		<label>Ich bin<span class="tx_red">*</span></label>
			<div id="input_person" class="input">
				<div class="radioGroup">
					<div class="radio">
						<input type="radio" value="Kunde" name="person" onclick="document.getElementById('client-id').style.display = 'block'"<?= $customer->login ? ' checked' : '' ?>> Kunde
					</div>
					<div class="radio">
						<input type="radio" value="Interessent" name="person" onclick="document.getElementById('client-id').style.display = 'none'"<?= $customer->login ? '' : ' checked' ?>> Interessent
					</div>
				</div>	
				<div class="error-msg">
					Sind Sie Kunde oder Interessent?
				</div>
			</div>
		
		<div id="client-id">
			<label>Kunden-Nr.<span class="tx_red">*</span></label>
			<div id="input_client_id" class="input">
				<input type="text" name="client_id" style="width: 80px"<?= $customer->login ? ' value="' . $customer->customers_id . '" readonly' : '' ?>>
				<div class="error-msg">
					Geben Sie bitte Ihre Kunden-Nr. an.
				</div>
			</div>
		</div>
		
		<label>Name<span class="tx_red">*</span></label>			
		<div id="input_last_name" class="input">
			<input type="text" name="last_name"<?= $customer->login ? ' value="' . $customer->customers_lastname . '"' : '' ?>>
			<div class="error-msg">
				Tragen Sie bitte Ihren Nachnamen ein.
			</div>
		</div>
			
		<label>Vorname<span class="tx_red">*</span></label>
		<div id="input_first_name" class="input">
			<input type="text" name="first_name"<?= $customer->login ? ' value="' . $customer->customers_firstname . '"' : '' ?>>
			<div class="error-msg">
				Tragen Sie bitte Ihren Vornamen ein.
			</div>
		</div>
		
		<?php if ($customer->login) $customerDefaultAddress = $customer->get_address_by_id($customer->customers_default_address_id); ?>
			
		<label>Firma<span class="tx_red">*</span></label>
		<div id="input_company" class="input">
			<input type="text" name="company"<?= $customer->login ? ' value="' . $customerDefaultAddress['company'] . '"' : '' ?>>
			<div class="error-msg">
				Tragen Sie bitte Ihre Firma ein.
			</div>
		</div>
		
		<label>Straße, Hausnr.<span class="tx_red">*</span></label>
		<div id="input_street" class="input">
			<input type="text" name="street"<?= $customer->login ? ' value="' . $customerDefaultAddress['street_address'] . '"' : '' ?>>
			<div class="error-msg">
				Tragen Sie bitte Ihre Straße und Hausnummer ein.
			</div>
		</div>
		
		<label>PLZ, Ort<span class="tx_red">*</span></label>
		<div id="input_postcode" class="input" style="display: inline-block; width: 80px">
			<input type="text" name="postcode" style="width: 60px"<?= $customer->login ? ' value="' . $customerDefaultAddress['postcode'] . '"' : '' ?>>
			<div class="error-msg">
				Tragen Sie bitte Ihre PLZ ein.
			</div>
		</div>
		<div id="input_city" class="input" style="display: inline-block; width: 157px; vertical-align: top">
			<input type="text" name="city" style="width: 137px"<?= $customer->login ? ' value="' . $customerDefaultAddress['city'] . '"' : '' ?>>
			<div class="error-msg">
				Tragen Sie bitte Ihren Ort ein.
			</div>
		</div>
			
		<label>E-Mail-Adresse<span class="tx_red">*</span></label>
		<div id="input_email" class="input">
			<input type="text" name="email"<?= $customer->login ? ' value="' . $customer->customers_email_address . '"' : '' ?>>
			<div class="error-msg">
				Geben Sie bitte eine g&uuml;ltige E-Mail-Adresse an.
			</div>
		</div>
		
		<label>Telefon mit Vorwahl<span class="tx_red">*</span></label>
		<div id="input_phone" class="input">
			<input type="text" name="phone"<?= $customer->login ? ' value="' . $customer->customers_telephone . '"' : '' ?>>
			<div class="error-msg">
				Geben Sie bitte Ihre Telefonnnummer an. (min. 4-stellig; +, -, Leerzeichen erlaubt)
			</div>
		</div>
		
		<label>Nachricht<span class="tx_red">*</span></label>
		<div id="input_message" class="input">
			<textarea name="message">Bitte senden Sie mir ihren aktuellen Katalog per Post zu.</textarea>
			<div class="error-msg">
				Geben Sie bitte die Nachricht an.
			</div>
		</div>
		
		<div class="tx_12_15" style="text-align: right; padding: 0 20px 0 0"><span class="tx_red">*</span>Pflichtfelder</div>
		
		<label></label>
		<div id="send-button-container" class="input" style="padding: 15px 0 0 8px"></div>	
		
		<input type="hidden" name="recipients" value="push">
		<input type="hidden" name="subject" value="[Bestellung] Aktueller Katalog">
		<input type="hidden" name="referrer" value="katalog.php">
		<input type="hidden" name="mail_options" value="FromAddr=push,HTMLTemplate=katalog.html,AlwaysList,KeepLines,CharSet=utf-8">
		<input type="hidden" name="good_template" value="formmail_success.html">	
	</form>
</div>

<script type="text/javascript">
	$(function()
	{
		if ($('input:radio[name:person]:checked').val() == 'Interessent') {
			document.getElementById('client-id').style.display = 'none';
		}
	
		$('#send-button-container').append('<input class="submitBtn w130 darkblue tx_12_15" type="submit" value="Bestellung senden" name="submit">');
		
		$('#form-katalog').submit(function()
		{
			var errors = new Array();

			if ( this.person.value == '' )
				errors.push('person');
			if ( $('input:radio[name:person]:checked').val() == 'Kunde' && this.client_id.value == '' )
				errors.push('client_id');
			if ( this.last_name.value == '' )
				errors.push('last_name');
			if ( this.first_name.value == '' )
				errors.push('first_name');
			if ( this.company.value == '' )
				errors.push('company');
			if ( this.street.value == '' )
				errors.push('street');
			if ( this.postcode.value == '' )
				errors.push('postcode');
			if ( this.city.value == '' )
				errors.push('city');
			if ( this.email.value.search(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) == -1 )
				errors.push('email');
			if ( this.phone.value.search(/^[\d\s\+\-]{4,15}$/) == -1 )
				errors.push('phone');
			if ( this.message.value == '' )
				errors.push('message');

			// no errors occured
			if (errors.length == 0)
				return true;

			// remove previous errors
			$('.input').each(function() {
				$(this).removeClass('error');
			});
			
			// show new errors			
			for (var index = 0; index < errors.length; index++)
				$('#input_' + errors[index]).addClass('error');

			return false;
		});
		
		$("#bestellen-link").click(function() {
			$('html, body').animate({
				scrollTop: $("#katalog-bestellen").offset().top
			}, 1500);
		});
	});
</script>
