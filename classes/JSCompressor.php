<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * @category MSCLIB
 * @package Compressor
 * @author Ivan Vergés
 */

/**
 * Javascrip Packer/compressor
 *
 * Merges every .js-file in directory/ies $dirs
 * into one file and compresses them using
 * Dead Edwards JavaScript-Packer
 *
 */
class JSCompressor {
	private $cache_dir = null;
	private $files = array();
	private $cache_name = null;
	private $last_error = '';

	private $code;

	/**
	 * Gets all JS-code in directory/ies $dirs or single file
	 *
	 */
	public function __construct($dirs=null, $cache_dir=null) {
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
	 * */
	function set_cache_name() {
		if($this->cache_dir) {
			if(empty($this->files)) $cache_name = md5($this->code).".js";
			else $cache_name = md5(implode("", $this->files)).".js";

			$this->cache_name = $this->cache_dir.$cache_name;
			return $this->cache_name;
		}
		else $this->throwError("{$this->cache_dir} is not set, cannot set cache_name!",true);
	}

	/**
	 *
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
	 */
	public function setDirs($dirs) {
		if(@is_file($dirs)) {
			$this->code = file_get_contents($dirs);
			$this->files[] = $dirs;
		}
		else $this->getCodeFromDirs($dirs);
		//string?
		if(empty($this->files) && is_string($dirs)) {
			$this->code = $dirs;
		}
	}

	/**
	 * Packs the JS-code using Dean Edwards JS-packer
	 *
	 * @param $encoding : None', 'Numeric', 'Normal', 'High ASCII'
	 */
	public function pack($encoding='Normal') {

		if( ! $this->output_cache() ) {

			include_once(dirname(__FILE__) . "/JavaScriptPacker.php");

			$packer = new JavaScriptPacker($this->code, $encoding);
			$this->code = $packer->pack();

			if($this->cache_name) {
				file_put_contents($this->cache_name, $this->code);
			}
		}
		return $this->code;
	}

	/**
	 * Gets all JS-code in directory/ies $dirs
	 *
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
						$parts = explode('.', $f);
						if('js' == end($parts)) {
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
