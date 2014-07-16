<?php
/*
  ösCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 BCC Global, Inc.
   by Peter Brunnengraeber
   v1.10 - 09.Okt.2008
   v1.20 - 21.Dez.2008 (Category exclusion function added)

  Based on 'ot_total.php' module v1.7 2003/02/13 00:12:04 hpdl
   Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_fx_percent_discount {
    var $title, $output;

    function ot_fx_percent_discount() {
      $this->code = 'ot_fx_percent_discount';
      $this->title = MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_TITLE;
      $this->description = MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_SORT_ORDER;
      $this->output = array();
    }

    function process() {
      global $order, $currencies, $cart, $prnDebug;

     //if the module is on, then apply a discount
      if(MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_STATUS == 'true'){
       //Set the discount as nill until incremented later
        $discount_quant = 0;

       //Break out the comma separated exclusion list
        $catExclArry = explode(',' , MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_EXCLUCAT);



       //Verify first we have minimum item count and the proper variables before doing all the hard work.
        if($cart->count_contents() >= MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_ITEMS && strlen(MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_EXCLUCAT) > 0){
          if($prnDebug){ print "<pre>** ORDER INFO **\n"; print_r($order); print "</pre>"; }


          if(MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_EXCLRECURSE == 'true'){

           //Find all the sub categories of these exclusion categories
            $newCats = array(); //set categories holder array
            foreach($catExclArry as $catExcl){
              $subcategories_query = "select c.categories_id, c.parent_id from " . TABLE_CATEGORIES . " c where c.parent_id = '". intval($catExcl) ."' order by sort_order";
              if($prnDebug){ print " ". $subcategories_query ." <br>\n"; }
              $subcategories_query = push_db_query($subcategories_query);
              while ($subcategories = push_db_fetch_array($subcategories_query)){
               //Add to the array the subcategories
                $newCats[] = $subcategories['categories_id'];
              } // close note all subcatagories
            } // close loop of exclusion categories

           //Find the categories that are sub-sub categories
            $n = 0; //Set the item counter
            while( $n < count($newCats)){
              $subcategories_query = "select c.categories_id, c.parent_id from " . TABLE_CATEGORIES . " c where c.parent_id = '". intval($newCats[$n]) ."' order by sort_order";
              if($prnDebug){ print " ". $subcategories_query ." <br>\n"; }
              $subcategories_query = push_db_query($subcategories_query);
              while ($subcategories = push_db_fetch_array($subcategories_query)){
              if($prnDebug){ print "* adding subcategory ". $subcategories['categories_id'] ."; * <br>\n"; }
               //Add to the array the subcategories
                $newCats[] = $subcategories['categories_id'];
              } // close note all subcatagories
             //increment item count to prevent revisiting
              $n++;
            }

           //Merge the new categores into the exclusion list
            $catExclArry = array_merge($catExclArry, $newCats);

          } // close exclude categories recursive option if
          if($prnDebug){ print "**  Excluded Categories: ". implode(', ', $catExclArry) ." ** <br>\n"; }


          if(MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_TAXRECALC == 'true'){
          } // close tax recalculation if



         //Process each item to calculate the new subtotal.
          foreach($order->products as $itemArry){
           //Set item price with base cost
            $itemPrice = $itemArry['final_price'];

           //Update total by qty.
            $itemPrice = $itemPrice * $itemArry['qty'];

           //Find the items category id.
            $prodCatID = push_get_product_path(push_get_prid($itemArry['id']));
            $prodCatID = explode('_', $prodCatID);
            $prodCatID = intval($prodCatID[(count($prodCatID) - 1)]);

           //See if the category is to be excluded from discount
            $doDiscount = true;

            foreach($catExclArry as $catExcl){
              if($prodCatID == intval($catExcl)){
                $doDiscount = false;
                if($prnDebug){ print "**  Exclude Item ". $itemArry['model'] ." (". $itemArry['id'] .") ** <br>\n"; }
                break;
              } //Close found match of exclude category
            } //Close loop of exclusion categories

           //Apply the discount to the price and add it to the total discount if true
            if($doDiscount){
              if($prnDebug){ print '* Discount '. $itemPrice .' using: '. MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_PERCENT ."% * <br>\n"; }
              $discount_quant += ($itemPrice * (MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_PERCENT / 100));
            } //Close apply discount if
          } //Close items foreach loop

         //Discount the total the
          $order->info['total'] -= round($discount_quant, 2);

         //Output the discount display for user
          $this->output[] = array('title' => $this->title . ':',
                                  'text' => $currencies->format($discount_quant, true, $order->info['currency'], $order->info['currency_value']),
                                  'value' => $discount_quant);

        } //Close verify minimum products quantity and excusion variables if
      } //Close module enabled if
    } //Close module process function


    function check() {
      if (!isset($this->_check)) {
        $check_query = push_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_STATUS'");
        $this->_check = push_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array(
            'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_STATUS',
            'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_SORT_ORDER',
            'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_ITEMS',
            'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_PERCENT',
            'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_EXCLUCAT',
            'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_EXCLRECURSE'
                    );
//            'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_TAXRECALC',

   }

    function install() {
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values
      ('Discount Enabled', 'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_STATUS', 'true', 'Do you want allow the discount per quantity?', '6', '1','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values
      ('Sort Order', 'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_SORT_ORDER', '5', 'Sort order of display.', '6', '2', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values
      ('Quantity Required', 'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_ITEMS', '1', 'The minimal quantity of products before the discount is applied.', '6', '3', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values
      ('Discount Percentage', 'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_PERCENT', '25', 'If the discounts are available, this indicates the percentage to apply to the total amount.', '6', '4', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values 
      ('Recalculate Tax', 'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_TAXRECALC', 'true', 'Recalculate tax charge on discounted amount?', '6', '5','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values
      ('Category Exclusions', 'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_EXCLUCAT', '0', 'Enter (Comma Separated) all categories to exclude discount.', '6', '6', now())");
      push_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values
      ('Exclude Subcategoies', 'MODULE_ORDER_TOTAL_DISCOUNT_PERCENT_EXCLRECURSE', 'false', 'Exclude the subcatagories of those excluded above?', '6', '7','push_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      push_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
