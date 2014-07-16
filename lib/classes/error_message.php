<?php
/**
 * 
 * redesign 2012
 * 
 * errör message stack (a class for that)
 * 
 * 
 */

//needs to be registered in session ->  application_top.php

class error_message{

var $message;
var $level;

//constructor
	function error_message(){
		//constructor
		$this->message ='';

		$this->level = 1;	//for future use 
							//to set an urgency level
	}

//concat message
	function add($error){
		
		$error=trim($error);
		if(strlen($error) > 0)
		{
			$this->message .= $error;
			return true;
		}
		else{
			return false;
		}
	}

//display message
	function display($clean=true){
	//if ($clean) erase it 
		$output = $this->message;
		if($clean){
			$this->message ='';
			$_SESSION['error_message']='';
		}
		return $output;
	}

//is there a message?
	function message_set(){
		if(strlen($this->message) > 0){
			return true;
		}else{
			return false;
		}
	}


}
?>
