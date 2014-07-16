<?php
/*
 * Krös
 */
?>

<a href="<?php 
	if ($customer->login)
	{
		echo push_href_link(FILENAME_ACCOUNT_INFO, '', 'SSL');
	}
	else
	{
		echo push_href_link(FILENAME_LOGIN, '', 'SSL');
	} ?>" id="loginlink">
				<div id="account" class="gradientgrey">
					<img src="<?php echo DIR_WS_IMAGES . "push/icons/ico_customer.png" ;?>" alt="" class="accountimage"  /><?php

/** 
 * Top-Header 
 */
if (push_session_is_registered('customer_id')) 
{
	echo "					<span class='customername tx_blue' id='greet_new'>" . $_SESSION['customer_first_name'] . ' ' . $_SESSION['customer_last_name'] . " </span>" ."\n";
}
else
{
	echo '					<span class="customername tx_blue">' . HEADER_TITLE_LOGIN . '</span>'."\n";
}
?>
				<span class="tx_16_20"><?php echo HEADER_TITLE_MY_ACCOUNT;?></span>
	
				<span class="selectarrow" style="position:absolute;top:22px;right:15px;">&nbsp;&nbsp;&nbsp;</span>
				</div>
			</a>
			<div id="accountinfo">
			<?php 
/*
 * Import aus bkr
 *
 */
if(!isset($_SESSION['customer_id'])) {
?>
			<form class="login bottom_border tx_13_15" action="<?php echo push_href_link(FILENAME_LOGIN, 'action=process', 'SSL') ?>" method="post"  style="padding-bottom: 15px">
				   <?php echo push_draw_hidden_field('redirectto', basename( $_SERVER['PHP_SELF'])."?".push_get_all_get_params('op') )?>
					<input type="text" name="email_address" value="" placeholder="E-Mail-Adresse">
					<label for="email_address" class="tx_12_15">E-Mail-Adresse</label>
					<input type="password" name="password" value="" placeholder="Passwort">
					<label for="password"  class="tx_12_15">Passwort</label>
					<?php echo push_draw_hidden_field('redirectto', basename( $_SERVER['PHP_SELF']) . "?" . push_get_all_get_params('op') ) ?>
					<input type="submit" name="login" value="Log in"  class="submitBtn w130 darkblue tx_12_15 tx_strong" style="margin-bottom: 15px">
				<a href="#" class="tx_blue tx_12_15"  onClick="$('#login1').hide();$('#login2').show()">Passwort vergessen?</a>
             </form>
			 
			 <div class="bottom_border register">
				<span class="tx_13_20"><?=TEXT_NEW_REGISTER?></span> 
				<span class="tx_16_20 tx_blue">Gleich registrieren!</span> 
				<a href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>" class="button w110 gradientblack tx_12_15 tx_white" style="margin: 10px 0; border: 1px solid #333333; width: 132px">Registrieren <img src="images/push/icons/ico_arrow-fw_S-double_white.png" style="position: absolute; right: 11px; top: 11px" /></a>
			</div>
			
		<div class="questionDiv qdSmall" id="login2" style="display:none;">
			<h1 class="tx_13_20 tx_strong" style="margin: 10px 0">Passwort vergessen</h1>
			<h2 class="qTitle tx_12_15" style="margin-bottom: 10px">Tragen Sie Ihre bei uns hinterlegte Emailadresse ein um ein neues Passwort zugesendet zu bekommen:</h2> 
			<form class="log" action="<?php echo push_href_link(FILENAME_PASSWORD_FORGOTTEN,  'action=process', 'SSL') ?>"  method="post">
				<fieldset style="width: 100%; margin:0;padding:0;">
				<label class="tx_12_15" for="email_address">E-Mail-Adresse</label>
				<input type="text" name="email_address" value="">
				</fieldset>
				<input class="button w110 gradientgrey tx_12_15" type="submit" name="Senden" value="senden"  style="border: 1px solid #bbbbbb; color: #333333; margin: 10px 0 0 0; height: 30px; cursor: pointer">
			</form>
		</div>
			<?php }
if($customer->login)
{
?> 
	<div class="account_shortcuts tx_13_30">
<?php if(false)
	{ ?>
<!--		<a href="<?php echo push_href_link(FILENAME_ACCOUNT,'','SSL')?>" class="tx_12_30 tx_blue">Mein Konto</a>-->
<!--		<a href="<?php echo push_href_link(FILENAME_ACCOUNT_EDIT,'','SSL')?>" class="tx_12_30 tx_blue">Persönliche Daten</a> -->
<?php } ?>
		<?php if($customer->login){?><a href="<?php echo push_href_link(FILENAME_WISHLIST,'','SSL')?>" class="tx_12_30 tx_blue">Mein Sortiment</a><?php } ?>
		<?php if($customer->login){?><a href="<?php echo push_href_link(FILENAME_ACCOUNT_HISTORY,'','SSL')?>" class="tx_12_30 tx_blue">Meine Bestellungen</a> <?php } ?>
		<?php if($customer->login){?><a href="<?php echo push_href_link(FILENAME_ACCOUNT_EDIT,'','SSL')?>" class="tx_12_30 tx_blue">Einstellungen</a> 
		<a href="<?php echo push_href_link(FILENAME_ACCOUNT_INFO,'','SSL')?>" class="tx_12_30 tx_blue">Persönliche Daten</a> 
		<a href="<?php echo push_href_link(FILENAME_ACCOUNT_PASSWORD,'','SSL')?>" class="tx_12_30 tx_blue">Passwort ändern</a><?php } ?>
        <?php if($customer->login){?><a href="<?php echo push_href_link(FILENAME_ACCOUNT_INFO,'','SSL')?>" class="tx_12_30 tx_blue">Mein Konto</a> <?php } ?>
		<?php 
			if(isset($_SESSION['customer_id'])){
		?>
		<?php	
			}
		?>
	</div>


	<a class="button gradientgrey tx_12_15" style="text-align: center; width: 136px; padding: 0 9px" href="<?php echo push_href_link(FILENAME_LOGOFF,'','SSL')?>">Abmelden</a> 
<?php }
/*
 *
 */
			?>
			</div>
