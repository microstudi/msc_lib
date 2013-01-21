<?php
/**
* @file views/html5/iframe.php
* @author Ivan Vergés
* @brief \<iframe> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("iframe",array('id'=>"th1",'class'=>"my_class",'body'=>"IFRAME alternative html content"));\n
* //or\n
* echo m_view("iframe","IFRAME alternative html content");
*
* @param body html content inside \<th>...\</th>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<iframe';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo ($vars['height'] ? ' height="' . htmlspecialchars($vars['height']) . '"' : '');
echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');
echo (in_array($vars['sandbox'], array("allow-forms", "allow-same-origin", "allow-scripts", "allow-top-navigation")) ? ' sandbox="' . $vars['sandbox'] . '"' : '');
echo (empty($vars['seamless']) ? '' : ' seamless="seamless"');
echo ($vars['src'] ? ' src="' . htmlspecialchars($vars['src']) . '"' : '');
echo ($vars['srcdoc'] ? ' srcdoc="' . htmlspecialchars($vars['srcdoc']) . '"' : '');

echo ($vars['width'] ? ' width="' . htmlspecialchars($vars['width']) . '"' : '');

echo '>';

echo $body;

echo '</iframe>';


?>
