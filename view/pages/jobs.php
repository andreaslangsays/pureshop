<div class="grid_12 alpha omega tx_13_20">
	<?php
	$done=false;
	$textintro = "<div class='grid_8' id='jobdescription'><p>Seit 2002 beliefern wir eine steigende Anzahl innovationsfreudiger Gastronomen und Einzelhändler mit tollen Produkten. Europaweit vertrauen mehrere tausend Kunden auf uns. Mit unserem Sortiment und der Qualität unser Logistik- und Serviceleistungen sind wir ein wichtiger Motor ihres wirtschaftlichen Erfolgs. <strong>Unser Motto: Taste it. Love it. Serve it.</strong></p>
<p>Unsere Arbeitszeiten sind sicherlich nicht immer nine to five, dafür bieten wir ein interessantes, abwechslungsreiches Arbeitsumfeld in einem netten Team und den besten Kakao der Stadt.</p>";
	if(isset($_GET['job']))
	{
		switch($_GET['job'])
		{
		
		case 'einkauf':
			echo $textintro;
		?>
			<h2 class="tx_blue">Einkauf</h2>
			<h3>Ihre Rolle bei uns</h3>
			<p>Als Mitarbeiter unserer Einkaufsabteilung verantworten Sie die Kommunikation mit unseren internationalen Lieferanten und pflegen unsere Beziehungen zu ihnen. Im Bereich Produkt-und Sortimentsmanagement sorgen Sie dafür, dass unser Datenmanagement immer auf dem neuesten Stand ist und natürlich sind Sie firm im Umgang mit Warenwirtschaftssystemen und Excel.</p>
			<h3>Ihr Profil</h3>
			<ul>
				<li>kaufmännische Ausbildung</li>
				<li>mindestens 3-5 Jahre Berufserfahrung</li>
				<li>entweder englischer Muttersprachler mit guten Deutschkenntnissen in Wort und Schrift  oder sehr gute Englischkenntnisse ebenfalls in Wort und Schrift</li>
				<li>sehr gute ERP Software und Excel Kenntnisse</li>
				<li>selbstständiges Denken</li>
				<li>Spaß und Engagement für Ihre Arbeit</li>
			</ul>
			<div class="blue-box tx_12_15" style="margin: 40px 0 40px 20px">
				<strong class="tx_12_15">Ihre vollständigen Bewerbungsunterlagen senden Sie bitte per Post an:</strong>
				push <br />
				Int'l Food & Beverage Import GmbH<br />
				 
				Mainstr. 173/174<br />
				C-56873 Bruessels<br /><br />
				<strong>Oder als E-Mail an:</strong>
				<a class="tx_blue tx_12_15" href="mailto:bewerbungen@if-bi.com" title="bewerbungen@if-bi.com">bewerbungen@if-bi.com</a>
			</div>
			<a class="button w90 gradientgrey tx_12_15 tx_center" title="Zurück" href="<?= push_href_link(FILENAME_JOBS) ?>">Zurück</a>
	<?php
			$done=true;
		break;
		case 'frontoffice':
			echo $textintro;
		?>
			<h2 class="tx_blue">Leiter Frontoffice</h2>
			<h3>Ihre Rolle bei uns</h3>
			<p>Als Leiter unseres Frontoffice verantworten Sie die Kommunikation mit unseren Kunden und pflegen unsere Beziehungen zu ihnen. Sie sorgen für einen reibungslosen Ablauf unseres Auftrags- und Reklamationsmanagements und führen unser Frontoffice Team entsprechend. Durch ihre Erfahrung im Sales sind Ihnen Begriffe wie Up- und Cross Sales nicht unbekannt und auch im Umgang mit ERP Software sind Sie ein alter Hase. </p>
			<h3>Ihr Profil</h3>
			<ul>
				<li>kaufmännische Ausbildung</li>
				<li>mindestens 3-5 Jahre Berufserfahrung</li>
				<li>entweder englischer Muttersprachler mit guten Deutschkenntnissen in Wort und Schrift oder sehr gute Englischkenntnisse ebenfalls in Wort und Schrift</li>
				<li>Erfahrung mit ERP Software</li>
				<li>Erfahrung im Sales Bereich</li>
				<li>selbstständiges Denken</li>
				<li>Spaß und Engagement für Ihre Arbeit</li>
				<li>Kommunikativ und stressresistent</li>
			</ul>
			<div class="blue-box tx_12_15" style="margin: 40px 0 40px 20px">
				<strong class="tx_12_15">Ihre vollständigen Bewerbungsunterlagen senden Sie bitte per Post an:</strong>
				push <br />
				Int'l Food & Beverage Import GmbH<br />
				 
				Mainstr. 173/174<br />
				C-56873 Bruessels<br /><br />
				<strong>Oder als E-Mail an:</strong>
				<a class="tx_blue tx_12_15" href="mailto:bewerbungen@if-bi.com" title="bewerbungen@if-bi.com">bewerbungen@if-bi.com</a>
			</div>
			<a class="button w90 gradientgrey tx_12_15 tx_center" title="Zurück" href="<?= push_href_link(FILENAME_JOBS) ?>">Zurück</a>
	<?php
			$done=true;
		break;
		case 'programmierer':
			echo $textintro;
		?>
			<h2 class="tx_blue">Programmierer</h2>
			<h3>Ihre Rolle bei uns</h3>
			<p>Als Programmierer in unserem Unternehmen helfen Sie uns unseren Online Shop und unsere Webseite so zu gestalten, dass unsere Kunden gar nicht mehr damit aufhören wollen bei uns einzukaufen. Der Schwerpunkt unserer Bemühungen im Frontend liegt natürlich auf einer möglichst geradlinigen und intuitiven Benutzerführung, während wir im Backend auf eine reibungslose Interaktion mit Warenwirtschaftssystemen, Logistikdienstleistern etc. angewiesen sind.</p>
			<h3>Ihr Profil</h3>
			<ul>
				<li>Erfahrung mit HTML</li>
				<li>Erfahrung in Shopsystemen vor allem Magento</li>
				<li>selbstständiges Denken</li>
				<li>Spaß und Engagement für Ihre Arbeit</li>
				<li>Kommunikativ und stressresistent</li>
				<li>Wünschenswert: Erfahrungen im Programmieren von mobilen Apps sowohl iOS als auch Android</li>
				<li>Vollzeit oder auch als studentischer Mitarbeiter</li>
			</ul>
			<div class="blue-box tx_12_15" style="margin: 40px 0 40px 20px">
				<strong class="tx_12_15">Ihre vollständigen Bewerbungsunterlagen senden Sie bitte per Post an:</strong>
				push <br />
				Int'l Food & Beverage Import GmbH<br />
				 
				Mainstr. 173/174<br />
				C-56873 Bruessels<br /><br />
				<strong>Oder als E-Mail an:</strong>
				<a class="tx_blue tx_12_15" href="mailto:bewerbungen@if-bi.com" title="bewerbungen@if-bi.com">bewerbungen@if-bi.com</a>
			</div>
			<a class="button w90 gradientgrey tx_12_15 tx_center" title="Zurück" href="<?= push_href_link(FILENAME_JOBS) ?>">Zurück</a>
	<?php
			$done=true;
		break;
		case 'layoutdesign':
			echo $textintro;
		?>
			<h2 class="tx_blue">Layout/Design</h2>
			<h3>Ihre Rolle bei uns</h3>
			<p>Als Designer in unserem Unternehmen helfen Sie uns einen bleibenden Eindruck zu hinterlassen, sowohl im Web- als auch im Printbereich. Außerdem arbeiten sie an der grafischen Weiterentwicklung unserer Eigenmarken. Besonders wichtig ist uns neben einem optischen Erfolgserlebnis vor allem die verkaufsorientierte Entwicklung der entsprechenden Materialien. Wir glauben schlichtweg an „Form follows Function“ und verlassen uns darauf, dass Ihr graphisches Gespür stets auch vom Antrieb geleitet wird, mit dem jeweiligen Projekt einen möglichst großen kaufmännischen Erfolg zu erzielen. </p>
			<h3>Ihr Profil</h3>
			<ul>
				<li>Erfahrung im Bereich Web und Print</li>
				<li>selbstständiges Denken</li>
				<li>Spaß und Engagement für Ihre Arbeit</li>
				<li>Kommunikativ und stressresistent</li>
				<li>Vollzeit oder auch als studentischer Mitarbeiter</li>
			</ul>
			<div class="blue-box tx_12_15" style="margin: 40px 0 40px 20px">
				<strong class="tx_12_15">Ihre vollständigen Bewerbungsunterlagen senden Sie bitte per Post an:</strong>
				push <br />
				Int'l Food & Beverage Import GmbH<br />
				 
				Mainstr. 173/174<br />
				C-56873 Bruessels<br /><br />
				<strong>Oder als E-Mail an:</strong>
				<a class="tx_blue tx_12_15" href="mailto:bewerbungen@if-bi.com" title="bewerbungen@if-bi.com">bewerbungen@if-bi.com</a>
			</div>
			<a class="button w90 gradientgrey tx_12_15 tx_center" title="Zurück" href="<?= push_href_link(FILENAME_JOBS) ?>">Zurück</a>
	<?php
		$done=true;
		break;
		default:
			$done=false;
		break;				
		}	
		
	}
	if($done){
		echo "</div>";	
	}
	if(!$done)
	{		
	?>
			<div class="grid_7"><img src="./images/assets/jobs/matrose-und-schiff_farbig.png" alt="" width="460" height="340" ></div><h1 class="grid_5 alpha omega" id="jobhead">Willkommen an Bord</h1>
			<div class="clearfix"></div>
		<div style="width:100%;border-top:1px dotted #ccc;margin-top:20px;margin-bottom:35px;"></div>
		<div class="grid_8">
			<h2>Aktuelle Stellenausschreibungen</h2>
			<p>Nine to Five? Wohl ever five to nine. Wir haben immer genug zu tun und immer zu wenig gute Leute. Bewerben Sie sich initiativ oder auf eine unserer Ausschreibungen. Wir freuen uns auf Sie.</p><br />
			
			<div class="tx_13_20 tx_strong">Wir stellen ein:</div><br /> 
			<!--<?php
				$jobsq = push_db_query("SELECT * FROM bkr_jobs WHERE active = 1 AND valid_to >= CURDATE() ORDER BY sort;");
				if (mysql_num_rows($jobsq) > 0) {
					while ($job = push_db_fetch_array($jobsq)) {
			?>
						<a href="<?= push_href_link(FILENAME_JOBS, "job=" . $job['ID']) ?>" class="job-title tx_15_20 tx_blue"><?= $job['title'] ?></a> 
				<?php
					}
				}
				?>-->
				<ul id="joblist">
				<a href="<?=push_href_link(FILENAME_JOBS, "job=einkauf") ?>"><li class="tx_15_20 tx_blue">Einkauf</li></a>
				<a href="<?=push_href_link(FILENAME_JOBS, "job=frontoffice") ?>"><li class="tx_15_20 tx_blue">Leiter Frontoffice</li></a>
				<a href="<?=push_href_link(FILENAME_JOBS, "job=programmierer") ?>"><li class="tx_15_20 tx_blue">Programmierer</li></a>
				<a href="<?=push_href_link(FILENAME_JOBS, "job=layoutdesign") ?>"><li class="tx_15_20 tx_blue">Layout / Design</li></a>
				</ul>
			
			<br /><br />
				<div class="tx_15_20 tx_blue" style="margin-left:25px;">Initiativbewerber</div><br />
				<div style="margin-left:25px;">Wenn Sie denken, dass wir füreinander bestimmt sind, handeln Sie bitte! Schreiben Sie uns! Aber bitte ohne Papier, unser Briefträger hat auch so schon genug zu leiden. Ganz zu schweigen von der Umwelt. Wir freuen uns viel lieber auf Ihre E-Mail:<br />
				<a class="tx_12_15 tx_blue" href="mailto:bewerbungen@if-bi.com" title="bewerbungen@if-bi.com">bewerbungen@if-bi.com</a>
				</div>
		</div>
	<?php
		}
	?>
</div>