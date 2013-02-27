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
*        if is array could be a redis server:
*        $dir = array(
*        	'handler' => "redis",
*        	'port' => redis_port,
*        	'host' => "redis_host",
*        	'password' => "redis_password",
*        	'database' => "redis_database", //optional
*        	'prefix' => 'sessions' //optional
*        )
*        or a IronCache (http://www.iron.io/cache)
*        $dir = array(
*        	'handler' => "ironcache",
*        	'token' => "token secret",
*        	'project_id' => "project_id"
*        )
*        //to be added: dynamodb
*/
function m_session_start($name='', $dir='', $gc = array('gc_probability' => 1, 'gc_divisor' => 100, 'gc_maxlifetime' => 3600)) {
	global $CONFIG;

	$start_session = true;
	if($dir) {
		if(is_array($dir)) {
			if($dir['handler'] == 'redis' && $dir['port'] && $dir['host']) {
				// SessionHandlerInterface
				if (!interface_exists('SessionHandlerInterface')) {
    				require_once(dirname(__DIR__) . "/classes/SessionHandlerInterface.php");
				}
   				require_once(dirname(__DIR__) . "/classes/predis/autoload.php");

   				// Instantiate a new client just like you would normally do. We'll prefix our session keys here.
				$client = new Predis\Client(array('host' => $dir['host'], 'port' => $dir['port'], 'password' => $dir['password'], 'database' => $dir['database']), array('prefix' => $dir['prefix'] ? $dir['prefix'] : 'm_sessions:'));
				// Set gc_ vars
				$handler = new Predis\Session\SessionHandler($client, $gc);
				// Register our session handler (it uses `session_set_save_handler()` internally).
				$handler->register();
			}
			if($dir['handler'] == 'ironcache' && $dir['token'] && $dir['project_id']) {
				require_once(dirname(__DIR__) . "/classes/iron/IronCore.class.php");
				require_once(dirname(__DIR__) . "/classes/iron/IronCache.class.php");
				$cache = new IronCache(array(
					//'protocol' => "http", //can fix http exception http error 0 https://github.com/iron-io/iron_cache_php
				    'token' => $dir['token'],
				    'project_id' => $dir['project_id']
				), 'm_sessions');
				$cache->ssl_verifypeer = false; //to fix http exception http error 0
				//fixes a problem with the ironcache class and php 5.4
				register_shutdown_function('session_write_close');
				//register session handler
				$cache->set_as_session_store($gc['gc_maxlifetime']);
			}
		}
		else {
			if(!is_dir($dir)) {
				@mkdir($dir);
			}
			if(is_dir($dir)) {
				session_save_path($dir);
			}
		}
	}

	if($start_session) {
		foreach($gc as $k => $v) {
			ini_set("session.$k", $v);
		}
		if($name) session_name($name);
		session_start();
	}

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
