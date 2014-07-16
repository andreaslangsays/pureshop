<?php 
/**
 *
 *	object to händle attributes of given product (if there are)
 *  attributes here are data from exrta-fields
 *
 */
 
 //Function to handle various Product-attributes - (should be an object!!)
 class pref{
 
 var $attr, $products_id, $special, $mhd, $expires_date;
 
 
	function pref($pid,$lin=''){
 //unset($this->attr);
 //Anhand der Product-ID die Variablen befüllen (als assoziatives Array)
		$prefq="SELECT SQL_CACHE pef.products_extra_fields_name, p2pef.products_extra_fields_value FROM products_extra_fields pef JOIN products_to_products_extra_fields p2pef ON (pef.products_extra_fields_id=p2pef.products_extra_fields_id)  WHERE products_extra_fields_status = '1' AND products_id=" . $pid . " ORDER BY pef.products_extra_fields_order;";
		if($lin=='dbt'){
			$qr=push_db_query($prefq, 'dbt');
		}else{
			$qr=push_db_query($prefq);
		}
		while($t=push_db_fetch_array($qr)){
			$this->attr[$t['products_extra_fields_name']]=$t['products_extra_fields_value'];
		}
		if($lin=='dbt'){
			$n=push_db_query("SELECT SQL_CACHE products_date_added FROM products WHERE products_id='" . $pid . "' AND DATE_SUB(CURDATE(),INTERVAL  " . INTERVAL_NEW_PRODUCTS . "  DAY) <= products_date_added  ", 'dbt');
		}else{
			$n=push_db_query("SELECT SQL_CACHE products_date_added FROM products WHERE products_id='" . $pid . "' AND DATE_SUB(CURDATE(),INTERVAL  " . INTERVAL_NEW_PRODUCTS . "  DAY) <= products_date_added  ");
		}
		if(push_db_fetch_array($n)){
		$this->attr['New']=true;
		}
		$this->products_id = $pid;
		
		$spec=push_db_fetch_array(push_db_query("SELECT products_mhd, expires_date FROM specials WHERE products_id='" . $pid . "'"));
		if($spec<>false){
			$this->special=true;
			$this->mhd=$spec['products_mhd'];
			$this->expires_date=$spec['expires_date'];
		}else{
			$this->mhd='';
			$this->special=false;
		}
	}
 
 
	function is_bio(){
	 	if(isset($this->attr['Bio'])){
			if($this->attr['Bio'] <> '' )
				return true;
		}
		return false;
 	}
	
	function get_bio_cert(){
		return $this->attr['Bio'];
	}
 	
	function is_coffee(){
		if(isset($this->attr['Roestgrad']) && $this->attr['Roestgrad']<>''){
			return true;
		}else{
			return false;
		}
	}
	
	function is_new(){
		if(isset($this->attr['New']) ){
			return true;
		}else{
			return false;
		}
	}
	
	function display_package($gallery=false){
		if(isset($this->attr['Menge'])){
			$text = $this->attr['Menge'] . " " .  $this->attr['Einheit'];
			if(isset($this->attr['Abtropfgewicht'])){
				if(!$gallery){
						$text .=" / Abtropfgewicht " . $this->attr['Abtropfgewicht'];
					}else{
						$text .="<br> Abtropfgewicht " . $this->attr['Abtropfgewicht'];
					}
			}
			return $text;
		}else{
			return "";
		}
	}
	
	function display_toolbox_images($image,$name, $sl=''){
	if(($image =='') || ($image =='bilder/noch_kein_bild.png') ){
		$image= 'bilder/' . get_categories_default_image($this->products_id,$sl);
	}
	
	if(!file_exists(DIR_WS_IMAGES . 'mini/'. $image)&&(file_exists(DIR_WS_IMAGES . 'small/'. $image))){
		push_copy_image(DIR_WS_IMAGES . 'small/' .$image, DIR_WS_IMAGES . 'mini/'. $image, 60, 60);
	}elseif(!file_exists(DIR_WS_IMAGES . 'mini/'. $image)&&(file_exists(DIR_WS_IMAGES . $image))){
		push_copy_image(DIR_WS_IMAGES .$image, DIR_WS_IMAGES . 'mini/'. $image, 60, 60);	
	}elseif(!file_exists(DIR_WS_IMAGES . 'mini/'. $image)&&(file_exists(DIR_WS_IMAGES . 'full_size/' . $image))){
		push_copy_image(DIR_WS_IMAGES  . 'full_size/' .$image, DIR_WS_IMAGES . 'mini/'. $image, 60, 60);	
	}
	
	$output = '	<a href="' . push_href_link($sl . FILENAME_PRODUCT_INFO, 'products_id=' . $this->products_id ) . '"><div class="outer">
		<div class="inner">
			<span></span>' . push_image(DIR_WS_IMAGES . 'mini/'. $image, $name) .'
		</div>'. "\n";
	$output .= "		" .$this->get_infographics("small");
	$output .= "	</div></a>";
	return $output;
	}
		
	function get_infographics($size=''){ // for future extentions (add a prefix to imagename) by default not!
//		if($this->is_new()){
//			$output .='<img src="images/newbkr/' . $size . 'corner-new.png" style="position:absolute;top:0;left:0;z-position:8;">' . "\n";
//			}
		$output='';
		if( $this->is_bio() && ($size=="")){
				$output .='<img src="images/pixel_trans.gif" class="BKR auszeichnung_bio_60x60" style="position:absolute;bottom:0;right:0;z-position:8;">' . "\n";
			}elseif( $this->is_bio() && ($size=="small")){
				$output .='<img src="images/pixel_trans.gif" class="BKR auszeichnung_bio_30x30" style="position:absolute;bottom:0;right:0;z-position:8;">' . "\n";	
			}
		return $output;
	}

 	function get_package(){
		return $this->attr['Menge'] .' ' . $this->attr['Einheit'];
	}
	
	 function get_abtropfgewicht(){
	 	if(isset( $this->attr['Abtropfgewicht'] )){
			return $this->attr['Abtropfgewicht'] .' g';
		}else{
			return false;
		}
	}
 
 	function has_coffee_profile(){
		if( ($this->attr['Koerper']<>'')&&($this->attr['Harmonie']<>'')&&($this->attr['Saeure']<>'')&&($this->attr['Aromen']<>'')&&($this->attr['Bouquet']<>'') ){
		return true;
		}else{
		return false;
		}
	}
	
 	function get_coffee_profile(){
	
		if( ($this->attr['Koerper']<>'')&&($this->attr['Harmonie']<>'')&&($this->attr['Saeure']<>'')&&($this->attr['Aromen']<>'')&&($this->attr['Bouquet']<>'') ){
		/**
		 * create a snippet 
		 */
		//first the Frame
			$txt =  '<div id="cprofile">
			<img src="images/newbkr/transicon.png" width="1" height="128" style="margin:0;margin-left:-3px;">';
		//then the Images
			$txt .= '	<img src="images/newbkr/cup.png" class="cup' . $this->attr['Koerper'] . ' corpse">';
			$txt .= '	<img src="images/newbkr/cup.png" class="cup' . $this->attr['Harmonie'] . ' harmony">';
			$txt .= '	<img src="images/newbkr/cup.png" class="cup' . $this->attr['Saeure'] . ' acid">';
			$txt .= '	<img src="images/newbkr/cup.png" class="cup' . $this->attr['Aromen'] . ' aroma">';
			$txt .= '	<img src="images/newbkr/cup.png" class="cup' . $this->attr['Bouquet'] . ' bouquet">';
			$txt .= '		<br>
					<span class="corpse">K&ouml;rper</span><span class="harmony">Harmonie</span><span class="acid">S&auml;ure</span><span class="aroma">Aromen</span><span class="bouquet">Bouquet</span> 
			</div>';
			return $txt;
		}else{
			return false;
		}
	}

	function is_special(){
		return $this->special;
	}
	
	function has_mhd(){
		if($this->mhd ==''){
			return false;
		}else{
			return true;
		}
	}

	function get_coffee_info(){
		$txt = '';
		if(isset($this->attr['Region']) && $this->attr['Region'] <> '')
		$txt .= '<b>' . TEXT_REGION .':</b> <span>' . $this->attr['Region'] . "</span>\n";
		if(isset($this->attr['Plantage']) && $this->attr['Plantage'] <> '')
		$txt .= '<b>' . TEXT_PLANTAGE .':</b> <span>' . $this->attr['Plantage'] . "</span>\n";
		if(isset($this->attr['Varietaet']) && $this->attr['Varietaet'] <> '')
		$txt .= '<b>' . TEXT_VARIETAET .':</b> <span>' . $this->attr['Varietaet'] . "</span>\n";
		if(isset($this->attr['Anbauhoehe']) && $this->attr['Anbauhoehe'] <> '')
		$txt .= '<b>' . TEXT_ANBAUHOEHE .':</b> <span>' . $this->attr['Anbauhoehe'] . "</span>\n";
		if(isset($this->attr['Boden']) && $this->attr['Boden'] <> '')
		$txt .= '<b>' . TEXT_BODEN .':</b> <span>' . $this->attr['Boden'] . "</span>\n";
		if(isset($this->attr['Aufbereitung']) && $this->attr['Aufbereitung'] <> '')
		$txt .= '<b>' . TEXT_AUFBEREITUNG .':</b> <span>' . $this->attr['Aufbereitung'] . "</span>\n";
		if(isset($this->attr['Schattenbaume']) && $this->attr['Schattenbaume'] <> '')
		$txt .= '<b>' . TEXT_SCHATTENBAUME .':</b> <span>' . $this->attr['Schattenbaume'] . "</span>\n";
		if(isset($this->attr['Qualitaet']) && $this->attr['Qualitaet'] <> '')
		$txt .= '<b>' . TEXT_QUALITY .':</b> <span>' . $this->attr['Qualitaet'] . "</span>\n";
		if(isset($this->attr['Roestgrad']) && $this->attr['Roestgrad'] <> '')
		$txt .= '<div style="height:30px;margin-top:10px;margin-bottom:20px;"><b style="position:relative;top:5px;">' . TEXT_ROESTGRAD .':</b> <span class="roestgrad"><img src="images/newbkr/cup.png" class="roest' . $this->attr['Roestgrad'] . "\"></span></div>\n";
		if(isset($this->attr['Mischungsbestandteile']) && $this->attr['Mischungsbestandteile'] <> '')
		$txt .= '<b>' . TEXT_MISCHUNGSBESTANDTEILE .':</b> <span>' . $this->attr['Mischungsbestandteile'] . "</span>\n";
		if(isset($this->attr['Zertifizierungen']) && $this->attr['Zertifizierungen'] <> '')
		$txt .= '<b>' . TEXT_ZERTIFIZIERUNGEN .':</b> <span>' . $this->attr['Zertifizierungen'] . "</span>\n";
		if(isset($this->attr['Geeignetfuer']) && $this->attr['Geeignetfuer'] <> '')
		$txt .= '<b>' . TEXT_GEEIGNETFUER .':</b> <span>' . $this->attr['Geeignetfuer'] . "</span>\n";
		return $txt;
	}
}
