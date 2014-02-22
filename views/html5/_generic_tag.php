<?php
/**
 * @file views/html5/_generic_tag.php
 * @brief \<{ANY}> tag for the default XHTML view
 *
 * @author Ivan Vergés
 */

if(empty($tag)) $tag = 'div';
if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo "<$tag";

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo '>';

echo $body;

echo "</$tag>";
