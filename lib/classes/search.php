<?php
/***
 *
 * Search - a search engine for oscommerce
 *
 * 2013 by KRös
 *
 */

class oscsearch{
	var $column_list,
		$results,
		$keywords,
		$keyphrase,
		$category,
		$subcategory,
		$restrictions,
		$description,
		$keyquery,
		$dfrom,
		$dto,
		$pfrom,
		$fixed,
		$pto,
		$resultquery,
		$ids,
		$keyparts,
		$lvst_used,
		$closest,
		$shortest,
		$original,
		$indb,
		$model,
		$replacement_set,
		$replacement,
		$direct_match,
		$javascript,
		$sortflag;
/**/
	function oscsearch()
	{
		global $languages_id, $_GET;
		//INIT
		$this->results = '';
		$this->model = "";
		//categories_id=79&inc_subcat=1
		if((isset($_GET["categories_id"])) && ($_GET["categories_id"] <>"all"))
		{
			$this->category = $_GET["categories_id"];
		}
		else
		{
			$this->category = "all";
		}
		if((isset($_GET["inc_subcat"])) && ($_GET["inc_subcat"] ==1))
		{
			$this->subcategory = true;
		}
		else
		{
			$this->subcategory = true;
		}		
		if((isset($_GET["search_in_description"])) && ($_GET["search_in_description"] ==1))
		{
			$this->description = true;
		}
		else
		{
			$this->description = false;
		}
		$this->lvst_used = false;
		$this->sortflag=false;
		$this->replacement_set = false;
		$this->direct_match=false;
		$this->closest="";
		$this->shortest=1000;
		$this->dfrom = 0;
		$this->dto = 0;
		if(isset($_GET['pfrom'] ) && ($_GET['pfrom'] > 0))
		{
			$this->pfrom = mysql_escape_string( $_GET['pfrom'] );
		}
		else
		{
			$this->pfrom = '';
		}
		if(isset($_GET['pto']) && $_GET['pto'] > 0)
		{
			$this->pto = mysql_escape_string( $_GET['pto'] );
		}
		else
		{
			$this->pto = '';
		}
		$this->indb=false;
		$this->idstr ="";
		//QUERY
		$this->resultquery = "select distinct p.products_id, p.manufacturers_id, p.products_model from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_TAX_RATES . " tr, " . TABLE_PRODUCTS . " p left join (" . TABLE_MANUFACTURERS . " m)
 on (p.manufacturers_id = m.manufacturers_id) , " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' ";
		//EOF QUERY
		
		$this->ids = array();
		$this->keyparts = array();
	}
/*****************************************/
/*** ********* ****** ****** ** ****** ***/
/*** * ***** * ***** * ***** ** * **** ***/
/*** ** *** ** **** *** **** ** ** *** ***/
/*** *** * *** *** ----- *** ** *** ** ***/
/*** **** **** ** ******* ** ** **** * ***/
/*** ********* ** ******* ** ** ****** ***/
/*****************************************/###########################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################
	function search()
	{
		//MAIN ROUTINE
		/*
		 * IDEA
		 * get all products_id
		 * reduce them and then apply restrictions
		 * 
		 *
		 */
		
		$this->original=$this->keyphrase;
		$this->fixed = $this->original;
		$this->set_restrictions();
		if($this->keyphrase)
		{	
			//1st look in database
			$this->get_matching_ids();
			$this->get_standard_ids();
	
			if(trim($this->idstr) == "")
			{
				$this->get_more_ids();
				$this->get_less_matching_ids();
			}
	//		echo "\n 1---" . $this->idstr;
			$this->get_pef_results();
	//		echo "\n 2---" . $this->idstr;
		
			if(trim($this->idstr) <>"")
			{
				$this->ids = array_unique(explode(',', trim($this->idstr)) );
			}
			// query -> normaler teil  + array of IDS + restrictions
		}
//		echo "\n\n  3---" . $this->idstr;

		//replace phrase?
		if( (strtolower($this->fixed) <> strtolower($this->keyphrase)) || (isset($tempres) && !$this->direct_match) )
		{
			//echo "<!-- O" . $this->original .  "F" . $this->fixed . "K" . $this->keyphrase .  " -->";
			$this->lvst_used = true;
			$this->original = $this->fixed;
		}/**/
	//	echo "\n\n  4---" . $this->idstr;

		$i=0;
		while(trim($this->idstr) == ""){
			$i++;
			$this->use_lvst();
			$this->set_keyword($this->closest);
			$this->get_matching_ids();
			$this->get_standard_ids();
			$this->get_pef_results();
			$this->ids = array_unique(explode(',',$this->idstr) );
			$this->lvst_used=true;
			//echo "\n\n  5---" . $this->idstr;
			if($i == 30){
				$this->ids=array('11','12');
				break;
			}
		}
		
		$this->original = stripslashes($this->original);
		$this->fixed = stripslashes($this->fixed);
		if(isset($this->replacement) && ($this->replacement<>""))
		{
			$this->keyphrase = stripslashes($this->replacement);
		}
		else
		{
			$this->keyphrase = stripslashes($this->keyphrase);
		}
		if(sizeof($this->ids) > 0){
			$this->javascript = "
			<script type='text/javascript'>
			$(document).ready(function(){
				pattern = new RegExp('(>[^<.]*)(" . $this->keyphrase . ")([^<.]*)', 'ig');
				$('.gallery').each(function() {
				var content = $(this).html();
				if (!content) return;
				$(this).html(content.replace(pattern, '$1<span style=\"display:inline;background-color:rgb(210,255,255);line-height:15px;\">$2</span>$3'));
				});
				$('.productListing').each(function() {
				var content = $(this).html();
				if (!content) return;
				$(this).html(content.replace(pattern, '$1<span style=\"background-color:rgb(210,255,255)\">$2</span>$3'));
				});
				
				$('#descriptionbox').each(function() {
				var content = $(this).html();
				if (!content) return;
				$(this).html(content.replace(pattern, '$1<span style=\"background-color:rgb(210,255,255)\">$2</span>$3'));
				});
				
				$('#detailblock').each(function() {
				var content = $(this).html();
				if (!content) return;
				$(this).html(content.replace(pattern, '$1<span style=\"background-color:rgb(210,255,255)\">$2</span>$3'));
				});
			});
			</script>
			";

			$this->write_query();
			$resultat = $this->resultquery . " AND (p.products_id IN (".(implode(",",$this->ids)) . " ) " . ( ($this->model <> "") ? " OR (p.products_model IN (" . $this->model . ") ) )" : " ) "  ) .  $this->restrictions;
		//	echo "\n\n\n   6---" . $this->idstr;
			return $resultat;
		}
		
	}
/**/
// The Setter functions:
	function set_category($categories='all')
	{
		$categories = trim($categories);
		if($categories == '' || $category == 'all')
		{
			$this->category = "all";
		}
		else
		{
			$this->category = $categories;	
		}
	}
//
	function set_subcategories($flag)
	{
		if($flag == 1)
		{
			$this->subcategory = true;
		}
		else
		{
			$this->subcategory = false;
		}
	}
//
	function set_description($flag)
	{
		if($flag == 1)
		{
			$this->description = true;
		}
		else
		{
			$this->description = false;
		}
	}
//
	function set_dfrom($t)
	{
		if(push_not_null($t) )
		{
			$this->dfrom = $t;
		}
	}
//
	function set_dto($t)
	{
		if(push_not_null($t) )
		{
			$this->dto = $t;
		}
	}
//
	function set_pfrom($t)
	{
		if(push_not_null($t) )
		{
			$this->pfrom = $t;
		}
	}
//
	function set_pto($t)
	{
		if(push_not_null($t) )
		{
			$this->pto = $t;
		}
	}
//
	function set_restrictions()
	{
		global $currency, $currencies;

		$where_str = " ";
		if ($this->category <> 'all')
		{
			if ($this->subcategory)
			{
				$subcategories_array = array();
				push_get_subcategories($subcategories_array, $this->category);
				$where_str .= " and (p2c.categories_id = '" . $this->category . "'";
				for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
					$where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
				}
				$where_str .= ")";
			} else {
				$where_str .= " and p2c.categories_id = '" . (int)$_GET['categories_id'] . "' ";
			}
		}

		$this->restrictions = $where_str;
		return $this->restrictions;
	}
//
	function set_keyword($searchkey = "")
	{
		$fing = array('<','>', '/','%');
		$searchkey = str_replace($fing, '', strip_tags(urldecode($searchkey)) );
		$q = "select search_key FROM search_patterns WHERE pattern_url ='" . mysql_escape_string($searchkey) . "' AND pattern_category='" . $this->category . "' AND pattern_description='" . $this->description . "';";
		if($r = push_db_fetch_array(push_db_query($q)))
		{
			$searchkey = $r['search_key'];
		}
		else
		{
			//cleanup
			$searchkey= mysql_real_escape_string(trim($searchkey));
			$this->sortflag=true;
			//set keys
		}
		$where_str = "";
		if($searchkey <> "")
		{ 
			if(push_parse_search_string($searchkey, $this->keywords))
			{
				$this->keyphrase = push_db_prepare_input($searchkey); 
				//INSERT FROM INDEX
				if (sizeof($this->keywords) > 0)
				{
					$where_str .= " and (";
					for ($i=0, $n=sizeof($this->keywords); $i<$n; $i++ ) {
						switch ($this->keywords[$i]) {
							case '(':
							case ')':
							case 'and':
							case 'or':
								$where_str .= " " . $this->keywords[$i] . " ";
							break;
							/* Insert for advanced german queries
							case 'und':
								$where_str .= " and ";
								break;
							case 'oder':
								$where_str .= " or ";
								break;*/
							default:
								$keyword = push_db_prepare_input($this->keywords[$i]);
								$this->keyparts[] = $this->keywords[$i];
							// START: Extra Fields Contribution
							// $where_str .= "(pd.products_name like '%" . push_db_input($keyword) . "%' or p.products_model like '%" . push_db_input($keyword) . "%' or m.manufacturers_name like '%" . push_db_input($keyword) . "%'";
								$where_str .= "(pd.products_name like '%" . push_db_input($keyword) . "%' or p.products_model like '%" . push_db_input($keyword) . "%' or m.manufacturers_name like '%" . push_db_input($keyword) . "%' /*or p2pef.products_extra_fields_value like '%" . push_db_input($keyword) . "%'*/";
							// END: Extra Fields Contribution
							 if ($this->description) $where_str .= " or pd.products_description like '%" . push_db_input($keyword) . "%'";
								$where_str .= ')';
							break;
						}
					}
					$where_str .= " )";
				}
			}
		}
		else
		{
			$this->keyphrase=false;
		}
		//echo '<!--' .$where_str . "--> ";
		$this->keyquery =$where_str;
	}
//
	function get_matching_ids()
	{
		$tempres = "";
		//look if there is already a matching resultset in DB
		$q = "SELECT search_key, pattern_ID, result, model, pattern_replacement, pattern_url FROM search_patterns WHERE (search_key = '" . mysql_escape_string($this->keyphrase) . "' OR pattern_url = '" . $this->keyphrase . "') AND pattern_description='" . (($this->description)?1:0) . "' AND pattern_category='" . $this->category . "' ;";
		if($r = push_db_fetch_array(push_db_query($q)))
		{
			$tempres .= $r['result'] . ",";
			$this->indb=$r['pattern_ID'];
			$this->model =$r['model'];
			$this->replacement=stripslashes($r['pattern_replacement']);
			if( (strtolower($this->keyphrase) == strtolower($r['pattern_replacement'])) || (strtolower($this->keyphrase) == strtolower($r['pattern_url'])) )
			{
				$this->direct_match=true;
				if( strtolower($this->keyphrase) == strtolower(stripslashes($r['pattern_url'])) )
				{
					$this->original = stripslashes($r['search_key']);
				}
			}
			else
			{
				$this->direct_match=false;
				$this->set_keyword($r['pattern_replacement']);
			}
			
		}


		if($this->model <>"")
		{
			//$this->model = substr($this->model, 0,-1);
		}
		$tempres = substr($tempres, 0,-1);
		if($tempres <>"")
		{
			
			if($this->idstr<>'')
			{
			$this->idstr .=",";
			}
			$this->idstr .= $tempres;
		}
	}

	function get_less_matching_ids()
	{
	
		$tempres = "";
		foreach($this->keywords as $kw)
		{
			$q = "SELECT result, model FROM search_patterns WHERE search_key LIKE '%" . $kw . "%' AND pattern_description='" . (($this->description)?1:0) . "' AND pattern_category='" . $this->category . "';";
			if($r = push_db_fetch_array(push_db_query($q)))
			{
				$tempres .= $r['result'] . ",";
				if($r['model']<>"")
				{
					$this->model .= $r['model'] . ",";
				}
			}
		}

		if($this->model <>"")
		{
			$this->model = substr($this->model, 0,-1);
		}
		$tempres = substr($tempres, 0,-1);
		if($tempres <>"")
		{
			
			if($this->idstr<>'')
			{
			$this->idstr .=",";
			}
			$this->idstr .= $tempres;
		}

	}
		
//
	function get_standard_ids($phrasis = false)
	{
		if(!$phrasis)
		{
			$phrasis = $this->keyphrase;
		}
		$rewrite= false; //
		if($this->direct_match == false)
		{
			$rewrite = true;
		}
		global $languages_id;
		$tempres="";
		$manufacturer = $phrasis;//,0,( (strpos(' ',$this->keyphrase) ) ? strpos(' ',$this->keyphrase) : strlen($this->keyphrase) ) );
		//do the standard-osc-search-routine
		$select_str = "SELECT DISTINCT 	p.products_id, pd.products_name ,		
									(MATCH (pd.products_name) AGAINST ('" . mysql_escape_string($this->keyphrase) . 
									"') +  MATCH (m.manufacturers_name) AGAINST ('" . mysql_escape_string($this->keyphrase) . 
									"') ) AS reihenfolge
										FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  left join (" . TABLE_MANUFACTURERS . " m)
										ON (p.manufacturers_id = m.manufacturers_id) ,
										" . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  
										WHERE p.products_model NOT LIKE 'ccb_%' 
										AND p.products_status = '1' 
										AND p.products_id = p2c.products_id 
										AND pd.products_id = p2c.products_id 
										AND pd.language_id = '" . (int)$languages_id . "' ";

		//1st try for direct result
		$q = $select_str . " AND (MATCH (pd.products_name) AGAINST ('" . $this->original . "')  OR MATCH (m.manufacturers_name) AGAINST ('" . $this->original . "') 
							" . ( ($this->description)? " OR MATCH (pd.products_description) AGAINST ('" . $this->keyphrase . "') )" : ")" )  . "
							" . $this->restrictions . "
							ORDER BY reihenfolge DESC";
		$r=push_db_query($q);
		if(push_db_num_rows($r) > 0)
		{
			while( $t = push_db_fetch_array($r))
			{
				if($t['reihenfolge'] > 1)
				{
					$tempres .=$t['products_id'].",";
				}
				//echo $t['products_id']."," .$t['products_name'] ." sum: " . $t['reihenfolge'] ."\n..<br>\n";
			}
			$this->direct_match=true;
			//echo ".";
		}
		else
		{
		
			$mq=push_db_query("SELECT products_id FROM products WHERE products_model='" . $this->keyphrase . "';");
			while( $t = push_db_fetch_array($mq))
			{
				$tempres .=$t['products_id'].",";
				$this->direct_match=true;
			}
			
		}


		if($tempres <>"")
		{
			$tempres = substr($tempres, 0,-1);
			if($this->idstr<>'')
			{
			$this->idstr .= "," ;
			}
			$this->idstr .= $tempres ;
		}
		
		if($this->direct_match && $rewrite)
		{
			//echo "<!--|||-->";	
		}

	}
//
	function get_more_ids(){
		$tempres="";
		$q = $this->resultquery ." ". $this->keyquery . " " .$this->restrictions ;
		//	echo "\n"."<!-- 2nd.  " . $q ."-->";
		$r=push_db_query($q);
		if(push_db_num_rows($r) > 0)
		{
			while( $t = push_db_fetch_array($r))
			{
				$tempres .=$t['products_id'].",";
			}
		}		
		
		if($tempres <>"")
		{
			$tempres = substr($tempres, 0,-1);
			if($this->idstr<>'')
			{
			$this->idstr .= "," ;
			}
			$this->idstr .= $tempres ;
		}

		
	}
//
	function get_pef_results(){
		//include pef in search 
		return ;
		$i=0;
		$tempres="";
		$q="SELECT p2.products_id FROM products_to_products_extra_fields p2 JOIN products_extra_fields pe ON (p2.products_extra_fields_id = pe.products_extra_fields_id ) JOIN  products_to_categories p2c ON (p2.products_id = p2c.products_id) WHERE "
		." pe.products_extra_fields_type NOT LIKE 't' AND pe.products_extra_fields_type NOT LIKE 'n' AND ( ";
		foreach($this->keyparts as $keyword)
		{	
			if($i>0)
			{
				$q .=" OR ";
			}
			if($this->direct_match)
			{
				$q .= " pe.products_extra_fields_name = '" . $keyword .  "' ";
			}
			else
			{
				$q .= " pe.products_extra_fields_name LIKE '%" . $keyword .  "%' ";
			}
			$i++;
		}
		$i=0;
		$q .= " ) OR ( ";
		foreach($this->keyparts as $keyword)
		{	
			if($i>0)
			{
				$q .=" OR ";
			}
			if($this->direct_match)
			{
				$q .= " p2.products_extra_fields_value LIKE '%" . $keyword .  "%' ";
			}
			else
			{
				$q .= " p2.products_extra_fields_value LIKE '%" . $keyword .  "%' ";
			}
			
			$i++;
		}
		
		$q .=" ) ";
		if($this->category <>'all'){
		$q .= " AND p2c.categories_id='" . $this->category . "'";
		}
		$r=push_db_query($q);
		if(push_db_num_rows($r) > 0)
		{
			while( $t = push_db_fetch_array($r)){
				$tempres .= $t['products_id'].",";
			}
		}
		
		$tempres = substr($tempres, 0,-1);
		if($tempres <>"")
		{
			
			if($this->idstr<>'')
			{
			$this->idstr .=",";
			}
			$this->idstr .= $tempres;
		}


		
	}
	
	function use_replacement_table($string){
		/**
		 * @TODO: create a table with substitite words for search
		 */
	}
//
	function cut_names($nastr){
			$narr= strip_tags($nastr);
			return $narr;
	}
//
	function use_lvst(){
		$input = $this->keyphrase;
		$words=array();
		$txq=push_db_query("SELECT DISTINCT m.manufacturers_name, p.products_model, pd.products_name, pd.products_description FROM manufacturers m, products_description pd, products p JOIN products_to_categories p2c ON (p.products_id = p2c.products_id)  WHERE  p.products_model NOT LIKE 'ccb_%' AND  p.products_id=pd.products_id AND m.manufacturers_id=p.manufacturers_id AND p.products_status=1 " . (($this->category =="all") ? "" : " AND p2c.categories_id = '" . $this->category . "' ")  ); 
		while($txt=mysql_fetch_assoc($txq))
		{
			$t .= $this->cut_names($txt['manufactures_name']) . " ";
			$t .=  $this->cut_names($txt['products_name']) . " ";
			$t .= $this->cut_names($txt['products_model']) . " ";
			if (  $this->description)
			{
				$t .= $this->cut_names($txt['products_description']) . " ";
			}
		}
		
		$words = explode(" ", $t);
		$words = array_unique($words);
		sort($words);
		// noch keine kuerzeste Distanz gefunden
		//foreach($inputar AS $input){
		// Wörterarray als Vergleichsquelle
		$shortest  = 1000;
		$shortest1 = 1000;
		$shortest2 = 1000;
		// durch die Wortliste gehen, um das aehnlichste Wort zu finden
		foreach ($words as $word)
		{
			if( ($word <> "") && ( !is_numeric($word) )){
				// berechne die Distanz zwischen Inputwort und aktuellem Wort
				$lev2 = levenshtein( strtolower($input), strtolower($word));
				$lev1 = levenshtein( metaphone($input), metaphone($word));
				// auf einen exakten Treffer prüfen
				if ($lev2 == 0) {
					$this->closest = $word;
					$shortest = 0;
					$this->shortest=0;
					break;
				}
				
				if (($lev1 <= $shortest1) || $shortest1 < 0) {
					$closest1  = $word;
					$shortest1 = $lev1;
				}
				
				if (($lev2 <= $shortest2) || $shortest2 < 0) {
					$closest2  = $word;
					$shortest2= $lev2;
				}
			}
		}
		
		if($shortest1<=$shortest2){
			$this->closest=$closest2;
			$this->shortest=$shortest1;
		}else{
			$this->closest=$closest1;
			$this->shortest=$shortest2;
		}
		$this->set_keyword($this->closest);
	}
//
	function write_query(){

		if($t=push_db_fetch_array(push_db_query('SELECT search_text FROM search_queries WHERE search_text ="' . mysql_escape_string($this->fixed) . '"')))
		{
			push_db_query('UPDATE search_queries SET counter = counter+1 WHERE search_text ="' . mysql_escape_string($this->fixed) . '"');
		}
		else
		{
			push_db_query('INSERT INTO search_queries (search_text,  counter) values("' . mysql_escape_string($this->fixed) . '", 1)');
		}
		if($this->indb)
		{
			//update
			$i = "UPDATE search_patterns SET result = '" . implode(",", $this->ids).  "'   WHERE pattern_ID ='" .  $this->indb . "'";
		}
		else
		{
			//insert
			$phrase=($this->lvst_used && ($this->replacement <> '')) ? $this->replacement : $this->keyphrase;
			$url = $this->get_url_string($this->fixed);
			$i= "INSERT INTO search_patterns (search_key, result, pattern_replacement, pattern_url, pattern_description, pattern_category) VALUES ('" . mysql_escape_string($this->fixed)  . "', '" . implode(",", $this->ids) . "', '" . mysql_escape_string($phrase) . "', '" . mysql_escape_string($url) . "',  '" . (($this->description)?1:0) . "', '" . $this->category . "')";
		}
		push_db_query($i);
	}
//
	function register_user_keywords(){
		if(push_session_is_registered('customer_id'))
		{
			// save the keywords in database
			define('NUMBER_OF_KEYWORDS', 10);
			$history_q = push_db_query("	SELECT 	keywords
										FROM 	customers_search_history
										WHERE 	customers_id = " . (int)$_SESSION['customer_id']);

			if (push_db_num_rows($history_q) == 0)
			{
				// create a new history for the user
				push_db_query("	INSERT INTO customers_search_history (customers_id, keywords) 
								VALUES 		(" . (int)$_SESSION['customer_id'] . ", '" . $this->original . "')");
			}
			else
			{
				$history = push_db_fetch_array($history_q);
				$historyKeywords = explode(";", $history['keywords']);

				if (!in_array($this->original, $historyKeywords))
				{
					// keyword not in the history
					// add new keyword at the beginning of the list
					array_unshift($historyKeywords, $this->original);
					
					if (sizeof($historyKeywords) > NUMBER_OF_KEYWORDS)
					{
						// remove the last (oldest) element of the list
						array_pop($historyKeywords);
					}				
				}
				else
				{
					// keyword in the history - move it t the beginning
					$key = array_search($this->original, $historyKeywords);
					unset($historyKeywords[$key]);
					array_unshift($historyKeywords, $this->original); 
				}
				// update the history
				$newHistory = implode(";", $historyKeywords);
				push_db_query("	UPDATE 	customers_search_history 
								SET 	keywords = '" . $newHistory . "'");
			}
		}
	}
//
	function get_url_string($word)
	{ 
		$look=array('€','‚','Š','Œ','Ž','‘','’','“','”','•','–','—','˜','™','š','‹','›','œ','ž','Ÿ','¡','¢','£','¤','¥','¦','§','¨','©','ª','«','¬','­','®','¯','°','±','²','³','´','µ','¶','·','¸','¹','º','¼','½','¾','¿','À','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','×','Ø','Ù','Ú','Û','Ü','Ý','Þ','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','÷','ø','ù','ú','û','ü','ý','þ','ÿ',"'",'"',' ',"\\", '/',"'",',','%');
		$repl=array('Euro','-','S','CE','Z','-','-','-','-','-','-','-','-','TM','s','-','-','oe','z','Y','i','c','L','o','Y','I','Paragraph','-','copy','a','-','-','-','R','-','-','-','2','3','-','mikro','P','-','-','1','o','14','12','34','?','A','A','A','AE','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','OE','x','O','U','U','U','UE','Y','th','ss','a','a','a','a','ae','a','ae','c','e','e','e','e','i','i','i','i','th','n','o','o','o','o','oe','-','o','u','u','u','ue','y','dh','y','','','-','-','-','','','');
		//$word = strtolower($word);
		$word = str_replace($look,$repl,$word);
		$look = array('------','-----','----', '---','--');
		$word = str_replace($look,'-',$word);
		return $word;
	}
	
	function utf8($string)
	{
		//$string = iconv("Windows-1252", "UTF-8//IGNORE", $string);
		return $string;
	}

}
?>
