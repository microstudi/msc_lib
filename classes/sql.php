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

class mSQL {

	/**
	 * open a connection if is not opened
	 */
	function open (){
	}

	/**
	 * Closes the connection
	 */
	function close (){
	}
	/**
	 */
	function query ($sql,$mode=''){
	}

	/**
	 * Escapes SQL strings
	 */
	function escape($val) {
		return str_replace("\\","\\\\",str_replace("'","\'",stripslashes($val)));
	}

	/**
	 * Return last error
	 */
	function getError() {
	}

}

?>
