<?php
/**
* @file views/xhtml/form.php
* @author Ivan Vergés
* @brief \<form> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("form",array('id'=>"form1",'class'=>"my_class",'body'=>"Form html content"));\n
* //or\n
* echo m_view("form","Form html content");
* </code>
*
* @param action html label action
* @param method html label method (post, get)
* @param enctype html label enctype (application/x-www-form-urlencoded, multipart/form-data)
* @param body html content inside \<form>...\</form>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<form';


require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

echo ($vars['accept-charset'] ? ' accept-charset="' . htmlspecialchars($vars['accept-charset']) . '"' : '');

echo ($vars['action'] ? ' action="' . htmlspecialchars($vars['action']) . '"' : '');

echo (in_array($vars['autocomplete'], array('on', 'off')) ? ' autocomplete="' . $vars['autocomplete'] . '"' : '');

echo (in_array($vars['enctype'], array('application/x-www-form-urlencoded', 'multipart/form-data', 'text/plain')) ? ' enctype="' . $vars['enctype'] . '"' : '');
echo (in_array($vars['method'], array('get', 'post')) ? ' method="' . $vars['method'] . '"' : '');

echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');
echo (empty($vars['novalidate']) ? '' : ' novalidate="novalidate"');
echo ($vars['target'] ? ' target="' . htmlspecialchars($vars['target']) . '"' : '');

echo '>';

echo $body;

echo '</form>';
