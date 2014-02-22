<?php
/**
* @file views/html5/map.php
* @author Ivan Vergés
* @brief \<map> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("map",array('id'=>"map1",'class'=>"my_class",'map'=>"map html content"));\n
* //or\n
* echo m_view("map","map html content");
* </code>
*
* @param body html content inside \<map>...\</map>
*/
if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<map';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");


echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');


echo '>';

echo $body;

echo '</map>';
