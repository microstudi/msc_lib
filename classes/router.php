<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Inspiration: http://cesar.la/mvc-hecho-en-casa-simple-lindo-y-efectivo.html
 *
 * @category MSCLIB
 * @package Routing
 * @author Ivan Vergés
 */

/**
 * mRouter connection class
 *
 *
 * Example
 * <code>
 * $db = new mRouter();
 * $db->addRoute('/user\/([0-9]+)$/i', "user_controller"); //where user_controller is a function
 * $db->addRoute('/user\/([0-9]+)$/i', "file_controller.php"); //file_controler.php is a file
 * </code>
 *
 */
class mRouter {
	private $route = array();
	private $base_path = '';
	private $current_url = null;
	private $error_action = null;
	private $last_error = '';
	private $action = '';
	private $result = '';


	function setBasePath($path) {
		$this->base_path = $path;
	}
    function addRoute($reg_expr, $action, $action_string = null) {
		if (!is_callable($action) && !is_file($action))
            return false;

        $this->route[$reg_expr] = array($action, $action_string);
    }
    function getRoutes() {
    	return $this->route;
    }

    function findAction($url) {
    	//url without base path
		$url = preg_replace("/^" . str_replace("/", "\/", quotemeta($this->base_path)) . "/", "", $url);
		//url without query part
		$this->current_url = $url;
		if($url{0} != '?') $url = strtok($url, "?");
		else $url = '';
		// echo "[$url]";
        foreach($this->route as $reg_expr => $acts) {
        	$action = $acts[0];
        	$action_string = $acts[1] ? $acts[1] : $action;
        	//exact match
			if($reg_expr == $url) {
				$this->action = $action_string;
			    $ret = true;
                if (is_callable($action)) {
                    $ret = call_user_func($action);
                    $this->result = $ret;
                }
                else {
                	ob_start(); //callback error ?
                    include($action);
                    $this->result = ob_get_contents();
                    ob_end_clean();
                }
                // echo "match [$reg_expr] [$url] [$action_string]\n";
                return $url; //return the url match

			}
            elseif (@preg_match($reg_expr, $url, $vars)) {
            	$this->action = $action_string;
                $ret = true;
                if (is_callable($action)) {
                    $ret = call_user_func($action, $vars);
                    $this->result = $ret;
                }
                else {
                    ob_start(); //callback error ?
                    include($action);
                    $this->result = ob_get_contents();
                    ob_end_clean();
                }
                return $url; //return the matched patterns
            }
        }
        if (!isset($ret) && !$ret) {
            $this->throwError('action_not_found');
        	return false;
    	}
    	return '';
    }

    function errorAction($action) {
		if (!is_callable($action) && !is_file($action))
            return false;

		$this->error_action = $action;

	}

	function current_action() {
		return $this->action;
	}
	function action_result() {
		return $this->result;
	}

    /**
	 * Show the last error
	 */
	function getError() {
		return $this->last_error;
	}
	/**
	 * throw errors
	 */
	function throwError($msg='') {
		$this->last_error = $msg;

		if(!is_null($this->error_action)) {

			header("HTTP/1.1 404 Not Found");
			$url = $this->current_url;
			if(is_callable($this->error_action)) {
				$ret = call_user_func($this->error_action);
				$this->result = $ret;
			}
			else {
                ob_start(); //callback error ?
				include($this->error_action);
	            $this->result = ob_get_contents();
	            ob_end_clean();
			}
		}
		else {
			throw new Exception($msg);
		}
		//die($msg);
	}
}
