<?php
/**
* @file views/xhtml/style.php
* @author Ivan Vergés
* @brief \<style> tag for the default XHTML view\n
*
* @section usage Example:
* <code>
* echo m_view("style",array('body'=>"Style content",'media'=>"media query",'scoped'=>true,'type'=>"MIME type"));\n
* //or\n
* echo m_view("style","Style content");
* </code>
*
* @param media async property (true|false)
* @param scope defer property (true|false)
* @param type Mime type (text/javascript)
* @param body html content inside \<style>...\</style>
*/
if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<style';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

//Media
echo ($vars['media'] ? ' media="'.$vars['media'].'"' : '');
//scoped
echo (empty($vars['scoped']) ? '' : ' scoped="scoped"');
//type
echo ($vars['type'] ? ' type="'.$vars['type'].'"' : '');

echo '>';

echo $body;

echo '</style>';
