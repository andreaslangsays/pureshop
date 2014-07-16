<?php
/*
 * Krös
 */

require('includes/ajax_top.php');

if (!push_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
}

if ($customer->customer_checkout_enabled == 0) {
	// only customers allowed
	push_redirect(push_href_link(FILENAME_DEFAULT));
}

$error = false;
$success = false;
if (isset($_POST['bnv-submit'])) {
	if (isset($_POST['bnv-accepted']) && isset($_POST['bnv-printed'])) {
		$customer->setImageUsage(1);
		$success = true;		
	} else {
		$error = true;
	}
}

$breadcrumb->reset();
$breadcrumb->add('Bildnutzungsvereinbarung', push_href_link(FILENAME_BILDNUTZUNGSVEREINBARUNG, '', 'SSL'));
  
require(DIR_WS_BOXES . 'html_header.php'); 
?>

<!-- body_text //-->
<div class="grid_16">	

<?php if ($success) { ?>
	<div class="tx_25_30">Vereinbarung ...</div><br />
	<div class="grid_2 alpha omega">
		<img src="images/push/green-light.png" />
	</div>
	<div class="grid_9 alpha omaga">
		<div class="tx_30_40">Vielen Dank!</div><br />
		<div class="tx_15_20">
			<strong>Sie können unseren Downloadservice jetzt nutzen.</strong><br />
			Sollten Sie diesbezüglich Fragen haben, wenden Sie sich bitte an unseren Kundenservice unter 030-284 700-0.
		</div><br /><br />
		<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 170px" href="<?= push_href_link(FILENAME_ACCOUNT_INFO) ?>">Weiter zum Mein Konto<img src="images/push/icons/ico_arrow-fw_S-double_white.png" style="position: absolute; right: 11px; top: 11px"></a>
	</div>
<?php } else { ?>
	<div class="tx_25_30">Vereinbarungen</div><br />
	<div class="grid_6 alpha gray-frame" style="width: 418px !important">
		<h2 style="padding-bottom: 15px; margin-bottom: 15px" class="bottom_separator">Nutzungsvereinbarung für Fotos, Graphiken und Logos</h2>
		
		<p>Die uns von der Firma Int’l Food & Beverage Import GmbH (nachfolgend „push“) in digitaler Form übersandten Bilder, Graphiken und Fotos (nachfolgend „Bilder“) unterliegen Urheberrechten, die entweder bei der push oder bei deren Partnern liegen, die im Zweifelsfalle durch die push vertreten werden.</p>
		<p>Die push räumt uns widerruflich ein unentgeltliches Nutzungsrecht an den Bildern ein.</p>
		<p>Hiermit bestätigen wir, dass wir diese Urheberrechte anerkennen und zu jedem Zeitpunkt beachten werden. <br />
		Wir bestätigen darüber hinaus, dass push die Nutzung der Bilder zu jedem Zeitpunkt ohne Angabe von Gründen einschränken oder untersagen kann.</p>
		<p>"Nutzung" im Sinne dieser Vereinbarung bedeutet das öffentliche Zeigen der Bilder bzw. deren Verwendung in kommerziellen oder nicht kommerziellen Publikationen oder anderweitigen Veröffentlichungen, insbesondere auch in digitaler Form. <br />
		Die Nutzung ist ausdrücklich nur im Zusammenhang mit der Vermarktung der durch push vertriebenen Produkte und Marken zulässig. Eine Nutzung der Bilder zur Vermarktung anderer Marken oder Produkte ist nicht zulässig und kann  Schadensersatzforderungen nach sich ziehen.</p>
		<p>Eine Modifikation und/oder Bearbeitung der Bilder ist nicht Gegenstand dieser Vereinbarung.</p>
		<p>Eine Weitergabe des Nutzungsrechts an Dritte ist nicht Gegenstand dieser Vereinbarung.</p>
		<p>Mit unserer Unterschrift und der Nutzung der Bilder akzeptieren wir die Vereinbarung ohne Einschränkungen.</p>
		<p>Sollten einzelne Bestimmungen dieser Vereinbarung unwirksam sein oder werden, so berührt dies die Gültigkeit der übrigen Bestimmungen dieser Vereinbarung nicht. Die Parteien verpflichten sich, unwirksame Bestimmungen durch neue Bestimmungen zu ersetzen, die der in den unwirksamen Bestimmungen enthaltenen Regelungen in rechtlich zulässiger Weise gerecht werden.</p>
		
		<div id="bildnutzungsvereinbarung-form">
			<div style="padding-top: 25px; margin-top: 25px" class="top_separator tx_25_30">Zustimmung</div>
			<p>Falls Sie unser <strong>Bildmaterial für Werbezwecke</strong> o.ä. nutzen möchten, benötigen wir Ihre Zustimmung zu dieser Vereinbarung.</p>
			<p>Bis auf wenige Ausnahmen können wir Ihnen alle Bilder, die Sie auf unseren Publikationen finden, zur Verfügung stellen. Diese können Sie entweder auf den jeweiligen Produktseiten <strong>selbst herunterladen oder</strong> bei unserem Kundenservice <strong>anfordern</strong>.</p>
			
			<div class="grid_6 alpha omega tx_13_20" style="margin-top: 20px">
				<form id="form-bnv" action="<?= push_href_link(FILENAME_BILDNUTZUNGSVEREINBARUNG) ?>#form-bnv" method="post">
					<?php if ($error) { ?>
								<div class="tx_red tx_strong tx_13_20">Sie müssen die Bedingungen zustimmen.</div><br />
					<?php } ?>
					<div class="grid_1 alpha" style="width: 20px">
						<input type="checkbox" name="bnv-accepted" />
					</div>
					<div class="grid_5 alpha" style="margin-bottom: 15px">
						Ich habe die Nutzungsvereinbarung für Fotos, Graphiken und Logos gelesen und stimme den Bedingungen zu.
					</div>
					<div class="grid_1 alpha" style="width: 20px">
						<input type="checkbox" name="bnv-printed" />
					</div>
					<div class="grid_5 alpha" style="margin-bottom: 30px">
						Ich habe die Nutzungsvereinbarung gedruckt.
					</div>
					<input class="submitBtn w200 darkblue tx_12_15 tx_strong" type="submit" value="Der Vereinbarung zustimmen" name="bnv-submit" />
				</form>
			</div>
		</div>
	</div>
<?php } ?>

</div>
<!-- body_text_eof //-->

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->

<?php require(DIR_WS_LIB . 'end.php'); ?>