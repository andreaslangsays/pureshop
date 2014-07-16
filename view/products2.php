<?php
/*
  $Idä: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $

  ösCommerce, Öpen Söürce E-Cömmerce Sölütiöns
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/ajax_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MANUFACTURERS);
require(DIR_WS_BOXES . 'html_header.php');
?>
<?php	include(DIR_WS_BOXES . 'static_menu.php'); ?>
<!-- /#left-column --> 
<!-- body_text //-->

<div class="grid_12">

<div id="cathead" class="maxihead" >
	<div id="inner-canvas" >
		<h1>Marken</h1>
		<p>Vielfalt und Qualität sind gut für Ihr Geschäft. Bei uns werden Sie fündig.</p> 
		<p>In Kürze erfahren Sie hier mehr über unsere Marken.</p><br />
		<div class="grid_12 alpha omega">
        	<div class="grid_2 alpha">
                <a class="partner-logo marke-sweets" title="Antica Torroneria" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=105_10507') ?>"> 
                    <img alt="Antica Torroneria" src="images/push/start/topmarken/ATP_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-sweets" title="Artisan Bakery" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=105_10503') ?>"> 
                    <img alt="Artisan Bakery" src="images/push/start/topmarken/ART_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-chocolate" title="Blömboom" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=102_10201') ?>"> 
                    <img alt="Blömboom" src="images/push/start/topmarken/BLOEM_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-sweets" title="Byron Bay" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=105_10501') ?>"> 
                    <img alt="Byron Bay" src="images/push/start/topmarken/BYR_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-tea" title="Celestial Seasonings" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=101_10105') ?>"> 
                    <img alt="Celestial Seasonings" src="images/push/start/topmarken/CS_logo.png">
                </a>
			</div>
            <div class="grid_2 omega">
                <a class="partner-logo marke-ice" title="Cape Dorato Smoothies" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=104_10401_1040101') ?>"> 
                    <img alt="Cape Dorato Smoothies" src="images/push/start/topmarken/CD_logo_smoothies.png">
                </a>
			</div>
            <div class="clearfix" style="margin-bottom: 20px"></div>
            <div class="grid_2 alpha">
                <a class="partner-logo marke-syrup" title="Cape Dorato Syrups" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=103_10301') ?>">  
                    <img alt="Cape Dorato Syrups" src="images/push/start/topmarken/CD_logo_syrups.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-ice" title="Cape Dorato Frappes" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=104_10401_1040102') ?>"> 
                    <img alt="Cape Dorato Frappes" src="images/push/start/topmarken/CD_logo_frappes.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-sweets" title="Crips" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=105_10511') ?>"> 
                    <img alt="Crips" src="images/push/start/topmarken/CRIP_logo_blue.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-tea" title="David Rio" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=101_10101') ?>"> 
                    <img alt="David Rio" src="images/push/start/topmarken/DR_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-sweets" title="Devonvale" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=105_10506') ?>"> 
                    <img alt="Devonvale" src="images/push/start/topmarken/DEVO_logo.png">
                </a>
			</div>
            <div class="grid_2 omega">
                <a class="partner-logo marke-tea" title="Drink me Chai" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=101_10102') ?>"> 
                    <img alt="Drink me Chai" src="images/push/start/topmarken/DMC_logo.png">
                </a>
			</div>
            <div class="clearfix" style="margin-bottom: 20px"></div>
            <div class="grid_2 alpha">
                <a class="partner-logo marke-sweets" title="Green Dream" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=105_10509') ?>"> 
                    <img alt="Green Dream" src="images/push/start/topmarken/GRE_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-tools" title="Hario" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=107_10709') ?>"> 
                    <img alt="Hario" src="images/push/start/topmarken/HARIO_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-sweets" title="Michel et Augustin" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=105_10505') ?>"> 
                    <img alt="Michel et Augustin" src="images/push/start/topmarken/MEA_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-tea" title="Revolution Tea" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=101_10104') ?>"> 
                    <img alt="Revolution Tea" src="images/push/start/topmarken/REV_logo.png">
                </a>
			</div>
            <div class="grid_2">
                <a class="partner-logo marke-tea" title="Schlürf" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=101_10103') ?>"> 
                    <img alt="Schlürf" src="images/push/start/topmarken/SCH_logo.png">
                </a>
			</div>
            <div class="grid_2 omega">
                <a class="partner-logo marke-sweets" title="The Fine Cookie" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=105_10502') ?>"> 
                    <img alt="The Fine Cookie" src="images/push/start/topmarken/TFCC_cookies_logo.png">
                </a>
			</div>
            <div class="clearfix" style="margin-bottom: 20px"></div>
            <div class="grid_2 alpha">
                <a class="partner-logo marke-syrup" title="Torani" href="<?= push_href_link(FILENAME_DEFAULT , 'cPath=103_10302') ?>"> 
                    <img alt="Torani" src="images/push/start/topmarken/TO_logo_red.png">
                </a>
			</div>            
        </div>
     </div>			
</div></div>

<!-- body_text_eof //-->

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>
