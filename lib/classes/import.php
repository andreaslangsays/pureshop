<?php /**
 * import class by KRös /AL
 */

class import{

	function import($directory='../shop/admin/imports'){
		$this->d = dir($directory);
		$this->dname=$directory;
	}
	
	function import_csv_files($part='A'){
		//read 1st file in stack
		$file_array = array();
		while($a = $this->d->read())
		{
			if( substr($a,-4) =='.csv')
			{
				$file_array[] = $a;

			}
		}
		if($part == 'A')
		{
			echo "PART A \n";
			//take files in certain line file after file
			if(in_array('categories.csv', $file_array))
			{
				echo "Kategorien: ";
				$this->csv = fopen($this->dname . "/" . 'categories.csv', 'r');
				$this->import_categories('csv');
				fclose($this->csv);
				echo "\n";
			}
			if(in_array('categories_description.csv', $file_array))
			{
				echo "Kategorie Beschreibungen: ";
				$this->csv = fopen($this->dname . "/" . 'categories_description.csv', 'r');
				$this->import_categories_description('csv');
				fclose($this->csv);
				echo "\n";
			}
			if(in_array('products.csv', $file_array))
			{ 
				echo "Produkte: ";
				$this->csv = fopen($this->dname . "/" . 'products.csv', 'r');
				$this->import_products('csv');
				fclose($this->csv);
				echo "\n";
			}
				if(in_array('products_categories.csv', $file_array))
			{ 
				echo "Verknüpfungen Produkt-Kategorie: ";
				$this->csv = fopen($this->dname . "/" . 'products_categories.csv', 'r');
				$this->import_products_to_categories('csv');
				fclose($this->csv);
				echo "\n";
			}
			if(in_array('products_description.csv', $file_array))
			{ 
				echo "Produktbeschreibungen / incl Name: ";
				$this->csv = fopen($this->dname . "/" . 'products_description.csv', 'r');
				$this->import_products_description('csv');
				fclose($this->csv);
				echo "\n";
			}			
			if(in_array('ve_prices.csv', $file_array))
			{ 
				echo "VE Preise: ";
				$this->csv = fopen($this->dname . "/" . 've_prices.csv', 'r');
				$this->import_ve_prices('csv');
				fclose($this->csv);
				echo "\n";
			}
			if(in_array('customers.csv', $file_array))
			{ 
				echo "Kunden: ";
				$this->csv = fopen($this->dname . "/" . 'customers.csv', 'r');
				$this->import_customers('csv');
				fclose($this->csv);
				echo "\n";	
			}
		}
		elseif($part == 'B')
		{	
			echo "PART B \n";
			if(in_array('adress_book.csv', $file_array))
			{ 
				echo "Adressen: ";
				$this->csv = fopen($this->dname . "/" . 'adress_book.csv', 'r');
				$this->import_address_book('csv');
				fclose($this->csv);
				echo "\n";
			}
		}
		elseif($part == 'TEST')
		{	
			echo "PART TEST \n";
			if(in_array('adress_book.csv', $file_array))
			{ 
				echo "Adressen: ";
				$this->csv = fopen($this->dname . "/" . 'adress_book.csv', 'r');
				$this->import_addressbook('csv');
				fclose($this->csv);
				echo "\n";
			}
		}
		else
		{
			
			echo "PART C \n";
			if(in_array('products_picture.csv', $file_array))
			{
				echo "Produktbilder: ";
				$this->csv = fopen($this->dname . "/" . 'products_picture.csv', 'r');
				$this->import_products_images('csv');
				fclose($this->csv);
				echo "\n";
			}
			if(in_array('manufacturers.csv', $file_array))
			{ 
				echo "Marken: ";
				$this->csv = fopen($this->dname . "/" . 'manufacturers.csv', 'r');
				$this->import_manufacturers('csv');
				fclose($this->csv);
				echo "\n";
			}
			
			if(in_array('manufacturers_picture.csv', $file_array))
			{ 
				echo "Marken-Bilder: ";
				$this->csv = fopen($this->dname . "/" . 'manufacturers_picture.csv', 'r');
				$this->import_manufacturers_images('csv');
				fclose($this->csv);
				echo "\n";
			}
			if(in_array('specials.csv', $file_array))
			{ 
				echo "Angebotspreise und Kundenspezifische Produktpreise: ";
				
				$this->csv = fopen($this->dname . "/" . 'specials.csv', 'r');
				$this->import_specials('csv');
				fclose($this->csv);
				echo "\n";
				
				//rename( $this->dname . "/" . 'specials.csv', $this->dname . "/backup/" . time() .'specials.csv');
			}
		}
	}

	function import_categories($mode = 'csv')
	{
		if($mode=='csv')
		{
/*			$csv = new csv;
			$names = fgets($this->csv);
			$narr= $csv->parse($names);
			$names=($narr[1]);
*/

			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$i=0;
			$ins = '';
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				if($values[0] <>'')
				{
					if($i>0)
					{
						$ins .= ',' . "\n";
					}
		
					$ins .= "( '" . $values[2] . "','" . (($values[0] == 1)? 0: $values[0] ) . "')";
					$i++;
				}
			}
			$q = push_db_query("TRUNCATE TABLE categories");
			$q =push_db_query(utf8_encode("INSERT INTO categories (" . trim($names[2]) . ", " . trim($names[0]) . ") VALUES " . $ins));
			echo $i;
		}
	}

	function import_categories_description($mode = 'csv')
	{
		
		if($mode == 'csv')
		{
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$i=0;
			$ins = '';
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				
				if($values[0] <>'')
				{
					if($i>0)
					{
						$ins .= ',' . "\n";
					}
					$ins .= "( '" . $values[0] . "', '" . $values[1] . "' ,'" . $values[2] . "')";
					$i++;
				}
			}
			$q = push_db_query("TRUNCATE TABLE categories_description;");
			$q =push_db_query($this->replace_specialchars("INSERT INTO categories_description (`" . trim($names[0]) . "`, `" . trim($names[1]) . "`,`" . $names[2] . "`) VALUES " . $ins));
		}
		echo $i;
	}

	function import_products($mode = 'csv')
	{
		if($mode == 'csv')
		{	$i=0;
			$importids = array();
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			while($values = fgetcsv($this->csv,0,';','"'))
			{	$i++;
				$db = array_combine($names,$values);
				if($db['products_model'] <>'')
				{
					$q=push_db_query("SELECT products_id FROM products WHERE products_model = '" . $db['products_model'] . "';");
					if($t=push_db_fetch_array($q))
					{
						$type = "update";
						$import="	UPDATE products SET 
									`products_quantity` = '" . (int)$db['products_quantity'] ."',
									`products_price` = '" . str_replace(',', '.',$db['products_price']) ."',
									`products_last_modified` = NOW(),
									`products_date_expire` = NULL,
									`products_date_available` = " . ( ($db['products_date_available']=='') ? "'0000-00-00 00:00:00'" : "'" . date('Y-m-d 00:00:00',strtotime($db['products_date_available']))."'" ) . ",
									`products_weight` = '" . str_replace(',', '.',$db['products_weight']) ."',
									`products_status` = '" . (($db['product_status']=="True")?1:0) . "',
									`products_carrot` = '0',
									`products_tax_class_id` = '" . $this->get_tax_class($db['products_tax_class_id']) ."',
									`include_in_discount` = '" . (($db['include_in_discount']== '1' || $db['include_in_discount']== '*' || $db['include_in_discount']== 'True' )?1:0) ."',
									`products_price1` = '0.0000',
									`products_price2` = '0.0000',
									`products_price3` = '0.0000',
									`products_price4` = '0.0000',
									`products_price5` = '0.0000',
									`products_price6` = '0.0000',
									`products_price7` = '0.0000',
									`products_price8` = '0.0000',
									`products_price1_qty` = '0',
									`products_price2_qty` = '0',
									`products_price3_qty` = '0',
									`products_price4_qty` = '0',
									`products_price5_qty` = '0',
									`products_price6_qty` = '0',
									`products_price7_qty` = '0',
									`products_price8_qty` = '0',
									`products_qty_blocks` = '1',
									`products_base_price` = '1.000000',
									`products_base_unit` = '1',
									`products_drop_shipment` = '" . (($db['product_drop_shipment']== '1' || $db['product_drop_shipment']== 'True' )?1:0) ."',
									`products_availability_id` = '" . $db['products_availability_id'] ."'
								WHERE 
									`products_model`  = '" . $db['products_model'] . "'";
									$importids[] = "'" . $db['products_model'] . "'";
					}
					else
					{
						$type = "insert";
						$import = "	INSERT INTO products SET ";
						if($db['products_model'] === (int)$db['products_model'])
						{
							$import .= "`products_id` = '" . $db['products_model'] . "', "; 
						}
						$import .= "`products_quantity` = '" . (int)$db['products_quantity'] ."',
									`products_model` = '" . $db['products_model'] . "',
									`products_price` = '" . str_replace(',', '.',$db['products_price']) ."',
									`products_date_added` = NOW(),
									`products_last_modified` = NOW(),
									`products_date_available` = " . ( ($db['products_date_available']=='') ? "'0000-00-00 00:00:00'" : "'" . date('Y-m-d 00:00:00',strtotime($db['products_date_available'])) . "'" ) . ",
									`products_date_expire` = NULL,
									`products_weight` = '" . str_replace(',', '.',$db['products_weight']) ."',
									`products_status` = '" . (($db['product_status']=="True")?1:0) . "',
									`products_carrot` = '0',
									`products_tax_class_id` = '" . $this->get_tax_class($db['products_tax_class_id']) ."',
									`include_in_discount` = '" . (($db['include_in_discount']== '1' || $db['include_in_discount']== '*' || $db['include_in_discount']== 'True' )?1:0) ."',
									`products_ordered`= '0',
									`products_price1` = '0.0000',
									`products_price2` = '0.0000',
									`products_price3` = '0.0000',
									`products_price4` = '0.0000',
									`products_price5` = '0.0000',
									`products_price6` = '0.0000',
									`products_price7` = '0.0000',
									`products_price8` = '0.0000',
									`products_price1_qty` = '0',
									`products_price2_qty` = '0',
									`products_price3_qty` = '0',
									`products_price4_qty` = '0',
									`products_price5_qty` = '0',
									`products_price6_qty` = '0',
									`products_price7_qty` = '0',
									`products_price8_qty` = '0',
									`products_qty_blocks` = '1',
									`products_base_price` = '1.000000',
									`products_base_unit` = '1',
									`products_drop_shipment` = '" . (($db['product_drop_shipment']== '1' || $db['product_drop_shipment']== 'True' )?1:0) ."',
									`products_availability_id` = '" . $db['products_availability_id'] . "' ";
									$importids[] = "'" . $db['products_model'] . "'";

					}
					push_db_query($import);
				}
			}
		}
		if(count($importids > 0))
		{
			push_db_query("UPDATE products SET products_status=0 WHERE products_model NOT IN ( " . implode(',',$importids ). " );");
		}
		echo $i;
		return true;
	}

	function import_products_description($mode = 'csv')
	{
		if($mode == 'csv')
		{
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$i=0;
			$ins = array();
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				if($values[0] <>'')
				{
					/*
					if($i>0)
					{
						$ins .= ',' . "\n";
					}*/
					$ins[]= "( '" .  $values[0] . "', '" . mysql_escape_string($values[1]) . "', '" . mysql_escape_string(str_replace("'", "´",$values[2])) . "',(SELECT products_id FROM products WHERE products_model='" . $values[3] . "') , '" . mysql_escape_string($values[4]) . "','" . mysql_escape_string($values[5]) . "','" . mysql_escape_string($values[6]) . "','" . mysql_escape_string($values[7]) . "' )";
					$i++;
				}
			}
			$q = push_db_query("TRUNCATE TABLE products_description;");
			foreach($ins as $sert){
				$q =mysql_query($this->replace_specialchars("INSERT INTO products_description (language_id, products_name, products_description, products_id, products_short_description, products_package_info, products_usage_info, products_pdf) VALUES " . $sert));
				}
		}
		echo $i;
	}

	function import_products_to_categories($mode = 'csv')
	{
		if($mode == 'csv')
		{
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$i=0;
			$ins='';
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				if($values[0] <>'')
				{
					if($i>0)
					{
						$ins .= ',' . "\n";
					}
					$ins .= "( (SELECT products_id FROM products WHERE products_model='" . $values[0] . "') , '" . $values[1] . "' )";
					$i++;
				}
			}
			$q = push_db_query("TRUNCATE TABLE products_to_categories;");
			
			$q =push_db_query("INSERT INTO products_to_categories (`products_id`, `categories_id`) VALUES " . $ins);
		}
		echo $i;
	}

	function import_products_images($mode='csv')
	{
		if($mode == 'csv')
			{
				$names = fgetcsv($this->csv,0,';','"');
				if($names[0]==NULL){
					echo "ERROR NO ENTRIES";
					return;
				}
				$imarr=array();
				$i=0;
				while($values = fgetcsv($this->csv,0,';','"'))
				{
					if($values[0] <>'' && $values[1] == '0')
					{
						//neues csv - Format
						//$ins = "UPDATE products SET products_image = 'AR" . $values[0] . "_" . $values[1] . "_" . $values[2] . ".jpg' WHERE products_model='" . $values[0] . "';";
						$ins = "UPDATE products SET products_image = '" . $values[2] . "' WHERE products_model='" . $values[0] . "';";
						
						$q =push_db_query($ins);
						$i++;
					}
					elseif($values[1]>0)
					{
						$imarr[$values[0]][(int)$values[1]-1] = $values[2]; 
					}
				}
//				$q = push_db_query("TRUNCATE TABLE products_to_categories;");
				
				$q =push_db_query($ins);
				echo $i;
			}
			if(count($imarr)>1)
			{
				foreach($imarr as $kay => $veluh)
				{
					$stringh = implode('|',$veluh);
					push_db_query("UPDATE products SET products_image_array = '" . $stringh . "' WHERE products_model='" . $kay . "';");	
					$setmodels[] = $kay;
				}
			}
			if(isset($setmodels) && count($setmodels)>0)
			{
				push_db_query("UPDATE products SET products_image_array = '' WHERE products_model NOT IN (" . implode(',',$setmodels) . ");");	
			}
			
	}

	function import_ve_prices($mode='csv')
	{
		if($mode == 'csv')
		{
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$i=0;
			//1st: get id of ve-option:
			
			$q=push_db_fetch_array(push_db_query("SELECT products_options_id from products_options WHERE products_options_name='VE'"));
			$ve_id= $q['products_options_id'];
			
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				if($values[0] <>'')
				{	
					$i++;
					$db = array_combine($names,$values);
					$db['ve_count']= intval($db['ve_count']);
					$q=push_db_query("SELECT pov.products_options_values_id FROM products_options_values pov JOIN products_options_values_to_products_options pov2po ON (pov.products_options_values_id = pov2po.products_options_values_id ) WHERE pov2po.products_options_id = '" . $ve_id . "' AND pov.products_options_values_name = '" . (int)$db['ve_count'] . "';");
					if(!$r = push_db_fetch_array($q))
					{
						push_db_query("INSERT INTO products_options_values SET language_id = '2',	products_options_values_name = '" . (int)$db['ve_count'] . "'");
						$ve_name_id = push_db_insert_id();
						push_db_query("INSERT INTO products_options_values_to_products_options SET products_options_id = '" . $ve_id . "', products_options_values_id = '" . $ve_name_id . "';");
					}
					else
					{
						$ve_name_id= $r['products_options_values_id'];
					}
					$test= push_db_fetch_array(push_db_query("SELECT products_attributes_id FROM products_attributes WHERE options_id='" . $ve_id . "' AND options_values_id='" . $ve_name_id . "' AND products_id IN (SELECT products_id FROM products WHERE products_model='" . $db['product_id'] . "') AND customers_id=0"));
					if($test)
					{							
							$ins = "UPDATE products_attributes SET options_values_price = '" . str_replace(',', '.', $db['ve_difference']) . "',	price_prefix = '-' WHERE products_attributes_id = '" . $test['products_attributes_id'] . "' AND customers_id=0;";
					}
					else
					{
						$ins = "INSERT INTO products_attributes SET products_id =(SELECT products_id FROM products WHERE products_model='" . $db['product_id'] . "'), options_id='" . $ve_id . "', options_values_id='" . $ve_name_id . "', options_values_price = '" . str_replace(',', '.', $db['ve_difference']) . "',	price_prefix = '-' , customers_id = '0'";
					}
					$dev0 =push_db_query($ins);
				}
			}
			$q =push_db_query($ins);
			echo $i;
		}
	}
	
	function import_manufacturers_info($mode = 'csv')
	{
		if($mode == 'csv')
		{
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$i=0;
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				if($values[0] <>'')
				{
					if($i>0)
					{
						$ins .= ',' . "\n";
					}
					$ins .= "( '" . $values[0] . "', '" . mysql_escape_string($values[1]) . "', '" . mysql_escape_string($values[2]) . "',(SELECT products_id FROM products WHERE products_model='" . $values[3] . "') , '" . mysql_escape_string($values[4]) . "','" . mysql_escape_string($values[5]) . "','" . mysql_escape_string($values[6]) . "','" . mysql_escape_string($values[7]) . "' )";
					$i++;
				}
			}
			$q = push_db_query("TRUNCATE TABLE products_description;");
			$q =push_db_query($this->replace_specialchars("INSERT INTO products_description (language_id, products_name, products_description, products_id, products_short_description, products_package_info, products_usage_info, products_pdf) VALUES " . $ins));
			echo $i;
		}
	}
	
	function import_manufacturers($mode = 'csv')
	{
		if($mode == 'csv')
		{
			$i=0;
			$manarr = array();
//			pruducts_id,	manufacturers_id,	manufaturers_name,	manufaturers_image,	manufaturers_header_image,	manufaturers_videos,	manufacturers_marke_onlin
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				if($values[0] <>'' && $values[1] <>'')
				{
					$i++;
					//1st get manufacturers ID (or generate it!!)
					$q= push_db_query("SELECT manufacturers_id FROM manufacturers WHERE manufacturers_id= '" . $values[1] . "';");
					if($r=push_db_fetch_array($q))
					{
						$manarr[] = $values[1];
						push_db_query($this->replace_specialchars("UPDATE manufacturers SET manufacturers_name ='" . $values[2] . "' WHERE manufacturers_id ='" . $values[1] . "';"));
					}
					else
					{
						push_db_query($this->replace_specialchars("INSERT INTO manufacturers SET manufacturers_id ='" . (intval($values[1])) . "', manufacturers_name ='" . $values[2] . "';"));
					}
					
					$ins = "UPDATE products SET manufacturers_id ='" . $values[1] . "' WHERE products_model = '" . $values[0] . "';";
					$q = push_db_query($ins);
				}
			}
			if(sizeof($manarr) > 0 )
			{
				$manarr = array_unique($manarr);
				push_db_query("UPDATE manufacturers SET manufacturers_active = 0 WHERE manufacturers_id NOT IN (" . (implode(',',$manarr)) . ")");	
			}
			echo $i;
		}
	}

	function import_manufacturers_images($mode='csv')
	{
		if($mode == 'csv')
			{
				$names = fgetcsv($this->csv,0,';','"');
				if($names[0]==NULL){
					echo "ERROR NO ENTRIES";
					return;
				}
				$i=0;
				while($values = fgetcsv($this->csv,0,';','"'))
				{
					if($values[0] <>'')
					{
						//neues csv - Format
						//$ins = "UPDATE products SET products_image = 'AR" . $values[0] . "_" . $values[1] . "_" . $values[2] . ".jpg' WHERE products_model='" . $values[0] . "';";
						$ins = "UPDATE manufacturers SET manufacturers_image = '" . $values[2] . "' WHERE manufacturers_id='" . $values[0] . "';";
						$i++;
						$q =push_db_query($ins);
					}
				}
//				$q = push_db_query("TRUNCATE TABLE products_to_categories;");
				
				$q =push_db_query($ins);
				echo $i;
			}
	}
//
	function import_customers($mode='csv')
	{
		if($mode == 'csv')
		{
			$j=0;
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$carr=array();
			$text='';
			$endqueries = '';
			//disable all customers
			push_db_query("UPDATE customers SET customer_checkout_enabled = 0;");
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				$db = array_combine($names,$values);
				if($db['customers_email_address'] <>'' && is_numeric($db['selectline_customers_id']))
				{
					
					$j++;
					$current_emailaddress=$db['customers_email_address'];
					$current_sl_id=$db['selectline_customers_id'];
					$current_id = $db['customers_id'];
					$test_in_db = push_db_query("SELECT customers_id, selectline_customers_id FROM customers WHERE customers_email_address= '" .mysql_escape_string($db['customers_email_address']) . "' OR customers_id='" . $current_id . "' ;");
					if($t1_ergebnis=push_db_fetch_array($test_in_db)){
					//means that there is an entry with current emailaddress
						if($t1_ergebnis['selectline_customers_id'] <= $current_sl_id  )
						{
							//update current entry and set sl_id to current sl_id
							push_db_query($this->replace_specialchars("UPDATE customers SET 
																		customers_gender ='" . mysql_escape_string($db['customers_gender']) . "',
																		customers_firstname = '" . mysql_escape_string($db['customers_firstname']) . "',
																		customers_lastname = '" . mysql_escape_string($db['customers_lastname']) . "',
																		customers_email_address = '" . mysql_escape_string($db['customers_email_address']) . "',
																		customers_telephone = '" . mysql_escape_string($db['customers_telephone']) . "',
																		customers_default_address_id= '" . $db['customer_adress_id'] ."',
																		customers_fax = '" . mysql_escape_string($db['customers_fax']) . "',
																		customer_discount_table = '" .  intval($db['customer_discount_table']) . "',
																		customer_discount_combines_all = '" . $db['customer_discount_combine'] . "',
																		customer_only_ve = '" . $db['customer_only_ve'] . "',
																		customer_image_usage = '" . (($db['customer_image_usage']=='True')? 1:0) . "',
																		customer_checkout_enabled = '" . (($db['customer_checkout_enabled']=='True')? 1:0) . "',
																		customer_credit_amount = '" . $db['customer_credit_amount'] . "',
																		customer_paper_bill = '" . $db['customer_paper_bill'] . "',
																		selectline_customers_id = '" . $db['selectline_customers_id'] . "'
																		WHERE customers_id ='" . $t1_ergebnis['customers_id'] . "'"));
							$discount_mode[$db['customer_discount_table']][] =  $t1_ergebnis['customers_id'];
						}
						else
						{
							//skip!
							//echo $current_sl_id . "skip!";
						}
					}
					else
					{
						//current emailaddress is not in database
						//test if current selectline_customers_id is in database (with different emailaddress)
						$test_sid_in_db = push_db_query("SELECT customers_id, selectline_customers_id FROM customers WHERE selectline_customers_id= '" .mysql_escape_string($db['selectline_customers_id']) . "';");
						$test_id_in_db = push_db_query("SELECT customers_id, selectline_customers_id FROM customers WHERE customers_id= '" .mysql_escape_string($db['customers_id']) . "';");
						if($t2_ergebnis= push_db_fetch_array($test_sid_in_db))
						{
							//new mailaddress with same sl_id
							push_db_query($this->replace_specialchars("UPDATE customers SET 
												customers_gender ='" .  mysql_escape_string($db['customers_gender']) . "', 
												customers_firstname = '" .  mysql_escape_string($db['customers_firstname']) . "',	
												customers_lastname = '" .  mysql_escape_string($db['customers_lastname']) . "',
												customers_email_address = '" .  mysql_escape_string($db['customers_email_address']) . "',	
												customers_telephone = '" . $db['customers_telephone'] . "',
												customers_default_address_id= '" . $db['customer_adress_id'] ."',
												customers_fax = '" . $db['customers_fax'] . "',	
												customer_discount_table = '" .  intval($db['customer_discount_table']) . "',
												customer_discount_combines_all = '" . $db['customer_discount_combine'] . "',	
												customer_only_ve = '" . $db['customer_only_ve'] . "',
												customer_image_usage = '" . (($db['customer_image_usage']=='True')? 1:0) . "',	
												customer_checkout_enabled = '" .(($db['customer_checkout_enabled']=='True')? 1:0) . "',
												customer_credit_amount = '" . $db['customer_credit_amount'] . "',	
												customer_paper_bill = '" . $db['customer_paper_bill'] . "'
												WHERE selectline_customers_id ='" .$t2_ergebnis['selectline_customers_id'] . "'"));
							$discount_mode[$db['customer_discount_table']][] =  $db['customers_id'];
						}
						elseif($t2_ergebnis= push_db_fetch_array($test_id_in_db))
						{
							//new mailaddress with same sl_id
							push_db_query($this->replace_specialchars("UPDATE customers SET 
												customers_gender ='" .  mysql_escape_string($db['customers_gender']) . "', 
												customers_firstname = '" .  mysql_escape_string($db['customers_firstname']) . "',	
												customers_lastname = '" .  mysql_escape_string($db['customers_lastname']) . "',
												customers_email_address = '" .  mysql_escape_string($db['customers_email_address']) . "',	
												customers_telephone = '" . $db['customers_telephone'] . "',
												customers_default_address_id= '" . $db['customer_adress_id'] ."',
												customers_fax = '" . $db['customers_fax'] . "',	
												customer_discount_table = '" .  intval($db['customer_discount_table']) . "',
												customer_discount_combines_all = '" . $db['customer_discount_combine'] . "',	
												customer_only_ve = '" . $db['customer_only_ve'] . "',
												customer_image_usage = '" . (($db['customer_image_usage']=='True')? 1:0) . "',	
												customer_checkout_enabled = '" .(($db['customer_checkout_enabled']=='True')? 1:0) . "',
												customer_credit_amount = '" . $db['customer_credit_amount'] . "',	
												customer_paper_bill = '" . $db['customer_paper_bill'] . "'
												selectline_customers_id ='" .$db['selectline_customers_id'] . "'
												WHERE customers_id ='" .$t2_ergebnis['customers_id'] . "'"));
							$discount_mode[$db['customer_discount_table']][] =  $db['customers_id'];
						}
						else
						{
							//complete new entry
							push_db_query($this->replace_specialchars("INSERT INTO customers SET 
													customers_id ='" .$db['customers_id'] . "',
													customers_gender ='" .  mysql_escape_string($db['customers_gender']) . "', 
													customers_firstname = '" .  mysql_escape_string($db['customers_firstname']) . "',	
													customers_lastname = '" .  mysql_escape_string($db['customers_lastname']) . "',
													customers_email_address = '" .  mysql_escape_string($db['customers_email_address']) . "',	
													customers_telephone = '" . $db['customers_telephone'] . "',
													customers_default_address_id= '" . $db['customer_adress_id'] ."',
													customers_fax = '" . $db['customers_fax'] . "',	
													customer_discount_table = '" .  intval($db['customer_discount_table']) . "',
													customer_discount_combines_all = '" . $db['customer_discount_combine'] . "',	
													customer_only_ve = '" . $db['customer_only_ve'] . "',
													customer_image_usage = '" . (($db['customer_image_usage']=='*')? 1:0) . "',	
													customer_checkout_enabled = '" .$db['customer_checkout_enabled'] . "',
													customer_credit_amount = '" . $db['customer_credit_amount'] . "',	
													customer_paper_bill = '" . $db['customer_paper_bill'] . "',
													selectline_customers_id = '" . $db['selectline_customers_id'] . "'"));
							$discount_mode[$db['customer_discount_table']][] =  push_db_insert_id();
						}
					
					}

			
			
			
// Abfrage ob email existiert
/*					$q = push_db_query($this->replace_specialchars("SELECT customers_id FROM customers WHERE customers_email_address= '" .mysql_escape_string($db['customers_email_address']) . "' /*AND selectline_customers_id= '" . $db['selectline_customers_id'] . "' AND customers_id= '" . $db['customers_id'] . "' ;"));
					if($r=push_db_fetch_array($q)) //DEFAULT CASE EMAIL MATCHES!!
					{

						push_db_query($this->replace_specialchars("UPDATE customers SET 
						customers_gender ='" . mysql_escape_string($db['customers_gender']) . "',
						customers_firstname = '" . mysql_escape_string($db['customers_firstname']) . "',
						customers_lastname = '" . mysql_escape_string($db['customers_lastname']) . "',
						customers_email_address = '" . mysql_escape_string($db['customers_email_address']) . "',
						customers_telephone = '" . mysql_escape_string($db['customers_telephone']) . "',
						customers_default_address_id= '" . $db['customer_adress_id'] ."',
						customers_fax = '" . mysql_escape_string($db['customers_fax']) . "',
						customer_discount_table = '" . intval($db['customer_discount_table']) . "',
						customer_discount_combines_all = '" . $db['customer_discount_combine'] . "',
						customer_only_ve = '" . $db['customer_only_ve'] . "',
						customer_image_usage = '" . (($db['customer_image_usage']=='True')? 1:0) . "',
						customer_checkout_enabled = '" . (($db['customer_checkout_enabled']=='True')? 1:0) . "',
						customer_credit_amount = '" . $db['customer_credit_amount'] . "',
						customer_paper_bill = '" . $db['customer_paper_bill'] . "',
						selectline_customers_id = '" . $db['selectline_customers_id'] . "'
						WHERE customers_id ='" . $r['customers_id'] . "'"));
						$discount_mode[$db['customer_discount_table']][] =  $r['customers_id'];
					}
					else
					{
					//abfrage ob SL-Kunde existiert 
					$q3= push_db_query($this->replace_specialchars("SELECT selectline_customers_id, customers_email_address, customers_password FROM customers WHERE customers_id= '" .$db['customers_id'] . "';"));
						if($r3=push_db_fetch_array($q3))
						{	//SL-ID MATCHES
							echo "[HIT".$r3['selectline_customers_id'] ."]";
							push_db_query($this->replace_specialchars("UPDATE customers SET 
							customers_gender ='" .  mysql_escape_string($db['customers_gender']) . "', 
							customers_firstname = '" .  mysql_escape_string($db['customers_firstname']) . "',	
							customers_lastname = '" .  mysql_escape_string($db['customers_lastname']) . "',
							customers_email_address = '" .  mysql_escape_string($db['customers_email_address']) . "',	
							customers_telephone = '" . $db['customers_telephone'] . "',
							customers_default_address_id= '" . $db['customer_adress_id'] ."',
							customers_fax = '" . $db['customers_fax'] . "',	
							customer_discount_table = '" .  intval($db['customer_discount_table']) . "',
							customer_discount_combines_all = '" . $db['customer_discount_combine'] . "',	
							customer_only_ve = '" . $db['customer_only_ve'] . "',
							customer_image_usage = '" . (($db['customer_image_usage']=='True')? 1:0) . "',	
							customer_checkout_enabled = '" .(($db['customer_checkout_enabled']=='True')? 1:0) . "',
							customer_credit_amount = '" . $db['customer_credit_amount'] . "',	
							customer_paper_bill = '" . $db['customer_paper_bill'] . "'
							WHERE selectline_customers_id ='" .$r3['selectline_customers_id'] . "'"));
							$discount_mode[$db['customer_discount_table']][] =  $db['customers_id'];

							if(mysql_escape_string(strtolower($db['customers_email_address'])) <> strtolower($r3['customers_email_address']))
							{
								$text .= "\nIMPORT MESSAGE BOF \n\n Kunde ". $r3['selectline_customers_id'] . " muss benachrichtigt werden, dass seine login-mailadresse ab jetzt " . mysql_escape_string($db['customers_email_address']) . " lautet. \n " . (($r3['customers_password']<>'')? ' (das Passwort bleibt gleich)': '(am besten neue Passwortmail absenden!!)' )  . " \n die alte Mailadresse lautete :" . $r3['customers_email_address'] . " \n \n EOF IMPORT MESSAGE \n";
							}
						}
						else
						{
							$test = push_db_query("SELECT customers_id FROM customers WHERE customers_email_address= '" .mysql_escape_string($db['customers_email_address']) . "'");
							if(!$t=push_db_fetch_array($test))
							{
								push_db_query($this->replace_specialchars("INSERT INTO customers SET 
								customers_id ='" .$db['customers_id'] . "',
								customers_gender ='" .  mysql_escape_string($db['customers_gender']) . "', 
								customers_firstname = '" .  mysql_escape_string($db['customers_firstname']) . "',	
								customers_lastname = '" .  mysql_escape_string($db['customers_lastname']) . "',
								customers_email_address = '" .  mysql_escape_string($db['customers_email_address']) . "',	
								customers_telephone = '" . $db['customers_telephone'] . "',
								customers_default_address_id= '" . $db['customer_adress_id'] ."',
								customers_fax = '" . $db['customers_fax'] . "',	
								customer_discount_table = '" . intval($db['customer_discount_table']) . "',
								customer_discount_combines_all = '" . $db['customer_discount_combine'] . "',	
								customer_only_ve = '" . $db['customer_only_ve'] . "',
								customer_image_usage = '" . (($db['customer_image_usage']=='*')? 1:0) . "',	
								customer_checkout_enabled = '" .$db['customer_checkout_enabled'] . "',
								customer_credit_amount = '" . $db['customer_credit_amount'] . "',	
								customer_paper_bill = '" . $db['customer_paper_bill'] . "',
								selectline_customers_id = '" . $db['selectline_customers_id'] . "'"));
								$discount_mode[$db['customer_discount_table']][] =  push_db_insert_id();
							}
						}
						
					}*/

				}
			}
			echo $j;
			if($text<>'')
			{
				//mail('kontakt@if-bi.com', 'Kundenadresse(n) Geaendert!!', $text);
			}

			foreach($discount_mode as $prozz => $idarr)
			{
				if($prozz > 0)
				{
					//try to identify 
					if($t=push_db_fetch_array(push_db_query("SELECT ID FROM discounts WHERE discount_group = 'private' AND discount_type = '%' AND  discount_value = '" . intval($prozz) . "' ")) )
					{
						$ID=$t['ID']; 	
					}
					else
					{
						$discount_entry = push_db_query("INSERT INTO discounts
														SET 
															discount_name = 'Persönlicher Kundenrabatt',
															discount_group = 'private',
															discount_type = '%',
															discount_base = 'overall_amount',
															discount_value = '" . intval($prozz) . "',
															discount_active = 1;
														");
						$ID = push_db_insert_id();
					}
					if($t=push_db_fetch_array(push_db_query("SELECT ID FROM discounts_conditions WHERE discount_id='" . $ID . "'")))
					{
					
						push_db_query("	UPDATE discounts_conditions 
										SET
											condition_value = '" . implode(',' , $idarr) . "'
										WHERE
											ID = '" . $t['ID'] . "'
										AND discount_id = '" . $ID . "'");
					}
					else
					{
						push_db_query("	INSERT INTO discounts_conditions 
										SET
											discount_id='" . $ID . "',
											condition_name = 'Kunde zugelassen',
											condition_type = 'customer',
											condition_key = 'customers_id',
											condition_operator = 'IN',
											condition_value = '" . implode(',' , $idarr) . "'
											");
					}
				}
			}
			
		}
		//push_db_query("UPDATE customers SET customer_checkout_enabled = 0 WHERE selectline_customers_id NOT IN (" . implode(',',$carr) . ")");

	}

function import_address_book($mode='csv')
	{
		if($mode == 'csv')
		{
			$i=0;
			$j=0;
			$k=0;
			$l=0;
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$insertstr ="INSERT INTO address_book (	address_book_id,
													customers_id, 
													entry_gender, 
													entry_company,
													entry_company_tax_id, 
													entry_firstname,
													entry_lastname, 
													entry_street_address, 
													entry_suburb,
													entry_postcode,
													entry_city,
													entry_state,
													entry_country_id  ) VALUES ";
			
			$valuez = '';
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				$db = array_combine($names,$values);
				if($db['entry_street_address']<>'' &&  $db['entry_postcode']<>'' && $db['entry_city']<>'' )
				{
					$db['entry_country_id'] = $this->get_country_id($db['entry_country_id']);
					if($i>0)
					{
						$valuez .=',' . "\n";
					}
					$i++;
					if($db['adress_type']=='KUL')
					{
						$addid = intval($db['address_book_id'].'22');	
					}
					else
					{
						$addid = intval($db['address_book_id'].'12');
					}
					$nostart=true;
					$valuez .=	" ('" .  mysql_escape_string($addid) . "',";
					$valuez .= " '" .  mysql_escape_string($db['customers_id']) . "',";
					$valuez .= " '" .  mysql_escape_string($db['entry_gender']) . "',";
					$valuez .= " '" .  mysql_escape_string($db['entry_company']) . "',";
					$valuez .= " '" .  mysql_escape_string($db['entry_company_tax_id']) . "',";
					$valuez .= " '" .  mysql_escape_string($db['entry_firstname']) . "',";
					$valuez .= " '" .  mysql_escape_string($db['entry_lastname']) . "',";
					$valuez .= " '" .  mysql_escape_string($db['entry_street_address']) . "',";
					$valuez .= " '" .  $this->unklar( $db['entry_suburb'] )  . "',";
					$valuez .= " '" .  mysql_escape_string($db['entry_postcode']) . "',";
					$valuez .= " '" .  mysql_escape_string($db['entry_city']) . "',";
					$valuez .= " '" .  $this->unklar($db['entry_state']) . "',";
					$valuez .= " '" .  $db['entry_country_id'] . "') ";
					
					if($db['adress_type']=='KUL')
					{
						$l++;
						push_db_query("UPDATE customers SET customers_shipping_address_id  = '" . $addid . "' WHERE selectline_customers_id = '" . $db['customers_id'] . "' AND customers_shipping_address_id IS NULL ");
					}
					elseif($db['adress_type']=='KUK')
					{
						$k++;
						push_db_query("UPDATE customers SET customers_default_address_id = '" . $addid . "' , customers_bill_address_id = '" . $addid . "' WHERE selectline_customers_id = '" . $db['customers_id'] . "' ");
						push_db_query("UPDATE customers SET customers_shipping_address_id  = '" . $addid . "' WHERE selectline_customers_id = '" . $db['customers_id'] . "' AND customers_shipping_address_id IS NULL ");	
					}
				}
				else
				{
					$j++;	
				}
			}
			/**/
			push_db_query("TRUNCATE TABLE address_book");
			push_db_query($insertstr . $this->replace_specialchars($valuez));
			//correct customer_ids:
			$weird=push_db_query("SELECT customers_id, selectline_customers_id FROM customers WHERE customers_id <> selectline_customers_id;");
			while ($thing = push_db_fetch_array($weird))
			{
				push_db_query("UPDATE address_book SET customers_id = '" . $thing['customers_id'] . "' WHERE customers_id ='" . $thing['selectline_customers_id'] . "';");	
			}
			echo $i . " entries written " . $j . " invalid entries (" . $k ." Kontakt) (" .$l . " Liefer)";
		}
	}

	function import_specials($mode = 'csv')
	{
		if($mode == 'csv')
		{
			$names = fgetcsv($this->csv,0,';','"');
			if($names[0]==NULL){
				echo "ERROR NO ENTRIES";
				return;
			}
			$i=0;
			$ins = '';
			$j=0;
			$cins=array();
			$cid = 0;
			$cdate=0;
			$datum= date('Y-m-d H:i:s', strtotime('+ 1 days') );
			while($values = fgetcsv($this->csv,0,';','"'))
			{
				if($values[9] == 0  && $values[2] > 0)
				{
					if($i>0)
					{
						$ins .= ',' . "\n";
					}
					if($cid == $values[1])
					{
						$ins .= "( (SELECT products_id FROM products WHERE products_model='" . $values[1] . "') , '" . str_replace(',','.',$values[2]) . "' ,NOW(),NOW(),". (($values[5] =='TEST' )? $values[5] : "NOW()") .", '" . $cdate . "', 1, '" . $values[10] . "' )";
					}
					else
					{
						$cid = $values[1];
						$cdate = ($values[6] <> '')? date('Y-m-d H:i:s', strtotime( '+1 day ' , strtotime($values[6]))) : $datum ;
						$ins .= "( (SELECT products_id FROM products WHERE products_model='" . $values[1] . "') , '" . str_replace(',','.',$values[2]) . "' ,NOW(),NOW(),". (($values[5] =='TEST' )? $values[5] : "NOW()") .", '" . (($values[6] <> '')? date('Y-m-d H:i:s', strtotime( '+1 day ' , strtotime($values[6]))): $datum  )  . "', 1, '" . $values[10] . "' )";
					}
					$i++;
				}
				elseif( $values[2] > 0)
				{
					if($t=push_db_fetch_array(push_db_query("SELECT customers_id FROM customers WHERE selectline_customers_id='" . $values[9] . "'")))
					{
						$j++;
						if($po=push_db_fetch_array(push_db_query("SELECT products_id FROM products WHERE products_model='" . $values[1] . "'")))
						{
							$proid=$po['products_id'];
						}
						else
						{
							$proid=0;
						}
						$cins[] = " ('" . $proid . "', '" . str_replace(',','.',$values[2]) . "', '" . $values[9] . "' ) ";
					}
				}
				//products_id  products_price customers_id 
			}
			
			$q = push_db_query("TRUNCATE TABLE specials;");
			if($ins<>'')
			{
				$q =push_db_query($this->replace_specialchars("INSERT INTO specials (products_id , 
																			specials_new_products_price	, 
																			specials_date_added	,
																			specials_last_modified	,
																			starts_date	,
																			expires_date,
																			status,
																			customers_id) 
																		 VALUES " . $ins));
			}
			if(count($cins) > 0)
			{
				push_db_query("TRUNCATE TABLE products_customers");
				foreach($cins as $iq)
				{
					$q =push_db_query($this->replace_specialchars("INSERT INTO products_customers (products_id,  products_price, customers_id ) 
																	 VALUES " . $iq));
				}
			}
			echo $i . " Ang. /" .$j ." Kundenpreise";		
		}
	}



	function get_tax_class($value)
	{
		if($value == 'unbekannt' || $value== 7)
		{
			return 2;
		}
		else
		{
			return 3;
		}
	}

	function replace_specialchars($string)
	{
	//	$find=		array('&nbsp;', '& ', 		'&ouml;', 	'&uuml;', 	'&auml;', 	'&Ouml;', 	'&Uuml;', 	'&Auml;', 	'&szlig;'	,' '		,'”'	);
	//	$replace=	array(' ', 		'&amp; ', 	'ö',		'ü', 		'ä', 		'Ö',		'Ü', 		'Ä', 		'ß'			,'&#8217'	,'&#8216');
		$string = iconv("Windows-1252", "UTF-8//IGNORE", $string);//str_replace($find, $replace, $string ); //html_entity_decode($string, ENT_NOQUOTES | ENT_XML1 ,'UTF-8' );
		return $string;
	}


	function get_country_id($cn)
	{
		if($cn<>'')
		{
			if($cn == 'D')
			{
				$cn ="DE";
			}
			$ci=push_db_fetch_array(push_db_query("SELECT countries_id FROM countries WHERE countries_iso_code_2= '" . $cn . "'"));
			if($ci)
			{
				return $ci['countries_id'];
			}
			else
			{
				return 81;
			}
		
		}
		else
		{
			return 81;
		}
	
	}
	
	function unklar($value)
	{
		//entfernt den Wert Unbekannt aus den Daten
		if($value == 'unbekannt')
		{
			return '';
		}
		else
		{
			return $value;
		}
		
	}
}
?>
