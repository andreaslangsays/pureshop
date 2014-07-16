<?php
/*
 * Krös
 */

require('includes/ajax_top.php');

if (!push_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
}

$success = false;
if (isset($_POST['edit-submit'])) {
	if (isset($_POST['paletten'])) {
		$customer->setUsePallet(1);
	} else {
		$customer->setUsePallet(0);
	}
	$success = true;
}

$breadcrumb->reset();
$breadcrumb->add('Einstellungen', push_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));
  
require(DIR_WS_BOXES . 'html_header.php');

include(DIR_WS_BOXES . 'static_menu.php'); 
?>

<!-- body_text //-->
<div class="grid_12 alpha omega">	
	<div class="grid_12">
		<div class="grid_6 alpha gray-box" style="width: 338px !important">
			<div class="gray-box-head tx_15_20">
				Vereinbarungen für meine <br />
				Kundennummer <strong><?= $customer->selectline_customers_id ?></strong>
			</div>
			<div class="gray-box-body tx_13_20" style="padding: 10px 20px; height: 90px">
				<div>
					<?php if ($customer->customer_checkout_enabled == 1) {	?>
								<img src="images/push/icons/ico_true_S-green.png" style="vertical-align: middle; margin-right: 5px" /> Kundendatenblatt
					<?php } else { ?>
								<img src="images/push/icons/ico_false_S-red.png" style="vertical-align: middle; margin-right: 5px" /> <span style="margin-right: 15px">Kundendatenblatt</span>
								<div id="kundendatenblatt-info-button" class="simple-gray-button show-info-popup" style="margin-bottom: 5px">Info</div>
					<?php } ?>		
				</div>
				<div>
					<?php if ($customer->customer_image_usage == 1) {	?>
								<img src="images/push/icons/ico_true_S-green.png" style="vertical-align: middle; margin-right: 5px" /> Bildnutzungsvereinbarung
					<?php } else { ?>
								<img src="images/push/icons/ico_false_S-red.png" style="vertical-align: middle; margin-right: 5px" /> <span style="margin-right: 15px">Bildnutzungsvereinbarung</span>
								<div id="bildnutzungsvereinbarung-info-button" class="simple-gray-button show-info-popup">Info</div>
					<?php } ?>		
				</div>
			</div>
		</div>	
		<div class="grid_6 omega gray-box" style="width: 338px !important">
			<div class="gray-box-head tx_15_20" style="padding-top: 15px; height: 30px">
				<img src="images/push/icons/ico_discount_individual.png" style="vertical-align: middle; margin-right: 5px" /> Individueller Kundenbonus
			</div>
			<div class="gray-box-body tx_13_20" style="padding: 10px 20px; height: 90px">
				<?php if ($discount->online_rabatt_procent > 0) { ?>					
						<img src="images/push/icons/ico_discount_true-s.png" style="vertical-align: middle; margin-right: 5px" /> <?= $discount->online_rabatt_procent ?>% Onlinezusatzrabatt <br />
				<?php } ?>
				<?php
					if(count($discount->customer_table)>1)
					{
						for ($i = 0; $i < count($discount->customer_table); $i+=3)
						{
				?>
							<img src="images/push/icons/ico_discount_true-s.png" style="vertical-align: middle; margin-right: 5px" /> 
							<?= $discount->customer_table[$i+1] ?>% Rabatt auf Gesamtbestellwert <?= $discount->customer_table[$i] > 0 ? 'ab ' . $currencies->format($discount->customer_table[$i], 1) : '' ?> <br />
	
				<?php
						}
					}
				?>
			</div>
		</div>	
	</div>
	<div class="grid_12" style="margin-top: 50px">
		<h2>Einstellungen</h2>
		<?= $success ? '<div class="tx_green tx_13_20 tx_strong">Änderungen gespeichert.</div><br />' : '' ?>
	</div>
	<div class="grid_12 gray-frame" style="width: 658px !important">
		<?php echo push_draw_form('einstellungen', push_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post'); ?>
			<div style="margin-bottom: 30px">
				<div style="margin-bottom: 10px">
					<img src="images/push/icons/ico_lkw_L.png" style="vertical-align: middle; margin-right: 10px" /><span class="tx_15_20">Versand</span>
				</div>
				<input type="checkbox" name="paletten" value=""<?= $customer->customer_use_pallet != 0 ? ' checked="checked"' : '' ?>><label for="paletten" class="tx_13_20" style="margin-left: 10px">Ja, ich nehme gelieferte Paletten entgegen.</label>
			</div>
			<?php /*
			<div class="tx_13_20" style="margin-bottom: 25px">
				<div style="margin-bottom: 10px">
					<img src="images/push/icons/ico_briefumschlag.png" style="vertical-align: middle; margin-right: 10px" /><span class="tx_15_20">Benachrichtigungen</span>
				</div>
				<div style="margin-bottom: 10px">
					Ich möchte von der push GmbH per E-Mail folgende Informationen erhalten:
				</div>
				<input type="checkbox" name="bla1" value=""><label for="bla1" class="tx_13_20" style="margin-left: 10px">Bla bla bla</label><br />
				<input type="checkbox" name="bla2" value=""><label for="bla2" class="tx_13_20" style="margin-left: 10px">Bla bla bla</label><br />
				<input type="checkbox" name="bla3" value=""><label for="bla3" class="tx_13_20" style="margin-left: 10px">Bla bla bla</label>
			</div>
			*/ ?>
			<input type="submit" name="edit-submit" value="Änderungen speichern" class="submitBtn w170 darkblue tx_12_15 tx_strong">
		</form>
	</div>
</div>
<!-- body_text_eof //-->

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->

<?php require(DIR_WS_LIB . 'end.php'); ?>