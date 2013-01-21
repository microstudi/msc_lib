<?php
/**
* @file views/html5/html.php
* @author Ivan Vergés
* @brief \<html> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("html",array('title'=>"Html page title",'head'=>"head html part, css definitions, javascript...",'body'=>"Body html content"));\n
*
* @param title html page title (inside \<title>...\</title>)
* @param head html head content after title, (inside \<head>...\</head>)
* @param body html content inside \<body>...\</body>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<!DOCTYPE html>
<html';

require("_common_html5_attributes.php");

echo ($vars['manifest'] ? ' manifest="' . htmlspecialchars($vars['manifest']) . '"' : '');
echo (empty($vars['xmlns']) ? '' : ' xmlns="http://www.w3.org/1999/xhtml"');

echo '>';

if($vars['tagtitle'] || $vars['head'] || $vars['body']) {
	echo '
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>' . $vars['tagtitle'] . '</title>
' . $vars['head'] .'
</head>
<body>
' . $vars['body'] . '
</body>
</html>';
}
else {
	echo $body;
	echo '</html>';
}
?>
