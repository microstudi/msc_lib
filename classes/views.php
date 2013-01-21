<?php
/**
* @file classes/views.php
* @author Ivan Vergés
* @brief Template View class\n
* This class provides a method to define & return views from specified paths\n
* views ara php files in specified paths\n
* This class is used by the file functions/views.php
*
* @section usage Usage
* $view = new mView(array("views/my_custom_view","views/default_view"));\n
* echo $view->view("my_page");\n
*
*/

class mView {
	private $paths = array();
	public $last_error = '';

	/**
	 * Constructor, define the paths to search views
	 * @param $paths array with paths to search views
	 *
	 * */
	public function __construct ($paths=array()) {
		$this->paths = $paths;
	}

	/**
	 * Returns the requested view
	 * @param $view the requested view, this view will be search in all specified paths in order. The first existing $view.php will be returned
	 * @param $vars array with vars to be passed to the view (every view access this vars from the var \b $vars
	 * */
	public function view($view,$vars=array()) {
		if($view) {
			foreach($this->paths as $path) {
				$f = "$path/$view.php";
				if(is_file($f)) {
					//echo "$f\n";
					ob_start();
					require($f);
					$body = ob_get_clean();
					//if($view == 'error') die($view."-".$body);
					return $body;
				}
			}
		}
		$this->last_error = $view;
		return false;
	}
	/**
	 * Dynamically adds more paths where to search views
	 * */
	public function addPath($path,$prepend=true) {
		 if(!in_array($path,$this->paths)) {
			 if($prepend) array_unshift($this->paths,$path);
			 else $this->paths[] = $path;
		 }
	}
}

?>
