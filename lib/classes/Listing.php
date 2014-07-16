<?php
/**
 * Class Listing 
 * returns an ordered List of products
 */
class Listing{

	var	
		$listing_by_category,
		$listing_by_manufacturer,
		$listing_by_price,
		$listing_new,
		$listing_offers,
		$listing_length,
		$listing_order,
		$listing_filter,
		$listing_modifyer,
		$listing,
		$listing_valis,
		$criteria,
		$ids;
	
	function __construct()
	{
		//initial setup
		$this->listing_by_category = false;
		$this->listing_by_manufacturer = false;
		$this->listing_by_price = false;
		$this->listing_new = false;
		$this->listing_offers = false;
		$this->listing_length = false;
		$this->listing_order = false;
		$this->listing_filter = false;
		$this->listing_modifyer = false;
		$this->listing_valid = false;
		$this->ids = '';
		$this->ids_age = time(); 
	}
	
	function query($input)
	{
		/*
		 * Möglichkeiten: 
		 * 1st: uniqueID, die auf einen Schlüssel in db verweist und dort entsprechende Abfragen herholt.
		 * 2nd: mehrere Parameter, die via Aufrufe übergeben werden - was ziemlich umständlich ist - ... Variante 1 rules!
		 */
		 if($txt = push_db_fetch_array(push_db_query("SELECT * FROM listings WHERE listing_key='" . s($input) . "'")))
		 {
			$this->criteria = $txt['criteria'];
			if($txt['ids']<>'')
			{
				$this->ids = $txt['ids']; // alle IDS wenn vorhanden
				$this->ids_age = $txt['ids_age']; // Alter der IDS (timestamp) 
 			}
			$this->listing_valid = true;
		 }
		 else
		 {
			 $this->listing_valid = false;
		 }
		 /*
		 In the end it has to be something like dis:
		 
		 SELECT DISTINCT
		  p.products_id,
		  (SELECT CASE WHEN (SELECT COUNT(status) FROM specials WHERE products_id = p.products_id AND specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND status = 1 GROUP BY products_id) > 0 THEN (SELECT MIN(specials_new_products_price) AS specials_new_products_price FROM specials WHERE products_id = p.products_id AND specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND status = 1 AND customers_id='36767'GROUP BY products_id) ELSE NULL END) as specials_new_products_price, (SELECT CASE WHEN (SELECT COUNT(status) FROM specials WHERE products_id = p.products_id AND specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND status = 1 GROUP BY products_id) > 0 THEN (SELECT MIN(specials_new_products_price) AS specials_new_products_price FROM specials WHERE products_id = p.products_id AND specials_date_added <= NOW() AND (expires_date > NOW() OR (expires_date = '0000-00-00 00:00:00' OR expires_date <=> NULL)) AND status = 1 AND customers_id='36767'GROUP BY products_id) ELSE p.products_price END) as final_price from ((products_description pd, products p ) left join manufacturers m on p.manufacturers_id = m.manufacturers_id ), products_to_categories p2c where p.products_model NOT LIKE 'ccb_%' AND p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '2' and (p2c.categories_id = '1010202' or p2c.categories_id = '1010201' or p2c.categories_id = '10102') order by pd.products_name
		 */
		 
	}
	
	function set_listing_type($listing)
	{
		//testing...
		//($category_depth == 'products' || isset($_GET['manufacturers_id']) || isset($_GET['specials'])|| isset($_GET['categories_id']) || isset($_GET['newproducts'])
		switch($listing)
		{
			case 'm':
				$this->listing_by_manufacturer=true;
			break;
			case 'p':
				$this->listing_by_price=true;
			break;
			case 'n':
				$this->listing_new=true;
			break;
			case 'o':
				$this->listing_offers=true;
			break;
			case 'c':
			default:	
				$this->listing_by_category=true;
			break;
		}
	}


	function set_category(){}	

	function set_listing_order(){}	

	function set_manufacturer(){}

	function search_listing($keyword){}

	function add_filter(){}	

	function get_listing(){}	

	function process_listing(){}	

	//AJAX METHODS
	
	function get_query($type,$typeID,$order,$filter)
	{
		//write criteria to db and return unique querystring
		//Database Table ? Konstruieren!
		
		
	}
	
	
	
}
?>