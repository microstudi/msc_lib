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
	private $link = '', $type = '', $host = '', $port = '', $user = '', $pass = '', $path = '', $bucket = '';
	public  $last_error = '', $last_path = '', $last_local = '', $last_remote = '';
	public  $ssh_mode = 'auto'; //auto, phpseclib, ssh2
	public  $libsec = false;
	private $quiet_mode = false;

	/**
	 * Sets the initial parameters to work
	 * @param string $type type of service, could be: file, ftp, ssh, s3
	 * @param string $host host to connect (ftp o ssh only), for s3 it's the endpoint (s3.amazonaws.com, s3-eu-west-1.amazonaws.com)
	 * @param string $user username  to connect (ftp o ssh only), for s3 it's the access key
	 * @param string $pass password  to connect (ftp o ssh only), for s3 it's the secret key
	 * @param string $path base path to operate, for s3 it's the prefix (a / char will be added)
	 * @param string $port port to connect (ftp o ssh only), for s3 it's the bucket to operate
	 */
	function __construct($type = '', $host = '', $user = '', $pass = '', $path = '', $port = '') {
		$this->type = $type;
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->pass = $pass;
		if(substr($path, -1, 1) != '/') $path .= "/";
		$this->path = $path;
		if($this->type == 's3') {
			$this->bucket = $port;
		}
	}

	function error_mode($mode = 'exception') {
		if($mode == 'exception')  $this->quiet_mode = false;
		elseif($mode == 'quiet')  $this->quiet_mode = 1;
		elseif($mode == 'string') $this->quiet_mode = 2;
	}

	/**
	 * Returns false if cannot connect o change to specified path
	 * */
	function connect() {

		//SSH library to use
		if($this->type == 'ssh' && ($this->ssh_mode == 'phpseclib' || ($this->ssh_mode == 'auto' && !extension_loaded('ssh2')))) {
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
		if($this->type == 's3') {
			include_once('S3.php');
		}

		switch($this->type) {
			case 'file':
					if($this->link) return true;

					$this->link = true;
					if($this->realpath($this->path)) {
						return true;
					}
					else {
						$this->link = false;
						$this->throwError('file-chdir-error');
					}
				break;

			case 'ftp':
					if(is_resource($this->link)) return true;

					if($this->link = @ftp_connect($this->host,(string)($this->port ? $this->port : 21) )) {
						if(@ftp_login($this->link, $this->user, $this->pass)) {
							//test path
							if($this->realpath($this->path)) return true;
							else $this->throwError('ftp-chdir-error');
						}
						else $this->throwError('ftp-auth-error');

					}
					else $this->throwError('ftp-connection-error');
				break;

			case 'ssh':
					if($this->libsec) {
						if($this->link instanceOf New_SFTP) return true;

						$this->link = new Net_SFTP($this->host, $this->port ? $this->port : 22);
						if ($this->link->login($this->user, $this->pass)) {
							if($this->realpath($this->path))	return true;
							else {
								$this->link = false;
								$this->throwError('ssh2-chdir-error');
							}
						}
						else $this->throwError('ssh2-auth-error');
					}
					else {
						if(is_resource($this->link)) return true;

						if($this->link = @ssh2_connect($this->host, $this->port ? $this->port : 22)) {
							if(ssh2_auth_password($this->link, $this->user, $this->pass)) {
								if($this->realpath($this->path))	return true;
								else $this->throwError('ssh2-chdir-error');
							}
							else $this->throwError('ssh2-auth-error');

						}
						else $this->throwError('ssh2-connection-error');
					}
				break;

			case 's3':
					if($this->link instanceOf S3) return true;
					$this->link = new S3($this->user, $this->pass, false, $this->host);
					$this->link->setExceptions(true);
					try {
						//try to find the bucket by requesting his location
						$lc = $this->link->getBucketLocation($this->bucket);
						return true;
					}catch(S3Exception $e) {
						$this->throwError($e->getMessage());
					}
				break;

		}
		return false;
	}

	/**
	 * Close the current connection
	 * @return [type] [description]
	 */
	function close() {
		$ok = true;
		switch($this->type) {
			case 'ftp':
					if(!is_resource($this->link)) return false;
					$ok = ftp_close($this->link);
				break;

			case 'ssh':
					if($this->libsec) {
						if( !($this->link instanceOf Net_SFTP) ) return false;
						$ok = $this->link->disconnect();
					}
					else {
						if(!is_resource($this->link)) return false;
						$ok = ssh2_exec($this->link, "exit");
					}
				break;
			case 's3':
					if( !($this->link instanceOf S3) ) return false;
				break;
		}
		$this->link = null;
		return $ok;
	}

	/**
	 * Ensures a valid path without duplicates /
	 * @param  [type] $path [description]
	 * @return [type]         [description]
	 */
	static function path($path) {
		while($path{0} == '/') $path = substr($path, 1);
		if(substr($path, -1, 1) != '/') $path .= '/';
		return $path;
	}

	/**
	 * Ensures a valid absolute path without duplicates /
	 * @param  [type] $remote [description]
	 * @return [type]         [description]
	 */
	public function get_path($remote='') {
		while($remote{0} == '/') $remote = substr($remote,1);
		return $this->path . $remote;
	}

	/**
	 * Tests a absolute path on the active connection
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
							return $this->throwError("$path not found: " . $this->last_error);
						}
					break;

				case 'ftp':
						$p = @ftp_pwd($this->link);
						if(@ftp_chdir($this->link, $path)) {
							$realpath = ftp_pwd($this->link);
							@ftp_chdir($this->link, $p);
						}
						else {
							return $this->throwError("$path not found: " . $this->last_error);
						}
					break;

				case 'ssh':
						if($this->libsec) {
							$p = $this->link->pwd();
							if($this->link->chdir($path)) {
								$realpath = $this->link->pwd();
								$this->link->chdir($p);
							}
							else {
								return $this->throwError("$path not found: " . $this->last_error);
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
								return $this->throwError($stderr . ": " . $this->last_error);
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
	 * @param  boolean $auto_create_dirs if true tries to autocreates the directory structure on remote
	 *                                   on S3 has no effect as AWS has no concept of "directory" (always true)
	 * @return boolean        returns true if success, false otherwise
	 */
	function upload($local, $remote, $auto_create_dirs = true) {
		if(!$this->connect()) return $this->last_error;
		$remote = $this->get_path($remote);
		$this->last_local  = $local;
		$this->last_remote = $remote;

		$ok = false;
		set_error_handler(array($this,'error_handler'), E_ALL & ~E_NOTICE);

		//if local is a stream, copy locally
		if(substr($local,0,7) == 'http://') {
			$tmp = array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));
			file_put_contents($tmp, file_get_contents($local));
			$local = $tmp;
		}

		if(!is_file($local)) {
			return $this->throwError("local-file-not-exists: $local");
		}
		switch($this->type) {
			case 'file':
					if($auto_create_dirs) $this->mkdir_recursive(dirname($remote));
					if(copy($local, $remote)) $ok = true;
					else return $this->throwError("file-error-uploading-to: " . $this->last_error);
				break;

			case 'ftp':
					$dir = dirname($remote);
					$odir = '';
					if($auto_create_dirs) $this->mkdir_recursive($dir);
					if($dir != '.') {
						$odir = ftp_pwd($this->link);
						ftp_chdir($this->link, $dir);
					}
					if(ftp_put($this->link, basename($remote), $local, FTP_BINARY)) $ok = true;
					if($odir) ftp_chdir($this->link, $odir);
					if(!$ok) return $this->throwError("ftp-error-uploading-to: " . $this->last_error);
				break;

			case 'ssh':
					if($auto_create_dirs) $this->mkdir_recursive(dirname($remote));
					if($this->libsec) {
						if($this->link->put($remote, $local, NET_SFTP_LOCAL_FILE)) $ok = true;
						else return $this->throwError("ssh2-error-uploading-to: " . $this->last_error);
					}
					else {
						if(ssh2_scp_send($this->link, $local, $remote)) $ok = true;
						else return $this->throwError("ssh2-error-uploading-to: " . $this->last_error);
					}
				break;

			case 's3':
					try {
						$this->link->putObjectFile($local, $this->bucket, $remote, S3::ACL_PUBLIC_READ);
						$ok = true;
					}catch(S3Exception $e) {
						return $this->throwError('s3-error-uploading-to: ' . $e->getMessage());
					}
				break;
		}
		restore_error_handler();
		return $ok;
	}

	/**
	 * deletes file on remote
	 * @param  string  $remote remote file (relative to $this->path) that will be deleted
	 * @param  boolean $auto_delete_dirs if true deletes empty the directory containing the remote file
	 *                                   if it is empty on AWS S3, is always true
	 * @return boolean        returns true if success, false otherwise
	 */
	function delete($remote, $auto_delete_dirs = true) {
		if(!$this->connect()) return false;
		$remote = $this->get_path($remote);
		$this->last_remote = $remote;

		$ok = false;
		set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);
		switch($this->type) {
			case 'file':
					if(unlink($remote)) {
						$ok = true;
						if($auto_delete_dirs) $this->delete_empty_dir(dirname($remote));
					}
					else return $this->throwError("file-error-deleting-to: " . $this->last_error);
				break;

			case 'ftp':
					$dir = dirname($remote);
					$odir = '';
					if($dir != '.') {
						$odir = ftp_pwd($this->link);
						ftp_chdir($this->link, $dir);
					}
					if(ftp_delete($this->link, basename($remote))) $ok = true;
					if($odir) ftp_chdir($this->link, $odir);
					if($auto_delete_dirs) $this->delete_empty_dir($dir);
					if(!$ok) return $this->throwError("ftp-error-deleting-to: " . $this->last_error);
				break;

			case 'ssh':
					if($this->libsec) {
						if($this->link->delete($remote, false)) {
							$ok = true;
							if($auto_delete_dirs) $this->delete_empty_dir(dirname($remote));
						}
						if(!$ok) return $this->throwError("ssh2-error-deleting-to: " . $this->last_error);
					}
					else {
						if($sftp = ssh2_sftp($this->link)) {
							if(ssh2_sftp_unlink($sftp, $remote)) {
								$ok = true;
								if($auto_delete_dirs) $this->delete_empty_dir(dirname($remote));
							}
							if(!$ok) return $this->throwError("ssh2-error-deleting-to: " . $this->last_error);
						}
					}
				break;

			case 's3':
					try{
						$this->link->deleteObject($this->bucket, $remote);
						$ok = true;
					} catch(S3Exception $e) {
						return $this->throwError("s3-error-deleting-to: " . $e->getMessage());
					}
				break;

		}
		restore_error_handler();
		return $ok;

	}

	/**
	 * Deletes a remote directory recursively
	 * @param  string $remote_dir the remote dir
	 * @return boolean        returns true if success, false otherwise
	 */
	public function rmdir($remote_dir_original) {
		if(!$this->connect()) return false;
		if(!$remote_dir_original) return false;
		$remote = $this->get_path($remote_dir_original);
		//never delete the working path
		if($remote == $this->get_path()) return false;
		$this->last_remote = $remote;

		$ok = false;
		set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);
		switch($this->type) {
			case 'file':
					if(m_rmdir($remote)) {
						$ok = true;
					}
					else return $this->throwError("file-error-rmdir-to: " . $this->last_error);
				break;

			case 'ftp':
					//try to delete the dir or file
					$ok = false;
					 if( !(@ftp_rmdir($this->link, $remote) || @ftp_delete($this->link, $remote)) ) {
					 	//if the attempt to delete fails, get the file listing
						$filelist = @ftp_nlist($this->link, $remote);
						//loop through the file list and recursively delete the FILE in the list
						foreach($filelist as $file) {
							$file = preg_replace("/^" . str_replace("/", "\/", quotemeta($this->path)) . "/", "", $file);
							$this->rmdir($file);
						}
						//if the file list is empty, delete the DIRECTORY we passed
						$ok = $this->rmdir($remote_dir_original);
					}
					else $ok = true;
					if(!$ok) return $this->throwError("ftp-error-rmdir-to: " . $this->last_error);
				break;

			case 'ssh':
					if($this->libsec) {
						if($this->link->delete($remote, true)) {
							$ok = true;
						}
						if(!$ok) return $this->throwError("ssh2-error-rmdir-to: " . $this->last_error);
					}
					else {
						$stream = ssh2_exec($this->link,"rm -rf " . escapeshellarg($remote));
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
							return $this->throwError("ssh2-error-rmdir-to: " . $stderr);
						}
					}
				break;

			case 's3':
					try {
						$ok = false;
						while (($contents = $this->link->getBucket($this->bucket, $remote)) !== false) {
					        foreach ($contents as $object) {
					            $this->link->deleteObject($this->bucket, $object['name']);
					        }
					       	if(count($contents)<1000) {
								$ok = true;
								break;
							}
					    }
					} catch(S3Exception $e) {
						return $this->throwError("s3-error-deleting-to: " . $e->getMessage());
					}
				break;

		}
		restore_error_handler();
		return $ok;
	}

	/**
	 * Deletes a directory if its empty on remote place
	 * @param  string $remote absolute remote dir!
	 * @return [type]         [description]
	 */
	protected function delete_empty_dir($remote_dir) {
		if(!$this->connect()) return $this->last_error;
		//never delete the root path
		if($remote_dir == $this->path) return true;

		switch ($this->type) {
			case 'file':
				if(is_dir($remote_dir) && count(scandir($remote_dir)) == 2) {
					if(@rmdir($remote_dir)) return $this->delete_empty_dir(dirname($remote_dir));
				}
				break;

			case 'ftp':
				if(@ftp_rmdir($this->link, $remote_dir)) return $this->delete_empty_dir(dirname($remote_dir));
				break;

			case 'ssh':
					if($this->libsec) {
						if($this->link->rmdir($remote_dir)) return $this->delete_empty_dir(dirname($remote_dir));
					}
					else {
						if($sftp = ssh2_sftp($this->link)) {
							if(@ssh2_sftp_rmdir($sftp, $remote_dir)) return $this->delete_empty_dir(dirname($remote_dir));
						}
					}
				break;
		}
		return true;
	}

	/**
	 * Creates a dir in remote recursively
	 * @param  string $remote_dir absolute remote dir!
	 * @return [type]             [description]
	 */
	protected function mkdir_recursive($remote_dir) {
		if(!$this->connect()) return $this->last_error;
		switch($this->type) {
			case 'file':
					if(!is_dir($remote_dir)) @mkdir($remote_dir, 0777, true);
					return is_dir($remote_dir);
				break;

			case 'ftp':
					$dir = $remote_dir;
					$odir = ftp_pwd($this->link);
					$parts = explode("/", $dir);
			        $ok = true;
			        $fullpath = "";
			        foreach($parts as $part){
		                if(empty($part)) {
	                        $fullpath .= "/";
	                        continue;
		                }
		                $fullpath .= $part."/";
		                if(@ftp_chdir($this->link, $fullpath)){
		                	ftp_chdir($this->link, $fullpath);
		                }
		                else {
		                	if(ftp_chdir($this->link, $part)) continue;
		                    elseif(@ftp_mkdir($this->link, $part)){
		                        ftp_chdir($this->link, $part);
		                    }
		                    else {
		                        $ok = false;
		                    }
		                }
			        }
			        if($odir) ftp_chdir($this->link, $odir);
			        return $ok;
			    break;

			case 'ssh':
					if($this->libsec) {
						return $this->link->mkdir($remote_dir);
					}
					else {
						if($sftp = ssh2_sftp($this->link)) {
							return ssh2_sftp_mkdir($sftp, $remote_dir, 0777, true);
						}
						return false;
					}
				break;
		}
	}

	/**
	 * retrieves file from remote (overwrites)
	 * @param  string $remote remote file (relative to $this->path)
	 * @param  string $local  local file (must be absolute or relative to the working document)
	 * @return boolean        returns true if success, false otherwise
	 */
	function download($remote, $local) {
		if(!$this->connect()) return $this->last_error;
		$remote = $this->get_path($remote);
		$this->last_local = $local;
		$this->last_remote = $remote;

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
					else return $this->throwError("file-error-downloading-from: " . $this->last_error);
				break;

			case 'ftp':
					$dir = dirname($remote);
					$odir = '';
					if($dir != '.') {
						$odir = ftp_pwd($this->link);
						ftp_chdir($this->link, $dir);
					}
					if(ftp_get($this->link, $local, basename($remote), FTP_BINARY)) $ok = true;
					if($odir) ftp_chdir($this->link, $odir);
					if(!$ok) return $this->throwError("ftp-error-downloading-from: " . $this->last_error);
				break;

			case 'ssh':
					if($this->libsec) {
						if($this->link->get($remote, $local)) $ok = true;
						else return $this->throwError("ssh2-error-downloading-from: " . $this->last_error);
					}
					else {
						if(ssh2_scp_recv($this->link, $remote, $local)) $ok = true;
						else return $this->throwError("ssh2-error-downloading-from: " . $this->last_error);
					}
				break;
			case 's3':
					try{
						$this->link->getObject($this->bucket, $remote, $local);
						$ok = true;
					}catch(S3Exception $e) {
						return $this->throwError($e->getMessage());
					}
				break;
		}
		restore_error_handler();

		return $ok;
	}

	/**
	 * Rename files on remote (overwrites)
	 * @param  string $remote_source remote file origin
	 * @param  string $remote_dest   remote file destination
	 * @param  boolean $auto_create_dirs if true tries to autocreates the directory structure on remote
	 *                                   on S3 has no effect as AWS has no concept of "directory" (always true)
	 * @return boolean        returns true if success, false otherwise
	 */
	function rename($remote_source, $remote_dest, $auto_create_dirs = true, $auto_delete_dirs = true) {
		if(!$this->connect()) return $this->last_error;
		$remote_source     = $this->get_path($remote_source);
		$remote_dest       = $this->get_path($remote_dest);
		if($remote_source == $remote_dest) return false;
		$this->last_remote = $remote_source;

		$ok = false;
		set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);

		switch($this->type) {
			case 'file':
					if($auto_create_dirs) $this->mkdir_recursive(dirname($remote_dest));
					if(rename($remote_source, $remote_dest)) {
						$ok = true;
						if($auto_delete_dirs) $this->delete_empty_dir(dirname($remote_source));
					}
					else return $this->throwError("file-error-renaming-to: " . $this->last_error);
				break;

			case 'ftp':
					if($auto_create_dirs) $this->mkdir_recursive(dirname($remote_dest));
					if(ftp_rename($this->link, $remote_source, $remote_dest)) {
						$ok = true;
						if($auto_delete_dirs) $this->delete_empty_dir(dirname($remote_source));
					}
					else return $this->throwError("ftp-error-renaming-to: " . $this->last_error);
				break;

			case 'ssh':
					if($auto_create_dirs) $this->mkdir_recursive(dirname($remote_dest));
					if($this->libsec) {
						if($this->link->rename($remote_source, $remote_dest)) {
							$ok = true;
							if($auto_delete_dirs) $this->delete_empty_dir(dirname($remote_source));
						}
						if(!$ok) return $this->throwError("ssh2-error-renaming-to: " . $this->last_error);
					}
					else {
						if($sftp = ssh2_sftp($this->link)) {
							if(ssh2_sftp_rename($sftp, $remote_source, $remote_dest)) {
								$ok = true;
								if($auto_delete_dirs) $this->delete_empty_dir(dirname($remote_source));
							}
						}
						if(!$ok) return $this->throwError("ssh2-error-renaming-to: " . $this->last_error);
					}
				break;

			case 's3':
					try{
						$this->link->copyObject($this->bucket, $remote_source, $this->bucket, $remote_dest, S3::ACL_PUBLIC_READ);
						$this->link->deleteObject($this->bucket, $remote_source);
						$ok = true;
					} catch(S3Exception $e) {
						return $this->throwError("s3-error-renaming-to: " . $e->getMessage());
					}
				break;
		}

		restore_error_handler();

		if($ok) $this->last_remote = $remote_dest;
		return $ok;
	}

	/**
	 * retrieves filesize from remote
	 * @param  string  $remote remote file to check file size
	 * @param  boolean $force  if it is true, then will try to download the file from ftp if ftp_size fails
	 * @return int         		returns -1 on error, file size otherwise
	 */
	function size($remote_original, $force=false) {
		if(!$this->connect()) return $this->last_error;
		$remote = $this->get_path($remote_original);
		$this->last_remote = $remote;
		$size = -1;
		set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);

		switch($this->type) {
			case 'file':
					if( !($size = filesize($remote)) ) $size = -1;
				break;

			case 'ftp':
					$dir = dirname($remote);
					$odir = '';
					if($dir != '.') {
						$odir = ftp_pwd($this->link);
						ftp_chdir($this->link, $dir);
					}
					$size = ftp_size($this->link, basename($remote));
					if($odir) ftp_chdir($this->link, $odir);
					if($size == -1 && $force) {
						//try to download the file and check the filesize
						$tmp = tempnam(sys_get_temp_dir(), 'file');
						if($this->download($remote_original, $tmp)) {
							if(is_file($tmp)) {
								$size = filesize($tmp);
								unlink($tmp);
							}
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
							return $this->throwError($stderr);
						}
					}
				break;
			case 's3':
					try {
						$info = $this->link->getObjectInfo($this->bucket, $remote);
						$size = (int) $info['size'];
					}catch(S3Exception $e) {
						return $this->throwError($e->getMessage());
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
		if(error_reporting() === 0) return;
		$this->last_error = "[$errno line $errline] $errstr";
		//echo "\n\n".$this->error."\n\n";
		return true;
	}

	protected function throwError($msg) {
		$this->last_error = "$msg";
		if($this->quiet_mode === false) throw new Exception($msg);
		elseif($this->quiet_mode === 2) return $msg;
		return false;
	}
}
?>
