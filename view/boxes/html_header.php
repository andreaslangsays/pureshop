<?php 
$beschreibung=(trim(DESCR) !="")?DESCR:CLAIM;
$beschreibung = str_replace('"',"'", $beschreibung);
$is_on_page_rueckrufservice = true;
?><!DOCTYPE html>
<html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]> <html class="ie7"> <![endif]-->
<!--[if IE 8 ]> <html class="ie8"> <![endif]-->
<!--[if IE 9 ]> <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html> <!--<![endif]-->
		<title><?php echo ( TITLEADD == "false" )? TITLE : TITLEADD;?></title>
        <meta itemprop="name" content="<?php echo TITLE ?> <?php echo ( TITLEADD == "false" )? '':"// " . TITLEADD; ?>" />
        <meta itemprop="description" content="<?php echo $beschreibung; ?>" />		
        <meta property="og:title" content="<?php echo TITLE ?> <?php echo ( TITLEADD == "false" )? '':"// " . TITLEADD; ?>"/>
        <meta property="og:url" content="<?php echo CANONICALURL;?>"/>
        <meta property="og:type" content="food"/>
        <meta property="og:image" content="http://www.if-bi.de/shop/images/<?php echo PROBI;?>"/>
        <meta property="og:site_name" content="IF-BI"/>
        <meta property="og:description" content="<?php echo $beschreibung; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="author" content="IF-BI" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <meta name="description" content="<?php echo $beschreibung; ?>" />
		<?php if (isset($_GET['cPath'])) echo '<meta name="fragment" content="!">' . "\n"; // for google crawler on products listing & gallery pages (https://support.google.com/webmasters/answer/174992) ?>
	<?php 
	if(!isset($_GET['filter'])&&!isset($_GET['page'])&&!isset($_GET['keywords'])){
		echo '<meta name=”robots” content=”index,follow” />' . "\n";
	}else{
		echo '<meta name=”robots” content=”noindex,follow” />' . "\n";
	}
	?>
	<link rel="canonical" href="<?= CANONICALURL;?>" /> 
	<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="favicon.ico">
<link rel="stylesheet" type="text/css" href="<?php echo push_href_rel_home_link('css/css.php?v42'); ?>" media="screen, print">
<!--	<link rel="stylesheet" type="text/css" href="<?php echo push_href_rel_home_link('css/main.css?v1'); ?>" media="screen, print"> 
	<link rel="stylesheet" type="text/css" href="<?php echo push_href_rel_home_link('css/al.css?v477');?>" media="screen, print">
	<link rel="stylesheet" type="text/css" href="<?php echo push_href_rel_home_link('css/boxes.css');?>" media="screen, print">
	<link rel="stylesheet" type="text/css" href="<?php echo push_href_rel_home_link('css/print.css');?>" media="print">
	<link rel="stylesheet" type="text/css" href="<?php echo push_href_link('javascript/jquery.rating.css');?>" media="screen, print" />
	<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />-->
<?php if ($is_on_page_rueckrufservice) { ?>    
    <link rel="stylesheet" href="javascript/jquery-ui-1.10.3.custom-datepicker/jquery-ui-1.10.3.custom.min.css" />
<?php } ?>
	<link href='//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,400italic' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Ubuntu+Mono:400,700' rel='stylesheet' type='text/css'>
<!--	<link rel="stylesheet" type="text/css" href="<?php echo push_href_rel_home_link('css/jquery.fancybox-1.3.4.css');?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo push_href_rel_home_link('css/960_16_col.css');?>" media="screen, print">
	<link type="text/css" rel="Stylesheet" href="<?php echo push_href_rel_home_link('css/bjqs.css');?>" />-->

		<!--[if lt IE 9]>
	<script type="text/javascript" src="javascript/html5ie.js"></script>
	<link rel="stylesheet" type="text/css" href="css/ie_fixes.css" media="screen">
 	<![endif]-->

	
	<!--[if lt IE 8]>
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
		<!--[if IE]>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<style type="text/css">
				.darkblue{
					background-color:#4195D5;
					}
			</style>
		<![endif]-->
	<!-- JQuery -->
<!-- Grab Google CDN's jQuery. fall back to local if necessary --><script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script><!-- -->
<script src="//code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<?php if ($is_on_page_rueckrufservice) { ?>
<script src="javascript/jquery-ui-1.10.3.custom-datepicker/jquery-ui-1.10.3.custom-datepicker.min.js"></script>
<!--<script src="javascript/jquery-ui-1.10.3.custom-datepicker/jquery.ui.datepicker-de.js"></script>-->
<?php } ?>
<script>!window.jQuery && document.write('<script src="javascript/jquery-1.7.1.min.js"><\/script>')</script>
<?php 

if(!$is_mobile && 'http://www.if-bi.com/shop/' == CANONICALURL )//&& $customer->customers_id == 36767)
{
	if( get_silvester())
	{

		?>
	
	<script type="text/javascript" src="javascript/fireworks.js"></script>
		<?php
	}
	else
	{
	?>
	<script type="text/javascript" src="javascript/snowflakes.js"></script>
	<style type="text/css">
	body{
		background-image:none;
		background-color:rgb(207,228,245);
		}
	</style>
	<?php
	}
}
?>
<script src="javascript/bjqs-1.3.js"></script>
	<script type="text/javascript">
	//<![CDATA[
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-17229613-1']);
			_gaq.push(['_gat._anonymizeIp']);
			_gaq.push(['_trackPageview']);
			_gaq.push(['_trackPageLoadTime']);
			setTimeout('_gaq.push([\'_trackEvent\', \'NoBounce\', \'Over 10 seconds\'])',10000);
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
			
		//]]></script>

	<!--[if IE]>
<script type="text/javascript">
jQuery(document).ready(function($){
//Assign to those input elements that have 'placeholder' attribute
$('input[placeholder]').each(function(){  
	var input = $(this);        
	$(input).val(input.attr('placeholder'));
	 input.css('color', "#AAAAAA");
	$(input).focus(function(){
		 if (input.val() == input.attr('placeholder')) {
			 input.val('');
			 input.css('color', "#4c4c4c");
		 }
	});
	$(input).blur(function(){
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
			input.val(input.attr('placeholder'));
			input.css('color', "#AAAAAA");
		}
	});
});

$('textarea[placeholder]').each(function(){  
	var input = $(this);        
	$(input).val(input.attr('placeholder'));
	 input.css('color', "#AAAAAA");
	$(input).focus(function(){
		 if (input.val() == input.attr('placeholder')) {
			 input.val('');
			 input.css('color', "#4c4c4c");
		 }
	});
	$(input).blur(function(){
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
			input.val(input.attr('placeholder'));
			input.css('color', "#AAAAAA");
		}
	});
});
});
</script>
<![endif]-->

</head>
<body>
<?php
if (!push_is_on_homepage()) {
?>
<!-- Top Anker -->

<a name="top"></a>
<header class="container_18"style="position:relative">
<div class="mmenu" id="mmenue" style="display:none;">
	<?php include(DIR_WS_BOXES . "megamenue.php"); ?>
</div>
	<div class="grid_18 alpha omega" style="position: relative; height: 100px">
		<div id="infoline">
			<?php include( DIR_WS_BOXES . "timegreeter.php"); ?>
			<?php include( DIR_WS_BOXES . "account.php"); ?>
			<?php include( DIR_WS_BOXES . "service.php"); ?>
		</div>
			<a class="grid_8 blueslogan" title="IF-BI" href="//www.if-bi.com" id="logo">
				<img alt="IF-BI" style="margin-top:0px;" src="<?php echo DIR_WS_IMAGES . 'push/push-logo.png'; ?>" > TASTE IT. LOVE IT. SHOP IT.
			</a>
			<?php /* 
			<div id="kontactImpressumHeader" class="alpha omega grid_7 tx_right">
				<a title="Impressum" class="tx_12_15 tx_blue" href="<?php echo push_href_link(FILENAME_IMPRESSUM); ?>">Impressum</a>
			</div>
			*/ ?>
			<div id="fb-like">
				<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2Fpush-Gmbh%2F376947199082200&amp;width=290&amp;height=55&amp;colorscheme=light&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;send=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:290px; height:55px;" allowTransparency="true"></iframe>
			</div>
<!-- logo -->
	</div>
	<div class="grid_18 alpha omega" style="position: relative; height: 151px;margin:0;">
			<?php
			
				include(DIR_WS_BOXES ."versand_heute.php");
		
			?>
			<?php include(DIR_WS_BOXES ."pimp_my_cup.php");?>
			<?php include(DIR_WS_BOXES ."ware_lager.php");?>
			<?php include(DIR_WS_BOXES ."mainmenu.php");?>
	</div>
	<div class="clearfix"></div><div id="set2" class="grid_18 alpha omega gradientgrey" style="width:978px;float:left;height:30px;margin-top:-30px;"> 

<!--	<div id="set2" class="grid_18 alpha omega gradientgrey ie_fix_1" style="position: relative; height: 30px;width:978px !important;"> -->
	
			<div class="grid_6 alpha omega " style="width: 500px; position: absolute; left: 0">
				<?php include(DIR_WS_BOXES . "search_form.php");?>
			</div>
			<div class="grid_6 alpha omega " style="width: 320px">
				<?php include(DIR_WS_BOXES . "cart_box.php"); ?>
			</div>
			<div class="grid_6 alpha omega " style="width: 150px">
				<?php include(DIR_WS_BOXES . "sortiment_box.php"); ?>
			</div>		
	</div>
</header>
<!-- header -->


    <div class="container_18 ie_fix_2" style="position:relative;border:1px solid #bbb;border-bottom:none; background-color:#fff; width: 978px;">
<!--<div class="container_18 ie_fix_2" style="position:relative;border:1px solid #bbb;border-bottom:none; background-color:#fff; width: 978px;top:-1px;">-->
<div class="container_16" style="position:relative; padding-bottom:60px;">
<!--[if lt IE 8]><p class=chromeframe>Ihr Browser ist <em>antik!</em> <a href="http://browsehappy.com/">Upgraden Sie zu einem anderen Browser</a> oder <a href="http://www.google.com/chromeframe/?redirect=true">installieren Sie Google Chrome Frame</a> damit Sie diese Seite richtig benutzen können.</p><![endif]-->
<div id="breadcrumbs" class="grid_8"><?php echo $breadcrumb->trail(' <span class="breadcrumb">&nbsp;</span> ');?></div>
<div class="clearfix"></div>
	<noscript>
    	<div id="no_js" class="grid_16 red-border" style="background-color: #FFEAEA; padding: 15px 0; margin-bottom: 50px">
            <div class="grid_11 tx_red tx_12_15" style="padding-left: 10px">
                <img src="images/push/icons/ico_false_S-red.png" style="vertical-align: middle; margin-right: 5px">JavaScript <br /><br />
                <?= TEXT_NO_JAVASCRIPT ?>
            </div>
		</div>
	</noscript>
<?php
}
?>