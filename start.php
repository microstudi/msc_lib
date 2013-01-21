<?php
/**
******************************************************
* @mainpage Simple View-based Library
*
* @author Ivan Vergés
* @version 0.5
* @date June 2011
*
* @section intro Introduction
* This library provides simple methods to build PHP websites based in views.\n
* Also integrates some other 3party applications like PHPMailer, JSCompressor, CSSCompressor & JavaScriptPacker, PHPSecLib\n
* \n
* This framework mainly uses global functions, all prefixed by \b m_*, a list of all of them here: <a href=globals_func.html>Global functions</a>\n
* \n
* Check the <a href=../examples/index.php>examples files</a>\n
*
* @section usage Usage
* Start using this library by including start.php:\n
* include_once("msc_lib/start.php");
*
*
*
* @section license_sec License
* http://www.gnu.org/copyleft/gpl.html GNU General Public License
*
*******************************************************/

/**
* @file start.php
* @author Ivan Vergés
* @brief Main include file
* Include this file to use the MSCLIB\n
* \n
* EG: include_once("msc_lib/start.php");
*
*/

///Include global vars & definitions
include_once(dirname(__FILE__)."/config/config.php");

/// Include config tools
include_once(dirname(__FILE__)."/config/settings.php");
include_once(dirname(__FILE__)."/config/db_conn.php");

/// Include libs
foreach($CONFIG->inc_classes as $mod) {
	include_once(dirname(__FILE__)."/classes/$mod.php");

}
foreach($CONFIG->inc_functions as $mod) {
	include_once(dirname(__FILE__)."/functions/$mod.php");
}

?>
