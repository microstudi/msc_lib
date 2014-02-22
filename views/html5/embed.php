<?php
/**
* @file views/html5/embed.php
* @author Ivan Vergés
* @brief \<embed> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("embed",array('id'=>"img1",'class'=>"my_class",'src'=>"src URL"));\n
* //or\n
* echo m_view("embed","src URL");
* </code>
*
* @param src html label src (src URL)
*/

if(is_array($vars)) $src = $vars['src'];
else {
	$src = $vars;
	$vars = array();
}

echo '<embed';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo ($vars['height'] ? ' height="' . htmlspecialchars($vars['height']) . '"' : '');
echo ($src ? ' src="' . htmlspecialchars($src) . '"' : '');
echo ($vars['type'] ? ' type="' . htmlspecialchars($vars['type']) . '"' : '');
echo ($vars['width'] ? ' width="' . htmlspecialchars($vars['width']) . '"' : '');

echo '>';
