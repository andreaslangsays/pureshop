<?php
/*
  $Idä: payment.php,v 1.37 2003/06/09 22:26:32 hpdl Exp $
  adapted for Separate Pricing Per Customer v4 2005/02/26

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class payment {
    var $modules, $selected_module;

// class constructor
    function payment($module = '') {
      global $payment, $language, $PHP_SELF;

      if (defined('MODULE_PAYMENT_INSTALLED') && push_not_null(MODULE_PAYMENT_INSTALLED)) {
       // BOF Separate Pricing Per Customer, next line original code/////////////////////////////////////////////////////////////////
     //       $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
	     global $sppc_customer_group_id;
	     if(!push_session_is_registered('sppc_customer_group_id')) {
	     $customer_group_id = '0';
	     } else {
	      $customer_group_id = $sppc_customer_group_id;
	     }
	   $customer_payment_query = push_db_query("select customers_payment_allowed from " . TABLE_CUSTOMERS . " where customers_id = '" . $_SESSION['customer_id'] . "'");
	   if ($customer_payment = push_db_fetch_array($customer_payment_query)  ) {
		   if (push_not_null($customer_payment['customers_payment_allowed'])) {
		  $temp_payment_array = explode(';', $customer_payment['customers_payment_allowed']);
		  $installed_modules = explode(';', MODULE_PAYMENT_INSTALLED);
		  for ($n = 0; $n < sizeof($installed_modules) ; $n++) {
			  // check to see if a payment method is not de-installed
			  if ( in_array($installed_modules[$n], $temp_payment_array ) ) {
				  $payment_array[] = $installed_modules[$n];
			  }
		  } // end for loop
		  $this->modules = $payment_array;
	   } else {
		   $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
	   }
	   } else { // default
		   $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
	   }
     // EOF Separate Pricing Per Customer///////////////////////////////////////////////////////////////


        $include_modules = array();

        if ( (push_not_null($module)) && (in_array($module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), $this->modules)) ) {
          $this->selected_module = $module;

          $include_modules[] = array('class' => $module, 'file' => $module . '.php');
        } else {
          reset($this->modules);
          while (list(, $value) = each($this->modules)) {
            $class = substr($value, 0, strrpos($value, '.'));
            $include_modules[] = array('class' => $class, 'file' => $value);
          }
        }

        for ($i=0, $n=sizeof($include_modules); $i<$n; $i++) {
          include(DIR_WS_LANGUAGES . $language . '/modules/payment/' . $include_modules[$i]['file']);
          include(DIR_WS_MODULES . 'payment/' . $include_modules[$i]['file']);

          $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class'];
        }

// if there is only one payment method, select it as default because in
// checkout_confirmation.php the $payment variable is being assigned the
// $_POST['payment'] value which will be empty (no radio button selection possible)
        if ( (push_count_payment_modules() == 1) && (!isset($GLOBALS[$payment]) || (isset($GLOBALS[$payment]) && !is_object($GLOBALS[$payment]))) ) {
          $payment = $include_modules[0]['class'];
        }

        if ( (push_not_null($module)) && (in_array($module, $this->modules)) && (isset($GLOBALS[$module]->form_action_url)) ) {
          $this->form_action_url = $GLOBALS[$module]->form_action_url;
        }
      }
    }

// class methods
/* The following method is needed in the checkout_confirmation.php page
   due to a chicken and egg problem with the payment class and order class.
   The payment modules needs the order destination data for the dynamic status
   feature, and the order class needs the payment module title.
   The following method is a work-around to implementing the method in all
   payment modules available which would break the modules in the contributions
   section. This should be looked into again post 2.2.
*/
    function update_status() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module])) {
          if (function_exists('method_exists')) {
            if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
              $GLOBALS[$this->selected_module]->update_status();
            }
          } else { // PHP3 compatibility
            @call_user_method('update_status', $GLOBALS[$this->selected_module]);
          }
        }
      }
    }

    function javascript_validation() {
      $js = '';
      if (is_array($this->modules)) {
        $js = '<script language="javascript"><!-- ' . "\n" .
              'function check_form() {' . "\n" .
              '  var error = 0;' . "\n" .
              '  var error_message = "' . JS_ERROR . '";' . "\n" .
              '  var payment_value = null;' . "\n" .
              '  if (document.checkout_payment.payment.length) {' . "\n" .
              '    for (var i=0; i<document.checkout_payment.payment.length; i++) {' . "\n" .
              '      if (document.checkout_payment.payment[i].checked) {' . "\n" .
              '        payment_value = document.checkout_payment.payment[i].value;' . "\n" .
              '      }' . "\n" .
              '    }' . "\n" .
              '  } else if (document.checkout_payment.payment.checked) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  } else if (document.checkout_payment.payment.value) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  }' . "\n\n";

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $js .= $GLOBALS[$class]->javascript_validation();
          }
        }

        //$js .= "\n" . '  if (payment_value == null) {' . "\n" .
        $js .= "\n" . '  if (payment_value == null && submitter != 1) {' . "\n" . // This line edited for CCGV
               '    error_message = error_message + "' . JS_ERROR_NO_PAYMENT_MODULE_SELECTED . '";' . "\n" .
               '    error = 1;' . "\n" .
               '  }' . "\n\n" .
              //'  if (error == 1) {' . "\n" .
              '  if (error == 1 && submitter != 1) {' . "\n" . // This line edited for CCGV
               '    alert(error_message);' . "\n" .
               '    return false;' . "\n" .
               '  } else {' . "\n" .
               '    return true;' . "\n" .
               '  }' . "\n" .
               '}' . "\n" .
               '//--></script>' . "\n";
      }

      return $js;
    }

    function selection() {
      $selection_array = array();

      if (is_array($this->modules)) {
        reset($this->modules);
        $ix=0;
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
          	$selection = $GLOBALS[$class]->index=$ix;
            $selection = $GLOBALS[$class]->selection();
            $ix++;
            if (is_array($selection)) $selection_array[] = $selection;
          }
        }
      }

      return $selection_array;
    }

 //ADDED FOR CCGV
 // check credit covers was setup to test whether credit covers is set in other parts of the code
function check_credit_covers() {
	global $credit_covers;

	return $credit_covers;
}
// END ADDED FOR CCGV

    function pre_confirmation_check() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
// ADDED FOR CCGV
	    if ($credit_covers) {
            $GLOBALS[$this->selected_module]->enabled = false;
            $GLOBALS[$this->selected_module] = NULL;
            $payment_modules = '';
          } else {
// END ADDED FOR CCGV
          $GLOBALS[$this->selected_module]->pre_confirmation_check();
        }
      }
    }
    } // CCGV

    function confirmation() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->confirmation();
        }
      }
    }

    function process_button() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->process_button();
        }
      }
    }

    function before_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->before_process();
        }
      }
    }

    function after_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->after_process();
        }
      }
    }

    function get_error() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->get_error();
        }
      }
    }
  }
?>
