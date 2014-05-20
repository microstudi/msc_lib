<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * View functions
 * This functions uses the class mView defined in the file classes/views.php
 *
 * @category MSCLIB
 * @package Views
 * @author Ivan Vergés
 */

/**
 * Adds custom paths to store views
 *
 *
 * @param $type the view to use
 */
function m_view_select($type) {
	global $CONFIG;
	$CONFIG->default_view = $type;

	$paths = array($CONFIG->views_path.'/'.$CONFIG->default_view);

	$CONFIG->view = new mView($paths);

}

/**
 * Adds custom paths to store views
 * @param $dir adds this dir to search views
 * @param $prepend if true, the path will be added on top of the list, this means it will be the first place to search de view
 */
function m_view_add_path($dir,$prepend=true) {
	global $CONFIG;
	if(!($CONFIG->view instanceOf mView)) {
		m_view_select($CONFIG->default_view);
	}
	if(is_dir($dir)) {
		$CONFIG->view->addPath(realpath($dir),$prepend);
	}
}

/**
 * Returns the view
 *
 * Example:
 * <code>
 * m_view_select('html5');
 * m_view_fallback('error');
 * m_view_add_path("views/default");
 * echo m_view("menu");
 * </code>
 *
 * @param $view the view to search, $view.php will be searched in all view paths, the first found will be returned
 * @param $vars array of pairs => values to send to the $view.php
 * @param $silent returns empty if not views are found (otherwises returns a error message or the fallback view page if configured)
 */
function m_view($view='',$vars=array(),$silent=false) {
	global $CONFIG;

	if(!($CONFIG->view instanceOf mView)) {
		m_view_select($CONFIG->default_view);
	}

	$body = $CONFIG->view->view($view,$vars);

	if($body===false && !$silent) {
		if($CONFIG->view_fallback && $CONFIG->view_fallback!=$view) {
			//$body = "$view-".$CONFIG->view_fallback;
			$body = $CONFIG->view->view($CONFIG->view_fallback,$vars);
		}
		else {
			$body = "TEMPLATE $view NOT FOUND\n";
		}
	}

	return $body;
}

/**
 * Sets a fallback template for the not found views
 * @param $template the error view used as fallback
 * */
function m_view_fallback($template=null) {
	global $CONFIG;
	if(!is_null($template)) $CONFIG->view_fallback = $template;

	return $CONFIG->view_fallback;
}

/**
 * Returns the last error from the view (if any)
 * */
function m_view_error() {
	global $CONFIG;
	//echo "view error: ".$CONFIG->view->last_error."<br/>";
	return $CONFIG->view->last_error;
}
