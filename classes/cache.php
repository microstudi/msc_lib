<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * @category MSCLIB
 * @package Files
 * @author Ivan Vergés
 */

/**
 * File cache class
 *
 * This file is used to handle cached files on several services like, local, ftp, ssh2, AmazonS3.
 *
 * This class is used by the functions of m_images
 *
 * Example:
 * <code>
 * $cache = new mCache('cache');
 * if($cache->expired("cached_file.txt", m_file_time("original_file.txt"))) {
 * 	$file = $cache->get("cached_file.txt");
 * }
 * else {
 * 	$file = "original_file.txt";
 * 	$db->put($file, "cached_file.txt");
 * }
 *
 * readfile($file);
 * </code>
 *
 */
class mCache {
	public $dir = '', $type = 'local', $url_prefix = '';
	protected $mfile = null;
	public $default_cache_time = 3600; //seconds for default cache time (in case cannot be retrievet)

	function __construct($dir = '', $type = 'local', $url_prefix = '') {
		while(substr($url_prefix, -1, 1) == '/') $url_prefix = substr($url_prefix, 0, -1);

		if($type == 'local') {
			if(!is_dir($dir)) {
				if(mkdir($dir, true)) @chmod($dir, 0777);
			}
			if(substr($dir, -1, 1) != '/') $dir = "$dir/";
			$this->type = 'local';
			$this->dir = $dir;
		}
		elseif($type instanceOf mFile) {
			$this->type = 'remote';
			$this->dir = $dir;
			$this->mfile = $type;
			$this->url_prefix = $url_prefix;
		}
	}

	function get_path($file='') {
		$dir = $this->dir;
		while(substr($dir, -1, 1) != '/') $dir .= '/';
		while($file{0} == '/') $file = substr($file, 1);
		return $dir . $file;
	}

	/**
	 * Returns a file (or url in remote) if cached version exists, false otherwise
	 * @param  string $file file to check
	 * @return mixed        string file or url
	 *                      boolean false if not exists
	 */
	function get($file) {
		$f = $this->get_path($file);
		if($this->type == 'local') {
			if(is_file($f)) {
				return $f;
			}
		}
		if($this->type == 'remote') {
			if($this->url_prefix) {
				if(m_url_exists($this->url_prefix . $f)) {
					return $this->url_prefix . $f;
				}
			}
			else {
				if($this->mfile->size($f) != -1) {
					return $this->mfile->get_path($f);
				}
			}
		}
		return false;
	}

	/**
	 * Retuns true if the file is newer than the cache
	 * @param  [type] $file file to check
	 * @return [type]       [description]
	 */
	function expired($file, $file_mtime = null) {
		$f = $this->get_path($file);
		$cache_mtime = 0;
		if($this->type == 'local')  $cache_mtime = (int) @filemtime($f);
		if($this->type == 'remote') $cache_mtime = (int) $this->mfile->mtime($f);

		if(empty($file_mtime)) $file_mtime = time() - $this->default_cache_time;

		if($cache_mtime < $file_mtime) return true;

		return false;
	}

	/**
	 * Saves a file to a cache
	 * @param  [type] $local  [description]
	 * @param  [type] $remote [description]
	 * @return [type]         [description]
	 */
	function put($local, $remote) {
		$f = $this->get_path($remote);
		if($this->type == 'local') {
			if(is_file($local)) {
				$ok = copy($local, $f);
				@chmod($f, 0666);
				return $ok;
			}
		}

		if($this->type == 'remote') {
			return $this->mfile->upload($local, $f);
		}
		return false;
	}

	function throwError($msg='') {
		throw new Exception($msg);
	}
}