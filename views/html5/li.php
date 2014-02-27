<?php
/**
* @file views/html5/li.php
* @author Ivan Vergés
* @brief \<li> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("li",array('id'=>"item1",'class'=>"my_class",'body'=>"Item 1 description"));\n
* //or\n
* echo m_view("li","Item 1 description");
* </code>
*
* @param body html content inside \<li>...\</li>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<li';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo (isset($vars['value']) ? ' value="'.intval($vars['value']).'"' : '');

echo '>';

echo $body;

echo '</li>';

