<?php
/*
  $Idä: wishlist.php,v 3.0  2005/08/24 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

  class wishlist {
	var $wishID;


	function wishlist() {
		$this->reset();
		
	}

	function restore_wishlist() {
		if (!push_session_is_registered('customer_id')) return false;

	// merge current wishlist items in database
		if (is_array($this->wishID)) {
        	reset($this->wishID);

			while (list($_SESSION['wishlist_id'], ) = each($this->wishID)) {
				$wishlist_query = push_db_query("select products_id from " . TABLE_WISHLIST . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $_SESSION['wishlist_id'] . "'");
				if (!push_db_num_rows($wishlist_query)) {
		   			push_db_query("insert into " . TABLE_WISHLIST . " (customers_id, products_id) values ('" . $_SESSION['customer_id'] . "', '" . $_SESSION['wishlist_id'] . "')");
					if (isset($this->wishID[$_SESSION['wishlist_id']]['attributes'])) {
	              		reset($this->wishID[$_SESSION['wishlist_id']]['attributes']);
						while (list($option, $value) = each($this->wishID[$_SESSION['wishlist_id']]['attributes'])) {
					    		push_db_query("insert into " . TABLE_WISHLIST_ATTRIBUTES . " (customers_id, products_id, products_options_id , products_options_value_id) values ('" . $_SESSION['customer_id'] . "', '" . $_SESSION['wishlist_id'] . "', '" . $option . "', '" . $value . "' )");
			    		}
					}
				}
			}
		}

		// reset session contents
		unset($this->wishID);

		$wishlist_session = push_db_query("select products_id from " . TABLE_WISHLIST . " where customers_id = '" . $_SESSION['customer_id'] . "'");
		while($wishlist = push_db_fetch_array($wishlist_session)) {
			$this->wishID[$wishlist['products_id']] = array($wishlist['products_id']);
		// attributes
       		$attributes_query = push_db_query("select products_options_id, products_options_value_id from " . TABLE_WISHLIST_ATTRIBUTES . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $wishlist['products_id'] . "'");
       		while ($attributes = push_db_fetch_array($attributes_query)) {
     			$this->wishID[$wishlist['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
       		}
		}
	}

	function add_wishlist($wishlist_id, $attributes_id) {

		if(!$this->in_wishlist($wishlist_id)) {

			$wishlist_id = push_get_uprid($wishlist_id, $attributes_id);
			// Insert into session
			$this->wishID[$wishlist_id] = array($wishlist_id);

			if (push_session_is_registered('customer_id')) {
			// Insert into database
	   			push_db_query("insert into " . TABLE_WISHLIST . " (customers_id, products_id) values ('" . $_SESSION['customer_id'] . "', '" . $wishlist_id . "')");
			}

	   		// Read array of options and values for attributes in id[]
			if (is_array($attributes_id)) {
				reset($attributes_id);
				while (list($option, $value) = each($attributes_id)) {
			    	$this->wishID[$wishlist_id]['attributes'][$option] = $value;
		   			// Add to customers_wishlist_attributes table
					if (push_session_is_registered('customer_id')) {
			    		push_db_query("insert into " . TABLE_WISHLIST_ATTRIBUTES . " (customers_id, products_id, products_options_id , products_options_value_id) values ('" . $_SESSION['customer_id'] . "', '" . $wishlist_id . "', '" . $option . "', '" . $value . "' )");
					}
	    		}
				push_session_unregister('attributes_id');
		  	}
		}
	}

	function remove($wishlist_id) {

		// Remove from session
		unset($this->wishID[$wishlist_id]);

		//remove from database
		if (push_session_is_registered('customer_id')) {
			push_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $wishlist_id . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
			push_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where products_id = '" . $wishlist_id . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
		}
	}


	function clear() {

		// Remove all from database
  		if (push_session_is_registered('customer_id')) {
 	  		$wishlist_products_query = push_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_SESSION['customer_id'] . "'");
	  		while($wishlist_products = push_db_fetch_array($wishlist_products_query)) {
				push_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $wishlist_products[products_id] . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
				push_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where products_id = '" . $wishlist_products[products_id] . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
	  		}
		}
	}

	function reset($reset_database = false) {


		// Remove all from database
		if (push_session_is_registered('customer_id') && ($reset_database == true)) {
        	push_db_query("delete from " . TABLE_WISHLIST . " where customers_id = '" . $_SESSION['customer_id'] . "'");
        	push_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where customers_id = '" . $_SESSION['customer_id'] . "'");
      	}

		// reset session contents
		unset($this->wishID);		
    }

	function in_wishlist($wishlist_id) {


		if (isset($this->wishID[$wishlist_id])) {
        	return true;
      	} else {
        	return false;
      	}
	}


	function get_att($wishlist_id) {
    	$pieces = explode('{', $wishlist_id);
	    return $pieces[0];
	}

    function count_wishlist() {  // get total number of items in wishlist 
      $total_items = 0;
      if (is_array($this->wishID)) {
        reset($this->wishID);
        while (list($wishlist_id, ) = each($this->wishID)) {
			if ($wishlist_id != "") {	// there are sometimes empty products IDs in db, don't know why...
          		$total_items++;
			}
        }
      }

      return $total_items;
    }

  }

?>
