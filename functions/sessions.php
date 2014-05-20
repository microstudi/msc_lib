<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Session functions file
 *
 * @category MSCLIB
 * @package Sessions
 * @author Ivan Vergés
 */

/**
 * Starts session
 *
 * Example:
 * <code>
 * m_session_start("MY_SESSION_NAME","data/sessions/");
 * m_register_error('access denied');
 * //errors are keep in session, from another page:
 * if(is_errors()) print_r(m_get_errors());
 * </code>
 *
 * If <b>$dir</b> is an array a Redis server could be specified:
 * <code>
 *        $dir = array(
 *        	'handler' => "redis",
 *        	'port' => redis_port,
 *        	'host' => "redis_host",
 *        	'password' => "redis_password",
 *        	'database' => "redis_database", //optional
 *        	'prefix' => 'sessions' //optional
 *        )
 * </code>
 *
 *       or a memcache server:
 * <code>
 *       $dir = array(
 *         'handler' => "memcache",
 *         'save_path' => "tcp://127.0.0.1:11211 , tcp://192.168.1.1:11211"
 *       )
 * </code>
 *
 *        or IronCache (http://www.iron.io/cache):
 * <code>
 *        $dir = array(
 *        	'handler' => "ironcache",
 *        	'token' => "token secret",
 *        	'project_id' => "project_id"
 *        )
 * </code>
 *
 * Example:
 * <code>
 * m_session_start("MY_SESSION_NAME", array('handler' => "memcache", 'save_path' => "tcp://127.0.0.1:11211"));
 * m_config_var("my_sticky_var", "foo", true);
 * //"my_sticky_var" is in session, from another page:
 * echo m_config_var("my_sticky_var");
 * </code>
 *
 * @param $name Session name
 * @param $dir where to save the session files (leave empty for default), tries to create it if not exists
 */
function m_session_start($name='', $dir='', $gc = array('gc_probability' => 1, 'gc_divisor' => 100, 'gc_maxlifetime' => 3600)) {
	global $CONFIG;
	$d = dirname(dirname(__FILE__));
	$start_session = true;
	foreach($gc as $k => $v) {
		ini_set("session.$k", $v);
	}
	if($dir) {
		if(is_array($dir)) {
			if($dir['handler'] == 'memcache') {
				if($dir['user']) {
					require_once($d . '/classes/MemcacheSASL.php');
					$m = new MemcacheSASL;
					$m->addServer($dir['server'], $dir['port'] ? $dir['port'] : 11211);
					$m->setSaslAuthData($dir['user'], $dir['password']);
					$m->setSaveHandler();
				}
				else {
					ini_set('session.save_handler', 'memcache');
					if($dir['save_path']) ini_set('session.save_path', $dir['save_path']);
				}
			}

			if($dir['handler'] == 'redis' && $dir['port'] && $dir['host']) {
				// SessionHandlerInterface
				if (!interface_exists('SessionHandlerInterface')) {
    				require_once($d . '/classes/SessionHandlerInterface.php');
				}
				require_once($d. '/classes/credis/Client.php');
				require_once($d. '/classes/credis/RedisSessionHandler.php');
				$redis = new Credis_Client($dir['host'], $dir['port'] ? $dir['port'] : 6379);
				if($dir['password']) $redis->auth($dir['password']);
				$sessHandler = new RedisSessionHandler($redis);
				if (version_compare(phpversion(), '5.4.0', '>=')) {
					session_set_save_handler($sessHandler);
				}
				else {
					session_set_save_handler(
					    array($sessHandler, 'open'),
					    array($sessHandler, 'close'),
					    array($sessHandler, 'read'),
					    array($sessHandler, 'write'),
					    array($sessHandler, 'destroy'),
					    array($sessHandler, 'gc')
					    );

					// the following prevents unexpected effects when using objects as save handlers
					register_shutdown_function('session_write_close');
				}
			}

			if($dir['handler'] == 'ironcache' && $dir['token'] && $dir['project_id']) {
				require_once($d . '/classes/iron/IronCore.class.php');
				require_once($d . '/classes/iron/IronCache.class.php');
				$cache = new IronCache(array(
					//'protocol' => "http", //can fix http exception http error 0 https://github.com/iron-io/iron_cache_php
				    'token' => $dir['token'],
				    'project_id' => $dir['project_id']
				));
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
		if($name) session_name($name);
		session_start();
	}

	if(empty($_SESSION['start'])) $_SESSION['start'] = time();

}

/**
 * Returns or sets any custom var to store in $CONFIG
 * @param $var string representing the var to store or retrive
 * @param $value if is null, then the current value will be returned, if not null then $var will be set
 * @param $in_session if <b>true</b> then var will be stored in session
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
 * @param $in_session if <b>true</b> deletes also the custom vars store in session
 */
function m_clean_custom_vars($in_session=false) {
	global $CONFIG;
	$CONFIG->custom_vars = array();
	if($in_session && $_SESSION['start']) {
		unset($_SESSION['custom_vars']);
	}
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
 * @param $register if <b>true</b> increments the register counter
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
 * @param $register if <b>true</b> increments the register counter
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
