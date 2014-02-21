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

