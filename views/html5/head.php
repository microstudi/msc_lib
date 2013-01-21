<?php
/**
* @file views/html5/head.php
* @author Ivan Vergés
* @brief \<head> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("head",array('title'=>"Html page title",'body'=>"head html part, css definitions, javascript..."));\n
*
* @param title html page title (inside \<title>...\</title>)
* @param body html head content after title, (inside \<head>...\</head>)
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<head';

require("_common_html5_attributes.php");

echo '>';

if($vars['tagtitle'] || $vars['body']) {
	echo '<head>
<meta charset="utf-8">
<title>' . $vars['tagtitle'] . '</title>
' . $vars['body'] .'
</head>';
}
else {
	echo $body;
	echo '</head>';
}
?>
