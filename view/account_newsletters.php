<?php
/*
  $Id: account_newsletters.php,v 1.3 2003/06/05 23:23:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');

  if (!push_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_NEWSLETTERS);

  $newsletter_query = push_db_query("select customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
  $newsletter = push_db_fetch_array($newsletter_query);

  if (isset($_POST['speichern'])) {
    if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
      $newsletter_general = push_db_prepare_input($_POST['newsletter_general']);
    } else {
      $newsletter_general = '0';
    }

    if ($newsletter_general != $newsletter['customers_newsletter']) {
      $newsletter_general = (($newsletter['customers_newsletter'] == '1') ? '0' : '1');

      push_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '" . (int)$newsletter_general . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    }

    $msg = SUCCESS_NEWSLETTER_UPDATED;
  }

	$breadcrumb->reset();
	$breadcrumb->add('Pers&ouml;nlicher Bereich', FILENAME_ACCOUNT);
	$breadcrumb->add(NAVBAR_TITLE_2, push_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));
require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'bkr_static_menu.php'); ?>
<!-- /#left-column --> 
</div>
<div id="center-column">
<!-- body_text //-->
<div class="maincontent">

	<div class="container_16">
		<div class="grid_12 alpha omega">
			<div class="grid_9 alpha">
				<h1><?php echo HEADING_TITLE; ?></h1><br />
				<?php
					if (isset($msg)) {
						echo '<span class="messageStackSuccess" style="width: 500px;">' . $msg . '</span><br />';
					}
				?>	
				<br /><b>Sie haben folgende Benachrichtigungen abonniert</b><br />
				<small>(Nicht gew&uuml;nschte Benachrichtigungen k&ouml;nnen Sie &uuml;ber das Kontrollk&auml;stchen deaktivieren)</small><br /><br /><br />
				
				<?php echo push_draw_form('account_newsletter', push_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')); ?>
					<div>
						<div class="newsletterCheck">
							<?php 
								$newsletter_query = push_db_query("select customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
								$newsletter = push_db_fetch_array($newsletter_query);
								echo push_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false), 'onclick="checkBox(\'newsletter_general\')"'); 
							?>
						</div>	
						<div class="newsletterInfo">
							<b>Angebots-Newsletter</b><br />
						Mit diesem Newsletter informieren wir Sie ca. 1-4 x im Monat über unsere aktuellsten Schnäppchen.
						</div>
					</div>
					<div style="width: 60px;">&nbsp;</div>
					<?php echo '<a class="btnGrey" href="' . push_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" style="margin-right: 20px;">abbrechen</a>'; ?>
					<input class="submitBtn" type="submit" value="speichern" name="speichern">
				</form>
			</div>
			<div class="grid_3 omega" id="veryNiceButterflies">
				&nbsp;
			</div>
		</div>
	</div>
	</div>
<!-- body_text_eof //-->
</div>
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>