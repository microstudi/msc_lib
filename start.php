<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Simple View-based Library
 * This library provides simple methods to build PHP websites based in views.
 * Also integrates some other 3party applications like PHPMailer, JSCompressor, CSSCompressor & JavaScriptPacker, PHPSecLib
 *
 * @author Ivan Vergés
 */

/**
* Main include file
* Include this file to use the MSCLIB
*
* Example:
* <code>
* include_once("msc_lib/start.php");
* </code>
*
*/

$d = dirname(__FILE__);
///Include global vars & definitions
include_once($d . "/config.php");

/// Include libs
foreach($CONFIG->inc_classes as $mod) {
	include_once($d . "/classes/$mod.php");

}
foreach($CONFIG->inc_functions as $mod) {
	include_once($d . "/functions/$mod.php");
}

