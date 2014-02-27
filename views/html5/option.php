<?php
/**
* @file views/html5/option.php
* @author Ivan Vergés
* @brief \<option> tag for the default HTML5 view\n
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

if(is_array($vars)) {
	$body = $vars['value'];
	if($vars['text'])  $body = $vars['text'];
	if($vars['body'])  $body = $vars['body'];
}
else {
	$body = $vars;
	$vars = array();
}

echo '<option';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo (isset($vars['value']) ? ' value="' . $vars['value'] . '"' : '');

echo ($vars['disabled'] ? ' disabled="disabled' : '');

echo ($vars['selected'] ? ' selected="selected"' : '');

echo ($vars['label'] ? ' label="' . $vars['label'] . '"' : '');

echo '>';

echo $body;

echo '</option>';

