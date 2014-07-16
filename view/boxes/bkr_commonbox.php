<?php  
//variable Box 

?>
<!-- recommendation //-->
<div class="frontpageBox">
<?php 
			$c=push_db_fetch_array(push_db_query('SELECT content, box_id, box_link, box_css FROM bkr_boxes WHERE ID="' . $box_id . '";'));
?>
<style><?php echo $c['box_css']; ?>
</style>
    <a title="" href="<?php echo $c['box_link'];?>">
		<div id="<?=$c['box_id']?>">
<?php
			echo stripslashes($c['content']);
?>
		</div>
	</a>
</div>
<?php 
	$box++; 
	if ($box % 2) {
		echo '<div class="boxSeparator"></div>';
	} 
?>
<!-- recommendation_eof //-->
