<?php
/*
  $Id: create_account.php,v 1.65 2003/06/09 23:03:54 hpdl Exp $
  adapted for Separate Pricing Per Customer 2005/02/14

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/ajax_top.php');

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

  $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $process = true;

//extra questions start
if (QUESTION_LOCATION == 'account')  require('extra_questions_upload_result_box.php');
//extra questions end

    if (ACCOUNT_GENDER == 'true') {
      if (isset($_POST['gender'])) {
        $gender = push_db_prepare_input($_POST['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = push_db_prepare_input($_POST['firstname']);
    $lastname = push_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = push_db_prepare_input($_POST['dob']);
    $email_address = push_db_prepare_input($_POST['email_address']);
// if (ACCOUNT_COMPANY == 'true') $company = push_db_prepare_input($_POST['company']);

	// BOF Separate Pricing Per Customer, added: field for tax id number
    if (ACCOUNT_COMPANY == 'true') {
    $company = push_db_prepare_input($_POST['company']);
    $company_tax_id = push_db_prepare_input($_POST['company_tax_id']);
    }
    // EOF Separate Pricing Per Customer, added: field for tax id number

    $street_address = push_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = push_db_prepare_input($_POST['suburb']);
    $postcode = push_db_prepare_input($_POST['postcode']);
    $city = push_db_prepare_input($_POST['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = push_db_prepare_input($_POST['state']);
      if (isset($_POST['zone_id'])) {
        $zone_id = push_db_prepare_input($_POST['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $country = push_db_prepare_input($_POST['country']);
    $telephone = push_db_prepare_input($_POST['telephone']);
    $fax = push_db_prepare_input($_POST['fax']);
    if (isset($_POST['newsletter'])) {
      $newsletter = push_db_prepare_input($_POST['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = push_db_prepare_input($_POST['password']);
    $confirmation = push_db_prepare_input($_POST['confirmation']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(push_date_raw($dob), 4, 2), substr(push_date_raw($dob), 6, 2), substr(push_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (push_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $check_email_query = push_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . push_db_input($email_address) . "' AND customers_default_address_id IS NOT NULL");
      $check_email = push_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
        $error = true;

        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      }
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = push_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = push_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = push_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . push_db_input($state) . "%' or zone_code like '%" . push_db_input($state) . "%')");
        if (push_db_num_rows($zone_query) == 1) {
          $zone = push_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'customers_password' => push_encrypt_password($password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = push_date_raw($dob);

      // BOF Separate Pricing Per Customer
      // if you would like to have an alert in the admin section when either a company name has been entered in
      // the appropriate field or a tax id number, or both then uncomment the next line and comment the default
      // setting: only alert when a tax_id number has been given
	  //    if ( (ACCOUNT_COMPANY == 'true' && push_not_null($company) ) || (ACCOUNT_COMPANY == 'true' && push_not_null($company_tax_id) ) ) {
	  if ( ACCOUNT_COMPANY == 'true' && push_not_null($company_tax_id)  ) {
      $sql_data_array['customers_group_ra'] = '1';
      }
      // EOF Separate Pricing Per Customer

  
  	//check if there is a newsletter-account
  	  $check_email_query = push_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . push_db_input($email_address) . "' AND customers_default_address_id IS NULL");
      $check_email = push_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
    		$method="update";
			$param = "customers_email_address = '" . push_db_input($email_address) . "'";
		}else{
			$method="insert";
			$param = "";
		}	

      push_db_perform(TABLE_CUSTOMERS, $sql_data_array, $method, $param );

      
	  if($method=='insert'){
	  		$_SESSION['customer_id'] = push_db_insert_id();
		}else{
			$getid=push_db_fetch_array(push_db_query("SELECT customers_id FROM customers WHERE customers_email_address = '" . push_db_input($email_address) . "';"));
			$_SESSION['customer_id'] = $getid['customers_id'];
		}
//extra questions start
if (QUESTION_LOCATION == 'account')  require('extra_question_db_upload_box.php');
//extra questions end

      $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
   /////////////////////////////////////////////////////////////replaced by SPPC///////////////////////
   //   if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
   ///////////////////////////////////////////////////////////////////////////////////////////////////

   if (ACCOUNT_COMPANY == 'true') { // BOF adapted for Separate Pricing Per Customer
      $sql_data_array['entry_company'] = $company;
      $sql_data_array['entry_company_tax_id'] = $company_tax_id;
      } // EOF adapted for Separate Pricing Per Customer



      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      push_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = push_db_insert_id();

      push_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "', customers_bill_address_id = '" . (int)$address_id . "', customers_shipping_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'");

      push_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$_SESSION['customer_id'] . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        push_session_recreate();
      }
 // ################# START MODIFICATIONS HTML EMAIL #################
      $customer_gender = $gender;
      $customer_last_name = $lastname;
 // ################# END MODIFICATIONS SEND HTML EMAIL #################
      $customer_first_name = $firstname;
      $customer_default_address_id = $address_id;
	  $customer_bill_address_id = $address_id;
	  $customer_shipping_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
      push_session_register('customer_id');
      push_session_register('customer_first_name');
      push_session_register('customer_default_address_id');
	  push_session_register('customer_bill_address_id');
	  push_session_register('customer_shipping_address_id');
      push_session_register('customer_country_id');
      push_session_register('customer_zone_id');

// restore cart contents
      $cart->restore_contents();
// restore wishlist to sesssion
        $wishList->restore_wishlist();
// build the message content
   // ################# START MODIFICATIONS HTML EMAIL #################
$Actualmail= ' ' . MAILNAME . ' ';
$Varlogo = ' '.VARLOGO.' ' ;
$Varhttp = ''.VARHTTP.'';
$Varstyle = ''.VARSTYLE.'';
$Vartable1 = ' '.VARTABLE1.' '  ;
$Vartable2 = ' '.VARTABLE2.' '  ;
$Vartextmail = EMAILWELCOME . EMAILTEXT . EMAILCONTACT . EMAILWARNING;
$Vartrcolor = ' '. TRCOLOR . '  ' ;
$Varmailfooter = '  ' . EMAIL_TEXT_FOOTER . ''  ;
$Varmailfooter2 =  ' '. EMAIL_TEXT_IMPRESSUM;// <a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'. HTTP_SERVER . DIR_WS_CATALOG .'</a> '. "\n" . '  <font size=-2>'.EMAIL_TEXT_FOOTERR .'</font> ' ;
// ################ End Added ##############

    if (ACCOUNT_GENDER == 'true') {
       if ($_POST['gender'] == 'm') {
// ################# START MODIFICATIONS HTML EMAIL #################
//         $email_text = EMAIL_GREET_MR;
   	    $Vartextmail = EMAILWELCOME . EMAILGREET_MR .  EMAILTEXT . EMAILCONTACT . EMAILWARNING;
// ################ End Added ##############
       } else {
// ################# START MODIFICATIONS HTML EMAIL #################
//         $email_text = EMAIL_GREET_MS;
   	    $Vartextmail =  EMAILWELCOME .EMAILGREET_MS .  EMAILTEXT . EMAILCONTACT . EMAILWARNING;
// ################ End Added ##############
       }
    } else {
// ################# START MODIFICATIONS HTML EMAIL #################
//      $email_text = EMAIL_GREET_NONE;
         $Vartextmail =  EMAILWELCOME  . EMAILGREET_NONE . EMAILTEXT . EMAILCONTACT . EMAILWARNING;
// ################ End Added ##############
    }
	
$Varaccountlink='<br><a href="http://www.Bruesselser-Kakaoroesterei.de/shop/">zum Onlineshop &raquo; </a><br><br><a href="https://www.Bruesselser-Kakaoroesterei.de/shop/account.php">Pers&ouml;nlicher Bereich &raquo; </a>';

require(DIR_WS_MODULES . 'email/html_create_account_process.php');

if (EMAIL_USE_HTML == 'true') {
$email_text = $html_email_text ;
}
else
 {
      $name = $firstname . ' ' . $lastname;
      if (ACCOUNT_GENDER == 'true') {
         if ($gender == 'm') {
           $email_text = sprintf(EMAIL_GREET_MR, $lastname);
         } else {
           $email_text = sprintf(EMAIL_GREET_MS, $lastname);
         }
      } else {
        $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
      }

$email_text .=  EMAILWELCOME . "\n\n" . EMAILTEXT ."\n\n" . EMAILCONTACT .
                EMAIL_TEXT_FOOTER . "\n\n\n" .
                EMAIL_SEPARATOR . "\n" .
                EMAILWARNING . "\n\n" ;
$email_text .=  HTTP_SERVER . DIR_WS_CATALOG . "\n" .
                EMAIL_TEXT_FOOTERR . "\n" ;

      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
  }

      push_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

// BOF Separate Pricing Per Customer: alert shop owner of account created by a company
// if you would like to have an email when either a company name has been entered in
// the appropriate field or a tax id number, or both then uncomment the next line and comment the default
// setting: only email when a tax_id number has been given
//    if ( (ACCOUNT_COMPANY == 'true' && push_not_null($company) ) || (ACCOUNT_COMPANY == 'true' && push_not_null($company_tax_id) ) ) {
      if ( ACCOUNT_COMPANY == 'true' && push_not_null($company_tax_id) ) {
      $alert_email_text = "Please note that " . $firstname . " " . $lastname . " of the company: " . $company . " has created an account.";
      push_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Company account created', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
// EOF Separate Pricing Per Customer: alert shop owner of account created by a company


// If you want receive sinon your mail the new client inscription
//      push_mail($name, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

//fin
// ############## END MODIFICATIONS HTML EMAIL #######################

      push_redirect(push_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
    }
  }

	$breadcrumb->reset();
	$breadcrumb->add(NAVBAR_TITLE, push_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
	
require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'bkr_static_menu.php'); 
?>
<!-- /#left-column --> 
</div>
<div id="center-column">


    <?php echo push_draw_form('create_account', push_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'class="defaultForm" onSubmit="return check_form(create_account);"') . push_draw_hidden_field('action', 'process'); ?>
        <h1><?php echo HEADING_TITLE; ?></h1><br />
<?php
	if ($messageStack->size('create_account') > 0) {
			echo $messageStack->output('create_account');
  	}
?>
        <p class="w520"><?php echo HEADING_TEXT1; ?></p>
        <p class="w520"><?php echo HEADING_TEXT2; ?></p>
		<h2><?php echo CATEGORY_PERSONAL; ?></h2>
<?php
	if (ACCOUNT_GENDER == 'true') {
?>
			<label><?php echo ENTRY_GENDER; ?></label>
            <div class="radioGroup<?php echo ($messageStack->contains(ENTRY_GENDER_ERROR) ? ' error' : ''); ?>">
                <div class="radio"><?php echo push_draw_radio_field('gender', 'm') . MALE; ?></div>
                <div class="radio"><?php echo push_draw_radio_field('gender', 'f') . FEMALE; ?></div>
			</div><br />
<?php
	}
?>
			<label><?php echo ENTRY_FIRST_NAME; ?></label>
            <?php echo push_draw_input_field('firstname', '', ($messageStack->contains(ENTRY_FIRST_NAME_ERROR)) ? 'class="error"' : ''); ?><br />
            <label><?php echo ENTRY_LAST_NAME; ?></label>
            <?php echo push_draw_input_field('lastname', '', ($messageStack->contains(ENTRY_LAST_NAME_ERROR)) ? 'class="error"' : ''); ?><br />
<?php
	if (ACCOUNT_DOB == 'true') {
?>
			<label><?php echo ENTRY_DATE_OF_BIRTH; ?></label>
			<?php echo push_draw_input_field('dob', '', ($messageStack->contains(ENTRY_DATE_OF_BIRTH_ERROR)) ? 'class="error"' : ''); ?><br />
<?php
	}
?>
			<label><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
			<?php echo push_draw_input_field('email_address', '', ($messageStack->contains(ENTRY_EMAIL_ADDRESS_ERROR)) ? 'class="error"' : ''); ?>
<?php
	if (ACCOUNT_COMPANY == 'true') {
?>
		<h2><?php echo CATEGORY_COMPANY; ?></h2>
			<label><?php echo ENTRY_COMPANY; ?></label>
			<?php echo push_draw_input_field('company'); ?><span class="optional">optional</span><br />
<!-- BOF Separate Pricing Per Customer: field for tax id number -->
			<label><?php echo ENTRY_COMPANY_TAX_ID; ?></label>
			<?php echo push_draw_input_field('company_tax_id'); ?><span class="optional">optional</span><br />
<!-- EOF Separate Pricing Per Customer: field for tax id number -->
<?php
	}
?>
		<h2><?php echo CATEGORY_ADDRESS; ?></h2>
			<label><?php echo ENTRY_STREET_ADDRESS; ?></label>
			<?php echo push_draw_input_field('street_address', '', ($messageStack->contains(ENTRY_STREET_ADDRESS_ERROR)) ? 'class="error"' : ''); ?><br />
<?php
	if (ACCOUNT_SUBURB == 'true') {
?>
			<label><?php echo ENTRY_SUBURB; ?></label>
			<?php echo push_draw_input_field('suburb', '', ($messageStack->contains(ENTRY_SUBURB_ERROR)) ? 'class="error"' : ''); ?><br />
<?php
	}
?>
			<label><?php echo ENTRY_POST_CODE; ?></label>
			<?php echo push_draw_input_field('postcode', '', ($messageStack->contains(ENTRY_POST_CODE_ERROR)) ? 'class="error"' : ''); ?><br />
			<label><?php echo ENTRY_CITY; ?></label>
			<?php echo push_draw_input_field('city', '', ($messageStack->contains(ENTRY_CITY_ERROR)) ? 'class="error"' : ''); ?><br />
<?php
	if (ACCOUNT_STATE == 'true') {
?>
			<label><?php echo ENTRY_STATE; ?></label>
<?php
		if ($process == true) {
			if ($entry_state_has_zones == true) {
				$zones_array = array();
				$zones_query = push_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
				while ($zones_values = push_db_fetch_array($zones_query)) {
					$zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
				}
				echo push_draw_pull_down_menu('state', $zones_array);
			} else {
				echo push_draw_input_field('state');
			}
		} else {
			echo push_draw_input_field('state');
		}
	}
?>
			<label><?php echo ENTRY_COUNTRY; ?></label>
            <?php echo push_get_country_list('country', '', ($messageStack->contains(ENTRY_COUNTRY_ERROR)) ? 'class="error"' : ''); ?><br />
            
		<h2><?php echo CATEGORY_CONTACT; ?></h2>
			<label><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
            <?php echo push_draw_input_field('telephone', '', ($messageStack->contains(ENTRY_TELEPHONE_NUMBER_ERROR)) ? 'class="error"' : ''); ?><br />
			<label><?php echo ENTRY_FAX_NUMBER; ?></label>
			<?php echo push_draw_input_field('fax'); ?><span class="optional">optional</span><br />
		<h2><?php echo CATEGORY_OPTIONS; ?></h2>
			<label><?php echo ENTRY_NEWSLETTER; ?></label>
			<div class="check"><?php echo push_draw_checkbox_field('newsletter', '1') . NEWSLETTER_TEXT; ?></div><br />
            
<?php
//extra questions start
if (QUESTION_LOCATION == 'account')  require('extra_questions_show_box.php');
//extra questions end
?>
		<h2><?php echo CATEGORY_PASSWORD; ?></h2>
			<label><?php echo ENTRY_PASSWORD; ?></label>
			<?php echo push_draw_password_field('password', '', ($messageStack->contains(ENTRY_PASSWORD_ERROR) || $messageStack->contains(ENTRY_PASSWORD_ERROR_NOT_MATCHING)) ? 'class="error"' : ''); ?><br />
			<label><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
			<?php echo push_draw_password_field('confirmation', '', ($messageStack->contains(ENTRY_PASSWORD_ERROR) || $messageStack->contains(ENTRY_PASSWORD_ERROR_NOT_MATCHING)) ? 'class="error"' : ''); ?><br />
            
		<?php echo push_submit(IMAGE_BUTTON_READY, 'class="submitBtn"'); ?>
	</form>
</div>
</div>
<?php
require(DIR_WS_BOXES . 'html_footer.php');
require(DIR_WS_LIB . 'end.php'); ?>