<?php
/**
* @file views/html5/colgroup.php
* @author Ivan Vergés
* @brief \<colgroup> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("colgroup",array('id'=>"th1",'class'=>"my_class",'body'=>"colgroup alternative html content"));\n
* //or\n
* echo m_view("colgroup","colgroup alternative html content");
* </code>
*
* @param body html content inside \<colgroup>...\</colgroup>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<colgroup';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo ($vars['span'] ? ' span="' . intval($vars['span']) . '"' : '');

echo '>';

echo $body;

echo '</colgroup>';
