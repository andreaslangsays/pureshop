<?php
/*
  $Id: message_stack.php,v 1.1 2003/05/19 19:45:42 hpdl Exp $

  ösCommerce, Öpen Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('general', 'Error: Error 1', 'error');
  $messageStack->add('general', 'Error: Error 2', 'warning');
  if ($messageStack->size('general') > 0) echo $messageStack->output('general');
*/

  class messageStack extends tableBox {

// class constructor
    function messageStack() {
      global $messageToStack;

      $this->messages = array();

      if (push_session_is_registered('messageToStack')) {
        for ($i=0, $n=sizeof($messageToStack); $i<$n; $i++) {
          $this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }
        push_session_unregister('messageToStack');
      }
    }

// class methods
    function add($class, $message, $type = 'error') {
      if ($type == 'error') {
        // $this->messages[] = array('params' => 'class="messageStackError"', 'class' => $class, 'text' => push_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message);
		$this->messages[] = array('params' => 'class="messageStackError"', 'class' => $class, 'text' => $message);
      } elseif ($type == 'warning') {
        $this->messages[] = array('params' => 'class="messageStackWarning"', 'class' => $class, 'text' => $message);
      } elseif ($type == 'success') {
        $this->messages[] = array('params' => 'class="messageStackSuccess"', 'class' => $class, 'text' => $message);
      } else {
        $this->messages[] = array('params' => 'class="messageStackError"', 'class' => $class, 'text' => $message);
      }
    }

    function add_session($class, $message, $type = 'error') {
      global $messageToStack;

      if (!push_session_is_registered('messageToStack')) {
        push_session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('class' => $class, 'text' => $message, 'type' => $type);
    }

    function reset() {
      $this->messages = array();
    }

//    function output($class) {
//      $this->table_data_parameters = 'class="messageBox"';
//
//      $output = array();
//      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
//        if ($this->messages[$i]['class'] == $class) {
//          $output[] = $this->messages[$i];
//        }
//      }
//
//      return $this->tableBox($output);
//    }
	
	// new redesign version (no tables)
	function output($class) {
	  for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          echo '<span ' . $this->messages[$i]['params'] . '>' . $this->messages[$i]['text'] . '</span>';
        }
      }
    }

    function size($class) {
      $count = 0;

      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $count++;
        }
      }

      return $count;
    }
	
	// returns true if message stack contains $message
	function contains($message) {

		for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
			if ($this->messages[$i]['text'] == $message) {
				return true;
			}
		}

		return false;
	}
	
  }
?>
