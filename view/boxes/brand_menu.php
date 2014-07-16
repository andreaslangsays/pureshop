<?php
/*
 * Krös
 */

// !!! if you change this arrays, change also the arrays in the function is_selected in functions/general.php !!! 

// Informationen
if(isset($_GET['mid']) && is_numeric($_GET['mid']) && $_GET['mid'] > 0 )
{
	//mid contains the manufacturers_id
	$mq=push_db_query("SELECT * FROM manufacturers WHERE manufacturers_id='" . intval($_GET['mid']) . "' ");	
	if($mr=push_db_fetch_array($mq))
	{
		$brand_name = $mr['manufacturers_name'];
		$brand_image = $mr['manufacturers_image'];
		$cmid = $mr['manufacturers_id'];
	}
}	
?>
<!-- menu //-->
<?php
	$totalnumberofbrands=0;
	$currentbrandposition=0;
	$prevbrandlink="";
	$nextbrandlink="";
	$info_box_contents = array();
	$info_box_contents[] = array('text' => 'Men&uuml;');
	$currentPage = basename($_SERVER['PHP_SELF']);
if(isset($hidebrands) && $hidebrands == 1)
{
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#brands').on('click', function(){
			$('.toggleview').slideToggle(300);			
		});
	});
</script>
<?php }
?>
<div id="brandMenu" class="infoBox grid_4 <?=(isset($hidebrands) && $hidebrands==1)? "positioned":"";?>">
	<div class="staticNavi">
		<a id="brands" class= "tx_13_20 showAllCategories frontpage <?=(isset($hidebrands) && $hidebrands==1)? "arrowright":"arrowdown";?>"" style="background-color:#ccc"> Alle Marken </a>
		 <?=(isset($hidebrands) && $hidebrands==1)? "<div class='toggleview'>":"";?>
		<?php
		$amq=push_db_query("SELECT manufacturers_name, manufacturers_id FROM manufacturers WHERE manufacturers_active = '1' AND manufacturers_id <> 1040201 ORDER BY manufacturers_name");
		while($amr = push_db_fetch_array($amq))
		{
			//Count brands!
			$totalnumberofbrands++;

			if($amr['manufacturers_id'] == $cmid){
				$currentbrandposition = $totalnumberofbrands; //	
			}
			if($currentbrandposition ==0)
			{
				$prevbrandlink = push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL');
			}
			if($totalnumberofbrands == ($currentbrandposition +1))
			{
				$nextbrandlink = push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL');		
			}
			
			?>
			<!-- <?=$amr['manufacturers_name']?> --><a class="tx_13_20  lvl1 dottedborder <?= ($amr['manufacturers_id'] == $cmid) ? "selected" : "" ?>" title="" href="<?php echo push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL'); ?>"><?=$amr['manufacturers_name']?></a>
		<?php
		}
		?>
		 <?=(isset($hidebrands) && $hidebrands==1)? "</div>":"";?>
	</div>
<?php ?>
</div>
<!-- menu_eof //-->
