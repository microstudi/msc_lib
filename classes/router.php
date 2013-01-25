<?php
/**
* @file classes/router.php
* @author Ivan Vergés
* @brief mRouter connection class\n
* Inspiration: http://cesar.la/mvc-hecho-en-casa-simple-lindo-y-efectivo.html
*
* @section usage Usage
* $db = new mRouter();\n
* $db->addRoute('/user\/([0-9]+)$/i', "user_controller");\n //where user_controller is a function
* $db->addRoute('/user\/([0-9]+)$/i', "file_controller.php");\n //file_controler.php is a file
*
*/

class mRouter {
	private $route = array();
	private $base_path = '';
	private $current_url = null;
	private $error_action = null;
	private $last_error = '';


	function setBasePath($path) {
		$this->base_path = $path;
	}
    function addRoute($reg_expr, $action) {
		if (!is_callable($action) && !is_file($action))
            return false;

        $this->route[$reg_expr] = $action;
    }

    function findAction($url) {
    	//url without base path
		$url = preg_replace("/^" . str_replace("/", "\/", quotemeta($this->base_path)) . "/", "", $url);
		//url without query part
		$this->current_url = $url;
		$url = strtok($url, "?");
		// echo "[$url]";
        foreach($this->route as $reg_expr => $action) {
			if($reg_expr == $url) {
			    $ret = true;
                if (is_callable($action))
                    $ret = call_user_func($action);
                else
                    include($action);
                return true;

			}
            elseif (@preg_match($reg_expr, $url, $vars)) {
                $ret = true;
                if (is_callable($action))
                    $ret = call_user_func($action, $vars);
                else
                    include($action);
                return true;
            }
        }
        if (!isset($ret) && !$ret)
            $this->throwError('action_not_found');
        return false;

    }

    function errorAction($action) {
		if (!is_callable($action) && !is_file($action))
            return false;

		$this->error_action = $action;

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

			if(is_callable($this->error_action)) $ret = call_user_func($this->error_action);
			else include($this->error_action);
		}
		else {
			throw new Exception($msg);
		}
		//die($msg);
	}
}

?>
