<?php
/**
* @file functions/sessions.php
* @author Ivan Vergés
* @brief Session functions file\n
*
* @section usage Usage
* m_session_start("MY_SESSION_NAME","data/sessions/");\n
* m_register_error('access denied');\n
* if(is_errors()) print_r(m_get_errors());\n
*
*/

/**
* Starts session
* @param $name Session name
* @param $dir where to save the session files (leave empty for default), tries to create it if not exists
*/
function m_session_start($name='',$dir='') {
	global $CONFIG;
	if($dir) {
		if(!is_dir($dir)) {
			@mkdir($dir);
		}
		if(is_dir($dir)) {
			session_save_path($dir);
			ini_set('session.gc_probability', 1);
			ini_set('session.gc_divisor', 100);
			ini_set('session.gc_maxlifetime',   3600);  // one hour time limit
		}
	}
	if($name) session_name($name);
	session_start();
	if(empty($_SESSION['start'])) $_SESSION['start'] = time();

}
/**
 * generates a unique hash using passed vars
 * @param $id
 * @param $time
 * */
function m_token($id,$time) {
	return md5($id.$time.$_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR']);
}
/**
 * Checks if exists messages in $CONFIG
 * @return true or false
 * */
function m_is_msgs() {
	if(!is_array($_SESSION['msgs'])) return false;
	if(count($_SESSION['msgs'])==0) return false;
	return true;
}
/**
 * Returns messages stored in $CONFIG
 * @return array of messages
 * */
function m_get_msgs() {
	if(!is_array($_SESSION['msgs'])) return array();
	return $_SESSION['msgs'];
}
/**
 * Stores a new message in $CONFIG
 * @param $msg message string to store
 * @param $register if \b true increments the register counter
 * */
function m_register_msg($msg,$register=false) {
	if(!is_array($_SESSION['msgs'])) $_SESSION['msgs'] = array();
	$_SESSION['msgs'][] = $msg;
	if($register) {
		if(empty($_SESSION['num_msgs'])) $_SESSION['num_msgs'] = 0;
		$_SESSION['num_msgs']++;
	}
	//print_r($_SESSION);
}
/**
 * Deletes all existing messages in $CONFIG
 * */
function m_clean_msgs() {
	$_SESSION['msgs'] = array();
	$_SESSION['num_msgs'] = 0;
	unset($_SESSION['msgs']);
}

/**
 * Checks if exists errors in $CONFIG
 * @return true or false
 * */
function m_is_errors() {
	if(!is_array($_SESSION['errors'])) return false;
	if(count($_SESSION['errors'])==0) return false;
	return true;
}
/**
 * Checks if exists errors in $CONFIG
 * @return array of errors
 * */
function m_get_errors() {
	if(!is_array($_SESSION['errors'])) return array();
	return $_SESSION['errors'];
}
/**
 * Stores a error message in $CONFIG
 * @param $msg error message string to store
 * @param $register if \b true increments the register counter
 * */
function m_register_error($msg,$register=false) {
	if(!is_array($_SESSION['errors'])) $_SESSION['errors'] = array();
	$_SESSION['errors'][] = $msg;
	if($register) {
		if(empty($_SESSION['num_errors'])) $_SESSION['num_errors'] = 0;
		$_SESSION['num_errors']++;
	}
	//print_r($_SESSION);
}
/**
 * Deletes all existing error messages in $CONFIG
 * */
 function m_clean_errors() {
	$_SESSION['errors'] = array();
	$_SESSION['num_errors'] = 0;
	unset($_SESSION['errors']);
}

?>
