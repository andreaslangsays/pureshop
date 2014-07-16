<?php
/*
 * by Krös
 */
	require('includes/ajax_top.php');

	$breadcrumb->reset();
	$breadcrumb->add('Shop', push_href_link(FILENAME_DEFAULT));
	$breadcrumb->add('Pimp my Cup', push_href_link(FILENAME_PIMP_MY_CUP));
	//$pmconload="onload='javascript:fancybox()'";
	require(DIR_WS_BOXES . 'html_header.php');
	

?>
<style type="text/css">

</style>
<div class="grid_16 pmcbg pmc">
<h1>SCHLUSS MIT DEM EINHEITSDESIGN AUF TO GO BECHERN !<br />
<b>INDIVIDUELLE BECHER SIND JETZT ANGESAGT !</b>
</h1>
<div class="leftside">
<h2>Mit P!mp my Cup wird Ihr Wunschbecher Realität</h2>
<ul>
	<li>Ihr individuelles Design</li>
	<li>Hochwertiger All-over-Druck, 4-farbig</li>
	<li>Schon ab 1.000 Stück</li>
	<li>Nur 3 Wochen Lieferzeit</li>
	<li>Kostenlose Muster</li>
</ul>
<p>
Die Abnahme großer Mengen von Getränkebechern im Einheitsdesign gehört von nun an der Vergangenheit an. 
Ihr Logo, Ihre Marke, Ihr Design: Nutzen Sie individuell gestaltete Becher als effektive Werbeträger und hinterlassen Sie einen bleibenden Eindruck bei Ihren Kunden.
</p>
</div>
<div class="rightside">
<img src="images/assets/pmc/pmc_lp1.png" alt />
<p>Jetzt Produkte ansehen
und Ihr kostenloses und unverbindliches Angebot anfordern.</p>
<button id="pmc_general"><span>Angebot kalkulieren</span><i></i></button>
<small>Die Seite öffnet sich in einem neuen iFrame</small>
</div>
 <div class="clearfix" ></div>
    <div id="pmc_wrap">
        <div class="vierboxtop"><img src="images/assets/pmc/cup1.png" alt="" /></div>
        <div class="vierboxtop"><img src="images/assets/pmc/cup2.png" alt="" /></div>
        <div class="vierboxtop"><img src="images/assets/pmc/cup3.png" alt="" /></div>
        <?php // <div class="vierboxtop"><img src="images/assets/pmc/cup4.png" alt="" /></div> ?>
        <div class="clearfix"></div>
        <img src="images/assets/pmc/line700.png" alt="">
        <div class="vierbox">
        <h3>Espresso Cup</h3>
        <p>Ideal für Espresso. Oder für Samplings. Und für Shots.</p>
        <button id="espressocup"><span>Zum Produkt</span><i>&nbsp;</i></button>
        </div>
        <div class="vierbox">
        <h3>Cold Cup</h3>
        <p>Single Wall Becher zum Ausschank kalter und heißer Getränke.</p>
        <button id="coldcup"><span>Zum Produkt</span><i></i></button>
        </div>
        <div class="vierbox">
        <h3>Hot Cup</h3>
        <p>Double Wall Becher – perfekt für besonders heiße Getränke.</p>
        <button id="hotcup"><span>Zum Produkt</span><i></i></button>
        </div>
	<?php 
		/*
        <div class="vierbox">
        <h3>Clear Cup</h3>
        <p>Ganz klar: Bier und Co. gehören in den Klarbecher.</p>
        <button id="clearcup"><span>Zum Produkt</span><i></i></button>
        </div>
		*/
	?>
    </div>
    <h5>P!IMP MY CUP REALISIERT IHREN INDIVIDUELL BEDRUCKTEN<br>
GETRÄNKEBECHER SCHNELL UND IN HÖCHSTER QUALITÄT.</h5>
</div>
</div>
<?php
	require(DIR_WS_BOXES . 'html_footer.php');
	require(DIR_WS_LIB . 'end.php'); 
?>