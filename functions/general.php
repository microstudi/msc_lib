<?php
/**
* @file functions/general.php
* @author Ivan Vergés
* @brief some usefull general functions file\n
*
*/

/**
 * Use the header to redirect to a specific path
 * @param $path where to go (eg: http://www.google.com)
 */
function m_forward($path) {
	global $CONFIG;

	header("Location: $path");
	exit;
}

function m_lib_version() {
    global $CONFIG;
    return $CONFIG->version;
}
/**
 * Returns a var from superglobals vars _REQUEST, _GET, _POST
 * @param $var var string to search in superglobals
 * @param $default default value if $var is not set (empty var returns empty)
 * @param $globals array with then name of globals to search vars
 */
function m_input($var,$default='',$globals = array('_GET','_POST', '_REQUEST')) {
	global $CONFIG;

	$v = null;
	foreach($globals as $g) {
		switch($g) {
			case '_GET': if(isset($_GET[$var])) $v = $_GET[$var];
				break;
            case '_POST': if(isset($_POST[$var])) $v = $_POST[$var];
                break;
            case '_REQUEST': if(isset($_REQUEST[$var])) $v = $_REQUEST[$var];
                break;
            case '_COOKIE': if(isset($_COOKIE[$var])) $v = $_COOKIE[$var];
                break;
            case '_SESSION': if(isset($_SESSION[$var])) $v = $_SESSION[$var];
                break;
		}
		if($v) break;
	}
	$v = m_stripslashes($v);
	if(!isset($v) && $default) $v = $default;
	return $v;
}

/**
 * Combines 2 arrays (similar to array_merge_recursive but without
 * @param multidimensional array1, array2, array3, etc
 *
 * si el primer parametre es un numero, només es farà recursió fins a aquell numero
 * */
function m_array_merge_recursive() {
	$arrays = func_get_args();
	$base = array_shift($arrays);
	if(is_numeric($base)) {
		$max_recursion = $base;
		$base = array_shift($arrays);
	}
	foreach ($arrays as $array) {
		reset($base); //important
		while (list($key, $value) = @each($array)) {
			//normal, infinite recursion
			if (is_array($value) && @is_array($base[$key]) && !isset($max_recursion)) {
				$base[$key] = m_array_merge_recursive($base[$key], $value);
			}
			//recursion limited
			elseif (is_array($value) && @is_array($base[$key]) && $max_recursion>1) {
				$base[$key] = m_array_merge_recursive($max_recursion-1,$base[$key], $value);
			}
			else {
				$base[$key] = $value;
				//echo "recursio: $max_recursion key: $key\n";
			}
		}
	}

	return $base;
}

/**
 * Returns or sets any custom var to store in $CONFIG
 * @param $var string representing the var to store or retrive
 * @param $value if is null, then the current value will be returned, if not null then $var will be set
 * @param $in_session if \b true then var will be stored in session
 */
function m_config_var($var,$value=null,$in_session=false) {
	global $CONFIG;

	if($_SESSION['start']) {
		if(!is_array($_SESSION['custom_vars'])) $_SESSION['custom_vars'] = array();
		if($_SESSION['custom_vars'][$var]) $CONFIG->custom_vars[$var] = $_SESSION['custom_vars'][$var];
	}

	if(!is_null($value)) {
		$CONFIG->custom_vars[$var] = $value;

		if($in_session && $_SESSION['start']) {
			if(!is_array($_SESSION['custom_vars'])) $_SESSION['custom_vars'] = array();
			$_SESSION['custom_vars'][$var] = $value;
		}
	}

	return $CONFIG->custom_vars[$var];
}

/**
 * Deletes a custom var from $CONFIG
 * @param $var the name of the var to delete
 * */
function m_delete_config_var($var) {
	global $CONFIG;

	unset($CONFIG->custom_vars[$var]);
	if($_SESSION['start']) {
		if($_SESSION['custom_vars'][$var]) {
			unset($_SESSION['custom_vars'][$var]);
		}
	}

	return true;
}
/**
 * Clean/deletes all the custom vars
 * @param $in_session if \b true deletes also the custom vars store in session
 */
function m_clean_custom_vars($in_session=false) {
	global $CONFIG;
	$CONFIG->custom_vars = array();
	if($in_session && $_SESSION['start']) {
		unset($_SESSION['custom_vars']);
	}
}

/**
 * From: http://www.php.net/manual/en/function.get-browser.php#101125
 * $ua = m_get _browser();
 * $yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
 * print_r($yourbrowser);
 * */
function m_get_browser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}
