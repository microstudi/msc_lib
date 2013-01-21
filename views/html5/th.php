<?php
/**
* @file views/html5/th.php
* @author Ivan Vergés
* @brief \<th> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("th",array('id'=>"th1",'class'=>"my_class",'body'=>"TH html content"));\n
* //or\n
* echo m_view("th","TH html content");
*
* @param body html content inside \<th>...\</th>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<th';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo ($vars['colspan'] ? ' colspan="' . intval($vars['colspan']) . '"' : '');
echo ($vars['rowspan'] ? ' rowspan="' . intval($vars['rowspan']) . '"' : '');
echo ($vars['headers'] ? ' headers="' . htmlspecialchars($vars['headers']) . '"' : '');
echo (in_array($vars['scope'], array("col", "colgroup", "row", "rowgroup")) ? ' scope="' . $vars['scope'] . '"' : '');

echo '>';

echo $body;

echo '</th>';


?>
