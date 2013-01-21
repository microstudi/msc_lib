<?php
/**
* @file views/html5/area.php
* @author Ivan Vergés
* @brief \<area> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("area",array('id'=>"area1",'class'=>"my_class",'body'=>"area content",'href'=>"http://URL"));
*
* @param rel html label rev
* @param body html content inside \<area>...\</area>
*/
if(is_array($vars)) {
	$body = $vars['body'];
	$href = $vars['href'];
}
else {
	$body = $vars;
	$href = $vars;
	$vars = array();
}

echo '<area';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

//alt
echo ($vars['alt'] ? ' alt="' . htmlspecialchars($vars['alt']) . '"' : '');

//coords
echo ($vars['coords'] ? ' coords="' . htmlspecialchars($vars['coords']) . '"' : '');

//href
echo ($href ? ' href="' . htmlspecialchars($href) . '"' : '');
//hreflang
echo ($vars['hreflang'] ? ' hreflang="' . htmlspecialchars($vars['hreflang']) . '"' : '');

//media
echo ($vars['media'] ? ' media="' . htmlspecialchars($vars['media']) . '"' : '');

//rel
echo (in_array($vars['rel'],array("alternate", "author", "bookmark", "help", "license", "next", "nofollow", "noreferrer", "prefetch", "prev", "search", "tag")) ? ' rel="' . $vars['rel'] . '"' : '');

//shape
echo (in_array($vars['shape'],array("default", "rect", "circle", "poly")) ? ' shape="' . $vars['shape'] . '"' : '');

//target
echo ($vars['target'] ? ' target="' . htmlspecialchars($vars['target']) . '"' : '');

//type
echo ($vars['type'] ? ' type="' . htmlspecialchars($vars['type']) . '"' : '');

echo '>';

echo $body;

echo '</area>';
?>
