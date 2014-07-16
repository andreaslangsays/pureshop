<?php
/*
  $Id: login.php,v 1.80 2003/06/05 23:28:24 hpdl Exp $
  adäpted for Separäte Price Per Customer v4.0 2005/02/22
  ösCommerce, Öpen Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License 
*/

  require('includes/ajax_top.php');
// include_once("includes/classes/Customer.php");
/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      elseif ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      elseif ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      elseif (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      elseif (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      elseif(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
 // if ($session_started == false) {
 //   push_redirect(push_href_link(FILENAME_COOKIE_USAGE));
 // }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

	if (isset($_GET['rdrct']) && !empty($_GET['rdrct'])) {
		if (!push_session_is_registered('redirect_after_login')) {
			push_session_register('redirect_after_login');
		}	
		$redirect_after_login = $_GET['rdrct'];
	}
	

	$error = false;
	
	if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
		$email_address = push_db_prepare_input($_POST['email_address']);
		$password = push_db_prepare_input($_POST['password']);

		$customer = new Customer();
		if ($customer->logIn($email_address, $password, false)) 
		{
			if (!empty($redirect_after_login))
			{
				$redto = $redirect_after_login;
				$redirect_after_login = '';					
				push_redirect(push_href_link($redto,'','NONSSL'));
			}	
			if (is_object($navigation) && sizeof($navigation->snapshot) > 0)
			{
				$origin_href = push_href_link($navigation->snapshot['page'], push_array_to_string($navigation->snapshot['get'], array(push_session_name())), $navigation->snapshot['mode']);
				$navigation->clear_snapshot();
				push_redirect($origin_href);
			}
			else
			{
				if(isset($_POST['redirectto']) && ($_POST['redirectto']<>'') )
				{
					push_redirect(push_href_link($_POST['redirectto'],'','NONSSL'));
				}
				elseif(isset($_SESSION['current_page']) && $_SESSION['current_page']<>'')
				{
					push_redirect($_SESSION['current_page'],'','NONSSL');
				}
				else
				{
					push_redirect(push_href_link(FILENAME_DEFAULT));
				}
			}
		}
		else 
		{
			$error = true;	
		}
		if ($error == true) 
		{
			$error_stack->add(TEXT_LOGIN_ERROR);
		}
	}
	elseif(isset($_GET['action']) && ($_GET['action'] == 'bkregister'))
	{
		//var_dump($_POST);	
		//array(3) { $_POST['kdnr' => string(5) "12234" $_POST['email_address'] => string(15) "andreas@wupp.de" 'suche' => string(15) "Login anfordern" }
		$kdnr = trim(push_db_prepare_input($_POST['kdnr']));
		$email_address = push_db_prepare_input($_POST['bkemail_address']);
		if (!empty($_POST['bkemail_address']))
		{
			$customer = new Customer();
			$customerId = $customer->findCustomerIdByEmail($email_address);
			if ($customerId)
			{
				$customer = new Customer($customerId);
				$customer->resetPassword(false, true);		
				$passwordResetted = true;
				$bkm="Der Bestandskunde " . $kdnr . " hat mit der Mailadresse " . $email_address . " Zugang zum Onlineshop angefordert. LINK WURDE GENERIERT! \n\n MFG der Onlineshop";
				push_mail('Office Front','rootifyme@gmail.com', "LINK SHOPZUGANG GENERIERT", $bkm, "Onlineshop", 'shop@if-bi.com');
			}
			else
			{
				//SEND MAIL TO FRONTOFFICE
				if(is_numeric($_POST['kdnr']) && validEmail($_POST['bkemail_address']))
				{
					$bkm="Der Bestandskunde " . $kdnr . " hat mit der Mailadresse " . $email_address . " Zugang zum Onlineshop angefordert. \n\n MFG der Onlineshop";
					// push_mail($this->customers_firstname . ' ' . $this->customers_lastname, $this->customers_email_address, $subject, $message,  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
					push_mail('Office Front','rootifyme@gmail.com', "SHOPZUGANG ANGEFORDERT", $bkm, "Onlineshop", 'shop@if-bi.com');
					push_mail('Officefront','orders@if-bi.com', "Kunde hat Shopzugang angefordert", $bkm, "Onlineshop", 'shop@if-bi.com');
					$subscriptionSent = true;
					$email_address = '';
					$kdnr = '';
				}
				else
				{
					$fehler['bool']=true;
					$fehler['message']="Ihre Daten konnten nicht verarbeitet werden.<br /> Hinweis: Ihre Daten waren entweder fehlerhaft oder unvollständig. Bitte &uuml;berpr&uuml;fen Sie Ihre Angaben und versuchen Sie es erneut. Achten Sie auf Gro&szlig;- und Kleinschreibung.";
				}
			}
		}
		else
		{
			$fehler['bool']=true;
			$fehler['message']="Ihre Daten konnten nicht verarbeitet werden.<br /> Hinweis: Ihre Daten waren entweder fehlerhaft oder unvollständig. Bitte &uuml;berpr&uuml;fen Sie Ihre Angaben und versuchen Sie es erneut. Achten Sie auf Gro&szlig;- und Kleinschreibung.";
		}
	}
	if($passwordResetted)
	{
		push_redirect(push_href_link(FILENAME_LOGIN ."?prskWsuebXcoweru" ));	
	}
	elseif($subscriptionSent)
	{
		push_redirect(push_href_link(FILENAME_LOGIN ."?wsiQof42wejRtsus" ));
	}

	$breadcrumb->reset();
	$breadcrumb->add('SHOP', push_href_link(FILENAME_DEFAULT));
	$breadcrumb->add('Anmelden oder registrieren', push_href_link(FILENAME_LOGIN, '', 'SSL'));


require(DIR_WS_BOXES . 'html_header.php');
?>
<!-- body_text //-->
<div class="grid_16">
	<?php
		if ($session_started == false) {
   			// cookies deactivated, not good, not good at all, very bad, disaster...
	?>
			<div class="grid_16 alpha omega red-border" style="background-color: #FFEAEA; padding: 15px 0; margin-bottom: 50px">
				<div class="grid_1" style="margin-left: 20px">
					<img src="images/push/red-cookie.png" />
				</div>
				<div class="grid_10 tx_red tx_12_15">
					<img src="images/push/icons/ico_false_S-red.png" style="vertical-align: middle; margin-right: 5px">Cookies <br /><br />
					Bitte <strong class="tx_red">aktivieren Sie Cookies</strong> in Ihrem Browser - eine Anmeldung ist sonst nicht möglich.  <br />
					Falls Sie Hilfe zur Aktivierung von Cookies benötigen, können Sie folgenden Link aufrufen:<br /><br />
					<a class="tx_blue tx_12_15" href="http://mzl.la/O81tBb" target="_blank" title="Mozilla Support: Websites melden Cookies werden blockiert – so beheben Sie das Problem">Mozilla Support: Websites melden Cookies werden blockiert – so beheben Sie das Problem</a>
				</div>
			</div>
	<?php
  		}
	?>

	<h1>Anmelden oder registrieren</h1><br />
	<?php
	if(isset($_GET['prskWsuebXcoweru']))
	{
		?>
	<div class="grid_2 alpha omega">
		<img src="images/push/ampeln-gelb.png" />
		</div>
		<div class="grid_9 alpha omega">
			<div class="tx_30_40">Ok.</div><br />
			<div class="tx_15_20">
				Wir haben Ihnen einen Link zur Passwortvergabe gesendet.<br />
				Bitte überprüfen Sie Ihren Posteingang.<br />
				Falls Sie die Mail nicht sehen, schauen Sie bitte auch in ihren Spamordner.
			</div>
		</div>
	</div>
		<?php
	}
	elseif(isset($_GET['wsiQof42wejRtsus']))
	{
		?>
		<div class="grid_2 alpha omega">
			<img src="images/push/ampeln-gelb.png" />
			</div>
			<div class="grid_12 alpha omega">
				<div class="tx_30_40">Ok.</div><br />
				<div class="tx_15_20">
					Ihre Anfrage wurde an unser Service-Team geschickt.<br /><br />
					Wir werden Ihnen schnellstmöglich die Zugangsdaten zu unserem Onlineshop zukommen lassen.<br />
					<?php
					$wt=date('w');
					$ch=date('G');
					if($wt>0 && $wt <6 &&  $ch >7 && $ch < 17)
					{				
					?>
					Bitte überprüfen Sie in den nächsten Stunden Ihren Posteingang.<br />
					<?php
					}
					else
					{
					?>
					Bitte überprüfen Sie in den nächsten Tagen  Ihren Posteingang.<br />
					<?php
					}
					?>
					Falls Sie unsere Mail nicht sehen, schauen Sie bitte auch in ihren Spamordner.
				</div>
			</div>
		</div>		
		<?php
	}
	else
	{
	?>
	<div class="grid_8 alpha white-frame">
		<strong class="tx_15_20">Neue Gewerbe-Kunden</strong><br />
		<p>Bitte <strong>drucken Sie unser Kundendatenblatt aus</strong> und senden Sie es uns vollst&auml;ndig ausgef&uuml;llt per Post, E-Mail oder Fax zur&uuml;ck. Wir richten dann sofort Ihr Nutzerkonto und - falls gew&uuml;nscht - auch Ihren Online-Zugang ein.</p>
		<p><strong>Wichtig:</strong> Wenn Sie online bestellen m&ouml;chten, markieren Sie im Formular die Option "Online-Konto einrichten" mit "JA".</p>
		<div style="margin-left: 15px">
			<a class="tx_blue" href="download/push_kundendatenblatt_DE_2013-03_form.pdf" title="Kundendatenblatt (PDF)"><img src="images/push/icons/ico_download.png" style="vertical-align: middle; margin: -3px 5px 0 0" /> Kundendatenblatt (PDF)</a>
		</div>
		<br />
		<?php include(DIR_WS_LIB . '/boxes/contact_box_neukundenbetreuung.php'); ?>
		<p>Im Bereich <a class="tx_12_15 tx_blue" href="<?= push_href_link(FILENAME_NEUKUNDENINFORMATION) ?>" title="Informationen">Informationen</a> finden Sie noch mehr Infos zur Registrierung und Ihren Vorteilen bei der push.</p>
		<a class="button-small w200 gradientgrey tx_blue tx_12_15" href="<?= push_href_link(FILENAME_NEUKUNDENINFORMATION) ?>" title="Neukundeninformation">Neukundeninformation</a>
	</div>

	<div class="grid_8 omega gray-frame">
		<strong class="tx_15_20">Registrierte Kunden</strong><br />
		<p>Wenn Sie bei uns ein Benutzerkonto besitzen, melden Sie sich bitte an.</p>
		<?php 
			echo push_draw_form('login', push_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', 'class="defaultForm label-right tx_13_20"'); 
		?>
			<div<?= $error_stack->message_set() ? ' style="height: 220px"' : '' ?>>
				<?php
					if ($error_stack->message_set()) {
				?>
						<div class="form-err-frame tx_red tx_12_15" style="padding-top: 100px; height: 90px<?= $error_stack->message_set() ? '; display: block"' : '' ?>">						
							<?= $error_stack->display(); ?>							
						</div>
				<?php
					}
				?>
				<div class="input">	
					<?php echo push_draw_input_field('email_address'); ?>
				</div>
				<label>E-Mail-Adresse<span class="tx_red">*</span></label>
				<div class="input">
					<?php echo push_draw_password_field('password'); ?>
				</div>
				<label>Passwort<span class="tx_red">*</span></label>	
				<div class="input"></div>		
			</div>
			<input type="submit" name="suche" value="Log in" class="submitBtn w130 darkblue tx_12_15"><br />
			<div class="input" style="padding-top: 15px">
				<a class="tx_blue tx_12_15" href="<?= push_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') ?>" title="Passwort vergessen?">Passwort vergessen?</a>
			</div>
			<label class="tx_12_15"><span class="tx_red">*</span>Pflichtfelder</label>
		</form>
	</div>

	<div class="grid_8 omega gray-frame" style="margin-top:20px;">
		<strong class="tx_15_20">Zugang für Bestandskunden</strong><br />
		<p>Wenn Sie bereits push Kunde sind, aber noch kein Login für unseren Onlineshop haben, nutzen Sie bitte dieses Formular.</p>
		<?php 
			echo push_draw_form('bkregister', push_href_link(FILENAME_LOGIN, 'action=bkregister', 'SSL'), 'post', 'class="defaultForm label-right tx_13_20" id="bkregister"'); 
		?>
			<div<?= $fehler['bool'] ? ' style="height: 220px"' : '' ?>>
				<?php
					if ($fehler['bool']) {
				?>
						<div class="form-err-frame tx_red tx_12_15" style="padding-top: 100px; height: 90px<?= $fehler['bool'] ? '; display: block"' : '' ?>">						
							<?= $fehler['message']; ?>							
						</div>
				<?php
					}
				?>
				<div class="input">
					<?php echo push_draw_input_field('kdnr'); ?>
				</div>
				<label>Ihre Kundennummer<span class="tx_red">*</span></label>	
				<div class="input">	
					<?php echo push_draw_input_field('bkemail_address'); ?>
				</div>
				<label>E-Mail-Adresse<span class="tx_red">*</span></label>
				<div class="input"></div>		
			</div>
			<input type="submit" name="suche" value="Login anfordern" class="submitBtn w130 darkblue tx_12_15"><br />
			<div class="input" style="padding-top: 15px">
			<!--	<a class="tx_blue tx_12_15" href="<?= push_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') ?>" title="Passwort vergessen?">Passwort vergessen?</a>-->
			</div>
			<label class="tx_12_15"><span class="tx_red">*</span>Pflichtfelder</label>
		</form>
		<script type="text/javascript">
		$(function(){
			$('#bkregister').on('submit',function(evt){
				evt.preventDefault();
				var $this = $(this);
				var error = false;
				var message = "";
				if($('input[name="kdnr"]').val() == '')
				{
					error = true;
					message += " Bitte geben Sie Ihre Kundennummer an.";
				}
				if($('input[name="bkemail_address"]').val() == '')
				{
					error = true;
					message += " Bitte geben Sie Ihre eMailadresse an.";
				}
				if(error)
				{
					alert(message)
				}
				else
				{
					document.bkregister.submit();
				}
					
			})	
		})
		</script>
	</div>
<?php

	}
	?>
</div>
<!-- body_text_eof //-->

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>
