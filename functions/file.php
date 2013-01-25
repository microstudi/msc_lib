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

	$type = $service;
	$CONFIG->file_remote_fp   = null;
	$default_ops              = array();
	switch ($service) {
		case 'file':
		case 'local':
			$default_ops = array('rootpath' => '', 'rooturl' => '');
			$type = 'file';
			break;
		case 'ftp':
			$default_ops = array('host' => '', 'port' => 21, 'user' => '', 'password' => '', 'rootpath' => '', 'rooturl' => '');
			break;

		case 'ssh':
		case 'ssh2':
			$default_ops = array('host' => '', 'port' => 22, 'user' => '', 'password' => '', 'rootpath' => '', 'rooturl' => '');
			$type = 'ssh';
			break;

		case 's3':
		case 'S3':
			$default_ops = array('endpoint' => 's3-eu-west-1.amazonaws.com', 'bucket' => '', 'key' => '', 'secret' => '', 'prefix' => '');
			$type = 's3';
			break;

		default:
			$type = 'file';
			break;
	}

	foreach($default_ops as $k => $v) {
		if(!array_key_exists($k, $options)) $options[$k] = $v;
	}

	if($type == 's3') {
		$options['host']     = $options['endpoint'];
		$options['user']     = $options['key'];
		$options['password'] = $options['secret'];
		$options['rootpath'] = $options['prefix'];
		$options['port']     = $options['bucket'];
		$options['rooturl']  = $options['bucket'] . "." . $options['endpoint'] . "/" . $options['prefix'];
	}
	$CONFIG->file_remote_fp = new mFile($type, $options['host'], $options['user'], $options['password'], $options['rootpath'], $options['port']);
	$CONFIG->file_remote_fp->error_mode('string');

	//configure the prefix
	m_file_url_prefix($options['rooturl']);
}
/**
 * Sets the prefix for a the returned url
 * @param  string $prefix the prefix wanted (usually http://something)
 *                        if null then the prefix will be autoextracted from current path
 * @return string 		  Returns the prefix
 */
function m_file_url_prefix($prefix = null) {
	global $CONFIG;


	if(is_null($prefix))	 {
		//return current conf if exists
		if($CONFIG->file_url_prefix) return $CONFIG->file_url_prefix;

		$prefix = dirname($_SERVER['SCRIPT_NAME']);
		if($prefix != '/') $prefix .= "/";
		$prefix = "http://" . $_SERVER['HTTP_HOST'] . $prefix;
	}

	while(substr($prefix, -1,1) == '/') $prefix = substr($prefix, 0 , -1);
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
		$prefix = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
	}

	if(substr($prefix, -1,1) != '/') $prefix .= "/";

	return $prefix . $file;
}

/**
 * Gets the file size (in bytes) from a remote place
 * @param  string $file the file to search (relative to the root directory)
 * @return int       the size
 */
function m_file_size($file) {
	global $CONFIG;

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

	return $CONFIG->file_remote_fp->download($remote, $local);

}
/**
 * Copy a file to a remote place
 * Autocreates destination tree of directories
 * @param  string $local  the local file
 * @param  string $remote the remote file
 * @param  boolean $auto_create_dirs if true tries to autocreates the directory structure on remote
 *                                   on S3 has no effect as AWS has no concept of "directory" (always true)
 * @return boolean        true if ok
 */
function m_file_put($local, $remote, $auto_create_dirs = true) {
	global $CONFIG;

	return $CONFIG->file_remote_fp->upload($local, $remote, $auto_create_dirs);
}
/**
 * Deletes a file in a remote place, deletes empties directories left
 * @param  string $remote the remote file to delete
 * @param  boolean $auto_delete_dirs if true deletes empty the directory containing the remote file
 *                                   if it is empty on AWS S3, is always true as theres is no "directories"
 * @return boolean         [description]
 */
function m_file_delete($remote, $auto_delete_dirs = true) {
	global $CONFIG;

	return $CONFIG->file_remote_fp->delete($remote, $auto_delete_dirs);
}

/**
 * Deletes a remote dir recursively (deletes all files inside)
 * @param  string $remote_dir the remote dir, on s3, the remote prefix which simulates the dir
 * @return boolean             [description]
 */
function m_file_rmdir($remote_dir) {
	global $CONFIG;

	return $CONFIG->file_remote_fp->rmdir($remote_dir, $auto_delete_dirs);
}

/**
 * Renames files in a remote place (creates directories if needed)
 * @param  [type] $remote_source [description]
 * @param  [type] $remote_dest   [description]
 * @return [type]         [description]
 */
function m_file_rename($remote_source, $remote_dest, $auto_create_dirs = true) {
	global $CONFIG;

	return $CONFIG->file_remote_fp->rename($remote_source, $remote_dest, $auto_create_dirs);
}

function m_file_error() {
	global $CONFIG;

	return $CONFIG->file_remote_fp->last_error;
}