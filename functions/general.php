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

/**
 * Sends a header for non-caching
 * @return [type] [description]
 */
function m_no_cache() {
  header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
}

function m_lib_version() {
    global $CONFIG;
    return $CONFIG->version;
}

/**
 * Retrieves a safe temp dir
 * @return [type] [description]
 */
function m_temp_dir() {
    return ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
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
 * Detect if current browser is mobile
 * from http://detectmobilebrowsers.com/
 * @return [type] [description]
 */
function m_is_mobile() {
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)));

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
