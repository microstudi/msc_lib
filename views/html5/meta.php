<?php
/**
* @file views/html5/meta.php
* @author Ivan Vergés
* @brief \<meta> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("meta",array('id'=>"meta1",'charset'=>"charset_set"));\n
* //or\n
* echo m_view("meta","charset_set");
* </code>
*
* @param charset charset_set
*/

if(is_array($vars)) $charset = $vars['charset'];
else {
	$charset = $vars;
	$vars = array();
}

echo '<meta';

require("_common_html5_attributes.php");

echo ($charset ? ' charset="' . htmlspecialchars($charset) . '"' : '');
echo ($vars['content'] ? ' content="' . htmlspecialchars($vars['content']) . '"' : '');
echo (in_array($vars['http-equiv'],array("content-type", "default-style", "refresh")) ? ' http-equiv="' . $vars['http-equiv'] . '"' : '');
echo (in_array($vars['name'],array("application-name", "author", "description", "generator", "keywords")) ? ' name="' . $vars['name'] . '"' : '');

echo '>';
