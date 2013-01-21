<?php
/**
* @file classes/file.php
* @author Ivan Vergés
* @brief FILE wrapper manipulation class\n
* This file is used to upload, download on several services like, local, ftp, ssh2, AmazonS3\n
* This class is used by the file functions/file.php
*
* @section usage Usage
* $db = new mFile('type','host','port','username','password','path');\n
* $db->connect();\n
* $db->upload($local_file, $remote_file);\n
*
*/class mFile {
	private $link = '', $type = '', $host = '', $port = '', $user = '', $pass = '', $path = '', $realpath = '';
	public $last_error = '', $last_path = '', $last_local = '', $last_remote = '';
	public $ssh_mode = 'auto'; //auto, phpseclib, ssh2
	private $libsec = false;

	/**
	 * Sets the initial parameters to work
	 * @param string $type type of service, could be: file, ftp, ssh, s3
	 * @param string $host host to connect (ftp o ssh only)
	 * @param string $port port to connect (ftp o ssh only)
	 * @param string $user username  to connect (ftp o ssh only)
	 * @param string $pass password  to connect (ftp o ssh only)
	 * @param string $path base path to operate
	 */
	function __construct($type = '', $host = '', $port = '', $user = '', $pass = '', $path = '') {
		$this->type = $type;
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->pass = $pass;
		if(substr($path, -1, 1) != '/') $path .= "/";
		$this->path = $path;
		//SSH library to use
		if($type == 'ssh' && ($this->ssh_mode = 'phpseclib' || ($this->ssh_mode == 'auto' && !extension_loaded('ssh2')))) {
			$this->libsec = true;
			include_once('phpseclib/Math/BigInteger.php');
			include_once('phpseclib/Crypt/Random.php');
			include_once('phpseclib/Crypt/Hash.php');
			include_once('phpseclib/Crypt/Rijndael.php');
			include_once('phpseclib/Crypt/AES.php');
			include_once('phpseclib/Crypt/DES.php');
			include_once('phpseclib/Crypt/TripleDES.php');
			include_once('phpseclib/Crypt/RSA.php');
			include_once('phpseclib/Crypt/RC4.php');
			include_once('phpseclib/Net/SSH2.php');
			include_once('phpseclib/Net/SFTP.php');
		}
	}

	/**
	 * Returns false if cannot connect o change to specified path
	 * */
	function connect() {
		if(is_resource($this->link) || ($this->link && ($this->type == 'file' || $this->libsec))) return true;

		switch($this->type) {
			case 'file':
					$this->link = true;
					if($this->realpath($this->path)) {
						return true;
					}
					else {
						$this->link = false;
						$this->last_error = 'file-chdir-error';
					}
				break;

			case 'ftp':
					if($this->link = @ftp_connect($this->host,(string)($this->port ? $this->port : 21) )) {
						if(@ftp_login($this->link, $this->user, $this->pass)) {
							//test path
							if($this->realpath($this->path)) return true;
							else $this->last_error = 'ftp-chdir-error';
						}
						else $this->last_error = 'ftp-auth-error';

					}
					else $this->last_error = 'ftp-connection-error';
				break;

			case 'ssh':
					if($this->libsec) {
						$this->link = new Net_SFTP($this->host, $this->port ? $this->port : 22);
						if ($this->link->login($this->user, $this->pass)) {
							if($this->realpath($this->path))	return true;
							else {
								$this->last_error = 'ssh2-chdir-error';
								$this->link = false;
							}
						}
						else $this->last_error = 'ssh2-auth-error';
					}
					else {
						if($this->link = @ssh2_connect($this->host, $this->port ? $this->port : 22)) {
							if(ssh2_auth_password($this->link, $this->user, $this->pass)) {
								if($this->realpath($this->path))	return true;
								else $this->last_error = 'ssh2-chdir-error';
							}
							else $this->last_error = 'ssh2-auth-error';

						}
						else $this->last_error = 'ssh2-connection-error';
					}
				break;

		}
		return false;
	}

	/**
	 * returns a complete path
	 * ensures /foo/bar/ not /foo/bar or /foobar/
	 * */
	function path($path='') {
		if($path{0} == '/') $path = substr($path,1);
		$path = $this->path . $path;
		if(substr($path, -1, 1) != '/') $path .= "/";
		return $path;
	}

	/**
	 * Tests a path on the active connection
	 * @param  string $path the path for to check existence
	 * @return string       returns the real path directory or false on failure
	 */
	function realpath($path='') {
		$this->last_path = $path;
		$realpath = false;
		set_error_handler(array($this,'error_handler'), E_ALL & ~E_NOTICE);

		if($this->link && $path) {
			$realpath = '';
			switch($this->type) {
				case 'file':
						if( !($realpath = realpath($path)) ) {
							$this->last_error = "$path not found: " . $this->last_error;
							return false;
						}
					break;

				case 'ftp':
						$p = @ftp_pwd($this->link);
						if(@ftp_chdir($this->link, $path)) {
							if($p) @ftp_chdir($this->link, $p);
							$realpath = ftp_pwd($this->link);
						}
						else {
							$this->last_error = "$path not found: " . $this->last_error;
							return false;
						}
					break;

				case 'ssh':
						if($this->libsec) {
							if($this->link->chdir($path)) {
								$realpath = $this->link->pwd();
							}
							else {
								$this->last_error = "$path not found: " . $this->last_error;
								return false;
							}
						}
						else {
							$stream = ssh2_exec($this->link,"readlink -ev " . escapeshellarg($path));
							$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
							// Enable blocking for both streams
							stream_set_blocking($errorStream, true);
							stream_set_blocking($stream, true);
							$stdout = trim(stream_get_contents($stream));
							$stderr = stream_get_contents($errorStream);
							fclose($errorStream);
							fclose($stream);
							//echo "$path [$stdout][$stderr]";die;
							if(!$stderr) $realpath = $stdout;
							else {
								$this->last_error = $stderr . ": " . $this->last_error;
								return false;
							}
						}
					break;
			}
			if(substr($realpath, 1, -1)!='/') $realpath .= "/";
		}
		restore_error_handler();
		return $realpath;
	}

	/**
	 * stores file on remote (overwrites)
	 * @param  string $local  local file (must be absolute or relative to the working document)
	 * @param  string $remote remote file (relative to $this->path)
	 * @return boolean        returns true if success, false otherwise
	 */
	function upload($local, $remote) {
		if(!$this->connect()) return false;
		$this->last_local  = $local;
		$this->last_remote = $remote;
		$name              = basename($local);


		$ok = false;
		set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);

		//if local is a stream, copy locally
		if(substr($local,0,7) == 'http://') {
			$tmp = array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));
			file_put_contents($tmp, file_get_contents($local));
			$local = $tmp;
		}

		if(!is_file($local)) {
			$this->last_error = "local-file-not-exists: $local";
			return false;
		}
		switch($this->type) {
			case 'file':
					if(copy($local, $remote)) $ok = true;
					else $this->last_error = "file-error-uploading-to: " . $this->last_error;
				break;

			case 'ftp':
					$dir = dirname($remote);
					if($dir != '.') ftp_chdir($this->link, $dir);
					if(ftp_put($this->link,basename($remote), $local,FTP_BINARY)) $ok = true;
					else $this->last_error = "ftp-error-uploading-to: " . $this->last_error;
				break;

			case 'ssh':
					if($this->libsec) {
						if($this->link->put($remote, $local, NET_SFTP_LOCAL_FILE)) $ok = true;
						else $this->last_error = "ssh2-error-uploading-to: " . $this->last_error;
					}
					else {
						if(ssh2_scp_send($this->link, $local, $remote)) $ok = true;
						else $this->last_error = "ssh2-error-uploading-to: " . $this->last_error;
					}
				break;
		}
		restore_error_handler();
		return $ok;
	}

	/**
	 * deletes file on remote
	 * @param  string $remote remote file (relative to $this->path) that will be deleted
	 * @return boolean        returns true if success, false otherwise
	 */
	function delete($remote) {
		if(!$this->connect()) return false;
		$this->last_remote = $remote;

		$ok = false;
		set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);
		switch($this->type) {
			case 'file':
					if(unlink($remote)) $ok = true;
					else $this->last_error = "file-error-deleting-to: " . $this->last_error;
				break;

			case 'ftp':
					$dir = dirname($remote);
					if($dir != '.') ftp_chdir($this->link, $dir);
					if(ftp_delete($this->link,basename($remote))) $ok = true;
					else $this->last_error = "ftp-error-deleting-to: " . $this->last_error;
				break;

			case 'ssh':
					if($this->libsec) {
						if($this->link->delete($remote, false)) $ok = true;
						else $this->last_error = "ssh2-error-deleting-to: " . $this->last_error;
					}
					else {
						if($sftp = ssh2_sftp($this->link)) {
							if(ssh2_sftp_unlink($sftp, $remote)) $ok = true;
							else $this->last_error = "ssh2-error-deleting-to: " . $this->last_error;
						}
					}
				break;
		}
		restore_error_handler();
		return $ok;

	}

	/**
	 * retrieves file from remote (overwrites)
	 * @param  string $remote remote file (relative to $this->path)
	 * @param  string $local  local file (must be absolute or relative to the working document)
	 * @return boolean        returns true if success, false otherwise
	 */
	function download($remote, $local) {
		if(!$this->connect()) return false;
		$this->last_local = $local;
		$this->last_remote = $remote;
		$name = basename($local);

		$ok = false;
		set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);

		if(substr($local,0,7) == 'http://') {
			$tmp = array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));
			file_put_contents($tmp, file_get_contents($local));
			$local = $tmp;
		}
		switch($this->type) {
			case 'file':
					if(copy($remote, $local)) $ok = true;
					else $this->last_error = "file-error-downloading-from: " . $this->last_error;
				break;

			case 'ftp':
					$dir = dirname($remote);
					if($dir != '.') ftp_chdir($this->link, $dir);
					if(ftp_get($this->link, $local,basename($remote),FTP_BINARY)) $ok = true;
					else $this->last_error = "ftp-error-downloading-from: " . $this->last_error;
				break;

			case 'ssh':
					if($this->libsec) {
						if($this->link->get($remote, $local)) $ok = true;
						else $this->last_error = "ssh2-error-downloading-from: " . $this->last_error;
					}
					else {
						if(ssh2_scp_recv($this->link, $remote, $local)) $ok = true;
						else $this->last_error = "ssh2-error-downloading-from: " . $this->last_error;
					}
				break;
		}
		restore_error_handler();

		return $ok;
	}
	/**
	 * retrieves filesize from remote
	 * @param  string  $remote remote file to check file size
	 * @param  boolean $force  if it is true, then will try to download the file from ftp if ftp_size fails
	 * @return int         		returns -1 on error, file size otherwise
	 */
	function size($remote, $force=false) {
		if(!$this->connect()) return false;
		$this->last_remote = $remote;
		$size = -1;
		set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);

		switch($this->type) {
			case 'file':
					if( !($size = filesize($remote)) ) $size = -1;
				break;

			case 'ftp':
					$dir = dirname($remote);
					if($dir != '.') ftp_chdir($this->link, $dir);
					$size = ftp_size($this->link,basename($remote));
					if($size == -1 && $force) {
						//try to download the file and check the filesize
						$tmp = tempnam();
						if($this->download($remote, $tmp)) {
							if(is_file($tmp)) $size = filesize($tmp);
						}
					}
				break;

			case 'ssh':
					if($this->libsec) {
						if( false === ($size = $this->link->size($remote)) ) $size = -1;
					}
					else {
						$stream = ssh2_exec($this->link,"stat -c %s " . escapeshellarg($remote));
						$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
						// Enable blocking for both streams
						stream_set_blocking($errorStream, true);
						stream_set_blocking($stream, true);
						$stdout = trim(stream_get_contents($stream));
						$stderr = stream_get_contents($errorStream);
						fclose($errorStream);
						fclose($stream);
						//echo "$path [$stdout][$stderr]";
						if(!$stderr) $size = (int)$stdout;
						else {
							$this->last_error = $stderr;
						}
					}
				break;
		}
		restore_error_handler();
		return $size;
	}

	/**
	 * Handle function errors
	 * */
	public function error_handler($errno, $errstr, $errfile, $errline, $errcontext) {
		$this->last_error = "$errstr";
		//echo "\n\n".$this->error."\n\n";
		return true;
	}
}
?>
