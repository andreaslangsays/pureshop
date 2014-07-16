<?php
/*
  $Id: html_output.php,v 1.56 2003/07/09 01:15:48 hpdl Exp $

  ösCommerce, Öpen Source E-Commerce Solutions
  http://www.oscömmerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// The HTML href link wrapper function
  function push_href_link($page = '', $parameters = '', $connection = '', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $session_started, $SID;

    if (!push_not_null($page)) {
     // die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

    if ($connection == 'NONSSL') {
			$link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == true) {
        $link = HTTpush_SERVER . DIR_WS_HTTpush_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } else {
		if($_SERVER['SERVER_PORT']==443)
		{
			$link = HTTP_SERVER . DIR_WS_HTTpush_CATALOG;
		}
		else
		{
			$link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
		}
    }

    if (push_not_null($parameters)) {
      $link .= $page . '?' . push_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (push_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTpush_COOKIE_DOMAIN) {
          $_sid = push_session_name() . '=' . push_session_id();
        }
      }
    }

    if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    }

    if (isset($_sid)) {
      $link .= $separator . push_output_string($_sid);
    }
	if(strpos($link,'product_info.php')){
		$link = generate_url_rewritten_string($link);
	}
	if(strpos($link,'brands.php')){
		$link = generate_url_rewritten_string($link, "b");
	}
	if(strpos($link,'index.php')){
		$link = generate_url_rewritten_string($link,'c');
	}
	
	$link=str_replace( DIR_WS_HTTP_CATALOG . '..','',$link);
	
    return $link;
  }

////
// The HTML image wrapper function
  function push_image($src, $alt = '', $width = '', $height = '', $parameters = '') {

	// START STS v4.4:
//	global $sts;
//	$sts->image($src); // Take image from template folder if exists.
	// END STS v4.4

    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . push_output_string($src) . '" border="0" alt="' . push_output_string($alt) . '"';

    if (push_not_null($alt)) {
      $image .= ' title=" ' . push_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && push_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = intval($image_size[0] * $ratio);
        } elseif (push_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = intval($image_size[1] * $ratio);
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (push_not_null($width) && push_not_null($height)) {
      $image .= ' width="' . push_output_string($width) . '" height="' . push_output_string($height) . '"';
    }

    if (push_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function push_submit($value, $parameters = '') {
    global $language;

	$submit = '<input type="submit" value="' . $value . '"';
    if (push_not_null($parameters)) {
    	$submit .= ' ' . $parameters;
    }    
    $submit .= '>';

    return $submit;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function push_image_submit($image, $alt = '', $parameters = '') {
    global $language;

	// START STS v4.4:
//	global $sts;
//	$src = $sts->image_button($image,$language);
	if ($src!='')
	  $image_submit = '<input type="image" src="' . push_output_string($src) . '" border="0" alt="' . push_output_string($alt) . '"';
	else
	// END STS v4.4

    $image_submit = '<input type="image" src="' . push_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . push_output_string($alt) . '"';

    if (push_not_null($alt)) $image_submit .= ' title=" ' . push_output_string($alt) . ' "';

    if (push_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function push_image_button($image, $alt = '', $parameters = '') {
    global $language;

	// START STS v4.4:
//	global $sts;
//	$src = $sts->image_button($image, $language, true); // 3rd parameter to tell push_image that file check has been already done
	if ($src!='') { // Take image from template folder if exists.
	  return push_image ($src);
	}
	// END STS v4.4

    return push_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function push_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return push_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function push_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . push_output_string($name) . '" action="' . push_output_string($action) . '" method="' . push_output_string($method) . '"';

    if (push_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function push_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . push_output_string($type) . '" name="' . push_output_string($name) . '"';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . push_output_string(stripslashes($GLOBALS[$name])) . '"';
    } elseif (push_not_null($value)) {
      $field .= ' value="' . push_output_string($value) . '"';
    }

    if (push_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Output a form password field
  function push_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return push_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a selection field - alias function for push_draw_checkbox_field() and push_draw_radio_field()
  function push_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '', $class='') {
    $selection = '<input type="' . push_output_string($type) . '" name="' . push_output_string($name) . '"';

    if (push_not_null($value)) $selection .= ' value="' . push_output_string($value) . '"';

    if ( ($checked == true) || ( isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ( ($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value)) ) ) ) {
      $selection .= ' CHECKED';
    }

    if (push_not_null($parameters)) $selection .= ' ' . $parameters;
    $selection .= $class;
    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function push_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return push_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
/*  function push_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return push_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }*/
  function push_draw_radio_field($name, $value = '', $checked = false, $compare = '', $class = '') {
    return push_draw_selection_field($name, 'radio', $value, $checked, $compare, $class);
  }
////
// Output a form textarea field
  function push_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . push_output_string($name) . '" wrap="' . push_output_string($wrap) . '" cols="' . push_output_string($width) . '" rows="' . push_output_string($height) . '"';

    if (push_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= push_output_string_protected(stripslashes($GLOBALS[$name]));
    } elseif (push_not_null($text)) {
      $field .= push_output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function push_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . push_output_string($name) . '"';

    if (push_not_null($value)) {
      $field .= ' value="' . push_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name])) {
      $field .= ' value="' . push_output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (push_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Hide form elements
  function push_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && push_not_null($SID)) {
      return push_draw_hidden_field(push_session_name(), push_session_id());
    }
  }

////
// Output a form pull down menu
  function push_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . push_output_string($name) . '"';

    if (push_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . push_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id'] || strcmp($values[$i]['text'], 'ungemahlen') == 0) {
        $field .= ' SELECTED';
      }

      $field .= '>' . push_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Creates a pull-down list of countries
  function push_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = push_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return push_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }

//extra questionst end
function push_draw_radio_menu($name, $values, $default = '', $parameters = '', $required = false)
{
$field = '';
if (push_not_null($parameters)) $field .= ' ' . $parameters;
if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

for ($i=0, $n=sizeof($values); $i<$n; $i++) {
$field .= '<BR>';
$field .= '<input type="radio" value="' . push_output_string($values[$i]['id']) . '"';
$field .= ' name="' . push_output_string($name) . '"';
if (push_not_null($parameters)) $field .= ' ' . $parameters;

if ($default == $values[$i]['id']) {
$field .= ' checked';
}
 $field .= '>' . push_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }

if ($required == true) $field .= TEXT_FIELD_REQUIRED;
return $field;
}
//extra questionst end


?>
