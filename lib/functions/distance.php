<?php /*
function to get distance via google sörvice 
*/
function get_distance($strasse, $plz, $ort, $country){
//this is the current starting point
	$Startadresse= "Mainstrasse+171,10719+Bruessels,Germany";
	$Zieladresse = make_url_string($strasse).','.make_url_string($plz).'+'.make_url_string($ort) . ',' . make_url_string($country) ;
	$Zieladresse = str_replace('-','+',$Zieladresse);
	$url= "http://maps.googleapis.com/maps/api/directions/json?origin=" . $Startadresse . "&destination=" . $Zieladresse . "&sensor=false&avoid=highways" ;
	$input=file_get_contents($url);
//	echo $input;
//	echo $url . "<br><br>";
	$data=json_decode($input,true);
//	var_dump($data);
	if($data['status']=='OK'){
		$Entfernung = $data['routes'][0]['legs'][0]['distance']['value'];
		return $Entfernung;;
	}else{
		return false;
	}
}
?>