<?php
/**
* @file classes/mysql.php
* @author Ivan Vergés
* @brief MySQL connection class\n
* This file is used to connect to mysql databases\n
* This class is used by the file functions/sql.php
*
* @section usage Usage
* $db = new mMySQL('host','name','user','pass');\n
* $db->open();\n
* $db->query("SELECT * FROM users");\n
*
*/

class mMySQL extends mSQL {
	private $host = null;
	private $name = null;
	private $user = null;
	private $pass = null;
	private $conn = null;
	private $utf8 = true;
	private $utf8_set = false;
	private $last_error = '';
	private $sql_results = array(); //all results

	/**
	 * Constructor, pass the connection data to the class
	 *
	 * @param $host Host of MySQL server
	 * @param $name Name of the database to connect
	 * @param $user MySQL username
	 * @param $pass MySQL password
	 * @param $utf8 retrieves data from MySQL as UTF-8 (ie: sends the command SET NAMES UTF8 before every query)
	 *
	 */
	function __construct ($host = null, $name = null, $user = null, $pass = null, $utf8=true){
		$this->host = $host;
		$this->name = $name;
		$this->user = $user;
		$this->pass = $pass;
		$this->utf8 = $utf8;
	}

	/**
	 * Compare to instances
	 */
	function is_same($host = '', $name = '', $user = '', $pass = '') {
		if($this->host === $host && $this->name === $name && $this->user === $user && $this->pass === $pass) {
			return true;
		}
		return false;
	}
	/**
	 * Returns if a connexion is open
	 * */
	function is_open() {
		return is_resource($this->conn);
	}
	/**
	 * open a connection if is not opened
	 */
	function open (){
		if($this->is_open()) return $this->conn;
		try {
			set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE);
			if($this->conn = @mysql_connect ($this->host, $this->user, $this->pass, true)) {
				if(mysql_select_db ($this->name, $this->conn)) {
					return $this->conn;
				}
				else {
					$this->throwError('MySQL Connection Database Error: ' . mysql_error(), true);
				}
			}
			else {
				$this->throwError('MySQL Connection Database Error: ' . mysql_error(), true);
			}
			restore_error_handler();
			return $this->conn;
		}
		catch(Exception $e) {
			$this->throwError($e->getMessage(), true);
		}
	}

	/**
	 * Closes the connection
	 */
	function close (){
		if($this->conn){
			mysql_close($this->conn);
			$this->conn = null;
		}
		else {
			$this->throwError("Error: No connection has been established to the database. Cannot close connection.");
		}
	}
	/**
	 * return a mysql query
	 * @param $sql SQL query
	 * @param $mode if empty, returns the result of mysql_query function (or false if fail)
	 * 	- @b insert insert mode: return the new id
	 * 	- @b update update mode: returns the number of results if it the operation success, no matter if it's really updated or not
	 * 	- @b affected affected mode: returns the number of affected rows (not updated rows returns anything)
	 * 	- @b deleted delete mode: returns the number of deleted rows (for DELETE querys)
	 */
	function query ($sql, $mode=''){

		if($this->conn) {
			if($this->utf8 && !$this->utf8_set) $this->setUTF8();
			if(is_array($sql))  $_sql = $sql;
			else $_sql = array($sql);

			if(count($_sql) == 0) $ret = false;
			else $ret = true;

			foreach($_sql as $i => $sql) {
				if($res = mysql_query($sql, $this->conn)) {
					$this->last_error = '';

					//insert mode, return the new id
					if($mode == 'insert') {
						$ret = mysql_insert_id($this->conn);
					}

					//this mode returns all fi it the the update goes right, no matter if it's really updated or not
					elseif($mode == 'update') {
						$info_str = mysql_info($this->conn);
						$a_rows = mysql_affected_rows($this->conn);
						preg_match("/Rows matched: ([0-9]*)/", $info_str, $r_matched);
						//print_r($info_str);echo "<br>\n";print_r($r_matched);echo "<br>\n";die("return: ".(($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows));
						$ret = ($a_rows < 1) ? ($r_matched[1] ? $r_matched[1] : 0) : $a_rows;
					}
					//this mode return the number of affected rows
					elseif($mode == 'affected' || $mode == 'delete') {
						$ret = mysql_affected_rows($this->conn);
					}
					else $ret = $res;
					$this->sql_results[$i] = $ret;
				}
				else {
					$ret = false;
					$this->throwError();
				}
			}
			return $ret;
		}
		else {
			$this->throwError('No connection');
			return false;
		}
	}
	/**
	 * Returns the array with all results
	 * @return array all results with the same array of input sql
	 */
	function results() {
		return $this->sql_results;
	}
	/**
	 * Sends the SET NAMES UTF8 to the MySQL server to establish data as UTF-8
	 * */
	function setUTF8() {
		if($this->conn) {
			if($res = mysql_query("SET NAMES UTF8", $this->conn)) {
				$this->last_error = '';
				$this->utf8_set = true;
				return $res;
			}
			else {
				$this->throwError();
			}
		}
		return false;
	}
	/**
	 * returns a mysql fetch object/array
	 * @param $t SQL query or mysql_query() result resource
	 * @param $issql if true, \b $t is a SQL text query, if false \b $t is a mysql_query() result
	 * @param $mode return mode: \b class returns result as object of this class, \b array returns result as array
	 */
	function fetch($t, $issql=true, $class='stdClass'){
		if($issql) {
			if(!($t = $this->query($t))) return false;
		}

		if(is_resource($t)) {
			if($class) {
				return mysql_fetch_object($t, $class);
			}
			else {
				return mysql_fetch_assoc($t);
			}
		}
		return false;
	}
	/**
	 * Escapes SQL strings
	 */
	function escape($val) {
		if($this instanceOf mMySQL && $this->conn) return mysql_real_escape_string($val, $this->conn);
		return parent::escape($val);
	}

	/**
	 * Return last error
	 */
	function getError() {
		return $this->last_error;
	}

	/**
	 * Handle function errors
	 * */
	public function error_handler($errno, $errstr, $errfile, $errline, $errcontext) {
		if(error_reporting() === 0) return;
		throw new Exception("[$errno line $errline] $errstr");
		//echo "\n\n".$this->error."\n\n";
		return true;
	}

	/**
	 * throw errors
	 */
	function throwError($msg='', $die=false) {
		if(!$msg) {
			if($this->conn) $msg = mysql_error($this->conn);
			else $msg = mysql_error();
		}
		$this->last_error = $msg;
		if($die) {
			throw new Exception($msg);
		}
		//die($msg);
	}

}

