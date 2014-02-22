<?php
/**
* @file views/html5/img.php
* @author Ivan Vergés
* @brief \<img> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("img",array('id'=>"img1",'class'=>"my_class",'src'=>"URL Image",'alt'=>"Alt text"));\n
* //or\n
* echo m_view("img","URL image");
* </code>
*
* @param src html label src (URL image)
*/

if(is_array($vars)) $src = $vars['src'];
else {
	$src = $vars;
	$vars = array();
}

echo '<img';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo ($src ? ' src="' . htmlspecialchars($src) . '"' : '');
echo ($vars['alt'] ? ' alt="' . htmlspecialchars($vars['alt']) . '"' : '');
echo ($vars['width'] ? ' width="' . htmlspecialchars($vars['width']) . '"' : '');
echo ($vars['height'] ? ' height="' . htmlspecialchars($vars['height']) . '"' : '');
echo (empty($vars['ismap']) ? '' : ' ismap="ismap"');
echo ($vars['usemap'] ? ' usemap="' . htmlspecialchars($vars['usemap']) . '"' : '');

echo '>';
