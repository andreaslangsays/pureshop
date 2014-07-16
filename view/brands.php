<?php
/*
  $Idä: shipping.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/ajax_top.php');

function tear_text($input)
{
	$array = array();
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line)
	{
		if(trim($line)<>'')
		{
			$array[]=$line ;
		}
	} 	
	return $array;		
}

function getyFeed($feed_url,$videofeed='')
{
	//YOUTUBE
	$result = array();
	if($feed_url<>'')
	{
		$content = file_get_contents($feed_url);
		$x = new SimpleXmlElement($content);
	}
	$count=0;
	$totalcounter = 0;
	$txt='';

	if(is_array($videofeed))
	{
		foreach($videofeed as $video)
		{
			$count++;
			$totalcounter++;
			//echo "<pre><code>";
			//var_dump($entry);
			//echo "</code></pre>";
			$intv=explode('?', $video);
			$vquery=explode('=',$intv[1]);
			$vid = $vquery[1];//substr($entry->guid, strpos($entry->guid, 'video:')+6) ;
			if($vid<>'')
			{
				$video_feed = file_get_contents("http://gdata.youtube.com/feeds/api/videos/$vid");
				$sxml = new SimpleXmlElement($video_feed);
				$video_title = $sxml->title;

				if($count==1)
				{
					$txt .= '<div class="page">' . "\n" ;	
				}
				$part = $entry->description;
				$src ="http://img.youtube.com/vi/$vid/0.jpg";//substr($part,strpos($part,'src="')+5);
				$txt .= "<div  data-guid='" . $vid . "?rel=0' class='video' style='padding:10px;width:230px;height:60px;overflow:hidden;'>\n";
				$txt .= "<img src='" . $src . "' style='height:60px;padding-right:10px;width:100px;float:left;'>";
				$txt .= "<div class='tx_blue tx_13_15' style='width:100px;float:left;height:60px;overflow:hidden;'>" . $video_title . "</div></div>\n\n\n";
				if($count==5)
				{
					$count=0;
					$txt .= "\n</div>\n\n";	
				}
			}
		}
	}

	foreach($x->channel->item as $entry)
	{
		$count++;
		$totalcounter++;
		//echo "<pre><code>";
		//var_dump($entry);
		//echo "</code></pre>";
		$vid = substr($entry->guid, strpos($entry->guid, 'video:')+6) ;
		//echo $vid;
		//return;
		if($count==1)
		{
			$txt .= '<div class="page">' . "\n" ;	
		}
		$part = $entry->description;
		$src =substr($part,strpos($part,'src="')+5);
		$src =substr($src,0,strpos($src,'"'));
		$txt .= "<div  data-guid='" . $vid . "?rel=0' class='video' style='padding:10px;width:230px;height:60px;overflow:hidden;'>\n";
		$txt .= "<img src='" . $src . "' style='height:60px;padding-right:10px;width:100px;float:left;'>";
		$txt .= "<div class='tx_blue tx_13_15' style='width:100px;float:left;height:60px;overflow:hidden;'>" . $entry->title . "</div></div>\n\n\n";
		if($count==5)
		{
			$count=0;
			$txt .= "\n</div>\n\n";	
		}
	}
	if($count > 0 && $count <5)
	{
		$txt .= "</div>";
	}
	$result['html'] = $txt;
	$result['counter']= $totalcounter;
	return $result;
}
function getpFeed($feed_url,$brandfeeds='') 
{
	//PINTEREST
	if($feed_url<>'')
	{
		$content = file_get_contents($feed_url);
		$x = new SimpleXmlElement($content);
	}
	$count=0;
	$totalcounter = 0;
	$txt='';
	if(is_array($brandfeeds))
	{
		foreach($brandfeeds as $brim)
		{
			$count++;
			$totalcounter++;
			if($count==1)
			{
				$txt .= '<div class="pinpage">' . "\n" ;	
			}
			$src =$brim;
			$txt .= "<div class='pinterest' data-p-href='http://www.if-bi.com/shop/images/assets/inspiration/$brim' rel='gallery' title='$brim' data-p-img='http://www.if-bi.com/shop/images/assets/inspiration/$brim' ><img src='http://www.if-bi.com/shop/images/assets/inspiration/$brim' style='height:60px;width:auto;max-width:100px;'></div>";
			if($count==15)
			{
				$count=0;
				$txt .= "\n</div>\n\n";	
			}
		}
	}
	if(isset($x))
	{
		foreach($x->channel->item as $entry)
		{
			$count++;
			$totalcounter++;
			if($count==1)
			{
				$txt .= '<div class="pinpage">' . "\n" ;	
			}
			$part = $entry->description;
			$src =substr($part,strpos($part,'src="')+5);
			$src =substr($src,0,strpos($src,'"'));
			$psrc= str_replace('/192x/', '/736x/', $src);
			$txt .= "<div class='pinterest' data-p-href='$psrc' title='$entry->title' data-p-img='$psrc' rel='gallery'><img src='" . $src . "' style='height:60px;width:auto;max-width:100px;'></div>";
			if($count==15)
			{
				$count=0;
				$txt .= "\n</div>\n\n";	
			}
		}
	}
	if($count > 0 && $count <15)
	{
		$txt .= "</div>";
	}
	$result['html'] = $txt;
	$result['counter']= $totalcounter;
	return $result;
}

	
if( (count($_GET)>0) && (!isset($_GET['redirected'])) && ($_GET['redirected'] <>'true')){
// Permanent redirection
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . push_href_link(FILENAME_DEFAULT, push_get_all_get_params(array('action'))) . "");
	exit();
}
	
	// require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);
if(isset($_GET['mid']) && is_numeric($_GET['mid']) && $_GET['mid'] > 0 )
{
	//mid contains the manufacturers_id
	$mq=push_db_query("SELECT * FROM manufacturers WHERE manufacturers_id='" . intval($_GET['mid']) . "'");	
	if($mr=push_db_fetch_array($mq))
	{
		$brand_id = $mr['manufacturers_id'];
		$brand_name = $mr['manufacturers_name'];
		$brand_image = $mr['manufacturers_image'];
		$brand_header_image = $mr['manufacturers_header_image'];
		$brand_overview = $mr['manufacturers_overview'];
		$brand_story = $mr['manufacturers_story'];
		$brand_links = $mr['manufacturers_links'];
		$brand_image_feed = $mr['manufacturers_imagefeed'];
		$brand_pinterest = $mr['manufacturers_imagerss'];
		$brand_video_feed = $mr['manufacturers_videofeed'];
		$brand_youtube = $mr['manufacturers_videorss'];
		$links=array();
		if(trim($mr['manufacturers_links'])<>'')
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $mr['manufacturers_links']) as $line)
		{
			$arg=explode('|',$line);
			$links[$arg[0]] = $arg[1];
		} 
	}

	$ourimages = tear_text($brand_image_feed);
	$bimages = 	getpFeed($brand_pinterest,$ourimages);

	$ourvideos =tear_text($brand_video_feed);
	if(trim($brand_youtube) <> '')
	{
		$bvideos = getyFeed($brand_youtube, $ourvideos);
	}
	$test = ($brand_id == '10302') ? true : false;
	$pai=999;
	$cat= $brand_id;
	while($pai > 0)
	{
		$t=push_db_fetch_array(push_db_query("SELECT categories_id, parent_id FROM categories WHERE categories_id IN (SELECT sq.parent_id FROM categories sq WHERE sq.categories_id= '" . $cat . "')"));
		$pai = $t['parent_id'];
		$cat = $t['categories_id'];
	}
	$cn = push_db_fetch_array(push_db_query('SELECT categories_name FROM categories_description WHERE language_id = "' . $languages_id . '" AND categories_id ="' . $cat . '" '));
	$primary_category_name =$cn['categories_name'];
	$primary_category = $cat;
	$subcategorie = array();
	$scq =push_db_query("SELECT  p2c.categories_id FROM products_to_categories p2c JOIN products p ON (p.products_id = p2c.products_id) WHERE p.manufacturers_id ='" . $brand_id . "' AND p.products_status = 1 GROUP BY p2c.categories_id ");
	while($subcat=push_db_fetch_array($scq))
	{
		$atop=false;
		$curcat = $subcat['categories_id'];
		while($atop == false && $curcat>0)
		{
			if($scr = push_db_fetch_array(push_db_query("SELECT c.categories_id, cd.categories_name FROM categories c JOIN categories_description cd ON (cd.categories_id = c.categories_id) WHERE (c.parent_id = '" . $primary_category . "' OR c.parent_id='" . $brand_id . "') AND c.categories_id='" . $curcat . "' AND cd.language_id= '" . $languages_id . "'")))
			{
				$atop = true;
				$subcategorie[$scr['categories_id']]= $scr['categories_name'];	
			}
			else
			{
				$txt=push_db_fetch_array(push_db_query("SELECT parent_id FROM categories WHERE categories_id ='" . $curcat . "'"));
				$curcat = $txt['parent_id']; 		
			}
		}
	}
}	
	
	$breadcrumb->reset();
	$breadcrumb->add('Shop', push_href_link(FILENAME_DEFAULT));
	$breadcrumb->add('Herstellerverzeichnis', push_href_link(FILENAME_MANUFACTURERS));
	$breadcrumb->add($brand_name, push_href_link(FILENAME_BRANDS, 'mid='.$brand_id ,'NONSSL'));
	
	require(DIR_WS_BOXES . 'html_header.php');
	$hidebrands=1;
	include(DIR_WS_BOXES . 'brand_menu.php'); 
?>
<h1 class="prefix_4 grid_12" style="margin-bottom:20px;"><?=$brand_name;?></h1>
<div class="grid_16 alpha omega" style="position:relative; min-height:500px;">
<div class="brandbackground grid_16" style="position:absolute;top:0;left:0;"><img style="witdh:940px;height:420px;box-shadow:5px 5px 5px #ccc;" src="images/assets/brands/<?=($brand_header_image == '')?'default_header.jpg':$brand_header_image?>" /></div>
<div class="overview" style="position:relative;margin-left:70px;margin-top:20px;background-image:url(images/assets/bovbg4x4.png);width:320px;padding:10px;min-height:500px;">
	<div class="overview-inside" style="width:280px;background-color:#fff;text-align:center;padding:20px;min-height:460px;">
		<!--<img src="images/assets/boverview.png" ><br>-->
		<?php
		if(trim($brand_image)<>"")
		{
		?>
			<img src="imagethumb.php?s=images/brands/<?=$brand_image?>&w=100" style="margin-top:40px;margin-bottom:20px;max-width:100px;">
		<?php
		}
		?>
		<h1 style="margin-bottom:20px;margin-left:20px;text-align:left;">Auf einen Blick</h1>
		<?php 
		if($brand_overview <> '')
		{
			echo "<ul style='margin-bottom:20px;'>";
			foreach(preg_split("/((\r?\n)|(\r\n?))/", $brand_overview) as $line)
			{
				echo "<li style='margin-bottom:5px; background-image:url(images/assets/ico_true_S-green.png);background-repeat:no-repeat;text-align:left;background-position: 0 4px; padding-left:26px;margin-left:20px;'>" . $line . "</li>";
			} 
			echo "</ul>";	
		}
		echo "<div class='autofix' style='border-bottom:1px dotted #ccc;width:220px;margin-left:30px;'></div>";
		//Fallunterscheidung wenn Torani! //HARDCODED
		if($brand_id == 10302)
		{
			$zahl = push_db_fetch_array(push_db_query("SELECT COUNT(products_id) as anz FROM products WHERE (manufacturers_id ='" . $brand_id . "' OR manufacturers_id = '1040201') AND products_status = 1 "));//	1040201
		}
		else
		{
			$zahl = push_db_fetch_array(push_db_query("SELECT COUNT(products_id) as anz FROM products WHERE manufacturers_id ='" . $brand_id . "' AND products_status = 1 "));
		}
		echo "<a href =\"" . push_href_link(FILENAME_DEFAULT, "cPath=" . $primary_category . "_" . $brand_id, NONSSL ) . "\" class='tx_blue' style='margin-left:30px;background-image:url(\"images/assets/ico_anchor.png\");background-repeat: no-repeat; background-position: 10px center; display:block;padding:10px 10px 10px 40px;text-align:left;width:180px;'>";
		echo $zahl['anz'] . " " . $brand_name . "-Produkte bei uns im Shop";
		echo "</a>";
		
		if($bimages['counter'] > 0)
		{
			echo "<div class='autofix' style='border-bottom:1px dotted #ccc;width:220px;margin-left:30px;'></div>";
			echo "<a href ='#images' class='tx_blue' style='margin-left:30px;background-image:url(\"images/assets/ico_anchor.png\");background-repeat: no-repeat; background-position: 10px center; display:block;padding:10px 10px 10px 40px;text-align:left;width:180px;'>";
			echo $bimages['counter'] . " Fotos";
			echo "</a>";

		}
		if(trim($brand_youtube) <> '')
		{
			echo "<div class='autofix' style='border-bottom:1px dotted #ccc;width:220px;margin-left:30px;'></div>";
			echo "<a href ='#videos' class='tx_blue' style='margin-left:30px;background-image:url(\"images/assets/ico_anchor.png\");background-repeat: no-repeat; background-position: 10px center; display:block;padding:10px 10px 10px 40px;text-align:left;width:180px;'>";
			echo $bvideos['counter'] . " Videos";
			echo "</a>";
		}
		/* */
		
		echo "<div class='autofix' style='border-bottom:1px dotted #ccc;width:220px;margin-left:30px;'></div>";
		if(trim($brand_story)<>'')
		{
		echo "<h4 style=\"margin-top:10px;margin-bottom:0px;padding:10px;\"> Die Geschichte</h4>";
		echo "<div class='tx_13_20' style=\"text-align:left;padding: 0 10px 10px 10px;\">" .  push_elegant_short_string($brand_story, 200) . "</div>";
		//echo "<div class='autofix' style='border-bottom:1px dotted #ccc;'></div>";
		
		?>
		<a href="#story" class="tx_blue tx_13_20">&raquo; weiterlesen</a>
		<?php
		}
		?>
	</div>
</div>

<div style="position:absolute; top:450px;right:0px; width:500px;">
<h2 style="margin-bottom:20px;"><?=$brand_name?> finden Sie in diesen Kategorien:</h2>
<ul class="mmenu grid_3">
<li class="cat_<?=$primary_category?> tx_13_30 mmenu" data-sel="<?=$primary_category?>" style="height:30px;"><a href="<?=push_href_link(FILENAME_DEFAULT, "cPath=" . $primary_category, NONSSL)?>" class="main_cat" style="display:block; line-height:30px;margin-top:5px;bottom:auto;"><?=$primary_category_name?></a></li>
<?php
foreach($subcategorie as $cat_id => $cat_name)
{
?>
	<li style="padding-left:20px;"><a href="<?= push_href_link(FILENAME_DEFAULT, "cPath=" . $primary_category . "_" . $cat_id)?>" class="tx_blue"><?=$cat_name?></a></li>
<?php		
}


?>
</ul>
<?php
//Fallunterscheidung wenn Torani! //HARDCODED
if($brand_id == 10302)
{
//1040201
	?><ul class="mmenu grid_3">
<li class="cat_104 tx_13_30 mmenu" data-sel="104" style="height:30px;"><a href="http://www.if-bi.com/testshop/Ice-Cold,c,cPath=104.html" class="main_cat" style="display:block; line-height:30px;margin-top:5px;bottom:auto;">Ice Cold</a></li>
		<li style="padding-left:20px;"><a href="http://www.if-bi.com/testshop/Ice-Cold-Torani-Ice-Cold,c,cPath=104_10402.html">Torani Ice Cold</a></li>
</ul>
<?php
}
?>
</div>
</div>
<div class='clearfix'></div>
<div style="height:100px;">&nbsp;</div>
<div class='clearfix'></div>
<h1 class=" grid_15" style="margin-left:70px;border-bottom:1px dotted #ccc;margin-bottom:10px">
Unsere Produkte von <?=$brand_name?>
</h1>
<div class="grid_15" id="brandgallery" style="margin-left:60px;height:240px;min-height:240px;position:relative;padding:0;width:900px;margin-bottom:20px;">
</div>

<div class='clearfix'></div>
<?php
if(trim($brand_story)<>'')
{
?>
<h1 class=" grid_15" style="margin-top:40px;margin-left:70px;border-bottom:1px dotted #ccc;margin-bottom:10px">
<?=$brand_name?> &ndash; die Geschichte</h1>
<div id="story" class="prefix_1 grid_10 tx_13_20" style="column-width: 280px;-moz-column-width: 280px;-webkit-column-width: 280px;"><?=$brand_story?> 
<span style="color:#666;">&nbsp;&nbsp;&#9632;</span></div>


<h1 class=" grid_15" style="margin-top:40px;margin-left:70px;border-bottom:1px dotted #ccc;margin-bottom:10px"></h1>

<?php
}
//hier Fotos etc

//////////////////////////TEST
if($bimages['counter'] > 0)
{

// http://www.pinterest.com/torani1925/feed.rss
?>
<h1 id="images" class="grid_15" style="margin-top:40px;margin-left:70px;border-bottom:1px dotted #ccc;margin-bottom:10px">
<?=$brand_name?> Inspirationen & Ideen</h1>
<div class="grid_15" style="margin-top:10px;margin-left:70px;border-bottom:1px dotted #ccc;margin-bottom:10px;position:relative;height:410px;"><div id="pincounter" class="tx_13_20" style="position:absolute;right:10px;top:-40px;"></div>
<div id="pintimg" style="height:390px; width:520px;float:left;margin-bottom:20px;text-align:center;margin-right:10px;"><a href="#" class="imgpop" rel="gallery"><img src="" style="max-height:390px;max-width:520px;"></a></div>
<div id="pinbox" >
<?php
//Load Feed and display images!
echo $bimages['html'];
?>
</div>
</div>
<div id="pinnav" style="float:right;position:relative;margin-right:10px;"><span class="pinprev" style="cursor:pointer;display:none;">0</span> <span class="pinnext" style="cursor:pointer">16-30</span></div>
<div class="clearfix"></div>
<?php
if(isset($links['Pinterest']))
{
	echo '<div class="brandlinkalpha"><img src="images/assets/pinterest.png"> <a target="_blank" href="' . $links['Pinterest'] . '" class="tx_blue">' .$brand_name. ' bei pinterest</a></div>';	
}
?>
<h1 class=" grid_15" style="margin-top:40px;margin-left:70px;margin-bottom:10px"></h1>
<script type="text/javascript">
$(document).ready(function(e) {
					$("a.imgpop").fancybox({
						'titlePosition'	: 'over',
						'transitionIn'	: 'elastic',
						'hideOnContentClick' : true,
						'transitionOut'	: 'elastic'
					});
});
$(function(){
	//Pinterest-Part
	var pimg= $('.pinterest').first().attr('data-p-img');
	var phref= $('.pinterest').first().attr('data-p-href');
	$('.pinterest').first().addClass('nowplaying');
	$('#pintimg a').attr('href', phref);
	$('#pintimg img').attr('src', pimg);
	$('.pinpage').hide().first().show();
	$('.pinterest').on('click',function(){
		$('.pinterest').removeClass('nowplaying')
		var timg= $(this).first().attr('data-p-img');
		var thref= $(this).first().attr('data-p-href');
		$('#pintimg a').attr('href', thref);
		$('#pintimg img').attr('src', timg);
		$(this).addClass('nowplaying');
	});

	var pincount= $('#pinbox .pinpage').length;
	var totalpins = 0;
	$('#pinbox .pinpage').each(function(index, element) {
		totalpins += $(this).children('.pinterest').length;
	});
	var pincurrent = 1;
	var a= (pincurrent+1)*15;
	if(a > totalpins)
	{
		a = totalpins;
	}
	if(totalpins < 15)
	{
		$('#pinnav').hide();
	}
	$('.pinnext').text( ((pincurrent*15)+1)+'-'+a )	
	$('#pincounter').html('<strong>'+ ((pincurrent*15)-14) + "-" + (((pincurrent*15)<totalpins)?(pincurrent*15):totalpins) + "</strong> von <strong>"+totalpins+"</strong> Bilder"); 

	$('.pinnext').on('click',function(){
		if(pincurrent < pincount)
		{
			pincurrent++;
			$('.pinpage').hide();
			$('#pinbox .pinpage:nth-child('+ pincurrent + ')').show();
			if(pincurrent>1)
			{
				z= (pincurrent-1)*15;
				$('.pinprev').text( (z-14)+'-'+z );
				$('.pinprev').show();
				
			}
			if(pincurrent == pincount)
			{
				$('.pinnext').hide();	
			}
			else
			{
				za= (pincurrent+1)*15;
				if(za > totalpins)
				{
					za = totalpins;
				}
				$('.pinnext').text( ((pincurrent*15)+1)+ '-' + (za) )
			}
			$('#pincounter').html('<strong>'+ ((pincurrent*15)-14) + "-" + (((pincurrent*15)<totalpins)?(pincurrent*15):totalpins) + "</strong> von <strong>"+totalpins+"</strong> Bilder"); 
		}
	});

	$('.pinprev').on('click',function(){
		if(pincurrent > 1)
		{
			pincurrent--;
			$('.pinpage').hide();
			$('#pinbox .pinpage:nth-child('+ pincurrent + ')').show();	
			if(pincurrent==1)
			{
				$('.pinprev').hide();
			}
			else
			{
				z= (pincurrent-1)*15;
				$('.pinprev').text( (z-14)+'-'+z );
			}
			if( pincount > 1)
			{
				$('.pinnext').show();
				z= (pincurrent+1)*15;
				if(z > totalpins)
				{
					z = totalpins;
				}
				$('.pinnext').text( ((pincurrent*15)+1)+'-'+z )
			}
			$('#pincounter').html('<strong>'+ ((pincurrent*15)-14) + "-" + (((pincurrent*15)<totalpins)?(pincurrent*15):totalpins) + "</strong> von <strong>"+totalpins+"</strong> Bilder");
		}
	});
});
</script>	
<?php

}
/////////////////////TEST
if(trim($brand_youtube) <> '')
{

?>
<h1 id="videos" class=" grid_15" style="padding-top:40px;margin-left:70px;margin-top:10px;border-bottom:1px dotted #ccc;margin-bottom:10px">
<?=$brand_name?> Videos</h1>
<div class="grid_15" style="margin-top:0px;margin-left:70px;position:relative"><div id="ytcounter" class="tx_13_20" style="position:absolute;right:10px;top:-40px;"></div>
<iframe id="youtubeplayer" width="640" height="390" src="" frameborder="0" allowfullscreen style="float:left;margin-top:20px;margin-right:10px;"></iframe>
<div id="ytprev" style="height:410px;margin-top:20px;overflow:hidden;margin-left:10px;">
<?php
//Load Feed and display images!
echo $bvideos['html'];
?>
</div>
<h1 class=" grid_15" style="margin-left:0px;border-bottom:1px dotted #ccc;margin-bottom:10px"></h1>
<div id="ytnav" style="float:right;position:relative;"><span class="ytprev" style="cursor:pointer;display:none;">0</span> <span class="ytnext" style="cursor:pointer">6-10</span></div>
</div>
<?php
if(isset($links['Youtube']))
{
	echo '<div class="brandlinkalpha"><img src="images/assets/youtube.png"> <a target="_blank" href="' . $links['Youtube'] . '" class="tx_blue">' .$brand_name. ' bei youtube</a></div>';	
}
?>
<script type="text/javascript">
$(function(){
	var fguid = $('.video').first().attr('data-guid');
	$('#youtubeplayer').attr('src', '//www.youtube-nocookie.com/embed/'+fguid);
	$('.video').first().addClass('nowplaying');
	$('.video').on('click', function(){
		$('.video').removeClass('nowplaying');
		var $this = $(this);
		var guid = $this.attr('data-guid');
		$('#youtubeplayer').attr('src', '//www.youtube-nocookie.com/embed/'+guid);
		$this.addClass('nowplaying')
	})
	
	var count= $('#ytprev .page').length;
	var totalvids = 0;
	$('#ytprev .page').each(function(index, element) {
		totalvids += $(this).children('.video').length;
	});
	var current = 1;
	var a= (current+1)*5;
	if(a > totalvids)
	{
		a = totalvids;
	}
	if(totalvids < 5)
	{
		$('#ytnav').hide();
	}
	$('.ytnext').text( ((current*5)+1)+'-'+a )	
	$('#ytcounter').html('<strong>'+ ((current*5)-4) + "-" + (((current*5)<totalvids)?(current*5):totalvids) + "</strong> von <strong>"+totalvids+"</strong> Videos"); 

	$('.ytnext').on('click',function(){
		if(current < count)
		{
			current++;
			$('.page').hide();
			$('#ytprev .page:nth-child('+ current + ')').show();
 
			if(current>1)
			{
				z= (current-1)*5;
				$('.ytprev').text( (z-4)+'-'+z );
				$('.ytprev').show();
				
			}
			if(current == count)
			{
				$('.ytnext').hide();	
			}
			else
			{
				za= (current+1)*5;
				console.log(za)
				if(za > totalvids)
				{
					za = totalvids;
				}
				$('.ytnext').text( ((current*5)+1)+ '-' + (za) )
			}
			$('#ytcounter').html('<strong>'+ ((current*5)-4) + "-" + (((current*5)<totalvids)?(current*5):totalvids) + "</strong> von <strong>"+totalvids+"</strong> Videos"); 
		}
	});
	
	$('.ytprev').on('click',function(){
		if(current > 1)
		{
			current--;
			$('.page').hide();
			$('#ytprev .page:nth-child('+ current + ')').show();	
			if(current==1)
			{
				$('.ytprev').hide();
			}
			else
			{
				z= (current-1)*5;
				$('.ytprev').text( (z-4)+'-'+z );
			}
			if( count > 1)
			{
				$('.ytnext').show();
				z= (current+1)*5;
				if(z > totalvids)
				{
					z = totalvids;
				}
				$('.ytnext').text( ((current*5)+1)+'-'+z )
			}
			$('#ytcounter').html('<strong>'+ ((current*5)-4) + "-" + (((current*5)<totalvids)?(current*5):totalvids) + "</strong> von <strong>"+totalvids+"</strong> Videos"); 
		}
	});
	
})</script>

<?php

}

if($customer->customers_id == 36767)
{
	$q=push_db_query("SELECT * FROM downloads WHERE ref_type='brand' AND ref_id='" . (int)$_GET['mid'] . "' AND active=1");
	if(push_db_num_rows($q)>0)
	{
		$counter= push_db_num_rows($q);
	?>
	<h1 id="downloads" class=" grid_15" style="padding-top:40px;margin-left:70px;margin-top:10px;border-bottom:1px dotted #ccc;margin-bottom:10px">
	<?=$brand_name?> Marken und Produktinformationen</h1>
	<div class="grid_15 prefix_1 alpha omega">
	<?php
	$pent=5;//anzahl der Einträge pro seite
	$zuze=0;//Bis zu diesem Zähler kein neuer div!
	$ze=0;//Zähler
	$disp='block;';
	while($t=push_db_fetch_array($q))
	{
		$ze++;
		if(($counter > $pent)&& ($ze>$zuze))
		{
			
			
			echo "<div class='dlpage' style='display:" . $disp . "' >";
			$disp='none;';
		}
		?>
		<div class="grid_3">
		
		<a href="download/brand/<?=$t['filename']?>" ><div style="overflow:hidden;height:158px;width:158px;padding:0;border:1px solid #ccc;margin-bottom:10px;"><img src="includes/boxes/previewdownload.php?xid=<?=$t['id']?>&w=160&h=250"></div></a>
		<a href="download/brand/<?=$t['filename']?>" style="margin-top:10px;" class="tx_blue tx_12_15"><img src="images/assets/ico_download.png" style="float:left;margin-top:8px;;margin-right:10px;"><?=$brand_name?> <?=$t['name']?></a>
		</div>
		<?php
		if(($counter > $pent)&& ($ze>$zuze))
		{
			$zuze +=$pent;
			echo "</div>";
		}

	}
	?>
	</div>	
	<?php
	if($counter > $pent)
	{
		//here some pager!
		$pages=$zuze/$pent;
		$curpage=2;
		?>
		<div id="dlnav" 
		style="float:right;position:relative;margin-right:10px;"><span 
		class="dlprev" style="cursor:pointer;display:none;">0</span> <span 
		class="dlnext" style="cursor:pointer"><?=($curpage*$pent)-($pent-1)	?> - <?=$curpage*$pent?></span></div>
<div class="clearfix"></div>
		<?php
	}
}
}



if(count($links) > 0)
{
	
	function beautify_url($txt)
	{
		$txt = str_ireplace('http://','',$txt);
		if(strpos($txt,'?'))
		{
			$txt = substr($txt,0,strpos($txt,'?'));
		}
		$txt = trim($txt,'/');
		$txt = rtrim($txt,'/');
		return $txt;
	}
	?>
	<h1 id="links" class=" grid_15" style="padding-top:40px;margin-left:70px;margin-top:10px;border-bottom:1px dotted #ccc;margin-bottom:10px">
	<?=$brand_name?> Links</h1>
	<div class="grid_5 prefix_1">
	<?php
	if(isset($links['Home']))
	{
		?><a href="<?=$links['Home']?>" target="_blank" class="tx_blue"><?=beautify_url($links['Home'])?></a><?php	
	}
	?>
	</div>
	<div class="grid_9">
	<?php
	if(isset($links['Facebook']))
	{
		echo '<div style="margin-bottom:10px;"><a  class="tx_blue" target="_blank" href="' . $links['Facebook'] . '"><img src="images/assets/facebook.png" style="vertical-align:middle;margin-right:10px;"> ' .$brand_name. ' bei facebook</a></div>';	
	}
	if(isset($links['Twitter']))
	{
		echo '<div style="margin-bottom:10px;"><a  class="tx_blue" target="_blank" href="' . $links['Twitter'] . '"><img src="images/assets/twitter.png" style="vertical-align:middle;margin-right:10px;"> ' .$brand_name. ' bei twitter</a></div>';	
	}
	if(isset($links['Pinterest']))
	{
		echo '<div style="margin-bottom:10px;"><a  class="tx_blue" target="_blank" href="' . $links['Pinterest'] . '"><img src="images/assets/pinterest.png" style="vertical-align:middle;margin-right:10px;"> ' .$brand_name. ' bei pinterest</a></div>';	
	}
	if(isset($links['Youtube']))
	{
		echo '<div style="margin-bottom:10px;"><a  class="tx_blue" target="_blank" href="' . $links['Youtube'] . '"><img src="images/assets/youtube.png" style="vertical-align:middle;margin-right:10px;"> ' .$brand_name. ' bei youtube</a></div>';	
	}
}
?>


</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#brandgallery').load('includes/modules/ajax/brandproducts.php?bid=<?=$brand_id?>');
	$('#brandgallery').on('click','.brandprev',function(e){
		var bpage = $('.brandprev').attr('data-page');
		$('#brandgallery').load('includes/modules/ajax/brandproducts.php?bid=<?=$brand_id?>&p='+bpage);	
	});
	$('#brandgallery').on('click','.brandnext',function(e){
		var bpage = $('.brandnext').attr('data-page');
		$('#brandgallery').load('includes/modules/ajax/brandproducts.php?bid=<?=$brand_id?>&p='+bpage);				
	});
	
<?php 	if(preg_match('/MSIE/i', $browser))
		{?>
			$('.ie7 #story').columnize({ width: 230, height:190 });
			$('.ie8 #story').columnize({ width: 230, height:190 });
			$('.ie9 #story').columnize({ width: 230, height:190 });
			$('#story div').css({	'padding': '5px',
									'width':'230px',
									'float':'left'});
		<?php 
		}?>
})
</script>
<?php 	if(preg_match('/MSIE/i', $browser))
		{?>
<script type="text/javascript" src="javascript/jquery.columnizer.js"></script>
		<?php 
		}?>
		
<!-- BRAND NAVI -->
<h1 class=" grid_15" style="margin-top:40px;margin-bottom:10px"></h1>
<div class="clearfix"></div>		
<div id="bbrandnav"><a href="products.php" style="left:0;top:0;"><img src="images/assets/ico_arrow-rw_S-double.png">  &nbsp;&nbsp;<span class="tx_12_15">Zur Übersicht</span></a>
<span class="tx_12_15" style="position:absolute; top:5px; left:126px;display:block;"><?php
$brandcountstring = $currentbrandposition."/".$totalnumberofbrands;
?>Marke <?= $brandcountstring ?></span>
<?php
if(trim($prevbrandlink) <> "")
{
	?><a href="<?=$prevbrandlink?>"  style="right:40px;top:0;width:20px;"><img src="images/assets/ico_arrow-rw_S.png" border="0"/></a>
	<?php
}
if($currentbrandposition < $totalnumberofbrands)
{
	?><a href="<?=$nextbrandlink?>" style="right:0px;top:0;width:20px;"><img src="images/assets/ico_arrow-fw_M.png" border="0"/></a>
	<?php
}
?>
</div>
<div class="clearfix"></div>	
<?php
	require(DIR_WS_BOXES . 'html_footer.php');
	require(DIR_WS_LIB . 'end.php'); 
?>