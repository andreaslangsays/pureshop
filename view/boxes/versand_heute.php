<?php
if($customer->customers_id <> 36767)
{
?>
<div id="versand-heute" class="tx_20_25">
	Innerhalb 
	<span id="versand-time-left" class="tx_blue tx_25_30">
		<script language="JavaScript">
			<?php
			
			$jetzt= time();
			$timewar = (freierTag($jetzt))? strtotime('Today +1day 16:00'):strtotime('Today  16:00');
			if(freierTag($jetzt) || $jetzt > strtotime('Today  16:00'))
			{
				$dauer = 1;
				
				while( freierTag(strtotime('Today +' . $dauer . 'day 16:00'))){
						$dauer++;
				}
				$timewar = strtotime('Today +' . $dauer . 'day 16:00');
				$timedist = $timewar - $jetzt;
			}
			// IFös
			if(freierTag($jetzt) || $dauer > 1)
			{
					if($dauer==1 &&freierTag($jetzt))
					{
						$dauer=2;
					}
				?>
			TargetDate = "<?= date('m/d/Y', $timewar) ?> 4:00 PM";
			//DisplayFormat = "%%D%%d %%H%%h:%%M%%m:%%S%%s";
			DisplayFormat = "%%H%%h:%%M%%m:%%S%%s";
			CustomHourInterval = <?= $dauer*24 ?>;
			<?php
				$tage=array('0'=>'Sonntag', '1'=>'Montag', '2'=>'Dienstag', '3'=>'Mittwoch', '4'=>'Donnerstag', '5'=>'Freitag', '6'=>'Samstag');
				$lieferung = ( !freierTag($jetzt) && time() < strtotime("today 16:00:00") ? "noch heute" : "am " . $tage[date('w',$timewar)]);
			}
			else
			{
				?>
			TargetDate = "<?= date('m/d/Y', strtotime("tomorrow")) ?> 4:00 PM";
			DisplayFormat = "%%H%%h:%%M%%m:%%S%%s";
			CustomHourInterval = 24;
				<?php
				$lieferung =time() < strtotime("today 16:00:00") ? "noch heute" : "schon morgen";
			}
			?>
			BackColor = "#F5F5F5";
			ForeColor = "#1179CB";
			CountActive = true;
			CountStepper = -1;
			LeadingZero = false;
			FinishMessage = "0h:0m:0s";
		</script>
		<script language="JavaScript" src="javascript/counter.js"></script>
	</span><br />
	bestellen und Ihre Ware <br />
	wird <?= $lieferung ?> versendet 
</div>
<?php
}
else
{
?>
<div id="versand-heute" class="tx_20_25">
	Innerhalb 
	<span id="versand-time-left" class="tx_blue tx_25_30">x
		<script language="JavaScript">
			<?php
			//PHP INIT
			$tage=array('0'=>'Sonntag', '1'=>'Montag', '2'=>'Dienstag', '3'=>'Mittwoch', '4'=>'Donnerstag', '5'=>'Freitag', '6'=>'Samstag');
			$dauer = 1;
			$f=false; //freier Tag kommt vor...
			$jetzt=time();
			if(!freierTag($jetzt) && $jetzt < strtotime('Today  16:00') )
			{
				//JS INIT
				?>
				TargetDate = "<?= date('m/d/Y', $jetzt) ?> 4:00 PM";
				CustomHourInterval = 24;
				<?php
				$lieferung =  "noch heute";
			}
			else
			{
				while( freierTag(strtotime('Today +' . $dauer . 'day'))){
						$dauer++;
				}

				$timewar = strtotime('Today +' . $dauer . 'day 16:00');
				$timedist = $timewar - $jetzt;
				$distance= ceil($timedist/60/60/24);
				?>
				TargetDate = "<?= date('m/d/Y', $timewar) ?> 4:00 PM";
				CustomHourInterval = <?= $distance*24 ?>;
				<?php
					$lieferung = ($dauer ==1) ? "schon morgen" : "am " . $tage[date('w',$timewar)];
			}
			?>
			DisplayFormat = "%%H%%h:%%M%%m:%%S%%s";
			BackColor = "#F5F5F5";
			ForeColor = "#1179CB";
			CountActive = true;
			CountStepper = -1;
			LeadingZero = false;
			FinishMessage = "0h:0m:0s";
		</script>
		<script language="JavaScript" src="javascript/counter.js"></script>
	</span><br />
	bestellen und Ihre Ware <br />
	wird <?= $lieferung ?> versendet 
</div>
<?php
}
?>