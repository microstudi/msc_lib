<?php

/**
 * [m_file_set_remote description]
 * @param  string $service The type of remote file handle: local, ftp, ssh2, s3
 * @param  array  $options options for the type used
 * @return [type]          [description]
 */
function m_file_set_remote($service = 'local', $options=array()) {
	global $CONFIG;

	if($CONFIG->file_remote_fp instanceOf mFile) $CONFIG->file_remote_fp->close();

	$CONFIG->file_remote_type = $service;
	$CONFIG->file_remote_fp   = null;
	$default_ops              = array();
	switch ($service) {
		case 'file':
		case 'local':
			$default_ops = array('rootpath' => '', 'rooturl' => '');
			$CONFIG->file_remote_type = 'file';
			break;
		case 'ftp':
			$default_ops = array('host' => '', 'port' => 21, 'user' => '', 'password' => '', 'rootpath' => '', 'rooturl' => '');
			break;

		case 'ssh':
		case 'ssh2':
			$default_ops = array('host' => '', 'port' => 22, 'user' => '', 'password' => '', 'rootpath' => '', 'rooturl' => '');
			$CONFIG->file_remote_type = 'ssh';
			break;

		case 's3':
			// code...
			break;

		default:
			$CONFIG->file_remote_type = 'local';
			break;
	}
	$CONFIG->file_remote_options = $default_ops;
	foreach($options as $k => $v) {
		if(array_key_exists($k, $default_ops)) $CONFIG->file_remote_options[$k] = $v;
	}
}
/**
 * Sets the prefix for a the returned url
 * @param  string $prefix the prefix wanted (usually http://something)
 *                        if null then the prefix will be autoextracte from current path
 * @return string 		  Returns the prefix
 */
function m_file_url_prefix($prefix = null) {
	global $CONFIG;

	if(is_null($prefix))	 {
		$prefix = dirname($_SERVER['SCRIPT_NAME']);
		if($prefix != '/') $prefix .= "/";
		$prefix = "http://" . $_SERVER['HTTP_HOST'] . $prefix;
	}

	$CONFIG->file_url_prefix = $prefix;

	return $prefix;
}
/**
 * Returns the public url from a file with the default prefix
 * @param  string $file [description]
 * @return string       [description]
 */
function m_file_url($file) {
	global $CONFIG;
	$prefix = $CONFIG->file_url_prefix;
	if(empty($prefix) && substr($file, 0, 4) != 'http') {
		$prefix = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . ($file{0} == '/' ? "" : "/") ;
	}
	if(is_file($file)) return $prefix . $file;

	return false;
}

/**
 * Gets the file size (in bytes) from a remote place
 * @param  string $file the file to search (relative to the root directory)
 * @return int       the size
 */
function m_file_size($file) {
	global $CONFIG;

	if( !($CONFIG->file_remote_fp instanceOf mFile ) ) {
		$CONFIG->file_remote_fp = new mFile(
			$CONFIG->file_remote_type,
			$CONFIG->file_remote_options['host'],
			$CONFIG->file_remote_options['port'],
			$CONFIG->file_remote_options['user'],
			$CONFIG->file_remote_options['password'],
			$CONFIG->file_remote_options['rootpath']
			);
	}
	return $CONFIG->file_remote_fp->size($file, true);
}

/**
 * Copy a file from remote place to local
 * @param  string $local  the local file
 * @param  string $remote the remote file
 * @return boolean        true if ok
 */
function m_file_get($remote, $local) {
	global $CONFIG;

	if( !($CONFIG->file_remote_fp instanceOf mFile ) ) {
		$CONFIG->file_remote_fp = new mFile(
			$CONFIG->file_remote_type,
			$CONFIG->file_remote_options['host'],
			$CONFIG->file_remote_options['port'],
			$CONFIG->file_remote_options['user'],
			$CONFIG->file_remote_options['password'],
			$CONFIG->file_remote_options['rootpath']
			);
	}

	return $CONFIG->file_remote_fp->download($remote, $local);
	return false;

}
/**
 * Copy a file to a remote place
 * Autocreates destination tree of directories
 * @param  string $local  the local file
 * @param  string $remote the remote file
 * @return boolean        true if ok
 */
function m_file_put($local, $remote) {
	if(!is_dir(dirname($remote))) @mkdir(dirname($remote), 0777, true);
	if($ok = @copy($local, $remote)) return true;
	return false;

}
/**
 * Deletes a file in a remote place, deletes empties directories left
 * if is a directory deletes the directory and all of his contents
 * @param  string $remote [description]
 * @return boolean         [description]
 */
function m_file_delete($remote) {
	if (is_dir($remote)) {
        m_rmdir($remote);
    }
    else unlink($remote);
}

/**
 * Renames files in a remote place (creates directories if needed)
 * @param  [type] $source [description]
 * @param  [type] $dest   [description]
 * @return [type]         [description]
 */
function m_file_rename($source, $dest) {
	if(!is_dir(dirname($dest))) {
		mkdir(dirname($dest), 0777, true);
	}
	return rename($source, $dest);
}