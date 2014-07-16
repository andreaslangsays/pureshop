<?php
/*
  $Id: password_forgotten.php,v 1.50 2003/06/05 23:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/ajax_top.php');
include_once("includes/classes/Customer.php");

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PASSWORD_FORGOTTEN);

$error = false;
$passwordResetted = false;

if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
	$email_address = push_db_prepare_input($_POST['email_address']);
	
	if (!empty($_POST['email_address'])) {
		$customer = new Customer();
		$customerId = $customer->findCustomerIdByEmail($email_address);
		
		if ($customerId) {
			$customer = new Customer($customerId);
			$customer->resetPassword(false, true);		
			$passwordResetted = true;
		} else {
			$error = true;
		}
	} else {
		$error = true;
	}
}

$breadcrumb->reset();
$breadcrumb->add('SHOP', push_href_link(FILENAME_DEFAULT));
$breadcrumb->add('Anmelden oder registrieren', push_href_link(FILENAME_LOGIN, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, push_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));

require(DIR_WS_BOXES . 'html_header.php');
?>
<!-- body_text //-->
<div class="grid_16">
<?php
  if (!$passwordResetted) {
  	// default view
?>
	<h2>Passwort vergessen?</h2>	
	<div class="grid_8 alpha gray-frame" style="display: block; float: none">	
		<p>Bitte geben Sie Ihre bei uns hinterlegte E-Mail-Adresse ein. Wir senden Ihnen anschließend einen Link zur Änderung Ihres Passwortes zu.</p>
		<?php echo push_draw_form('password_forgotten', push_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL'), 'post', 'id="password_forgotten" class="defaultForm label-right tx_13_20"'); ?>
			<div id="input_email_address" class="input<?= $error ? ' error' : '' ?>">
				<input type="text" name="email_address">
				<div class="error-msg">
					<img style="position: relative; top: 3px; margin-right: 5px" src="images/push/icons/ico_false_S-red.png"> Ungültige E-Mail-Adresse<br /><br />
					Hinweis: Bitte geben Sie eine gültige E-Mail-Adresse an.
				</div>
			</div>		
			<label>E-Mail-Adresse<span class="tx_red">*</span></label>
			<br /><br />
    		<div class="tx_12_15" style="position: absolute; bottom: -5px; right: 83px"><span class="tx_red">*</span>Pflichtfelder</div>
			<input type="submit" name="suche" value="Link anfordern" class="submitBtn w130 darkblue tx_12_15">
		</form>
	</div>
	<br /><br />
	<a href="<?= push_href_link(FILENAME_LOGIN, '', 'SSL') ?>" class="button w80 gradientgrey tx_12_15">Zurück</a>
<?php
	} else {
		// password resetted
?>
		<h2>Passwort vergessen ...</h2>	
		<div class="grid_2 alpha omega">
			<img src="images/push/ampeln-gelb.png" />
		</div>
		<div class="grid_9 alpha omaga">
			<div class="tx_30_40">Ok.</div><br />
			<div class="tx_15_20">
				Sie erhalten in Kürze eine E-Mail mit einem Link zur Vergabe eines neuen Passwortes. Bitte <strong>rufen Sie den Link innerhalb von 24 Stunden auf</strong>.<br />
				Sollten Sie diesbezüglich Fragen haben, wenden Sie sich bitte an unseren Kundenservice unter <span class="tx_blue tx_15_20">+49(0)30 28 47 00-0</span>.
			</div><br /><br />
			<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 150px" href="<?= push_href_link(FILENAME_DEFAULT) ?>">Weiter zum Shop <img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
		</div>
<?php
	}
?>
</div>
<!-- body_text_eof //-->

<script type="text/javascript">
	$(function()
	{
		$('#password_forgotten').submit(function()
		{
			var errors = new Array();
			
			if ( this.email_address.value.search(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) == -1 )
				errors.push('email_address');

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

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>