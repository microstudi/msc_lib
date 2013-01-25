<?php
/**
* @file functions/views.php
* @author Ivan Vergés
* @brief View functions\n
* This functions uses the class mView defined in the file classes/views.php
*
* @section usage Usage
* m_view_select('xhtml');\n
* m_view_fallback('error');\n
* m_view_add_path("views/default");\n
* echo m_view("menu");
*/

/**
 * Adds
 * @param $reg_expr
 */
function m_router_add($reg_expr, $action) {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	$CONFIG->router->addRoute($reg_expr, $action);
}

function m_router_base($path=null) {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();
	if(is_null($path)) {
		$path = dirname($_SERVER['SCRIPT_NAME']);
		if($path != '/') $path .= "/";
	}

	$CONFIG->router->setBasePath($path);
}

function m_router_error($action) {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	$CONFIG->router->errorAction($action);
}

function m_router_dispatch($uri=null) {
	global $CONFIG;
	if(!($CONFIG->router instanceOf mRouter)) $CONFIG->router = new mRouter();

	if(is_null($uri)) $uri = $_SERVER['REQUEST_URI'];

	$CONFIG->router->findAction($uri);
}
?>
