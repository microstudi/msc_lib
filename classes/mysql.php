<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * @category MSCLIB
 * @package SQL
 * @author Ivan Vergés
 */

/**
 * MySQL connection class
 *
 * This file is used to connect to mysql databases
 *
 * Example:
 * <code>
 * $db = new mMySQL('host','name','user','pass');
 * $db->open();
 * $db->query("SELECT * FROM users");
 * </code>
 *
 */
class mMySQL extends mSQL {
	private $host = null;
	private $name = null;
	private $user = null;
	private $pass = null;
	private $conn = null;
	private $utf8 = true;
	private $driver = 'mysqli';
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
	public function __construct ($host = null, $name = null, $user = null, $pass = null, $utf8=true){
		$this->host = $host;
		$this->name = $name;
		$this->user = $user;
		$this->pass = $pass;
		$this->utf8 = $utf8;
		$this->token = "$host-$name";

		if(!function_exists('mysqli_connect'))  $this->driver = 'mysql';
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
			set_error_handler(array($this,'error_handler'),E_ALL & ~E_NOTICE & ~E_STRICT);

			$this->utf8_set = false;
		    if($this->driver == 'mysqli') {
		        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->name);
		    }
		    else {
		        $this->conn = mysql_connect($this->host, $this->user, $this->pass, true);
				if(!mysql_select_db ($this->name, $this->conn)) {
					$this->throwError('MySQL Connection Database Error: ' . mysql_error(), true);
				}
		    }

			if($this->conn) {
				return $this->conn;
			}
			else {
				$this->throwError('MySQL Connection Database Error: ' . ($this->driver == 'mysqli' ? $this->conn->error : mysql_error()), true);
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
			if($this->driver == 'mysqli') $this->conn->close();
			else mysql_close($this->conn);
			$this->conn = null;
			$this->utf8_set = false;
		}
		else {
			$this->throwError('Error: No connection has been established to the database. Cannot close connection.');
		}
	}
	/**
	 * return a mysql query
	 * @param $sql SQL query
	 * @param $mode if empty, returns the result of mysql_query function (or false if fail)
	 *     <b>insert</b> insert mode: return the new id<br>
	 *     <b>update</b> update mode: returns the number of results if it the operation success, no matter if it's really updated or not<br>
	 *     <b>affected</b> affected mode: returns the number of affected rows (not updated rows returns anything)<br>
	 *     <b>deleted</b> delete mode: returns the number of deleted rows (for DELETE querys)
	 */
	function query ($sql, $mode=''){

		if($this->conn) {
			if($this->utf8) $this->setUTF8();
			if(is_array($sql))  $_sql = $sql;
			else $_sql = array($sql);

			if(count($_sql) == 0) $ret = false;
			else $ret = true;

			foreach($_sql as $i => $sql) {

				if($this->driver == 'mysqli') $res = $this->conn->query($sql);
				else $res = mysql_query($sql, $this->conn);

				if($res) {
					$this->last_error = '';

					//insert mode, return the new id
					if($mode == 'insert') {
						if($this->driver == 'mysqli') $ret = $this->conn->insert_id;
						else $ret = mysql_insert_id($this->conn);
					}

					//this mode returns all fi it the the update goes right, no matter if it's really updated or not
					elseif($mode == 'update') {
						if($this->driver == 'mysqli') {
							$info_str = $this->conn->info;
							$a_rows = $this->conn->affected_rows;
						}
						else {
							$info_str = mysql_info($this->conn);
							$a_rows = mysql_affected_rows($this->conn);
						}
						preg_match('/Rows matched: ([0-9]*)/', $info_str, $r_matched);
						//print_r($info_str);echo "<br>\n";print_r($r_matched);echo "<br>\n";die("return: ".(($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows));
						$ret = ($a_rows < 1) ? ($r_matched[1] ? $r_matched[1] : 0) : $a_rows;
					}
					//this mode return the number of affected rows
					elseif($mode == 'affected' || $mode == 'delete') {
						if($this->driver == 'mysqli') $ret = $this->conn->affected_rows;
						else $ret = mysql_affected_rows($this->conn);
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
		if($this->utf8_set) return true;
		if($this->conn) {
			if($this->driver == 'mysqli') $res = $this->conn->set_charset('utf8');
			else $res = mysql_query('SET NAMES UTF8', $this->conn);
			if($res) {
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
	 * @param $issql if true, <b>$t</b> is a SQL text query, if false <b>$t</b> is a mysql_query() result
	 * @param $mode return mode: <b>class</b> returns result as object of this class, \b array returns result as array
	 */
	function fetch($t, $issql=true, $class='stdClass'){
		if($issql) {
			if(!($t = $this->query($t))) return false;
		}

		if(($this->driver == 'mysqli' && is_object($t)) || is_resource($t)) {
			if($class) {
				if($this->driver == 'mysqli') return $t->fetch_object($class);
				else return mysql_fetch_object($t, $class);
			}
			else {
				if($this->driver == 'mysqli') return $t->fetch_assoc();
				else return mysql_fetch_assoc($t);
			}
		}
		return false;
	}
	/**
	 * Escapes SQL strings
	 */
	function escape($val) {
		if($this instanceOf mMySQL && $this->conn) {
			if($this->driver == 'mysqli') return $this->conn->escape_string($val);
			else return mysql_real_escape_string($val, $this->conn);
		}
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
			if($this->driver == 'mysqli') {
				if($this->conn) $msg = $this->conn->error;
			}
			else {
				if($this->conn) $msg = mysql_error($this->conn);
				else $msg = mysql_error();
			}
		}
		$this->last_error = $msg;
		if($die) {
			throw new Exception($msg);
		}
		//die($msg);
	}

}

