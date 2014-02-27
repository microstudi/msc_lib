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
 * This class is used by the file functions/sql.php
 *
 *
 * Example:
 * <code>
 * $db = new mMySQL('host','name','user','pass');
 * $db->open();
 * $db->query("SELECT * FROM users");
 * </code>
 *
 */
abstract class mSQL {
	private $host = null;
	private $name = null;
	private $user = null;
	private $pass = null;
	private $conn = null;
	private $utf8 = true;
	public $token = '';

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
	}

	/**
	 * open a connection if is not opened
	 */
	public function open (){
	}

	/**
	 * Closes the connection
	 */
	public function close (){
	}

	/**
	 */
	public function query ($sql, $mode=''){
	}

	/**
	 * Escapes SQL strings
	 */
	public function escape($val) {
		return str_replace("\\","\\\\", str_replace("'","\'", stripslashes($val)));
	}

	/**
	 * Return last error
	 */
	public function getError() {
	}

}

