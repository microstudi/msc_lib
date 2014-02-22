<?php
/**
* @file views/html5/base.php
* @author Ivan Vergés
* @brief \<base> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("base",array('id'=>"base1",'class'=>"my_class",'href'=>"base href content"));\n
* //or\n
* echo m_view("base","base href content");
* </code>
*
* @param href html content inside \<base href="...">
*/
if(is_array($vars)) $href = $vars['body'];
else {
	$href = $vars;
	$vars = array();
}

echo '<base';


//href
echo ($href ? ' href="' . htmlspecialchars($href) . '"' : '');

echo ($vars['taget'] ? ' target="' . htmlspecialchars($vars['target']) . '"' : '');

echo '>';
