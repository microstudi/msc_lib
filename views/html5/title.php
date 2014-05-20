<?php
/**
* @file views/html5/title.php
* @author Ivan Vergés
* @brief \<title> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("title",array('id'=>"title1",'class'=>"my_class",'body'=>"TITLE html content"));\n
* //or\n
* echo m_view("title","TITLE html content");
* </code>
* @param body html content inside \<title>...\</title>
*/


if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<title';

require('_common_html5_attributes.php');

echo '>';

echo $body;

echo '</title>';
