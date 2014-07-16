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
<?php	include(DIR_WS_BOXES . 'brand_menu.php'); ?>
<!-- /#left-column --> 
<!-- body_text //-->



		<h1 class="grid_12" style="margin-top:0;margin-bottom:20px;">Marken</h1>
		<p class="grid_12"  style="margin-top:10px;margin-bottom:20px;">Vielfalt und Qualität sind gut für Ihr Geschäft. Bei uns werden Sie fündig.</p> 
		<p class="grid_12" style="margin-top:10px;margin-bottom:20px;">Wählen Sie eine Marke um alle Informationen zu sehen.</p><br />
<div class="alpha grid_12 omega" style="margin-left:-10px;width:720px !important;">
		<?php
		$amq=push_db_query("SELECT manufacturers_name, manufacturers_id, manufacturers_image FROM manufacturers WHERE manufacturers_active = '1'  AND manufacturers_id <> 1040201  ORDER BY manufacturers_name");
		while($amr = push_db_fetch_array($amq))
		{
			$pai=999;
			$cat= $amr['manufacturers_id'];
			while($pai > 0)
			{
				$t=push_db_fetch_array(push_db_query("SELECT categories_id, parent_id FROM categories WHERE categories_id IN (SELECT sq.parent_id FROM categories sq WHERE sq.categories_id= '" . $cat . "')"));
				$pai = $t['parent_id'];
				$cat = $t['categories_id'];
			}
			//echo $cat;
			
//			<!--  --><a class="tx_13_20  lvl1 dottedborder <?= ($amr['manufacturers_id'] == $cmid) ? "selected" : "" ? >" title="" href="<?php echo push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL'); ? >"><?=$amr['manufacturers_name']? ></a>
		?>
			<div class="grid_2 marke-hover" style="margin-bottom:20px;">
				<div class="marke-image-<?=$cat?>" style="height:90px;width:100px;padding-top:10px">
				<a class="" title="<?=$amr['manufacturers_name']?>" href="<?php echo push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL'); ?>" style="height:80px;max-height:80px;display:block; border:none;line-height:80px;margin-bottom:0;text-align:center;"> 
				<?php if(trim($amr['manufacturers_image'])<>""){?>	
					<img alt="<?=$amr['manufacturers_name']?>" src="imagethumb.php?s=images/brands/<?=$amr['manufacturers_image']?>&w=80" style="max-width:80px;max-height:80px;vertical-align:middle;">
				<?php } ?>
				</a></div>
				<a title="<?=$amr['manufacturers_name']?>" href="<?php echo push_href_link(FILENAME_BRANDS,'mid='.$amr['manufacturers_id'],'NONSSL'); ?>" class="tx_12_15 marke-text-<?=$cat?>" style="padding:5px;display:block;height:30px;">
					<?=$amr['manufacturers_name']?>
				</a>
			</div>
	<?php 

		}?>
</div>
<!-- body_text_eof //-->

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>
