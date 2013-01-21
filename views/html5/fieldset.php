<?php
/**
* @file views/html5/fieldset.php
* @author Ivan Vergés
* @brief \<fieldset> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("fieldset",array('id'=>"fieldset1",'class'=>"my_class",'fieldset'=>"fieldset html content"));\n
* //or\n
* echo m_view("fieldset","fieldset html content");
*
* @param body html content inside \<fieldset>...\</fieldset>
*/
if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<fieldset';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo (empty($vars['disabled']) ? '' : ' disabled="disabled"');
echo ($vars['form'] ? ' form="' . htmlspecialchars($vars['form']) . '"' : '');
echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');


echo '>';

echo $body;

echo '</fieldset>';
?>
