<?php

//include('../../application_top.php');
$kool='';
if(isset($_GET['osCzid'])){$kool='&osCzid='.$_GET['osCzid'];}
$heute = date("Y-m-d");

//echo $heute." <br> ";
//echo $heute; /////<- war nur zu Testzwecken nötig
//Definieren der Veranstaltungen:
/***************************************************************************************************************************************************************
Diese Datei bezieht sich auf shop/static/Veranstaltungen.php
jede if-Abfrage wird mit dem Veranstaltungsdatum in Monat.Tag-Formatierung vorbereitet ($vera)
der Link innerhalb des HTML-Blocks beinhaltet die Übergabe von schoko mit Wert, dieser Wert muß in Veranstaltungen.php ausgewertet werden
der entsprechende Bereich ist der Anfang des Body-Bereichs
$bereits=1 entspricht der break-Anweisung in switch [Andreas Lang 22.02.2007] [* Muß noch optimiert werden!*]
******************************************************************************************************************************************************************/
//Update: jetzt soll das ganze Datenbankbasiert werden!

$soein_query= "Select * from bkr_veranstaltungen WHERE datum>='".$heute."' ORDER BY datum ASC";
$ergebnis=push_db_query($soein_query);
$txt=push_db_fetch_array($ergebnis);
//echo"<br>";
//var_dump($txt);


?>
<div id="veranst_bereich" >
<div style="position:relative; top:77px; left:8px;text-align:left; color: #742707 ;">
<b>Wann</b>: <?echo $txt['tag'].'.'.$txt['monat'].'.'.$txt['jahr'];?><br />
<b>Um</b>: <?echo $txt['zeit'];?> Uhr<br />
<b>Wo</b>: hier bei uns<br/ >
<b>Dauer</b>: <?echo $txt['dauer'];?> Std<br />
<b>Preis</b>: <?echo $txt['preis'];?> &euro;<br /><br>
<b>Mehr Infos</b>: <br /><a href="http://www.Bruesselser-Kakaoroesterei.de/shop/static/veranstaltungen.php?vera=<?php echo $txt['ID'].$kool;?>">hier klicken</a >
&nbsp;<br />
</div >
<div sytle="position:relative"></div>
<div style="position:relative; bottom:-73px; left:36px; font-size:12px ; font-family:verdana ; text-align: center; color: #742707 ;">
<b> <a href="http://www.Bruesselser-Kakaoroesterei.de/shop/static/veranstaltungen.php?vera=<?php echo $txt['ID'].$kool;?>"><?echo $txt['titel'];?></a></b ><br />
</div>

<?PHP





?>
