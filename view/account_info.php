<?php
require('includes/ajax_top.php');

if (!push_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
}

$breadcrumb->reset();
$breadcrumb->add('Persönliche Daten', push_href_link(FILENAME_ACCOUNT_INFO, '', 'SSL'));
  
require(DIR_WS_BOXES . 'html_header.php');

include(DIR_WS_BOXES . 'static_menu.php'); 

$customerDefaultAddress = $customer->get_address_by_id($customer->customers_default_address_id);
$customerBillAddress = $customer->get_address_by_id($customer->customers_bill_address_id);
?>

<!-- body_text //-->
<div class="grid_12 alpha omega">	
	<div class="grid_12">
		<h1>Meine hinterlegten Kundendaten</h1>
	</div>
	<div class="grid_12 bottom_border tx_13_20" style="padding-bottom: 25px; margin-bottom: 30px">
		<h2>Kontaktinformationen</h2>
		<div class="grid_3 alpha">Firma:</div><div class="grid_9 omega"><?= $customerDefaultAddress['company'] ?>&nbsp;</div>
		<div class="grid_3 alpha">Adresse:</div><div class="grid_9 omega"><?= $customerDefaultAddress['street_address'] . '<br />' . $customerDefaultAddress['postcode'] . ' ' . $customerDefaultAddress['city'] ?><br /></div>
		<div class="clearfix"></div><br />
		<div class="grid_3 alpha">Telefon:</div><div class="grid_9 omega"><?= $customer->customers_telephone ?>&nbsp;</div>
		<div class="grid_3 alpha">Fax:</div><div class="grid_9 omega"><?= $customer->customers_fax ?>&nbsp;</div>
		<div class="grid_3 alpha">E-Mail:</div><div class="grid_9 omega"><?= $customer->customers_email_address ?>&nbsp;</div>
	</div>
	<div class="grid_12 bottom_border tx_13_20" style="padding-bottom: 25px; margin-bottom: 30px">
		<h2>Rechnungsadresse</h2>
		<?= push_address_format(6, $customerBillAddress, 1, ' ', '<br />') ?>
	</div>
	<div class="grid_12 bottom_border tx_13_20" style="padding-bottom: 25px; margin-bottom: 30px">
		<h2>Lieferdresse(n)</h2>
		<?php 	
			foreach ($customer->getShippingAddressesIds() as $addressId) {
				echo push_address_format(6, $customer->get_address_by_id($addressId), 1, ' ', '<br />') . '<br />';
			} 
		?>
	</div>
	<div class="grid_7 tx_13_20" style="padding-bottom: 25px; margin-bottom: 30px">
		<h2>Ihre Daten haben sich geändert?</h2>
		Zur <strong>Änderung</strong> Ihrer Daten <strong>laden Sie bitte unser Kundendatenblatt herunter</strong> und tragen dort nur ihre geänderten Daten ein.<br /><br />
		<strong>Bitte halten Sie Ihre Kundendaten immer auf dem aktuellen Stand</strong>. So sichern Sie, dass Ihre Bestellungen an die korrekte Adresse geliefert werden oder wir Sie im Falle eines Falles kontaktieren können.
		<br /><br />
		<div style="margin-left: 15px">
			<a class="tx_blue" href="download/push_kundendatenblatt_DE_2013-03_form.pdf" title="Kundendatenblatt (PDF)"><img src="images/push/icons/ico_download.png" style="vertical-align: middle; margin: -3px 5px 0 0" /> Kundendatenblatt (PDF)</a>
		</div>
		<br />
		<?php include(DIR_WS_LIB . '/boxes/contact_box_neukundenbetreuung.php'); ?>
		<br />
		Wenn Sie nur Ihr Online-Passwort ändern möchten, können Sie das im Bereich <a href="<?= push_href_link(FILENAME_ACCOUNT_PASSWORD) ?>" title="Passwort ändern" class="tx_blue tx_13_20">Passwort ändern</a> tun.
	</div>
</div>
<!-- body_text_eof //-->

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->

<?php require(DIR_WS_LIB . 'end.php'); ?>