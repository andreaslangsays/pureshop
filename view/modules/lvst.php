<?php
//die('Called');
$input = trim($_GET['keywords']);
// $select_str . $from_str . $where_str . $order_str;
//die('Levenstein');
//Query teilen
$where_pos=strrpos($listing_sql, 'where');
$fixpart=  $select_str;
$changepart= $where_str . $order_str;
//echo $fixpart . "  " . $changepart;
//echo $_GET['keywords'];


//herausfinden der Keywords: eigentlich nur einmal noetig...? 
$t='';
$txq=push_db_query("SELECT DISTINCT m.manufacturers_name, p.products_model, pd.products_name, pd.products_description FROM manufacturers m, products_description pd, products p WHERE p.products_id=pd.products_id AND m.manufacturers_id=p.manufacturers_id AND p.products_status=1"); 
while($txt=mysql_fetch_assoc($txq))
{
	$t .= " " .strip_tags($txt['manufactures_name']);
	$t .= " " .strip_tags($txt['products_name']);
	$t .= " " .strip_tags($txt['products_model']);
	 if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) $t .= " " .strip_tags($txt['products_description']);
}
//echo $t;


$words  = explode(" ", $t); 
// noch keine kuerzeste Distanz gefunden
//foreach($inputar AS $input){
// Wörterarray als Vergleichsquelle
$shortest  = 1000;
$shortest1 = 1000;
$shortest2 = 1000;
// durch die Wortliste gehen, um das aehnlichste Wort zu finden
foreach ($words as $word) {
if($word<>""){

  // berechne die Distanz zwischen Inputwort und aktuellem Wort
  $lev2 = levenshtein( strtolower($input), strtolower($word));
  $lev1 = levenshtein( metaphone($input), metaphone($word));
 
  // auf einen exakten Treffer prüfen
  if ($lev2 == 0) {
      $closest = $word;
      $shortest = 0;
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
$closest=$closest2;
}else{
$closest=$closest1;
}

echo "<span class=\"info-warning\">Ihre Suche nach &ldquo;$input&rdquo; ergab leider keine Produkttreffer. </span>\n<br>";
if ($shortest == 0) {
  echo "<span class=\"info-statement\"> Meinten Sie: $closest\n";
} else {
  echo "<span class=\"info-statement\">Meinten Sie: $closest?\n ";
}
//$listing_sql=str_replace($input)
$keywordstr = urlencode($closest);
//$key_categories_id='all';
$closest= push_db_input($closest);

$ia=explode(' ',strtolower($input));
function likestr(&$item1, $key)
{
    $item1 = "%".$item1."%";
}

array_walk($ia, 'likestr');
$changepart=str_replace($ia,'%'.(strtolower($closest)).'%',$changepart);


$listing_sql=$fixpart.$changepart;
$count = push_db_num_rows(push_db_query($listing_sql));

if($count==0){
$messageStack->add_session('search', TEXT_NO_PRODUCTS);
push_redirect(push_href_link(FILENAME_ADVANCED_SEARCH, push_get_all_get_params(), 'NONSSL', true, false));
}else{
echo " $count " . (($count==1)? 'Ergebnis' : 'Ergebnisse') .".</span>";
}

?>
