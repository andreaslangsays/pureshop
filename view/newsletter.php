<?php
/*
 $Id: spotlight.php, v 0.1 2008/04/21 14:57:20 wilt Exp $
 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2002 - 2003 osCommerce
 Released under the GNU General Public License
 */
//NL Anmeldung
$message=array();
//eMail-adress-Format ueberpruefen
function address_well_formed($adress=""){
	if(preg_match('/^[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)*\@[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)+$/i', $adress)){
		return true;	
	}else{
		return false;
		
	}
}
if(isset($_POST['mail'])){
	$mailaddress=$_POST['mail'];
	//is there something todo?
	if( (isset($_POST['speichern'])) && (address_well_formed($mailaddress)) ) {
//time to do something!
//1st: look in NLM
				//connect to DB
				include('../newsletter/incl/mysql_connector.php');
				//is the address there?
				$ask=mysql_fetch_assoc( mysql_query("SELECT count(a_ID) as total FROM  abonnenten WHERE a_email='" .  $mailaddress . "' AND f_name='BKR'; ") );
				//Set Query (let it count : $count)
				if($ask['total'] > 0){
					//address is there!
					$newsletter_general = $_POST['newsletter_general'];	
					if($newsletter_general == '0')
					{
						//remove from NLMdb
						$t="DELETE FROM abonnenten WHERE a_email='" .  $mailaddress . "'  AND f_name='BKR';"; 
						$delete=mysql_query($t);
						$message[]="removed";
					}
				}elseif($ask['total'] == 0){
					if($newsletter_general == '1')
					{
						//insert into NLMdb
						$insert=mysql_query("INSERT INTO abonnenten (a_email, f_name) VALUES ( '" .  $mailaddress . "', 'BKR')");
						$message[]="inserted";
					}
				}//so far for NLM
			mysql_close($dbc);
	}
}
require('includes/ajax_top.php');
// redirect to account_newsletters.php if user logged in
if (push_session_is_registered('customer_id')) {
	push_redirect(push_href_link("account_newsletters.php", '', 'SSL'));
}
//2nd treat local customers
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTER);
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_NEWSLETTERS);
//now we can use the push_functions!!!
if(isset($_POST['mail'])){
$message=array();
	if( (isset($_POST['speichern'])) && (address_well_formed($mailaddress)) ) {
				//look if address is in the pool 
				$ask=push_db_fetch_array(push_db_query("SELECT count(customers_id) AS total FROM customers WHERE customers_email_address='" . $mailaddress . "';"));
	
				if (isset($_POST['newsletter_offer']) && is_numeric($_POST['newsletter_offer'])) {
					$newsletter_offer = push_db_prepare_input($_POST['newsletter_offer']);
				} else {
					$newsletter_offer = '0';		// austragen
				}
	
				//at first for opt out!! simply do it!
				if($newsletter_offer == '0'){					// austragen
					//look if mail exists in customers table
					if($ask['total']>0){
						$update="UPDATE customers SET customers_newsletter=0, customers_gutschein='no' WHERE customers_email_address='" . $mailaddress . "'";
						push_db_query($update);
						$message[]="removed";
					}
					//that should work!
	 
				}elseif($newsletter_offer == '1'){			// eintragen
	
				//if there is no account in the pool
					if($ask['total'] == 0){
						$insert_new="INSERT INTO customers (customers_email_address, customers_newsletter, customers_comment, customers_gutschein) VALUES ('" . $mailaddress . "', 1 , 'Account erstellt als Newsletterabonnent am " . date('d.m. Y G:i:s') . "', 'yes');";
						push_db_query($insert_new);
						$message[]="inserted";
					}else{
						//account exists!!					
						//has it a customers_default_address_id (is not null)??
						$has=push_db_fetch_array(push_db_query("SELECT customers_id, count(customers_id) AS total FROM customers WHERE customers_email_address='" . $mailaddress . "' AND customers_default_address_id IS NOT NULL GROUP BY customers_id;"));
						if($has['total']>0){
							//if it does--> is user logged in?
							if($_SESSION['customer_id'] != $has['customers_id']){
								$navigation->set_snapshot();
								$message="Bitte loggen Sie sich ein um die Newsletter-Einstellungen f&uuml;r Ihren Account zu &auml;ndern";
								push_session_register('message');
							//	push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL')); //goodbye!!
							}elseif($_SESSION['customer_id'] == $has['customers_id']){
							//update customer table
								$update="UPDATE customers SET customers_newsletter = 1, customers_gutschein = 'yes' WHERE customers_email_address='" . $mailaddress . "'";
								push_db_query($update);
								$message[]="inserted";
							}
						}else{
						//else (user has no real account) -> update 
						$update="UPDATE customers SET customers_newsletter=1, customers_gutschein='yes' WHERE customers_email_address='" . $mailaddress . "'";
						push_db_query($update);
						$message[]="inserted";
					}
				}
		
			}
	}
}
if( (isset($_POST['speichern'])) && (count($message)<1) ){
	if($newsletter_offer == '0' && address_well_formed($_POST['mail']))
		$message="Die Adresse <b>" . $_POST['mail'] . "</b> befindet sich nicht in unserer Datenbank";
	else
		$message="'<b>" . $_POST['mail'] . "</b>' ist keine g&uuml;ltige E-Mail-Adresse!";
	
	if( $newsletter_general == '1' && address_well_formed($_POST['mail']))
		$message="Die Adresse <b>" . $_POST['mail'] . "</b> ist bereits als Newsletter-Abonnent registriert.";
	else
		$message="'<b>" . $_POST['mail'] . "</b>' ist keine g&uuml;ltige E-Mail-Adresse!";
	
}elseif(is_array($message)){
	if($message[0]=="inserted")
		$message="Die Adresse <b>" . $_POST['mail'] . "</b> wurde erfolgreich als Newsletter-Abonnent registriert.";
	if($message[0]=="removed")
		$message="Die Adresse <b>" . $_POST['mail'] . "</b> wurde vom Newsletter-Empfang ausgeschlossen.";
}
require('includes/classes/http_client.php');
$breadcrumb->add(NAVBAR_TITLE);

//HEAD

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
			<h1>Newsletter Abonnements</h1><br />
			<?php 
				if(isset($message)){
					echo '	<p style="padding:5px;color:#ee7f00;">';
					echo $message;
					echo '</p>';
				}
			?><br />
			Abonnieren Sie unsere Newsletter und seien Sie als erster über Tips und Angebote informiert. Sie gehen keinerlei Verpflichtungen ein und können unseren Nachrichtenservice jederzeit wieder abbestellen. Ihre Daten behandeln wir vertraulich und geben sie nicht an Dritte weiter.<br /><br />
			<small>Bitte wählen Sie die gewünschten Newsletter. Zur Austragung aus allen Newslettern deaktivieren Sie alle Kästchen und klicken Sie "speichern".</small><br /><br />

			<form action="newsletter.php" method="post" class="defaultForm">
			<?php echo push_draw_hidden_field('action', 'process'); ?>
				<div>
					<div class="newsletterCheck">
						<input type="radio" name="newsletter_offer" value="0" /> nein<br />
						<input type="radio" name="newsletter_offer" value="1" /> ja
					</div>	
					<div class="newsletterInfo">
						<b>Angebots-Newsletter</b><br />
						Mit diesem Newsletter informieren wir Sie ca. 1-4 x im Monat über unsere aktuellsten Schnäppchen.
					</div>
				</div><br /><br />	
				<div>
					<div class="newsletterCheck">
						<input type="radio" name="newsletter_general" value="0" />nein<br />
						<input type="radio" name="newsletter_general" value="1" checked="checked"/>ja
					</div>	
					<div class="newsletterInfo">
						<b>Saisonaler Newsletter</b><br />
						Mit diesem Newsletter informieren wir Sie ca. 1 im Quartal über unser Neuigkeiten rund um die Bruesselser Kakaorösterei.
					</div>
				</div><br /><br />				
				
				<label>eMail-Adresse</label>
				<input id="mail" name="mail" type="text" style="width: 290px" value="<?php 
				if((isset($is_mail))){
					echo mysql_escape_string(strip_tags($_POST['mail'])); 
				}
				?>"><br /><br />
			
				<label></label><input class="submitBtn" type="submit" value="speichern" name="speichern">
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