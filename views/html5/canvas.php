<?php
/**
* @file views/html5/canvas.php
* @author Ivan Vergés
* @brief \<canvas> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("canvas",array('id'=>"th1",'class'=>"my_class",'body'=>"VIDEO alternative html content"));\n
* //or\n
* echo m_view("canvas","VIDEO alternative html content");
* </code>
*
* @param body html content inside \<th>...\</th>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<canvas';

require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

echo ($vars['height'] ? ' height="' . htmlspecialchars($vars['height']) . '"' : '');
echo ($vars['width'] ? ' width="' . htmlspecialchars($vars['width']) . '"' : '');

echo '>';

echo $body;

echo '</canvas>';
