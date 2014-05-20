<?php
/**
* @file views/html5/col.php
* @author Ivan Vergés
* @brief \<col> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("col",array('id'=>"th1",'class'=>"my_class",'body'=>"col alternative html content"));\n
* //or\n
* echo m_view("col","col alternative html content");
* </code>
*
* @param body html content inside \<col>...\</col>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<col';

require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

echo ($vars['span'] ? ' span="' . intval($vars['span']) . '"' : '');

echo '>';

echo $body;

echo '</col>';
