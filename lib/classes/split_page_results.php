<?php
/*
  $Idä: split_page_results.php,v 1.15 2003/06/09 22:35:34 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class splitPageResults {
    var $sql_query, $number_of_rows, $current_page_number, $number_of_pages, $number_of_rows_per_page, $page_name;

/* class constructor */
    function splitPageResults($query, $max_rows, $count_key = '*', $page_holder = 'page',  $link = 'db_link') {
      global $_GET, $_POST, $$link, $sortiment_listing, $wishList;

      $this->sql_query = $query;
      $this->page_name = $page_holder;

      if (isset($_GET[$page_holder])) {
        $page = $_GET[$page_holder];
      } elseif (isset($_POST[$page_holder])) {
        $page = $_POST[$page_holder];
      } else {
        $page = '';
      }

      if (empty($page) || !is_numeric($page)) $page = 1;
      $this->current_page_number = $page;

      $this->number_of_rows_per_page = $max_rows;

      $pos_to = strlen($this->sql_query);
      $pos_from = strpos($this->sql_query, ' from', 0);

      $pos_group_by = strpos($this->sql_query, ' group by', $pos_from);
      if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

      $pos_having = strpos($this->sql_query, ' having', $pos_from);
      if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

      $pos_order_by = strpos($this->sql_query, ' order by', $pos_from);
      if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

      if (strpos($this->sql_query, 'distinct') || strpos($this->sql_query, 'group by')) {
        $count_string = 'distinct ' . push_db_input($count_key);
      } else {
        $count_string = push_db_input($count_key);
      }

		if (isset($sortiment_listing) && $sortiment_listing) {			// this class can't deal with the subquery when sorting sortiment by häufigkeit
			$this->number_of_rows = $wishList->count_wishlist();
		} else {
			//try to catch unknown table p
			/* */
			$count_query = "SELECT COUNT(" . $count_string . ") AS total " . substr($this->sql_query, $pos_from, ($pos_to - $pos_from));
			if(stripos($count_query,' from') == false)
			{
				$this->number_of_rows = 0;				
			}
			else
			{
			/**/
			
				$count_query = push_db_query($count_query,$link);
				$count = push_db_fetch_array($count_query);
				$this->number_of_rows = $count['total'];
			}
		}
	  
      $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

      if ($this->current_page_number > $this->number_of_pages) {
        $this->current_page_number = $this->number_of_pages;
      }

      $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

     // $this->sql_query .= " limit " . $offset . ", " . $this->number_of_rows_per_page;
     $this->sql_query .= " limit " . abs(max($offset, 0)) . ", " . $this->number_of_rows_per_page;
    }

/* class functions */

// display split-page-number-links
	function display_links($max_page_links, $parameters = '') {
		global $PHP_SELF, $request_type;
		
		$display_links_string = '';
		
		$class = 'class="pageResults"';
		
		if (push_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

// previous button - not displayed on first page
		if ($this->current_page_number > 1){
			$display_links_string .= '<a href="' . push_href_link(basename($_SERVER['SCRIPT_NAME']), $parameters . (($this->current_page_number > 2)?$this->page_name . '=' . ($this->current_page_number - 1):''), $request_type) . '" id="pageselector-prev" class="BKR btn_seitennavi_zurueck" title="' . PREVNEXT_TITLE_PREVIOUS_PAGE . '"></a>';
			}

// check if number_of_pages > $max_page_links
		$cur_window_num = intval($this->current_page_number / $max_page_links);
		if ($this->current_page_number % $max_page_links) $cur_window_num++;
		
		$max_window_num = intval($this->number_of_pages / $max_page_links);
		if ($this->number_of_pages % $max_page_links) $max_window_num++; 

// previous window of pages
//      if ($cur_window_num > 1) $display_links_string .= '<a href="' . push_href_link(basename($_SERVER['SCRIPT_NAME']), $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" class="pageResults" title="' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . '">...</a>';

// page nn button
	/**/
		if( ($this->current_page_number > $max_page_links)&&($cur_window_num * $max_page_links > $this->number_of_pages)){
			$jump_to_pg=$this->number_of_pages - $max_page_links + 1;
		}else{
			$jump_to_pg = 1 + (($cur_window_num - 1) * $max_page_links);
		}
	 /**/
		//for ($jump_to_page = $jump_to_pg; ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
//			if ($jump_to_page == $this->current_page_number) {
//			  $display_links_string .= '<span class="BKR btn_seitennavi_blanko_1">' . $jump_to_page . '</span>';
//			} else {
//			  $display_links_string .= '<a href="' . push_href_link(basename($_SERVER['SCRIPT_NAME']), $parameters . (($jump_to_page>1)?$this->page_name . '=' . $jump_to_page:''), $request_type) . '" class="BKR btn_seitennavi_blanko" title="' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . '">' . $jump_to_page . '</a>';
//			}
//		}

// next window of pages
  //    if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . push_href_link(basename($_SERVER['SCRIPT_NAME']), $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" class="pageResults" title="' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . '">...</a>&nbsp;';

// next button
		if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '<a href="' . push_href_link(basename($_SERVER['SCRIPT_NAME']), $parameters . 'page=' . ($this->current_page_number + 1), $request_type) . '" id="pageselector-next" class="BKR btn_seitennavi_vor" title="' . PREVNEXT_TITLE_NEXT_PAGE . '"></a>';

      return $display_links_string;
    }

 /**
  * display_pages
  * return a String like  "page x of y"
  */
	function display_pages($pages_string){
		$currentpage =	$this->current_page_number;
		$pagescount = $this->number_of_pages;
		return sprintf($pages_string, $currentpage, $pagescount);
	}
	
// display number of total products found 
    function display_count($text_output) {
      return sprintf($text_output, $this->number_of_rows);
    }
  }
?>