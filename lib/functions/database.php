<?php
/*
  $Id: database.php,v 1.21 2003/06/09 21:21:59 hpdl Exp $
  adapted for Separate Pricing Per Customer 2005/03/04

  ösCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function push_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = mysql_pconnect($server, $username, $password);
    } else {
      $$link = mysql_connect($server, $username, $password);
    }

    if ($$link) mysql_select_db($database);
	
// BOF CHARSET HACK
// zu Testzwecken eingefügt; evtl. nützlich, falls irgendwann der Shop auf UTF8 umgestellt werden sollte.
 mysql_query("SET CHARACTER SET 'utf8'");
// EOF CHARSET HACK

    return $$link;
  }

  function push_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function push_db_error($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function push_db_query($query, $link = 'db_link') {
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = mysql_query($query, $$link) or push_db_error($query, mysql_errno(), mysql_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
       $result_error = mysql_error();
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function push_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . push_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . push_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return push_db_query($query, $link);
  }

  function push_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function push_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function push_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function push_db_insert_id($link = 'db_link') {
     global $$link;
	return mysql_insert_id($$link);
  }

  function push_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function push_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }

  function push_db_output($string) {
    return htmlspecialchars($string);
  }

  function push_db_input($string, $link = 'db_link') {
    global $$link;

    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($string, $$link);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($string);
    }

    return addslashes($string);
  }

  function push_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(push_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = push_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }

////////////////////////////////////////////////////SPPC//////////////////////////////////////////////////////////////////////////
// BOF Separate Pricing Per Customer, adapted from sample code in user comments on
// 2013 change to different treatment: use customer ids instead of customer groups
// http://www.php.net/manual/en/function.mysql-list-tables.php
// Wrap DB_DATABASE with Back Ticks, Fixes Hyphens in Database Name, code from
// Jef Stumpf/Krumbsnatcher: http://forums.oscommerce.com/index.php?showtopic=53436&view=findpost&p=563454
	function push_db_table_exists($table, $link = 'db_link')
	{
		$result = push_db_query("show table status from `" . DB_DATABASE . "`");
		while ($list_tables = push_db_fetch_array($result))
		{
			if ($list_tables['Name'] == $table)
			{
				return true;
			}
		}
		return false;
	}
//FUNCTION
	function push_db_check_age_specials_retail_table() {
		$result = push_db_query("show table status from `" . DB_DATABASE . "`");
		$last_update_table_specials = "2000-01-01 12:00:00";
		$table_srp_exists = false;
		while ($list_tables = push_db_fetch_array($result))
		{
			if ($list_tables['Name'] == TABLE_SPECIALS_RETAIL_PRICES)
			{
				$table_srp_exists = true;
				$last_update_table_srp = $list_tables['Update_time'];
			}
			if ($list_tables['Name'] == TABLE_SPECIALS)
			{
				$last_update_table_specials = $list_tables['Update_time'];
			}
		} // end while
	
		if(!$table_srp_exists || ($last_update_table_specials > $last_update_table_srp))
		{
			if ($table_srp_exists)
			{
				$query1 = "truncate " . TABLE_SPECIALS_RETAIL_PRICES . "";
				if (push_db_query($query1)) 
				{
					$query2 = "insert into " . TABLE_SPECIALS_RETAIL_PRICES . " select s.products_id, s.specials_new_products_price, s.status, s.customers_id from " . TABLE_SPECIALS . " s where s.customers_id = '0'";
				$result =  push_db_query($query2);
				}
			}
			else
			{ // table specials_retail_prices does not exist
				$query1 = "create table " . TABLE_SPECIALS_RETAIL_PRICES . " (products_id int NOT NULL default '0', specials_new_products_price decimal(15,4) NOT NULL default '0.0000', status tinyint, customers_id int, primary key (products_id) )" ;
				$query2 = "insert into " . TABLE_SPECIALS_RETAIL_PRICES . " select s.products_id, s.specials_new_products_price, s.status, s.customers_id from " . TABLE_SPECIALS . " s where s.customers_id = '0'";
				if( push_db_query($query1) && push_db_query($query2) )
				{
					; // execution succesfull
				}
			} // end else
		} // end if(!$table_srp_exists || ($last_update_table_specials....
	}
//FUNCTION
	function push_db_check_age_products_group_prices_cg_table($pg_customer_id)
	{
		$result = push_db_query("show table status from `" . DB_DATABASE . "`");
		$last_update_table_pgp = strtotime('2000-01-01 12:00:00');
		$table_pgp_exists = false;
		while ($list_tables = push_db_fetch_array($result))
		{
			if ($list_tables['Name'] == TABLE_PRODUCTS_CUSTOMER_PRICES)
			{
				$table_pgp_exists = true;
				$last_update_table_pgp = strtotime($list_tables['Update_time']);
			}
			elseif ($list_tables['Name'] == TABLE_SPECIALS )
			{
				$last_update_table_specials = strtotime($list_tables['Update_time']);
			}
			elseif ($list_tables['Name'] == TABLE_PRODUCTS )
			{
				$last_update_table_products = strtotime($list_tables['Update_time']);
			}
			elseif ($list_tables['Name'] == TABLE_PRODUCTS_GROUPS )
			{
				$last_update_table_products_groups = strtotime($list_tables['Update_time']);
			}
		} // end while
		if ($table_pgp_exists == false)
		{
			$create_table_sql = "create table IF NOT EXISTS " . TABLE_PRODUCTS_CUSTOMER_PRICES . " (customer_id  int NOT NULL default 0, products_id int NOT NULL default '0', products_price decimal(15,4) NOT NULL default '0.0000', specials_new_products_price decimal(15,4) default NULL, status tinyint, primary key (products_id) )" ;
			$fill_table_sql1 = "insert into " . TABLE_PRODUCTS_CUSTOMER_PRICES ." select '" . $pg_customer_id . "', p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
			$update_table_sql1 = "update " . TABLE_PRODUCTS_CUSTOMER_PRICES ." ppt left join " . TABLE_PRODUCTS_CUSTOMERS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_id ='" . $pg_customer_id . "' and ppt.customer_id ='" . $pg_customer_id . "' ";
			$update_table_sql2 = "update " . TABLE_PRODUCTS_CUSTOMER_PRICES ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_id = '" . $pg_customer_id . "' and ppt.customer_id = '" . $pg_customer_id . "' ";
			if ( push_db_query($create_table_sql) && push_db_query($fill_table_sql1) && push_db_query($update_table_sql1) && push_db_query($update_table_sql2) )
			{
				return true;
			}
		} // end if ($table_pgp_exists == false)
		
		if ( ($last_update_table_pgp < $last_update_table_products && (time() - $last_update_table_products > (int)MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE * 60) ) || $last_update_table_specials > $last_update_table_pgp || $last_update_table_products_groups > $last_update_table_pgp )
		{ // then the table should be updated
			$empty_query = "DELETE FROM " . TABLE_PRODUCTS_CUSTOMER_PRICES . " WHERE customer_id = '" . $pg_customer_id . "' ";
			$fill_table_sql1 = "insert into " . TABLE_PRODUCTS_CUSTOMER_PRICES ." select '" . $pg_customer_id . "', p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
			$update_table_sql1 = "update " . TABLE_PRODUCTS_CUSTOMER_PRICES ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_id ='" . $pg_customer_id . "'  and ppt.customer_id ='" . $pg_customer_id . "' ";
			$update_table_sql2 = "update " . TABLE_PRODUCTS_CUSTOMER_PRICES . " ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_id = '" . $pg_customer_id . "'  and ppt.customer_id ='" . $pg_customer_id . "' ";
			if ( push_db_query($empty_query) && push_db_query($fill_table_sql1) && push_db_query($update_table_sql1) && push_db_query($update_table_sql2) )
			{
				return true;
			}
		}
		else 
		{ // no need to update
			return true;
		} // end checking for update
	}

// EOF Separate Pricing Per Customer
////////////////////////////////////////////////////SPPC//////////////////////////////////////////////////////////////////////////
?>
