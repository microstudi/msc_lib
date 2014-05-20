<?php
/**
* @file views/html5/blockquote.php
* @brief \<blockquote> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("blockquote",array('id'=>"div1",'class'=>"my_class",'body'=>"B html content"));\n
* //or\n
* echo m_view("blockquote","B html content");
* </code>
*
* @author Ivan Vergés
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<blockquote';

require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

//cite
echo ($vars['cite'] ? ' cite="' . htmlspecialchars($vars['cite']) . '"' : '');

echo '>';

echo $body;

echo '</blockquote>';