<?php
/*
  $Id: germanbanktransfer.php 157 2005-04-07 20:33:35Z dogu $

  OSC German Banktransfer
  (http://www.oscommerce.com/community/contributions,826)

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 - 2003 osCommerce

  Released under the GNU General Public License
*/

  class germanbanktransfer {
    var $code, $title, $description, $enabled;

// class constructor
    function germanbanktransfer() {
      global $order, $gbt_array;

      $this->code = 'germanbanktransfer';
      $this->title = MODULE_PAYMENT_GERMANBT_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_GERMANBT_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_GERMANBT_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_GERMANBT_STATUS == 'True') ? true : false);
	  $this->index;
      if ((int)MODULE_PAYMENT_GERMANBT_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_GERMANBT_ORDER_STATUS_ID;
      }
      if (is_object($order)) $this->update_status();

      if ($gbt_array['bt_fax'] == true)
        $this->email_footer = MODULE_PAYMENT_GERMANBT_TEXT_EMAIL_FOOTER;
    }

// class methods
    function update_status() {
      global $order, $customer_group_id;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_GERMANBT_ZONE > 0) ) {
        $check_flag = false;
        $check_query = push_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_GERMANBT_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = push_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }

	  // check banktransfer after x times
	  if (MODULE_PAYMENT_GERMANBT_ENABLE_AFTER == 'true'){
	    $test_query = push_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id='" . $_SESSION['customer_id'] . "' AND orders_status = " . MODULE_PAYMENT_GERMANBT_ENABLE_AFTER_ORDER_STATUS );
	    $result = push_db_fetch_array($test_query);

	    $total = $result['total'];
	    if ( $total > MODULE_PAYMENT_GERMANBT_ENABLE_AFTER_TIMES) {
		    $this->enabled = true;
	    }else{
		    $this->enabled = false;
		}
		if($customer_group_id!='0')
		    $this->enabled = true;
	  }
	  // end check banktransfer after x times

    // disable the module if the order only contains virtual products
      if ($this->enabled == true) {
        if ($order->content_type == 'virtual') {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      $js = 'if (payment_value == "' . $this->code . '") {' . "\n" .
            '  var banktransfer_blz = document.checkout_payment.banktransfer_blz.value;' . "\n" .
            '  var banktransfer_number = document.checkout_payment.banktransfer_number.value;' . "\n" .
            '  var banktransfer_owner = document.checkout_payment.banktransfer_owner.value;' . "\n" ;

    if (MODULE_PAYMENT_GERMANBT_FAX_CONFIRMATION =='true'){
      $js .='  var banktransfer_fax = document.checkout_payment.banktransfer_fax.checked;' . "\n" .
            '  if (banktransfer_fax == false) {' . "\n" ;
    }
      $js .='    if (banktransfer_owner == "") {' . "\n" .
            '      error_message = error_message + "' . JS_GERMANBT_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (banktransfer_blz == "") {' . "\n" .
            '      error_message = error_message + "' . JS_GERMANBT_BLZ . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (banktransfer_number == "") {' . "\n" .
            '      error_message = error_message + "' . JS_GERMANBT_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" ;
    if (MODULE_PAYMENT_GERMANBT_FAX_CONFIRMATION =='true'){
      $js .='  }' . "\n" ;
    }
      $js .='}' . "\n";
      return $js;
    }

    function selection() {
      global $order, $_POST, $gbt_array, $gbt_number;

      $selection = array('id' => $this->code,
                         'module' => $this->title,
      	                 'fields' => array(array('title' => MODULE_PAYMENT_GERMANBT_TEXT_NOTE,
      	                                         'field' => MODULE_PAYMENT_GERMANBT_TEXT_BANK_INFO .'<br><br>'),
      	                                   array('title' => "<label class='grid_3 alpha'>" . MODULE_PAYMENT_GERMANBT_TEXT_BANK_OWNER . "</label>",
      	                                         'field' => push_draw_input_field('banktransfer_owner', $order->billing['firstname'] .' '. $order->billing['lastname'], ' onclick="document.checkout_payment.payment['.$this->index.'].checked=true" class="grid_6"'). "<div class='grid_3 omega' style='margin-left:-20px;margin-bottom:20px;min-height:20px'>&nbsp;</div><br>"),
      	                                   array('title' => "<label class='grid_3 alpha'>" . MODULE_PAYMENT_GERMANBT_TEXT_BANK_BLZ . "</label>",
      	                                         'field' => push_draw_input_field('banktransfer_blz', $gbt_array["bt_blz"], ' onclick="document.checkout_payment.payment['.$this->index.'].checked=true" size="8" maxlength="8" class="grid_6"'). "<div class='grid_3 omega' style='margin-left:-20px;margin-bottom:20px;min-height:20px'>&nbsp;</div><br>"),
      	                                   array('title' => "<label class='grid_3 alpha'>" . MODULE_PAYMENT_GERMANBT_TEXT_BANK_NUMBER . "</label>",
      	                                         'field' => push_draw_input_field('banktransfer_number', $gbt_number, ' onclick="document.checkout_payment.payment['.$this->index.'].checked=true" size="16" maxlength="32" class="grid_6"'). "<div class='grid_3 omega' style='margin-left:-20px;margin-bottom:20px;min-height:20px'>&nbsp;</div><br>"),
      	                                   array('title' => "<label class='grid_3 alpha'>" . MODULE_PAYMENT_GERMANBT_TEXT_BANK_NAME . "</label>",
      	                                         'field' => push_draw_input_field('banktransfer_bankname',$gbt_array["bt_bankname"], ' onclick="document.checkout_payment.payment['.$this->index.'].checked=true" class="grid_6"'). "<div class='grid_3 omega' style='margin-left:-20px;margin-bottom:20px;min-height:20px'>&nbsp;</div><br>"),
      	                                   array('title' => '',
      	                                         'field' => push_draw_hidden_field('recheckok', $gbt_array["recheckok"]))
      	                                   ));

      if (MODULE_PAYMENT_GERMANBT_FAX_CONFIRMATION =='true'){
        $selection['fields'][] = array('title' => MODULE_PAYMENT_GERMANBT_TEXT_NOTE,
      	                               'field' => MODULE_PAYMENT_GERMANBT_TEXT_NOTE2 . '<a href="' . MODULE_PAYMENT_GERMANBT_URL_NOTE . '" target="_blank"><b>' . MODULE_PAYMENT_GERMANBT_TEXT_NOTE3 . '</b></a>' . MODULE_PAYMENT_GERMANBT_TEXT_NOTE4);
      	$selection['fields'][] = array('title' => MODULE_PAYMENT_GERMANBT_TEXT_BANK_FAX,
      	                               'field' => push_draw_checkbox_field('banktransfer_fax', 'on'));

      }

      return $selection;
    }

    function pre_confirmation_check(){
      global $_POST, $gbt_array, $gbt_number;
      global $banktransfer_number, $banktransfer_blz;

      push_session_register('gbt_array');
      push_session_register('gbt_number');
	  
// SCHUTZ VOR LEEREN VARIABLEN!!!!
		if ($banktransfer_number == '') $banktransfer_number = $_POST['banktransfer_number'];
		if ($banktransfer_blz == '') $banktransfer_blz = $_POST['banktransfer_blz'];
		if ($gbt_number == '') $gbt_number = $_POST['banktransfer_number'];
		if ($gbt_array["bt_blz"] == '') $gbt_array["bt_blz"] = $_POST['banktransfer_blz'];
		if ($gbt_array["bt_owner"] == '') $gbt_array["bt_owner"] = $_POST['banktransfer_owner'];
		if ($gbt_array["bt_bankname"] == '') $gbt_array["bt_bankname"] = $_POST['banktransfer_bankname'];
// ENDE SCHUTZ VOR LEEREN VARIABLEN!!!

   //   if ($_POST['banktransfer_fax'] == false) {
// Klasse laden
		include(DIR_WS_CLASSES . 'banktransfer_validationrt.php');

        $banktransfer_validation = new AccountCheck;
        $banktransfer_result = $banktransfer_validation->CheckAccount($banktransfer_number, $banktransfer_blz);

		$this->writelog( "LOG VALIDATION", "\nYum Yum Doodle Dum!\n\n". $banktransfer_number . "---" .  $banktransfer_blz ."\n" .  $banktransfer_result );


// Array und Rueckgabewerte initialisieren
		$gbt_number = $banktransfer_validation->banktransfer_number;
		$gbt_array = array("bt_owner" => $_POST['banktransfer_owner'],
							"bt_blz" => $banktransfer_validation->banktransfer_blz,
							"bt_bankname" => $banktransfer_validation->Bankname,
							"bt_prz" => $banktransfer_validation->PRZ,
							"bt_status" => $banktransfer_result);

        if ($banktransfer_validation->Bankname != '')
          $gbt_array["bt_bankname"] =  $banktransfer_validation->Bankname;
        else
          $gbt_array["bt_bankname"] = $_POST['banktransfer_bankname'];



        if ($banktransfer_result > 0 ||  $_POST['banktransfer_owner'] == '') {
          if ($_POST['banktransfer_owner'] == '') {
            $error = 'Name des Kontoinhabers fehlt!';
            $recheckok = '';
          } else {
            switch ($banktransfer_result) {
              case 1: // number & blz not ok
                $error = MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_1;
                $recheckok = 'true';
                break;
              case 5: // BLZ not found
                $error = MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_5;
//                $recheckok = 'true';
                break;
              case 8: // no blz entered
                $error = MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_8;
               break;
              case 9: // no number entered
                $error = MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_9;
                break;
              default:
                $error = MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR_4;
                $recheckok = 'true';
                break;
            }
          }


                /* Log schreiben */
                $LogContent =  
								"\n\tKontoinhaber.......: " . $_POST['banktransfer_owner'] .
								"\n\tKontonummer........: " . $banktransfer_validation->banktransfer_number .
								"\n\tBankleitzahl.......: " . $banktransfer_validation->banktransfer_blz .
								"\n\tBankname...........: " . $banktransfer_validation->Bankname .
								"\n\tBerechnungsmethode.: " . $banktransfer_validation->PRZ .
								"\n\tErgebnis...........: " . $banktransfer_result .' - ' . $internal_error;


                $this->writelog( "LOG VALIDATION", $LogContent );

          if ($_POST['recheckok'] != "true") {
            $gbt_array['recheckok'] = $recheckok;
            $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) ;
            push_redirect(push_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
          }
        }
		
	/*    } else {
       			$gbt_array = array("bt_owner" => $_POST['banktransfer_owner'],
                          "bt_fax" => true);
      }
	*/
    }

    function confirmation() {
      global $_POST, $gbt_array, $gbt_number, $checkout_form_action, $checkout_form_submit;
      //, $banktransfer_bankname, $banktransfer_blz, $banktransfer_number,

      if (!$_POST['banktransfer_owner'] == '') {
        $confirmation = array('title' => $this->title,
                              'fields' => array(array('title' => MODULE_PAYMENT_GERMANBT_TEXT_BANK_OWNER,
                                                      'field' => $gbt_array["bt_owner"]),
                                                array('title' => MODULE_PAYMENT_GERMANBT_TEXT_BANK_BLZ,
                                                      'field' => $gbt_array["bt_blz"]),
                                                array('title' => MODULE_PAYMENT_GERMANBT_TEXT_BANK_NUMBER,
                                                      'field' => $gbt_number),
                                                array('title' => MODULE_PAYMENT_GERMANBT_TEXT_BANK_NAME,
                                                      'field' => $gbt_array["bt_bankname"])
                                                ));
      }
      if ($gbt_array["bt_fax"] == true) {
        $confirmation = array('fields' => array(array('title' => MODULE_PAYMENT_GERMANBT_TEXT_BANK_FAX)));
      }
      return $confirmation;
    }

    function process_button() {
      return false;

    }

    function before_process() {
      return false;
    }

    function after_process() {
      global $insert_id, $gbt_number, $gbt_array;
      // $_POST, $banktransfer_val, $banktransfer_owner, $banktransfer_bankname, $banktransfer_blz, $banktransfer_number, $banktransfer_status, $banktransfer_prz, $banktransfer_fax, $checkout_form_action, $checkout_form_submit;

//      push_db_query("INSERT INTO banktransfer (orders_id, banktransfer_blz, banktransfer_bankname, banktransfer_number, banktransfer_owner, banktransfer_status, banktransfer_prz) VALUES ('" . $insert_id . "', '" . $gbt_array['bt_blz'] . "', '" . $gbt_array['bt_bankname'] . "', '" . $gbt_number . "', '" . $gbt_array['bt_owner'] ."', '" . $gbt_array['bt_status'] ."', '" . $gbt_array['bt_prz'] ."')");
// ('" . $insert_id . "', '" . $gbt_array['bt_blz'] . "', '" . $gbt_array['bt_bankname'] . "', '" . $gbt_number . "', '" . $gbt_array['bt_owner'] ."', '" . $gbt_array['bt_status'] ."', '" . $gbt_array['bt_prz'] ."')");
      $sql_data_array = array('orders_id' => $insert_id,
                              'banktransfer_blz' => $gbt_array['bt_blz'],
                              'banktransfer_bankname' => $gbt_array['bt_bankname'],
                              'banktransfer_number' => $gbt_number,
                              'banktransfer_owner' => $gbt_array['bt_owner'],
                              'banktransfer_status' => $gbt_array['bt_status'],
                              'banktransfer_prz' => $gbt_array['bt_prz']);

      if ($gbt_array['bt_fax'] == true)
        $sql_data_array ["banktransfer_fax"] = $gbt_array['bt_fax'];

      push_db_perform(TABLE_GERMANBT, $sql_data_array);

      push_session_unregister('gbt_array');
      push_session_unregister('gbt_number');

    }

    function get_error() {
      global $_GET;

      $error = array('title' => MODULE_PAYMENT_GERMANBT_TEXT_BANK_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_GERMANBT_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }
      return $this->_check;
   }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Banktranfer Payments', 'MODULE_PAYMENT_GERMANBT_STATUS', 'True', 'Do you want to accept banktransfer payments?', '6', '1', 'push_cfg_select_option(array(\'True\', \'False\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_GERMANBT_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'push_get_zone_class_title', 'push_cfg_pull_down_zone_classes(', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_GERMANBT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_GERMANBT_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'push_cfg_pull_down_order_statuses(', 'push_get_order_status_name', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key,configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added)values ('Allow Fax Confirmation', 'MODULE_PAYMENT_GERMANBT_FAX_CONFIRMATION', 'false', 'Do you want to allow fax confirmation?', '6', '2', 'push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added)values ('Use database lookup for BLZ?', 'MODULE_PAYMENT_GERMANBT_DATABASE_BLZ', 'false', 'Do you want to use database lookup for BLZ? Ensure that the table banktransfer_blz exists and is set up properly!', '6', '0', 'push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fax-URL','MODULE_PAYMENT_GERMANBT_URL_NOTE', 'fax.html', 'The fax-confirmation file. It must located in catalog-dir', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable banktransfer after x orders?', 'MODULE_PAYMENT_GERMANBT_ENABLE_AFTER', 'false', 'Do you want to enable banktransfer after x orders? ', '6', '0', 'push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('After x times banktransfer will be enabled', 'MODULE_PAYMENT_GERMANBT_ENABLE_AFTER_TIMES', '3', 'Number of orders which must be finished before banktransfer will be enabled', '6', '0', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key,configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Order Status to calculate', 'MODULE_PAYMENT_GERMANBT_ENABLE_AFTER_ORDER_STATUS', '3', 'Set status of orders which will be used to count', '6', '0', 'push_cfg_pull_down_order_statuses(','push_get_order_status_name', now())");
      push_db_query("CREATE TABLE IF NOT EXISTS " . TABLE_GERMANBT . " (orders_id int(11) NOT NULL default '0', banktransfer_owner varchar(64) default NULL, banktransfer_number varchar(24) default NULL, banktransfer_bankname varchar(255) default NULL, banktransfer_blz varchar(8) default NULL, banktransfer_status int(11) default NULL, banktransfer_prz char(2) default NULL, banktransfer_fax char(2) default NULL, KEY orders_id(orders_id))");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_GERMANBT_STATUS', 'MODULE_PAYMENT_GERMANBT_ZONE', 'MODULE_PAYMENT_GERMANBT_ORDER_STATUS_ID', 'MODULE_PAYMENT_GERMANBT_SORT_ORDER', 'MODULE_PAYMENT_GERMANBT_DATABASE_BLZ', 'MODULE_PAYMENT_GERMANBT_FAX_CONFIRMATION', 'MODULE_PAYMENT_GERMANBT_URL_NOTE', 'MODULE_PAYMENT_GERMANBT_ENABLE_AFTER','MODULE_PAYMENT_GERMANBT_ENABLE_AFTER_TIMES', 'MODULE_PAYMENT_GERMANBT_ENABLE_AFTER_ORDER_STATUS');
    }
	
	    ////
    /* --- Added FrankM 20050413 --- */
    // Diese Funktion schreibt das Log
    function writelog( $descr, $lgString ) {
        define('LOGFILE', DIR_WS_LIB . 'data/bt_validation.log');
        $dateTime   = date( "j F, Y, g:i a" );

        error_log (
                "[bt_validation -> $dateTime] -> Referer: " .
                getenv( "HTTP_REFERER" ) .
                "\n\t$descr: $lgString\n\n",
                3, LOGFILE );
    }
  }
?>
