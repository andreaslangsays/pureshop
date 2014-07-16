<?php
/*
 * Krös
 */
 
	require('includes/ajax_top.php');
	include_once("includes/classes/Customer.php");
	
	$breadcrumb->reset();
	$breadcrumb->add('Mein Konto', push_href_link(FILENAME_WISHLIST));
	$breadcrumb->add('Anmelden oder registrieren', push_href_link(FILENAME_LOGIN));
	$breadcrumb->add('Passwort vergessen', push_href_link(FILENAME_REGISTRATION));
	
	require(DIR_WS_BOXES . 'html_header.php');
	
	$passwordChanged = false;
	$loggedIn = false;
	
	if (isset($_POST["password_new"]) && isset($_POST["password_confirmation"])) {
		// check user input
		
		$password_new = push_db_prepare_input($_POST['password_new']);
    	$password_confirmation = push_db_prepare_input($_POST['password_confirmation']);	
		
		if (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
			$changePasswordError = 'Das Passwort muss mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten.';
		} else if ($password_new != $password_confirmation) {
			$changePasswordError = 'Die Inhalte der Felder stimmen nicht überein.';
		}
		
		if (!isset($changePasswordError)) {
			$customer = new Customer();
			$customerId = $customer->findCustomerIdByChangePasswordCode($_GET["code"]);
			if ($customerId) {
				$customer = new Customer($customerId);
				if ($customer->setPassword($password_new, $_GET["code"])) {				
					$passwordChanged = true;
					if (!$customer->logIn($customer->customers_email_address, $password_new, false)) {
						$logInError = 'logging in not successful';
					} else {
						$loggedIn = true;
					}
				} else {
					$changePasswordError = 'Das Password konnte nicht gesetzt werden.';	
				}
			} else {
				$changePasswordError = 'reg id not found';
			}
		}	
	} 
?>	
<div class="grid_8 tx_13_15">
		<h2 style="width: 500px">Neues Passwort vergeben und anmelden</h2>
<?php	
	if (isset($_GET["code"]) && !$passwordChanged) {
		// show form
		
		$customer = new Customer();	
		$customerId = $customer->findCustomerIdByChangePasswordCode($_GET["code"]);
		
		if ($customerId) {
?>
			<div class="grid_8 alpha gray-frame">
				Bitte tragen Sie Ihr neues Passwort ein. <br />
				<strong>Wichtig:</strong> Das Passwort muss mindestens <?= ENTRY_PASSWORD_MIN_LENGTH ?> Zeichen lang sein.	<br /><br />				
				<form id="form-registration" class="defaultForm label-right tx_13_20" method="post" action="./password_reset.php?code=<?= $_GET["code"] . (isset($_GET["reg"]) ? "&reg=true" : "") ?>">
					<div id="fields-frame"<?= isset($changePasswordError) ? ' style="height: 170px"' : '' ?>>	
						<div class="form-err-frame tx_red tx_12_15" style="padding-top: 100px; height: 70px<?= isset($changePasswordError) ? '; display: block' : '' ?>">
							Fehler<br /><br />
							Hinweis: <em id="error-txt" class="tx_red"><?= isset($changePasswordError) ? $changePasswordError : '' ?></em>
						</div>
						<div class="input">
							<input type="password" name="password_new">
						</div>
						<label>Neues Passwort<span class="tx_red">*</span></label>
						<div class="input">
							<input type="password" name="password_confirmation">
						</div>
						<label>Neues Passwort wiederholen<span class="tx_red">*</span></label>					
					</div>
					<br />
					<div class="input">
						<input type="submit" value="Log in" class="submitBtn w130 darkblue tx_12_15">
					</div>
					<label class="tx_12_15" style="margin-top: 20px"><span class="tx_red">*</span>Pflichtfelder</label>
				</form>
			</div>
			
			<script type="text/javascript">
				$(function()
				{	
					$('#form-registration').submit(function()
					{
						var error = '';
			
						if (this.password_new.value == '' || this.password_new.value.length < <?= ENTRY_PASSWORD_MIN_LENGTH ?>) {
							error = 'Das Passwort muss mindestens <?= ENTRY_PASSWORD_MIN_LENGTH ?> Zeichen enthalten.';
						} else if (this.password_new.value != this.password_confirmation.value) {
							error = 'Die Inhalte der Felder stimmen nicht überein.';
						}
			
						// no errors occured
						if (error == '') {
							return true;
						}
						
						// show error
						$('#fields-frame').css({
							height: "170px"
						})
						$('.form-err-frame').show();
						$('#error-txt').text(error);
			
						return false;
					});
				});
			</script>
<?php			
		} else {
			echo 'wrong parameter';	
		}
	}
	
	if ($passwordChanged) {
		// show success page

		if (!$loggedIn && $error_stack->display() == Customer::ERROR_COOKIES_DISABLED) {
?>
				<div class="grid_16 alpha omega red-border" style="background-color: #FFEAEA; padding: 15px 0; margin-bottom: 50px">
					<div class="grid_1" style="margin-left: 20px">
						<img src="images/push/red-cookie.png" />
					</div>
					<div class="grid_10 tx_red tx_12_15">
						Cookies <br /><br />
						Bitte <strong class="tx_red">aktivieren Sie Cookies</strong> in Ihrem Browser - eine Anmeldung ist sonst nicht möglich.  <br />
						Falls Sie Hilfe zur Aktivierung von Cookies benötigen, können Sie folgenden Link aufrufen:<br /><br />
						<a class="tx_blue tx_12_15" href="http://mzl.la/O81tBb" target="_blank" title="Mozilla Support: Websites melden Cookies werden blockiert – so beheben Sie das Problem">Mozilla Support: Websites melden Cookies werden blockiert – so beheben Sie das Problem</a>
					</div>
				</div>
		<?php
			}
		?>
		<div class="grid_2 alpha omega">
			<img src="images/push/green-light.png" />
		</div>
		<div class="grid_6 alpha omaga">
			<div class="tx_30_40">Alles klar.</div><br />
			<div class="tx_15_20">Sie haben Ihr Passwort erfolgreich geändert.</div><br /><br />
			<?php
				if (isset($_GET['r'])) {
			?>
					<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 170px" href="<?= push_href_link(FILENAME_DEFAULT) ?>">Zur Startseite<img src="images/push/icons/ico_arrow-fw_S-double_white.png" style="position: absolute; right: 11px; top: 11px"></a>
			<?php
				} else {
			?>
					<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 170px" href="<?= push_href_link(FILENAME_ACCOUNT_INFO) ?>">Weiter zum Mein Konto<img src="images/push/icons/ico_arrow-fw_S-double_white.png" style="position: absolute; right: 11px; top: 11px"></a>
			<?php
				}				
			?>
		</div>
<?php
	}
?>

</div>

<?php
	require(DIR_WS_BOXES . 'html_footer.php');
	require(DIR_WS_LIB . 'end.php'); 
?>