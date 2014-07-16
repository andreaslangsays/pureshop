<?php
	/* PHP-Script um SQL-Anfragen an die MySql-Datenbank weiterzuleiten.
	  */

	// URL-Variablen auslesen
	$submitMethod = $_GET['submitMethod'];
	
	// Variablen entgegen nehmen, entweder per POST oder per default �ber GET
	if ($submitMethod && strtolower($submitMethod) == 'post') {
		$command = $_POST['command'];
		$database = $_POST['database'];
		$commandString = stripslashes($_POST['commandString']);	
	} else {
		$command = $_GET['command'];
		$database = $_GET['database'];
		$commandString = stripslashes($_GET['commandString']);	
	}
	echo "T1";
	// XmlItem f�r Fehlerausgabe anlegen
	$errors = new XmlItem('Errors');
	
	// Datenbankvariablen setzen
	require_once('../includes/configure.php');
	$host = DB_SERVER;
	$user = DB_SERVER_USERNAME;

	$pw = DB_SERVER_PASSWORD;
	$database = DB_DATABASE;
	
	echo "T2";
	// durchzuf�hrende Aktion bestimmen und ausf�hren
	if ($command) {
		switch (strtolower($command)) {
			case 'testconnection':
				testConnection();
				break;
			case 'executecommand':
				if ($commandString)
					executeCommand($commandString);
					
				break;
		}
	} else {
		testConnectionForUser();
	}

	// *** SQL-Kommando ausf�hren ***
	function executeCommand($commandString) {
		global $database, $errors, $host, $user, $pw;
		
		echo "T3";
		
		$xmlWriter = new SmallXmlWriter();
		$resultSet = new XmlItem('CommandResult');
		$resultSet->addSubItem($errors);

		echo "T4";
		// Verbindung zur Datenbank
		if ($database) {
			$dbSession = createDbSession($database, $host, $user, $pw);
			echo "T5-1";
			echo "T5-5";
			
			if ($dbSession) {
				echo "T6";
				
				// Kommando parsen				
				if (preg_match('/^select .* from paymentsr$/', trim(strtolower($commandString)))) { 
					echo "T7";
									
					// Payments aus Datei lesen
					$resultSet = getPayments($dbSession, $resultSet);
					
					echo "T8";
					
					echo $resultSet;
					return;
				} else if (preg_match('/^select .* from shippings$/', trim(strtolower($commandString)))) {
					// Shippings aus Datei lesen
					$resultSet = getShippings($dbSession, $resultSet);
				} else {	
					// Standard-Modus, der lediglich das SQL-Statement in der Datenbank ausf�hrt
					// Kommando ausf�hren
					@$result = mysql_query($commandString, $dbSession);

					if ($result) {
						// Spaltennamen bestimmen
						$headers = new XmlItem('Headers');
						for ($i = 0; $i < @mysql_num_fields($result); $i++) {
							$colHeader = new XmlItem('ColHeader');
							$colHeader->addSubItem(mysql_field_name($result, $i));
							$headers->addSubItem($colHeader);
						}
						$resultSet->addSubItem($headers);

						// Inhalt bestimmen
						while ($row = @mysql_fetch_array($result, MYSQL_NUM)) {
							$rowItem = new XmlItem('Reihe');
							
							foreach ($row as $column) {
								$colItem = new XmlItem('Spalte');
								$colItem->addSubItem($column);
								$rowItem->addSubItem($colItem);
							}
							
							$resultSet->addSubItem($rowItem);
						}				
						
						// Ergebnis leeren
						@mysql_free_result($result);
					} else {
						$executeError = new XmlItem('ExecuteError');
						$executeError->addSubItem('Anfrage fehlgeschlagen: ' . mysql_error());
						$errors->addSubItem($executeError);
					}
				}
				
				// Datenbankverbindung schlie�en
				closeDbSession($dbSession);
			}
		} else {
			$databaseMissedError = new XmlItem('DatabaseMissedError');
			$databaseMissedError->addSubItem('Database wurde nicht �ber die URL �bergeben');
			$errors->addSubItem($databaseMissedError);
		}

		// XML-String formatiert ausgeben
		$xmlWriter->writeItem($resultSet);
	}
	
	// *** Versandarten auslesen ***
	function getShippings($dbSession, $resultSet) {
	
		// Spaltennamen setzen
		$headers = new XmlItem('Headers');
		$colHeader1 = new XmlItem('ColHeader');
		$colHeader1->addSubItem('ID');
		$headers->addSubItem($colHeader1);
		$colHeader2 = new XmlItem('ColHeader');
		$colHeader2->addSubItem('NAME');
		$headers->addSubItem($colHeader2);
		$resultSet->addSubItem($headers);

		// Dateien nach den Konstanten parsen
		$files = getFilenames("SELECT configuration_value FROM configuration WHERE configuration_key='MODULE_SHIPPING_INSTALLED'", $dbSession);
		foreach($files as $file) {
			$resultSet = parseFile($file, "shipping", $resultSet);
		}
		return $resultSet;		
	}
	
	// *** Zahlungsarten auslesen ***
	function getPayments($dbSession, $resultSet) {
		
		echo "Hallo 3";		
		
		// Spaltennamen setzen
		$headers = new XmlItem('Headers');
		$colHeader1 = new XmlItem('ColHeader');
		$colHeader1->addSubItem('ID');
		$headers->addSubItem($colHeader1);
	
    	$colHeader2 = new XmlItem('ColHeader');
		$colHeader2->addSubItem('NAME');
		$headers->addSubItem($colHeader2);
		$resultSet->addSubItem($headers);
		
		echo "Hallo 4";		
		
		// Dateien nach den Konstanten parsen
		$files = getFilenames("SELECT configuration_value FROM configuration WHERE configuration_key='MODULE_PAYMENT_INSTALLED'", $dbSession);
		
		foreach($files as $file) {
			
			$resultSet = parseFile($file, "payment", $resultSet);
		}
		
		echo "Hallo 4";
		return;
		
		
		return $resultSet;
	}
	
	// *** Dateinamen abrufen ***
	function getFilenames($sqlStatement, $dbSession) {

		@$result = mysql_query($sqlStatement, $dbSession);					
		if ($result) {
			$files = "";
			while ($row = @mysql_fetch_array($result, MYSQL_NUM)) {
				foreach ($row as $column) {
					$files = $files."$column;";
				}							
			}
			$files = substr($files, 0, strlen($files) - 1);
			
			// Ergebnis leeren
			@mysql_free_result($result);
			
			// Dateien auslesen
			return explode(";", $files);
		}
	}
	
	// *** Datei parsen ***
	function parseFile($filename, $parse, $resultSet) {
		$filename = "../includes/languages/german/modules/$parse/$filename";		
		//$filename = "../includes/modules/$parse/$filename";		
		include $filename;

		echo $filename;
		
		$parseTitle = "/MODULE\_".strtoupper($parse)."\_[^\_]+\_TEXT\_TITLE[^\']*/";
		$parseData = "/MODULE\_".strtoupper($parse)."\_[^\_]+/";

		echo "T10";
		
		$filecontents = file_get_contents($filename);
		
		
		echo $parseTitle."<br>".$titles."<br>";
		
		$nTitles = preg_match_all($parseTitle, $filecontents, $titles);
		preg_match_all($parseData, $filecontents, $data);
		
		$foundIds = '';
		
		echo "T20";
		echo "T50";
		
		for ($nTitle = 0; $nTitle < $nTitles; $nTitle++) {

			echo "T40";
		return;
		
			// Schauen ob diese ID schon hinzugef�gt wurde
			if (strpos($foundIds, $data[0][$nTitle]) === FALSE) {
				$rowItem = new XmlItem('Reihe');
			
				// ID hinzuf�gen
				$colItem = new XmlItem('Spalte');
				$colItem->addSubItem($data[0][$nTitle + 1]);
				$rowItem->addSubItem($colItem);

				// Name hinzuf�gen
				$colItem = new XmlItem('Spalte');
				if (defined($titles[0][$nTitle]))
					$colItem->addSubItem(constant($titles[0][$nTitle]));
				else
					$colItem->addSubItem("");
				$rowItem->addSubItem($colItem);

				$resultSet->addSubItem($rowItem);
				
				$foundIds .= $data[0][$nTitle].'|';
			}
		}
		
		echo "T50";
		
		return $resultSet;
	}
	
	// *** Verbindung zu einer Datenbank testen ***
	function testConnection() {
		global $database, $errors, $host, $user, $pw;
		
		$xmlWriter = new SmallXmlWriter();
		$result = new XmlItem('ConnectionTest');
		$result->addSubItem($errors);
		
		// PHP-Version testen
		$phpTest = new XmlItem('PhpTest');
		if (phpversion() >= 4)
			$phpTest->addSubItem('true');
		else {
			$phpTest->addSubItem('false');			
			$phpTestError = new XmlItem('PhpTestError');
			$phpTestError->addSubItem('PHP Version kleiner als 4.0');
			$errors->addSubItem($phpTestError);
		}
		$result->addSubItem($phpTest);
		
		// Verbindung mit Datenbank testen
		$databaseTest = new XmlItem('DatabaseTest');
		if ($database) {
			$dbSession = createDbSession($database, $host, $user, $pw);
			if ($dbSession) {
				$databaseTest->addSubItem('true');
				closeDbSession($dbSession);
			}
			else
				$databaseTest->addSubItem('false');
		} else {
			$databaseTest->addSubItem('false');
			
			$databaseMissedError = new XmlItem('DatabaseMissedError');
			$databaseMissedError->addSubItem('Database wurde nicht �ber die URL �bergeben');
			$errors->addSubItem($databaseMissedError);
		}
		$result->addSubItem($databaseTest);
		
		// XML-String formatiert ausgeben
		$xmlWriter->writeItem($result);
	}

	// *** Verbindung zu einer Datenbank testen und HTML-Ausgaben anstatt XML erstellen***
	function testConnectionForUser() {
		global $database, $errors, $host, $user, $pw;
		
		echo '<html><body>';
		
		// PHP-Version testen
		echo 'Php Test: ';
		if (phpversion() >= 4)
			echo 'erfolgreich<br />';
		else
			echo 'nicht erfolgreich (PHP Version kleiner als 4.0)<br />';
		
		// Verbindung mit Datenbank testen
		echo '<br />Datenbank Test: ';
		if ($database) {
			$dbSession = createDbSession($database, $host, $user, $pw);
			if ($dbSession) {
				echo 'erfolgreich<br />';
				closeDbSession($dbSession);
			}
			else
				echo 'nicht erfolgreich (Verbindung fehlgeschlagen)<br />';
		} else {
			echo 'nicht erfolgreich (Datenbank wurde nicht �ber die URL �bergeben)<br />';
		}
		echo '</body></html>';
	}
	
	// *** Verbindung zu einer Datenbank herstellen ***
	function createDbSession($dbName, $dbHostname, $dbUser, $dbPW) 	{
		global $errors;
		
		@$dbSession = mysql_connect($dbHostname, $dbUser, $dbPW);
		
		if (!$dbSession) {
			$connectionError = new XmlItem('ConnectionError');
			$connectionError->addSubItem('Keine Verbindung m�glich: '.mysql_error());
			$errors->addSubItem($connectionError);
			
			return null;
		}
			
		if (!mysql_select_db($dbName, $dbSession)) {
			$databaseConnectionError = new XmlItem('DatabaseConnectionError');
			$databaseConnectionError->addSubItem('Auswahl der Datenbank fehlgeschlagen');
			$errors->addSubItem($databaseConnectionError);
			
			return null;
		}
			
		return $dbSession;
	}
	
	// *** Existierende Verbindung schlie�en ***
	function closeDbSession($dbSession) {
		// XtCommerce kann das aus unerkl�rlichen Gr�nden nicht 
		//mysql_close($dbSession);
	}
	
	
	
	// *** XmlWriter Klasse, die einen Baum von XmlItems als Xml formatiert ausgibt ***
	class SmallXmlWriter {
		var $_version;
		var $_header;
		
		// *** XmlWriter Objekt anlegen ***
		function SmallXmlWriter() {
			$this->_version = "1.0";
			$this->_header =  "<?xml version=\"$this->_version\"?>\n";
		}
		
		// *** Root-XmlItem schreiben ***
		function writeItem($xmlItem) {
			$this->writeXmlHeader();
			echo $xmlItem->getString();
		}

		// *** Xml-Header schreiben ***
		function writeXmlHeader() {
			echo $this->_header;
		}
	}

	// *** XmlItem, die der XmlWriter schreibt ***
	class XmlItem {
		var $_name;
		var $_attributes;
		var $_subItems;
		
		// *** XmlItem Objekt mit namen anlegen ***
		function XmlItem($name) {
			$this->_attributes = array();
			$this->_subItems = array();
			$this->_name = $name;
		}
		
		/* Subitems hinzuf�gen. 
		 * Durch angabe von nur einem Subitem, das kein XmlItem ist wird der String direkt rausgeschrieben.
		 * Bei mehreren Subitems, werden reine String-Items (nicht XmlItem) ignoriert.
		 */
		function addSubItem($subItem) {
			array_push($this->_subItems, $subItem);
		}
		
		// *** XmlAttribute hinzuf�gen ***
		function addAttribute($attribute) {
			array_push($this->_attributes, $attribute);
		}
		
		// *** Xml-String Formatierung zur�ckliefern ***
		function getString() {
			$output = "<$this->_name";

			// Attribute hinzuf�gen
			foreach ($this->_attributes as $attribute) {
				$output .= " $attribute->getString()";
			}
			
			if (count($this->_subItems) == 0)
				$output .= " />";
			else if (count($this->_subItems) == 1) {
				$output .= ">";
				
				if (strtolower(get_class($this->_subItems[0])) == 'xmlitem') {
					$value = "\n".$this->_subItems[0]->getString();
					$output .= str_replace("\n", "\n\t", $value)."\n";
				}
				else
					$output .= htmlspecialchars($this->_subItems[0], ENT_QUOTES);			

				$output .= "</$this->_name>";
			} else {
				$output .= ">";
				
				foreach ($this->_subItems as $subItem) {
					if (strtolower(get_class($subItem)) == 'xmlitem') {
						$value = $subItem->getString();
						$output .= str_replace("\n", "\n\t", "\n".$value);
					}
				}
				
				$output .= "\n</$this->_name>";
			}
			
			return $output;
		}
	}

	// *** XmlAttribute f�r ein XmlItem-Objekt ***
	class XmlAttribute {
		var $_name;
		var $_value;
		
		// *** Ein neues XmlAttribute Objekt mit Namen und Inhalt anlegen ***
		function XmlAttribute($name, $value) {
			$this->_name = $name;
			$this->_value = $value;
		}

		// *** String Formatierung f�r XmlItem zur�ckliefern ***
		function getString() {
			return $this->_name.'=\"'.htmlspecialchars($this->_value, ENT_QUOTES).'\"';
		}
	}

?>