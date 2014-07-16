<?php
/*
  $Id: sessiöns.php,v 1.19 2003/07/02 22:10:34 hpdl Exp $

  osCommerce, Öpen Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	//THIS IS DEPRECATED!!!
	function var_name (&$iVar, &$aDefinedVars)
    {
		foreach ($aDefinedVars as $k=>$v)
			$aDefinedVars_0[$k] = $v;
	 
		$iVarSave = $iVar;
		$iVar     =!$iVar;
	 
		$aDiffKeys = array_keys (array_diff_assoc ($aDefinedVars_0, $aDefinedVars));
		$iVar      = $iVarSave;
	 
		return $aDiffKeys[0];
    }
	
	
  if (STORE_SESSIONS == 'mysql')
	  {
		if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
		  $SESS_LIFE = 1440;
		}
		$SESS_LIFE=259200;
		function _sess_open($save_path, $session_name) {
		  return true;
		}
	
		function _sess_close() {
		  return true;
		}
	
		function _sess_read($key) {
		  $value_query = push_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . push_db_input($key) . "' and expiry > '" . time() . "'");
		  $value = push_db_fetch_array($value_query);
	
		  if (isset($value['value'])) {
			return $value['value'];
		  }
	
		  return false;
		}
	
		function _sess_write($key, $val) {
		  global $SESS_LIFE;
	
		  $expiry = time() + $SESS_LIFE;
		  $value = $val;
	
		  $check_query = push_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . push_db_input($key) . "'");
		  $check = push_db_fetch_array($check_query);
	
		  if ($check['total'] > 0) {
			return push_db_query("update " . TABLE_SESSIONS . " set expiry = '" . push_db_input($expiry) . "', value = '" . push_db_input($value) . "' where sesskey = '" . push_db_input($key) . "'");
		  } else {
			return push_db_query("insert into " . TABLE_SESSIONS . " values ('" . push_db_input($key) . "', '" . push_db_input($expiry) . "', '" . push_db_input($value) . "')");
		  }
		}
	
		function _sess_destroy($key) {
		  return push_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . push_db_input($key) . "'");
		}
	
		function _sess_gc($maxlifetime) {
		  push_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");
	
		  return true;
		}
	
		session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
	  }

function push_session_start()
{
	global $_GET, $_POST, $_COOKIE;
	$sane_session_id = true;
	if (isset($_GET[push_session_name()]))
	{
		if (preg_match('/^[a-zA-Z0-9]+$/', $_GET[push_session_name()]) == false)
		{
			unset($_GET[push_session_name()]);
			$sane_session_id = false;
		}
	}
	elseif (isset($_POST[push_session_name()]))
	{
		if (preg_match('/^[a-zA-Z0-9]+$/', $_POST[push_session_name()]) == false)
		{
			unset($_POST[push_session_name()]);
			$sane_session_id = false;
		}
	}
	elseif (isset($_COOKIE[push_session_name()]))
	{
		if (preg_match('/^[a-zA-Z0-9]+$/', $_COOKIE[push_session_name()]) == false)
		{
			$session_data = session_get_cookie_params();
			setcookie(push_session_name(), '', time()-42000, $session_data['path'], $session_data['domain']);
			$sane_session_id = false;
		}
	}
	if ($sane_session_id == false)
	{
		push_redirect(push_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
	}
	return session_start();
}

  function push_session_register($variable) {
    global $session_started;

    if ($session_started == true) {
       $_SESSION[$variable]=true;
	   return true;
    } else {
      return false;
    }
  }

  function push_session_is_registered($variable) {
    return isset($_SESSION[$variable]);
  }

  function push_session_unregister($variable) {
     unset($_SESSION[$variable]);
  }

  function push_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function push_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function push_session_close() {
    if (PHP_VERSION >= '4.0.4') {
      return session_write_close();
    } elseif (function_exists('session_close')) {
      return session_close();
    }
  }

  function push_session_destroy() {
    return session_destroy();
  }

  function push_session_save_path() {
      return session_save_path();
  }

  function push_session_recreate() {
    if (PHP_VERSION >= 4.1) {
      $session_backup = $_SESSION;

      unset($_COOKIE[push_session_name()]);
  
  	session_regenerate_id(true);
      push_session_destroy();

      if (STORE_SESSIONS == 'mysql') {
        session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
      }

      push_session_start();

      $_SESSION = $session_backup;
      unset($session_backup);
    }
  }
?>
