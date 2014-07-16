<?php
	// This is the CSS *ID* yöu wänt to assign to the UL (unordered list) containing
	// your category menu. Used in conjuction with the CSS list you create for the menu.
	define('CSS_ANKER_FOR_MENU', 'class="infoBoxContents');

	// This is the *CLASSNAME* you want to tag a LI to indicate the selected category.
	// The currently selected category (and its parents, if any) will be tagged with
	// this class. Modify your stylesheet as appropriate. Leave blank or set to false to not assign a class.
	define('CLASSNAME_FOR_ACTIVE', 'active');
	
	define('CLASSNAME_FOR_SELECTED', 'selected');

	// This is the *CLASSNAME* you want to tag a LI to indicate a category has subcategores.
	// Modify your stylesheet to draw an indicator to show the users that subcategories are
	// available. Leave blank or set to false to not assign a class.
	define('CLASSNAME_FOR_PARENT', 'parent');

	// Kandidaten fuer categories menu	
	$categoriesMenu = array(	FILENAME_DEFAULT,
								FILENAME_SPECIALS );
	
// END Configuration options


	
?>

<!-- categories //-->
<?php
	// show the categories menu only on specified sites
//	if ( in_array( basename( $_SERVER['PHP_SELF'] ), $categoriesMenu ) ) {
		
	echo '<div id="categories">';
	
	//		$info_box_contents = array();
	//		$info_box_contents[] = array('text' => BOX_HEADING_CATEGORIES);
	// generate a bulleted list (uses configuration options above)
	$head = '<a class=" gradientstaticmenulight tx_13_20 showAllCategories';
	if (isset($cPath_array) ) {
	$head .= ' frontpage" title="alle Kategorien"  class="tx_13_20 tx_upper  gradientstaticmenulight" onclick="showthemall()" style="cursor: pointer;">Alle Kategorien ansehen <span id="carr" style="float:right;display:block;margin-right:20px;margin-top:6px;">&nbsp;</span>'; 
	} else {
	$head .= '" title="alle Kategorien"  class="tx_13_20 tx_upper gradientstaticmenulight" >Kategorien';
	}
	$head .= '</a>';
	
	echo $head;
	echo push_make_cat_ul_list('allofthem','');
	echo push_make_cat_ul_list();
	echo '</div>';
?>
<?php
if((int)$current_category_id < 1000)
{
?>
<script type="text/javascript">
$(function(){ $('#allofthem').hide(0);})
//$(function(){ $('#allofthem').slideUp(800);})
</script>
<?php
}
else
{
?>
<script type="text/javascript">$(function(){ $('#allofthem').hide(0);})</script>
<?php	
}
?>
<!-- categories_eof //-->
<?php 
if((basename( $_SERVER['PHP_SELF'] )== FILENAME_DEFAULT)&&( isset($_GET['cPath'])|| isset($_GET['manufacturers_id'])|| isset($_GET['newproducts'])|| isset($_GET['specials']) ) ){
?>
<!-- filter -->
<script type="text/javascript">
$(document).ready(function(){
	$('#filterblock').load('./includes/modules/ajax/ajax_filter.php?<?php echo push_get_all_get_params();?>', function(){
		$('#filterblock:has(li)').fadeIn('fast')}); 
	});
</script>
<div id="filterblock" style="display:none"></div>
<?php
}
include(DIR_WS_BOXES .'filter.php');
?>
