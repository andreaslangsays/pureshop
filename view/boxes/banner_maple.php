<?php
/*
 * Maple Moose Banner
 * 2013
 */
?>
<style>
#elkhint{
	position:absolute;
	top:15px;
	left:375px;
	z-index:200;
	font-size:12px;
}

#elkhint strong{
	color:#ffffff;
}
#elkhint .pointerleft{
	position:relative;
	float:left;
	top:24px;
	width:0px;
	height:0px;
	border-top:12px solid transparent;
	border-right:12px solid #330102;
	border-bottom:12px solid transparent;
	border-left:12px solid transparent;

	}
#elkhint .pointerright{
	float:right;
	padding:6px 12px;
	background-color:#330102;
	color:#ffffff;
		box-shadow:6px 6px 6px #888888;	
}
#elkhint.elkup{
	top:-8000px;
}
#elk{
	cursor:pointer;
	position:absolute;
	width:254px;
	height:161px;
	/*background-image:url(images/assets/maple/MapleMoose_2d_0001_head-eyes-straight.png);*/
	background-image:url(images/assets/maple/MapleMoose_2d_0001_head-eyes-down.png);
	background-repeat:no-repeat;
	top:-25px;
	left:190px;
	z-index:0;
}
#elk:hover{
	position:absolute;
	width:254px;
	height:161px;
	background-image:url(images/assets/maple/MapleMoose_2d_0001_head-eyes-right.png);
	background-repeat:no-repeat;
	top:-25px;
	left:190px;
	z-index:0;

}
#elk.elkup{
	background-image:url(images/assets/maple/MapleMoose_2d_0001_head-eyes-straight.png);
	/*background-image:url(images/assets/maple/MapleMoose_2d_0001_head-eyes-down.png);*/
	z-index:5;
	cursor:auto;
}
#elktoggler{
position:absolute;
left:40px;
top:48px;
font-size:12px;
color:#320001;
cursor:pointer;

}
#elktoggler:hover{
	text-decoration:underline;
	}
#elkadvert{
position:absolute;
left:10px;
top:73px;
width:700px;
height:460px;
background-image:url(images/assets/maple/ad_dr_mm-teaser.jpg);
z-index:4;
}
#invisible-images{
position:absolute;
top:-5000px;
width:254px;
height:161px;
/*background-image:url(images/assets/maple/MapleMoose_2d_0001_head-eyes-straight.png);*/

	
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('#elk').on('hover',function(){
		$('#elkhint').show();
		});
	$('#elk').on('mouseout',function(){
		$('#elkhint').hide();
	});
	$('#elk').on('click',function(){
		$('#elkhint').hide();
		$('#elkhint').addClass('elkup');
		$('#elk').addClass('elkup');
		$('#elkadvert').show();
		$('#elktoggler').hide();
		_gaq.push(['_trackEvent', 'Startseitenbanner', 'Elch!']);
	})
	$('#elkadvert div').on('click', function(e){
			$('#elkadvert').hide();
			$('#elkhint').removeClass('elkup');
			$('#elk').removeClass('elkup');
			$('#elktoggler').show();
			})
	$('#elktoggler').on('click',function(){
			$('#elktoggler span').toggle();
			$('#elk').toggle();
		});
});
</script>
<div id="invisible-images">
<img src="images/assets/maple/ad_dr_mm-teaser.jpg" />
<img src="images/assets/maple/MapleMoose_2d_0001_head-eyes-straight.png" />
<img src="images/assets/maple/MapleMoose_2d_0001_head-eyes-right.png" />
<img src="images/assets/maple/MapleMoose_2d_0001_head-eyes-down.png" />
</div>
<div id="elktoggler"><span style="display:block">Anzeige ausblenden</span><span style="display:none">Anzeige einblenden</span></div>
<div id="elk"></div>
<div id="elkhint" style="display:none;"><div class="pointerleft"></div><div class="pointerright">Ich weiß was!<br>Ich weiß was!<br><strong>(Klick mich an)</strong></div></div>
<div id="elkadvert" style="display:none;"><div style="position:absolute;right:0;top:0;width:120px;height:40px;cursor:pointer;"></div></div>


