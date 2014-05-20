<?php
/**
* @file views/xhtml/td.php
* @author Ivan Vergés
* @brief \<td> tag for the default XHTML view\n
*
* @section usage Example:
* <code>
* echo m_view("td",array('id'=>"td1",'class'=>"my_class",'body'=>"TD html content"));\n
* //or\n
* echo m_view("td","TD html content");
* </code>
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<td>...\</td>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<td';

require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

echo ($vars['colspan'] ? ' colspan="' . intval($vars['colspan']) . '"' : '');
echo ($vars['rowspan'] ? ' rowspan="' . intval($vars['rowspan']) . '"' : '');
echo ($vars['headers'] ? ' headers="' . htmlspecialchars($vars['headers']) . '"' : '');

echo '>';

echo $body;

echo '</td>';
