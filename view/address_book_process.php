<?php
/*
  $Id: address_book_process.php,v 1.79 2003/06/09 23:03:52 hpdl Exp $
  adapted for Separate Pricing Per Customer 2005/02/16

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
  
  //redirect_array: only redirect to existing pages! a safety issue...
  $rdarr=array(FILENAME_CHECKOUT_SHIPPING,
  				FILENAME_CHECKOUT_PAYMENT,
				FILENAME_ADDRESS_BOOK );

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK_PROCESS);

  if (isset($_GET['action']) && ($_GET['action'] == 'deleteconfirm') && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    push_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$_SESSION['customer_id'] . "'");
	
	if ((int)$_GET['delete'] == $customer_bill_address_id) {
		$sql_data_array = array('customers_bill_address_id' => $customer_default_address_id);
		push_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");
		$customer_bill_address_id = $customer_default_address_id;
	} else if ((int)$_GET['delete'] == $customer_shipping_address_id) {
		$sql_data_array = array('customers_shipping_address_id' => $customer_default_address_id);
		push_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");
		$customer_shipping_address_id = $customer_default_address_id;
	}
	
    $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');

	if(isset($_POST['redirectto']) && in_array($_POST['redirectto'], $rdarr) ){
	  push_redirect(push_href_link($_POST['redirectto'], '', 'SSL'));
	}
    push_redirect(push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
  }

// error checking when updating or adding an entry or changing address
  $process = false;
  if (isset($_POST['action']) && (($_POST['action'] == 'process') || ($_POST['action'] == 'update') || ($_POST['action'] == 'changeCA') || ($_POST['action'] == 'changeBA') || ($_POST['action'] == 'changeSA'))) {
    $process = true;
    $error = false;

    if (ACCOUNT_GENDER == 'true') $gender = push_db_prepare_input($_POST['gender']);
    if (ACCOUNT_COMPANY == 'true') $company = push_db_prepare_input($_POST['company']);

    // BOF Separate Pricing Per Customer
    if (ACCOUNT_COMPANY == 'true' && isset($_POST['company_tax_id'])) {
	$company_tax_id = push_db_prepare_input($_POST['company_tax_id']);
    }
    // EOF Separate Pricing Per Customer


    $firstname = push_db_prepare_input($_POST['firstname']);
    $lastname = push_db_prepare_input($_POST['lastname']);
    $street_address = push_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = push_db_prepare_input($_POST['suburb']);
    $postcode = push_db_prepare_input($_POST['postcode']);
    $city = push_db_prepare_input($_POST['city']);
    $country = push_db_prepare_input($_POST['country']);
    if (ACCOUNT_STATE == 'true') {
      if (isset($_POST['zone_id'])) {
        $zone_id = push_db_prepare_input($_POST['zone_id']);
      } else {
        $zone_id = false;
      }
      $state = push_db_prepare_input($_POST['state']);
    }

	if (!(($_POST['action'] == 'changeCA' || $_POST['action'] == 'changeBA' || $_POST['action'] == 'changeSA') && $_POST['newA'] != 'createNewAddress')) {
		// check form

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;
		
		if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_GENDER_ERROR);
		} else {
			$messageStack->add_session('addressbookChangeAddr', ENTRY_GENDER_ERROR);
		}
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

		if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_FIRST_NAME_ERROR);
		} else {
			$messageStack->add_session('addressbookChangeAddr', ENTRY_FIRST_NAME_ERROR);
		}
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

		if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_LAST_NAME_ERROR);
		} else {
			$messageStack->add_session('addressbookChangeAddr', ENTRY_LAST_NAME_ERROR);
		}
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;
		
		if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_STREET_ADDRESS_ERROR);
		} else {
			$messageStack->add_session('addressbookChangeAddr', ENTRY_STREET_ADDRESS_ERROR);
		}
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

		if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_POST_CODE_ERROR);
		} else {
			$messageStack->add_session('addressbookChangeAddr', ENTRY_POST_CODE_ERROR);
		}
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

		if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_CITY_ERROR);
		} else {
			$messageStack->add_session('addressbookChangeAddr', ENTRY_CITY_ERROR);
		}
    }

    if (!is_numeric($country)) {
      $error = true;
		
		if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_COUNTRY_ERROR);
		} else {
			$messageStack->add_session('addressbookChangeAddr', ENTRY_COUNTRY_ERROR);
		}
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

			if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_STATE_ERROR_SELECT);
			} else {
				$messageStack->add_session('addressbookChangeAddr', ENTRY_STATE_ERROR_SELECT);
			}
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

			if (($_POST['action'] == 'process') || ($_POST['action'] == 'update')) {
	        $messageStack->add('addressbook', ENTRY_STATE_ERROR);
			} else {
				$messageStack->add_session('addressbookChangeAddr', ENTRY_STATE_ERROR);
			}
        }
      }
    }
	}
	
    if ($error == false) {
      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => (int)$country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;

       // BOF Separate Pricing Per Customer
      if (ACCOUNT_COMPANY == 'true' && push_not_null($company_tax_id)) {
	      $sql_data_array['entry_company_tax_id'] = $company_tax_id;
      }
      // EOF Separate Pricing Per Customer


      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = (int)$zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      if ($_POST['action'] == 'update') {
        push_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$_GET['edit'] . "' and customers_id ='" . (int)$_SESSION['customer_id'] . "'");

        // BOF Separate Pricing Per Customer: alert shop owner of tax id number added to an account
      if (ACCOUNT_COMPANY == 'true' && push_not_null($company_tax_id)) {
	      $sql_data_array2['customers_group_ra'] = '1';
      push_db_perform(TABLE_CUSTOMERS, $sql_data_array2, 'update', "customers_id ='" . (int)$_SESSION['customer_id'] . "'");

      // if you would *not* like to have an email when a tax id number has been entered in
      // the appropriate field, comment out this section. The alert in admin is raised anyway
		
      $alert_email_text = "Please note that " . $firstname . " " . $lastname . " of the company: " . $company . " has added a tax id number to his account information.";
      push_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Tax id number added', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
		// EOF Separate Pricing Per Customer: alert shop owner of account created by a company


// reregister session variables
        if ( (isset($_POST['primary']) && ($_POST['primary'] == 'on')) || ($_GET['edit'] == $customer_default_address_id) ) {
          $customer_first_name = $firstname;
          $customer_country_id = $country;
          $customer_zone_id = (($zone_id > 0) ? (int)$zone_id : '0');
          $customer_default_address_id = (int)$_GET['edit'];

          $sql_data_array = array('customers_firstname' => $firstname,
                                  'customers_lastname' => $lastname,
                                  'customers_default_address_id' => (int)$_GET['edit']);

          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;

          push_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        }
		
		$messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');
		
      } else {
		  
		if ($_POST['action'] == 'changeCA' || $_POST['action'] == 'changeBA' || $_POST['action'] == 'changeSA') {
			// change contact/bill/shipping address
			
			if (!isset($_POST['newA'])) {
				// print error message if no radio button checked
				$messageStack->add_session('addressbook', ERROR_CHANGING_ADDRESS_BOOK);
								
			} else {
				if ($_POST['newA'] == 'createNewAddress') {
					// create new address
					$sql_data_array['customers_id'] = (int)$_SESSION['customer_id'];
					push_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
					$address_book_id = push_db_insert_id();
				} else {
					// use address selected by user
					$address_book_id = $_POST['newA'];
				}
				
				// change customer's firstname, lastname and address
				$address_query = push_db_query("SELECT entry_firstname, entry_lastname FROM " . TABLE_ADDRESS_BOOK . " WHERE customers_id = '" . (int)$_SESSION['customer_id'] . "' AND address_book_id = '" . $address_book_id . "'");
				$addr = push_db_fetch_array($address_query);
				if ($_POST['action'] == 'changeCA') {
					$sql_data_array = array('customers_firstname' => $addr['entry_firstname'],
											'customers_lastname' => $addr['entry_lastname'],
											'customers_default_address_id' => $address_book_id);
				} else if ($_POST['action'] == 'changeBA') {
					$sql_data_array = array('customers_bill_address_id' => $address_book_id);
				} else {
					$sql_data_array = array('customers_shipping_address_id' => $address_book_id);
				}
				push_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");
				
				// reregister session variables and set message
				if ($_POST['action'] == 'changeCA') {
					$coun_query = push_db_query("SELECT entry_country_id, entry_zone_id FROM " . TABLE_ADDRESS_BOOK . " WHERE customers_id = '" . (int)$_SESSION['customer_id'] . "' AND address_book_id = '" . $address_book_id . "'");
					$coun = push_db_fetch_array($coun_query);
					
					$customer_first_name = $addr['entry_firstname'];
					$customer_country_id = $addr['entry_lastname'];
					$customer_zone_id = $coun['entry_zone_id'];
					$customer_default_address_id = $address_book_id;
					$messageStack->add_session('addressbook', SUCCESS_CONTACT_ADDRESS_CHANGE, 'success');
				} else if ($_POST['action'] == 'changeBA') {
					$customer_bill_address_id = $address_book_id;
					$messageStack->add_session('addressbook', SUCCESS_BILL_ADDRESS_CHANGE, 'success');
				} else {
					$customer_shipping_address_id = $address_book_id;
					$messageStack->add_session('addressbook', SUCCESS_SHIPPING_ADDRESS_CHANGE, 'success');
				}		
			}
			
		} else {	
			// create new address	  
			$sql_data_array['customers_id'] = (int)$_SESSION['customer_id'];
			push_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
			$messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_CREATED, 'success');
		}
      }
	
	if(isset($_POST['redirectto']) && in_array($_POST['redirectto'], $rdarr) ){
	  push_redirect(push_href_link($_POST['redirectto'], '', 'SSL'));
	}
      push_redirect(push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
	  
    } else if (isset($_POST['action']) && ($_POST['action'] == 'changeCA' || $_POST['action'] == 'changeBA' || $_POST['action'] == 'changeSA')) {
	
	if(isset($_POST['redirectto']) && in_array($_POST['redirectto'], $rdarr) ){
	  push_redirect(push_href_link($_POST['redirectto'], '', 'SSL'));
	}
		push_redirect(push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
	}
  }


////////////////////////Victim of the following lines SPPC///////////////////////////////////////
 // if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
 //   $entry_query = push_db_query("select entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$_GET['edit'] . "'");
///////////////////////////////////////////////////////////////////////////////////////////////
 // BOF Separate Pricing Per Customer
  if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $entry_query = push_db_query("select entry_gender, entry_company, entry_company_tax_id, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$_GET['edit'] . "'");
 // EOF Separate Pricing Per Customer



    if (!push_db_num_rows($entry_query)) {
      $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

	if(isset($_POST['redirectto']) && in_array($_POST['redirectto'], $rdarr) ){
	  push_redirect(push_href_link($_POST['redirectto'], '', 'SSL'));
	}
      push_redirect(push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }

    $entry = push_db_fetch_array($entry_query);
  } elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    if ($_GET['delete'] == $customer_default_address_id) {
      $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

	if(isset($_POST['redirectto']) && in_array($_POST['redirectto'], $rdarr) ){
	  push_redirect(push_href_link($_POST['redirectto'], '', 'SSL'));
	}
      push_redirect(push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    } else {
      $check_query = push_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$_SESSION['customer_id'] . "'");
      $check = push_db_fetch_array($check_query);

      if ($check['total'] < 1) {
        $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

	if(isset($_POST['redirectto']) && in_array($_POST['redirectto'], $rdarr) ){
	  push_redirect(push_href_link($_POST['redirectto'], '', 'SSL'));
	}
        push_redirect(push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
      }
    }
  } else {
    $entry = array();
  }

  if (!isset($_GET['delete']) && !isset($_GET['edit'])) {
    if (push_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
      $messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);

	if(isset($_POST['redirectto']) && in_array($_POST['redirectto'], $rdarr) ){
	  push_redirect(push_href_link($_POST['redirectto'], '', 'SSL'));
	}
      push_redirect(push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, push_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

  if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $breadcrumb->add(NAVBAR_TITLE_MODIFY_ENTRY, push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $_GET['edit'], 'SSL'));
  } elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $breadcrumb->add(NAVBAR_TITLE_DELETE_ENTRY, push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'], 'SSL'));
  } else {
    $breadcrumb->add(NAVBAR_TITLE_ADD_ENTRY, push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'));
  }
require(DIR_WS_BOXES . 'html_header.php');
?>
<div id="left-column">
<?php	include(DIR_WS_BOXES . 'bkr_static_menu.php'); ?>
<!-- /#left-column --> 
</div>
<div id="center-column">
<!-- body_text //-->
<div class="maincontent">

<?php
  if (!isset($_GET['delete'])) {
    include('includes/form_check.js.php');
  }
?>
<?php 
	if (!isset($_GET['delete'])) {
		echo push_draw_form('addressbook', push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($_GET['edit']) ? 'edit=' . $_GET['edit'] : ''), 'SSL'), 'post', 'class="defaultForm" onSubmit="return check_form(addressbook);"');
	}
	
	if (isset($_GET['edit'])) { 
		echo '<h1>' . HEADING_TITLE_MODIFY_ENTRY . '</h1><br />'; 
	} elseif (isset($_GET['delete'])) { 
		echo '<h1>' . HEADING_TITLE_DELETE_ENTRY . '</h1><br /><br />'; 
	} else { 
		echo '<h1>' . HEADING_TITLE_ADD_ENTRY . '</h1><br />'; 
	}
	         
	if ($messageStack->size('addressbook') > 0) {
		echo $messageStack->output('addressbook');
	}

	if (isset($_GET['delete'])) {
?>
		<div class="confirmDeleteAddress">
<?php 		
			echo DELETE_ADDRESS_DESCRIPTION . '<br /><br /><br />'; 
			echo '<a class="btnGrey" href="' . push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . IMAGE_BUTTON_CANCEL . '</a>';
			echo '<a class="btnOrange m70" href="' . push_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'] . '&action=deleteconfirm', 'SSL') . '">' . IMAGE_BUTTON_DELETE . '</a>';
?>		
		</div>
<?php 	
		echo '<div class="confirmDeleteAddress">' . push_address_label($_SESSION['customer_id'], $_GET['delete'], true, ' ', '<br />') . '</div><br />';

	} else {
		include(DIR_WS_MODULES . 'address_book_details.php'); 

		if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
?>	
			<br /><label><?php echo '<a class="btnGrey" href="' . push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . IMAGE_BUTTON_CANCEL . '</a>'; ?></label>
<?php		
			echo push_draw_hidden_field('action', 'update') . push_draw_hidden_field('edit', $_GET['edit']);
			echo push_submit(IMAGE_BUTTON_SAVE, 'class="submitBtn"');

		} else {
			if (sizeof($navigation->snapshot) > 0) {
				$back_link = push_href_link($navigation->snapshot['page'], push_array_to_string($navigation->snapshot['get'], array(push_session_name())), $navigation->snapshot['mode']);
			} else {
				$back_link = push_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
			}
?>
			<br /><label><?php echo '<a class="btnGrey" href="' . $back_link . '">' . IMAGE_BUTTON_CANCEL . '</a>'; ?></label>
<?php
            echo push_draw_hidden_field('action', 'process');
			echo push_submit(IMAGE_BUTTON_SAVE, 'class="submitBtn"');
		}
	}

	if (!isset($_GET['delete'])) echo '</form>'; 
?>
      
	</div>
<!-- body_text_eof //-->
</div>
</div>

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_LIB . 'end.php'); /**/
?>