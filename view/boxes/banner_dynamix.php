<?php

function display_banner_box($id)
{
	$bqc="SELECT * FROM startboxes WHERE box_id='" . $id . "'";
	if($banner= push_db_fetch_array(push_db_query($bqc)))
	{
	
		$external = "";
		if(substr_count($banner['box_link'], 'if-bi.com') <1)
		{
			$external = "target='_blank'";
		}
?>
	<a href="<?= $banner['box_link'] ?>" title="<?=$banner['box_title']?>" class="box<?=$banner['box_width']?>x<?=$banner['box_height']?><?= ' ' . $banner['box_group']?>" style="background: url('./images/assets/box_bg/<?=$banner['box_bg']?>')" <?= $external ?> onClick="_gaq.push(['_trackEvent', 'Startseitenbanner', '<?=$banner['box_title']?>']);">
<?php
/*
	<!--<div style="width: 420px; height: 180px; padding: 0px; background-color: #ffffff">
		<!--<img src="images/assets/drei_gewinnt/3-gewinnt_teaser_shop_460x220.jpg" style="margin-right: 20px; float: left" />
		<div class="tx_15_20" style="color: #333; margin-bottom: 20px">
			
		</div>
		<div class="tx_25_30 tx_strong" style="color: #333">
			
		</div>-- >
	</div>-->
	*/
		if($banner['box_button'] <> ''){
			
	?>
		<span class="<?= ($banner['box_button_class'] == '')? 'gradientblack' : $banner['box_button_class'] ?> button w130 tx_12_15 tx_white" style="position:absolute;top:<?= $banner['box_button_top']?>px;left:<?= $banner['box_button_left']?>px;<?=($banner['box_button_width']>0)?'width:' . $banner['box_button_width'] . 'px;':''?>display:block;padding:6px 10px;"><?=$banner['box_button']?><img src="images/push/icons/ico_arrow-fw_S_white.png"></span>
	<?php
		}
		if(strtoupper($banner['box_title']) == 'KakaoCAMPUS')
		{
			//insert counter
			$days_left = floor((mktime(0, 0, 0, 9, 16, 2013) - time()) / 86400);
			if($days_left >0)
			{
				echo "<div style=\"position:absolute;letter-spacing:-1px;right:35px;bottom:39px;color:#fff;font-size:30px;font-weight:bold;\">".$days_left."</div>";
			}
		}
		?>
	</a>
<?php
	}
}

$blid = 0;//order Number to group banners and select randomly
$i = 0;
$bannerq=push_db_query("SELECT * FROM startboxes WHERE box_start_date <= CURDATE() AND box_end_date >= CURDATE() AND box_active =1 ORDER BY box_order ASC");
while($bannerd=push_db_fetch_array($bannerq))
{
	$i++;
	$bcid = $bannerd['box_order'];
	if( $blid > 0 && $bcid <> $blid)
	{
		if(is_array($banarr))
		{
			//$banarr = array();
			$banzahl = count($banarr);
			if($banzahl==1)
			{
				display_banner_box($banarr[0]);
			}
			else
			{
				$banzufall =  rand(1,$banzahl);
				$banzufall--;
				display_banner_box($banarr[$banzufall]);
			}
			unset($banarr);
		}

		if(!is_array($banarr))
		{
			$banarr = array();	
		}
		$banarr[]=$bannerd['box_id'];
	}
	else
	{
		if(!is_array($banarr))
		{
			$banarr = array();	
		}
		$banarr[]=$bannerd['box_id'];

	}
	$blid=$bcid;
}
if(is_array($banarr))
{
	//$banarr = array();
	$banzahl = count($banarr);
	if($banzahl==1)
	{
		display_banner_box($banarr[0]);
	}
	else
	{
		$banzufall =  rand(1,$banzahl);
		$banzufall--;
		display_banner_box($banarr[$banzufall]);
	}
	unset($banarr);
}

?>