<?php
/*
  $Id: account_password.php,v 1.1 2003/05/19 19:55:45 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');

  if (!push_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_PASSWORD);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $password_current = push_db_prepare_input($_POST['password_current']);
    $password_new = push_db_prepare_input($_POST['password_new']);
    $password_confirmation = push_db_prepare_input($_POST['password_confirmation']);

    $error = false;
	$success = false;

    if (strlen($password_current) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = ENTRY_PASSWORD_CURRENT_ERROR;
	  
    } elseif (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = ENTRY_PASSWORD_NEW_ERROR;
	  
    } elseif ($password_new != $password_confirmation) {
      $error = ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING;
    }

    if ($error == false) {
      $check_customer_query = push_db_query("select customers_password from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
      $check_customer = push_db_fetch_array($check_customer_query);

      if (push_validate_password($password_current, $check_customer['customers_password'])) {
        push_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . push_encrypt_password($password_new) . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'");

        push_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");

        $success = SUCCESS_PASSWORD_UPDATED;
      } else {
        $error = ERROR_CURRENT_PASSWORD_NOT_MATCHING;
      }
    }
  }

	$breadcrumb->reset();
	$breadcrumb->add('Passwort &auml;ndern', push_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));
	
require(DIR_WS_BOXES . 'html_header.php');

if (!$success) {
	include(DIR_WS_BOXES . 'static_menu.php'); 
}
?>

<!-- body_text //-->
<div class="grid_12">
	<h2>Passwort &auml;ndern</h2>

<?php
if ($success) {
?>
	<div class="grid_2 alpha omega">
		<img src="images/push/green-light.png" />
	</div>
	<div class="grid_6 alpha omaga">
		<div class="tx_30_40">Alles klar.</div><br />
		<div class="tx_15_20">Sie haben Ihr Passwort erfolgreich ge&auml;ndert.</div><br /><br />
		<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 170px" href="<?= push_href_link(FILENAME_ACCOUNT_INFO) ?>">Weiter zum Mein Konto<img src="images/push/icons/ico_arrow-fw_S-double_white.png" style="position: absolute; right: 11px; top: 11px"></a>
	</div>
<?php 
} else {
?>
	<div class="grid_8 alpha omega gray-frame tx_13_20">
		Bitte tragen Sie Ihr neues Passwort ein. <br />Das Passwort muss mindestens <?= ENTRY_PASSWORD_MIN_LENGTH ?> Zeichen lang sein.<br /><br />
		<?php 
			echo push_draw_form('account_password', push_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'), 'post', 'id="account_password" class="defaultForm label-right tx_13_20"') . push_draw_hidden_field('action', 'process');
		?>
			<div id="fields-frame"<?= $error ? ' style="height: 260px"' : '' ?>>	
				<div class="form-err-frame tx_red tx_12_15" style="padding-top: 140px; height: 70px<?= $error ? '; display: block"' : '' ?>">						
					<img src="images/push/icons/ico_false_S-red.png" style="vertical-align: middle; margin-right: 5px" /> Fehler<br /><br />
					Hinweis: <span id="error-txt" class="tx_red"><?= $error ?></em>						
				</div>
				<div class="input">	
					<?php echo push_draw_password_field('password_current'); ?>
				</div>
				<label>Aktuelles Passwort<span class="tx_red">*</span></label>
				<div class="input">
					<?php echo push_draw_password_field('password_new'); ?>
				</div>
				<label>Neues Passwort<span class="tx_red">*</span></label>	
				<div class="input">
					<?php echo push_draw_password_field('password_confirmation'); ?>
				</div>
				<label>Neues Passwort <br />wiederholen<span class="tx_red">*</span></label>	
				<div class="input"></div>		
			</div>
			<input type="submit" value="Speichern" class="submitBtn w130 darkblue tx_12_15"><br />
			<div style="position: absolute; bottom: 0px; right: 80px" class="tx_12_15"><span class="tx_red">*</span>Pflichtfelder</label></div>
		</form>
	</div>
	
	<script type="text/javascript">
		$(function()
		{	
			$('#account_password').submit(function()
			{
				var error = '';
				
				if (this.password_current.value == '') {
					error = 'Ihre Passworteingabe ist falsch.';
				} else if (this.password_new.value == '' || this.password_new.value.length < <?= ENTRY_PASSWORD_MIN_LENGTH ?>) {
					error = 'Das Passwort muss mindestens <?= ENTRY_PASSWORD_MIN_LENGTH ?> Zeichen enthalten.';
				} else if (this.password_new.value != this.password_confirmation.value) {
					error = 'Die Inhalte der Felder stimmen nicht &uuml;berein.';
				}
	
				// no errors occured
				if (error == '') {
					return true;
				}
				
				// show error
				$('#fields-frame').css({
					height: "240px"
				})
				$('.form-err-frame').show();
				$('#error-txt').text(error);
	
				return false;
			});
		});
	</script>
<?php
}
?>
	
<!-- body_text_eof //-->
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->

<?php require(DIR_WS_LIB . 'end.php'); /**/
?>