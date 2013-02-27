<?php
/**
 * @file classes/CSSCompressor.php
 * Merges every .css-file in directory/ies $dirs
 * into one file and compresses them
 * also parses $constants
 *
 * */
 /**
 * @class CSSCompressor
 */
class CSSCompressor {
	private $cache_dir = null;
	private $files = array();
	private $cache_name = null;

	private $code;
	private $constantSelectors = array();
	private $last_error = '';

	/**
	 * Constructor, sets cache and dirs
	 *
	 * @method __construct
	 */
	public function __construct($dirs=null,$cache_dir=null) {
		if($cache_dir) $this->set_cache_dir($cache_dir);
		if($dirs) $this->setDirs($dirs);
	}

	/**
	 * Set up the cache dir
	 * */
	function set_cache_dir($dir) {
		if(!@is_dir($dir)) {
			if(mkdir($dir)) chmod($dir,0777);
		}
		if(@is_writeable($dir)) {
			$this->cache_dir = realpath($dir).'/';
			return true;
		}
		else {
			$this->throwError("$dir is not writeable for cache!",true);
		}
	}
	/**
	 * outputs a file from the cache if needed
	 *
	 * @method output_cache
	 * */
	function output_cache() {
		$f = $this->set_cache_name();
		if(!$this->cache_dir) return false;
		if(!@is_file($f)) return false;

		$cache_time = @filemtime($f);
		$send_cache = true;
		foreach(array_merge($this->files,array(__FILE__)) as $file) {
			if($cache_time < @filemtime($file)) {
				$send_cache = false;
				break;
			}
		}

		if($send_cache) {
			if($this->code = file_get_contents($f))	return true;
			else return false;
		}

		return false;
	}
	/**
	 *
	 * @method set_cache_name
	 * */
	function set_cache_name() {
		if($this->cache_dir) {
			if(empty($this->files)) $cache_name = md5($this->code).".css";
			else $cache_name = md5(implode("",$this->files)).".css";

			//echo print_r($this->files);
			$this->cache_name = $this->cache_dir.$cache_name;
			return $this->cache_name;
		}
		else $this->throwError("{$this->cache_dir} is not set, cannot set cache_name!",true);
	}
	/**
	 *
	 * @method get_cache_name
	 * */
	function get_cache_name() {
		if($this->cache_dir) {
			return basename($this->cache_name);
		}
		else $this->throwError("{$this->cache_dir} is not set, cannot get cache_name!",true);
	}

	/**
	 * Gets all CSS-code in dir(s) $dirs or a single file
	 *
	 * @method __construct
	 */
	public function setDirs($dirs) {
		if(@is_file($dirs)) {
			$this->code = file_get_contents($dirs);
			$this->files = array($dirs);
		}
		else $this->getCodeFromDirs($dirs);
		//string?
		if(empty($this->files) && is_string($dirs)) {
			$this->code = $dirs;
		}
	}

	/**
	 * Compresses the code and takes care of constans
	 *
	 * @method pack
	 */
	public function pack() {

		if( ! $this->output_cache() ) {

			$this->compress();
			//$this->extractConstantSelectors();
			//$this->replaceConstantDefinitions();

			if($this->cache_name) {
				file_put_contents($this->cache_name,$this->code);
			}
		}

		return $this->code;
	}

	/**
	 * Extracts and removes 'constant selectors' (#selector = $constant;) from code
	 *
	 * @method extractConstantSelectors
	 */
	private function extractConstantSelectors() {
		$matches = array();
		$mergedSelectors = array();
		$pattern = '/([^;|\n|}][^=|}]*)[ ]?=[ ]?(\$[^;]*);/';

		preg_match_all($pattern, $this->code, $matches);

		$i = 0;
		foreach($matches[1] as $selector) {
			$selector = trim($selector);
			$constant = trim($matches[2][$i++]);
			$mergedSelectors[$constant][] = $selector;
		}

		$this->code = preg_replace($pattern, '', $this->code);
		$this->constantSelectors = $mergedSelectors;
	}

	/**
	 * Replaces all constant-definitions ($constant {css}) with the selectors that wanted them
	 *
	 * @method replaceConstantDefinitions
	 */
	private function replaceConstantDefinitions() {
		$matches = array();
		$find = array();
		$replace = array();
		$pattern = '/(\$[^ |:]*)(.*?)[ ]?{/';

		preg_match_all($pattern, $this->code, $matches);

		$i = 0;
		foreach($matches[1] as $constant) {
			$find[$i] = $constant .$matches[2][$i];
			$replace[$i] = array();
			foreach($this->constantSelectors[$constant] as $selector) {
				$replace[$i][] = $selector .$matches[2][$i];
			}
			$replace[$i] = implode($replace[$i], ',');
			$i++;
		}

		function cmp($a, $b) {
			$aLen = strlen($a);
			$bLen = strlen($b);

			if($aLen == $bLen) {
				return 0;
			}

			return ($aLen > $bLen) ? -1 : 1;
		}

		uasort($find, 'cmp');

		# There's a reason for this... (order of keys and key-values and shit...)
		$tmp = array();
		foreach($find as $k => $v) {
			$tmp[$k] = $replace[$k];
		}
		$replace = $tmp;

		$this->code = str_replace($find, $replace, $this->code);
	}

	/**
	 * Removes comments, white-space and line-breaks from code
	 *
	 * @method compress
	 */
	private function compress() {
		$this->code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $this->code);
		$this->code = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), ' ', $this->code);
		$this->code = str_replace('{ ', '{', $this->code);
		$this->code = str_replace(' }', '}', $this->code);
		$this->code = str_replace(', ', ',', $this->code);
		$this->code = str_replace('; ', ';', $this->code);
	}

	/**
	 * Gets all CSS-code in directory/ies $dirs
	 *
	 * @method getCodeFromDirs
	 */
	private function getCodeFromDirs($dirs) {
		$this->code = '';

		if(!is_array($dirs)) {
			$tmp = $dirs;
			$dirs = array();
			$dirs[] = $tmp;
		}
		$this->files = array();
		foreach($dirs as $dir) {

			if(@is_dir($dir)) {
				$dh = opendir($dir);
				if($dh) {
					while($f = readdir($dh)) {
						if('css' == end(explode('.', $f))) {
							$this->files[] = "$dir/$f";
						}
					}
				}
			}
		}
		if(!empty($this->files)) {
			sort($this->files);
			foreach($this->files as $f) {
				$this->code .= file_get_contents($f);
			}
		}
	}
	/**
	 * Shows errors
	 */
	function getError() {
		return $this->last_error;
	}
	/**
	 * throw errors
	 */
	function throwError($msg='', $die=false) {
		$this->last_error = $msg;
		if($die) {
			echo $msg;
			throw new Exception($msg);
			die;
		}
		//die($msg);
	}

}

?>
