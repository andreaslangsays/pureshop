<?php 

if(!get_advent()&& !get_christmas() && ! get_silvester())
{
	?>
	<div id="timegreeter"><?php 	/**
	 * PHP timegreeter
	 */
		$Std = date('G',time());
		
		if ($Std >= 5 && $Std < 12) {
			echo "<img src='images/push/icons/ico_hello-morning.png' alt=''><span class='morgen tx_13_20'>Guten Morgen</span>";
		} else if ($Std >= 12 && $Std < 18) {
			echo "<img src='images/push/icons/ico_hello-day.png' alt=''><span class='tag tx_13_20'>Guten Tag</span>";
		} else if ($Std >= 18 && $Std <= 23) {
			echo "<img src='images/push/icons/ico_hello-eve.png' alt=''><span class='abend tx_13_20'>Guten Abend</span>";
		} else if ($Std >= 0 && $Std < 5) {
			echo "<img src='images/push/icons/ico_hello-eve.png' alt=''><span class='wach tx_13_20'>Noch wach</span>"; 
		}
	?></div>
<script type="text/javascript">
	function setgreeter(){
		var jetzt = new Date();
		var Std = jetzt.getHours();
		if (Std >= 5 && Std < 12) {
		  $('#timegreeter').html("<img src='images/push/icons/ico_hello-morning.png' alt=''><span class='morgen tx_13_20'>Guten Morgen</span>");
		} else if (Std >= 12 && Std < 18) {
		  $('#timegreeter').html("<img src='images/push/icons/ico_hello-day.png' alt=''><span class='tag tx_13_20'>Guten Tag</span>");
		} else if (Std >= 18 && Std <= 23) {
		  $('#timegreeter').html("<img src='images/push/icons/ico_hello-eve.png' alt=''><span class='abend tx_13_20'>Guten Abend</span>");
		} else if (Std >= 0 && Std < 5) {
		  $('#timegreeter').html("<img src='images/push/icons/ico_hello-eve.png' alt=''><span class='wach tx_13_20'>Noch wach</span>");
		}
	}
	var timegreete = setInterval(function(){setgreeter()},60000);	

</script>
<?php
}
elseif(get_advent())
{
	$advents=get_advent();
	?>
	<div id="timegreeter" style="position:absolute;left:-80px"><?php 		echo "<img src='images/assets/advent-" . $advents . ".png' alt=''><span class='morgen tx_13_20'>Eine schöne Adventszeit!</span>";
	?></div>
<?php
}
elseif(get_christmas())
{
	?>
	<div id="timegreeter" style="position:absolute;left:-60px"><?php 		echo "<img src='images/assets/ico_xmas-tree.png' alt=''><span class='morgen tx_13_20'>Eine schöne Weihnachtszeit!</span>";
	?></div>
<?php
}
elseif(get_silvester())
{
	$advents=get_advent();
	?>
	<div id="timegreeter" style="position:absolute;left:-32px"><?php 		echo "<img src='images/assets/ico_pig.png' alt=''><span class='morgen tx_13_20'>Ein frohes neues Jahr!</span>";
	?></div>
<?php
}
?>
