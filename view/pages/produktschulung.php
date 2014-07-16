<?php

// new registration
if ( isset( $_POST['submit_anmeldung'] ) && !empty( $_POST['first_name'] ) && !empty( $_POST['last_name'] ) && !empty( $_POST['address_street'] ) 
		&& !empty( $_POST['address_nr'] ) && !empty( $_POST['address_postal_code'] ) && !empty( $_POST['address_city'] ) 
		&& !empty( $_POST['email'] ) && !empty( $_POST['telephone'] ) && !empty( $_POST['course_id'] ) ) {
	
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_URL, "http://Bruesselsschoolofcoffee.de/BSOC/api/registration.php?method=create&event_id=" 
			. rawurlencode( $_POST['course_id'] ) . "&first_name=" . rawurlencode( $_POST['first_name'] ) . "&last_name=" 
			. rawurlencode( $_POST['last_name'] ) . "&address_street=" . rawurlencode( $_POST['address_street'] ) 
			. "&address_nr=" . rawurlencode( $_POST['address_nr'] )	. "&address_postal_code=" . rawurlencode( $_POST['address_postal_code'] ) 
			. "&address_city=" . rawurlencode( $_POST['address_city'] )	. "&email=" . rawurlencode( $_POST['email'] ) 
			. "&telephone=" . rawurlencode( $_POST['telephone'] ) . "&company=" . rawurlencode( $_POST['company'] )
			. "&participant_name=" . rawurlencode( $_POST['participant_name'] ) . "&comment=" . rawurlencode( $_POST['comment'] )
			. "&confirm_url=" . rawurlencode( push_href_link(FILENAME_PRODUKTSCHULUNG) ) );
	$json_registration_response = curl_exec( $ch );
	$registration_response = json_decode( $json_registration_response, true );
}

// confirm registration
if ( isset( $_GET['confirm'] ) && isset( $_GET['register_id'] ) ) {
	
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_URL, "http://Bruesselsschoolofcoffee.de/BSOC/api/registration.php?method=update&confirm_id=" 
			. rawurlencode( $_GET['confirm'] ) . "&register_id=" . rawurlencode( $_GET['register_id'] ) );
	$json_confirmation_response = curl_exec( $ch );
	$confirmation_response = json_decode( $json_confirmation_response, true );
}


if ( isset( $confirmation_response ) && $confirmation_response['head']['status'] == 1 ) {
	// show confirmation successful page
?>
	<div class="grid_16" style="margin-bottom: 50px">
		<h2>Anmeldung zur Produktschulung</h2>
  		<div class="grid_2 alpha omega">
			<img src="images/push/green-light.png" />
		</div>
		<div class="grid_8 alpha omaga">
			<div class="tx_30_40">Vielen Dank!</div><br />
			<div class="tx_15_20">Ihre Anmeldung zur Produktschulung ist eingegangen.</div>
            <br /><br />
			<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 175px" href="<?= push_href_link(FILENAME_PRODUKTSCHULUNG) ?>">Weiter zu Produktschulung<img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
			<div id="redirect-counter" class="tx_12_15 tx_light_gray" style="margin-top: 7px"></div>
		</div>
	</div>
	
	<script>
		/*$(document).ready(function(e) {
			var time_left = 10;  
			window.setInterval(function() {
				$('#redirect-counter').text("Die automatische Weiterleitung erfolgt in " + time_left + " Sekunden.");
				if (eval(time_left) == 0) {
					window.location.href = ("<?= push_href_link( FILENAME_DEFAULT ) ?>");                 
				} else {
					time_left--;
				}
			}, 1000); 			
		});	*/	
	</script>
<?php 
} else {
	// show standard page
	
	include(DIR_WS_BOXES . 'static_menu.php'); 	
	
	// get events from google calendar
	require_once 'libraries/google_api/Google_Client.php';
	require_once 'libraries/google_api/contrib/Google_CalendarService.php';
	
	$produktschulungen_calendar_id = 'a8mkgmmquoqca7qah0k55fmpos@group.calendar.google.com';
	
	$client = new Google_Client();
	$cal = new Google_CalendarService($client);
	
	$params = array("timeMin"		=> date(DateTime::ATOM, strtotime("today")),	
					"singleEvents"	=> "true",
					"orderBy"		=> "startTime");
					
	$events = $cal->events->listEvents($produktschulungen_calendar_id, $params);
?>
	<div class="grid_12">
		<div class="grid_12 alpha" style="margin-bottom: 40px; width: 700px; height: 340px; background: url('./images/push/bilder/service/push_kundenschulung_SH_0557_web-700x340.jpg'); position: relative">
			<div style="width: 170px; position: absolute; bottom: 20px; right: 30px" class="tx_12_15 tx_white">
				Stefanie Hoffmann  ist eine unserer push Kunden- trainerinnen und seit 12 Jahren in der Kakaoszene unterwegs.
			</div>
		</div>
		
		<div class="grid_8 alpha">
			<h1 style="margin-bottom: 25px">Unser Trainingszentrum</h1>
			<p>Mindestens einmal im Monat stehen wir Ihnen bei unseren Produktschulungen persönlich mit unserem Know-How zur Seite.
		In den Räumlichkeiten der Bruessels School of Coffee* unterweisen wir Sie nicht nur in der Zubereitung unserer Produkte. Wir geben Ihnen auch neue Ideen für Getränkenamen, -dekorationen und Vermarktungs-Know-How mit auf den Weg.</p>
			<div id="schulung-for-free" class="tx_15_20">
				Diese Schulungen sind für push-Kunden <strong>kostenlos</strong>
			</div>
			<p class="tx_strong">Nutzen Sie diese Chance und lassen Sie sich inspirieren!</p>
			<br /><br />
			<h2>Schulungstermine & Anmeldung</h2>
			<p><strong>Alles was Sie zur Schulung mitbringen müssen ist Neugier und gute Laune.</strong> Die Produkte und Zubereitungsutensilien stellen wir zur Verfügung. <br />Folgen Sie dem magischen Anmelde-Button ... . <strong>Wir freuen uns auf Sie!</strong></p>
		</div>
		
		<div class="grid_4 omega gray-box">
			<div class="gray-box-head tx_15_20">
				Die nächsten Kunden-schulungstermine 
			</div>		
			<div class="gray-box-body tx_13_20">
				<div>
					<div style="margin-bottom: 10px"> 
						<?php
							foreach ($events->getItems() as $event) {
								//if (strpos(strtolower($event->getSummary()), "produktschulung") !== false) {
									$date = new DateTime($event->getStart()->getDateTime());
									// echo strftime("%a, %d.%m.%G", $date->getTimestamp()) . "<br />";		// <- getTimestamp not working (???)
						?>
									<strong><?= strftime("%a, %d.%m.%G", strtotime($date->format("Y-m-d"))) ?> </strong><br />
						<?php
								//}
							}
						?>
					</div>
					Jeweils von 10 bis 15 Uhr
				</div>
				<div style="border-bottom: 1px dotted #BBBBBB; width: 200px; margin: 10px 0 10px -10px"></div>
				Alle Termine finden Sie auch in unserem <a class="tx_blue tx_12_15" href="<?= push_href_link(FILENAME_CALENDAR) ?>" title="Kalender">Kalender</a>.
				<div style="border-bottom: 1px dotted #BBBBBB; width: 200px; margin: 10px 0 10px -10px"></div>
				Die Schulungen finden in den Räumen der Bruessels School of Coffee statt.<br />
				<a class="tx_blue tx_12_15" href="http://Bruesselsschoolofcoffee.de/BSOC/" title="Bruessels School Of Coffee" target="_blank">www.Bruesselsschoolofcoffee.de</a>
			</div>
		</div>
		
		<?php
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_URL, "http://Bruesselsschoolofcoffee.de/BSOC/api/course.php?method=get&course=push" );
			$json_response = curl_exec( $ch );
			$response = json_decode( $json_response, true );
			
			if ( $response['head']['status'] == 1 && !empty( $response['body'] ) ) { ?>
				<div class="grid_8 alpha omega" style="margin-top: 30px">
					<div class="grid_2 alpha tx_12_15 tx_strong tx_light_gray">Datum</div>
					<div class="grid_2 tx_12_15 tx_strong tx_light_gray">Uhrzeit</div>
					<div class="grid_2 tx_12_15 tx_strong tx_light_gray">Buchungsstatus</div>
					<div class="grid_2 omega tx_12_15 tx_strong tx_light_gray"></div>
					<div class="grid_8 alpha omega" style="border-bottom: 1px solid #ccc; margin-bottom: 10px; margin-top: 5px"></div>
					
					<?php $j = 0;
					foreach ( $response['body'] as $course ) { 
						$j++; ?>
						<div class="grid_8 alpha omega<?php if ($j < sizeof($response['body'])) echo ' bottom_border'; ?>" style="padding-bottom: 10px; padding-top: 10px">
							<div class="grid_2 alpha tx_13_20 tx_strong">
								<?= $course['date'] ?>
							</div>
							<div class="grid_2 tx_13_20">
								 10:00 - 15:00 Uhr
							</div>
							<div class="grid_2 tx_13_20">
								<div class="buchungsstatus" title="<?= $course['limit'] - $course['registered'] ?> Plätze frei">
									<?php 
										$counter = ( int ) ( ( $course['registered'] ) * 8 / $course['limit'] );
										
										for ($i = 0; $i < 8; $i++) {
											echo '<div class="' . ($i < $counter ? "besetzt" : "frei") . '"></div>';
										}								
									?>
								</div>
							</div>
							<div class="grid_2 omega tx_13_20">
								<?php if ($course['registered'] < $course['limit']) { ?>
									<a class="button darkblue tx_white tx_12_15 anmelden-btn" style="cursor: pointer; border: 1px solid #4195D5; width: 72px; float: right; padding-left: 20px" data-course-id="<?= $course['id'] ?>">Anmelden</a>
								<?php } else { ?>
									ausgebucht
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<div class="grid_8 alpha omega tx_12_15 tx_light_gray top_separator" style="padding-top: 10px; margin: 0 0 40px 0">Die Teilnehmerzahl pro Schulung ist auf 8 Personen begrenzt, damit jeder optimal mitarbeiten kann.</div>
					<div class="tx_15_20" style="margin-bottom: 40px">Sie können sich auch per E-Mail anmelden:</div>
					<div class="blue-box tx_12_15 tx_light_gray">
						<strong class="tx_light_gray">Anmeldung per E-Mail bitte an:</strong>
						<a class="tx_blue tx_12_15" href="mailto:sales@if-bi.com" title="sales@if-bi.com">sales@if-bi.com</a><br /><br />
						<strong class="tx_light_gray">Veranstaltungsort:</strong>
						BSOC <br />
						Bruessels School of Coffee  <br />
						Mainstr. 171/172 <br />
						C-56873 Bruessels
					</div>
					<br /><br />
					<div class="tx_12_15 tx_light_gray top_separator" style="padding-top: 10px; margin-top: 20px">
						* Beachten Sie bitte, dass die Ausbildung an der Kakaomaschine durch die Trainer der BSOC nicht zum Schulungsumfang gehört. 
					</div>
				</div>
				
				<div id="prodToCart" class="produktschulung-popup" style="display: none; width: 520px; margin-left: -260px; position: absolute; top: 50%">
					<div style="width: 440px; margin: 20px; background-color: rgba(255, 255, 255, 0.9); padding: 15px 20px; display: relative">
						<img class="close-info-popup" src="images/push/close-info-popup.gif" style="top: 10px; right: 10px" onclick="$('.produktschulung-popup').hide(); $('#semitransparent').hide();" />
						<h2 class="bottom_separator" style="width: 440px; padding-bottom: 20px; margin-bottom: 20px">Anmeldung zur Produktschulung</h2>
						<form id="produktschulunganmeldung" class="defaultForm label-left tx_13_15" method="post">
							<label>Datum<span class="tx_red">*</span></label>
							<div id="input_course_id" class="input">
								<select name="course_id" style="width: 260px">
									<?php foreach ( $response['body'] as $course ) { ?>
										<option value="<?= $course['id'] ?>"><?= $course['date'] ?></option>
									<?php } ?>
								</select>
								<div class="error-msg"> Tragen Sie bitte Ihren Namen ein. </div>
							</div>
							<label>Firma</label>
							<div id="input_company" class="input">
								<input type="text" value="" name="company" />
							</div>					
							<label>Name<span class="tx_red">*</span></label>
							<div id="input_last_name" class="input">
								<input type="text" value="" name="last_name" />
								<div class="error-msg"> Tragen Sie bitte Ihren Namen ein. </div>
							</div>
							<label>Vorname<span class="tx_red">*</span></label>
							<div id="input_first_name" class="input">
								<input type="text" value="" name="first_name" />
								<div class="error-msg"> Tragen Sie bitte Ihren Vornamen ein. </div>
							</div>
							<label>Straße, Nr.<span class="tx_red">*</span></label>
							<div id="input_address_street" class="input">
								<input type="text" value="" name="address_street" style="display: inline-block; margin-right: 13px; width: 130px" />
								<input type="text" value="" name="address_nr" style="width: 70px" />
								<div class="error-msg"> Tragen Sie bitte Ihre Straße und Hausnummer ein. </div>
							</div>	
							<label>PLZ, Ort<span class="tx_red">*</span></label>
							<div id="input_address_postal_code" class="input">
								<input type="text" value="" name="address_postal_code" style="display: inline-block; margin-right: 13px; width: 70px" />
								<input type="text" value="" name="address_city" style="width: 130px" />
								<div class="error-msg"> Tragen Sie bitte Ihre PLZ und Ort ein. </div>
							</div>				
							<label>E-Mail-Adresse<span class="tx_red">*</span></label>
							<div id="input_email" class="input">
								<input type="text" value="" name="email" />
								<div class="error-msg"> Geben Sie bitte eine gültige E-Mail-Adresse an. </div>
							</div>
							<label>Telefon<span class="tx_red">*</span></label>
							<div id="input_telephone" class="input">
								<input type="text" value="" name="telephone" />
								<div class="error-msg"> Geben Sie bitte Ihre Telefonnnummer an. (min. 4-stellig; +, -, Leerzeichen erlaubt) </div>
							</div>
							<div style="position: relative">
								<label style="white-space: nowrap">Name des Teilnehmers</label>
								<span class="tx_light_gray tx_12_15" style="position: absolute; top: 35px; left: 35px">(falls abweichend)</span>
								<div id="input_participant_name" class="input">
									<input type="text" value="" name="participant_name" />
								</div>
							</div>
							<label>Nachricht an uns</label>
							<div id="input_comment" class="input">
								<textarea value="" name="comment" style="height: 60px"></textarea>
							</div>
							<br />
							<div id="input_agb" class="input" style="margin: 20px 0 0 5px; width: 400px; position: relative">
								<input type="checkbox" name="agb" style="margin: -2px 5px 0 0; height: 13px" /> 
								<div style="position: relative; left: 20px; top: -20px">Ich habe die <a class="tx_12_15 tx_blue" target="_blank" href="http://Bruesselsschoolofcoffee.de/BSOC/wp-content/themes/bsoc/doc/AGB_SEMINAR.pdf">AGB der Bruessels School of Coffee</a> gelesen und <br />akzeptiere sie.</div>
								<div class="error-msg"> Sie müssen die AGB akzeptieren. </div>
							</div>
							<div class="tx_right tx_light_gray tx_12_15" style="margin: 15px 23px 0 0"><span class="tx_red">*</span>Pflichtfelder</div>
							<input class="submitBtn tx_strong w150 darkblue tx_12_15" type="submit" name="submit_anmeldung" value="Anmeldung absenden" style="margin: 20px 0 0 5px">
						</form>
					</div>
				</div>
				
				<script type="text/javascript">
					$(function()
					{
						$('.anmelden-btn').click(function(e) {
							$('.produktschulung-popup').show(); 
							$('#semitransparent').show(); 
							$("select[name=course_id] option").removeAttr('selected'); 
							$("select[name=course_id] option[value='" + $(this).data('course-id') + "']").attr('selected', true); 
							$('html, body').animate({scrollTop: $('#prodToCart').offset().top}, 1000);
						});
						
						$('#produktschulunganmeldung').submit(function()
						{
							var errors = new Array();
				
							if ( this.first_name.value == '' )
								errors.push('first_name');
							if ( this.last_name.value == '' )
								errors.push('last_name');
							if ( this.address_street.value == '' || this.address_nr.value == '' )
								errors.push('address_street');
							if ( this.address_postal_code.value == '' || this.address_city.value == '' )
								errors.push('address_postal_code');
							if ( this.email.value.search(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) == -1 )
								errors.push('email');
							if ( this.telephone.value.search(/^[\d\s\+\-]{4,15}$/) == -1 )
								errors.push('telephone');
							if ( this.agb.checked == false )
								errors.push('agb');
				
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
					});
				</script>
			<?php }
		?>
		
		<div id="semitransparent"<?= isset( $registration_response ) || ( isset( $confirmation_response ) && $confirmation_response['head']['status'] == 0 ) ? ' style="display: block"' : '' ?>></div>
		
		<?php if ( isset( $registration_response ) || ( isset( $confirmation_response ) && $confirmation_response['head']['status'] == 0 ) ) { ?>
			<div id="prodToCart" class="registration-popup-success" style="display: block; top: 60%">
				<div style="width: 380px; margin: 20px; background-color: #ffffff; padding: 15px 20px; display: relative">
					<img class="close-info-popup" src="images/push/close-info-popup.gif" style="top: 10px; right: 10px" onclick="$('.registration-popup-success').hide(); $('#semitransparent').hide();" />
					<?php if ($registration_response['head']['status'] == 1) { ?>
						<img style="vertical-align: middle; margin-right: 7px" src="images/push/icons/ico_true_S-green.png" />
						<strong class="tx_13_15" style="color: #99cc00">Vielen Dank für Ihre Anmeldung zur Produktschulung. <br /><br />Sie erhalten in Kürze per E-Mail einen Link. Bitte klicken Sie auf den Link, um Ihre Anmeldung abzuschliessen.</strong>
					<?php } else { ?>
						<img style="vertical-align: middle; margin-right: 5px" src="images/push/icons/ico_false_S-red.png" />
						<strong class="tx_13_15 tx_red"><?= array_key_exists('error_message', $registration_response['head']) ? $registration_response['head']['error_message'] : 'Ihre Anmeldung konnte nicht abgeschlossen werden.' ?></strong>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
<?php 
} 
?>