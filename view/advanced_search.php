<?php
/*
  $Id: advanced_search.php,v 1.50 2003/06/05 23:25:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);

  $breadcrumb->add(NAVBAR_TITLE_1, push_href_link(FILENAME_ADVANCED_SEARCH));
require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'bkr_static_menu.php'); ?>
<!-- /#left-column --> 
</div>
<div id="center-column">
<!-- body_text //-->
<div class="maincontent">
<?php echo push_draw_form('advanced_search', push_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get', 'onSubmit="return check_form(this);" class="defaultForm"') . push_hide_session_id(); ?>
            <h1><?php echo HEADING_TITLE_1; ?></h1>
<?php
  if ($messageStack->size('search') > 0) {
?>
   
        <div><?php echo $messageStack->output('search'); ?></div>
<?php
  }
?>
<div id="adv_search_form" style="display:block;overflow:hidden;">
<?php
echo "<h2>" . HEADING_SEARCH_CRITERIA . "</h2>";
echo '<div style="overflow:hidden; display:inline-block;margin-bottom:20px;">';
echo push_draw_input_field('keywords', '', 'style="width: 440px;display:inline-block;margin-right:10px;"');
echo '<input type="submit" name="suche" value="Los" class="submitBtn" style="color:white !important;display:inline-block;width:auto;">'; 
echo  '<div class="check">'.push_draw_checkbox_field('search_in_description', '1', true) . ' ' . TEXT_SEARCH_IN_DESCRIPTION .'</div>';
echo "</div>";
?>
        <!--<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo '<a href="javascript:popupWindow(\'' . push_href_link(FILENAME_POPUP_SEARCH_HELP) . '\')">' . TEXT_SEARCH_HELP_LINK . '</a>'; ?></td>
            <td class="smallText" align="right"><?php echo push_image_submit('button_search.gif', IMAGE_BUTTON_SEARCH); ?></td>
          </tr>
        </table></td>-->
        <div style="overflow:hidden; display:inline-block;margin-bottom:20px;">
                <label><?php echo ENTRY_CATEGORIES; ?></label>
                <?php echo push_draw_pull_down_menu('categories_id', push_get_categories(array(array('id' => 'all', 'text' => TEXT_ALL_CATEGORIES)))); ?>
    
                <div class="check" style="margin-bottom:20px;"><?php echo push_draw_checkbox_field('inc_subcat', '1', true) . ' ' . ENTRY_INCLUDE_SUBCATEGORIES; ?></div>
      <!--          <label><?php echo ENTRY_MANUFACTURERS; ?></label>
                <?php echo push_draw_pull_down_menu('manufacturers_id', push_get_manufacturers(array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS)))); ?>
<br>--><br>
                <label><?php echo ENTRY_PRICE_FROM; ?></label>
                <?php echo push_draw_input_field('pfrom'); ?>
                <br>
                <label><?php echo ENTRY_PRICE_TO; ?></label>
				<?php echo push_draw_input_field('pto'); ?><br>
                <label><?php echo ENTRY_DATE_FROM; ?></label>
                <?php echo push_draw_input_field('dfrom', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?>
                <br>
                <label><?php echo ENTRY_DATE_TO; ?></label>
                <?php echo push_draw_input_field('dto', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?>
                </div>
                </div>
                </form>
      
	</div>
<!-- body_text_eof //-->
</div>
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>