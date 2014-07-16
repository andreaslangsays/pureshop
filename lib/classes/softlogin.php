<?php
/***
 * Softlögin 2013
 * keep user softlogged even if session has endet.
 * 
 * Der Kunde wird also wieder eingeloggt, er muss sich jedoch für bestimmte Dinge richtig einloggen
 * zentrale Eigenschaft ist daher softlogin
 * solange diese Eigenschaft true ist, muss der Nutzer sich "richtig" einloggen danach wird er für eine Weile so akzeptiert 
 * (1h bis zur Abmeldung bzw Schließen des Browserfensters)
 * das könnte auch konfigurierbar gemacht werden
 *
 */

class softlogin{

	var $userid, $loginsession, $userpass, $softlogin, $keks, $salt, $duration, $cookiename;

	function softlogin()
	{
		
		//Init
		$this->cookiename = "_SXL";
		$this->salt = rand(10,100);
		$this->duration = 365 * 24 * 60 * 60;
		//Einlesen des (vorhandenen?) Cookies
		if($this->read_cookie())
		{
			//todo if userid größer 0 und customerid nicht vorhanden : User einloggen (als softlogin)
			//wenn user eingeloggt ist nichts ändern das muss beim (richtigen Login passieren)
			//Login und Logout sind noch zu bearbeiten und die harten Dateien (Login nötig bei Checkout persönliche Daten bearbeiten etc.)
/*			if(isset($_SESSION['customer_id']))
			{
				if($this->is_softlogin())
				{
					$this->softlogin=true;	
				}
				else
				{
					$this->loginsession=session_id();
				}
				$this->userid=$_SESSION['customer_id'];
				$this->set_cookie();
			}
			else
			{
				$this->loginsession=session_id(); //Session mit der Kunde sich einloggt
				$this->userid=false;//Kunde ist nicht eingeloggt
				$this->cookieset=false;
				$this->softlogin=false;
				$this->set_cookie();
			}
			*/
		}
		else
		{
			$this->loginsession=session_id(); //Session mit der Kunde sich einloggt
			$this->userid=0;//Kunde ist nicht eingeloggt
			$this->softlogin=false;
			$this->set_cookie();
		}
	}

	function generate_keks(){
		$this->keks=array($this->softlogin,($this->userid + $this->salt),$this->loginsession, $this->salt);
	}

	function decode_keks(){
		$this->softlogin = $this->keks[0];
		$this->userid =  $this->keks[1] - $this->keks[3];
		$this->loginsession = $this->keks[2];
	}

	function set_cookie(){ //setzt den Cookie^
		global $_COOKIE;
		$this->generate_keks();
		setcookie($this->cookiename, serialize($this->keks), time() + $this->duration);
		return true;
	}
	
	function is_softlogin(){
		
		return true;
	}
	
	function clear_cookie(){
		setcookie($this->cookiename, '', 0);
		return true;
	}
	
	function read_cookie(){
		global $_COOKIE;
		if(isset($_COOKIE[$this->cookiename])){
			$this->keks=unserialize($_COOKIE[$this->cookiename]);
			$this->decode_keks();
			return true;
		}
		else
		{
			return false;
		}
	}

}


?>