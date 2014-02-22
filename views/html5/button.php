<?php
/**
* @file views/html5/button.php
* @author Ivan Vergés
* @brief \<button> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("button",array('id'=>"button1",'class'=>"my_class",'button'=>"BUTTON html content"));\n
* //or\n
* echo m_view("button","BUTTON html content");
* </code>
*
* @param body html content inside \<button>...\</button>
*/
if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<button';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo (empty($vars['autofocus']) ? '' : ' autofocus="autofocus"');
echo (empty($vars['disabled']) ? '' : ' disabled="disabled"');
echo ($vars['form'] ? ' form="' . htmlspecialchars($vars['form']) . '"' : '');
echo ($vars['formaction'] ? ' formaction="' . htmlspecialchars($vars['formaction']) . '"' : '');
echo (in_array($vars['formenctype'], array("application/x-www-form-urlencoded", "multipart/form-data", "text/plain")) ? ' formenctype="' . $vars['formenctype'] . '"' : '');
echo (in_array($vars['formmethod'], array("get", "post")) ? ' formmethod="' . $vars['formmethod'] . '"' : '');
echo (empty($vars['formnovalidate']) ? '' : ' formnovalidate="formnovalidate"');
echo ($vars['formtarget'] ? ' formtarget="' . htmlspecialchars($vars['formtarget']) . '"' : '');
echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');
echo (in_array($vars['type'], array("button", "reset", "submit")) ? ' type="' . $vars['type'] . '"' : '');
echo ($vars['value'] ? ' value="' . htmlspecialchars($vars['value']) . '"' : '');

echo '>';

echo $body;

echo '</button>';
