<?php
chdir('../../../');
include('includes/ajax_top.php');
function mail_att($to,$subject,$message,$anhang) 
{ 
	$absender = "push Onlineshop"; 
	$absender_mail = "onlineshop@if-bi.com"; 
	$reply = "onlineshop@if-bi.com"; 
	
	$mime_boundary = "-----=" . md5(uniqid(mt_rand(), 1)); 
	
	$header  ="From:".$absender."<".$absender_mail.">\n"; 
	$header .= "Reply-To: ".$reply."\n"; 
	
	$header.= "MIME-Version: 1.0\r\n"; 
	$header.= "Content-Type: multipart/mixed;\r\n"; 
	$header.= " boundary=\"".$mime_boundary."\"\r\n"; 
	
	$content = "This is a multi-part message in MIME format.\r\n\r\n"; 
	$content.= "--".$mime_boundary."\r\n"; 
	$content.= "Content-Type: text/plain\r\n"; 
	$content.= "Content-Transfer-Encoding: 8bit\r\n\r\n"; 
	$content.= $message."\r\n"; 
	
	//$anhang ist ein Mehrdimensionals Array 
	//$anhang enthält mehrere Dateien 
	if(is_array($anhang) AND is_array(current($anhang))) 
	{ 
		foreach($anhang AS $dat) 
		{ 
			$data = chunk_split(base64_encode($dat['data'])); 
			$content.= "--".$mime_boundary."\r\n"; 
			$content.= "Content-Disposition: attachment;\r\n"; 
			$content.= "\tfilename=\"".$dat['name']."\";\r\n"; 
			$content.= "Content-Length: .".$dat['size'].";\r\n"; 
			$content.= "Content-Type: ".$dat['type']."; name=\"".$dat['name']."\"\r\n"; 
			$content.= "Content-Transfer-Encoding: base64\r\n\r\n"; 
			$content.= $data."\r\n"; 
		} 
		$content .= "--".$mime_boundary."--";  
	} 
	else //Nur 1 Datei als Anhang 
	{ 
		$data = chunk_split(base64_encode($anhang['data'])); 
		$content.= "--".$mime_boundary."\r\n"; 
		$content.= "Content-Disposition: attachment;\r\n"; 
		$content.= "\tfilename=\"".$anhang['name']."\";\r\n"; 
		$content.= "Content-Length: .".$dat['size'].";\r\n"; 
		$content.= "Content-Type: ".$anhang['type']."; name=\"".$anhang['name']."\"\r\n"; 
		$content.= "Content-Transfer-Encoding: base64\r\n\r\n"; 
		$content.= $data."\r\n"; 
	}  
	
	if(@mail($to, $subject, $content, $header)) return true; 
	else return false; 
}

if( $customer->login)
{
	
	$random_hash = md5(date('r', time()));
	$text ="Reklamation von " . $customer->customers_firstname . " " . $customer->customers_lastname ."(KdNr.:" . $customer->customers_id . ")";
	$text .= " Bestellung Nr:" . $_POST['ordernr'] . "\n";
	$text .= "Lieferschein Nr:" . $_POST['cargo'] . "\n";
	$text .= "Position:" . $_POST['position'] . "\n";
	if($_POST['artnr']<>"")
	{
	  $text .= "Artikelnummer Nr:" . $_POST['artnr'] . "\n";
	}
	if($_POST['name']<>"")
	{
		$text .= "Artikel Bezeichnung:" . $_POST['name'] . "\n";
	}
	if(intval($_POST['quantity'])>0)
	{
	  $text .= "Anzahl " . intval($_POST['quantity']) . "\n";
	}
	
	  $text .= "Grund (Auswahl): " . $_POST['reason'] . "\n";
	if($_POST['annotation']<>"")
	{
		$text .= "Bemerkung: " . $_POST['annotation'] . "\n";
	}
	if($_POST['solution']<>"")
	{
		$text .= "Lösungsvorschlag Kunde: " . $_POST['solution'] . "\n";
	}
	if($_POST['MHD']<>'')
	{
		$text .= "MHD: " . $_POST['MHD'] . "\n";
	}
	$text .= "Ansprechpartner: " . $_POST['person'] . "\n";
	
	if($_POST['date'] <> "TT.MM.JJJJ")
	{
		$text .= "Rücksprache erwünscht am " . $_POST['date'] . "\n";
		if( $_POST['timeslot'] <> "Zeitraum")
		{
			$text .= "um " .$_POST['timeslot'] . "\n";
		}
		$text .= "Rückrufnummer: " . $_POST['phone'] . "\n";
	} 
	
	if(is_array($_POST['bild']) && count($_POST['bild']) > 0)
	{
		$anhang=array();

		foreach($_POST['bild'] as $bildchen)
		{
			$name="images/mailupload/".$bildchen;
			$size = filesize($name);
			$data = file_get_contents($name); 
			if(function_exists("mime_content_type")) 
				$type = mime_content_type($name); 
			else 
				$type = "application/octet-stream"; 
			$anhang[] = array("name"=>$bildchen, "size"=>$size, "type"=>$type, "data"=>$data); 
			//$text .="name: " . $bildchen . "}\n size: " . $size . "}\n type " . $type . "}\n data " . $data . "} \n\n\n";
		}
	}
	if(mail_att("service@if-bi.com",utf8_decode("Reklamation ". $customer->customers_firstname . " " . $customer->customers_lastname) ,utf8_decode($text),$anhang))
		echo "true";
	else
		echo "false";	
}
else
{
	echo 'false';
}
?>