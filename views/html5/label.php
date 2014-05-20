<?php
/**
* @file views/html5/label.php
* @author Ivan Vergés
* @brief \<label> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("label",array('id'=>"img1",'class'=>"my_class",'src'=>"label content"));\n
* //or\n
* echo m_view("label","label content");
* </code>
*
* @param for for input id
*/

if(is_array($vars)) {
	$body = $vars['label'];
	if(empty($body)) $body = $vars['body'];
}
else {
	$body = $vars;
	$vars = array();
}

echo '<label';

require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

echo ($vars['for'] ? ' for="' . htmlspecialchars($vars['for']) . '"' : '');
echo ($vars['form'] ? ' form="' . htmlspecialchars($vars['form']) . '"' : '');

echo '>' . $body .'</label>';
