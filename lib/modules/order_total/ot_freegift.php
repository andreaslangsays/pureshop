<?php
  class ot_freegift {
    var $title, $output;

    function ot_freegift() {
      $this->code = 'ot_freegift';
      $this->title = MODULE_FREEGIFT_TITLE;
      $this->description = MODULE_FREEGIFT_DESCRIPTION;
      $this->enabled = ((MODULE_FREEGIFT_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_FREEGIFT_SORT_ORDER;
      $this->output = array();
    }

    function process() {

      global $order, $currencies, $ot_subtotal, $cart, $together_product_names;

      //$od_amount = $this->calculate_freegift();
    }

    function calculate_freegift() {
     global $cart, $together_product_names, $languages_id;

	$freegift_query =  push_db_query("SELECT pd.products_name, p.products_model FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd Inner Join " . TABLE_PRODUCTS . " p ON p.products_id = pd.products_id WHERE p.products_id =  '" . $cart->get_freegift() . "'");
	$freegift = push_db_fetch_array($freegift_query);
	if ($cart->get_freegift() != 0) {
	  $sql_data_array = array('orders_id' => $insert_id,
  						  'products_id' => $cart->get_freegift(),
						  'products_model' => $freegift['products_model'],
						  'products_name' => $freegift['products_name']);
	} else {
	  $sql_data_array = array('orders_id' => $insert_id,
  						  'products_id' => $cart->get_freegift(),
						  'products_model' => 'NA',
						  'products_name' => 'No Free Gift');  
	  }
	  push_db_perform('orders_freegift', $sql_data_array);
	// End Free Gift Modification  
      return $od_amount;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FREEGIFT_STATUS'");
        $this->check = mysql_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_FREEGIFT_STATUS', 'MODULE_FREEGIFT_MINIMUM_SPEND', 'MODULE_FREEGIFT_CATEGORY');
    }

	function create_tables() {
	  $this->drop_tables();
	  $create_tables_sql = "CREATE TABLE `orders_freegift` (
					`orders_freegift_id` int(11) NOT NULL auto_increment,
					`orders_id` int(11) default NULL,
					`products_id` int(11) default NULL,
					`products_model` varchar(12) default NULL,
					`products_name` varchar(64) default NULL,
					PRIMARY KEY  (`orders_freegift_id`)
					)";
	  return(push_db_query($create_tables_sql));
	}

	function drop_tables() {
	  push_db_query("DROP TABLE IF EXISTS orders_freegift");
	}
	
    function install() {
	  $tester = $this->create_tables();
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values
    ('Activate Free Gift', 'MODULE_FREEGIFT_STATUS', 'true', 'Do you want to enable the Free Gift module?', '6', '1','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  push_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values
	('Minimum Spend', 'MODULE_FREEGIFT_MINIMUM_SPEND', '" . $tester . "', 'Minimum Spend to Qualify For The Free Gift', '6', '2', now())");
	  push_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values
	('Free Gift Category', 'MODULE_FREEGIFT_CATEGORY', '1', 'The category ID that contains all of the potentially free items', '6', '3', 'push_cfg_pull_down_cat_list(', 'push_get_category_name_default_language', now())");
    }
	
    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $this->drop_tables();
    }
  }
?>