<?php

class mCache {
	public $dir = '', $type = 'local', $url_prefix = '';
	protected $mfile = null;

	function __construct($dir = '', $type = 'local', $url_prefix = '') {
		if($type == 'local') {
			if(!is_dir($dir)) {
				if(mkdir($dir, true)) @chmod($dir, 0777);
			}
			if(substr($dir, -1, 1) != "/") $dir = "$dir/";
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
		if(substr($dir, -1, 1) != '/') $dir .= "/";
		if($file{0} == '/') $file = substr($file, 1);
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
				if(m_url_exists($this->url_prefix . "/". $f)) {
					return $this->url_prefix . "/" . $f;
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