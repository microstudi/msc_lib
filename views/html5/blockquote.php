<?php
/**
* @file views/html5/blockquote.php
* @author Ivan Vergés
* @brief \<blockquote> tag for the default HTML5 view\n
*
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<blockquote';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

//cite
echo ($vars['cite'] ? ' cite="' . htmlspecialchars($vars['cite']) . '"' : '');

echo '>';

echo $body;

echo '</blockquote>';
?>
