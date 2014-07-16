<?php
//die('advsrcxxx!!?"!!""!WTF!?!?');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);

// ##Begin fehlertolerante Suche mit Search enhancement mod
if(isset($_GET['keywords']) && $_GET['keywords'] != '')
 {
 $doppelt=stripslashes($_GET['keywords']); //wird zum Vergleichen benoetigt damit er nicht doppelt ersetzt
//kuerzen der Keywords um or and etc
 $ersterfilter = stripslashes(strtolower($_GET['keywords']));
 $ersterfilter=str_replace(' or','',$ersterfilter);
 $ersterfilter=str_replace(' and','',$ersterfilter);
 $ersterfilter=str_replace(' +','',$ersterfilter);
 $ersterfilter=str_replace(' &','',$ersterfilter);

 $pw_keywords = explode(' ',$ersterfilter);
 $pw_replacement_words = $pw_keywords;
 $pw_boldwords = $pw_keywords;
 $sql_words = push_db_query("SELECT * FROM " . TABLE_SEARCHWORD_SWAP);
 $pw_replacement = '';


 while ($sql_words_result = push_db_fetch_array($sql_words))
      {
       if(stripslashes(strtolower($_GET['keywords'])) == stripslashes(strtolower($sql_words_result['sws_word'])))
         {
          $pw_replacement = stripslashes($sql_words_result['sws_replacement']);
          $pw_link_text = '<b><i>' . stripslashes($sql_words_result['sws_replacement']) . '</i></b>';
           $pw_phrase = 1;
       	   $pw_mispell = 1;
           break;
       	 }
        for($i=0; $i<sizeof($pw_keywords); $i++)
          {
           if($pw_keywords[$i]  == stripslashes(strtolower($sql_words_result['sws_word'])))
             {
              $pw_replacement_words[$i] = stripslashes($sql_words_result['sws_replacement']);
              $pw_boldwords[$i] = '<b><i>' . stripslashes($sql_words_result['sws_replacement']) . '</i></b>';
              $pw_mispell = 1;
              break;
             }
           }
      }
  $keineanzeige=0;
  //Wenn das letzte Zeichen ein Leerzeichen ist dann loeschen
  if (substr($doppelt,-1)==' ')
    $doppelt = substr_replace($doppelt,'',-1);
  if(!isset($pw_phrase))
    {
      for($i=0; $i<sizeof($pw_keywords); $i++)
        {
         //Kontrolle das Suchwort nicht identisch ist mit Eingestzten Wort
         if ($pw_replacement_words[$i] == $doppelt)
           {
              $i=sizeof($pw_keywords);
              $keineanzeige=1;
           }
           else
           {
           $pw_replacement .= $pw_replacement_words[$i] . ' ';
                  $pw_link_text   .= $pw_boldwords[$i]. ' ';
           }
        }
     }
 $pw_replacement = trim($pw_replacement);
 $pw_link_text   = trim($pw_link_text);
 $pw_link_text= str_replace(' or ',' (oder) ',$pw_link_text);
 // Wenn Suchwort nicht identisch ist mit dem Ersatzwort dann Anzeigen
 // $pw_string wird product_listing uebergeben
 if ($keineanzeige==0)
  {
   $pw_string      = '<br><span class="main"><font color="red">' .    TEXT_REPLACEMENT_SUGGESTION1 . '</font><a href="' . push_href_link( FILENAME_ADVANCED_SEARCH_RESULT , 'keywords' . urlencode($pw_replacement) . '' ) . '">' . $pw_link_text . '</a></span><font color="red">'. TEXT_REPLACEMENT_SUGGESTION2.'</font></span><br>';
  }
  else
  {
  $pw_string ='';
  }

}
// ##Ende fehlertolerante Suche mit Search enhancement mod

  $error = false;

  if ( (isset($_GET['keywords']) && empty($_GET['keywords'])) &&
       (isset($_GET['dfrom']) && (empty($_GET['dfrom']) || ($_GET['dfrom'] == DOB_FORMAT_STRING))) &&
       (isset($_GET['dto']) && (empty($_GET['dto']) || ($_GET['dto'] == DOB_FORMAT_STRING))) &&
       (isset($_GET['pfrom']) && !is_numeric($_GET['pfrom'])) &&
       (isset($_GET['pto']) && !is_numeric($_GET['pto'])) ) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  } else {
    $dfrom = '';
    $dto = '';
    $pfrom = '';
    $pto = '';
    $keywords = '';

    if (isset($_GET['dfrom'])) {
      $dfrom = (($_GET['dfrom'] == DOB_FORMAT_STRING) ? '' : $_GET['dfrom']);
    }

    if (isset($_GET['dto'])) {
      $dto = (($_GET['dto'] == DOB_FORMAT_STRING) ? '' : $_GET['dto']);
    }

    if (isset($_GET['pfrom'])) {
      $pfrom = $_GET['pfrom'];
    }

    if (isset($_GET['pto'])) {
      $pto = $_GET['pto'];
    }

    if (isset($_GET['keywords'])) {
      $keywords = push_db_prepare_input($_GET['keywords']);
    }

    $date_check_error = false;
    if (push_not_null($dfrom)) {
      if (!push_checkdate($dfrom, DOB_FORMAT_STRING, $dfrom_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_FROM_DATE);
      }
    }

    if (push_not_null($dto)) {
      if (!push_checkdate($dto, DOB_FORMAT_STRING, $dto_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_TO_DATE);
      }
    }

    if (($date_check_error == false) && push_not_null($dfrom) && push_not_null($dto)) {
      if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
        $error = true;

        $messageStack->add_session('search', ERROR_TO_DATE_LESS_THAN_FROM_DATE);
      }
    }

    $price_check_error = false;
    if (push_not_null($pfrom)) {
      if (!settype($pfrom, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_FROM_MUST_BE_NUM);
      }
    }

    if (push_not_null($pto)) {
      if (!settype($pto, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_MUST_BE_NUM);
      }
    }

    if (($price_check_error == false) && is_float($pfrom) && is_float($pto)) {
      if ($pfrom >= $pto) {
        $error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_LESS_THAN_PRICE_FROM);
      }
    }

    if (push_not_null($keywords)) {
      if (!push_parse_search_string($keywords, $search_keywords)) {
        $error = true;

        $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
      }
    }
  }

  if (empty($dfrom) && empty($dto) && empty($pfrom) && empty($pto) && empty($keywords)) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  }

  if ($error == true) {
    push_redirect(push_href_link(FILENAME_ADVANCED_SEARCH, push_get_all_get_params(), 'NONSSL', true, false));
	
  } else if (push_session_is_registered('customer_id')) {
	 	// save the keywords in database
		
		define('NUMBER_OF_KEYWORDS', 10);
		
		$history_q = push_db_query("	SELECT 	keywords
									FROM 	customers_search_history
									WHERE 	customers_id = " . (int)$_SESSION['customer_id']);
	
		if (push_db_num_rows($history_q) == 0) {
			// create a new history for the user
			push_db_query("	INSERT INTO customers_search_history (customers_id, keywords) 
							VALUES 		(" . (int)$_SESSION['customer_id'] . ", '" . $keywords . "')");
							
		} else {
			$history = push_db_fetch_array($history_q);
			$historyKeywords = explode(";", $history['keywords']);
			
			if (!in_array($keywords, $historyKeywords)) {
				// keyword not in the history
				
				// add new keyword at the beginning of the list
				array_unshift($historyKeywords, $keywords);
				
				if (sizeof($historyKeywords) > NUMBER_OF_KEYWORDS) {
					// remove the last (oldest) element of the list
					array_pop($historyKeywords);
				}				
			} else {
				// keyword in the history - move it t the beginning
				$key = array_search($keywords, $historyKeywords);
				unset($historyKeywords[$key]);
				array_unshift($historyKeywords, $keywords); 
			}
			
			// update the history
			$newHistory = implode(";", $historyKeywords);
			push_db_query("	UPDATE 	customers_search_history 
							SET 	keywords = '" . $newHistory . "'");
		}
  }
// ##Begin fehlertolerante Suche mit Search enhancement mod
                $search_enhancements_keywords = $_GET['keywords'];
                $search_enhancements_keywords = strip_tags($search_enhancements_keywords);
                $search_enhancements_keywords = addslashes($search_enhancements_keywords);
                if ($search_enhancements_keywords != $last_search_insert) {
                        push_db_query("insert into  " . TABLE_SEARCH_QUERIES . "  (search_text)  values ('" .  $search_enhancements_keywords . "')");
                        push_session_register('last_search_insert');
                        $last_search_insert = $search_enhancements_keywords;
                }
// ##Ende fehlertolerante Suche mit Search enhancement mod


/*
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
  $error = false;

  if ( (isset($_GET['keywords']) && empty($_GET['keywords'])) &&
       (isset($_GET['dfrom']) && (empty($_GET['dfrom']) || ($_GET['dfrom'] == DOB_FORMAT_STRING))) &&
       (isset($_GET['dto']) && (empty($_GET['dto']) || ($_GET['dto'] == DOB_FORMAT_STRING))) &&
       (isset($_GET['pfrom']) && !is_numeric($_GET['pfrom'])) &&
       (isset($_GET['pto']) && !is_numeric($_GET['pto'])) ) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  } else {
    $dfrom = '';
    $dto = '';
    $pfrom = '';
    $pto = '';
    $keywords = '';

    if (isset($_GET['dfrom'])) {
      $dfrom = (($_GET['dfrom'] == DOB_FORMAT_STRING) ? '' : $_GET['dfrom']);
    }

    if (isset($_GET['dto'])) {
      $dto = (($_GET['dto'] == DOB_FORMAT_STRING) ? '' : $_GET['dto']);
    }

    if (isset($_GET['pfrom'])) {
      $pfrom = $_GET['pfrom'];
    }

    if (isset($_GET['pto'])) {
      $pto = $_GET['pto'];
    }

    if (isset($_GET['keywords'])) {
      $keywords = $_GET['keywords'];
    }

    $date_check_error = false;
    if (push_not_null($dfrom)) {
      if (!push_checkdate($dfrom, DOB_FORMAT_STRING, $dfrom_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_FROM_DATE);
      }
    }



    if (push_not_null($dto)) {
      if (!push_checkdate($dto, DOB_FORMAT_STRING, $dto_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_TO_DATE);
      }
    }

    if (($date_check_error == false) && push_not_null($dfrom) && push_not_null($dto)) {
      if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
        $error = true;

        $messageStack->add_session('search', ERROR_TO_DATE_LESS_THAN_FROM_DATE);
      }
    }

    $price_check_error = false;
    if (push_not_null($pfrom)) {
      if (!settype($pfrom, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_FROM_MUST_BE_NUM);
      }
    }

    if (push_not_null($pto)) {
      if (!settype($pto, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_MUST_BE_NUM);
      }
    }

    if (($price_check_error == false) && is_float($pfrom) && is_float($pto)) {
      if ($pfrom >= $pto) {
        $error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_LESS_THAN_PRICE_FROM);
      }
    }

    if (push_not_null($keywords)) {
      if (!push_parse_search_string($keywords, $search_keywords)) {
        $error = true;

        $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
      }
    }
  }

  if (empty($dfrom) && empty($dto) && empty($pfrom) && empty($pto) && empty($keywords)) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  }

  if ($error == true) {
    push_redirect(push_href_link(FILENAME_ADVANCED_SEARCH, push_get_all_get_params(), 'NONSSL', true, false));
	
  } else if (push_session_is_registered('customer_id')) {
	 	// save the keywords in database
		
		define('NUMBER_OF_KEYWORDS', 10);
		
		$history_q = push_db_query("	SELECT 	keywords
									FROM 	customers_search_history
									WHERE 	customers_id = " . (int)$_SESSION['customer_id']);
	
		if (push_db_num_rows($history_q) == 0) {
			// create a new history for the user
			push_db_query("	INSERT INTO customers_search_history (customers_id, keywords) 
							VALUES 		(" . (int)$_SESSION['customer_id'] . ", '" . $keywords . "')");
							
		} else {
			$history = push_db_fetch_array($history_q);
			$historyKeywords = explode(";", $history['keywords']);
			
			if (!in_array($keywords, $historyKeywords)) {
				// keyword not in the history
				
				// add new keyword at the beginning of the list
				array_unshift($historyKeywords, $keywords);
				
				if (sizeof($historyKeywords) > NUMBER_OF_KEYWORDS) {
					// remove the last (oldest) element of the list
					array_pop($historyKeywords);
				}				
			} else {
				// keyword in the history - move it t the beginning
				$key = array_search($keywords, $historyKeywords);
				unset($historyKeywords[$key]);
				array_unshift($historyKeywords, $keywords); 
			}
			
			// update the history
			$newHistory = implode(";", $historyKeywords);
			push_db_query("	UPDATE 	customers_search_history 
							SET 	keywords = '" . $newHistory . "'");
		}
  }
?>
*/
?>