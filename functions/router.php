<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Router functions
 * This functions uses the class mView defined in the file classes/router.php
 *
 * @category MSCLIB
 * @package Routing
 * @author Ivan Vergés
 */

/**
 * Adds action that processes a url
 *
 *  @uses mRouter
 *
 * @param $reg_expr
 */
function m_router_add($reg_expr, $action, $action_string = null) {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	$CONFIG->router->addRoute($reg_expr, $action, $action_string);
}

/**
 * Sets the base url for matching actions in url
 *
 * @uses mRouter
 *
 * @param  [type] $path [description]
 * @return [type]       [description]
 */
function m_router_base($path=null) {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();
	if(is_null($path)) {
		$path = dirname($_SERVER['SCRIPT_NAME']);
		if($path != '/') $path .= '/';
	}

	$CONFIG->router->setBasePath($path);
}


/**
 * Return the current rules
 *
 * @uses mRouter
 *
 */
function m_router_get_rules() {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	return $CONFIG->router->getRoutes();
}

/**
 * Configure the fallback if none actions found
 *
 * @uses mRouter
 *
 * @param  [type] $action [description]
 * @return [type]         [description]
 */
function m_router_error($action) {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	$CONFIG->router->errorAction($action);
}

/**
 * Executes the router action
 *
 * @uses mRouter
 *
 * @param  [type] $uri [description]
 * @return misc      false on failure, matched URI on success
 */
function m_router_dispatch($uri=null) {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	if(is_null($uri)) $uri = $_SERVER['REQUEST_URI'];

	return $CONFIG->router->findAction($uri);
}

/**
 * Returns the current action, empty if none
 *
 * @uses mRouter
 *
 * @return [type] [description]
 */
function m_router_current_action() {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	return $CONFIG->router->current_action();
}

/**
 * Returns the action result, empty if none
 *
 * @uses mRouter
 *
 * @return [type] [description]
 */
function m_router_result() {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	return $CONFIG->router->action_result();
}