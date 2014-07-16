<?php
	require_once 'libraries/google_api/Google_Client.php';
	require_once 'libraries/google_api/contrib/Google_CalendarService.php';
	
	$events_calendar_id = 'cogkj74k70g14499ls5rejiamg@group.calendar.google.com';
	$produktschulungen_calendar_id = 'a8mkgmmquoqca7qah0k55fmpos@group.calendar.google.com';
	
	$client = new Google_Client();
	$cal = new Google_CalendarService($client);

	// get events
	$params_today = array(	"timeMin" => date(DateTime::ATOM, strtotime("today")),
							"timeMax" => date(DateTime::ATOM, strtotime("tomorrow"))); 
	$events_today = $cal->events->listEvents($events_calendar_id, $params_today);
	$schulungen_today = $cal->events->listEvents($produktschulungen_calendar_id, $params_today);
	
	$params_next_week = array(	"timeMin" => date(DateTime::ATOM, strtotime("today +1 week")),
								"timeMax" => date(DateTime::ATOM, strtotime("today +1 week +1 day"))); 
	$events_next_week = $cal->events->listEvents($events_calendar_id, $params_next_week);
	$schulungen_next_week = $cal->events->listEvents($produktschulungen_calendar_id, $params_next_week);
	
	$params_next_month = array(	"timeMin" => date(DateTime::ATOM, strtotime("today +1 month")),
								"timeMax" => date(DateTime::ATOM, strtotime("today +1 month +1 day"))); 
	$events_next_month = $cal->events->listEvents($events_calendar_id, $params_next_month);
	$schulungen_next_month = $cal->events->listEvents($produktschulungen_calendar_id, $params_next_month);
?>

<h1>Event- & Terminkalender</h1>

<div class="grid_8 alpha omega">
	<p>In unserem Google-Kalender finden Sie von terminorientierten Ideen bis hin zu unseren <a class="tx_blue" href="<?= push_href_link(FILENAME_PRODUKTSCHULUNG) ?>" title="Produktschulungen">Produktschulungen</a> alle Termine rund um die push. Klicken Sie auf einen Eintrag um mehr zu erfahren. Wir w&uuml;nschen viel Spa&szlig; damit.</p>
</div>

<div class="grid_12 alpha omega">

	<div class="grid_4 alpha gray-box calendar-box">
		<div class="gray-box-head tx_15_20">
			Termine <span class="tx_blue">HEUTE</span><br /> 
			am <?= strftime("%a, %d.%m.%G"); ?> 
		</div>		
		<div class="gray-box-body calendar-box-body">
			<?php
				foreach ($schulungen_today->getItems() as $event) {
			?>
					<div class="tx_13_15">
						<?= $event->getSummary() ?>
					</div>
			<?php
				}
				foreach ($events_today->getItems() as $event) {
			?>
					<div class="tx_13_15">
						<?= $event->getSummary() ?>
					</div>
			<?php
				}
			?>
		</div>
	</div>
	
	<div class="grid_4 gray-box calendar-box">
		<div class="gray-box-head tx_15_20">
			Termine <span class="tx_blue">IN 1 WOCHE</span><br /> 
			am <?= strftime("%a, %d.%m.%G", strtotime("+1 week")); ?> 
		</div>		
		<div class="gray-box-body calendar-box-body">
			<?php
				foreach ($schulungen_next_week->getItems() as $event) {
			?>
					<div class="tx_13_15">
						<?= $event->getSummary() ?>
					</div>
			<?php
				}
				foreach ($events_next_week->getItems() as $event) {
			?>
					<div class="tx_13_15">
						<?= $event->getSummary() ?>
					</div>
			<?php
				}
			?>
		</div>
	</div>
	
	<div class="grid_4 omega gray-box calendar-box">
		<div class="gray-box-head tx_15_20">
			Termine <span class="tx_blue">IN 1 MONAT</span><br /> 
			am <?= strftime("%a, %d.%m.%G", strtotime("+1 month")); ?> 
		</div>		
		<div class="gray-box-body calendar-box-body">
			<?php
				foreach ($schulungen_next_month->getItems() as $event) {
			?>
					<div class="tx_13_15">
						<?= $event->getSummary() ?>
					</div>
			<?php
				}
				foreach ($events_next_month->getItems() as $event) {
			?>
					<div class="tx_13_15">
						<?= $event->getSummary() ?>
					</div>
			<?php
				}
			?>
		</div>
	</div>

	<iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=460&amp;wkst=2&amp;hl=de&amp;bgcolor=%23ffffff&amp;src=cogkj74k70g14499ls5rejiamg%40group.calendar.google.com&amp;src=a8mkgmmquoqca7qah0k55fmpos%40group.calendar.google.com&amp;color=%23182C57&amp;ctz=Europe%2FBruessels" style=" border-width:0 " width="700" height="460" frameborder="0" scrolling="no"></iframe>
	
	<div class="tx_12_15 tx_light_gray blue-box" style="width: 680px; margin-top: 15px; margin-left: 0">
		Klicken Sie auf ein Ereignis im Kalender um Details zu erfahren.<br /><br />
		Und noch ein Hinweis sei erlaubt: Nat&uuml;rlich ist es in den meisten F&auml;llen unpraktikabel, Promotions nur f&uuml;r einen Tag laufen zu lassen. Daf&uuml;r sind die notwendigen Schritte wie Personalschulung, Pflege der Kassensysteme, Promotion usw. viel zu aufw&auml;ndig. Unsere Promotionideen sind Beispiele daf&uuml;r, wie sich Gastronomie noch erlebbarer machen l&auml;sst. In den meisten F&auml;llen bieten sich weitaus l&auml;ngere Promotions (z.B. auf Monatsfrist) an. 
	</div> 
</div>